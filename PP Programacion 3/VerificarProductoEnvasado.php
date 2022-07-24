<?php

use IgnacioEnriquez\Producto;
use IgnacioEnriquez\ProductoEnvasado;

require_once("./clases/ProductoEnvasado.php");  

try 
{
    $retorno = new stdClass();
    $retorno -> mensaje = "";

    $json_producto = isset($_POST["obj_producto"]) == true && empty($_POST["obj_producto"]) == false ? (string)$_POST["obj_producto"] : throw new Exception("El Producto Envasado no fue enviado como parametro"); 
    $obj_producto = json_decode($json_producto);

    $nombre = isset($obj_producto -> nombre) == true && empty($obj_producto -> nombre) == false ? $obj_producto -> nombre : throw new Exception("El producto no contiene nombre");
    $origen = isset($obj_producto -> origen) == true && empty($obj_producto -> origen) == false ? $obj_producto -> origen : throw new Exception("El producto no contiene origen");
    
    $productoEnvasado = new ProductoEnvasado($nombre,$origen);
    $arrayProductos = ProductoEnvasado::Traer();

    $retornoJson = $productoEnvasado -> Existe($arrayProductos);    

    if($retornoJson === false)
    {      
        $retorno -> mensaje = $productoEnvasado -> toJSON(); 
    }
    else
    {
        $retorno -> mensaje = "";
    }
    
} catch (Exception $ex) 
{
    $retorno =  "ERROR : " . $ex -> getMessage();    
}
finally
{
    echo json_encode($retorno);
}