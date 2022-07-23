<?php

namespace IgnacioEnriquez;

use stdClass;
use Exception;

class Producto
{

    public string $nombre;
    public string $origen;

    public function __construct(string $nombre, string $origen)
    {
        $this->nombre = $nombre;
        $this->origen = $origen;
    }

    public function ToJSON(): string
    {
        $retorno = new stdClass();
        $retorno->nombre = $this->nombre;
        $retorno->origen = $this->origen;

        return json_encode($retorno);
    }

    public function GuardarEnJson(string $path): string
    {
        $retorno = new stdClass();
        $retorno->exito = true;
        $retorno->mensaje = "El producto fue guardado en el archivo correctamente";

        try {
            //ABRO EL ARCHIVO
            $ar = fopen($path, "a"); //A - append

            //ESCRIBO EN EL ARCHIVO
            $cant = fwrite($ar, $this->ToJSON() . ",\r\n");

            if ($cant <= 0) {
                throw new Exception("Ocurrio un error al escribir el archivo y no fue guardado el producto");
            }
        } catch (Exception $ex) {

            $retorno->exito = false;
            $retorno->mensaje = "GuardarEnArchivo : " . $ex->getMessage();
        } finally {
            fclose($ar);
            return json_encode($retorno);
        }
    }

    public static function TraerJSON(string $path): array
    {
        $array_productos = array();
        $contenido = "";

        //ABRO EL ARCHIVO
        $ar = fopen($path, "r");

        //LEO LINEA X LINEA DEL ARCHIVO 
        while (!feof($ar)) {
            $contenido .= fgets($ar);
        }

        //CIERRO EL ARCHIVO
        fclose($ar);

        $array_contenido = explode(",\r\n", $contenido);

        for ($i = 0; $i < count($array_contenido); $i++) {
            if ($array_contenido[$i] != "") {
                $producto =  json_decode($array_contenido[$i]);
                $nombre = $producto->nombre;
                $origen = $producto->origen;

                $producto = new Producto($nombre, $origen);
                array_push($array_productos, $producto);
            }
        }

        return $array_productos;
    }

    public static function VerificarProductoJSON(Producto $producto): string
    {
        $retorno = new stdClass();
        $retorno->exito = false;
        $retorno->mensaje = "";

        $contadorExactIguales = 0;

        $maxNombresIguales = 0;
        $nombreMasRepetido = "";

        $string_nombres = "";
        $string_origenes = "";


        try {
            $array_productos = Producto::TraerJSON(__DIR__ . "/../archivos/productos.json");

            if ($producto != null) 
            {
                if (count($array_productos) != 0) 
                {
                    foreach ($array_productos as $productoArray) {
                        $string_nombres .= $productoArray->nombre;
                        $string_origenes .= $productoArray->origen;
                    }

                    foreach ($array_productos as $productoArray) {
                        if ($productoArray->nombre == $producto->nombre && $productoArray->origen == $producto->origen) {
                            $contadorExactIguales++;
                            $retorno->exito = true;
                        } else {
                            $cantIguales = substr_count($string_nombres, $productoArray->nombre);

                            if ($cantIguales > $maxNombresIguales) {
                                $nombreMasRepetido = $productoArray->nombre;
                                $maxNombresIguales = $cantIguales;
                            }
                        }
                    }

                    if ($retorno->exito == true) 
                    {
                        $retorno->mensaje = "El producto existe en el archivo y tiene una cantidad de {$contadorExactIguales} productos iguales";
                    } else 
                    {
                        $retorno->mensaje = "El producto no existe en el archivo y el producto mas popular es {$nombreMasRepetido} con una cantidad de {$maxNombresIguales}";
                    }

                } else 
                {
                    $retorno -> mensaje = "Se debe agregar minimo 1 producto para verificar si existe";
                }
            } else {
                throw new Exception("El objeto producto pasado es NULL");
            }
        } catch (Exception $ex) 
        {
            $retorno->mensaje = "Error : " . $ex->getMessage();
        }

        return json_encode($retorno);
    }




