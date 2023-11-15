<?php

if(isset($_GET['agregar'])) {
  $id = $_GET['agregar'];
  $nombre = $_GET['nombre'];
  $precio = $_GET['precio'];
  $imagen = $_GET['ruta_imagen'];
  $cantidad = $_GET['cantidad']; //Cantidad predeterminada para agregar al carrito

  $caracteristicas = array();
  foreach ($_GET as $clave => $valor) {
    if (strpos($clave, 'caracteristica_') === 0) {
      $nombre_caracteristica = substr($clave, strlen('caracteristica_'));
      $caracteristicas[$nombre_caracteristica] = $valor;
    }
  }

    // Verificar si el producto ya está en el carrito
    $producto_encontrado = false;
    if(isset($_SESSION['carrito'][$id])) {
      // Si el producto ya está en el carrito, verificar si las características coinciden
      foreach ($_SESSION['carrito'][$id]['caracteristicas'] as $clave => $valor) {
        if ($caracteristicas[$clave] != $valor) {
          $producto_encontrado = false; // Las características no coinciden
          break;
        }
      }
      $producto_encontrado = true; // Las características coinciden
    }

    if ($producto_encontrado) {
      // Si el producto ya está en el carrito con las mismas características, actualizar la cantidad en lugar de agregarlo nuevamente
      $_SESSION['carrito'][$id]['cantidad'] += $cantidad;
      incrementarPopularidad($id);
      $_SESSION['notificacion'] = 'El producto se agregó al carrito.';
    } else {
      // Si el producto no está en el carrito o las características no coinciden, agregarlo como un nuevo elemento
      $_SESSION['carrito'][$id . '_' . implode('_', $caracteristicas)] = array(
        "nombre" => $nombre,
        "precio" => $precio,
        "imagen" => $imagen,
        "cantidad" => $cantidad,
        "caracteristicas" => $caracteristicas
      );
      incrementarPopularidad($id);
      $_SESSION['notificacion'] = 'El producto se agregó al carrito.';
    }
  }

// Eliminar un producto del carrito
if (isset($_GET['eliminar'])) {
  $id = $_GET['eliminar'];
  unset($_SESSION['carrito'][$id]);
}

// Vaciar el carrito completamente
if (isset($_GET['vaciar'])) {
  unset($_SESSION['carrito']);
}

// Mostrar la cantidad de productos en el carrito
$cantidad_total = 0;
if (isset($_SESSION['carrito'])) {
  foreach ($_SESSION['carrito'] as $producto) {
    $cantidad_total += $producto['cantidad'];
  }
}

function incrementarPopularidad($producto_id) {
    require('bd.php');
  // Realizar una consulta SQL para incrementar el valor de la columna "popular" en 1 para el producto dado
  $consulta = "UPDATE `productos` SET `popular` = `popular` + 1 WHERE `id` = $producto_id";

  // Ejecutar la consulta SQL
  $resultado = mysqli_query($conexion, $consulta);

  // Verificar si la consulta se ejecutó correctamente
  if (!$resultado) {
    die("Error al incrementar la popularidad del producto: " . mysqli_error($conexion));
  }
}


?>