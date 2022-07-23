<?php

require_once("./clases/Usuario.php");

try 
{
    $retorno = new stdClass();
    $retorno->exito = false;
    $retorno->mensaje = "La persona no fue agregada a la base de datos";

    $correo = isset($_POST["correo"]) == true && empty($_POST["correo"]) == false ? (string)$_POST["correo"] : throw new Exception("El correo no fue enviado como parametro");
    $clave = isset($_POST["clave"]) == true && empty($_POST["clave"]) == false ? (string)$_POST["clave"] : throw new Exception("la clave no fue enviado como parametro");
    $nombre = isset($_POST["nombre"]) == true && empty($_POST["nombre"]) == false ? (string)$_POST["nombre"] : throw new Exception("El nombre no fue enviado como parametro");
    $id_perfil = isset($_POST["id_perfil"]) == true && empty($_POST["id_perfil"]) == false ? (string)$_POST["id_perfil"] : throw new Exception("El id del perfil no fue enviado como parametro");
   
    $usuario = new Usuario($nombre,$correo,$clave,0,$id_perfil);    

    if ($usuario -> Agregar() === true) 
    {
        $retorno->exito = true;
        $retorno->mensaje = "La persona fue agregada a la base de datos";
    }

    echo json_encode($retorno);

} catch (Exception $ex) 
{
    $retorno->mensaje = "Error : " . $ex -> getMessage();
    echo json_encode($retorno);
}