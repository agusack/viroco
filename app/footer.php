<footer>
  <section id="conteiner_footer">
  <div class="nosotros">
    <p class="titulo">NOSOTROS</p>
    <p class="nos_detalles"><i class="fa-solid fa-location-dot"></i> San Martín 456, Río Grande</p>
    <p class="nos_detalles"><i class="fa-brands fa-whatsapp"></i> 2964-454699</p>
    <p class="nos_detalles"><i class="fa-regular fa-clock"></i> 10hs a 12hs - 16hs a 20hs</p>
  </div>
  <div class="medio">
    <div>
      <img src="/Viroco/img/logos/clover.png" class="ajuste_img4" alt="Clover">
    </div>
  </div>
  <div class="categorias">
    <p class="titulo">CATEGORÍAS</p>
    <div>
    <?php
      include('bd.php');
      $categorias = obtenerCategorias($conexion);
      $contador = 0;
      ?>

      <?php foreach ($categorias as $categoria) : ?>
        <?php if ($contador === 0) { ?>
          <ul>
        <?php } ?>
        <li>
          <a href="/Viroco/app/tienda.php?categoria=<?php echo $categoria['id']; ?>">
            <?php echo $categoria['nombre']; ?>
          </a>
        </li>
        <?php $contador++; ?>
        <?php if ($contador === 4) { ?>
          </ul>
          <?php $contador = 0; ?>
        <?php } ?>
      <?php endforeach; ?>

      <?php if ($contador !== 0) { ?>
        </ul>
      <?php } ?>
    </div>
    <div class="logo">
        <img src="/Viroco/img/logos/viroco.png" alt="Viroco Regalería">
    </div>
  </div>
  </section>
  <div id="copy">
    <p>Hecho con <i class="fa-solid fa-heart fa-beat" style="color: #FCC9BD;"></i> por <a href="https://www.instagram.com/ackagus/?hl=es-la" target="_blank" rel="noopener noreferrer" style="text-decoration: underline; color: #7fd4dd;">@ackagus</a></p>
  </div>
</footer>