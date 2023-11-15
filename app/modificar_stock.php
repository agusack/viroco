<?php
// Archivo bd.php
require_once 'conexion.php';

// Verificar si se reciben los datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados por AJAX
    $producto_id = $_POST['producto_id'];
    $combinacion_unica = $_POST['combinacion_unica'];
    $cantidad = (int) $_POST['cantidad'];

    // Consultar la combinación única en la base de datos
    $sql = "SELECT id_combinacion, stock FROM combinaciones_producto WHERE id_producto = :producto_id AND combinacion_unica = :combinacion_unica";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
    $stmt->bindParam(':combinacion_unica', $combinacion_unica, PDO::PARAM_STR);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        $id_combinacion = $resultado['id_combinacion'];
        $stock_actual_combinacion = $resultado['stock'];

        // Consultar el stock general del producto
        $sqlStockGeneral = "SELECT stock FROM productos WHERE id = :producto_id";
        $stmtStockGeneral = $conexion->prepare($sqlStockGeneral);
        $stmtStockGeneral->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
        $stmtStockGeneral->execute();
        $resultadoStockGeneral = $stmtStockGeneral->fetch(PDO::FETCH_ASSOC);

        if ($resultadoStockGeneral) {
            $stock_general = $resultadoStockGeneral['stock'];

            if ($stock_actual_combinacion >= $cantidad && $stock_general >= $cantidad) {
                // Restar la cantidad solicitada al stock de combinación y al stock general
                $nuevo_stock_combinacion = $stock_actual_combinacion - $cantidad;
                $nuevo_stock_general = $stock_general - $cantidad;

                // Actualizar el stock de combinación en la base de datos
                $sqlUpdateCombinacion = "UPDATE combinaciones_producto SET stock = :nuevo_stock_combinacion WHERE id_combinacion = :id_combinacion";
                $stmtUpdateCombinacion = $conexion->prepare($sqlUpdateCombinacion);
                $stmtUpdateCombinacion->bindParam(':nuevo_stock_combinacion', $nuevo_stock_combinacion, PDO::PARAM_INT);
                $stmtUpdateCombinacion->bindParam(':id_combinacion', $id_combinacion, PDO::PARAM_INT);
                $stmtUpdateCombinacion->execute();

                // Actualizar el stock general en la base de datos
                $sqlUpdateGeneral = "UPDATE productos SET stock = :nuevo_stock_general WHERE id = :producto_id";
                $stmtUpdateGeneral = $conexion->prepare($sqlUpdateGeneral);
                $stmtUpdateGeneral->bindParam(':nuevo_stock_general', $nuevo_stock_general, PDO::PARAM_INT);
                $stmtUpdateGeneral->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
                $stmtUpdateGeneral->execute();

                // Realizar la reserva temporal de stock
                // Esto implica crear un registro en la tabla de reservas_stock
                $sqlReserva = "INSERT INTO reservas_stock (id_producto, combinacion_unica, cantidad_reservada) VALUES (:producto_id, :combinacion_unica, :cantidad)";
                $stmtReserva = $conexion->prepare($sqlReserva);
                $stmtReserva->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
                $stmtReserva->bindParam(':combinacion_unica', $combinacion_unica, PDO::PARAM_STR);
                $stmtReserva->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);

                if ($stmtReserva->execute()) {
                    // Responder con una respuesta JSON de éxito
                    $response = ['status' => 'success', 'message' => 'Stock actualizado y reservado correctamente'];
                    echo json_encode($response);
                    exit;
                } else {
                    // Responder con una respuesta JSON de error en caso de fallo en la reserva
                    $response = ['status' => 'error', 'message' => 'Error al reservar el stock'];
                    echo json_encode($response);
                    exit;
                }
            } else {
                // Responder con una respuesta JSON de error (no hay suficiente stock)
                $response = ['status' => 'error', 'message' => 'No hay suficiente stock disponible'];
                echo json_encode($response);
                exit;
            }
        } else {
            // Responder con una respuesta JSON de error (producto no encontrado)
            $response = ['status' => 'error', 'message' => 'Producto no encontrado'];
            echo json_encode($response);
            exit;
        }
    } else {
        // Responder con una respuesta JSON de error (combinación no encontrada)
        $response = ['status' => 'error', 'message' => 'Combinación no encontrada'];
        echo json_encode($response);
        exit;
    }
}
?>
