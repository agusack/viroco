<?php
session_start();

    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
        header('Location: ../app/login.php');
        exit();
    }

    require '../app/conexion.php';

    // Mostrar lista de productos con nombres de categorías y subcategorías
    $sql = "SELECT productos.*, categorias.nombre_categoria, subcategorias.nombre_subcategoria FROM productos
        INNER JOIN categorias ON productos.id_categoria = categorias.id_categoria
        INNER JOIN subcategorias ON productos.id_subcategoria = subcategorias.id_subcategoria";
    $stmt = $conexion->query($sql);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel de Administración - Tienda Online</title>
    <link rel="stylesheet" href="../estilos/estilo_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css">
    <script src="https://kit.fontawesome.com/9ab94acfdc.js" crossorigin="anonymous"></script>
</head>
<body>
    <div id="wrapper">
        <?php
            include('navbar_admin.php')
        ?>
        <div id="main">
            <h2>Lista de Productos</h2>
            <button><a href='añadir_producto.php' class='agregar-button'>Agregar producto</a></button>
            <table>
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th>Subcategoría</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><img src="<?php echo $producto['imagen'] ?>" alt="" style="height: 50px; width: 50px;" ></td>
                            <td><?php echo $producto['nombre'] ?></td>
                            <td><?php echo $producto['precio'] ?></td>
                            <td><?php echo $producto['nombre_categoria'] ?></td>
                            <td><?php echo $producto['nombre_subcategoria'] ?></td>
                            <td><?php echo $producto['stock'] ?></td>
                            <td>
                                <a href="editar_producto.php?id=<?php echo $producto['id'] ?>">Editar</a>
                                <a href="eliminar_producto.php?id=<?php echo $producto['id'] ?>">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>        
</body>
</html>