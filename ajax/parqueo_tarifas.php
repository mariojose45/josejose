<?php

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/ParqueoTarifas.php";

$parqueo_tarifas = new ParqueoTarifas();

$id_tariasprecios = isset($_POST["id_tariasprecios"]) ? limpiarCadena($_POST["id_tariasprecios"]) : "";
$precio_fraccion_carro = isset($_POST["precio_fraccion_carro"]) ? limpiarCadena($_POST["precio_fraccion_carro"]) : "";
$precio_hora_carro = isset($_POST["precio_hora_carro"]) ? limpiarCadena($_POST["precio_hora_carro"]) : "";
$tarifa_dia_carro = isset($_POST["tarifa_dia_carro"]) ? limpiarCadena($_POST["tarifa_dia_carro"]) : "";
$tarifa_noche_carro = isset($_POST["tarifa_noche_carro"]) ? limpiarCadena($_POST["tarifa_noche_carro"]) : "";
$tarifa_evento_carro = isset($_POST["tarifa_evento_carro"]) ? limpiarCadena($_POST["tarifa_evento_carro"]) : "";
$precio_fraccion_moto = isset($_POST["precio_fraccion_moto"]) ? limpiarCadena($_POST["precio_fraccion_moto"]) : "";
$precio_hora_moto = isset($_POST["precio_hora_moto"]) ? limpiarCadena($_POST["precio_hora_moto"]) : "";
$tarifa_dia_moto = isset($_POST["tarifa_dia_moto"]) ? limpiarCadena($_POST["tarifa_dia_moto"]) : "";
$tarifa_noche_moto = isset($_POST["tarifa_noche_moto"]) ? limpiarCadena($_POST["tarifa_noche_moto"]) : "";
$tarifa_evento_moto = isset($_POST["tarifa_evento_moto"]) ? limpiarCadena($_POST["tarifa_evento_moto"]) : "";
$precio_fraccion_camion = isset($_POST["precio_fraccion_camion"]) ? limpiarCadena($_POST["precio_fraccion_camion"]) : "";
$precio_hora_camion = isset($_POST["precio_hora_camion"]) ? limpiarCadena($_POST["precio_hora_camion"]) : "";
$tarifa_dia_camion = isset($_POST["tarifa_dia_camion"]) ? limpiarCadena($_POST["tarifa_dia_camion"]) : "";
$tarifa_noche_camion = isset($_POST["tarifa_noche_camion"]) ? limpiarCadena($_POST["tarifa_noche_camion"]) : "";
$tarifa_evento_camion = isset($_POST["tarifa_evento_camion"]) ? limpiarCadena($_POST["tarifa_evento_camion"]) : "";
$no_correlativo_ticket = isset($_POST["no_correlativo_ticket"]) ? limpiarCadena($_POST["no_correlativo_ticket"]) : "";
$valor_ticket_extraviado = isset($_POST["valor_ticket_extraviado"]) ? limpiarCadena($_POST["valor_ticket_extraviado"]) : "";
$tiempo_gracia_ticket = isset($_POST["tiempo_gracia_ticket"]) ? limpiarCadena($_POST["tiempo_gracia_ticket"]) : "";
$cantidad_parqueos = isset($_POST["cantidad_parqueos"]) ? limpiarCadena($_POST["cantidad_parqueos"]) : "";
$idsucursal = isset($_POST["idsucursal"]) ? limpiarCadena($_POST["idsucursal"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':

        $rspta = $parqueo_tarifas->editar(
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
        );
        echo $rspta ? "Tarifas actualizadas" : "Tarifas no se pudo actualizar";

        break;



    case 'mostrar':
        $rspta = $parqueo_tarifas->mostrar($id_tariasprecios);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $parqueo_tarifas->listar();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => ($reg->condicion) ? '<button class="btn btn-warning" onclick="mostrar(' . $reg->id_tariasprecios . ')"><i class="fa fa-pencil"></i></button>' :
                    '<button class="btn btn-warning" onclick="mostrar(' . $reg->id_tariasprecios . ')"><i class="fa fa-pencil"></i></button>',
                "1" => $reg->sucursal,
                "2" => ($reg->condicion) ? '<span class="label bg-green">Activado</span>' :
                    '<span class="label bg-red">Desactivado</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;



}
?>