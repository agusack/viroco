<?php
error_log(" Inicio del script de limpieza de reservas. -", 3, "depuracion.txt");


    // Archivo bd.php
    require '../app/conexion.php';
    error_log(" conecto -", 3, "depuracion.txt");

    date_default_timezone_set('America/Argentina/Buenos_Aires');
    // Calcular la fecha y hora hace 30 minutos
    $limite_tiempo = date('Y-m-d H:i:s', strtotime('-30 minutes'));

    // Consultar las reservas caducadas
    $sql = "SELECT * FROM reservas_stock WHERE hora_reserva <= :limite_tiempo";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':limite_tiempo', $limite_tiempo, PDO::PARAM_STR);
    $stmt->execute();
    $reservas_caducadas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    error_log(" obtuvo datos reserva -", 3, "depuracion.txt");

    foreach ($reservas_caducadas as $reserva) {
        $producto_id = $reserva['id_producto'];
        $combinacion_unica = $reserva['combinacion_unica'];
        $cantidad_reservada = $reserva['cantidad_reservada'];

        // Restaurar el stock en la tabla "combinaciones_producto"
        $sqlRestaurarStockCombinaciones = "UPDATE combinaciones_producto SET stock = stock + :cantidad_reservada WHERE id_producto = :producto_id AND combinacion_unica = :combinacion_unica";
        $stmtRestaurarStockCombinaciones = $conexion->prepare($sqlRestaurarStockCombinaciones);
        $stmtRestaurarStockCombinaciones->bindParam(':cantidad_reservada', $cantidad_reservada, PDO::PARAM_INT);
        $stmtRestaurarStockCombinaciones->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
        $stmtRestaurarStockCombinaciones->bindParam(':combinacion_unica', $combinacion_unica, PDO::PARAM_STR);
        $stmtRestaurarStockCombinaciones->execute();

        error_log(" update combinaciones_producto -", 3, "depuracion.txt");

        // Obtener el stock actual del producto en la tabla "productos"
        $sqlStockProducto = "SELECT stock FROM productos WHERE id = :producto_id";
        $stmtStockProducto = $conexion->prepare($sqlStockProducto);
        $stmtStockProducto->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
        $stmtStockProducto->execute();
        $stock_producto = $stmtStockProducto->fetchColumn();

        // Actualizar el stock en la tabla "productos"
        $nuevo_stock_producto = $stock_producto + $cantidad_reservada;
        $sqlRestaurarStockProducto = "UPDATE productos SET stock = :nuevo_stock_producto WHERE id = :producto_id";
        $stmtRestaurarStockProducto = $conexion->prepare($sqlRestaurarStockProducto);
        $stmtRestaurarStockProducto->bindParam(':nuevo_stock_producto', $nuevo_stock_producto, PDO::PARAM_INT);
        $stmtRestaurarStockProducto->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
        $stmtRestaurarStockProducto->execute();

        error_log(" update productos -", 3, "depuracion.txt");

        // Eliminar la reserva caducada
        $sqlEliminarReserva = "DELETE FROM reservas_stock WHERE id_reserva = :id_reserva";
        $stmtEliminarReserva = $conexion->prepare($sqlEliminarReserva);
        $stmtEliminarReserva->bindParam(':id_reserva', $reserva['id_reserva'], PDO::PARAM_INT);
        $stmtEliminarReserva->execute();

        error_log(" delete reservas -", 3, "depuracion.txt");
    }

    error_log(" Fin del script de limpieza de reservas. ". $limite_tiempo ." -", 3, "depuracion.txt");
?>