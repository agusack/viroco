<?php

define('URL_PRINCIPAL', '/Viroco/index.php');

// Inicia la sesión.
session_start();

// Desactiva todas las variables de sesión.
$_SESSION = array();

// Destruye la sesión.
session_destroy();

// Redirige al usuario a la página de inicio de sesión.
header('location:' . URL_PRINCIPAL);
exit;

?>