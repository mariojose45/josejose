<?php

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Parqueo_Operaciones.php";

$parqueooperaciones = new ParqueoOperaciones();



$idlectura = isset($_POST["idlectura"]) ? limpiarCadena($_POST["idlectura"]) : "";
$tipo_vehiculo = isset($_POST["tipo_vehiculo"]) ? limpiarCadena($_POST["tipo_vehiculo"]) : "";
$placa = isset($_POST["placa"]) ? limpiarCadena($_POST["placa"]) : "";
$fecha_ingreso = isset($_POST["fecha_ingreso"]) ? limpiarCadena($_POST["fecha_ingreso"]) : "";
$numero_ticket = isset($_POST["numero_ticket"]) ? limpiarCadena($_POST["numero_ticket"]) : "";

//datos de modas cobro

$fecha_ingreso_cobro = isset($_POST["fecha_ingreso_cobro"]) ? limpiarCadena($_POST["fecha_ingreso_cobro"]) : "";
$tiempo_gracia_ticket_cobro = isset($_POST["tiempo_gracia_ticket_cobro"]) ? limpiarCadena($_POST["tiempo_gracia_ticket_cobro"]) : "";
$p_fraccion = isset($_POST["p_fraccion"]) ? limpiarCadena($_POST["p_fraccion"]) : "";
$p_hora = isset($_POST["p_hora"]) ? limpiarCadena($_POST["p_hora"]) : "";
$tarifa_dia = isset($_POST["tarifa_dia"]) ? limpiarCadena($_POST["tarifa_dia"]) : "";
$tarifa_noche = isset($_POST["tarifa_noche"]) ? limpiarCadena($_POST["tarifa_noche"]) : "";
$tarifa_evento = isset($_POST["tarifa_evento"]) ? limpiarCadena($_POST["tarifa_evento"]) : "";
$tipo_vehiculo_cobro = isset($_POST["tipo_vehiculo_cobro"]) ? limpiarCadena($_POST["tipo_vehiculo_cobro"]) : "";
$tip_evento_cobro = isset($_POST["tip_evento_cobro"]) ? limpiarCadena($_POST["tip_evento_cobro"]) : "";
$numeroplacaEvento = isset($_POST["numeroplacaEvento"]) ? limpiarCadena($_POST["numeroplacaEvento"]) : "";
$tipo_vehiculoEventos = isset($_POST["tipo_vehiculoEventos"]) ? limpiarCadena($_POST["tipo_vehiculoEventos"]) : "";
$fecha_cobro = isset($_POST["fecha_cobro"]) ? limpiarCadena($_POST["fecha_cobro"]) : "";
$tiempo_transcurrido_horas = isset($_POST["tiempo_transcurrido_horas"]) ? limpiarCadena($_POST["tiempo_transcurrido_horas"]) : "";
$tiempo_transcurrido_minutos = isset($_POST["tiempo_transcurrido_minutos"]) ? limpiarCadena($_POST["tiempo_transcurrido_minutos"]) : "";
$nit = isset($_POST["nit"]) ? limpiarCadena($_POST["nit"]) : "";
$nombre_cliente = isset($_POST["nombre_cliente"]) ? limpiarCadena($_POST["nombre_cliente"]) : "";
$direccion_cliente = isset($_POST["direccion_cliente"]) ? limpiarCadena($_POST["direccion_cliente"]) : "";
$idcliente = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
$tipo_documento_cliente = isset($_POST["tipo_documento_cliente"]) ? limpiarCadena($_POST["tipo_documento_cliente"]) : "";
$total_venta = isset($_POST["total_venta"]) ? limpiarCadena($_POST["total_venta"]) : "";
$cefectivo = isset($_POST["cefectivo"]) ? limpiarCadena($_POST["cefectivo"]) : "";
$ctarjeta = isset($_POST["ctarjeta"]) ? limpiarCadena($_POST["ctarjeta"]) : "";
$ctransferencia = isset($_POST["ctransferencia"]) ? limpiarCadena($_POST["ctransferencia"]) : "";
$ccredito = isset($_POST["ccredito"]) ? limpiarCadena($_POST["ccredito"]) : "";
$observacion_credito = isset($_POST["observacion_credito"]) ? limpiarCadena($_POST["observacion_credito"]) : "";
$rescambio = isset($_POST["rescambio"]) ? limpiarCadena($_POST["rescambio"]) : "";

