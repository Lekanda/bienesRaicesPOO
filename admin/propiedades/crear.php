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

    $propiedad = new Propiedad;

    // Consulat a DB para obtener los vendedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db,$consulta);

    // Arreglo con mensajes de errores
    $errores = Propiedad::getErrores();
    // debuguear($errores);

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // El constructor de la clase es un Arreglo y $_POST tambien por eso se puede pasar asi.
        $propiedad = new Propiedad($_POST['propiedad']);

        // debuguear($propiedad);
        // debuguear($_FILES['propiedad']);

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
        if ($_FILES['propiedad']['tmp_name']['imagen']) {
            $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
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
        <!-- Errores -->
        <?php foreach ($errores as $error) : ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?> 
        <!-- Formulario -->
        <form method="POST" class="formulario" action="/bienesraicesPOO/admin/propiedades/crear.php" enctype="multipart/form-data">
            
            <?php include '../../includes/templates/formulario_propiedades.php' ?>

            <input type="submit" value="Crear Propiedad" class="boton boton-verde">
        </form>
    </main>

<?php 
    incluirTemplate('footer');
?>