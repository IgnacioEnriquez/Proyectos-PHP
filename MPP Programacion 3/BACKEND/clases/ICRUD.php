<?php

interface ICRUD
{
	static function TraerTodos() : array;	
	public function Agregar() : bool;
	function Modificar() : bool;	
    static function Eliminar(int $id) : bool;
}
