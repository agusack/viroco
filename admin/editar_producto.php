<?php

function uploadImage($file) {
    $target_dir = "/Viroco/img/productos/";
    $target_file = $target_dir . basename($file["name"]);
    move_uploaded_file($file["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $target_file);
    return $target_file;
}

session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../app/login.php');
    exit();
}

require '../app/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $subcategoria = $_POST['subcategoria'];
    $stock = $_POST['stockGeneral'];

    // subir imágenes nuevas usando la función uploadImage
    $imagen = isset($_POST['imagen']) ? $_POST['imagen'] : $row['imagen'];
    $imagen2 = isset($_POST['imagen2']) ? $_POST['imagen2'] : $row['imagen2'];
    $imagen3 = isset($_POST['imagen3']) ? $_POST['imagen3'] : $row['imagen3'];
    $imagen4 = isset($_POST['imagen4']) ? $_POST['imagen4'] : $row['imagen4'];

    if ($_FILES['imagen']['name']) {
        $imagen = uploadImage($_FILES['imagen']);
    }

    if ($_FILES['imagen2']['name']) {
        $imagen2 = uploadImage($_FILES['imagen2']);
    }

    if ($_FILES['imagen3']['name']) {
        $imagen3 = uploadImage($_FILES['imagen3']);
    }

    if ($_FILES['imagen4']['name']) {
        $imagen4 = uploadImage($_FILES['imagen4']);
    }

    // Actualizar producto en la base de datos
    $sql = "UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, id_categoria = :categoria, id_subcategoria = :subcategoria, stock = :stock, imagen = :imagen, imagen2 = :imagen2, imagen3 = :imagen3, imagen4 = :imagen4 WHERE id = :id";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        ':id' => $id,
        ':nombre' => $nombre,
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

    // Actualizar características existentes
    $oldcaracteristicas = $_POST['oldcaracteristicas'];
    foreach ($oldcaracteristicas as $id_caracteristica => $datos_caracteristica) {
        $nombre_caracteristica = $datos_caracteristica['nombre'];
        $valor_caracteristica = $datos_caracteristica['valor'];
        
        // Actualizar las características existentes en la tabla "caracteristicas"
        $sql_update_caracteristica = "UPDATE caracteristicas SET nombre = :nombre, valor = :valor WHERE id_caracteristica = :id_caracteristica";
        $stmt_update_caracteristica = $conexion->prepare($sql_update_caracteristica);
        $stmt_update_caracteristica->execute([
            ':nombre' => $nombre_caracteristica,
            ':valor' => $valor_caracteristica,
            ':id_caracteristica' => $id_caracteristica
        ]);
    }

    // Agregar nuevas características
    $caracteristicas = $_POST['caracteristicas'];
    foreach ($caracteristicas as $caracteristica) {
        $nombre_caracteristica = $caracteristica['nombre'];
        $valor_caracteristica = $caracteristica['valor'];
        
        // Insertar nuevas características en la tabla "caracteristicas"
        $sql_insert_caracteristica = "INSERT INTO caracteristicas (id_producto, nombre, valor) VALUES (:id_producto, :nombre, :valor)";
        $stmt_insert_caracteristica = $conexion->prepare($sql_insert_caracteristica);
        $stmt_insert_caracteristica->execute([
            ':id_producto' => $id, // ID del producto al que se asocia la característica
            ':nombre' => $nombre_caracteristica,
            ':valor' => $valor_caracteristica
        ]);
    }

    // Actualizar combinaciones existentes
    $combinaciones_existentes = $_POST['oldcombinaciones'];
    foreach ($combinaciones_existentes as $id_combinacion => $combinacion_data) {
        $stock_combinacion = $combinacion_data['stock'];
        
        // Actualizar el stock de las combinaciones existentes en la tabla "combinaciones_producto"
        $sql_update_combinacion = "UPDATE combinaciones_producto SET stock = :stock WHERE id_combinacion = :id_combinacion";
        $stmt_update_combinacion = $conexion->prepare($sql_update_combinacion);
        $stmt_update_combinacion->execute([
            ':stock' => $stock_combinacion,
            ':id_combinacion' => $id_combinacion
        ]);
    }

    // Agregar nuevas combinaciones
    $nuevas_combinaciones = $_POST['combinaciones'];
    foreach ($nuevas_combinaciones as $combinacion_data) {
        $caracteristica_1 = $combinacion_data['caracteristica_1'];
        $caracteristica_2 = $combinacion_data['caracteristica_2'];
        $stock_combinacion = $combinacion_data['stock'];
        
        // Crear la combinación única con los nombres y valores seleccionados
        $combinacion_unica = "$caracteristica_1 - $caracteristica_2";

        // Insertar la nueva combinación en la tabla "combinaciones_producto"
        $sql_insert_combinacion = "INSERT INTO combinaciones_producto (id_producto, combinacion_unica, stock) VALUES (:id_producto, :combinacion_unica, :stock)";
        $stmt_insert_combinacion = $conexion->prepare($sql_insert_combinacion);
        $stmt_insert_combinacion->execute([
            ':id_producto' => $id,
            ':combinacion_unica' => $combinacion_unica,
            ':stock' => $stock_combinacion
        ]);
    }

    header('Location: productos_admin.php');
}

