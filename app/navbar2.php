<!-- Barra de navegación con la enlace al carrito de compras y el formulario de búsqueda -->
<?php

// Obtener todas las categorías y subcategorías de la base de datos
$sql = "SELECT c.*, s.*
        FROM categorias c
        LEFT JOIN (
            SELECT s.* 
            FROM subcategorias s
            JOIN (
                SELECT id_categoria 
                FROM categorias
            ) cte ON s.id_categoria = cte.id_categoria
        ) s ON c.id_categoria = s.id_categoria
        ORDER BY c.id_categoria, s.id_subcategoria";

$resultado_categorias = mysqli_query($conexion, $sql);

// Crear un array para almacenar las categorías y subcategorías agrupadas por id_categoria
$categorias = array();

while ($fila = mysqli_fetch_assoc($resultado_categorias)) {
    // Agregar la categoría al array si aún no existe
    if (!isset($categorias[$fila['id_categoria']])) {
        $categorias[$fila['id_categoria']] = array(
            'id_categoria' => $fila['id_categoria'],
            'nombre' => $fila['nombre_categoria'],
            'subcategorias' => array()
        );
    }

    // Agregar la subcategoría al array si aún no existe
    if ($fila['id_subcategoria'] && !isset($categorias[$fila['id_categoria']]['subcategorias'][$fila['id_subcategoria']])) {
        $categorias[$fila['id_categoria']]['subcategorias'][$fila['id_subcategoria']] = array(
            'id_subcategoria' => $fila['id_subcategoria'],
            'nombre' => $fila['nombre_subcategoria']
        );
    }
}

// Ordenar las subcategorías por id_subcategoria
foreach ($categorias as &$categoria) {
    usort($categoria['subcategorias'], function ($a, $b) {
        return $a['id_subcategoria'] - $b['id_subcategoria'];
    });
}

// Ordenar las categorías por id_categoria
ksort($categorias);
?>

<nav>
  <ul>
    <li><button type="button" id="boton-categorias">Categorías</button></li>
      <div id="cuadro_categorias" style="display:none;">
        <h2>Todas las categorías</h2>
        <ul id="lista_categorias">
          <!-- Las categorías y subcategorías se agregarán aquí con JavaScript -->
        </ul>
      </div>
    <li><a href="/Viroco/app/carrito.php">Carrito (<?php echo $cantidad_total; ?>)</a></li>
    <li>
      <form action="/Viroco/app/tienda.php" method="post">
        <label for="buscar">Buscar productos:</label>
        <input type="text" id="buscar" name="buscar">
        <button type="submit">Buscar</button>
      </form>
    </li>
    <li>
      <?php
        // Verificar si la sesión está iniciada
        if(isset($_SESSION['username'])) {
          // Mostrar mensaje personalizado con el nombre de usuario
          echo '<a href="#" class="username">Hola '.$_SESSION['username'].'</a>';
        } else {
          // Mostrar enlace "Iniciar sesión"
          echo '<a href="/Viroco/app/iniciar_sesion.php">Iniciar sesión</a>';
        }
      ?>
      <div id="menu-usuario">
        <?php
        // Mostrar botón de panel de administración solo si el usuario es un administrador
            if(isset($_SESSION['is_admin']) && ($_SESSION['is_admin'] > 0)) {
              echo '<a href="/Viroco/admin/admin_panel.php">Panel de Administración</a>';
            }
        ?>
        <a href="/Viroco/app/cerrar_sesion.php">Cerrar sesión</a>
      </div>
    </li>
  </ul>
</nav>
    

<script>
// Función para mostrar las categorías y subcategorías en el div "cuadro_categorias"
function mostrarCategorias() {
  // Obtener el div "cuadro_categorias"
  var cuadroCategorias = document.getElementById("cuadro_categorias");

  // Crear un nuevo elemento "ul" para la lista de categorías y subcategorías
  var listaCategorias = document.createElement("ul");

  // Agregar cada categoría y subcategoría al elemento "ul"
  <?php
  foreach ($categorias as $categoria) {
      echo "var liCategoria = document.createElement('li');\n";
      echo "liCategoria.textContent = '".$categoria['nombre']."';\n";
      echo "listaCategorias.appendChild(liCategoria);\n";

      foreach ($categoria['subcategorias'] as $subcategoria) {
          echo "var liSubcategoria = document.createElement('li');\n";
          echo "liSubcategoria.textContent = '".$subcategoria['nombre']."';\n";
          echo "liCategoria.appendChild(liSubcategoria);\n";
      }
  }

  ?>

  // Agregar la lista de categorías y subcategorías al div "cuadro_categorias"
  cuadroCategorias.appendChild(listaCategorias);

  // Mostrar el div "cuadro_categorias"
  cuadroCategorias.style.display = "block";
}

// Obtener el botón "Categorías"
var botonCategorias = document.getElementById("boton-categorias");

// Asignar la función de mostrar las categorías y subcategorías al evento "click" del botón "Categorías"
botonCategorias.addEventListener("click", mostrarCategorias);
</script>