switch ($_GET["op"]) {
    case 'guardarIngreso':
        $rspta = $parqueooperaciones->insertarLectura($tipo_vehiculo, $placa, $fecha_ingreso);
        echo $rspta;

        break;

    case 'guardarCobro':
        $rspta = $parqueooperaciones->guardarCobro(
            $idlectura,
            $fecha_ingreso_cobro,
            $tiempo_gracia_ticket_cobro,
            $p_fraccion,
            $p_hora,
            $tarifa_dia,
            $tarifa_noche,
            $tarifa_evento,
            $tipo_vehiculo_cobro,
            $numero_ticket,
            $tip_evento_cobro,
            $numeroplacaEvento,
            $tipo_vehiculoEventos,
            $fecha_cobro,
            $tiempo_transcurrido_horas,
            $tiempo_transcurrido_minutos,
            $nit,
            $nombre_cliente,
            $direccion_cliente,
            $idcliente,
            $tipo_documento_cliente,
            $total_venta,
            $cefectivo,
            $ctarjeta,
            $ctransferencia,
            $ccredito,
            $observacion_credito,
            $rescambio
        );
        echo $rspta;

        break;

    case 'desactivar':
        $rspta = $parqueooperaciones->desactivar($idlectura);
        echo $rspta ? "Lectura Desactivada" : "Desactivada no se puede desactivar";
        break;
        break;



    case 'validarnumero_ticket':
        $rspta = $parqueooperaciones->validarnumero_ticket($numero_ticket);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;

    case 'tarifaDiaNocheEvento':
        $rspta = $parqueooperaciones->tarifadia();
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;

    case 'listar':
        $fecha_inicio_lectura = $_REQUEST["fecha_inicio_lectura"];
        $fecha_fin_lectura = $_REQUEST["fecha_fin_lectura"];
        $rspta = $parqueooperaciones->listar($fecha_inicio_lectura, $fecha_fin_lectura);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $url = '../reportes/exTicket_LecturaParqueo.php?id=';
            $data[] = array(

                "0" => ($reg->condicion) ? ' <button class="btn btn-danger" onclick="desactivar(' . $reg->idlectura . ')"><i class="fa fa-close"></i></button>' .
                    '<a target="_blank" href="' . $url . $reg->idlectura . '" title="Ticket 79mm"><button class="btn btn-success" title="Imprimir Ticket 79mm"><i class="fa fa-print"></i> </button> </a>' :
                    '<a target="_blank" href="' . $url . $reg->idlectura . '" title="Ticket 79mm"><button class="btn btn-success" title="Imprimir Ticket 79mm"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->idlectura,
                "2" => $reg->tipo_vehiculo,
                "3" => $reg->placa,
                "4" => $reg->fecha_ingreso,
                "5" => $reg->usuario,
                "6" => ($reg->condicion == '1') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>'
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




    case 'listarxsucursalParqueo':
        $fecha_inicio_lectura = $_REQUEST["fecha_inicio_lectura"];
        $fecha_fin_lectura = $_REQUEST["fecha_fin_lectura"];
        $idsucursal2 = $_REQUEST["idsucursal2"];
        $rspta = $parqueooperaciones->listarxsucursalParqueo($fecha_inicio_lectura, $fecha_fin_lectura, $idsucursal2);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $url = '../reportes/exTicket_LecturaParqueo.php?id=';
            $data[] = array(

                "0" => ($reg->condicion) ? ' <button class="btn btn-danger" onclick="desactivar(' . $reg->idlectura . ')"><i class="fa fa-close"></i></button>' .
                    '<a target="_blank" href="' . $url . $reg->idlectura . '" title="Ticket 79mm"><button class="btn btn-success" title="Imprimir Ticket 79mm"><i class="fa fa-print"></i> </button> </a>' :
                    '<a target="_blank" href="' . $url . $reg->idlectura . '" title="Ticket 79mm"><button class="btn btn-success" title="Imprimir Ticket 79mm"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->tipo_vehiculo,
                "2" => $reg->placa,
                "3" => $reg->fecha_ingreso,
                "4" => $reg->usuario,
                "5" => ($reg->condicion == '1') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>'
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





    case 'listarFac':
        $fecha_inicio_lectura_fac = $_REQUEST["fecha_inicio_lectura_fac"];
        $fecha_fin_lectura_fac = $_REQUEST["fecha_fin_lectura_fac"];
        $rspta = $parqueooperaciones->listarFac($fecha_inicio_lectura_fac, $fecha_fin_lectura_fac);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $url = '../reportes/exTicket_CobroParqueo.php?id=';
            $data[] = array(

                "0" => ($reg->estado == 'Aceptado') ? ' <button class="btn btn-danger" onclick="desactivar(' . $reg->idcobros_tickets . ')"><i class="fa fa-close"></i></button>' .
                    '<a target="_blank" href="' . $url . $reg->idcobros_tickets . '" title="Ticket 79mm"><button class="btn btn-success" title="Imprimir Ticket 79mm"><i class="fa fa-print"></i> </button> </a>' :
                    '<a target="_blank" href="' . $url . $reg->idcobros_tickets . '" title="Ticket 79mm"><button class="btn btn-success" title="Imprimir Ticket 79mm"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->idlectura,
                "2" => $reg->idcobros_tickets,
                "3" => $reg->nombre_cliente,
                "4" => $reg->tipo_documentoCliente,
                "5" => $reg->num_docCliente,
                "6" => $reg->placa,
                "7" => $reg->fecha_ingreso_cobro,
                "8" => $reg->fecha_cobro,
                "9" => $reg->tiempo_transcurrido_horas . "-" . $reg->tiempo_transcurrido_minutos,
                "10" => number_format((float) $reg->total_venta, 2, '.', ','),
                "11" => number_format((float) $reg->cefectivo, 2, '.', ','),
                "12" => number_format((float) $reg->ctarjeta, 2, '.', ','),
                "13" => number_format((float) $reg->ctransferencia, 2, '.', ','),
                "14" => number_format((float) $reg->ccredito, 2, '.', ','),
                "15" => number_format((float) $reg->rescambio, 2, '.', ','),
                "16" => $reg->nombreUsuario,
                "17" => $reg->serie_ecoFactura,
                "18" => $reg->numero_ecoFactura,
                "19" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>'
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




    case 'listarFacxSucursal':
        $fecha_inicio_lectura_fac = $_REQUEST["fecha_inicio_lectura_fac"];
        $fecha_fin_lectura_fac = $_REQUEST["fecha_fin_lectura_fac"];
        $idsucursal3 = $_REQUEST["idsucursal3"];
        $rspta = $parqueooperaciones->listarFacxSucursal($fecha_inicio_lectura_fac, $fecha_fin_lectura_fac, $idsucursal3);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $url = '../reportes/exTicket_CobroParqueo.php?id=';
            $data[] = array(

                "0" => ($reg->estado == 'Aceptado') ? ' <button class="btn btn-danger" onclick="desactivar(' . $reg->idcobros_tickets . ')"><i class="fa fa-close"></i></button>' .
                    '<a target="_blank" href="' . $url . $reg->idcobros_tickets . '" title="Ticket 79mm"><button class="btn btn-success" title="Imprimir Ticket 79mm"><i class="fa fa-print"></i> </button> </a>' :
                    '<a target="_blank" href="' . $url . $reg->idcobros_tickets . '" title="Ticket 79mm"><button class="btn btn-success" title="Imprimir Ticket 79mm"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->idlectura,
                "2" => $reg->idcobros_tickets,
                "3" => $reg->nombre_cliente,
                "4" => $reg->tipo_documentoCliente,
                "5" => $reg->num_docCliente,
                "6" => $reg->placa,
                "7" => $reg->fecha_ingreso_cobro,
                "8" => $reg->fecha_cobro,
                "9" => $reg->tiempo_transcurrido_horas . "-" . $reg->tiempo_transcurrido_minutos,
                "10" => number_format((float) $reg->total_venta, 2, '.', ','),
                "11" => number_format((float) $reg->cefectivo, 2, '.', ','),
                "12" => number_format((float) $reg->ctarjeta, 2, '.', ','),
                "13" => number_format((float) $reg->ctransferencia, 2, '.', ','),
                "14" => number_format((float) $reg->ccredito, 2, '.', ','),
                "15" => number_format((float) $reg->rescambio, 2, '.', ','),
                "16" => $reg->nombreUsuario,
                "17" => $reg->serie_ecoFactura,
                "18" => $reg->numero_ecoFactura,
                "19" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>'
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


    case 'totalesDashboardParqueo':
        $idsucursal = isset($_REQUEST["idsucursal"]) ? $_REQUEST["idsucursal"] : "";
        $fecha_inicio = isset($_REQUEST["fecha_inicio"]) ? $_REQUEST["fecha_inicio"] : "";
        $fecha_fin = isset($_REQUEST["fecha_fin"]) ? $_REQUEST["fecha_fin"] : "";
        $rspta = $parqueooperaciones->totalesDashboardParqueo($idsucursal, $fecha_inicio, $fecha_fin);
        $total_ingresos = 0;
        $vehiculos_cobrados = 0;
        $lecturas_pendientes = 0;
        $tiempo_promedio_minutos = 0;

        if ($reg = $rspta->fetch_object()) {
            $total_ingresos = $reg->total_ingresos;
            $vehiculos_cobrados = $reg->vehiculos_cobrados;
            $lecturas_pendientes = $reg->lecturas_pendientes;
            $tiempo_promedio_minutos = $reg->tiempo_promedio_minutos;
        }

        // Formatear minutos en horas y minutos (Ej: 1h 30m)
        $horas = floor($tiempo_promedio_minutos / 60);
        $minutos = round($tiempo_promedio_minutos % 60);
        $tiempo_formateado = ($horas > 0 ? $horas . "h " : "") . $minutos . "m";

        echo json_encode(array(
            "ingresos" => number_format((float) $total_ingresos, 2, '.', ','),
            "vehiculos" => $vehiculos_cobrados,
            "ocupacion" => $lecturas_pendientes,
            "tiempo" => $tiempo_formateado
        ));
        break;

    case 'graficoLecturasParqueo':
        $idsucursal = isset($_REQUEST["idsucursal"]) ? $_REQUEST["idsucursal"] : "";
        $fecha_inicio = isset($_REQUEST["fecha_inicio"]) ? $_REQUEST["fecha_inicio"] : "";
        $fecha_fin = isset($_REQUEST["fecha_fin"]) ? $_REQUEST["fecha_fin"] : "";
        $rspta = $parqueooperaciones->graficoLecturasParqueo($idsucursal, $fecha_inicio, $fecha_fin);
        $fechas = array();
        $totales = array();
        while ($reg = $rspta->fetch_object()) {
            $fechas[] = $reg->fecha;
            $totales[] = $reg->total;
        }
        echo json_encode(array("fechas" => $fechas, "totales" => $totales));
        break;

    case 'graficoCobrosParqueo':
        $idsucursal = isset($_REQUEST["idsucursal"]) ? $_REQUEST["idsucursal"] : "";
        $fecha_inicio = isset($_REQUEST["fecha_inicio"]) ? $_REQUEST["fecha_inicio"] : "";
        $fecha_fin = isset($_REQUEST["fecha_fin"]) ? $_REQUEST["fecha_fin"] : "";
        $rspta = $parqueooperaciones->graficoCobrosParqueo($idsucursal, $fecha_inicio, $fecha_fin);
        $fechas = array();
        $totales = array();
        while ($reg = $rspta->fetch_object()) {
            $fechas[] = $reg->fecha;
            $totales[] = $reg->total;
        }
        echo json_encode(array("fechas" => $fechas, "totales" => $totales));
        break;

    case 'listarTotalUsuariosParqueo':
        $idsucursal = isset($_REQUEST["idsucursal"]) ? $_REQUEST["idsucursal"] : "";
        $fecha_inicio = isset($_REQUEST["fecha_inicio"]) ? $_REQUEST["fecha_inicio"] : "";
        $fecha_fin = isset($_REQUEST["fecha_fin"]) ? $_REQUEST["fecha_fin"] : "";

        $rspta = $parqueooperaciones->listarTotalUsuariosParqueo($idsucursal, $fecha_inicio, $fecha_fin);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->usuario,
                "1" => $reg->sucursal,
                "2" => "Q/. " . number_format((float) $reg->total_venta, 2, '.', ',')
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