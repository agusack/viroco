<?php
session_start();

require('bd.php');
require('funciones.php');

$id = $_GET['id'];

// Obtener la información del producto de la base de datos
$consulta = "SELECT * FROM productos WHERE id = $id";
$resultado = $conexion->query($consulta);
$producto = $resultado->fetch_assoc();

$sql = "SELECT * FROM `caracteristicas` WHERE `id_producto` = $id";
$resultado = $conexion->query($sql);

// Array para almacenar las opciones
$opciones = array();

// Recorrer cada fila del resultado de la consulta
while ($fila = $resultado->fetch_assoc()) {
    $atributo = $fila['nombre'];
    $valor = $fila['valor'];

    // Si el atributo aún no ha sido agregado al array de opciones, agregarlo con un array vacío
    if (!isset($opciones[$atributo])) {
        $opciones[$atributo] = array();
    }

    // Agregar el valor al array de opciones
    $opciones[$atributo][] = $valor;
}

// Obtener las imágenes del producto de la base de datos
$imagenes = array();
if ($producto["imagen"]) {
    $imagenes[] = $producto["imagen"];
}
if ($producto["imagen2"]) {
    $imagenes[] = $producto["imagen2"];
}
if ($producto["imagen3"]) {
    $imagenes[] = $producto["imagen3"];
}
if ($producto["imagen4"]) {
    $imagenes[] = $producto["imagen4"];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $producto['nombre']; ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/9ab94acfdc.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../estilos/estilo_producto_detallado.css">
    <link rel="stylesheet" href="../estilos/estilo_navbar.css">
    <link rel="stylesheet" href="../estilos/estilo_index.css">
    <link rel="stylesheet" href="../estilos/estilo_footer.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    <main>
        <section class="producto-detallado">
            <div class="container">
                <div class="row">
                    <div class="galeria">
                        <div class="img-miniaturas">
                            <?php foreach ($imagenes as $imagen) { ?>
                                <img src="<?php echo $imagen ?>" alt="<?php echo $producto['nombre']; ?>" class="img-miniatura" onmouseover="document.getElementById('img-principal').src = '<?php echo $imagen ?>';" onclick="document.getElementById('img-principal').src = '<?php echo $imagen ?>';">
                            <?php } ?>
                        </div>
                    </div>
                    <div class="imagen"><img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" class="img-principal" id="img-principal"></div>
                    <div class="informacion">
                        <?php 
                            echo "<h1>".$producto['nombre']."</h1>";
                            echo "<h3 class='precio'>Precio: $".$producto['precio']."</h3>";
                            // Si no hay ninguna opción para mostrar, mostrar un mensaje indicando que no hay stock
                            if (empty($opciones) && ($producto['stock'] < 1)) {
                            echo "<p>Lo siento, este producto no está disponible en este momento.</P>";
                            }
                            else {
                            // Mostrar los inputs y opciones
                            foreach ($opciones as $atributo => $valores) {
                              echo "<div class='form-group'>";
                              echo "<label for='$atributo'>$atributo: </label>";
                              echo "<select id='$atributo' name='caracteristica_$atributo' required>";
                              echo "<option value=''>Seleccione una opción</option>";
                              
                              foreach ($valores as $valor) {
                                  echo "<option value='$valor'>$valor</option>";
                              }
                              
                              echo "</select>";
                              echo "</div>";
                            }  
                        ?>

                                <div class="form-group cantidad-container">
                                <label for="cantidad">Cantidad: </label>
                                <div class="quantity">
                                    <button class="minus-btn"><i class="fas fa-minus"></i></button>
                                    <input class="quantity-input" type="number" name="cantidad" value="1" min="1">
                                    <button class="plus-btn"><i class="fas fa-plus"></i></button>
                                </div>
                                </div>

                                <?php
                                echo "<a class=\"agregar-carrito\" id=\"agregar-carrito-btn\" data-id=\"".$producto['id']."\"><div class='card-button'>";
                                echo "<svg class='svg-icon' viewBox='0 0 20 20'>";
                                echo "<path d='M17.72,5.011H8.026c-0.271,0-0.49,0.219-0.49,0.489c0,0.271,0.219,0.489,0.49,0.489h8.962l-1.979,4.773H6.763L4.935,5.343C4.926,5.316,4.897,5.309,4.884,5.286c-0.011-0.024,0-0.051-0.017-0.074C4.833,5.166,4.025,4.081,2.33,3.908C2.068,3.883,1.822,4.075,1.795,4.344C1.767,4.612,1.962,4.853,2.231,4.88c1.143,0.118,1.703,0.738,1.808,0.866l1.91,5.661c0.066,0.199,0.252,0.333,0.463,0.333h8.924c0.116,0,0.22-0.053,0.308-0.128c0.027-0.023,0.042-0.048,0.063-0.076c0.026-0.034,0.063-0.058,0.08-0.099l2.384-5.75c0.062-0.151,0.046-0.323-0.045-0.458C18.036,5.092,17.883,5.011,17.72,5.011z'></path>";
                                echo "<path d='M8.251,12.386c-1.023,0-1.856,0.834-1.856,1.856s0.833,1.853,1.856,1.853c1.021,0,1.853-0.83,1.853-1.853S9.273,12.386,8.251,12.386z M8.251,15.116c-0.484,0-0.877-0.393-0.877-0.874c0-0.484,0.394-0.878,0.877-0.878c0.482,0,0.875,0.394,0.875,0.878C9.126,14.724,8.733,15.116,8.251,15.116z'></path>";
                                echo "<path d='M13.972,12.386c-1.022,0-1.855,0.834-1.855,1.856s0.833,1.853,1.855,1.853s1.854-0.83,1.854-1.853S14.994,12.386,13.972,12.386z M13.972,15.116c-0.484,0-0.878-0.393-0.878-0.874c0-0.484,0.394-0.878,0.878-0.878c0.482,0,0.875,0.394,0.875,0.878C14.847,14.724,14.454,15.116,13.972,15.116z'></path>";
                                echo "</svg>";
                                echo "Agregar al carrito</div></a>";
                                echo "<div id='stock'></div>";
                            }
                            echo "<p>Descripción: ".$producto['descripcion']."</p>";
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include('footer.php'); ?>
<script>
    $(document).ready(function() {

      // Función para realizar la solicitud AJAX y obtener el stock disponible
      function obtenerStockDisponible(combinacion_seleccionada) {
        $.ajax({
          url: 'consultar_stock.php',
          method: 'POST',
          data: {
              producto_id: <?php echo $producto['id']; ?>,
              combinacion_seleccionada: combinacion_seleccionada
            },
          dataType: 'json',
          success: function(response) {
            var stock_disponible = response.stock_disponible !== undefined ? response.stock_disponible : 0;
            console.log("Stock disponible:", stock_disponible);
            var info_stock = $('#stock');
                      info_stock.html('<p>Tenemos en stock: ' + stock_disponible + '</p>');
                      info_stock.css({
                        'display': 'flex',
                        'justify-content': 'center',
                        'align-items': 'center'
                      })
            if (stock_disponible > 0) {
                var boton = $('.card-button');
                boton.css({
                  'background-color': '#7fd4dd',
                  'color': '#255a66',
                  'cursor': 'pointer',
                  'pointer-events': 'visible'
                });
                // Evento de clic en el botón "Agregar al carrito"
                $('#agregar-carrito-btn').click(function(e) {
                  e.preventDefault(); // Evita que el enlace recargue la página

                  // Obtener la cantidad solicitada por el usuario
                  var cantidadSolicitada = parseInt($('.quantity-input').val());

                  // Obtener las características seleccionadas por el usuario del formulario
                  var caracteristicas_elegidas = {};
                  $('.form-group select').each(function() {
                    var atributo = $(this).attr('id');
                    var valor = $(this).val();
                    if (valor) {
                      caracteristicas_elegidas[atributo] = valor;
                    }
                  });

                  // Armar la combinación seleccionada por el usuario
                  var combinacion_seleccionada = Object.values(caracteristicas_elegidas).join(' - ');

                  // Realizar la petición AJAX para verificar el stock
                  $.ajax({
                    url: 'verificar_stock.php', // Archivo PHP que realizará la verificación de stock
                    method: 'POST',
                    data: {
                      producto_id: <?php echo $producto['id']; ?>,
                      combinacion_unica: combinacion_seleccionada
                    },
                    dataType: 'json',
                    success: function(response) {
                      if (cantidadSolicitada <= response.stock_disponible) {
                        // Aquí puedes agregar el código para agregar el producto al carrito de compras
                        // Construir la URL para agregar el producto al carrito
                        var idProducto = <?php echo $producto['id']; ?>;
                        var nombreProducto = "<?php echo $producto['nombre']; ?>";
                        var precioProducto = "<?php echo $producto['precio']; ?>";
                        var rutaImagen = "<?php echo $producto['imagen']; ?>";
                        var cantidad = cantidadSolicitada;
                        var caracteristicasQuery = '';
                        for (var atributo in caracteristicas_elegidas) {
                          caracteristicasQuery += `&caracteristica_${encodeURIComponent(atributo)}=${encodeURIComponent(caracteristicas_elegidas[atributo])}`;
                        }
                        var url = `?id=${idProducto}&agregar=${idProducto}&nombre=${encodeURIComponent(nombreProducto)}&precio=${precioProducto}&ruta_imagen=${encodeURIComponent(rutaImagen)}&cantidad=${cantidad}${caracteristicasQuery}`;

                        $.ajax({
                          url: 'modificar_stock.php',
                          method: 'POST',
                          data: {
                            producto_id: <?php echo $producto['id']; ?>,
                            combinacion_unica: combinacion_seleccionada,
                            cantidad: cantidadSolicitada
                          },
                          dataType: 'json',
                          success: function(response) {
                            console.log('Stock modificado en la base de datos:', response);
                          },
                          error: function() {
                            console.log('Error al modificar el stock en la base de datos.');
                          }
                        });

                        // Redirigir al usuario a la URL para agregar el producto al carrito
                        window.location.href = url;
                        console.log("agregado al carrito");
                      } else {
                        alert('Lo sentimos, no hay suficiente stock disponible. Tenemos en stock: ' + response.stock_disponible);
                      }
                    },
                    error: function() {
                      alert('Ha ocurrido un error al verificar el stock.');
                    }
                  });
                  console.log(combinacion_seleccionada);
                });
            } else {
              console.log('no funciona boton');
                var boton = $('.card-button');
                // Cambia el estilo del botón
                boton.css({
                  'background-color': '#ccc',
                  'color': '#888',
                  'cursor': 'not-allowed',
                  'pointer-events': 'none'
                });
            }
          },
          error: function(xhr, status, error) {
            console.error("Error al obtener el stock:", error);
            // Manejar el error en caso de que la solicitud falle.
          }
        });
      }

      var caracteristicas_elegidas = {};
      // Función para actualizar la combinación seleccionada
      function actualizarCombinacionSeleccionada() {
        var combinacion_seleccionada = Object.values(caracteristicas_elegidas).join(' - ');
        console.log("Combinación seleccionada: ", combinacion_seleccionada);
        return combinacion_seleccionada;
      }

      // Función para manejar el evento 'change' en los selects (igual que antes)
      function onSelectChange(event) {
        var select = event.target;
        var atributo = select.id;
        var valor = select.value;
        if (valor) {
          caracteristicas_elegidas[atributo] = valor;
        } else {
          delete caracteristicas_elegidas[atributo];
        }
        var combinacion_seleccionada = actualizarCombinacionSeleccionada();
        obtenerStockDisponible(combinacion_seleccionada); // Llamar a la función para obtener el stock después de cada cambio
      }

      // Agregar el evento 'change' a los selects con la clase 'form-group' (igual que antes)
      var selects = document.querySelectorAll('.form-group select');
      selects.forEach(function(select) {
        select.addEventListener('change', onSelectChange);
      });

    });

      const quantityInput = document.querySelector(".quantity-input");
      const plusButton = document.querySelector(".plus-btn");
      const minusButton = document.querySelector(".minus-btn");

      plusButton.addEventListener("click", () => {
        quantityInput.value = parseInt(quantityInput.value) + 1;
      });

      minusButton.addEventListener("click", () => {
        if (quantityInput.value > 1) {
          quantityInput.value = parseInt(quantityInput.value) - 1;
        }
      });

              // Verificar si la notificación está presente en la sesión
              const sessionNotification = "<?php echo isset($_SESSION['notificacion']) ? $_SESSION['notificacion'] : '' ?>";
        if (sessionNotification !== "") {
            // Esperar a que el contenido de la página se haya cargado completamente
            document.addEventListener("DOMContentLoaded", function() {
            // Obtener la referencia al elemento de notificación
            const notification = document.querySelector(".notification");

            // Mostrar la notificación después de 1 segundo
            setTimeout(function() {
                notification.style.display = "block";
            }, 1000);

            // Ocultar la notificación después de 5 segundos
            setTimeout(function() {
                notification.style.display = "none";
                // Llamar a un archivo PHP para borrar la variable de sesión
                $.ajax({
                    url: "eliminar_notificacion.php",
                    type: "POST",
                    success: function(response) {
                        console.log("Notificación borrada");
                    }
                });
            }, 5000);
            });
        }
</script>
</body>
</html>
<?php
// Verificar si existe una notificación almacenada en la variable de sesión
if (isset($_SESSION['notificacion'])) {

    // Mostrar la notificación en una ventanita flotante
    echo '<div class="notification">' . $_SESSION['notificacion'] . '</div>';
}
?>
