<?php

    session_start();

    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
        header('Location: ../app/login.php');
        exit();
    }

    include_once "../app/bd.php";

        // Consulta SQL para obtener todas las ventas
        $sql = "SELECT * FROM pedidos";

        // Ejecutar consulta
        $result = $conexion->query($sql);

        $conexion->close();


?>

<!DOCTYPE html>
<html>
<head>
    <title>Pedidos</title>
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
            <h2>Pedidos</h2>
            <?php
                if ($result->num_rows > 0) {
                    // Mostrar tabla con resultados
                    echo "<table><tr><th>ORDEN</th><th>NOMBRE USUARIO</th><th>CORREO USUARIO</th><th>FECHA</th><th>TOTAL</th><th>MODO PAGO</th><th>ENTREGA</th><th>PRODUCTOS</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        // Obtener el JSON almacenado en la columna "entrega"
                        $entregaJson = $row["entrega"];
                        
                        // Decodificar el JSON en un array asociativo
                        $entregaData = json_decode($entregaJson, true);

                        // Obtener el JSON almacenado en la columna "productos"
                        $productosJson = $row["productos"];

                        // Decodificar el JSON en un array asociativo
                        $productosData = json_decode($productosJson, true);

                        echo "<tr><td>" . $row["id_pedido"] . "</td><td>" . $row["nombre_usuario"] . "</td><td>" . $row["correo_usuario"] . "</td><td>" . $row["fecha_pedido"] . "</td><td>" . $row["total"] . "</td><td>" . $row["metodo_pago"] . "</td><td>";

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
                        }

                        echo '<button class="aprobado-btn" onclick="marcarPedido(' . $row["id_pedido"] . ', \'Aprobado\')">Aprobado</button>';
                        echo '<button class="rechazado-btn" onclick="marcarPedido(' . $row["id_pedido"] . ', \'Rechazado\')">Rechazado</button>';
                        echo "<br>";

                        echo "</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No se encontraron ventas.</p>";
                }
                ?>
        </div>
    </div>
<script>

function marcarPedido(idPedido, estado) {
    // Realiza una solicitud GET a venta.php con parámetros de consulta para aprobar o rechazar el pedido.
    fetch(`../admin/venta.php?id_pedido=${idPedido}&estado=${estado}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // La operación se realizó con éxito, muestra una alerta y actualiza la página.
                alert('Pedido marcado como ' + estado);
                location.reload();
            } else {
                // Hubo un error, muestra una alerta de error.
                alert('Error al marcar el pedido como ' + estado);
            }
        })
        .catch(error => {
            console.error('Error de red:', error);
        });
}
    
</script>
</body>
</html>