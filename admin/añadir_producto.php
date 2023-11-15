<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../app/login.php');
    exit();
}

require '../app/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_producto = $_POST['nombre_producto'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $subcategoria = $_POST['subcategoria'];
    $stock = $_POST['stockGeneral'];

    // Verifica si se ha seleccionado un archivo de imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["imagen"]["tmp_name"];
        $name = basename($_FILES["imagen"]["name"]);
        $target_dir = "/Viroco/img/productos/";
        $target_file = $target_dir . $name;

        // Mueve el archivo cargado a la carpeta de destino
        if (move_uploaded_file($tmp_name, $_SERVER['DOCUMENT_ROOT'] . $target_file)) {
            $imagen = $target_file;
        } else {
            $imagen = '';
        }
    } else {
        $imagen = '';
    }

    // Verifica si se ha seleccionado el segundo archivo de imagen
    if (isset($_FILES['imagen2']) && $_FILES['imagen2']['error'] === UPLOAD_ERR_OK) {
        $tmp_name2 = $_FILES["imagen2"]["tmp_name"];
        $name2 = basename($_FILES["imagen2"]["name"]);
        $target_file2 = $target_dir . $name2;

        // Mueve el archivo cargado a la carpeta de destino
        if (move_uploaded_file($tmp_name2, $_SERVER['DOCUMENT_ROOT'] . $target_file2)) {
            $imagen2 = $target_file2;
        } else {
            $imagen2 = '';
        }
    } else {
        $imagen2 = '';
    }

    // Verifica si se ha seleccionado el tercer archivo de imagen
    if (isset($_FILES['imagen3']) && $_FILES['imagen3']['error'] === UPLOAD_ERR_OK) {
        $tmp_name3 = $_FILES["imagen3"]["tmp_name"];
        $name3 = basename($_FILES["imagen3"]["name"]);
        $target_file3 = $target_dir . $name3;

        // Mueve el archivo cargado a la carpeta de destino
        if (move_uploaded_file($tmp_name3, $_SERVER['DOCUMENT_ROOT'] . $target_file3)) {
            $imagen3 = $target_file3;
        } else {
            $imagen3 = '';
        }
    } else {
        $imagen3 = '';
    }

    // Verifica si se ha seleccionado el cuarto archivo de imagen
    if (isset($_FILES['imagen4']) && $_FILES['imagen4']['error'] === UPLOAD_ERR_OK) {
        $tmp_name4 = $_FILES["imagen4"]["tmp_name"];
        $name4 = basename($_FILES["imagen4"]["name"]);
        $target_file4 = $target_dir . $name4;

        // Mueve el archivo cargado a la carpeta de destino
        if (move_uploaded_file($tmp_name4, $_SERVER['DOCUMENT_ROOT'] . $target_file4)) {
            $imagen4 = $target_file4;
        } else {
            $imagen4 = '';
        }
    } else {
        $imagen4 = '';
    }

    // Insertar nuevo producto en la base de datos
    $sql = "INSERT INTO productos (nombre, descripcion, precio, id_categoria, id_subcategoria, stock, imagen, imagen2, imagen3, imagen4) VALUES (:nombre, :descripcion, :precio, :categoria, :subcategoria, :stock, :imagen, :imagen2, :imagen3, :imagen4)";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre_producto,
        ':descripcion' => $descripcion,
        ':precio' => $precio,
        ':categoria' => $categoria,
        ':subcategoria' => $subcategoria,
        ':stock' => $stock,
        ':imagen' => $imagen,
        ':imagen2' => $imagen2,
        ':imagen3' => $imagen3,
        ':imagen4' => $imagen4
    ]);

    // Recuperar el ID del producto recién insertado
    $id_producto = $conexion->lastInsertId();

    // Agregar características al producto
    $caracteristicas = $_POST['caracteristicas'];
    foreach ($caracteristicas as $caracteristica) {
        $nombre = $caracteristica['nombre'];
        $valor = $caracteristica['valor'];

        $sql = "INSERT INTO caracteristicas (id_producto, nombre, valor) VALUES (:id_producto, :nombre, :valor)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':id_producto' => $id_producto,
            ':nombre' => $nombre,
            ':valor' => $valor
        ]);
    }

    // Agregar combinaciones de características y stock
    $combinaciones = $_POST['combinaciones'];
    foreach ($combinaciones as $combinacion) {
        $caracteristica_1 = $combinacion['caracteristica_1'];
        $caracteristica_2 = $combinacion['caracteristica_2'];
        $stock_combinacion = $combinacion['stock'];

        // Aquí generas la combinación única con los nombres y valores seleccionados
        $combinacion_unica = "$caracteristica_1 - $caracteristica_2";

        // Insertas la combinación en la tabla "combinaciones_producto"
        $sql_combinacion = "INSERT INTO combinaciones_producto (id_producto, combinacion_unica, stock) VALUES (:id_producto, :combinacion_unica, :stock)";
        $stmt_combinacion = $conexion->prepare($sql_combinacion);
        $stmt_combinacion->execute([
            ':id_producto' => $id_producto,
            ':combinacion_unica' => $combinacion_unica,
            ':stock' => $stock_combinacion,
        ]);
    }

    header('Location: productos_admin.php');
}

