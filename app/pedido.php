<?php

session_start();

// Incluye el archivo de conexión a la base de datos
require('bd.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe los datos del formulario
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
    $deliveryMethod = isset($_POST["deliveryMethod"]) ? $_POST["deliveryMethod"] : "";
    $nombreLocal = isset($_POST["nombreLocal"]) ? $_POST["nombreLocal"] : "";
    $dniLocal = isset($_POST["dniLocal"]) ? $_POST["dniLocal"] : "";
    $nombreDomicilio = isset($_POST["nombreDomicilio"]) ? $_POST["nombreDomicilio"] : "";
    $telefonoDomicilio = isset($_POST["telefonoDomicilio"]) ? $_POST["telefonoDomicilio"] : "";
    $ciudad = isset($_POST["ciudad"]) ? $_POST["ciudad"] : "";
    $calle = isset($_POST["calle"]) ? $_POST["calle"] : "";
    $altura = isset($_POST["altura"]) ? $_POST["altura"] : "";
    $depto = isset($_POST["depto"]) ? $_POST["depto"] : "";
    $postalCode = isset($_POST["postalCode"]) ? $_POST["postalCode"] : "";
    $paymentMethod = isset($_POST["paymentMethod"]) ? $_POST["paymentMethod"] : "";
    $celularEfectivo = isset($_POST["celularEfectivo"]) ? $_POST["celularEfectivo"] : "";
    $celularTransferencia = isset($_POST["celularTransferencia"]) ? $_POST["celularTransferencia"] : "";
    $celularTarjeta = isset($_POST["celularTarjeta"]) ? $_POST["celularTarjeta"] : "";

    // Obtén los datos del usuario desde la sesión
    $id_usuario = $_SESSION['id'];
    $nombre_usuario = $_SESSION['username'];
    $correo_usuario = $_SESSION['correo'];
    
    foreach ($_SESSION['carrito'] as $id => $producto) {
        $productos[] = [
            "producto" => $producto["nombre"],
            "caracteristicas" => $producto["caracteristicas"],
            "cantidad" => $producto["cantidad"],
            "id_producto" => $id
        ];
          
        $cantidad_vendida = $producto["cantidad"];
    }
    
    $productos_json = json_encode($productos);
    $total = $_SESSION['total'];
    
    // Obtén la fecha actual
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $fecha_pedido = date('Y-m-d H:i:s');

    if ($deliveryMethod == 'local') {

        $entrega = [
            'Entrega' => 'Retiro por el local',
            'Nombre' => $nombreLocal,
            'DNI' => $dniLocal,
        ];

        $entrega_json = json_encode($entrega);

        if ($paymentMethod == 'efectivo') {
            $celular = $celularEfectivo;
        } elseif ($paymentMethod == 'transferencia') {
            $celular = $celularTransferencia;
        } else {
            $celular = $celularTarjeta;
        }

        $sql = "INSERT INTO pedidos (id_usuario, nombre_usuario, correo_usuario, celular, fecha_pedido, total, entrega, metodo_pago, productos)
                VALUES ('$id_usuario', '$nombre_usuario', '$correo_usuario', '$celular', '$fecha_pedido', '$total', '$entrega_json', '$paymentMethod', '$productos_json')";
        
        if ($conexion->query($sql) !== TRUE) {
            die("Error al registrar la venta: " . $conexion->error);
            $response = 'error';
          } else {
            $response = 'ok';
          }


        // Cerrar la conexión a la base de datos
        $conexion->close();
        
        
    } else {

        $entrega = [
            'Entrega' => 'Envio a domicilio',
            'Nombre' => $nombreDomicilio,
            'Telefono' => $telefonoDomicilio,
            'Ciudad' => $ciudad,
            'Calle' => $calle,
            'Altura' => $altura,
            'Depto' => $depto,
            'Codigo Postal' => $postalCode
        ];

        $entrega_json = json_encode($entrega);

        if ($paymentMethod == 'efectivo') {
            $celular = $celularEfectivo;
        } elseif ($paymentMethod == 'transferencia') {
            $celular = $celularTransferencia;
        } else {
            $celular = $celularTarjeta;
        }

        $sql = "INSERT INTO pedidos (id_usuario, nombre_usuario, correo_usuario, celular, fecha_pedido, total, entrega, metodo_pago, productos)
                VALUES ('$id_usuario', '$nombre_usuario', '$correo_usuario', '$celular', '$fecha_pedido', '$total', '$entrega_json', '$paymentMethod', '$productos_json')";
        
        if ($conexion->query($sql) !== TRUE) {
            die("Error al registrar la venta: " . $conexion->error);
            $response = 'error';
          } else {
            $response = 'ok';
          }

        // Cerrar la conexión a la base de datos
        $conexion->close();

    }

    // Devolver la respuesta en formato JSON sin HTML
    header("Content-Type: application/json");
    echo json_encode($response);
} else {
    // Si la solicitud no es de tipo POST, devuelve un error
    http_response_code(400);
    echo "Error: Método de solicitud no válido";
}
?>




