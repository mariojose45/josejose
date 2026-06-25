<?php

ob_start();
require_once __DIR__ . '/../config/SessionControl.php';

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class ParqueoTarifas
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}


	//Implementamos un método para editar registros
	public function editar(
		$id_tariasprecios,
		$precio_fraccion_carro,
		$precio_hora_carro,
		$tarifa_dia_carro,
		$tarifa_noche_carro,
		$tarifa_evento_carro,
		$precio_fraccion_moto,
		$precio_hora_moto,
		$tarifa_dia_moto,
		$tarifa_noche_moto,
		$tarifa_evento_moto,
		$precio_fraccion_camion,
		$precio_hora_camion,
		$tarifa_dia_camion,
		$tarifa_noche_camion,
		$tarifa_evento_camion,
		$no_correlativo_ticket,
		$valor_ticket_extraviado,
		$tiempo_gracia_ticket,
		$cantidad_parqueos,
		$idsucursal
	) {
		$sql = "UPDATE tarifas_precios SET 
					precio_fraccion_carro='$precio_fraccion_carro',
					precio_hora_carro='$precio_hora_carro',
					tarifa_dia_carro='$tarifa_dia_carro',
					tarifa_noche_carro='$tarifa_noche_carro',
					tarifa_evento_carro='$tarifa_evento_carro',
					precio_fraccion_moto='$precio_fraccion_moto',
					precio_hora_moto='$precio_hora_moto',
					tarifa_dia_moto='$tarifa_dia_moto',
					tarifa_noche_moto='$tarifa_noche_moto',
					tarifa_evento_moto='$tarifa_evento_moto',
					no_correlativo_ticket='$no_correlativo_ticket',
					valor_ticket_extraviado='$valor_ticket_extraviado',
					tiempo_gracia_ticket='$tiempo_gracia_ticket',
					cantidad_parqueos='$cantidad_parqueos',
					idusuario_update='" . $_SESSION["idusuario"] . "',
					idsucursal='$idsucursal',
					precio_fraccion_camion='$precio_fraccion_camion',
					precio_hora_camion='$precio_hora_camion',
					tarifa_dia_camion='$tarifa_dia_camion',
					tarifa_noche_camion='$tarifa_noche_camion',
					tarifa_evento_camion='$tarifa_evento_camion'
					WHERE id_tariasprecios='$id_tariasprecios'";
		//print_r($sql);
		return ejecutarConsulta($sql);
	}


	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($id_tariasprecios)
	{
		$sql = "SELECT * FROM tarifas_precios WHERE id_tariasprecios='$id_tariasprecios'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT s.nombre AS sucursal,t.* FROM tarifas_precios t
				INNER JOIN sucursal s ON s.idsucursal=t.idsucursal";
		return ejecutarConsulta($sql);
	}

}

?>