<?php

require_once("./clases/Usuario.php");
$pathArchivos = "./archivos/usuarios.json";

try 
{
    $retorno = new stdClass();
    $retorno->exito = false;
    $retorno->mensaje = "La persona no existe en el JSON";

    $usuario_json = isset($_POST["usuario_json"]) == true && empty($_POST["usuario_json"]) == false ? (string)$_POST["usuario_json"] : throw new Exception("El usuario no fue enviado como parametro");

    $usuarioEncontrado = Usuario::TraerUno($usuario_json);    

    if (isset($usuarioEncontrado)) 
    {
        $retorno->exito = true;
        $retorno->mensaje = "La persona existe en el JSON";
    }

    echo json_encode($retorno);

} catch (Exception $ex) 
{
    $retorno->mensaje = "Error : " . $ex -> getMessage();
    echo json_encode($retorno);
}
