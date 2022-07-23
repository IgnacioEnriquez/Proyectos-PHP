<?php

require_once("./clases/ProductoEnvasado.php");

use IgnacioEnriquez\ProductoEnvasado;

try 
{  
    $retorno = new stdClass();
    $retorno -> exito = false;
    $retorno -> mensaje = "";

    $producto_json = isset($_POST["producto_json"]) == true && empty($_POST["producto_json"]) == false ? (string)$_POST["producto_json"] : throw new Exception("El producto en formato JSON no fue enviado como parametro");     

    $producto_obj = json_decode($producto_json);

    $id = isset($producto_obj -> id) == true && empty($producto_obj -> id) == false ? $producto_obj -> id : throw new Exception("El producto no contiene ID");
    $nombre = isset($producto_obj -> nombre) == true && empty($producto_obj -> nombre) == false ? $producto_obj -> nombre : throw new Exception("El producto no contiene Nombre");
    $origen = isset($producto_obj -> origen) == true && empty($producto_obj -> origen) == false ? $producto_obj -> origen : throw new Exception("El producto no contiene Origen");

    $producto = new ProductoEnvasado($nombre,$origen);

    if($producto -> Eliminar($id))
    {
        $retornoGuardado_JSON = $producto -> GuardarEnJson("./archivos/productos_eliminados.json");
        $retornoGuardado_OBJ = json_decode($retornoGuardado_JSON);

        if($retornoGuardado_OBJ -> exito == true)
        {
            $retorno -> exito = true;        
            $retorno -> mensaje = "Se elimino correctamente el producto envasado";
        }
        else
        {
            $retorno -> exito = true;        
            $retorno -> mensaje = "Se elimino correctamente el producto envasado pero no se agrego al archivo 'productos_eliminados.json' ";
        }
        

    }
    else
    {
        throw new Exception("No se pudo agregar el producto envasado");
    }       
    
} catch (Exception $ex) 
{
    $retorno -> mensaje = "ERROR : " . $ex -> getMessage();    
}
finally
{
    echo json_encode($retorno);
}