<?php

session_start();

require('bd.php');
require('funciones.php');

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tienda online</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/9ab94acfdc.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../estilos/estilo_navbar.css">
  <link rel="stylesheet" href="../estilos/estilo_tienda.css">
  <link rel="stylesheet" href="../estilos/estilo_productos_card.css">
</head>
<body>
  <?php
    include('navbar.php');
  ?>
  <section id="page">
  <button id="toggle-menu">☰</button>
    <div id="menu" class="col-md-2">
    <div class="list-group">
      <h4>Categorías</h4>
      <?php
        $query = "SELECT id_categoria, nombre_categoria FROM categorias";
        $result = mysqli_query($conexion, $query);
        while ($categoria = mysqli_fetch_assoc($result)) {
          echo '<a href="?categoria=' . $categoria['id_categoria'] . '" class="list-group-item" id="main-category">' . $categoria['nombre_categoria'] . '</a>';
          $query = "SELECT id_subcategoria, nombre_subcategoria FROM subcategorias WHERE id_categoria=" . $categoria['id_categoria'];
          $subcategorias = mysqli_query($conexion, $query);
          while ($subcategoria = mysqli_fetch_assoc($subcategorias)) {
            echo '<a href="?categoria=' . $categoria['id_categoria'] . '&subcategoria=' . $subcategoria['id_subcategoria'] . '" class="list-group-item" id="sub-category">' . $subcategoria['nombre_subcategoria'] . '</a>';
          }
        }
      ?>
    </div>
  </div>
  <section id="seccion-productos">
    <div class="productos">
      <ul class="productos-list">
          <?php
          //$url = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
          while ($producto = mysqli_fetch_array($resultado)) {
            echo "<div class='card'><a href='producto.php?id=". $producto['id'] . "'>";
            echo "<div class='card-img'><img class='card-img' src='" . $producto['imagen'] . "' alt='" . $producto['nombre'] . "'></div>";
            echo "<div class='card-info'>";
            echo "<p class='text-title'>" . $producto['nombre'] . "</p>";
            echo "<p class='text-body'>" . $producto['descripcion'] . "</p>";
            echo "</div>";
            echo "<div class='card-footer'>";
            echo "<span class='text-title'>$" . $producto['precio'] . "</span></a>";
            echo "<a href='producto.php?id=". $producto['id'] . "'><div class='card-button'>";
            echo "<svg class='svg-icon' viewBox='0 0 20 20'>";
            echo "<path d='M17.72,5.011H8.026c-0.271,0-0.49,0.219-0.49,0.489c0,0.271,0.219,0.489,0.49,0.489h8.962l-1.979,4.773H6.763L4.935,5.343C4.926,5.316,4.897,5.309,4.884,5.286c-0.011-0.024,0-0.051-0.017-0.074C4.833,5.166,4.025,4.081,2.33,3.908C2.068,3.883,1.822,4.075,1.795,4.344C1.767,4.612,1.962,4.853,2.231,4.88c1.143,0.118,1.703,0.738,1.808,0.866l1.91,5.661c0.066,0.199,0.252,0.333,0.463,0.333h8.924c0.116,0,0.22-0.053,0.308-0.128c0.027-0.023,0.042-0.048,0.063-0.076c0.026-0.034,0.063-0.058,0.08-0.099l2.384-5.75c0.062-0.151,0.046-0.323-0.045-0.458C18.036,5.092,17.883,5.011,17.72,5.011z'></path>";
            echo "<path d='M8.251,12.386c-1.023,0-1.856,0.834-1.856,1.856s0.833,1.853,1.856,1.853c1.021,0,1.853-0.83,1.853-1.853S9.273,12.386,8.251,12.386z M8.251,15.116c-0.484,0-0.877-0.393-0.877-0.874c0-0.484,0.394-0.878,0.877-0.878c0.482,0,0.875,0.394,0.875,0.878C9.126,14.724,8.733,15.116,8.251,15.116z'></path>";
            echo "<path d='M13.972,12.386c-1.022,0-1.855,0.834-1.855,1.856s0.833,1.853,1.855,1.853s1.854-0.83,1.854-1.853S14.994,12.386,13.972,12.386z M13.972,15.116c-0.484,0-0.878-0.393-0.878-0.874c0-0.484,0.394-0.878,0.878-0.878c0.482,0,0.875,0.394,0.875,0.878C14.847,14.724,14.454,15.116,13.972,15.116z'></path>";
            echo "</svg>";
            echo "</div></a>";
            echo "</div></div>";
          }
          ?>
      </ul>
    </div>
  </section>
  <div class="pagination">
    <?php
    // Calcula el número total de páginas
    $query_total = "SELECT COUNT(*) as total FROM productos";
    $resultado_total = mysqli_query($conexion, $query_total);
    $fila_total = mysqli_fetch_assoc($resultado_total);
    $total_productos = $fila_total['total'];
    $total_paginas = ceil($total_productos / $productos_por_pagina);

    $pagina_anterior = $pagina_actual - 1;
    $pagina_siguiente = $pagina_actual + 1;

    if ($pagina_actual > 1) {
        echo "<a href='tienda.php?pagina=$pagina_anterior'>Anterior</a>";
    }
    if ($total_paginas > 1) {
        for ($i = 1; $i <= $total_paginas; $i++) {
            if ($i == $pagina_actual) {
                echo "<span class='current'>$i</span>";
            } else {
                echo "<a href='tienda.php?pagina=$i'>$i</a>";
            }
        }
    }
    if ($pagina_actual < $total_paginas) {
        echo "<a href='tienda.php?pagina=$pagina_siguiente'>Siguiente</a>";
    }
    ?>
</div>
  </section>
  </body>
  <script>
    const toggleMenu = document.getElementById('toggle-menu');
    const menu = document.getElementById('menu');

    toggleMenu.addEventListener('click', () => {
      menu.classList.toggle('visible');
    });
  </script>
</html>
<?php
// Cerrar la conexión
mysqli_close($conexion);
?>