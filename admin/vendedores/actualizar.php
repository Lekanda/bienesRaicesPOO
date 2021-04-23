<?php 
    require '../../includes/app.php';

    // Importar o usar las clases
    use App\Vendedor;

    // Restringir el acceso a determinadas paginas.
    estaAutenticado();

    // Validar la URL por ID valido(INT)
    $id= $_GET['id'];
    $id=filter_var($id, FILTER_VALIDATE_INT);
    if (!$id) {
        header('Location: /bienesraicesPOO/admin');
    }

    // Obtener los datos dl vendedor a actualizar
    $vendedor = Vendedor::find($id);

    // Arreglo con mensajes de errores
    $errores = Vendedor::getErrores();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // debuguear($_POST);
        // Asignar los valores a los atributos
        $args = $_POST['vendedor'];

        $vendedor->sincronizar($args);
        // debuguear($vendedor);
        // Validacion
        $errores = $vendedor->validar();
        // debuguear($errores);

        // Comprobar que no haya errores en arreglo $errores. Comprueba que este VACIO (empty).
        if (empty($errores)) {
            // Guarda en la DB
           $vendedor->guardar();
        }
    }

    incluirTemplate('header');
?>  

    <main class="contenedor seccion">
        <h1>Actualizar Vendedor</h1>

        <a href="/bienesraicesPOO/admin" class="boton boton-verde">Volver</a>

        <!-- Errores -->
        <?php foreach ($errores as $error) : ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?> 

        <!-- Formulario -->
        <form method="POST" class="formulario" enctype="multipart/form-data">

            <?php include '../../includes/templates/formulario_vendedores.php' ?>

            <input type="submit" value="Guardar Cambios" class="boton boton-verde">
        </form>
    </main>
<?php 
    incluirTemplate('footer');
?>