<?php


require 'includes/app.php';
// Importar la conexion
$db = conectarDB();

// Crear un email y password
$email = "a@a.com";
$password = "123456";

$passwordHash = password_hash($password, PASSWORD_BCRYPT);

var_dump($passwordHash);

// Querypara crear el usuario
$query = "INSERT INTO usuarios (email,password) VALUES ('${email}','${passwordHash}')";

// agregar a la DB
mysqli_query($db, $query);