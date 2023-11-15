<?php

    session_start();

    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
        header('Location: ../app/login.php');
        exit();
    }

    include_once "../app/bd.php";

        // Consulta SQL para obtener todas las ventas
        $sql = "SELECT * FROM ventas ORDER BY id_venta DESC";


        // Ejecutar consulta
        $result = $conexion->query($sql);

        $conexion->close();


?>

<!DOCTYPE html>
<html>
<head>
    <title>Ventas</title>
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
    <!-- Formulario para agregar categorías -->
            <h2>Ventas</h2>
            <?php
                if ($result->num_rows > 0) {
                    // Mostrar tabla con resultados
                    echo "<table><tr><th>ORDEN VENTA</th><th>NOMBRE USUARIO</th><th>CORREO USUARIO</th><th>FECHA VENTA</th><th>TOTAL</th><th>ESTADO</th><th>ENTREGA</th><th>PRODUCTOS</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        // Obtener el JSON almacenado en la columna "delivery"
                        $entregaJson = $row["entrega"];
                        
                        // Decodificar el JSON en un array asociativo
                        $entregaData = json_decode($entregaJson, true);

                        // Obtener el JSON almacenado en la columna "productos"
                        $productosJson = $row["productos"];

                        // Decodificar el JSON en un array asociativo
                        $productosData = json_decode($productosJson, true);

                        echo "<tr><td>" . $row["id_venta"] . "</td><td>" . $row["nombre_usuario"] . "</td><td>" . $row["correo_usuario"] . "</td><td>" . $row["fecha_venta"] . "</td><td>" . $row["total"] . "</td><td>" . $row["estado"] . "</td><td>";
                        // Mostrar los datos de entrega como clave-valor uno debajo del otro
                        foreach ($entregaData as $key => $value) {
                            echo "<b>$key:</b> $value<br>";
                        }

                        echo "</td><td>";

                        // Mostrar los datos de productos con características
                        foreach ($productosData as $producto) {
                            echo "<b>Producto:</b> " . $producto["producto"] . "<br>";
                            echo "<b>Características:</b><br>";

                            // Mostrar las características como clave-valor uno debajo del otro
                            foreach ($producto["caracteristicas"] as $caracteristica => $valor) {
                                echo "<b>$caracteristica:</b> $valor<br>";
                            }
                            echo "<b>Cantidad:</b> " . $producto["cantidad"] . "<br>";
                            echo "<br>";
                        }

                        echo "</td></tr>";
                    }
                    echo "</table>";
                    } else {
                        echo "<p>No se encontraron ventas.</p>";
                    }
            ?>



        </div>
    </div>
</body>
</html>