<?php

    session_start();
    
    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
        header('Location: ../app/login.php');
        exit();
    }

    require '../app/bd.php';

//Consulta para obtener la lista de usuarios
$query = "SELECT * FROM usuarios";

//Ejecutar la consulta
$resultado = mysqli_query($conexion, $query);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="../estilos/estilo_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css">
    <script src="https://kit.fontawesome.com/9ab94acfdc.js" crossorigin="anonymous"></script>
</head>
<body>
    <div id="wrapper">
        <?php
            include('navbar_admin.php')
        ?>
        <div id="main">
            <h1>Lista de Usuarios</h1>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Es Admin</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo $fila['id'] ?></td>
                            <td><?php echo $fila['username'] ?></td>
                            <td><?php echo $fila['is_admin'] ? 'Sí' : 'No' ?></td>
                            <td>
                                <a href="eliminar_usuario.php?id=<?php echo $fila['id'] ?>">Eliminar</a>
                                <a href="asignar_admin.php?id=<?php echo $fila['id'] ?>">Asignar Admin</a>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>