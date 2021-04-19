<?php

namespace App;

class Vendedor extends ActiveRecord{
    public static function all (){
        //Escribir el Query a DB SQL
        $query = "SELECT * FROM vendedores";
        $resultado = self::consultarSQL($query);
        // debuguear($resultado);
        return $resultado;
    }
}