<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarse</title>
    <link rel="stylesheet" href="../estilos/estilo_login.css">
</head>
<body>
    <section id="page">
        <div class="container">
            <h2 class="title">Registrarse</h2>
            <?php
                $username_err = (isset($_SESSION['username_err'])) ? $_SESSION['username_err'] : '';
                $email_err = (isset($_SESSION['email_err'])) ? $_SESSION['email_err'] : '';  
                $password_err = (isset($_SESSION['password_err'])) ? $_SESSION['password_err'] : '';
                $confirm_password_err = (isset($_SESSION['confirm_password_err'])) ? $_SESSION['confirm_password_err'] : '';

                unset($_SESSION['username_err'], 
                    $_SESSION['email_err'],  
                    $_SESSION['password_err'],
                    $_SESSION['confirm_password_err']);

                if (!empty($username_err)) {
                    echo '<p class="alert alert-danger">' . $username_err . '</p>';
                }

                if (!empty($email_err)) {
                echo '<p class="alert alert-danger">' . $email_err . '</p>'; 
                }

                if (!empty($password_err)) {
                    echo '<p class="alert alert-danger">' . $password_err . '</p>';
                }

                if (!empty($confirm_password_err)) {
                echo '<p class="alert alert-danger">' . $confirm_password_err . '</p>';
                }  
            ?>
            <form action="registro.php" method="post">
                <!-- Campo de nombre de usuario -->
                <div class="form-group">
                    <label class="label">Nombre de usuario</label>
                    <input type="text" name="username" class="input">
                </div>
                <!-- Campo de correo electrónico -->
                <div class="form-group">
                    <label class="label">Correo electrónico</label>
                    <input type="email" name="email" class="input">
                </div>
                <!-- Campo de contraseña -->
                <div class="form-group">
                    <label class="label">Contraseña</label>
                    <input type="password" name="password" class="input">
                </div>
                <!-- Campo de confirmación de contraseña -->
                <div class="form-group">
                    <label class="label">Confirmar contraseña</label>
                    <input type="password" name="confirm_password" class="input">
                </div>
                <!-- Botón de envío -->
                <div class="form-group">
                    <input type="submit" class="button is-primary" value="Registrarse">
                </div>
                <p style="color: #255a66;">¿Ya tiene una cuenta? <a href="iniciar_sesion.php">Inicie sesión aquí.</a></p>
            </form>
        </div>
    </section>
</body>
</html>