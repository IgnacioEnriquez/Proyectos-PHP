<?php

require_once("./clases/Usuario.php");
$pathArchivos = "./archivos/usuarios.json";

try 
{
    $arrayUsuarios = Usuario::TraerTodosJSON($pathArchivos);
    $jsonUsuarios = json_encode($arrayUsuarios);

    echo $jsonUsuarios;   
    
} catch (Exception $ex) 
{
    echo "ERROR : " . $ex -> getMessage();
    
}
