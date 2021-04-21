<?php

namespace App;
// Esta es la forma vieja con PHP8 se hace desde el constructor
class Propiedad extends ActiveRecord{
    protected static $tabla='propiedades';
    
    protected static $columnasDB =['id','titulo','precio','imagen','descripcion','habitaciones','wc','estacionamiento','creado','vendedorId'];

    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamiento;
    public $creado;
    public $vendedorId;

    // Constructor
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date('Y/m/d');
        $this->vendedorId = $args['vendedorId'] ?? '';
    }

    public function validar(){
        // Validar que no vaya vacio
        if (!$this->titulo) {
            // $errores[] => añade al arreglo $errores
            self::$errores[] = "Debes añadir un titulo";
        }
        if (!$this->precio) {
            self::$errores[] = "Debes añadir un precio";
        }
        if (strlen($this->descripcion) < 20) {
            self::$errores[] = "Debes añadir una descripcion";
        }
        if (!$this->habitaciones) {
            self::$errores[] = "Debes añadir un numero de Habitaciones";
        }
        if (!$this->wc) {
            self::$errores[] = "Debes añadir un numero de Baños";
        }
        if (!$this->estacionamiento) {
            self::$errores[] = "Debes añadir un numero de plazas de aparcamiento";
        }
        if (!$this->vendedorId) {
            self::$errores[] = "Debes añadir un Identificador de vendedor";
        }
        
        if (!$this->imagen) {
            self::$errores[] = "Debes seleccionar una imagen para la propiedad";
        }

        return self::$errores;
    }
}

