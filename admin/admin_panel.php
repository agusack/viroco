<?php

    session_start();
        
    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
        header('Location: ../app/login.php');
        exit();
    }

    require('../app/bd.php');

    $total_usuarios_query = "SELECT COUNT(*) AS total_usuarios FROM usuarios";
    $total_usuarios_result = $conexion->query($total_usuarios_query);
    $total_usuarios = $total_usuarios_result->fetch_assoc()["total_usuarios"];

    // Consulta SQL para obtener el total de ventas
    $sql_total = "SELECT SUM(total) as total_ventas FROM ventas WHERE estado = 'Aprobado'";

    // Consulta SQL para obtener el número de pedidos completados
    $sql_completados = "SELECT COUNT(*) as num_completados FROM ventas WHERE estado = 'Aprobado'";

    // Consulta SQL para obtener el número de pedidos pendientes
    $sql_pendientes = "SELECT COUNT(*) as num_pendientes FROM ventas WHERE estado = 'Rechazado'";

    // Conectar con la base de datos y ejecutar consultas
    $resultado_total = mysqli_query($conexion, $sql_total);
    $resultado_completados = mysqli_query($conexion, $sql_completados);
    $resultado_pendientes = mysqli_query($conexion, $sql_pendientes);

    // consulta SQL para recuperar los datos de la tabla "productos"
    $sql_stock = "SELECT * FROM productos";

    // ejecutar la consulta SQL
    $result = mysqli_query($conexion, $sql_stock);

    // variables para contar los productos
    $total_productos = 0;
    $productos_agotados = 0;
    $productos_bajo_stock = 0;

    // procesar el resultado de la consulta SQL
    if (mysqli_num_rows($result) > 0) {
        // contar el número total de productos
        $total_productos = mysqli_num_rows($result);

        // contar el número de productos agotados y con bajo stock
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['stock'] == 0) {
                $productos_agotados++;
            } else if ($row['stock'] <= 10) {
                $productos_bajo_stock++;
            }
        }
    }

  // Consulta SQL para contar la cantidad de categorías y subcategorías en total
  $consulta_total_categorias = "SELECT COUNT(*) AS total_categorias FROM (SELECT id_categoria, nombre_categoria FROM categorias UNION ALL SELECT id_subcategoria, nombre_subcategoria FROM subcategorias) AS t";
  $resultado_total_categorias = $conexion->query($consulta_total_categorias);
  $fila_total_categorias = $resultado_total_categorias->fetch_assoc();
  $total_categorias = $fila_total_categorias['total_categorias'];

  // Consulta SQL para contar la cantidad de categorías principales
  $consulta_categorias_principales = "SELECT COUNT(*) AS categorias_principales FROM categorias";
  $resultado_categorias_principales = $conexion->query($consulta_categorias_principales);
  $fila_categorias_principales = $resultado_categorias_principales->fetch_assoc();
  $categorias_principales = $fila_categorias_principales['categorias_principales'];

  // Consulta SQL para contar la cantidad de subcategorías
  $consulta_subcategorias = "SELECT COUNT(*) AS subcategorias FROM subcategorias";
  $resultado_subcategorias = $conexion->query($consulta_subcategorias);
  $fila_subcategorias = $resultado_subcategorias->fetch_assoc();
  $subcategorias = $fila_subcategorias['subcategorias'];

  // Consulta SQL para contar la cantidad de subcategorías
  $consulta_pedidos = "SELECT COUNT(*) AS pedidos FROM pedidos";
  $resultado_pedidos = $conexion->query($consulta_pedidos);
  $fila_pedidos = $resultado_pedidos->fetch_assoc();
  $pedidos = $fila_pedidos['pedidos'];


  // Cerrar la conexión a la base de datos
  mysqli_close($conexion);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel de admin</title>
    <link rel="stylesheet" href="../estilos/estilo_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css">
    <script src="https://kit.fontawesome.com/9ab94acfdc.js" crossorigin="anonymous"></script>
</head>
<body>
    <div id="wrapper">
            <?php
                include('navbar_admin.php')
            ?>
        <div class="container">
          <h1><i class="fa-regular fa-face-laugh-beam fa-beat"></i> ¡Hola <?php echo $_SESSION['username'] ?>!</h1>
            <h2>Resumen de la tienda</h2>
              <div class="container_card">
              <div class="card is-shady">
                <div class="card-content">
                  <div class="media">
                    <div class="media-left">
                      <i class="fas fa-users fa-3x"></i>
                    </div>
                    <div class="media-content">
                      <p class="subtitulo">Clientes: <?php echo $total_usuarios; ?></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card is-shady">
                <div class="card-content">
                  <div class="media">
                    <div class="media-left">
                      <i class="fas fa-dollar-sign fa-3x"></i>
                    </div>
                    <?php
                    if(mysqli_num_rows($resultado_total) > 0 && mysqli_num_rows($resultado_completados) > 0 && mysqli_num_rows($resultado_pendientes) > 0) {
                      $fila_total = mysqli_fetch_assoc($resultado_total);
                      $total_ventas = $fila_total["total_ventas"];
                    
                      $fila_completados = mysqli_fetch_assoc($resultado_completados);
                      $num_completados = $fila_completados["num_completados"];
                    
                      $fila_pendientes = mysqli_fetch_assoc($resultado_pendientes);
                      $num_pendientes = $fila_pendientes["num_pendientes"];
                    
                      // Mostrar resumen de ventas en la sección de HTML correspondiente
                      echo '<div class="media-content">';
                      echo '<p class="subtitulo">Ventas: $' . $total_ventas . '</p>';
                      echo '<p>Ventas completadas: ' . $num_completados . '</p>';
                      echo '<p>Ventas pendientes: ' . $num_pendientes . '</p>';
                      echo '</div>';
                    } else {
                      // Si no se encontraron resultados, mostrar mensaje de error
                      echo '<div class="media-content">';
                      echo '<p class="subtitulo">No se encontraron resultados</p>';
                    }
                    ?>
                  </div>
                </div>
              </div>
              <div class="card is-shady">
                <div class="card-content">
                  <div class="media">
                    <div class="media-left">
                      <i class="fas fa-boxes fa-3x"></i>
                    </div>
                    <div class="media-content">
                      <p class="subtitulo">Inventario: <?php echo $total_productos; ?></p>
                      <p>Productos agotados: <?php echo $productos_agotados; ?></p>
                      <p>Productos con bajo stock: <?php echo $productos_bajo_stock; ?></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card is-shady">
                <div class="card-content">
                  <div class="media">
                    <div class="media-left">
                      <i class="fa-solid fa-code-pull-request fa-3x"></i> <!-- Icono de categorías -->
                    </div>
                    <div class="media-content">
                      <p class="subtitulo">Pedidos totales: <?php echo $pedidos; ?></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card is-shady">
                <div class="card-content">
                  <div class="media">
                    <div class="media-left">
                      <i class="fas fa-sitemap fa-3x"></i> <!-- Icono de categorías -->
                    </div>
                    <div class="media-content">
                      <p class="subtitulo">Categorías totales: <?php echo $total_categorias; ?></p>
                      <p>Categorías principales: <?php echo $categorias_principales; ?></p>
                      <p>Subcategorías: <?php echo $subcategorias; ?></p>
                    </div>
                  </div>
                </div>
              </div>
              </div>
        </div>
    </div>
</body>
</html>