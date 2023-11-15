<?php

  session_start();

  if (!isset($_SESSION['username'])) {
    echo'<script type="text/javascript">
    alert("Debes iniciar sesion para poder proceder al pago.");
    window.location.href="iniciar_sesion.php";
    </script>';
    exit;
  }

  require('bd.php');
  require('funciones.php');
  function mostrarProductosCarrito() {
    if (!isset($_SESSION['carrito'])) {
      echo "<p>No hay productos en el carrito</p>";
    } else {
      $subtotal = 0;
      echo "<table class=\"tabla-carrito\">";
      echo "<thead><tr><th>Producto</th><th>Cantidad</th><th>Total</th></tr></thead>";
      echo "<tbody>";
  
      foreach ($_SESSION['carrito'] as $id => $producto) {
        $subtotal += $producto['precio'] * $producto['cantidad'];  
        echo "<tr>";
        echo "<td><img src=\"" . $producto['imagen'] . "\" alt=\"" . $producto['nombre'] . "\"></td>";
        echo "<td>" . $producto['cantidad'] . "</td>";        
        echo "<td>$" . $producto['precio'] * $producto['cantidad'] . "</td>";
        echo "</tr>";
      }
  
      echo "<tr>";
      echo "<td>Total</td>";       
      echo "<td colspan='2' class='.total_tabla'>$" . number_format($subtotal, 2) . "</td>";        
      echo "</tr>";
  
      echo "</tbody>";
      echo "</table>"; 
    }
  }

  $total = 0;
  foreach ($_SESSION['carrito'] as $id => $producto) {$total += $producto['precio'] * $producto['cantidad'];}
  $_SESSION['total'] = $total;  
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
  <link rel="stylesheet" href="../estilos/estilo_checkout.css">
  <link rel="stylesheet" href="../estilos/estilo_navbar.css">
  <link rel="stylesheet" href="../estilos/estilo_footer.css">
