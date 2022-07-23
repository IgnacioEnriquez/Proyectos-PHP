<?php

use IgnacioEnriquez\Producto;

require_once("./clases/Producto.php");
$pathArchivos = "./archivos/productos.json";


try 
{
    $arrayUsuarios = Producto::TraerJSON($pathArchivos);
    $jsonUsuarios = json_encode($arrayUsuarios);

    echo $jsonUsuarios;   
    
} catch (Exception $ex) 
{
    echo "ERROR : " . $ex -> getMessage();    
}
