<?php 
    // Importar la DB
    require 'includes/config/database.php';
    $db = conectarDB();

    $errores = [];

    // Autenticar el usuario
    // Para poder coger los datos con POST se hace de esta manera.
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
        $password = mysqli_real_escape_string($db, $_POST['password']);
        
        if (!$email) {
            $errores[] = "El email es obligatorio o no es valido";
        }
        
        if(!$password){
            $errores[] = "El password es obligatorio o no es valido";
        }
        if (empty($errores)) {
            // Revisar sí el usuario existe.
            $query = "SELECT * FROM usuarios WHERE email = '${email}'";
            $resultado = mysqli_query($db,$query);
            if ($resultado->num_rows) {
                // Revisar si el password es correcto
                $usuario = mysqli_fetch_assoc($resultado);
                
                // Verificar sí el Password es correcto o no. True si es ok , false si nok.
                $auth = password_verify($password, $usuario['password']);
                if ($auth) {
                    // El usuario esta autenticado
                    session_start();

                    // Llenar el arreglo de la sesion.
                    $_SESSION['usuario'] = $usuario['email'];
                    $_SESSION['login'] = true;

                    header('Location: /bienesraicesPOO/admin');

                } else {
                    $errores[] = "El Password es incorrecto";
                }
            } else {
                $errores[] = "El usuario no existe";
            }
        }
    }
    require 'includes/funciones.php';
    incluirTemplate('header');
?> 
    <main class="contenedor seccion contenido-centrado">
        <h1>Iniciar Sesion</h1>
        <?php foreach ($errores as $error ): ?> 
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>

        <form method="POST" class="formulario" novalidate>
            <fieldset>
                <legend>Introduce datos de Inicio de Sesion</legend>

                <label for="email">E-mail</label>
                <input type="email" placeholder="Tu Email" id="email" name="email" required>

                <label for="password">Contraseña</label>
                <input type="password" placeholder="Tu contraseña" id="password" name="password" required>
            </fieldset>
            <input type="submit" value="Iniciar Sesion" class="boton boton-verde">
        </form>
    </main>
<?php 
    incluirTemplate('footer');
?>