$sql_categorias = "SELECT * FROM categorias";
$resultado_categorias = $conexion->query($sql_categorias);

$sql_subcategorias = "SELECT * FROM subcategorias";
$resultado_subcategorias = $conexion->query($sql_subcategorias);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Añadir Producto - Tienda Online</title>
    <link rel="stylesheet" href="../estilos/estilo_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css">
    <script src="https://kit.fontawesome.com/9ab94acfdc.js" crossorigin="anonymous"></script>
</head>
<body>
<div id="wrapper">
        <?php
            include('navbar_admin.php')
        ?>
        <div id="main">
            <h2 style="text-decoration: underline; font-weight: bold;">Añadir Producto</h2>

            <form action="añadir_producto.php" method="POST" enctype="multipart/form-data">
                <label for="nombre_producto" style="font-weight: bold;">Nombre:</label>
                <input type="text" name="nombre_producto" id="nombre_producto" required><br>

                <label for="descripcion" style="font-weight: bold;">Descripción:</label>
                <textarea name="descripcion" id="descripcion" required></textarea><br>

                <label for="precio" style="font-weight: bold;">Precio:</label>
                <input type="number" step="0.01" name="precio" id="precio" required><br>

                <label for="categoria" style="font-weight: bold;">Categoría:</label>
                <select name="categoria" id="categoria" onchange="actualizarSubcategorias()" required>
                    <option value="">Selecciona una categoría</option>
                    <?php foreach ($resultado_categorias as $categoria) { ?>
                        <option value="<?php echo $categoria['id_categoria'] ?>"><?php echo $categoria['nombre_categoria'] ?></option>
                    <?php } ?>
                </select><br>

                <label for="subcategoria" style="font-weight: bold;">Subcategoría:</label>
                <select name="subcategoria" id="subcategoria" required>
                    <option value="">Selecciona una subcategoría</option>
                    <?php foreach ($resultado_subcategorias as $subcategoria) { ?>
                        <option value="<?php echo $subcategoria['id_subcategoria'] ?>" data-categoria="<?php echo $subcategoria['id_categoria'] ?>"><?php echo $subcategoria['nombre_subcategoria'] ?></option>
                    <?php } ?>
                </select><br>

                <label for="imagen" style="font-weight: bold;">Imagen 1:</label>
                <input type="file" name="imagen" id="imagen" accept="image/*" required><br>

                <label for="imagen2" style="font-weight: bold;">Imagen 2 (opcional):</label>
                <input type="file" name="imagen2" id="imagen2" accept="image/*"><br>

                <label for="imagen3" style="font-weight: bold;">Imagen 3 (opcional):</label>
                <input type="file" name="imagen3" id="imagen3" accept="image/*"><br>

                <label for="imagen4" style="font-weight: bold;">Imagen 4 (opcional):</label>
                <input type="file" name="imagen4" id="imagen4" accept="image/*"><br>

                <h3 style="margin-bottom: 5px; font-weight: bold;">Características:</h3>
                <div id="contenedorCaracteristicas">
                    <!-- Input para agregar nuevas características -->
                    <span class="agregar" onclick="agregarCampoCaracteristica()" style="width: 50px; height: 50px; color: #fff; background-color: #45a049; cursor: pointer; padding: 5px; border: 1px solid #ccc;"><i class="fas fa-plus"></i> Agregar característica</span>

                    <!-- Campos de características -->
                    <div class="campoCaracteristica">
                        <!-- Aqui borre los inputs -->
                    </div>
                </div>

                <h3 style="margin-top: 20px; margin-bottom: 5px; font-weight: bold;">Combinaciones de características:</h3>
                <div id="contenedorCombinaciones">
                    <span class="agregar" onclick="agregarCampoCombinacion()" style="width: 50px; height: 50px; color: #fff; background-color: #45a049; cursor: pointer; padding: 5px; border: 1px solid #ccc;"><i class="fas fa-plus"></i> Agregar combinación</span>

                    <!-- Campos de combinaciones -->
                    <div class="campoCombinacion">
                    </div>
                </div>

                <!-- Botón para agregar stock general -->
                <h3 style="margin-top: 20px; margin-bottom: 5px; font-weight: bold;">Agregar stock general:</h3>
                <span class="agregar" id="agregarStockGeneral" onclick="mostrarInputStockGeneral()" style="width: 50px; height: 50px; color: #fff; background-color: #45a049; cursor: pointer; padding: 5px; border: 1px solid #ccc;"><i class="fas fa-plus"></i> Agregar stock general</span>

                <!-- Input de stock general oculto por defecto -->
                <div id="contenedorStockGeneral" style="display: none;">
                    <label for="stockGeneral" style="font-weight: bold;">Stock General:</label>
                    <input type="number" name="stockGeneral" id="stockGeneral">
                </div><br>

                <input type="submit" value="Añadir" style="margin-top: 20px;">
            </form>
        </div>

    </div>
