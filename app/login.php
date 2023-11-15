<?php
// Incluye el archivo de conexión a la base de datos y las funciones de validación.
require_once 'conexion.php';

session_start();

// Verifica si el usuario ya inició sesión y lo redirecciona a la página principal.
if (isset($_SESSION['id'], $_SESSION['username'], $_SESSION['correo'], $_SESSION['password'], $_SESSION['is_admin'])) {
    header('location: ' . URL_PRINCIPAL);
    exit;
}

// Define e inicializa las variables.
$email = $password = '';
$email_err = $password_err = '';

// Procesa los datos del formulario cuando se envía.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Valida el correo electrónico.
    $email = trim($_POST['email']);
    if (empty($email)) {
        $email_err = 'Por favor ingrese su correo electrónico.';
    }

    // Valida la contraseña.
    $password = trim($_POST['password']);
    if (empty($password)) {
        $password_err = 'Por favor ingrese su contraseña.';
    }

    // Verifica si no hay errores de entrada antes de continuar.
    if (empty($email_err) && empty($password_err)) {

        // Prepara y vincula la declaración SQL.
        $sql = 'SELECT id, username, correo, password, is_admin FROM usuarios WHERE correo = :email';
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        // Ejecuta la consulta.
        $stmt->execute();

        // Vincula los resultados de la consulta a unas variables.
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Obtiene los resultados de la consulta.
        if ($stmt->rowCount() === 1) {
            $hashed_password = $resultado['password'];
            if (password_verify($password, $hashed_password)) {

                // Si la contraseña es correcta, inicia la sesión y almacena los datos del usuario en las variables de sesión.
                session_start();
                $_SESSION['id'] = $resultado['id'];
                $_SESSION['username'] = $resultado['username'];
                $_SESSION['correo'] = $resultado['correo'];
                $_SESSION['password'] = $resultado['password'];
                $_SESSION['is_admin'] = $resultado['is_admin'];

                header('location: ' . URL_PRINCIPAL);
                exit;
            } else {
                // Si la contraseña es incorrecta, muestra un mensaje de error.
                $password_err = 'La contraseña que ingresó no es válida.';
            }
        } else {
            // Si el correo electrónico no existe, muestra un mensaje de error.
            $email_err = 'No se encontró una cuenta con ese correo electrónico.';
        }

        if (!empty($email_err)) {
            $_SESSION['email_err'] = $email_err; 
        }
        
        if (!empty($password_err)) {
           $_SESSION['password_err'] = $password_err;
        }
        
        header('location: iniciar_sesion.php');
        exit;

        // Cierra la sentencia preparada.
        unset($stmt);
    }
}
?>

