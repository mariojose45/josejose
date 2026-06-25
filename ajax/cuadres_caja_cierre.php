<?php

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Cuadres_caja_cierre.php";
date_default_timezone_set('America/Guatemala');

$cuadreinicio = new CuadreInicio();

$total_efectivo_inicio = isset($_POST["total_efectivo_inicio"]) ? limpiarCadena($_POST["total_efectivo_inicio"]) : "";
$idcuadre_caja = isset($_POST["idcuadre_caja"]) ? limpiarCadena($_POST["idcuadre_caja"]) : "";

$fecha_hora_inicio = isset($_POST["fecha_hora_inicio"]) ? limpiarCadena($_POST["fecha_hora_inicio"]) : "";
$total_efectivo = isset($_POST["total_efectivo"]) ? limpiarCadena($_POST["total_efectivo"]) : "";


$total_ventas_diarias = isset($_POST["total_ventas_diarias"]) ? limpiarCadena($_POST["total_ventas_diarias"]) : "";
$total_efectivo_cierre_operaciones = isset($_POST["total_efectivo_cierre_operaciones"]) ? limpiarCadena($_POST["total_efectivo_cierre_operaciones"]) : "";
$operacion_efectvio = isset($_POST["operacion_efectvio"]) ? limpiarCadena($_POST["operacion_efectvio"]) : "";
$descripcion_numero_boleta = isset($_POST["descripcion_numero_boleta"]) ? limpiarCadena($_POST["descripcion_numero_boleta"]) : "";
$valor_operacion_efectivo = isset($_POST["valor_operacion_efectivo"]) ? limpiarCadena($_POST["valor_operacion_efectivo"]) : "";
$saldo_final_cierre_caja = isset($_POST["saldo_final_cierre_caja"]) ? limpiarCadena($_POST["saldo_final_cierre_caja"]) : "";

$total_ventas_diarias_efectivo = isset($_POST["total_ventas_diarias_efectivo"]) ? limpiarCadena($_POST["total_ventas_diarias_efectivo"]) : "";
$total_ventas_diarias_tarjeta = isset($_POST["total_ventas_diarias_tarjeta"]) ? limpiarCadena($_POST["total_ventas_diarias_tarjeta"]) : "";
$total_ventas_diarias_credito = isset($_POST["total_ventas_diarias_credito"]) ? limpiarCadena($_POST["total_ventas_diarias_credito"]) : "";
$total_ventas_diarias_transferencia = isset($_POST["res_total_ventas_diarias_transferencia"]) ? limpiarCadena($_POST["res_total_ventas_diarias_transferencia"]) : "";

$fecha_hora_cierre = date('Y-m-d H:i:s');
;
$total_efectivocierre = isset($_POST["total_efectivocierre"]) ? limpiarCadena($_POST["total_efectivocierre"]) : "";

$total_ventas_gastosEfectivo = isset($_POST["total_ventas_gastosEfectivo"]) ? limpiarCadena($_POST["total_ventas_gastosEfectivo"]) : "";
$total_ventas_AbonosVentas = isset($_POST["total_ventas_AbonosVentas"]) ? limpiarCadena($_POST["total_ventas_AbonosVentas"]) : "";
$total_ventas_NCVentas = isset($_POST["total_ventas_NCVentas"]) ? limpiarCadena($_POST["total_ventas_NCVentas"]) : "";

$idusuario = $_SESSION["idusuario"];

$hora_inicio = date('d-m-Y H:i:s');


switch ($_GET["op"]) {


    case 'guardaryeditar2':
        $rspta = $cuadreinicio->insertar2(
            $total_efectivo_inicio,
            $fecha_hora_cierre,
            $total_efectivocierre,
            $idusuario,
            $hora_inicio,
            $total_ventas_diarias,
            $total_efectivo_cierre_operaciones,
            $operacion_efectvio,
            $total_ventas_diarias_efectivo,
            $total_ventas_diarias_tarjeta,
            $total_ventas_diarias_credito,
            $total_ventas_diarias_transferencia,
            $total_ventas_gastosEfectivo,
            $total_ventas_AbonosVentas,
            $total_ventas_NCVentas
        );
        echo $rspta;
        break;

    case 'guardaryeditar2Parqueo':
        $rspta = $cuadreinicio->guardaryeditar2Parqueo(
            $total_efectivo_inicio,
            $fecha_hora_cierre,
            $total_efectivocierre,
            $idusuario,
            $hora_inicio,
            $total_ventas_diarias,
            $total_efectivo_cierre_operaciones,
            $operacion_efectvio,
            $total_ventas_diarias_efectivo,
            $total_ventas_diarias_tarjeta,
            $total_ventas_diarias_credito,
            $total_ventas_diarias_transferencia,
            $total_ventas_gastosEfectivo,
            $total_ventas_AbonosVentas,
            $total_ventas_NCVentas
        );
        echo $rspta;
        break;

    case 'anular':
        $rspta = $cuadreinicio->anular($idcuadre_caja);
        echo $rspta ? "Cierre anulado" : "Cierre no se puede anular";
        break;



    case 'listar':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idsucursal = $_REQUEST["idsucursal"];

        $rspta = $cuadreinicio->listar($fecha_inicio, $fecha_fin, $idsucursal);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $url = '../reportes/excuadreCajaCierre.php?id=';
            $data[] = array(
                "0" => ($reg->tipo_operacion == 'CIERRE CAJA') ? ' <button class="btn btn-danger" onclick="anular(' . $reg->idcuadre_caja . ')"><i class="fa fa-close"></i></button>' . '<a target="_blank" href="' . $url . $reg->idcuadre_caja . '"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>' : '<a target="_blank" href="' . $url . $reg->idcuadre_caja . '"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->fechainicio,
                "2" => $reg->hora_inicio,
                "3" => $reg->total_efectivo_inicio,
                "4" => $reg->total_efectivo,
                "5" => $reg->total_ventas_diarias,
                "6" => $reg->total_efectivo_cierre_operaciones,
                "7" => $reg->operacion_efectvio,
                "8" => $reg->descripcion_numero_boleta,
                "9" => $reg->valor_operacion_efectivo,
                "10" => $reg->saldo_final_cierre_caja,
                "11" => $reg->usuario,
                "12" => $reg->tipo_operacion
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

    case 'listarParqeuo':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idsucursal = $_REQUEST["idsucursal"];

        $rspta = $cuadreinicio->listar($fecha_inicio, $fecha_fin, $idsucursal);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $url = '../reportes/exTicket_CobroParqueoCierre.php?id=';
            $data[] = array(
                "0" => ($reg->tipo_operacion == 'CIERRE CAJA') ? ' <button class="btn btn-danger" onclick="anular(' . $reg->idcuadre_caja . ')"><i class="fa fa-close"></i></button>' . '<a target="_blank" href="' . $url . $reg->idcuadre_caja . '"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>' : '<a target="_blank" href="' . $url . $reg->idcuadre_caja . '"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->fechainicio,
                "2" => $reg->hora_inicio,
                "3" => $reg->total_efectivo_inicio,
                "4" => $reg->total_efectivo,
                "5" => $reg->total_ventas_diarias,
                "6" => $reg->total_efectivo_cierre_operaciones,
                "7" => $reg->operacion_efectvio,
                "8" => $reg->descripcion_numero_boleta,
                "9" => $reg->valor_operacion_efectivo,
                "10" => $reg->saldo_final_cierre_caja,
                "11" => $reg->usuario,
                "12" => $reg->tipo_operacion
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