</body>
<script>
    function actualizarSubcategorias() {
    // Obtener el valor seleccionado de la categoría
    const categoriaSeleccionada = document.getElementById("categoria").value;

    // Obtener todas las opciones de subcategoría
    const opcionesSubcategoria = document.querySelectorAll("#subcategoria option");

    // Recorrer las opciones de subcategoría y mostrar solo las que corresponden a la categoría seleccionada
    opcionesSubcategoria.forEach(opcion => {
        const categoriaDeLaOpcion = opcion.dataset.categoria;
        if (categoriaDeLaOpcion === categoriaSeleccionada || categoriaSeleccionada === "0") {
        opcion.style.display = "block";
        } else {
        opcion.style.display = "none";
        }
    });
    }

    let contadorCaracteristicas = 1;
    let contadorCombinaciones = 1;
    let caracteristicasIngresadas = []; // Arreglo para almacenar las características ingresadas

    function agregarCampoCaracteristica() {
        const contenedor = document.getElementById('contenedorCaracteristicas');
        const nuevoCampo = document.createElement('div');
        nuevoCampo.className = 'campoCaracteristica';
        nuevoCampo.innerHTML = `
            <input type="text" name="caracteristicas[${contadorCaracteristicas}][nombre]" placeholder="Nombre" required>
            <input type="text" name="caracteristicas[${contadorCaracteristicas}][valor]" placeholder="Valor" required>
            <span class="eliminar" onclick="eliminarCampoCaracteristica(this)" style="width: 50px; height: 50px; color: #fff; background-color: #cc0000; cursor: pointer; padding: 5px; border: 1px solid #ccc;"><i class="fas fa-trash"></i> Eliminas caracteristica</span>
        `;
        contenedor.appendChild(nuevoCampo);

        // Al agregar una nueva característica, almacenarla en el arreglo de características
        caracteristicasIngresadas.push({
            nombre: `Característica ${contadorCaracteristicas}`,
            valor: `caracteristica_${contadorCaracteristicas}`
        });

        contadorCaracteristicas++;
    }

    function eliminarCampoCaracteristica(el) {
        const campo = el.parentNode;
        campo.parentNode.removeChild(campo);
    }

    function agregarCampoCombinacion() {
        const contenedor = document.getElementById('contenedorCombinaciones');
        const nuevoCampo = document.createElement('div');
        nuevoCampo.className = 'campoCombinacion';
        nuevoCampo.innerHTML = `
            <select name="combinaciones[${contadorCombinaciones}][caracteristica_1]" required>
                <option value="" disabled selected>Seleccionar característica 1</option>
                ${generarOpcionesCaracteristicas(1)}
            </select>
            <select name="combinaciones[${contadorCombinaciones}][caracteristica_2]" required>
                <option value="" disabled selected>Seleccionar característica 2</option>
                ${generarOpcionesCaracteristicas(2)}
            </select>
            <input type="number" name="combinaciones[${contadorCombinaciones}][stock]" placeholder="Stock" required>
            <span class="eliminar" onclick="eliminarCampoCombinacion(this)" style="width: 50px; height: 50px; color: #fff; background-color: #cc0000; cursor: pointer; padding: 5px; border: 1px solid #ccc;"><i class="fas fa-trash"></i> Eliminar caracteristica</span>
        `;
        contenedor.appendChild(nuevoCampo);
        contadorCombinaciones++;
    }

    function eliminarCampoCombinacion(el) {
        const campo = el.parentNode;
        campo.parentNode.removeChild(campo);
    }

    // Función para generar las opciones de características dinámicamente
    function generarOpcionesCaracteristicas(numCaracteristica) {
        let opciones = '';
        const camposCaracteristica = document.querySelectorAll('.campoCaracteristica');
        const caracteristicasAgrupadas = {};

        camposCaracteristica.forEach((campo, index) => {
            const nombreInput = `caracteristicas[${index}][nombre]`;
            const valorInput = `caracteristicas[${index}][valor]`;
            const inputNombre = campo.querySelector(`input[name="${nombreInput}"]`);
            const inputValor = campo.querySelector(`input[name="${valorInput}"]`);

            if (inputNombre && inputValor) {
                const nombre = inputNombre.value;
                const valor = inputValor.value;

                if (!caracteristicasAgrupadas[nombre]) {
                    caracteristicasAgrupadas[nombre] = [];
                }

                caracteristicasAgrupadas[nombre].push(valor);
            }
        });

        for (const nombreCaracteristica in caracteristicasAgrupadas) {
            opciones += `<optgroup label="${nombreCaracteristica}">`;

            caracteristicasAgrupadas[nombreCaracteristica].forEach(valorCaracteristica => {
                opciones += `<option value="${valorCaracteristica}">${valorCaracteristica}</option>`;
            });

            opciones += `</optgroup>`;
        }

        // Filtrar las opciones según el número de característica
        opciones = opciones.replace(new RegExp(`(disabled selected>Seleccionar característica ${numCaracteristica})`), `$1</option>`);

        return opciones;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Agrega un evento 'change' al contenedor de combinaciones
        const contenedorCombinaciones = document.getElementById('contenedorCombinaciones');
        contenedorCombinaciones.addEventListener('change', function(event) {
            if (event.target && event.target.name && event.target.name.endsWith('[stock]')) {
                // Si el evento proviene de un campo de entrada de stock, actualiza el stock general
                actualizarStockGeneral();
            }
        });
    });


    function mostrarInputStockGeneral() {
        const contenedorStockGeneral = document.getElementById('contenedorStockGeneral');
        contenedorStockGeneral.style.display = 'block';

        // Ocultar el botón "Agregar stock general" después de mostrar el input
        const botonAgregarStockGeneral = document.getElementById('agregarStockGeneral');
        botonAgregarStockGeneral.style.display = 'none';
    }

    function actualizarStockGeneral() {
        const stockCombinaciones = document.querySelectorAll('input[name^="combinaciones["][name$="[stock]"]');
        let stockTotal = 0;

        stockCombinaciones.forEach(inputStock => {
            const stock = parseInt(inputStock.value);
            if (!isNaN(stock)) {
                stockTotal += stock;
            }
        });

        // Actualizar el campo de stock general
        const inputStockGeneral = document.getElementById('stockGeneral');
        inputStockGeneral.value = stockTotal;
        console.log(stockCombinaciones);
        console.log(stockTotal);
    }
</script>
</html>