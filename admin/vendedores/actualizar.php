<?php 
     require '../../includes/app.php';

     use App\Vendedor;
 
     estaAutenticado();
 
     // Validar la URL por ID valido(INT)
     $id= $_GET['id'];
     $id=filter_var($id, FILTER_VALIDATE_INT);
     if (!$id) {
         header('Location: /bienesraicesPOO/admin');
     }
 
     // Obtener los datos de la propiedad
     $vendedor = Vendedor::find($id);
 
     // Arreglo con mensajes de errores
     $errores = Vendedor::getErrores();
 
 
 
     // Ejecuta el codigo despues de que el usuario envie el formulario
     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         // Asignar los atributos
         $args = $_POST['vendedor'];
 
         $vendedor->sincronizar($args);
 
         // Validacion
         $errores = $vendedor->validar();
 
         // Comprobar que no haya errores en arreglo $errores. Comprueba que este VACIO (empty).
         if (empty($errores)) {
             $vendedor->guardar();
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
            <?php include '../../includes/templates/formulario_vendedores.php' ?>

            <input type="submit" value="Editar Vendedor" class="boton boton-verde">
        </form>
    </main>

<?php 
    incluirTemplate('footer');
?>