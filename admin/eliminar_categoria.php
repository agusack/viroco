<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../app/login.php');
    exit();
}

$id_categoria = $_GET['id_categoria'];

// Conexión a la base de datos
require '../app/bd.php';

// Comprobamos si la conexión es exitosa
if (mysqli_connect_errno()) {
    echo "Fallo al conectar a la base de datos: " . mysqli_connect_error();
}

// Sentencia SQL para eliminar la categoría
$sql = "DELETE FROM categorias WHERE id_categoria=$id_categoria";

if (mysqli_query($conexion, $sql)) {
    mysqli_close($conexion);
    header('Location: categorias_admin.php');
    exit();
} else {
    echo "Error al eliminar la categoría: " . mysqli_error($conexion);
}