<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../app/login.php');
    exit();
}

if (isset($_GET['id_pedido'], $_GET['estado'])) {
    $idPedido = $_GET['id_pedido'];
    $estado = $_GET['estado'];

    // Incluye el archivo de conexión a la base de datos
    include '../app/bd.php';

    // Iniciar una transacción
    mysqli_begin_transaction($conexion);

    $success = false;

    try {
        // Obtener los datos del pedido que se va a mover a "ventas"
        $querySelectPedido = "SELECT * FROM pedidos WHERE id_pedido = ?";
        $stmtSelectPedido = mysqli_prepare($conexion, $querySelectPedido);
        mysqli_stmt_bind_param($stmtSelectPedido, "i", $idPedido);
        mysqli_stmt_execute($stmtSelectPedido);
        $resultPedido = mysqli_stmt_get_result($stmtSelectPedido);

        if ($rowPedido = mysqli_fetch_assoc($resultPedido)) {
            // Obtener el JSON de productos
            $productosJSON = json_decode($rowPedido['productos'], true);

            if ($productosJSON) {
                foreach ($productosJSON as $producto) {
                    // Obtener el id del producto y la combinación única
                    $idProductoConCombinacion = $producto['id_producto'];
                    $parts = explode('_', $idProductoConCombinacion);
                    $idProducto = $parts[0]; // El primer elemento es el ID del producto
                    $combinacionUnica = str_replace('_', ' - ', implode('_', array_slice($parts, 1))); // Reemplazar guiones bajos con " - "

                    $cantidad = $producto['cantidad'];

                    // Restaurar el stock en la tabla "productos"
                    $queryUpdateStockProducto = "UPDATE productos SET stock = stock + ? WHERE id = ?";
                    $stmtUpdateStockProducto = mysqli_prepare($conexion, $queryUpdateStockProducto);
                    mysqli_stmt_bind_param($stmtUpdateStockProducto, "is", $cantidad, $idProducto);

                    if (mysqli_stmt_execute($stmtUpdateStockProducto)) {
                        // Stock de producto actualizado correctamente
                    } else {
                        echo "Error al actualizar el stock de producto: " . mysqli_error($conexion);
                    }

                    // Restaurar el stock en la tabla "combinaciones_producto"
                    $queryUpdateStockCombinacion = "UPDATE combinaciones_producto SET stock = stock + ? WHERE id_producto = ? AND combinacion_unica = ?";
                    $stmtUpdateStockCombinacion = mysqli_prepare($conexion, $queryUpdateStockCombinacion);
                    mysqli_stmt_bind_param($stmtUpdateStockCombinacion, "iss", $cantidad, $idProducto, $combinacionUnica);

                    if (mysqli_stmt_execute($stmtUpdateStockCombinacion)) {
                        // Stock de combinación única actualizado correctamente
                    } else {
                        echo "Error al actualizar el stock de combinación única: " . mysqli_error($conexion);
                    }

                    // Cerrar las consultas preparadas
                    mysqli_stmt_close($stmtUpdateStockProducto);
                    mysqli_stmt_close($stmtUpdateStockCombinacion);
                }
            }

            // Insertar datos en la tabla "ventas"
            $queryInsertVenta = "INSERT INTO ventas (id_usuario, nombre_usuario, correo_usuario, fecha_venta, total, estado, entrega, productos) 
                                VALUES (?, ?, ?, NOW(), ?, ?, ?, ?)";
            $stmtInsertVenta = mysqli_prepare($conexion, $queryInsertVenta);
            mysqli_stmt_bind_param($stmtInsertVenta, "issssss",
                $rowPedido['id_usuario'],
                $rowPedido['nombre_usuario'],
                $rowPedido['correo_usuario'],
                $rowPedido['total'],
                $estado,
                $rowPedido['entrega'],
                $rowPedido['productos']
            );

            if (mysqli_stmt_execute($stmtInsertVenta)) {
                // Eliminar el pedido de la tabla "pedidos" si la inserción en "ventas" fue exitosa
                $queryDeletePedido = "DELETE FROM pedidos WHERE id_pedido = ?";
                $stmtDeletePedido = mysqli_prepare($conexion, $queryDeletePedido);
                mysqli_stmt_bind_param($stmtDeletePedido, "i", $idPedido);

                if (mysqli_stmt_execute($stmtDeletePedido)) {
                    // Commit la transacción si todas las operaciones se realizaron con éxito
                    mysqli_commit($conexion);
                    $success = true;
                } else {
                    echo "Error al eliminar pedido: " . mysqli_error($conexion);
                }
            } else {
                echo "Error al insertar en ventas: " . mysqli_error($conexion);
            }
        } else {
            echo "Pedido no encontrado";
        }

        // Cerrar las declaraciones preparadas
        mysqli_stmt_close($stmtSelectPedido);
        mysqli_stmt_close($stmtInsertVenta);
        mysqli_stmt_close($stmtDeletePedido);
    } catch (Exception $e) {
        // Si ocurre alguna excepción, se revierte la transacción
        mysqli_rollback($conexion);
        echo "Error en transacción: " . $e->getMessage();
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($conexion);

    // Verificar si la operación se realizó con éxito y mostrar una respuesta JSON correspondiente
    if ($success) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error al procesar el pedido'));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Solicitud no válida'));
}
?>


