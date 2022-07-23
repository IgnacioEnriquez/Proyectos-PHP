<?php

interface IBM
{
	function Modificar() : bool;	
	static function Eliminar(int $id) : bool;	
}
