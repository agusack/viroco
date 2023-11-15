<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../app/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_subcategoria = $_POST['id_subcategoria'];
    $nombre_subcategoria = $_POST['nombre_subcategoria'];
    $id_categoria = $_POST['id_categoria'];

    // Conexión a la base de datos
    require '../app/bd.php';

    // Comprobamos si la conexión es exitosa
    if (mysqli_connect_errno()) {
        echo "Fallo al conectar a la base de datos: " . mysqli_connect_error();
    }

    // Sentencia SQL para editar la categoría
    $sql = "UPDATE subcategorias SET nombre_subcategoria='$nombre_subcategoria', id_categoria='$id_categoria' WHERE id_subcategoria=$id_subcategoria";

    if (mysqli_query($conexion, $sql)) {
        mysqli_close($conexion);
        header('Location: categorias_admin.php');
        exit();
    } else {
        echo "Error al editar la categoría: " . mysqli_error($conexion);
    }
} else {
    $id_subcategoria = $_GET['id_subcategoria'];

    // Conexión a la base de datos
    require '../app/bd.php';

    // Comprobamos si la conexión es exitosa
    if (mysqli_connect_errno()) {
        echo "Fallo al conectar a la base de datos: " . mysqli_connect_error();
    }

    // Sentencia SQL para obtener la categoría
    $sql = "SELECT * FROM subcategorias WHERE id_subcategoria=$id_subcategoria";

    $resultado = mysqli_query($conexion, $sql);

    if ($fila = mysqli_fetch_array($resultado)) {
        $nombre_subcategoria = $fila['nombre_subcategoria'];
        $id_categoria = $fila['id_categoria'];
    } else {
        echo "Error: categoría no encontrada.";
        exit();
    }

    mysqli_close($conexion);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Categoría</title>
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
            <h1>Editar Categoría</h1>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="hidden" name="id_subcategoria" value="<?php echo $id_subcategoria; ?>">
                <label for="nombre_subcategoria">Nombre de la subcategoría:</label>
                <input type="text" name="nombre_subcategoria" id="nombre_subcategoria" value="<?php echo $nombre_subcategoria; ?>">
                <label for="id_categoria">Categoría global:</label>
                <input type="number" name="id_categoria" id="id_categoria" value="<?php echo $id_categoria; ?>">
                <input type="submit" value="Guardar">
            </form>
        </div>
    </div>
</body>
</html>