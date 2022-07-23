<?php

require_once("./clases/Usuario.php");

try 
{    
    $array_usuarios = Usuario::TraerTodosSinClave();  

        $tabla =
            "<table>" .
            "<thead>";

        foreach ($array_usuarios[0] as $key => $value) 
        {
                       
            $tabla .= "<th>" . $key . "</th>";
            
        }

        $tabla .= "</thead>";

        $tabla .= "<tbody>";

        for ($i = 0; $i < count($array_usuarios); $i++) 
        {
            $tabla .= "<tr>";

            foreach ($array_usuarios[$i] as $key => $value) 
            {
                $tabla .= "<td>" . $value . "</td>";
            }

            $tabla .= "</tr>";
        }

        $tabla .= "</tbody>";
        $tabla .= "</table>";
        
        echo $tabla;
    
} catch (Exception $ex) 
{
    echo "ERROR : " . $ex -> getMessage();
    
}