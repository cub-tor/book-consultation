<?php


class Libros {

 


    //Constructor
    public function __construct(){
               
                
    }
    

    //Método de conexión con la base de datos    
    public function conexion($servidor, $usuario, $clave, $pdo){
        try {
        //Conexión PDO
            $cadenaConexion = "mysql:dbname=$pdo;host=$servidor";
            $pdo = new PDO($cadenaConexion, $usuario, $clave,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
            return $pdo;
        }
        catch (PDOException $e) {
            return null;
        }
    }

    //Introducir id de la tabla autor    
    /**
     * consultarAutores
     *
     * @param  mixed $pdo
     * @param  mixed $id
     * @return void array con el id de autor solicitado
     */
    public function consultarAutores($pdo, $id) {

        try {

            $sql = "SELECT * FROM autor where id='$id'";
            $resultado = $pdo->query($sql);
            
            if ($resultado->rowCount() > 0){

                while ($fila = $resultado->fetchObject())
                $datos[] = $fila;
                //devuelve el primer elemento de datos
                return $datos[0];

            } else {
                return null;
            }
        }  catch (PDOException $e) {
                return null;
        }
    }

    //Introducir id de la tabla autor    
    /**
     * consultarLibros
     *
     * @param  mixed $pdo
     * @param  mixed $id
     * @return void devuelve un array con los libros del autor solicitado por id
     */
    public function consultarLibros($pdo, $id) {

        try {

            $sql = "SELECT * FROM Libro where id_autor='$id'";
            $resultado = $pdo->query($sql);
                        if ($resultado->rowCount() > 0){

                while ($fila = $resultado->fetchObject())
                $datos[] = $fila;
                return $datos;

            } else {
                return null;
            }
        }  catch (PDOException $e) {
                return null;
        }
    }

    //id del libro solicitado    
    /**
     * consultarDatosLibro
     *
     * @param  mixed $pdo
     * @param  mixed $id
     * @return void Devuelve un array con los datos del id del libro solicitado
     */
    public function consultarDatosLibro ($pdo, $id) {


        try {

            $sql = "SELECT * FROM Libro where id='$id'";
            $resultado = $pdo->query($sql);
            
            if ($resultado->rowCount() > 0){

                while ($fila = $resultado->fetchObject())
                $datos[] = $fila;
                return $datos[0];

            } else {
                return null;
            }
        }  catch (PDOException $e) {
                return null;
        }
    }

    //eliminia según el id del autor        
    /**
     * borrarAutor
     *
     * @param  mixed $pdo
     * @param  mixed $id
     * @return void Devuelve verdadero si se ha ejecutado bien, si no falso
     */
    public function borrarAutor ($pdo, $id) {

        $error = false;

        //Elimina le autor solicitado por id
        try {
            //Indicar que se inicia una transacción
            $pdo->beginTransaction();

            //Primero borrar los libros escritos por el autor, para que no queden colgados
            $sql = "DELETE FROM libro where id_autor='$id'";
            $elementosBorrados = $pdo->query($sql);//resultado de la consulta

            //Si la consulta se ha ejecutado bien
            if ($elementosBorrados->execute()){
                //Borrar el autor
                $sql = "DELETE FROM autor WHERE id='$id'";
                $elementosBorrados = $pdo->prepare($sql);//prepare() prepara una sentencia para ser ejecutada
                
                if($elementosBorrados->execute()){
                    $error = false;
                } else {
                    $error = true;
                } 

            } else {
                $error =true;
            }
            //Comprobar si ha salido bien
            if(!$error){
                //Ha salido bien, guardar los cambios
                $pdo-> commit();
                return true;

            } else {
                //No ha salido bien, volver al estado anterior a la consulta
                $pdo -> rollback();
                return false;
            }

        }  catch (PDOException $e) {
                return false;
        }
    }

    //elimina según el id de libro    
    /**
     * borrarLibro
     *
     * @param  mixed $pdo
     * @param  mixed $id
     * @return void Devuelve verdadero si se ha ejecutado bien, si no falso
     */
    public function borrarLibro ($pdo, $id) {

        //Elimina el libro solicitado por id

        try {

            $sql = "DELETE FROM libro where id='$id'";
            $resultado = $pdo->query($sql);

            //Si el nº filas afectadas es mayor que 0
            if ($resultado->rowCount() > 0){

                return true;

            } else {
                return false;
            }
        }  catch (PDOException $e) {
                return false;
        }

    }

}
?>