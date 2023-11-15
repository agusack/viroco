<?php
  // Obtener la categoría actual, si se especifica en la URL
  $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

  // Obtener la subcategoría actual, si se especifica en la URL
  $subcategoria = isset($_GET['subcategoria']) ? $_GET['subcategoria'] : '';

  // Obtener la cadena de búsqueda, si se especifica en el formulario
  $buscar = isset($_POST['buscar']) ? $_POST['buscar'] : '';

  // Query para obtener los productos de la base de datos
  if ($subcategoria && $buscar) {
    $query = "SELECT * FROM productos WHERE id_subcategoria = '$subcategoria' AND nombre LIKE '%$buscar%'";
  } else if ($categoria && !$subcategoria) {
    $query = "SELECT * FROM productos WHERE id_categoria = '$categoria'";
  } else if ($categoria && $subcategoria) {
    $query = "SELECT * FROM productos WHERE id_subcategoria = '$subcategoria'";
  } else if ($buscar) {
    $query = "SELECT * FROM productos WHERE nombre LIKE '%$buscar%'";
  } else {
    $query = "SELECT * FROM productos";
  } 

  // Número de productos por página
  $productos_por_pagina = 10;

  // Página actual
  if (isset($_GET['pagina'])) {
      $pagina_actual = intval($_GET['pagina']);
  } else {
      $pagina_actual = 1; // Página por defecto si no se especifica
  }

  // Calcula el offset y el límite
  $offset = ($pagina_actual - 1) * $productos_por_pagina;
  $query .= " LIMIT $offset, $productos_por_pagina";

  $resultado = mysqli_query($conexion, $query);

  function obtenerCategorias($conexion) {
    // Hacer una consulta a la base de datos para obtener todas las categorías
    $query = "SELECT * FROM categorias";
    $result = mysqli_query($conexion, $query);
  
    // Crear un array vacío para almacenar las categorías principales
    $categorias_principales = array();
  
    // Iterar sobre el resultado de la consulta
    while ($row = mysqli_fetch_assoc($result)) {
      // Crear un array que represente la categoría principal
      $categoria = array(
        'id' => $row['id_categoria'],
        'nombre' => $row['nombre_categoria'],
        'subcategorias' => array()
      );
  
      // Agregar la categoría principal al array de categorías principales
      $categorias_principales[$row['id_categoria']] = $categoria;
    }
  
    // Hacer una consulta a la base de datos para obtener todas las subcategorías
    $query = "SELECT * FROM subcategorias";
    $result = mysqli_query($conexion, $query);
  
    // Iterar sobre el resultado de la consulta
    while ($row = mysqli_fetch_assoc($result)) {
      // Si la subcategoría tiene una categoría superior, es una subcategoría de una categoría principal
      if ($row['id_categoria']) {
        $subcategoria = array(
          'id' => $row['id_subcategoria'],
          'nombre' => $row['nombre_subcategoria']
        );
  
        // Agregar la subcategoría a su categoría principal correspondiente
        $categorias_principales[$row['id_categoria']]['subcategorias'][] = $subcategoria;
      }
    }
  
    // Devolver el array de categorías principales
    return $categorias_principales;
  
    // Cerrar la conexión a la base de datos
    $conexion->close();
  }
