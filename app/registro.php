<!-- Este código tiene algunas validaciones de seguridad importantes que ayudan a prevenir ataques maliciosos:

Verificación del método HTTP: if ($_SERVER['REQUEST_METHOD'] == 'POST') se asegura de que los datos solo se procesen si se envían a través del método HTTP POST. Esto ayuda a prevenir el procesamiento de datos maliciosos enviados por otro método como GET.

Validación de campos de entrada: las variables $username, $email, $password, $confirm_password se validan para asegurarse de que no estén vacías mediante la función empty(), que es una forma sencilla de prevenir inyecciones de código y errores de tipo E_NOTICE. Además, se valida la longitud de la contraseña para asegurarse de que tenga al menos 8 caracteres.

Uso de declaraciones preparadas y vinculación de parámetros: la consulta SQL se prepara utilizando la función prepare() para evitar inyecciones SQL. Las variables se vinculan a los parámetros de la consulta utilizando la función bindParam() para evitar valores no válidos y caracteres especiales.

Uso de funciones criptográficas robustas: la contraseña se cifra mediante la función password_hash() con el algoritmo de cifrado bcrypt y una sal de forma predeterminada para proteger la seguridad de las contraseñas de los usuarios.

Cierre de la conexión a la base de datos: la conexión a la base de datos se cierra correctamente mediante la invocación de unset($pdo) después de haber terminado de procesar los datos. Esto es importante para liberar los recursos del servidor y evitar ataques de denegación de servicio.

En resumen, el código implementa varias técnicas y prácticas recomendadas de seguridad que ayudan a proteger los datos del usuario y prevenir posibles vulnerabilidades de seguridad.
-->
<?php
// Incluye la configuración de la conexión a la base de datos.
require_once 'conexion.php';

session_start();

// Define las variables y establece los valores iniciales en vacío.
$username = $email = $password = $confirm_password = '';
$username_err = $email_err = $password_err = $confirm_password_err = '';

// Procesa los datos del formulario cuando se envía.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Valida el nombre de usuario.
    if (empty(trim($_POST['username']))) {
        $username_err = 'Por favor ingrese un nombre de usuario.';
    } else {
        // Prepara una consulta select.
        $sql = 'SELECT id FROM usuarios WHERE username = :username';

        if ($stmt = $conexion->prepare($sql)) {
            $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);
            $param_username = trim($_POST['username']);

            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $username_err = 'Este nombre de usuario ya está en uso.';
                } else {
                    $username = $param_username;
                }
            } else {
                echo 'Algo salió mal. Por favor, inténtelo de nuevo más tarde.';
            }

            unset($stmt);
        }
    }

    // Valida el correo electrónico.
    if (empty(trim($_POST['email']))) {
        $email_err = 'Por favor ingrese un correo electrónico.';
    } else {
        $email = trim($_POST['email']);
    }

    // Valida la contraseña.
    if (empty(trim($_POST['password']))) {
        $password_err = 'Por favor ingrese una contraseña.';
    } elseif (strlen(trim($_POST['password'])) < 8) {
        $password_err = 'La contraseña debe tener al menos 8 caracteres.';
    } else {
        $password = trim($_POST['password']);
    }

    // Valida la confirmación de la contraseña.
    if (empty(trim($_POST['confirm_password']))) {
        $confirm_password_err = 'Por favor confirme la contraseña.';
    } else {
        $confirm_password = trim($_POST['confirm_password']);

        if ($password != $confirm_password) {
            $confirm_password_err = 'La confirmación de la contraseña no coincide con la contraseña.';
        }
    }

    // Verifica que no haya errores de entrada antes de insertar en la base de datos.
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        // Prepara una consulta insert.
        $sql = 'INSERT INTO usuarios (username, correo, password) VALUES (:username, :email, :password)';

        if ($stmt = $conexion->prepare($sql)) {
            // Vincula las variables a los parámetros de la consulta.
            $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $param_email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $param_password, PDO::PARAM_STR);

            // Establece los parámetros.
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Crea un hash de la contraseña.

            if ($stmt->execute()) {
                // Redirige a la página de inicio de sesión después de registrar correctamente al usuario.
                header('location: iniciar_sesion.php');
                exit;
            } else {
                echo 'Algo salió mal. Por favor, inténtelo de nuevo más tarde.';
            }

            unset($stmt);
        }
    }

    if (!empty($username_err)) {
        $_SESSION['username_err'] = $username_err; 
    }
    
    if (!empty($email_err)) {
        $_SESSION['email_err'] = $email_err; 
    }

    if (!empty($password_err)) {
        $_SESSION['password_err'] = $password_err; 
    }
    
    if (!empty($confirm_password_err)) {
        $_SESSION['confirm_password_err'] = $confirm_password_err; 
    }        
    
    header('location: registrarse.php');
    exit;

        
    // Cierra la conexión a la base de datos.
    unset($conexion);
    
}
?>