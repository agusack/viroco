<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../app/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_categoria = $_POST['id_categoria'];
    $nombre_categoria = $_POST['nombre_categoria'];

    // Conexión a la base de datos
    require '../app/bd.php';

    // Comprobamos si la conexión es exitosa
    if (mysqli_connect_errno()) {
        echo "Fallo al conectar a la base de datos: " . mysqli_connect_error();
    }

    // Sentencia SQL para editar la categoría
    $sql = "UPDATE categorias SET nombre_categoria='$nombre_categoria' WHERE id_categoria=$id_categoria";

    if (mysqli_query($conexion, $sql)) {
        mysqli_close($conexion);
        header('Location: categorias_admin.php');
        exit();
    } else {
        echo "Error al editar la categoría: " . mysqli_error($conexion);
    }
} else {
    $id_categoria = $_GET['id_categoria'];

    // Conexión a la base de datos
    require '../app/bd.php';

    // Comprobamos si la conexión es exitosa
    if (mysqli_connect_errno()) {
        echo "Fallo al conectar a la base de datos: " . mysqli_connect_error();
    }

    // Sentencia SQL para obtener la categoría
    $sql = "SELECT * FROM categorias WHERE id_categoria=$id_categoria";

    $resultado = mysqli_query($conexion, $sql);

    if ($fila = mysqli_fetch_array($resultado)) {
        $nombre_categoria = $fila['nombre_categoria'];
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
                <input type="hidden" name="id_categoria" value="<?php echo $id_categoria; ?>">
                <label for="nombre_categoria">Nombre de la categoría:</label>
                <input type="text" name="nombre_categoria" id="nombre_categoria" value="<?php echo $nombre_categoria; ?>">
                <input type="submit" value="Guardar">
            </form>
        </div>
    </div>
</body>
</html>