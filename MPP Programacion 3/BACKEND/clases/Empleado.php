<?php

require_once("Usuario.php");
require_once("ICRUD.php");


class Empleado extends Usuario implements ICRUD
{
    public string $foto;
    public int $sueldo;

    public function __construct(string $nombre, string $correo, string $clave, int $id_perfil = 0, string $foto = "not path", int $sueldo = 0, int $id = 0, string $perfil = "")
    {
        parent::__construct($nombre, $correo, $clave, $id, $id_perfil, $perfil);
        $this->foto = $foto;
        $this->sueldo = $sueldo;
    }

    public static function TraerTodos(): array
    {
        $array_empleados = array();

        try {

            $pdo = new PDO('mysql:host=localhost;dbname=usuarios_test;charset=utf8', "root", "");

            $consultaTraerTodos = $pdo->prepare('SELECT id,correo,clave,nombre,id_perfil,foto,sueldo FROM empleados');

            $consultaTraerTodos->execute();

            $resultado = $consultaTraerTodos->fetchall(PDO::FETCH_ASSOC);

            foreach ($resultado as $fila) 
            {
                $id = $fila["id"];
                $correo = $fila["correo"];
                $clave = $fila["clave"];
                $nombre = $fila["nombre"];
                $id_perfil = $fila["id_perfil"];
                $foto = $fila["foto"];
                $sueldo = $fila["sueldo"];

                $perfil = Usuario::ObtenerPerfilBD($id_perfil);

                $empleado = new Empleado($nombre, $correo, $clave, $id_perfil, $foto, $sueldo, $id, $perfil);

                array_push($array_empleados, $empleado);
            }
        } catch (Exception $ex) 
        {
            throw new Exception(" TraerTodos : " . $ex->getMessage());
        }

        return $array_empleados;
    }

    public function Agregar(): bool
    {
        try 
        {                               
            $pdo = new PDO('mysql:host=localhost;dbname=usuarios_test;charset=utf8', "root", "");

            $consultaAgregar = $pdo->prepare("INSERT INTO empleados (correo, clave, nombre, id_perfil, foto, sueldo) VALUES(:correo, :clave, :nombre, :id_perfil, :foto, :sueldo)");

            $consultaAgregar->bindValue(':correo', $this->correo, PDO::PARAM_STR);
            $consultaAgregar->bindValue(':clave', $this->clave, PDO::PARAM_STR);
            $consultaAgregar->bindValue(':id_perfil', $this->id_perfil, PDO::PARAM_INT);
            $consultaAgregar->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consultaAgregar->bindValue(':sueldo', $this->sueldo, PDO::PARAM_INT);
            $consultaAgregar->bindValue(':foto', $this->foto, PDO::PARAM_STR);

            $consultaAgregar->execute();

            if ($consultaAgregar->rowCount() > 0) 
            {                
                $retorno = true;

            } else {
                throw new Exception("NO SE AGREGO LA PERSONA A LA BASE");
            }
        } catch (Exception $ex) 
        {
           
            $retorno = false;
        }

        return $retorno;
    }

    public function Modificar(): bool
    {
        try {                  

            $pdo = new PDO('mysql:host=localhost;dbname=usuarios_test;charset=utf8', "root", "");

            $consultaAgregar = $pdo->prepare("UPDATE empleados SET correo = :correo, clave = :clave, nombre = :nombre, id_perfil = :id_perfil,foto = :foto,sueldo = :sueldo  WHERE id = :id");

            $consultaAgregar->bindValue(':correo', $this->correo, PDO::PARAM_STR);
            $consultaAgregar->bindValue(':clave', $this->clave, PDO::PARAM_STR);
            $consultaAgregar->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);           
            $consultaAgregar->bindValue(':foto', $this->foto, PDO::PARAM_STR);
            $consultaAgregar->bindValue(':id_perfil', $this->id_perfil, PDO::PARAM_INT);
            $consultaAgregar->bindValue(':sueldo', $this->sueldo, PDO::PARAM_INT);
            $consultaAgregar->bindValue("id", $this->id, PDO::PARAM_INT);

            $consultaAgregar->execute();

            if ($consultaAgregar->rowCount() > 0) 
            {
                $retorno = true;
            } else 
            {
                throw new Exception("NO SE PUDO MODIFICAR LA PERSONA");
            }
        } catch (Exception $ex) 
        {
            $retorno = false;
        }

        return $retorno;
    }
    public static function Eliminar(int $id): bool
    {
        return true;
    }

    public static function ValidarArchivoFoto(array $foto,string $nombre): string|null
    {       

        try {

            if ($foto != NULL) 
            {
                $foto_nombre = $foto["name"];
                $extension = pathinfo($foto_nombre, PATHINFO_EXTENSION);

                //VERIFICO EL TAMAÑO MAXIMO QUE PERMITO SUBIR

                if ($foto["size"] > 2000000) {
                    throw new Exception("El archivo es demasiado grande, inserte uno mas chico");
                }

                //OBTIENE EL TAMAÑO DE UNA IMAGEN, SI EL ARCHIVO NO ES UNA		
                //IMAGEN, RETORNA FALSE       

                $esImagen = getimagesize($_FILES["foto"]["tmp_name"]);

                if ($esImagen != false) 
                {
                    if ( $extension != "jpg" && $extension != "jpeg" && $extension != "gif" && $extension != "png") 
                    {
                        throw new Exception("Solo son permitidas imagenes con extension JPG, JPEG, PNG o GIF.");
                    }
                    else
                    {
                        $foto_nombre = $foto_nombre;                                            
                        $extension = pathinfo($foto_nombre, PATHINFO_EXTENSION);           
                        $nombreArchivo = $nombre . "." . date("his") . "." . $extension;
                        $retorno = "./empleados/fotos/" . $nombreArchivo;   

                    }
                } 
                else 
                {
                    throw new Exception("El archivo no es una imagen,POR FAVOR inserte una imagen");
                }
            } else {
                throw new Exception("Es necesario cargar una foto para poder realizar el ALTA");
            }

        } catch (Exception $ex) 
        {
            $retorno = null;
        }

        return $retorno;
    }

    public static function GuardarArchivoTemporal(array $pathFoto, string $pathDestino): bool
    {
        try 
        {
            if (isset($pathFoto)) 
            {
                $retorno =  move_uploaded_file($pathFoto["tmp_name"], $pathDestino);
            }

        } catch (Exception $ex) {
            $retorno = false;
        }

        return $retorno;
    }
}
