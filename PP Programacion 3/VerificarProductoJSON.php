<?php

use IgnacioEnriquez\Producto;

require_once("./clases/Producto.php");
$pathArchivos = "./archivos/productos.json";

try 
{
    $retorno = new stdClass();
    $retorno -> exito = false;
    $retorno -> mensaje = "";

    $nombre = isset($_POST["nombre"]) == true && empty($_POST["nombre"]) == false ? (string)$_POST["nombre"] : throw new Exception("El nombre no fue enviado como parametro"); 
    $origen = isset($_POST["origen"]) == true  && empty($_POST["origen"]) == false ? (string)$_POST["origen"] : throw new Exception("El origen no fue enviada como parametro");    

    $producto = new Producto($nombre,$origen);

    $retornoJson = $producto -> VerificarProductoJSON($producto);

    $objRetorno = json_decode($retornoJson);

    if($objRetorno -> exito === true)
    {
        setcookie($nombre . "_" . $origen, date("d/m/y h:i:s") . " " . $objRetorno -> mensaje);
        $retorno -> exito = true;
        $retorno -> mensaje = $objRetorno -> mensaje;

        //echo $objRetorno -> mensaje;

    }
    else
    {
        throw new Exception($objRetorno -> mensaje);
    }
    
} catch (Exception $ex) 
{
    $retorno -> mensaje =  "ERROR : " . $ex -> getMessage();    
}
finally
{
    echo json_encode($retorno);
}