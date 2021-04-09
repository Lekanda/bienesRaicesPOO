<?php

namespace App;
// Esta es la forma vieja con PHP8 se hace desde el constructor
class Propiedad{
    //  Base de Datos. Para que solo haya una conexion a la DB. Sí no , cada vez que instanciamos un objeto se crea uan conexion nueva y consume mucha memoria. Static hace que la conexion sea la misma para todos los objetos instanciados.
    protected static $db; // Estatico p q siempre son las mismas credenciales para conexion a DB. Al ser protected solo se puede instanciar desde el mismo objeto. Al ser static el metodo que se cree para manejar este atributo debe ser tambien estatico.

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

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? '';
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? 'imagen.jpg';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date('Y/m/d');
        $this->vendedorId = $args['vendedorId'] ?? '';
    }

    public function guardar(){
        // echo "Guardando en la DB";

        // Insertar en la DB
        $query = "INSERT INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, wc, estacionamiento, creado, vendedorId) VALUES ( '$this->titulo', '$this->precio','$this->imagen','$this->descripcion','$this->habitaciones','$this->wc','$this->estacionamiento','$this->creado','$this->vendedorId')";
        // debuguear($query);
        
        $resultado=self::$db-> query($query);
        debuguear($resultado);
    }

    // Definir la conexion a la DB.
    public static function setDB($database){
        // Self podia ser Propiedad. Debe ponerse self x estatico
        self::$db=$database; // En estaticos hay $
    }
}