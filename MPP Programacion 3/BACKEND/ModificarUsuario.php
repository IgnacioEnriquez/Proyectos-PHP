<?php

require_once("./clases/Usuario.php");
$pathArchivos = "./archivos/usuarios.json";

try 
{
    $retorno = new stdClass();
    $retorno->exito = false;
    $retorno->mensaje = "La persona no fue modificada";

    $usuario_json = isset($_POST["usuario_json"]) == true && empty($_POST["usuario_json"]) == false ? (string)$_POST["usuario_json"] : throw new Exception("El usuario no fue enviado como parametro");

    $parametros_obj = json_decode($usuario_json);
    
    $id = $parametros_obj -> id;
    $nombre = $parametros_obj -> nombre;
    $correo = $parametros_obj -> correo;
    $clave = $parametros_obj -> clave;
    $id_perfil = $parametros_obj -> id_perfil;

    $usuario_obj = new Usuario($nombre,$correo,$clave,$id,$id_perfil);    

    if ($usuario_obj -> Modificar()) 
    {
        $retorno->exito = true;
        $retorno->mensaje = "La persona fue modificada";
    }

    echo json_encode($retorno);

} catch (Exception $ex) 
{
    $retorno->mensaje = "Error : " . $ex -> getMessage();
    echo json_encode($retorno);
}