<?php 


define('TEMPLATES_URL', __DIR__ . '/templates');
define('FUNCIONES_URL', __DIR__ . 'funciones.php');
define('CARPETAS_IMAGENES', __DIR__ . '/../imagenes/');



function incluirTemplate( string $nombre, bool $inicio = false){
    // echo TEMPLATES_URL . "/${nombre}.php";
    include TEMPLATES_URL . "/${nombre}.php";
}

function estaAutenticado(){
    session_start();
    if (!$_SESSION['login']){
        header('Location: /bienesraicesPOO/');
    }
}

function debuguear($variable){
    echo"<pre>";
    var_dump($variable);
    echo"</pre>";
    exit;
}

// Escapa/Sanitizar el HTML
function s ($html) : string{
    $s = htmlspecialchars($html);
    return $s;
}

// Validar el tipo de contenido
function validarTipoContenido ($tipo){
    $tipos = ['vendedor','propiedad'];
    // in_array -> busca un string en un arreglo. 1º el string a buscar; 2º Arreglo donde buscarlo
    return in_array($tipo, $tipos);
}

// Muestra los mensajes
function mostrarNotificacion($codigo){
    $mensaje = '';

    switch ($codigo) {
        case 1:
            $mensaje = 'Creado correctamente';
            break;
        case 2:
            $mensaje = 'Actualizado Correctamente';
            break;
        case 3:
            $mensaje = 'Borrado Correctamente';
             break;
        default:
            $mensaje=false;
            break;
    }

    return $mensaje;
}