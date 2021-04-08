<?php
    // IMPORTANTE!!!!  Iniciar la sesion con sesion_start() para poder leer $_SESSION
    session_start();
    // Reiniciamos el arreglo de sesion a uno nuevo vacio.
    $_SESSION = [];
    // var_dump($_SESSION);
    header('Location: /');
?>