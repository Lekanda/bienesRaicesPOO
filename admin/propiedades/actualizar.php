<?php
    require '../../includes/app.php';

    use App\Propiedad;
    use Intervention\Image\ImageManagerStatic as Image;



    estaAutenticado();

    // Validar la URL por ID valido(INT)
    $id= $_GET['id'];
    $id=filter_var($id, FILTER_VALIDATE_INT);
    if (!$id) {
        header('Location: /bienesraicesPOO/admin');
    }

    // Obtener los datos de la propiedad
    $propiedad = Propiedad::find($id);
    // debuguear($propiedad);

    // Consulat a DB para obtener los vendedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db,$consulta);

    // Arreglo con mensajes de errores
    $errores = Propiedad::getErrores();



    // Ejecuta el codigo despues de que el usuario envie el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Asignar los atributos
        $args = $_POST['propiedad'];

        $propiedad->sincronizar($args);

        // Validacion
        $errores = $propiedad->validar();

        // Subida de archivos(Imagen). Realiza un resize a la imagen con Intervention Image.
        // Generar un nombre unico
        $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

        if ($_FILES['propiedad']['tmp_name']['imagen']) {
        $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
        $propiedad->setImagen($nombreImagen);
        }

        // Comprobar que no haya errores en arreglo $errores. Comprueba que este VACIO (empty).
        if (empty($errores)) {
            // Almacenar la imagen
            if ($_FILES['propiedad']['tmp_name']['imagen']){
                $image->save(CARPETAS_IMAGENES . $nombreImagen);
            }
            // debuguear($_POST);
            // $_POST['vendedorId'] === 1; //TODO AJUSTE MIO:  $_POST['vendedorId'] === 1; 
            
            $propiedad->guardar();
        }
    }

    incluirTemplate('header');
?>  

    <main class="contenedor seccion">
        <h1>Actualizar Propiedad</h1>

        <a href="/bienesraicesPOO/admin" class="boton boton-verde">Volver</a>

        <?php foreach ($errores as $error) : ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?> 

        <form method="POST" class="formulario" enctype="multipart/form-data">
            <?php include '../../includes/templates/formulario_propiedades.php' ?>

            <input type="submit" value="Editar Propiedad" class="boton boton-verde">
        </form>
    </main>

<?php 
    incluirTemplate('footer');
?>