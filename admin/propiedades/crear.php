<?php 

    require '../../includes/app.php';

    // Importar o usar la classe
    use App\Propiedad;

    // debuguear($propiedad);

    estaAutenticado();
    // Restringir el acceso a determinadas paginas.
    

    // Base de Datos
    $db = conectarDB();

    // Consulat a DB para obtener los vendedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db,$consulta);

    // Arreglo con mensajes de errores
    $errores = [];

    // Ejecuta el codigo despues de que el usuario envie el formulario
    $titulo = '';
    $precio = '';
    $descripcion = '';
    $habitaciones = '';
    $wc = '';
    $estacionamiento = '';
    $vendedorId = '';
    $creado = '';
    
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        
        // echo "<pre>";
        // var_dump($_POST);
        // echo "</pre>";
        // echo "<pre>";
        // var_dump($_FILES);
        // echo "</pre>";


        $titulo = mysqli_real_escape_string($db, $_POST["titulo"]);
        $precio = mysqli_real_escape_string($db, $_POST["precio"]);
        $descripcion = mysqli_real_escape_string($db, $_POST["descripcion"]);
        $habitaciones = mysqli_real_escape_string($db, $_POST["habitaciones"]);
        $wc = mysqli_real_escape_string($db, $_POST["wc"]);
        $estacionamiento = mysqli_real_escape_string($db, $_POST["estacionamiento"]);
        $vendedorId = mysqli_real_escape_string($db, $_POST["vendedor"]);
        $creado = date('Y/m/d');


        // Asignar Files hacia una Variable
        $imagen = $_FILES['imagen'];
        // var_dump($imagen['name']);



        // Validar que no vaya vacio
        if (!$titulo) {
            // $errores[] => añade al arreglo $errores
            $errores[] = "Debes añadir un titulo";
        }
        if (!$precio) {
            $errores[] = "Debes añadir un precio";
        }
        if (strlen($descripcion) < 20) {
            $errores[] = "Debes añadir una descripcion";
        }
        if (!$habitaciones) {
            $errores[] = "Debes añadir un numero de Habitaciones";
        }
        if (!$wc) {
            $errores[] = "Debes añadir un numero de Baños";
        }
        if (!$estacionamiento) {
            $errores[] = "Debes añadir un numero de plazas de aparcamiento";
        }
        if (!$vendedorId) {
            $errores[] = "Debes añadir un Identificador de vendedor";
        }
        if (!$imagen['name'] || $imagen['error']) {
            $errores[] = "Debes seleccionar una imagen";
        }
        // Validar las imagenes por tamaño (1000Kb)
        $medida = 1000 * 1000;
        if ($imagen['size'] > $medida) {
            $errores[] = "Tamaño imagen grande, Max: 100Kb";
        }



        // echo "<pre>";
        // var_dump($errores);
        // echo "</pre>";

        // echo "<pre>";
        // var_dump($_FILES);
        // echo "</pre>";


        // Comprobar que no haya errores en arreglo $errores. Comprueba que este VACIO (empty).
        if (empty($errores)) {

            /**Subida de Archivos**/
            // Crear una carpeta
            $carpetaImagenes = '../../imagenes/';

            if (!is_dir($carpetaImagenes)) {
                mkdir($carpetaImagenes);
            }

            // Generar un nombre unico
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
            var_dump($nombreImagen);

            // Subir la imagen
            move_uploaded_file($imagen['tmp_name'], $carpetaImagenes  . $nombreImagen);

            // Insertar en la DB
            $query = "INSERT INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, wc, estacionamiento, creado, vendedorId) VALUES ( '$titulo', '$precio','$nombreImagen','$descripcion','$habitaciones','$wc','$estacionamiento','$creado','$vendedorId')";
            echo $query;

            $resultado = mysqli_query($db,$query);
            
            if($resultado){
                // echo "Insertado correctamente";
                // Redirecionar al usuario
                header('Location: /bienesraicesPOO/admin?resultado=1');
            }
        }
    }

    incluirTemplate('header');
?>  





    <main class="contenedor seccion">
        <h1>Crear</h1>

        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach ($errores as $error) : ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?> 

        <form method="POST" class="formulario" action="/bienesraicesPOO/admin/propiedades/crear.php" enctype="multipart/form-data">
            <fieldset>

                <legend>Informacion General</legend>

                <label for="titulo">Titulo:</label>
                <input 
                    type="text" 
                    id="titulo" 
                    name="titulo" 
                    placeholder="Titulo Propiedad" 
                    value="<?php echo $titulo; ?>"
                >

                <label for="precio">Precio:</label>
                <input 
                    type="number" 
                    id="precio" 
                    name="precio" 
                    placeholder="Precio Propiedad" 
                    value="<?php echo $precio; ?>"
                >

                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

                <label for="descripcion">Descripcion:</label>
                <textarea id="descripcion" name="descripcion"><?php echo $descripcion; ?></textarea>

            </fieldset>
            

            <fieldset>

                <legend>Informacion Propiedad</legend>

                <label for="habitaciones">Habitaciones:</label>
                <input 
                    type="number" 
                    id="habitaciones" 
                    name="habitaciones"  
                    placeholder="Numero Habitaciones 1-9" 
                    min="1" 
                    max="9" 
                    step="1" 
                    value="<?php echo $habitaciones; ?>"
                >

                <label for="wc">WC:</label>
                <input 
                    type="number" 
                    id="wc" name="wc" 
                    placeholder="Numero wc Propiedad 1-9" 
                    min="1" 
                    max="9" 
                    step="1" 
                    value="<?php echo $wc; ?>"
                >

                <label for="estacionamiento">Estacionamiento:</label>
                <input 
                    type="number" 
                    id="estacionamiento" 
                    name="estacionamiento" 
                    placeholder="Estacionamientos para Coches 1-9" 
                    min="1" 
                    max="9" 
                    step="1" 
                    value="<?php echo $estacionamiento; ?>"
                >

            </fieldset>

            <fieldset>
                <legend>Vendedor</legend>

                <select name="vendedor" value="<?php echo $vendedorId; ?>">
                    <option value="">-- Seleccione --</option>
                    <?php while ($vendedor = mysqli_fetch_assoc($resultado) ): ?>
                        <option 
                            <?php echo $vendedorId === $vendedor['id'] ? 'selected' : ''; ?>
                            value="<?php echo $vendedor['id'] ?>"><?php echo $vendedor['nombre'] . " " . $vendedor['apellido']; ?>
                        </option>
                    <?php endwhile;?>
                </select>
            </fieldset>

            <input type="submit" value="Crear Propiedad" class="boton boton-verde">
        </form>
    </main>

<?php 
    incluirTemplate('footer');
?>