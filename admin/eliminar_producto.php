<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../app/login.php');
    exit();
}

require '../app/conexion.php';

$id = $_GET['id'];

// Eliminar características del producto de la tabla combinaciones_producto
$sql = "DELETE FROM combinaciones_producto WHERE id_producto = :id";
$stmt = $conexion->prepare($sql);
$stmt->execute([
    ':id' => $id
]);

// Eliminar producto de la tabla productos
$sql = "DELETE FROM productos WHERE id = :id";
$stmt = $conexion->prepare($sql);
$stmt->execute([
    ':id' => $id
]);

// Eliminar características del producto de la tabla caracteristicas
$sql = "DELETE FROM caracteristicas WHERE id_producto = :id";
$stmt = $conexion->prepare($sql);
$stmt->execute([
    ':id' => $id
]);

header('Location: productos_admin.php');