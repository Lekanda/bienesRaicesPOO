<?php 


define('TEMPLATES_URL', __DIR__ . '/templates');
define('FUNCIONES_URL', __DIR__ . 'funciones.php');


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