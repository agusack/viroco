<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../app/login.php');
    exit();
}
//Incluir el archivo de conexión a la base de datos
include('../app/bd.php');

//Obtener el ID del usuario a asignar permisos de administrador
$id = $_GET['id'];

//Realizar la consulta para actualizar el campo "is_admin" en la tabla "usuarios"
$query = "UPDATE usuarios SET is_admin = 1 WHERE id = $id";
$resultado = mysqli_query($conexion, $query);

//Redireccionar de vuelta a la página de usuarios
header('Location: usuarios_admin.php');
exit;