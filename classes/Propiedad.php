<?php

namespace App;
// Esta es la forma vieja con PHP8 se hace desde el constructor
class Propiedad{
    //  Base de Datos. Para que solo haya una conexion a la DB. Sí no , cada vez que instanciamos un objeto se crea uan conexion nueva y consume mucha memoria. Static hace que la conexion sea la misma para todos los objetos instanciados.
    protected static $db; // Estatico p q siempre son las mismas credenciales para conexion a DB. Al ser protected solo se puede instanciar desde el mismo objeto. Al ser static el metodo que se cree para manejar este atributo debe ser tambien estatico.

    protected static $columnasDB =['id','titulo','precio','imagen','descripcion','habitaciones','wc','estacionamiento','creado','vendedorId'];

    // Errores de Validaciones
    protected static $errores = [];


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

    // Definir la conexion a la DB.
    public static function setDB($database){
        // Self podia ser Propiedad. Debe ponerse self x estatico
        self::$db=$database; // En estaticos hay $
    }

    // Constructor
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? '';
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

    public function guardar(){
        // Sanitizar los datos con funcion externa.
        $atributos=$this->sanitizarAtributos();

        // debuguear(array_keys($atributos)); // Nos da los nombre de las variables de los valores
        // debuguear(array_values($atributos)); // Nos da los valores
        
        // $string = join(', ',array_values($atributos)); // Join crea un string a partir de un arreglo
        // debuguear($string); // Nos da los nombre de las variables de los valores

        // Insertar en la DB
        $query = " INSERT INTO propiedades ( "; // IMPORTANTE ESPACIOS
        $query .= join(', ',array_keys($atributos)); //Nos da los nombre de las columnas de los valores
        $query .= " ) VALUES (' "; // IMPORTANTE ESPACIOS
        $query .= join("', '",array_values($atributos)); // Nos da los valores.  "', '" => pone a cada valor'' rodeando
        $query .= " ') "; // IMPORTANTE ESPACIOS
        // debuguear($query);
        
        $resultado=self::$db-> query($query); // Nos da true/False segun ha sido la conexion a DB
        return($resultado);
    }

    // Identificar y unir los atributos de la DB.
    public function atributos(){
        $atributos = [];
        foreach (self::$columnasDB as $columna) {
            if($columna==='id') continue; // Ignora la columna ID
            $atributos [$columna]=$this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos(){
        $atributos = $this->atributos();
        // debuguear($atributos);
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            // echo $key;
            // echo $value;
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        // debuguear($sanitizado);
        return $sanitizado;
    }

    //  Subida de Archivos
    public function setImagen($imagen){
        // Asignar al atributo de imagen el nombre de la imagen
        if ($imagen) {
            $this->imagen = $imagen;
        }
    }




    // Validacion
    public static function getErrores(){
        return self::$errores;
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
            self::$errores[] = "Debes seleccionar una imagen";
        }

        return self::$errores;
    }
}

