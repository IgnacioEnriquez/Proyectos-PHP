<?php

use IgnacioEnriquez\ProductoEnvasado;
require_once("./clases/ProductoEnvasado.php");

$tabla = isset($_GET["tabla"]) ? (string)$_GET["tabla"] : null ;

$arrayProductos = ProductoEnvasado::traer();

if($tabla === "mostrar")
{  
    $tablaHTML = '<html>
    <head><title>Listado de Productos Envasados</title></head>
    <body>
    
    <h1>Listado de cursos</h1>
    
    <table>
    <tr>
      <th style="padding:0 15px 0 15px;"><strong>ID</strong></th>      
      <th style="padding:0 15px 0 15px;"><strong>NOMBRE </strong></th>
      <th style="padding:0 15px 0 15px;"><strong>ORIGEN </strong></th>
      <th style="padding:0 15px 0 15px;"><strong>CODIGOBARRA </strong></th>
      <th style="padding:0 15px 0 15px;"><strong>PRECIO </strong></th>
      <th style="padding:0 15px 0 15px;"><strong>FOTO </strong></th>
    </tr>
    
    ';

    foreach ($arrayProductos as $Producto) 
    {
        $stringProducto = '<tr>
        <td style="padding:0 15px 0 15px;"><strong>'. $Producto -> id .'</strong></td>
        <td style="padding:0 15px 0 15px;"><strong>'. $Producto -> nombre .'</strong></td>
        <td style="padding:0 15px 0 15px;"><strong>'. $Producto -> origen .'</strong></td>
        <td style="padding:0 15px 0 15px;"><strong>'. $Producto -> codigoBarra .'</strong></td>
        <td style="padding:0 15px 0 15px;"><strong>'. $Producto -> precio .'</strong></td> 
        <td style="padding:0 15px 0 15px;"><img src="'. $Producto -> pathFoto .'" width="100" height="100"></td>
        </tr>
        
        ';

        $tablaHTML .= $stringProducto;       
    }

    $tablaHTML .= "</table>

    </body>
    </html>";
    
    echo $tablaHTML;

}
else
{
    echo json_encode($arrayProductos);
}
