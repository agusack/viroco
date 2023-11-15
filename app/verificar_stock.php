<?php
// verificar_stock.php

// Agrega aquí la lógica para realizar la consulta en la base de datos y verificar el stock
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibe los datos enviados desde JavaScript
    $producto_id = $_POST['producto_id'];
    $combinacion_seleccionada = $_POST['combinacion_unica'];

    // Realiza la consulta en la tabla "combinaciones_producto" para obtener el stock disponible
    require('conexion.php'); // Reemplaza 'conexion.php' por el archivo que contiene la conexión a la base de datos

    $sql = "SELECT stock FROM combinaciones_producto WHERE id_producto = :id_producto AND combinacion_unica = :combinacion_unica";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id_producto', $producto_id, PDO::PARAM_INT);
    $stmt->bindParam(':combinacion_unica', $combinacion_seleccionada, PDO::PARAM_STR);
    $stmt->execute();

    // Obtener el resultado de la consulta
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        $stock_disponible = $resultado['stock'];

        // Devuelve la respuesta en formato JSON
        $response = array('stock_disponible' => $stock_disponible);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    } else {
        // Si no se encontró la combinación, devuelve stock_disponible = 0
        $response = array('stock_disponible' => 0);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}
?>