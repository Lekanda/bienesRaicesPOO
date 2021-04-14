<?php
    require '../includes/app.php';
    estaAutenticado();

    use App\Propiedad;

    // Implementar un metodo para obtener todas las propiedades con Active Record
    $propiedades = Propiedad::all();
    // debuguear($propiedades);

    // Muestra mensaje condicional, si no hay lo pone como null
    $resultado = $_GET['resultado'] ?? null;

    // 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        
        if ($id) {
            // Elimina la imagen (archivo)
            $query = "SELECT imagen FROM propiedades WHERE id = ${id}";
            $resultado = mysqli_query($db, $query);
            $propiedad = mysqli_fetch_assoc($resultado);
            unlink('../imagenes/' . $propiedad['imagen']);

            // Elimina la propiedad de la DB.
            $query = "DELETE FROM propiedades WHERE id = ${id}";
            $resultado = mysqli_query($db,$query);
            if ($resultado) {
                header('Location: /bienesraicesPOO/admin?resultado=3' );
            }
        }
    }

// Incluye un template
incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>
        <?php if (intval($resultado) === 1) : ?>
                <p class="alerta exito">Anuncio Creado Correctamente</p>
            <?php elseif (intval($resultado) === 2) :?>
                <p class="alerta exito">Anuncio Actualizado Correctamente</p>
            <?php elseif (intval($resultado) === 3) :?>
                <p class="alerta exito">Anuncio Borrado Correctamente</p>
        <?php endif; ?>

        <a href="/bienesraicesPOO/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>

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

                        <td><?php echo $propiedad->precio; ?>€</td>
                        <td>
                            <form method="POST" class="w-100">
                                <input type="hidden" name="id" value="<?php echo $propiedad->id; ?>">

                                <input type="submit" class="boton-rojo-block" value="Eliminar">
                            </form>
                            <a href="/bienesraicesPOO/propiedades/actualizar.php?id=<?php echo $propiedad->id; ?>" class="boton-amarillo-block">Actualizar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
<?php

// Cerrar la conexion
mysqli_close($db);

incluirTemplate('footer');
?>