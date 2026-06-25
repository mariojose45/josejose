<?php
ob_start();
if (strlen(session_id()) < 1) {
    session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Nomina_pagos.php";

$nominapagos = new NominaPagos();

$idnomina_pagos = isset($_POST["idnomina_pagos"]) ? limpiarCadena($_POST["idnomina_pagos"]) : "";
$fecha_inicio = isset($_POST["fecha_inicio"]) ? limpiarCadena($_POST["fecha_inicio"]) : "";
$fecha_fin = isset($_POST["fecha_fin"]) ? limpiarCadena($_POST["fecha_fin"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
$detalles_json = isset($_POST["detalles_json"]) ? json_decode($_POST["detalles_json"], true) : [];

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idnomina_pagos)) {
            $rspta = $nominapagos->insertar($fecha_inicio, $fecha_fin, $descripcion, $detalles_json);
            echo $rspta;
        } else {
            $rspta = $nominapagos->editar($idnomina_pagos, $fecha_inicio, $fecha_fin, $descripcion, $detalles_json);
            echo $rspta;
        }
        break;

    case 'guardaryeditar_adelanto_quincenal':
        if (empty($idnomina_pagos)) {
            $rspta = $nominapagos->insertar_adelantos_quincenal($fecha_inicio, $fecha_fin, $descripcion, $detalles_json);
            echo $rspta;
        } else {
            $rspta = $nominapagos->editar_adelantos_quincenal($idnomina_pagos, $fecha_inicio, $fecha_fin, $descripcion, $detalles_json);
            echo $rspta;
        }
        break;

    case 'desactivar':
        $rspta = $nominapagos->desactivar($idnomina_pagos);
        echo $rspta ? "Nomina de Pagos Desactivada" : "Nomina de Pagos no se puede desactivar";
        break;


    case 'mostrar':
        $rspta = $nominapagos->mostrar($idnomina_pagos);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;

    case 'detallenominapagos':
        $rspta = $nominapagos->detallenominapagos($idnomina_pagos);
        echo json_encode($rspta);
        break;

    case 'listar':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $rspta = $nominapagos->listar($fecha_inicio, $fecha_fin);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $url = '../reportes/exNominapagos.php?id=';
            $url1 = '../reportes/exNominapagosComprobante.php?id=';
            $data[] = array(
                "0" => ($reg->condicion) ? '<button class="btn btn-warning" onclick="mostrar(' . $reg->idnomina_pagos . ')"><i class="fa fa-pencil"></i></button>' .
                    ' <button class="btn btn-danger" onclick="desactivar(' . $reg->idnomina_pagos . ')"><i class="fa fa-close"></i></button>' .
                    '<a target="_blank" title="Nomina de Pagos" href="' . $url . $reg->idnomina_pagos . '"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" title="Comprobante de Pagos" href="' . $url1 . $reg->idnomina_pagos . '"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>' :
                    '<a target="_blank" title="Nomina de Pagos" href="' . $url . $reg->idnomina_pagos . '"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" title="Comprobante de Pagos" href="' . $url1 . $reg->idnomina_pagos . '"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->idnomina_pagos,
                "2" => $reg->fechainicio,
                "3" => $reg->fechafin,
                "4" => $reg->descripcion,
                "5" => $reg->nombre_usuario,
                "6" => $reg->fecha_creacion,
                "7" => $reg->usuario_mod,
                "8" => $reg->fecha_modificacion,
                "9" => $reg->usuario_delete,
                "10" => $reg->fecha_delete,
                "11" => ($reg->condicion) ? '<span class="label bg-green">Activado</span>' :
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

    case 'listarEmpleados':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $rspta = $nominapagos->listarEmpleados($fecha_inicio, $fecha_fin);
        echo json_encode($rspta);
        break;

    case 'listar_adelantos_quincenal':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $rspta = $nominapagos->listar_adelantos_quincenal($fecha_inicio, $fecha_fin);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $url1 = '../reportes/exNominapagosComprobanteAdelantoQuincenal.php?id=';
            $opciones = "";
            if ($reg->condicion == 1) {
                $opciones = '
                    <a target="_blank" href="' . $url1 . $reg->idnomina_pagos_quincenales . '" class="btn btn-info btn-xs" title="Comprobante de Pago">
                        <i class="fa fa-file-text"></i> Comprobante
                    </a>
                    <button class="btn btn-danger btn-xs" onclick="anular_adelanto(' . $reg->idnomina_pagos_quincenales . ')" title="Anular Nómina">
                        <i class="fa fa-close"></i> Anular
                    </button>
                ';
            } else {
                $opciones = '<span class="label bg-red">Sin opciones</span>';
            }
            $data[] = array(
                "0" => $opciones,
                "1" => $reg->idnomina_pagos_quincenales,
                "2" => $reg->fechainicio,
                "3" => $reg->fechafin,
                "4" => $reg->descripcion,
                "5" => $reg->nombre_usuario,
                "6" => $reg->fecha_creacion,
                "7" => $reg->usuario_mod,
                "8" => $reg->fecha_modificacion,
                "9" => $reg->usuario_delete,
                "10" => $reg->fecha_delete,
                "11" => ($reg->condicion) ? '<span class="label bg-green">Activado</span>' :
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

    case 'desactivar_adelanto_quincenal':
        $idnomina_pagos_quincenales = $_REQUEST["idnomina_pagos_quincenales"];
        $rspta = $nominapagos->desactivar_adelanto_quincenal($idnomina_pagos_quincenales);
        echo $rspta ? "Pago Quincenal Desactivada" : "Pago Quincenal no se puede desactivar";
        break;
}
?>