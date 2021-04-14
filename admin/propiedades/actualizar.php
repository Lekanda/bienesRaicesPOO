<?php

use App\Propiedad;

require '../../includes/app.php';

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
    $errores = [];

    // Ejecuta el codigo despues de que el usuario envie el formulario
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // debuguear($_POST);

        // Asignar los atributos
        $args = $_POST['propiedad'];

        $propiedad->sincronizar($args);
        debuguear($propiedad);

        // Asignar Files hacia una Variable
        $imagen = $_FILES['imagen'];
        var_dump($imagen['name']);


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
        
        // Validar las imagenes por tamaño (1000Kb)
        $medida = 1000 * 1000;
        if ($imagen['size'] > $medida) {
            $errores[] = "Tamaño imagen grande, Max: 100Kb";
        }

        // Comprobar que no haya errores en arreglo $errores. Comprueba que este VACIO (empty).
        if (empty($errores)) {
            // Crear una carpeta
            $carpetaImagenes = '../../imagenes/';
            if (!is_dir($carpetaImagenes)) {
                mkdir($carpetaImagenes);
            }

            $nombreImagen = '';

            /**Subida de Archivos**/
            // var_dump($imagen);
            if ($imagen['name']) {
                // Generar un nombre unico
                $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

                // Eliminar imagen previa.
                unlink($carpetaImagenes . $propiedad['imagen']);

                 // Subir la imagen
                move_uploaded_file($imagen['tmp_name'], $carpetaImagenes  . $nombreImagen);

            } else {
                $nombreImagen = $propiedad['imagen'];
            }

            // Insertar en la DB
            $query = " UPDATE propiedades SET titulo = '${titulo}', precio = '${precio}', imagen = '${nombreImagen}' , descripcion = '${descripcion}', habitaciones = ${habitaciones},  wc = ${wc},  estacionamiento = ${estacionamiento},  vendedorId = ${vendedorId} WHERE id=${id}";

            $resultado = mysqli_query($db,$query);
            if($resultado){
                // Redirecionar al usuario
                header('Location: /bienesraicesPOO/admin?resultado=2');
            }
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