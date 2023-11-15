<?php
// Datos de conexión a la base de datos.
$host = 'localhost';
$dbname = 'viroco';
$username = 'root';
$password = '';

// Intenta conectarse a la base de datos.
try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configura el modo de error para PDO en modo excepción.
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("No se pudo conectar a la base de datos: " . $e->getMessage());
}

// Define una constante para la URL de la página principal.
define('URL_PRINCIPAL', '/Viroco/index.php');
?>