?>
<div id="navbar">
<nav>
  <div id="conteiner_nav">
    <div id="logo"><img src="/Viroco/img/logos/viroco.png" class="ajuste_img" alt="Viroco Regaleria"></div>
      <div id="conteiner_buscador">
        <div class="buscador">
          <form action="/Viroco/app/tienda.php" method="post">
            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            <input type="text" id="buscar" name="buscar" placeholder="Buscar">
          </form>
        </div>
      </div>
    <div id="conteiner_user">
      <div id="carrito" class="button_nav"><a href="/Viroco/app/carrito.php"><i class="fas fa-shopping-cart" id="carrito-icono"></i> (<?php echo $cantidad_total; ?>)</a></div>
      <!-- HTML existente -->
      <div id="user" class="button_nav" style="position: relative;">
        <?php
          // Verificar si la sesión está iniciada
          if(isset($_SESSION['username'])) {
            // Mostrar mensaje personalizado con el nombre de usuario
            echo '<a href="#" class="username">Hola '.$_SESSION['username'].'!</a>';
          } else {
            // Mostrar enlace "Iniciar sesión"
            echo '<a href="/Viroco/app/iniciar_sesion.php">Iniciar sesión</a>';
          }
        ?>
        <div id="menu-usuario" style="display: none; position: absolute; top: 100%; right: 0; width: 20rem;">
        <?php
          // Mostrar botón de panel de administración solo si el usuario es un administrador
          if(isset($_SESSION['is_admin']) && ($_SESSION['is_admin'] > 0)) {
            echo '<a href="/Viroco/admin/admin_panel.php">Panel de Administración</a>';
          }
        ?>
        <a href="/Viroco/app/cerrar_sesion.php">Cerrar sesión</a>
        </div>
        </div>
    </div>
  </div>
</nav>
<div id="subnav">
    <div>
      <a href="/Viroco/index.php" class="button_subnav">Inicio</a>
    </div>
    <div id="menuCategorias" class="menu">
      <a class="button_subnav">Categorías</a>
      <div class="menu-categorias" id="listaCategorias">
        <?php $categorias = obtenerCategorias($conexion); ?>
        <?php foreach ($categorias as $categoria) : ?>
          <div class="categoria-principal">
            <p>
              <a href="/Viroco/app/tienda.php?categoria=<?php echo $categoria['id']; ?>">
                <?php echo $categoria['nombre']; ?>
              </a>
            </p>
            <ul class="subcategorias">
              <?php foreach ($categoria['subcategorias'] as $subcategoria) : ?>
                <li>
                  <a href="/Viroco/app/tienda.php?categoria=<?php echo $categoria['id']; ?>&subcategoria=<?php echo $subcategoria['id']; ?>">
                    <?php echo $subcategoria['nombre']; ?>
                  </a>
                  <?php if (!empty($subcategoria['productos'])): ?>
                    <ul class="productos">
                      <?php foreach ($subcategoria['productos'] as $producto) : ?>
                        <li><?php echo $producto['nombre']; ?></li>
                      <?php endforeach; ?>
                    </ul>
                  <?php endif; ?>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
</div>
</div>
<script>
  const menuCategorias = document.querySelector("#menuCategorias");
  const listaCategorias = document.querySelector("#listaCategorias");

  // muestra el menu de categorias al pasar el mouse por encima
  menuCategorias.addEventListener("mouseover", function() {
    listaCategorias.style.display = "flex";
  });

  // cierra el menu de categorias si el mouse se mueve fuera de él
  menuCategorias.addEventListener("mouseleave", function() {
    listaCategorias.style.display = "none";
  });

  // evita que el menu se cierre si el mouse esta dentro de el
  listaCategorias.addEventListener("mouseover", function() {
    listaCategorias.style.display = "flex";
  });

  // cierra el menu si el mouse da clic en cualquier parte dentro del documento
  document.addEventListener("click", function(e) {
    if (!menuCategorias.contains(e.target)) {
      listaCategorias.style.display = "none";
    }
  });

  // cierra el menu si el mouse se mueve fuera de la lista de categorias
  listaCategorias.addEventListener("mouseleave", function() {
    listaCategorias.style.display = "none";
  });

  // Obtener el elemento que contiene el nombre de usuario
  var usernameLink = document.querySelector('.username');

  // Obtener el cuadro de opciones
  var optionsBox = document.querySelector('#menu-usuario');

  // Mostrar el cuadro de opciones al pasar el mouse por encima del nombre de usuario
  usernameLink.addEventListener('mouseover', function() {
    optionsBox.style.display = 'block';
  });

  // Ocultar el cuadro de opciones al mover el mouse fuera del mismo
  optionsBox.addEventListener('mouseleave', function() {
    optionsBox.style.display = 'none';
  });

</script>