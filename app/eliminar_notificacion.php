<?php
session_start();

// Verificar si existe una notificación almacenada en la variable de sesión
if (isset($_SESSION['notificacion'])) {
    // Eliminar la notificación de la variable de sesión
    unset($_SESSION['notificacion']);
}
?>