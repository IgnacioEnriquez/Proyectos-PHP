<?php

require_once("./clases/Empleado.php");

try 
{
    $retorno = new stdClass();
    $retorno->exito = false;
    $retorno->mensaje = "El empleado no fue agregado a la base de datos";

    $correo = isset($_POST["correo"]) == true && empty($_POST["correo"]) == false ? (string)$_POST["correo"] : throw new Exception("El correo no fue enviado como parametro");
    $clave = isset($_POST["clave"]) == true && empty($_POST["clave"]) == false ? (string)$_POST["clave"] : throw new Exception("la clave no fue enviado como parametro");
    $nombre = isset($_POST["nombre"]) == true && empty($_POST["nombre"]) == false ? (string)$_POST["nombre"] : throw new Exception("El nombre no fue enviado como parametro");
    $id_perfil = isset($_POST["id_perfil"]) == true && empty($_POST["id_perfil"]) == false ? (int)$_POST["id_perfil"] : throw new Exception("El id del perfil no fue enviado como parametro");
    $sueldo = isset($_POST["sueldo"]) == true && empty($_POST["sueldo"]) == false ? (int)$_POST["sueldo"] : throw new Exception("El sueldo no fue enviado como parametro");
    $foto = isset($_FILES["foto"]) == true ? $_FILES["foto"] : throw new Exception("La Foto no fue enviado como parametro");    
  
    $pathDestino = Empleado::ValidarArchivoFoto($foto,$nombre);

    if(isset($pathDestino))
    {
        $empleado_obj = new Empleado($nombre,$correo,$clave,$id_perfil,$pathDestino,$sueldo);
    
        if($empleado_obj -> Agregar())
        {   
            
            if(Empleado::GuardarArchivoTemporal($foto,$pathDestino))
            {
                $retorno -> exito = true;
                $retorno -> mensaje = "El empleado fue agregado correctamente";                 
            }
            else
            {            
                $retorno -> exito = true;
                $retorno -> mensaje = "Se pudo agregar empleado correctamente pero no se pudo guardar su foto";                 
            }         
        
        }
        else
        {        
            throw new Exception("No se pudo agregar el empleado correctamente");
        }
    }
    else
    {
        throw new Exception("El archivo no es valido, elija otra foto.");
    }             

} catch (Exception $ex) 
{    
    $retorno->mensaje = "Error : " . $ex -> getMessage();
}
finally
{
    echo json_encode($retorno);  
}

    


