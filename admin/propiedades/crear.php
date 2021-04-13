<?php 

    require '../../includes/app.php';

    // Importar o usar las clases
    use App\Propiedad;
    use Intervention\Image\ImageManagerStatic as Image;



    // debuguear($propiedad);

    estaAutenticado();
    // Restringir el acceso a determinadas paginas.
    

    // Base de Datos
    $db = conectarDB();

    // Consulat a DB para obtener los vendedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db,$consulta);

    // Arreglo con mensajes de errores
    $errores = Propiedad::getErrores();
    // debuguear($errores);

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
        // El constructor de la clase es un Arreglo y $_POST tambien por eso se puede pasar asi.
        $propiedad = new Propiedad($_POST);

         /**Subida de Archivos**/
        // Crear una carpeta
        $carpetaImagenes = '../../imagenes/';
        if (!is_dir($carpetaImagenes)) {
            mkdir($carpetaImagenes);
        }

        // Generar un nombre unico
        $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

        // Setear la imagen 
        // Realiza un resize a la imagen con Intervention Image.
        if ($_FILES['imagen']['tmp_name']) {
            $image = Image::make($_FILES['imagen']['tmp_name'])->fit(800,600);
            $propiedad->setImagen($nombreImagen);
        }

        // Validar 
        $errores = $propiedad->validar();

        // Comprobar que no haya errores en arreglo $errores. Comprueba que este VACIO (empty).
        if (empty($errores)) {
            // Crear la carpeta imagenes
            if(!is_dir(CARPETAS_IMAGENES)){
                mkdir(CARPETAS_IMAGENES);
            }

            // Guarda la imagen en el servidor
            $image->save(CARPETAS_IMAGENES . $nombreImagen);

            // Guarda en la DB
            $resultado = $propiedad->guardar();

            // Mensaje de exito/error en guardar datos.
            if($resultado){
                header('Location: /bienesraicesPOO/admin?resultado=1');
            }
        }
    }

    incluirTemplate('header');
?>  

    <main class="contenedor seccion">
        <h1>Crear</h1>

        <a href="/bienesraicesPOO/admin" class="boton boton-verde">Volver</a>

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

                <select name="vendedorId" value="<?php echo $vendedorId; ?>">
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