<?php
    require '../includes/app.php';
    estaAutenticado();

    // Importar Clases
    use App\Propiedad;
    use App\Vendedor;

    // Implementar un metodo para obtener todas las propiedades con Active Record
    $propiedades = Propiedad::all();
    $vendedores = Vendedor::all();


    // Muestra mensaje condicional, si no hay lo pone como null
    $resultado = $_GET['resultado'] ?? null;
    // debuguear($resultado);

    
    // 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // debuguear($_POST);

        // Validar ID
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        
        if ($id) {
            $tipo = $_POST['tipo'];
            // debuguear($tipo);

            if (validarTipoContenido($tipo)) {
                // Compara lo que vamos a Eliminar
                if ($tipo === 'vendedor') {
                    // Obtener los datos del vendedor
                    $vendedor = Vendedor::find($id);
                    $vendedor->eliminar();
                } else if ($tipo === 'propiedad') {
                    // Obtener los datos de la propiedad
                    $propiedad = Propiedad::find($id);
                    $propiedad->eliminar();
                }
            }
        }
    }

    // Incluye un template
    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>
        
        <?php  
            $mensaje = mostrarNotificacion(intval($resultado));
            if ($mensaje) { ?>
            <p class="alerta exito"><?php echo s($mensaje) ?></p>
            <?php } ?>

        <a href="/bienesraicesPOO/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>
        <a href="/bienesraicesPOO/admin/vendedores/crear.php" class="boton boton-amarillo">Nuevo Vendedor</a>

        <h2>Propiedades</h2>
        <table class="propiedades">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titulo</th>
                    <th>Imagen</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="cuerpo-tabla-propiedades"><!-- Mostrar los resultados -->
                <?php foreach($propiedades as $propiedad) : ?>
                    <tr>
                        <td> <?php echo $propiedad->id; ?> </td>
                        <td><?php echo $propiedad->titulo; ?></td>

                        <td class="imagen-tabla"><img class="imagen-tabla" src="/bienesraicesPOO/imagenes/<?php echo $propiedad->imagen; ?>" class="imagen-tabla"></td>

                        <td><?php echo $propiedad->precio; ?>???</td>
                        <td>
                            <form method="POST" class="w-100">
                                <input type="hidden" name="id" value="<?php echo $propiedad->id; ?>">
                                <input type="hidden" name="tipo" value="propiedad">

                                <input type="submit" class="boton-rojo-block" value="Eliminar">
                            </form>
                            <a href="/bienesraicesPOO/admin/propiedades/actualizar.php?id=<?php echo $propiedad->id; ?>" class="boton-amarillo-block">Actualizar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Vendedores</h2>
        <table class="propiedades">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Telefono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="cuerpo-tabla-propiedades"><!-- Mostrar los resultados -->
                <?php foreach($vendedores as $vendedor) : ?>
                    <tr>
                        <td> <?php echo $vendedor->id; ?> </td>
                        <td><?php echo $vendedor->nombre. ' ' . $vendedor->apellido; ?></td>
                        <td><?php echo $vendedor->telefono; ?></td>

                        <td>
                            <form method="POST" class="w-100">
                                <input type="hidden" name="id" value="<?php echo $vendedor->id; ?>">
                                <input type="hidden" name="tipo" value="vendedor">

                                <input type="submit" class="boton-rojo-block" value="Eliminar">
                            </form>
                            <a href="/bienesraicesPOO/admin/vendedores/actualizar.php?id=<?php echo $vendedor->id; ?>" class="boton-amarillo-block">Actualizar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </main>
<?php
    incluirTemplate('footer');
?>