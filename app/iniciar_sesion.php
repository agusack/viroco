<?php 
session_start();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesion</title>
    <link rel="stylesheet" href="../estilos/estilo_login.css">
</head>
<body>
    <section id="page">
        <div class="container">
            <h2 class="title">Iniciar sesión</h2>
            <?php
                $email_err = (isset($_SESSION['email_err'])) ? $_SESSION['email_err'] : '';
                $password_err = (isset($_SESSION['password_err'])) ? $_SESSION['password_err'] : '';

                unset($_SESSION['email_err'], $_SESSION['password_err']);

                if (!empty($email_err)) {
                    echo '<p class="alert alert-danger">' . $email_err . '</p>';
                }

                if (!empty($password_err)) {
                    echo '<p class="alert alert-danger">' . $password_err . '</p>'; 
                }
            ?>
            <form action="login.php" method="post" onsubmit="return validateForm()">
                <div class="form-group">
                    <label class="label" for="email">Correo electrónico:</label>
                    <input class="input" type="email" name="email" id="email" value="">
                </div>
                <div class="form-group">
                    <label class="label" for="password">Contraseña:</label>
                    <input class="input" type="password" name="password" id="password">
                </div>
                <div class="botonera">
                    <button class="button is-primary" type="submit">Iniciar sesión</button>
                    <a href="registrarse.php"><button class="button is-link" type="button">Registrarse</button></a>
                </div>
                <div id="error-message" class="help is-danger" style="display:none;"></div>
            </form>
        </div>
    </section>
    <script>
    function validateForm() {
        // Obtener los valores de los campos
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;

        // Verificar si los campos tienen contenido
        if(email === "" || password === "") {
            var errorMessage = document.getElementById("error-message");
            errorMessage.innerHTML = "Por favor, ingrese su correo electrónico y contraseña";
            errorMessage.style.display = "block";

            return false;
        }

        return true;
    }
    </script>
</body>
</html>