<nav>
  <ul>
    <li><a href="/Viroco/app/tienda.php">Todos los productos</a></li>
    <li><a href="/Viroco/app/tienda.php?categoria=1">Moda</a></li>
    <li><a href="/Viroco/app/tienda.php?categoria=2">Tecnología</a></li>
    <li><a href="/Viroco/app/tienda.php?categoria=3">Hogar</a></li>
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