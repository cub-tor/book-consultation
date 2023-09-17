<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API DWES</title>
    <style>
        @import url("estilo.css");
    </style>
 <body>

 



<div class="lista">
    <?php

    // Si se ha hecho una peticion que busca informacion de un autor "get_datos_autor" a traves de su "id"...
    if (isset($_GET["action"]) && isset($_GET["id"]) && $_GET["action"] == "get_datos_autor") 
    {
        //Se realiza la peticion a la api que nos devuelve el JSON con la información de los autores
        $app_info = file_get_contents('https://nhtc000.000webhostapp.com/api.php?action=get_datos_autor&id=' . $_GET["id"]);
        // Se decodifica el fichero JSON y se convierte a array
        $app_info = json_decode($app_info);


    ?> <!-- Mostramos los datos del autor -->
         <h4>Detalle de Autor</h4>
        <p>
            <td>Nombre: </td><td> <?php echo $app_info[0]->nombre ?></td>
        </p>
        <p>
            <td>Apellidos: </td><td> <?php echo $app_info[0]->apellidos ?></td>
        </p>
        <p>
            <td>Lugar de nacimiento: </td><td> <?php echo $app_info[0]->nacionalidad ?></td>
        </p>
        
        <!-- Mostramos los libros del autor -->
        <p>
            <td>Publicaciones: </td>
        </p>
        <ul class="librosAutor">
        <?php 
            foreach ($app_info as $objetoLibro) : 
                if(property_exists($objetoLibro, 'titulo')) { ?>
                    <li>
                        <a href="<?php echo "https://nhtc000.000webhostapp.com/cliente.php?action=get_datos_libro&id=" . $objetoLibro->id  ?>">
                        <?php echo $objetoLibro->titulo.'<br>'; }?>
                        </a>
                    </li>
        <?php endforeach;?>

        </ul>	
        <br /> 
        <!-- Enlace para volver a la lista de autores  ok-->
        <p class="volver">
            <a href="https://nhtc000.000webhostapp.com/cliente.php?action=get_listado_autores" alt="Lista de autores">Volver a la lista de autores</a>
        </p>
    <?php
    }
    elseif (isset($_GET["action"]) && $_GET["action"] == "get_listado_autores") //sino muestra la lista de autores
    {
        // Pedimos al la api que nos devuelva una lista de autores. La respuesta se da en formato JSON
        $lista_autores = file_get_contents('https://nhtc000.000webhostapp.com/api.php?action=get_listado_autores');
        // Convertimos el fichero JSON en array
        //var_dump($lista_autores);
        $lista_autores = json_decode($lista_autores);
    ?>  
         <h4>Listado de Autores</h4>
        <ul class="autores">
        <!-- Mostramos una entrada por cada autor -->
        <?php  foreach($lista_autores as $autores): ?>
            <li>
                <!-- Enlazamos cada nombre de autor con su informacion (primer if) ok-->
                <a href="<?php echo "https://nhtc000.000webhostapp.com/cliente.php?action=get_datos_autor&id=" . $autores->id  ?>">
                <?php echo $autores->nombre . " " . $autores->apellidos ?>
                </a>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php

    // Si se ha hecho una peticion que busca informacion de un libro "get_datos_libro" a traves de su "id"...
    } elseif (isset($_GET["action"]) && isset($_GET["id"]) && $_GET["action"] == "get_datos_libro") 
    {
        //Se realiza la peticion a la api que nos devuelve el JSON con la información de los autores
        $app_info = file_get_contents('https://nhtc000.000webhostapp.com/api.php?action=get_datos_libro&id=' . $_GET["id"]);
        // Se decodifica el fichero JSON y se convierte a array
        $app_info = json_decode($app_info);
    

    ?> <!-- Mostramos los datos del libro -->
         <h4>Detalle de un libro</h4>
        <p>
            <td>Título: </td><td> <?php echo $app_info[0]->titulo ?></td>
        </p>
        <p>
            <td>Fecha de publicacion: </td><td> <?php echo $app_info[0]->f_publicacion ?></td>
        </p>
        <p>
            <a href="<?php echo "https://nhtc000.000webhostapp.com/cliente.php?action=get_datos_autor&id=". $app_info[0]->id  ?>">
            <td>Autor: </td><td> <?php echo $app_info[0]->nombre." ".$app_info[0]->apellidos ?></td>
            </a>
        </p>
        
        <br /> 
        <!-- Enlace para volver a la lista de libros -->
        <p class="volver">
            <a href="https://nhtc000.000webhostapp.com/cliente.php?action=get_listado_libros" alt="Lista de libros">Volver a la lista de libros</a>
        </p>
    <?php
    }
    elseif (isset($_GET["action"]) && $_GET["action"] == "get_listado_libros") //sino muestra la lista de libros
    {
        // Pedimos al la api que nos devuelva una lista de libros. La respuesta se da en formato JSON
        $lista_libros= file_get_contents('https://nhtc000.000webhostapp.com/api.php?action=get_listado_libros');
        // Convertimos el fichero JSON en array
        //var_dump($lista_autores);
        $lista_libros = json_decode($lista_libros);
    ?>
        <h4>Listado de Libros</h4>
        <ul class="libros">
        <!-- Mostramos una entrada por cada autor -->
        <?php  foreach($lista_libros as $libro): ?>
            <li>
                <!-- Enlazamos cada libro para hacer referencia a la info específica del libro (segundo elseif) -->
                <a href="<?php echo "https://nhtc000.000webhostapp.com/cliente.php?action=get_datos_libro&id=" . $libro->id  ?>">
                <?php echo ($libro->titulo /*. " " . $libro->f_publicacion*/) ?>
                </a>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php
    } 
    require ("vista.html");

    ?>
</div>
    
 </body>
</html>