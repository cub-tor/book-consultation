<?php
// Esta API tiene dos posibilidades; Mostrar una lista de autores o mostrar la información de un autor específico.

//Datos de la aplicación, obtenidos de bd

//Referencio el modelo Libros para pobtener los datos
require_once("Libros.php");

//Creo un objeto nuevo para usarlo con los métodos del modelo que permiten recoger sus datos
//Conexión con la base de datos ($pdo)
$objetoLibros = new Libros();

$pdo = $objetoLibros->conexion("localhost", "id19921094_usuario1", "Basededatos000webhost.com", "id19921094_libros");
//$pdo = $objetoLibros->conexion("localhost", "usuario1", "1234", "Libros");



//Consultas a la bd


/**
 * get_listado_autores: consulta para obtener los datos de los autores. Devuelve un array asociativo de objetos.
 *
 * @return void $datos o null
 */
function get_listado_autores(){

    //Esta información se cargará de la base de datos
    global $pdo;

    try {

        $sql = "SELECT * FROM autor";
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



/**
 * get_datos_autor: Consulta para obtener los datos de un autor 
 *
 * @param  mixed $id
 * @return void $datos o null
 */
function get_datos_autor($id){

	//Esta información se cargará de la base de datos.OK
    global $pdo;

    try {

        //Hago una consulta para obtener los datos de un autor 
        $sqlAutor = "SELECT * FROM autor WHERE id='$id' ";
        $resultadoAutor = $pdo->query($sqlAutor);

        //Hago una consulta para obtener los datos de sus libros
        $sqlPublicaciones = "SELECT l.id, l.titulo FROM autor a JOIN libro l ON a.id = l.id_autor WHERE a.id='$id' ";
        $resultadoPublicaciones= $pdo->query($sqlPublicaciones);

        if ($resultadoAutor->rowCount() > 0 && $resultadoPublicaciones->rowCount() > 0){

			//Procesar result set como objeto
			while ($fila = $resultadoAutor->fetchObject()){
				$datosAutor[] = $fila;
			}
            while ($fila = $resultadoPublicaciones->fetchObject()){
				$datosPublicaciones[] = $fila;
			}
            return array_merge($datosAutor,$datosPublicaciones);
        
		} else {
            return null;
        }

    } catch (PDOException $e) {
        echo 'Excepción capturada: ', $e->getMessage(), "\n";
    }    
      
}


/**
 * get_listado_libros: Consulta id, titulo y fecha de publicacion de la tabla libros. Devuelve id y titulo de los  libros.
 *
 * @return void $datos o null
 */
function get_listado_libros() {
    
    global $pdo;

    try {
        //Devuelve id y titulo de los  libros. 
        $sql = "SELECT id, titulo, f_publicacion, id_autor FROM libro";
        $resultado = $pdo->query($sql);

        if ($resultado->rowCount() > 0){
			//Procesar result set como objeto
			while ($fila = $resultado->fetchObject()){
				$datos[] = $fila;
                //return $datos;
			}
            return $datos;
		} else {
            return null;
        }
    } catch (PDOException $e) {
        echo 'Excepción capturada: ', $e->getMessage(), "\n";
    }
}


/**
 * get_datos_libro: Consulta que devuelve titulo y f_publicacion (libro), nombre y apellidos (autor) del libro consultado por id.
 *
 * @param  mixed $id
 * @return void $datos o null
 */
function get_datos_libro($id) {
    
    global $pdo;

    try {

        //Devuelve titulo y f_publicacion (libro), nombre y apellidos (autor) del libro consultado por id. 
        $sql = "SELECT l.id, l.titulo, l.f_publicacion, l.id_autor, a.nombre, a.apellidos, a.id  FROM autor a JOIN libro l ON a.id = l.id_autor WHERE l.id='$id' ";
        $resultado = $pdo->query($sql);

        if ($resultado->rowCount() > 0){
			//Procesar result set como objeto
			while ($fila = $resultado->fetchObject()){
				$datos[] = $fila;
                
			}
            return $datos;
		} else {
            return null;
        }
    } catch (PDOException $e) {
        echo 'Excepción capturada: ', $e->getMessage(), "\n";
    }
}



//Redireccionar según las posibles url

$posibles_URL = array("get_listado_autores", "get_datos_autor", "get_listado_libros", "get_datos_libro");

$valor = "Ha ocurrido un error";

if (isset($_GET["action"]) && in_array($_GET["action"], $posibles_URL)) {

  switch ($_GET["action"]){

      case "get_listado_autores":
        $valor = get_listado_autores();//ok
        break;
      case "get_datos_autor":
        if (isset($_GET["id"]))
            $valor = get_datos_autor($_GET["id"]);//ok. http://localhost/t7/api.php?action=get_datos_autor&id=1
        else
            $valor = "Argumento no encontrado";
        break;
      case "get_listado_libros":
            $valor = get_listado_libros();//ok
        break;
      case "get_datos_libro":
        if (isset($_GET["id"]))
            $valor = get_datos_libro($_GET["id"]);//ok: http://localhost/t7/api.php?action=get_datos_libro&id=1
        else
            $valor = "Argumento no encontrado";
        break;
    }
}

//devolvemos los datos serializados en JSON
exit(json_encode($valor));

//Cerrar la conexión
$pdo = null;
?>