<?php 

    // Para cerrar la conexion de autenticacion. 1º traer el $_SESSION a la pantalla donde ponemos el boton de cerrar sesion.
    if (!isset($_SESSION)) {
        session_start();
    }
    // var_dump($_SESSION);
    $auth=$_SESSION['login'] ?? false;
    // var_dump($auth);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienes Raices</title>
    <link rel="stylesheet" href="/bienesraicesPOO/build/css/app.css">
</head>
<body>
    
    <header class="header <?php echo $inicio ? 'inicio' : '';?>">
        <div class="contenedor contenido-header">
            <div class="barra">
                <a href="/bienesraicesPOO/index.php">
                    <img src="/bienesraicesPOO/build/img/logo.svg" alt="Logotipo de Bienes Raices">
                </a>

                <div class="mobile-menu">
                    <img src="/bienesraicesPOO/build/img/barras.svg" alt="icono menu responsive">
                </div>
                <div class="derecha">
                    <img class="dark-mode-boton" src="/bienesraicesPOO/build/img/dark-mode.svg">
                    <nav class="navegacion">
                        <a href="/bienesraicesPOO/nosotros.php">Nosotros</a>
                        <a href="/bienesraicesPOO/anuncios.php">Anuncios</a>
                        <a href="/bienesraicesPOO/blog.php">Blog</a>
                        <a href="/bienesraicesPOO/contacto.php">Contacto</a>
                        <?php if ($auth) : ?>
                            <a href="/bienesraicesPOO/cerrar-sesion.php">Cerrar Sesion</a>
                        <?php else : ?>
                            <a href="/bienesraicesPOO/login.php">Login</a>
                        <?php endif ?>
                    </nav>
                </div>
                
            </div> <!--.barra-->

            <?php echo $inicio ? "<h1>Venta de Casas De Lujo y Apartamentos Exclusivos</h1>" : ''; ?>

        </div>
    </header>