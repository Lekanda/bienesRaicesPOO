<?php 

namespace App;

class ActiveRecord{
    
    protected static $db; 

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
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date('Y/m/d');
        $this->vendedorId = $args['vendedorId'] ?? 1;
    }

    public function guardar(){
        if (!is_null($this->id)) {
            // Actualizar
            $this->actualizar();
        }else{
            // Crear
            $this->crear();
        }
    }

    public function crear(){
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
        // Mensaje de exito/error en guardar datos.
        if($resultado){
            header('Location: /bienesraicesPOO/admin?resultado=1');
        }
    }




    // Actualizar el registro de la DB
    public function actualizar(){
        // debuguear('Actualizando.....');

        // Sanitizar los datos con funci888on externa.
        $atributos=$this->sanitizarAtributos();

        $valores=[];
        foreach ($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }
        $query = "UPDATE propiedades SET ";
        $query .= join(', ', $valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1";

        $resultado = self::$db->query($query);

        
        if($resultado){
            // Redirecionar al usuario
            header('Location: /bienesraicesPOO/admin?resultado=2');
        }
    }


    // Eliminar un registro
    public function eliminar(){
        // debuguear('Eliminando' . $this->id);

        // Elimina la propiedad de la DB.
        $query = "DELETE FROM propiedades WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);

        if ($resultado) {
            $this->borrarImagen();
            header('Location: /bienesraicesPOO/admin?resultado=3' );
        }

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
        // Elimina la imagen previa
        // debuguear($this->imagen);
        // Sí hay un id quiere decir que se esta actualizando, ya que, al  crear uno nuevo el id se crea solo en la DB con auto_increment.
        if (!is_null($this->id)) {
            $this->borrarImagen();
        }
        // Asignar al atributo de imagen el nombre de la imagen
        if ($imagen) {
            $this->imagen = $imagen;
        }
    }


    // Borrar la imagen
    public function borrarImagen(){
        // debuguear('Eliminando....');
        // Comprobar si existe el archivo
        $existeArchivo = file_exists(CARPETAS_IMAGENES . $this->imagen);
        // debuguear($existeArchivo);
        if ($existeArchivo) {
            unlink(CARPETAS_IMAGENES . $this->imagen);
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

    // Lista todas las propiedades de la DB.Static pq no hace falta crear una nueva instancia, consultamos la db y traemos todos los registros
    public static function all (){
        //Escribir el Query a DB SQL
        $query = "SELECT * FROM propiedades";
        $resultado = self::consultarSQL($query);
        // debuguear($resultado);
        return $resultado;
    }

    // Metodo para actualizar un registro de la DB x su ID.
    public static function find($id){
        $query = "SELECT * FROM propiedades WHERE id = ${id}";
        $resultado = self::consultarSQL($query);

        return array_shift($resultado); // array_shift: Nos devuelve el primer resultado de un arreglo
    }


    
    // Metodo para hacer consultas a SQL DB. REUTILIZABLE
    public static function consultarSQL ($query) {
        //*** Consultar la DB ***
        $resultado = self::$db->query($query);
        //*** Iterar los resultados ***
        $array = [];
        while ($registro = $resultado->fetch_assoc()){
            $array[]=self::crearObjeto($registro);
        }
        // debuguear($array);
        //*** Liberar la memoria ***
        $resultado->free();
        //*** retornar los resultados ***
        return $array;
    }

    protected static function crearObjeto($registro){
        $objeto = new self; // Quiere decir 'self' la clase padre(Propiedad)
        // debuguear($objeto);
        // debuguear($registro);

        foreach ($registro as $key => $value) {
            // debuguear($key);
            if(property_exists($objeto,$key)){
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    // Sincroniza el obj en memoriacon los cambios realizados por el usuario
    public function sincronizar($args = []){
        // debuguear($args);
        foreach ($args as $key => $value) {
            // Sí en el arreglo existe una propiedad con llave($key)
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}