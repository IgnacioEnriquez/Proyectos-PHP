<?php

require_once("./clases/Usuario.php");
$pathArchivos = "./archivos/usuarios.json";

try 
{
    $retorno = new stdClass();
    $retorno->exito = false;
    $retorno->mensaje = "La persona no fue eliminada";

    $id = isset($_POST["id"]) == true && empty($_POST["id"]) == false 
    ? (int)$_POST["id"] : throw new Exception("El ID no fue enviado como parametro");

    $accion = isset($_POST["accion"]) == true && empty($_POST["accion"]) == false 
    ? (string)$_POST["accion"] : throw new Exception("La accion no fue enviado como parametro");

    if($accion == "borrar")
    {
        if(Usuario::Eliminar($id))
        {
            $retorno -> exito = true;
            $retorno -> mensaje = "La persona fue eliminada con exito";
        }
        else
        {
            throw new Exception("El ID que se desea eliminar no existe en la base de datos");
        };

    }
    else
    {
        throw new Exception("La accion es diferente a las preestablecidas");
    }      
   
} catch (Exception $ex) 
{
    $retorno->mensaje = "Error : " . $ex -> getMessage();
}
finally
{
    echo json_encode($retorno);
}