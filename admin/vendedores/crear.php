<?php 
    require '../../includes/app.php';

    // Importar o usar las clases
    use App\Vendedor;

    // Restringir el acceso a determinadas paginas.
    estaAutenticado();

    $vendedor = new Vendedor;

    // Arreglo con mensajes de errores
    $errores = Vendedor::getErrores();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // debuguear($_POST);
        // // El constructor de la clase es un Arreglo y $_POST tambien por eso se puede pasar asi.
        $vendedor = new Vendedor($_POST['vendedor']);
        // debuguear($vendedor);

        // Validar 
        $errores = $vendedor->validar();

        // Comprobar que no haya errores en arreglo $errores. Comprueba que este VACIO (empty).
        if (empty($errores)) {
            // Guarda en la DB
           $vendedor->guardar();
        }
    }

    incluirTemplate('header');
?>  

    <main class="contenedor seccion">
        <h1>Registrar Vendedor</h1>

        <a href="/bienesraicesPOO/admin" class="boton boton-verde">Volver</a>

        <!-- Errores -->
        <?php foreach ($errores as $error) : ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?> 

        <!-- Formulario -->
        <form method="POST" class="formulario" action="/bienesraicesPOO/admin/vendedores/crear.php" enctype="multipart/form-data">
            <?php include '../../includes/templates/formulario_vendedores.php' ?>
            <input type="submit" value="Crear Vendedor" class="boton boton-verde">
        </form>
    </main>
<?php 
    incluirTemplate('footer');
?>