</head>
<body>
  <?php include('navbar.php'); ?>
  <div class="page">
    <div class="pasarela">
    <h2>Pago:</h2>
      <div id="deliveryMethodContainer">
        <h2>Seleccionar método de entrega</h2>
        <form id="checkoutForm">
          <input type="radio" name="deliveryMethod" value="local"> Retirar por el local<br>
          <div id="localFormContainer" style="display: none;">
            <h2>Datos para retiro en el local</h2>
            <label for="nombreLocal">Nombre Completo:</label>
            <input type="text" id="nombreLocal" name="nombreLocal" autocomplete="off"><br>

            <label for="dniLocal">DNI:</label>
            <input type="number" id="dniLocal" name="dniLocal" autocomplete="off"><br>
          </div>
          <input type="radio" name="deliveryMethod" value="domicilio" id="deliveryDomicilio"> Entrega a domicilio<br>
          <div id="domicilioFormContainer" style="display: none;">
            <h2>Datos para entrega a domicilio</h2>
            <label for="nombreDomicilio">Nombre Completo:</label>
            <input type="text" id="nombreDomicilio" name="nombreDomicilio" autocomplete="off"><br>

            <label for="telefonoDomicilio">Telefono:</label>
            <input type="number" id="telefonoDomicilio" name="telefonoDomicilio" autocomplete="off"><br>

            <label for="ciudad">Ciudad:</label>
            <input type="text" id="ciudad" name="ciudad" autocomplete="off"><br>
            
            <label for="calle">Calle:</label>
            <input type="text" id="calle" name="calle" autocomplete="off"><br>

            <label for="altura">Altura:</label>
            <input type="number" id="altura" name="altura" autocomplete="off"><br>

            <label for="depto">Departamento (opcional):</label>
            <input type="text" id="depto" name="depto" autocomplete="off"><br>
            
            <label for="postalCode">Código Postal:</label>
            <input type="number" id="postalCode" name="postalCode" autocomplete="off"><br>
          </div>
          <button type="button" onclick="handleDeliveryMethod()">Continuar</button>
      </div>
      <div id="paymentMethodContainer" style="display: none;">
        <h2>Seleccionar método de pago</h2>
          <input type="radio" name="paymentMethod" value="efectivo" onclick="showPaymentForm('efectivoFormContainer')"> Efectivo<br>
          <div id="efectivoFormContainer" style="display: none;">
            <h2>¡Terminaremos la compra por Whatsapp!</h2>
            <p>Te enviaremos un mensaje, por favor déjanos tu número</p>
            <label for="celularEfectivo">Nro de celular:</label>
            <input type="number" id="celularEfectivo" name="celularEfectivo" autocomplete="off"><br>
          </div>
          <input type="radio" name="paymentMethod" value="transferencia" onclick="showPaymentForm('cbuFormContainer')"> Transferencia<br>
          <div id="cbuFormContainer" style="display: none;">
            <h2>¡Terminaremos la compra por Whatsapp!</h2>
            <p>Te enviaremos un mensaje con nuestros datos para la transferencia. Por favor, déjanos tu número</p>
            <label for="celularTransferencia">Nro de celular:</label>
            <input type="number" id="celularTransferencia" name="celularTransferencia" autocomplete="off"><br>
          </div>
          <input type="radio" name="paymentMethod" value="debito_credito" onclick="showPaymentForm('tarjetaFormContainer')"> Debito/Crédito<br>
          <div id="tarjetaFormContainer" style="display: none;">
            <h2>¡Terminaremos la compra por Whatsapp!</h2>
            <p>Te enviaremos un link para que puedas abonar. Por favor, déjanos tu número</p>
            <label for="celularTarjeta">Nro de celular:</label>
            <input type="number" id="celularTarjeta" name="celularTarjeta" autocomplete="off"><br>
          </div>
          <button type="submit">Continuar</button>
        </form>
      </div>
      <div id="loader_container" class="loader">
        <div class="justify-content-center jimu-primary-loading"></div>
      </div>
      <div id="pago_procesado"></div>
    </div>
    <div class="carrito">
      <h2>Su compra:</h2>
      <?php MostrarProductosCarrito(); ?>
    </div>
  </div>
  <?php include('footer.php'); ?>
  <script>

const checkboxDomicilio = document.getElementById("deliveryDomicilio");
checkboxDomicilio.addEventListener("click", handleCheckboxChange);

function handleCheckboxChange(event) {
  if (event.target.checked) {
    document.getElementById('domicilioFormContainer').style.display = 'block';
    document.getElementById('localFormContainer').style.display = 'none';
  }
}

const checkboxLocal = document.querySelector('input[value="local"]');
checkboxLocal.addEventListener("change", handleLocalCheckboxChange);

function handleLocalCheckboxChange(event) {
  if (event.target.checked) {
    document.getElementById('domicilioFormContainer').style.display = 'none';
    document.getElementById('localFormContainer').style.display = 'block';
  }
}

