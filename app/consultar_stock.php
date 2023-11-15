<?php
// Verificar que se haya realizado una solicitud POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Supongamos que estás enviando los datos de las características seleccionadas como un JSON codificado
  $producto_id = $_POST['producto_id'];
  $combinacion_seleccionada = $_POST['combinacion_seleccionada'];

  // Incluir el archivo "bd.php" para obtener la conexión a la base de datos
  require_once "bd.php";

  // Construir la consulta SQL con la combinación única en formato 'Blanco - M'
  $sql = "SELECT stock FROM combinaciones_producto WHERE id_producto = '$producto_id' AND combinacion_unica = '$combinacion_seleccionada'";

  // Ejecutar la consulta y obtener el resultado
  $result = $conexion->query($sql);

  if ($result->num_rows > 0) {
    // Obtener el valor del stock desde el resultado
    $row = $result->fetch_assoc();
    $response = array('stock_disponible' => $row['stock']);
    header('Content-Type: application/json');
    echo json_encode($response);
  } else {
    // No se encontró el stock para la combinación única
    $response = array("error" => "No se encontró el stock para la combinación única");
    header('Content-Type: application/json');
    echo json_encode($response);
  }
}
?>