$id = $_GET['id'];

// Obtener datos del producto a editar
$sql = "SELECT * FROM productos WHERE id = :id";
$stmt = $conexion->prepare($sql);
$stmt->execute([
    ':id' => $id
]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener categorías
$sql_categorias = "SELECT * FROM categorias";
$stmt_categorias = $conexion->prepare($sql_categorias);
$stmt_categorias->execute();
$categorias = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);

// Obtener subcategorías
$sql_subcategorias = "SELECT * FROM subcategorias";
$stmt_subcategorias = $conexion->prepare($sql_subcategorias);
$stmt_subcategorias->execute();
$subcategorias = $stmt_subcategorias->fetchAll(PDO::FETCH_ASSOC);

// Obtener combinaciones únicas actuales
$sql_combinaciones = "SELECT id_combinacion, combinacion_unica, stock FROM combinaciones_producto WHERE id_producto = :id";
$stmt_combinaciones = $conexion->prepare($sql_combinaciones);
$stmt_combinaciones->execute([':id' => $id]);
$combinaciones_actuales = $stmt_combinaciones->fetchAll(PDO::FETCH_ASSOC);


// Obtener características del producto
$sql_caracteristicas = "SELECT * FROM caracteristicas WHERE id_producto = :id";
$stmt_caracteristicas = $conexion->prepare($sql_caracteristicas);
$stmt_caracteristicas->execute([
    ':id' => $id
]);
$caracteristicas = $stmt_caracteristicas->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Producto - Tienda Online</title>
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
            <h1>Editar Producto</h1>

            <form action="editar_producto.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" value="<?php echo $row['nombre'] ?>"><br>

                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" id="descripcion"><?php echo $row['descripcion'] ?></textarea><br>

                <label for="precio">Precio:</label>
                <input type="number" step="0.01" name="precio" id="precio" value="<?php echo $row['precio'] ?>"><br>

                <label for="categoria">Categoría:</label>
                <select name="categoria" id="categoria">
                    <?php foreach($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['id_categoria'] ?>"<?php if($categoria['id_categoria'] == $row['id_categoria']): ?> selected<?php endif; ?>><?php echo $categoria['nombre_categoria'] ?></option>
                    <?php endforeach; ?>
                </select><br>

                <label for="subcategoria">Subcategoría:</label>
                <select name="subcategoria" id="subcategoria">
                    <?php foreach($subcategorias as $subcategoria): ?>
                        <option value="<?php echo $subcategoria['id_subcategoria'] ?>"<?php if($subcategoria['id_subcategoria'] == $row['id_subcategoria']): ?> selected<?php endif; ?>><?php echo $subcategoria['nombre_subcategoria'] ?></option>
                    <?php endforeach; ?>
                </select><br>

                <?php
                    echo "<label for='imagen'>Imagen:</label>";
                    echo "<br>";
                    echo "<img class='ajuste_img2' src='" . $row['imagen'] . " ' height='150'>";
                    echo "<br><br>";
                ?>

                <label for="imagen">Seleccionar imagen principal:</label>
                <input type="file" name="imagen" id="imagen" accept="image/*"><br>

                <?php
                    echo "<label for='imagen2'>Imagen 2:</label>";
                    echo "<br>";
                    echo "<img src='" . $row['imagen2'] . "' height='150'>";
                    echo "<br><br>";
                ?>

                <label for="imagen2">Seleccionar imagen 2:</label>
                <input type="file" name="imagen2" id="imagen2" accept="image/*"><br>


                <?php
                    echo "<label for='imagen3'>Imagen 3:</label>";
                    echo "<br>";
                    echo "<img src='" . $row['imagen3'] . "' height='150'>";
                    echo "<br><br>";
                ?>

                <label for="imagen3">Seleccionar imagen 3:</label>
                <input type="file" name="imagen3" id="imagen3" accept="image/*"><br>

                <?php
                    echo "<label for='imagen4'>Imagen 4:</label>";
                    echo "<br>";
                    echo "<img src='" . $row['imagen4'] . "' height='150'>";
                    echo "<br><br>";
                ?>

                <label for="imagen4">Seleccionar imagen 4:</label>
                <input type="file" name="imagen4" id="imagen4" accept="image/*"><br>

                <input type="hidden" name="imagen" value="<?php echo $row['imagen'] ?>">
                <input type="hidden" name="imagen2" value="<?php echo $row['imagen2'] ?>">
                <input type="hidden" name="imagen3" value="<?php echo $row['imagen3'] ?>">
                <input type="hidden" name="imagen4" value="<?php echo $row['imagen4'] ?>">

                <h3 style="margin-top: 20px; margin-bottom: 5px; font-weight: bold;">Características existentes:</h3>
                <div id="contenedorCaracteristicasExistentes">
                    <!-- Campos de características existentes -->
                    <?php foreach ($caracteristicas as $caracteristica) { ?>
                        <div class="campoCaracteristicaExistente"><br>
                            <input type="text" name="oldcaracteristicas[<?php echo $caracteristica['id_caracteristica'] ?>][nombre]" placeholder="Nombre" value="<?php echo $caracteristica['nombre'] ?>">
                            <input type="text" name="oldcaracteristicas[<?php echo $caracteristica['id_caracteristica'] ?>][valor]" placeholder="Valor" value="<?php echo $caracteristica['valor'] ?>">
                            <br><span class="eliminar" onclick="eliminarCampoCaracteristica(this)" style="width: 50px; height: 50px; color: #fff; background-color: #cc0000; cursor: pointer; padding: 5px; border: 1px solid #ccc;"><i class="fas fa-trash"></i> Eliminar caracteristica</span>
                        </div>
                    <?php } ?>
                </div>
                
                <br><h3 style="margin-bottom: 5px; font-weight: bold;">Nueva característica:</h3>
                <div id="contenedorCaracteristicas">
                    <!-- Input para agregar nuevas características -->
                    <span class="agregar" onclick="agregarCampoCaracteristica()" style="width: 50px; height: 50px; color: #fff; background-color: #45a049; cursor: pointer; padding: 5px; border: 1px solid #ccc;"><i class="fas fa-plus"></i> Agregar característica</span>

                    <!-- Campos de características -->
                    <div class="campoCaracteristica">
                        <!-- Aqui borre los inputs -->
                    </div>
                </div>

                <br><h3 style="margin-top: 20px; margin-bottom: 5px; font-weight: bold;">Combinaciones existentes:</h3>
                <div id="contenedorCombinacionesExistentes">
                    <?php 
                    if (count($combinaciones_actuales) == 0) { ?>
                    <p>No hay combinaciones</p>
                    <?php } else {
                        foreach ($combinaciones_actuales as $combinacion) { ?>
                        <div class="campoCombinacionExistente">
                            <label>Combinación: <?php echo $combinacion['combinacion_unica']; ?></label><br>
                            <label>Stock:</label>
                            <input type="number" name="oldcombinaciones[<?php echo $combinacion['id_combinacion']; ?>][stock]" value="<?php echo $combinacion['stock']; ?>">
                        </div>
                    <?php }};?>
                </div>

                <br><h3 style="margin-top: 20px; margin-bottom: 5px; font-weight: bold;">Nueva combinacion:</h3>
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

                <br><input type="submit" name="submit" value="Actualizar" style="margin-top: 10px;">
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
        const camposCaracteristicaExistente = document.querySelectorAll('.campoCaracteristicaExistente');
        const caracteristicasAgrupadas = {};

        // Obtener características existentes y agregarlas a caracteristicasAgrupadas
        camposCaracteristicaExistente.forEach(campo => {
            const nombreInput = campo.querySelector('input[name^="oldcaracteristicas["]').value;
            const valorInput = campo.querySelector('input[name^="oldcaracteristicas["][name$="][valor]"]').value;

            if (!caracteristicasAgrupadas[nombreInput]) {
                caracteristicasAgrupadas[nombreInput] = [];
            }

            caracteristicasAgrupadas[nombreInput].push(valorInput);
        });

        // Obtener características ingresadas y agregarlas a caracteristicasAgrupadas
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
        const contenedorCombinacionesExistentes = document.getElementById('contenedorCombinacionesExistentes');
        contenedorCombinacionesExistentes.addEventListener('change', function(event) {
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
        const stockOldCombinaciones = document.querySelectorAll('input[name^="oldcombinaciones["][name$="[stock]"]')
        let stockTotal = 0;

        stockCombinaciones.forEach(inputStock => {
            const stock = parseInt(inputStock.value);
            if (!isNaN(stock)) {
                stockTotal += stock;
            }
        });

        stockOldCombinaciones.forEach(inputStock => {
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