function handleDeliveryMethod() {
    const checkboxDomicilio = document.getElementById("deliveryDomicilio");
    const deliveryMethodContainer = document.getElementById('deliveryMethodContainer');
    const paymentMethodContainer = document.getElementById('paymentMethodContainer');

    const opcionesEntrega = document.querySelectorAll('input[type="radio"][name="deliveryMethod"]');
    let alMenosUnaSeleccionada = false;

    opcionesEntrega.forEach(function (opcion) {
        if (opcion.checked) {
            alMenosUnaSeleccionada = true;
        }
    });

    if (alMenosUnaSeleccionada) {
        if (checkboxDomicilio.checked) {
            // Verificar campos de entrega a domicilio
            const nombreDomicilio = document.getElementById('nombreDomicilio').value.trim();
            const telefonoDomicilio = document.getElementById('telefonoDomicilio').value.trim();
            
            // Agregar verificaciones para los otros campos del formulario de entrega a domicilio
            const ciudad = document.getElementById('ciudad').value.trim();
            const calle = document.getElementById('calle').value.trim();
            const altura = document.getElementById('altura').value.trim();
            const postalCode = document.getElementById('postalCode').value.trim();

            if (
                nombreDomicilio === "" ||
                telefonoDomicilio === "" ||
                ciudad === "" ||
                calle === "" ||
                altura === "" ||
                postalCode === ""
            ) {
                alert("Por favor, complete todos los campos obligatorios para la entrega a domicilio.");
            } else {
                document.getElementById('domicilioFormContainer').style.display = 'block';
                deliveryMethodContainer.style.display = 'none';
                paymentMethodContainer.style.display = 'block'; // Mostrar el formulario de método de pago
            }
        } else {
            // Verificar campos de retiro por el local
            const nombreLocal = document.getElementById('nombreLocal').value.trim();
            const dniLocal = document.getElementById('dniLocal').value.trim();

            if (nombreLocal === "" || dniLocal === "") {
                alert("Por favor, complete todos los campos obligatorios para el retiro en el local.");
            } else {
                document.getElementById('localFormContainer').style.display = 'block';
                deliveryMethodContainer.style.display = 'none';
                paymentMethodContainer.style.display = 'block'; // Mostrar el formulario de método de pago
            }
        }
    } else {
        alert("Por favor, seleccione al menos una opción de entrega.");
    }
}


function showPaymentForm(formId) {
  // Oculta todos los formularios de método de pago
  document.getElementById('efectivoFormContainer').style.display = 'none';
  document.getElementById('cbuFormContainer').style.display = 'none';
  document.getElementById('tarjetaFormContainer').style.display = 'none';

  // Muestra el formulario de método de pago seleccionado
  document.getElementById(formId).style.display = 'block';
}

document.getElementById('checkoutForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Validación de método de pago y número de celular
    const opcionesPago = document.querySelectorAll('input[type="radio"][name="paymentMethod"]');
    let alMenosUnaSeleccionada = false;

    opcionesPago.forEach(function (opcion) {
        if (opcion.checked) {
            alMenosUnaSeleccionada = true;
        }
    });

    if (alMenosUnaSeleccionada) {
        const selectedOption = document.querySelector('input[type="radio"][name="paymentMethod"]:checked').value;
        const celularInput = document.getElementById(`celular${selectedOption.charAt(0).toUpperCase() + selectedOption.slice(1)}`);

        if (celularInput.value.trim() === "") {
            alert("Por favor, completa el campo de número de celular correspondiente.");
            return; // Detener el envío del formulario si falta el número de celular
        }
    } else {
        alert("Por favor, selecciona un método de pago.");
        return; // Detener el envío del formulario si no se ha seleccionado un método de pago
    }

    // Si la validación es exitosa, continuar con el envío del formulario
    fetch('../app/pedido.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Muestra la respuesta en la consola
        if (data === 'ok') {
          document.getElementById('paymentMethodContainer').style.display = 'none'; 
          document.getElementById('pago_procesado').style.display = 'flex';
          document.getElementById('pago_procesado').innerHTML = '<p><i class="fa-solid fa-circle-check" style="color: #03b800;"></i></p><br><p>!Gracias, nos pondremos en contacto con vos!</p><br><a href="tienda.php?vaciar"><button>Volver a la tienda</button></a>';   
        } else {
          document.getElementById('paymentMethodContainer').style.display = 'none';
          document.getElementById('pago_procesado').style.display = 'flex'; 
          document.getElementById('pago_procesado').innerHTML = '<p><i class="fa-solid fa-circle-xmark" style="color: #cc0000;"></i></p><br><p>Hubo un problema, por favor intente de nuevo.</p><br><a href="carrito.php"><button>Aceptar</button></a>';
        }
    })
    .catch(error => {
        console.error('Error de red:', error);
    });
});
</script>
</body>
</html>