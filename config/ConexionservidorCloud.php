<?php 
require_once "globalservidor.php";

$_conexion = new mysqli(DB_HOST1,DB_USERNAME1,DB_PASSWORD1,DB_NAME1);

mysqli_query( $_conexion, 'SET NAMES "'.DB_ENCODE1.'"');

//Si tenemos un posible error en la conexión lo mostramos
if (mysqli_connect_errno())
{
	printf("Falló conexión a la base de datos: %s\n",mysqli_connect_error());
	exit();
}

if (!function_exists('_ejecutarConsulta'))
{
	function _ejecutarConsulta($sql)
	{
		global $_conexion;
		$query = $_conexion->query($sql);
		return $query;
	}

	function _ejecutarConsultaSimpleFila($sql)
	{
		global $_conexion;
		$query = $_conexion->query($sql);		
		$row = $query->fetch_assoc();
		return $row;
	}

	function _ejecutarConsulta_retornarID($sql)
	{
		global $_conexion;
		$query = $_conexion->query($sql);		
		return $_conexion->insert_id;			
	}

	function _limpiarCadena($str)
	{
		global $_conexion;
		$str = mysqli_real_escape_string($_conexion,trim($str));
		return htmlspecialchars($str);
	}
}
?>