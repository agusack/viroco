<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../app/login.php');
    exit();
}
//Incluir el archivo de conexión a la base de datos
include('../app/bd.php');

//Obtener el ID del usuario a eliminar
$id = $_GET['id'];

//Realizar la consulta para eliminar al usuario
$query = "DELETE FROM usuarios WHERE id = $id";
$resultado = mysqli_query($conexion, $query);

//Redireccionar de vuelta a la página de usuarios
header('Location: usuarios_admin.php');
exit;