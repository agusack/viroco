<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../app/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_categoria = $_POST['nombre_categoria'];

    require '../app/bd.php';

    // Comprobamos si la conexión es exitosa
    if (mysqli_connect_errno()) {
        echo "Fallo al conectar a la base de datos: " . mysqli_connect_error();
    }

    // Sentencia SQL para agregar la categoría
    $sql = "INSERT INTO categorias (nombre_categoria) VALUES ('$nombre_categoria')";

    if (mysqli_query($conexion, $sql)) {
        mysqli_close($conexion);
        header('Location: categorias_admin.php');
        exit();
    } else {
        echo "Error al agregar la categoría: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Añadir categoria - Tienda Online</title>
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
    <!-- Formulario para agregar categorías -->
            <h2>Añadir Categoría</h2>
            <form action="añadir_categoria.php" method="POST">
                <label for="nombre_categoria">Nombre de la categoría:</label>
                <input type="text" name="nombre_categoria" id="nombre_categoria">
                <input type="submit" value="Agregar">
            </form>
        </div>
    </div>
</body>
</html>