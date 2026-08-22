<?php
if (strlen(session_id()) < 1) {
    session_start();
}

require_once "../modelos/Kardex.php";
$kardex = new Kardex();
switch ($_GET["op"]) {
    case 'listarDetallado':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idsucursal = $_REQUEST["idsucursal"];
        $codigo_pro = $_REQUEST["codigo_pro"];

        $rspta = $kardex->listarDetallado($fecha_inicio, $fecha_fin, $idsucursal, $codigo_pro);

        $data = array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->idkardex,
                "1" => $reg->fecha,
                "2" => $reg->hora,
                "3" => $reg->codigo,
                "4" => $reg->nombre_articulo,
                "5" => $reg->sucursal,
                "6" => $reg->concepto,
                "7" => $reg->num_documento,
                "8" => number_format($reg->cantidad_existente, 6),
                "9" => number_format($reg->cantidad_modificacion, 6),
                "10" => $reg->tipo_modificacion,
                "11" => number_format($reg->cantidad_final, 6),
                "12" => $reg->responsable
            );
        }

        $results = array(
            "sEcho" => 1, // Información para el datatables
            "iTotalRecords" => count($data), // Enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), // Enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);
        break;


    case 'listarTodo':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idsucursal = $_REQUEST["idsucursal"];

        $rspta = $kardex->listarDetalladoTodo($fecha_inicio, $fecha_fin, $idsucursal);

        $data = array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->idkardex,
                "1" => $reg->fecha,
                "2" => $reg->hora,
                "3" => $reg->codigo,
                "4" => $reg->nombre_articulo,
                "5" => $reg->sucursal,
                "6" => $reg->concepto,
                "7" => $reg->num_documento,
                "8" => number_format($reg->cantidad_existente, 2),
                "9" => number_format($reg->cantidad_modificacion, 2),
                "10" => $reg->tipo_modificacion,
                "11" => number_format($reg->cantidad_final, 2),
                "12" => $reg->responsable
            );
        }

        $results = array(
            "sEcho" => 1, // Información para el datatables
            "iTotalRecords" => count($data), // Enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), // Enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);
        break;
}
ob_end_flush();
