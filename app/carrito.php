<?php

  session_start();

  require('bd.php');
  require('funciones.php');

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Carrito de compras</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/9ab94acfdc.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../estilos/estilo_carrito.css">
  <link rel="stylesheet" href="../estilos/estilo_navbar.css">
</head>
<body>
  <?php include('navbar.php'); ?>
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <h1>Carrito de compras</h1>
        <?php mostrarProductosCarrito(); ?>
      </div>
      <div class="col-md-4">
        <h1>Resumen del carrito</h1>
        <div class="panel panel-default">
          <div class="panel-body">
            <?php mostrarResumenCarrito(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

<?php

function mostrarProductosCarrito() {
  if (!isset($_SESSION['carrito'])) {
    echo "<p>No hay productos en el carrito</p>";
  } else {
    $subtotal = 0;
    echo "<table class=\"tabla-carrito\">";
    echo "<thead><tr><th>Producto</th><th>Detalles</th><th>Cantidad</th><th>Precio</th><th>Total</th><th>Acción</th></tr></thead>";
    echo "<tbody>";
    foreach ($_SESSION['carrito'] as $id => $producto) {
      $subtotal += $producto['precio'] * $producto['cantidad'];
      echo "<tr>";
      echo "<td><img src=\"" . $producto['imagen'] . "\" alt=\"" . $producto['nombre'] . "\"></td>";
      echo "<td>";
      foreach($producto['caracteristicas'] as $caracteristica=>$valor) {
        echo "<div><strong>" . $caracteristica . ": </strong>" . $valor . "</div>";
      }
      echo "</td>";
      echo "<td>" . $producto['cantidad'] . "</td>";
      echo "<td>$" . $producto['precio'] . "</td>";
      echo "<td>$" . $producto['precio'] * $producto['cantidad'] . "</td>";
      echo "<td><a href=\"?eliminar=$id\" class=\"btn btn-xs btn-danger\">Eliminar</a></td>";
      echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
  }
}

function mostrarResumenCarrito() {
  if (!isset($_SESSION['carrito'])) {
    echo "<p>No hay productos en el carrito</p>";
  } else {
    $subtotal = 0;
    foreach ($_SESSION['carrito'] as $id => $producto) {
      $subtotal += $producto['precio'] * $producto['cantidad'];
    }
    echo "<p>Total: $" . $subtotal . "</p>";
    echo "<a href=\"?vaciar=1\" class=\"btn btn-s btn-warning\">Vaciar carrito</a>";
    echo "<a href=\"checkout.php\" class=\"btn btn-s btn-success pull-right\">Proceder al pago</a>";
  }
}

?>
