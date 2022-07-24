<?php

namespace IgnacioEnriquez;

require_once(__DIR__ . "./Producto.php");
require_once(__DIR__ . "./IParte1.php");
require_once(__DIR__ . "./IParte2.php");
require_once(__DIR__ . "./IParte3.php");




use stdClass;
use Exception;
use PDO;
use PDOException;


// Falta Agregar Producto Envasado
class ProductoEnvasado extends Producto implements IParte1,IParte2,IParte3
{
    public int $id;
    public int $codigoBarra;
    public int $precio;
    public string | null $pathFoto;

    public function __construct(string $nombre = "No Asignado", string $origen = "No Asignado",int $codigoBarra = 0,int $precio = -1, string $pathFoto = NULL, int $id = 0,)
    {
        parent::__construct($nombre,$origen);
        $this->id = $id;
        $this->codigoBarra = $codigoBarra;
        $this->precio = $precio;
        $this->pathFoto = $pathFoto;
    }

    public function toJSON(): string
    {
        $retorno = new stdClass();

        $retorno->nombre = $this->nombre;
        $retorno->origen = $this->origen;
        $retorno->id = $this->id;
        $retorno->codigoBarra = $this->codigoBarra;
        $retorno->precio = $this->precio;
        $retorno->pathFoto = $this->pathFoto;

        return json_encode($retorno);
    }

    public function Agregar(): bool
    {       
        $retorno = true;

        try 
        {
            $pdo = new PDO('mysql:host=localhost;dbname=productos_bd;charset=utf8', "root", "");

            $consulta = $pdo->prepare('INSERT INTO productos (codigo_barra, nombre, origen, precio, foto)
             VALUES(:codigoBarra, :nombre, :origen, :precio, :foto)');

            $consulta->bindValue(':codigoBarra', $this->codigoBarra, PDO::PARAM_INT);
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':origen', $this->origen, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
            $consulta->bindValue(':foto', $this->pathFoto, PDO::PARAM_STR);

            $consulta->execute();
         
        } catch (PDOException $ex) 
        {
            $retorno = false;            
        }

        return $retorno;
    }

    public static function Traer(): array
    {
        $retornoBD = array();
        $retornoArray = array();

        try 
        {
            $pdo = new PDO('mysql:host=localhost;dbname=productos_bd;charset=utf8', "root", "");

            $consulta = $pdo->prepare('SELECT id, codigo_barra AS codigoBarra, nombre AS nombre, origen AS origen,
            precio AS precio, foto AS pathFoto FROM productos ');

            $consulta->execute();

            $retornoBD = $consulta->fetchAll(PDO::FETCH_OBJ);

            foreach($retornoBD as $productoBD)
            {
                $idProducto = $productoBD -> id;
                $codigoBarraProducto = $productoBD -> codigoBarra;
                $nombreProducto = $productoBD -> nombre;              
                $origenProducto = $productoBD -> origen;
                $precioProducto = $productoBD -> precio;

                if(isset($productoBD -> pathFoto))
                {                
                    $pathFotoProducto = $productoBD -> pathFoto;           
                }
                else
                {                
                    $pathFotoProducto = "NULL";             
                }


                $productoConvertido = new ProductoEnvasado($nombreProducto,$origenProducto,$codigoBarraProducto,$precioProducto,$pathFotoProducto,$idProducto);

                array_push($retornoArray,$productoConvertido);
            }


        } catch (PDOException $th) 
        {
            $retornoArray = array();
            //En caso de un error retorno un array vacio          
        }

        return $retornoArray;
    }

    public static function Eliminar(int $id): bool
    {
        $retorno = true;

        try {

            $pdo = new PDO('mysql:host=localhost;dbname=productos_bd;charset=utf8', "root", "");

            $consulta = $pdo->prepare('DELETE FROM productos WHERE id = :id');

            $consulta->bindValue(':id', $id, PDO::PARAM_INT);

            $consulta->execute();                  

            if($consulta ->rowCount() == 0)
            {    
                throw new Exception("No se elimino ningun producto con ese ID");                     
            }    

        } catch (PDOException $ex) 
        {
            $retorno = false;              
        }

        return $retorno;
    }
   
    public function Modificar(): bool
    {
        $retorno = true;

        try 
        {
            $pdo = new PDO('mysql:host=localhost;dbname=productos_bd;charset=utf8', "root", "");

            $consulta = $pdo->prepare("UPDATE productos SET codigo_barra = :codigoBarra, nombre = :nombre, 
            origen = :origen, precio = :precio, foto = :pathFoto WHERE id = :id");


            $consulta->bindValue(':codigoBarra', $this->codigoBarra, PDO::PARAM_INT);
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':origen', $this->origen, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
            $consulta->bindValue(':pathFoto', $this->pathFoto, PDO::PARAM_STR);
            $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);

            $consulta->execute();

            if($consulta ->rowCount() == 0)
            {    
                throw new Exception("No se modifico ningun producto");                     
            }                    
            

        } catch (PDOException $ex) 
        {
            $retorno = false;
        }

        return $retorno;
    }

    public function Existe(array $productos): bool
    {
        $retorno = false;

        foreach ($productos as $producto) 
        {
            if($producto -> nombre === $this->nombre && $producto -> origen === $this->origen)
            {
                $retorno = true;
                break;
            }            
        }
        
        return $retorno;
    }

}