    /* protected static function ObtenerPerfilBD($id): string
    {
        $retorno = "";

        try {
            $pdo = new PDO('mysql:host=localhost;dbname=usuarios_test;charset=utf8', "root", "");

            $consultaDescripcion = $pdo->prepare('SELECT descripcion FROM perfiles WHERE id = :id');

            $consultaDescripcion->bindValue(":id", $id, PDO::PARAM_INT);

            $consultaDescripcion->execute();

            if ($consultaDescripcion->rowCount() > 0) {
                $retornoBD = $consultaDescripcion->fetch();
                $retorno = $retornoBD["descripcion"];
            } else {
                throw new Exception("No existe ningun perfil con ese ID");
            }
        } catch (Exception $ex) {
            $retorno = $ex->getMessage();
        }

        return $retorno;
    }
    public function Agregar(): bool
    {
        try {

            $pdo = new PDO('mysql:host=localhost;dbname=usuarios_test;charset=utf8', "root", "");

            $consultaAgregar = $pdo->prepare("INSERT INTO usuarios (correo, clave, nombre, id_perfil) VALUES(:correo, :clave, :nombre, :id_perfil)");

            $consultaAgregar->bindValue(':correo', $this->correo, PDO::PARAM_STR);
            $consultaAgregar->bindValue(':clave', $this->clave, PDO::PARAM_STR);
            $consultaAgregar->bindValue(':id_perfil', $this->id_perfil, PDO::PARAM_INT);
            $consultaAgregar->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);

            $consultaAgregar->execute();

            if ($consultaAgregar->rowCount() > 0) {
                $retorno = true;
            } else {
                throw new Exception("NO SE AGREGO LA PERSONA A LA BASE");
            }
        } catch (Exception $ex) {
            $retorno = false;
        }

        return $retorno;
    }

    public static function TraerTodos(): array
    {
        $array_usuarios = array();

        try {

            $pdo = new PDO('mysql:host=localhost;dbname=usuarios_test;charset=utf8', "root", "");

            $consultaTraerTodos = $pdo->prepare('SELECT id,correo,clave,nombre,id_perfil FROM usuarios');

            $consultaTraerTodos->execute();

            $resultado = $consultaTraerTodos->fetchall(PDO::FETCH_ASSOC);

            foreach ($resultado as $fila) {
                $id = $fila["id"];
                $correo = $fila["correo"];
                $clave = $fila["clave"];
                $nombre = $fila["nombre"];
                $id_perfil = $fila["id_perfil"];
                $perfil = self::ObtenerPerfilBD($id_perfil);

                $usuario = new Usuario($nombre, $correo, $clave, $id, $id_perfil, $perfil);

                array_push($array_usuarios, $usuario);
            }
        } catch (Exception $ex) {
            throw new Exception(" TraerTodos : " . $ex->getMessage());
        }

        return $array_usuarios;
    }

    public static function TraerTodosSinClave(): array
    {
        $array_usuarios = Usuario::TraerTodos();

        foreach ($array_usuarios as $usuario) {
            $usuario->clave = "";
        }

        return $array_usuarios;
    }

    public static function TraerUnoBD(string $correo, string $clave): Usuario | null
    {
        $retorno = null;

        try {
            $pdo = new PDO('mysql:host=localhost;dbname=usuarios_test;charset=utf8', "root", "");

            $consultaTraerUno = $pdo->prepare('SELECT id,correo,clave,nombre,id_perfil FROM usuarios WHERE correo = :correo AND clave = :clave');

            $consultaTraerUno->bindValue(":correo", $correo, PDO::PARAM_STR);
            $consultaTraerUno->bindValue(":clave", $clave, PDO::PARAM_STR);

            $consultaTraerUno->execute();

            if ($consultaTraerUno->rowCount() > 0) {
                $usuarioTraido = $consultaTraerUno->fetch(PDO::FETCH_ASSOC);

                $id = $usuarioTraido["id"];
                $correo = $usuarioTraido["correo"];
                $clave = $usuarioTraido["clave"];
                $nombre = $usuarioTraido["nombre"];
                $id_perfil = $usuarioTraido["id_perfil"];
                $perfil = self::ObtenerPerfilBD($id_perfil);

                $retorno = new Usuario($nombre, $correo, $clave, $id, $id_perfil, $perfil);
            }
        } catch (Exception $ex) {
            throw new Exception(" TraerUno : " . $ex->getMessage());
        }

        return $retorno;
    }

    public function Modificar(): bool
    {
        try {


            $pdo = new PDO('mysql:host=localhost;dbname=usuarios_test;charset=utf8', "root", "");

            $consultaAgregar = $pdo->prepare("UPDATE usuarios SET correo = :correo, clave = :clave, nombre = :nombre, id_perfil = :id_perfil WHERE id = :id");

            $consultaAgregar->bindValue(':correo', $this->correo, PDO::PARAM_STR);
            $consultaAgregar->bindValue(':clave', $this->clave, PDO::PARAM_STR);
            $consultaAgregar->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consultaAgregar->bindValue(':id_perfil', $this->id_perfil, PDO::PARAM_INT);
            $consultaAgregar->bindValue("id", $this->id, PDO::PARAM_INT);

            $consultaAgregar->execute();

            if ($consultaAgregar->rowCount() > 0) {
                $retorno = true;
            } else {
                throw new Exception("NO SE PUDO MODIFICAR LA PERSONA");
            }
        } catch (Exception $ex) {
            $retorno = false;
        }

        return $retorno;
    }

    public static function Eliminar(int $id): bool
    {
        try {

            $pdo = new PDO('mysql:host=localhost;dbname=usuarios_test;charset=utf8', "root", "");

            $consultaAgregar = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");

            $consultaAgregar->bindValue("id", $id, PDO::PARAM_INT);

            $consultaAgregar->execute();

            if ($consultaAgregar->rowCount() > 0) {
                $retorno = true;
            } else {
                throw new Exception("NO SE PUDO ELIMINAR LA PERSONA");
            }
        } catch (Exception $ex) {
            $retorno = false;
        }

        return $retorno;
    }*/
}
