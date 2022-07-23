<?php

use IgnacioEnriquez\Producto;

require_once("./clases/Producto.php");

try {
    $retorno = new stdClass();
    $retorno->exito = false;
    $retorno->mensaje = "";

    $nombre = isset($_GET["nombre"]) == true && empty($_GET["nombre"]) == false ? (string)$_GET["nombre"] : throw new Exception("El nombre no fue enviado como parametro");
    $origen = isset($_GET["origen"]) == true  && empty($_GET["origen"]) == false ? (string)$_GET["origen"] : throw new Exception("El origen no fue enviada como parametro");

    $valorCookie = isset($_COOKIE[$nombre . "_" . $origen]) == true ? (string)$_COOKIE[$nombre . "_" . $origen] : throw new Exception("No existe ninguna cookie con ese nombre");

    $retorno->exito = true;
    $retorno->mensaje = $valorCookie;

} catch (Exception $ex) 
{
    $retorno->mensaje =  "ERROR : " . $ex->getMessage();

} finally 
{
    echo json_encode($retorno);
}
