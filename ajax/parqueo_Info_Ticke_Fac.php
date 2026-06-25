<?php

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Parqueo_Info_Ticke_Fac.php";

$parquointicketfac = new Parquointicketfac();

$id_informacionticket = isset($_POST["id_informacionticket"]) ? limpiarCadena($_POST["id_informacionticket"]) : "";
$instruccionesdeticket = isset($_POST["instruccionesdeticket"]) ? limpiarCadena($_POST["instruccionesdeticket"]) : "";
$mensajefinal = isset($_POST["mensajefinal"]) ? limpiarCadena($_POST["mensajefinal"]) : "";
$correlativo_ticket = isset($_POST["correlativo_ticket"]) ? limpiarCadena($_POST["correlativo_ticket"]) : "";
$horario_atencion = isset($_POST["horario_atencion"]) ? limpiarCadena($_POST["horario_atencion"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':

        $rspta = $parquointicketfac->editar($id_informacionticket, $instruccionesdeticket, $mensajefinal, $correlativo_ticket, $horario_atencion);
        echo $rspta ? "Información actualizada" : "Información no se pudo actualizar";

        break;

    case 'mostrar':
        $rspta = $parquointicketfac->mostrar($id_informacionticket);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $parquointicketfac->listar();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => ($reg->condicion) ? '<button class="btn btn-warning" onclick="mostrar(' . $reg->id_informacionticket . ')"><i class="fa fa-pencil"></i></button>' :
                    '<button class="btn btn-warning" onclick="mostrar(' . $reg->id_informacionticket . ')"><i class="fa fa-pencil"></i></button>',
                "1" => $reg->nombre,
                "2" => $reg->instruccionesdeticket,
                "3" => $reg->mensajefinal,
                "4" => $reg->correlativo_ticket_factura,
                "5" => $reg->horario_atencion,
                "6" => ($reg->condicion) ? '<span class="label bg-green">Activado</span>' :
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