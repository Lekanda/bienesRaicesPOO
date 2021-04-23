<?php

namespace App;

class Vendedor extends ActiveRecord{
    protected static $tabla='vendedores';

    protected static $columnasDB =['id','nombre','apellido','telefono'];

    public $id;
    public $nombre;
    public $apellido;
    public $telefono;
    // public $imagen;


    // Constructor
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        // $this->imagen = $args['imagen'] ?? '';
    }

    public function validar(){
        // Validar que no vaya vacio
        if (!$this->nombre) {
            // $errores[] => añade al arreglo $errores
            self::$errores[] = "Debes añadir un Nombre";
        }
        if (!$this->apellido) {
            // $errores[] => añade al arreglo $errores
            self::$errores[] = "Debes añadir un Apellido";
        }
        if (!$this->telefono) {
            // $errores[] => añade al arreglo $errores
            self::$errores[] = "Debes añadir un Telefono";
        }
        // Busca un patron dentro de un txt. Solo numeros de 0 a 9. 10 digitos a la fuerza
        if (!preg_match('/[0-9]{9}/',$this->telefono)) {
            self::$errores[] = "Telefono 9 numeros de 0 a 9 cada uno";
        }

        return self::$errores;
    }
}