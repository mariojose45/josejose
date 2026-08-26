
<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Cuentasporcobrar.php";

$cuentasporcobrar = new Cuentasporcobrar();

$idventa = isset($_POST["idventa"]) ? limpiarCadena($_POST["idventa"]) : "";
$idcliente = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
$total_venta = isset($_POST["total_venta"]) ? limpiarCadena($_POST["total_venta"]) : "";
$total_abono = isset($_POST["total_abono"]) ? limpiarCadena($_POST["total_abono"]) : "";
$saldo_venta = isset($_POST["saldo_venta"]) ? limpiarCadena($_POST["saldo_venta"]) : "";
$tipo_pago = isset($_POST["tipo_pago"]) ? limpiarCadena($_POST["tipo_pago"]) : "";
$fechapago = isset($_POST["fechapago"]) ? limpiarCadena($_POST["fechapago"]) : "";
$tipo_banco = isset($_POST["tipo_banco"]) ? limpiarCadena($_POST["tipo_banco"]) : "";
$numero_boleta = isset($_POST["numero_boleta"]) ? limpiarCadena($_POST["numero_boleta"]) : "";
$recibo_caja_numero = isset($_POST["recibo_caja_numero"]) ? limpiarCadena($_POST["recibo_caja_numero"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
$idusuario = $_SESSION["idusuario"];



////ventas x lotes
$idventa_lote = isset($_POST["idventa_lote"]) == true ? $_POST["idventa_lote"] : "";
$idcliente_lote = isset($_POST["idcliente_lote"]) == true ? $_POST["idcliente_lote"] : "";
$total_venta_lote = isset($_POST["total_venta_lote"]) == true ? $_POST["total_venta_lote"] : "";
$total_abono_lote = isset($_POST["total_abono_lote"]) == true ? $_POST["total_abono_lote"] : "";
$saldo_venta_lote = isset($_POST["saldo_venta_lote"]) == true ? $_POST["saldo_venta_lote"] : "";
$tipo_pago_lote = isset($_POST["tipo_pago_lote"]) == true ? $_POST["tipo_pago_lote"] : "";
$fechapago_lote = isset($_POST["fechapago_lote"]) == true ? $_POST["fechapago_lote"] : "";
$tipo_banco_lote = isset($_POST["tipo_banco_lote"]) == true ? $_POST["tipo_banco_lote"] : "";
$numero_boleta_lote = isset($_POST["numero_boleta_lote"]) == true ? $_POST["numero_boleta_lote"] : "";
$recibo_caja_numero_lote = isset($_POST["recibo_caja_numero_lote"]) == true ? $_POST["recibo_caja_numero_lote"] : "";
$descripcion_lote = isset($_POST["descripcion_lote"]) == true ? $_POST["descripcion_lote"] : "";
///fin

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idventa)) {
            $rspta = $cuentasporcobrar->insertar($idcliente);
            echo $rspta ? "Cuenta por Cobrar  registrada" : "Cuenta por Cobrar  no se pudo registrar";
        } else {
            $rspta = $cuentasporcobrar->editar($idventa, $idcliente, $total_venta, $total_abono, $saldo_venta, $tipo_pago, $fechapago, $tipo_banco, $numero_boleta, $recibo_caja_numero, $descripcion, $idusuario);
            echo $rspta ? "Cuenta por Cobrar  actualizada" : "Cuenta por Cobrar  no se pudo actualizar";
        }
        break;

    case 'guardaryeditarxlote':
        $rspta = $cuentasporcobrar->guardaryeditarxlote($idventa_lote, $idcliente_lote, $total_venta_lote, $total_abono_lote, $saldo_venta_lote, $tipo_pago_lote, $fechapago_lote, $tipo_banco_lote, $numero_boleta_lote, $recibo_caja_numero_lote, $descripcion_lote);
        echo $rspta ? "Cuenta por Cobrar  registrada" : "Cuenta por Cobrar  no se pudo registrar";

        break;



    case 'desactivar':
        $rspta = $cuentasporcobrar->desactivar($idventa);
        echo $rspta ? "Cta por cobrar Reversada" : "Cta por cobrar  no se puede Reversar";
        break;
        break;


    case 'mostrar':
        $rspta = $cuentasporcobrar->mostrar($idventa);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
        break;

    case 'listarFacturas':

        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $rspta = $cuentasporcobrar->listarFacturas($fecha_inicio, $fecha_fin);
        //Vamos a declarar un array
        $data = array();

        $url = '../reportes/exTicketctacobrar.php?id=';
        $url2 = '../reportes/exTicket58mmCtaxcobrar.php?id=';
        $url3 = '../reportes/exVentaFormatoCartaCtaxcobrar.php?id=';

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<a target="_blank" href="' . $url . $reg->idventa . '"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url2 . $reg->idventa . '"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url3 . $reg->idventa . '"  title="Carta"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->idventa,
                "2" => $reg->usuario_creacion,
                "3" => $reg->sucursal,
                "4" => $reg->nombre_cliente,
                "5" => $reg->fecha,
                "6" => $reg->total_venta,
                "7" => $reg->total_abono,
                "8" => $reg->saldo_venta,
                "9" => $reg->numero_pagos,
                "10" => $reg->numerodeabonos,
                "11" => ($reg->estadopago == 'Pago Aplicado') ? '<span class="label bg-green">Pago Aplicado</span>' :
                    '<span class="label bg-red">Pendiente Pago</span>'
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


    case 'listarAbonos':

        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $rspta = $cuentasporcobrar->listarAbonos($fecha_inicio, $fecha_fin);
        //Vamos a declarar un array
        $data = array();

        $url = '../reportes/exTicketctacobrar_Abono.php?id=';
        $url2 = '../reportes/exTicket58mmCtaxcobrar_Abono.php?id=';
        $url3 = '../reportes/exVentaFormatoCartaCtaxcobrar_Abono.php?id=';

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => ($reg->condicion == 1) ?
                    ('<button class="btn btn-danger" onclick="anular_abono(' . $reg->idventa . ',' . $reg->idcta_cobrar . ')">
                        <i class="fa fa-close"></i> Anular
                    </button>' .
                        ' <a target="_blank" href="' . $url . $reg->idcta_cobrar . '&idventa=' . $reg->idventa . '">
                        <button class="btn btn-success"><i class="fa fa-print"></i> </button>
                    </a>' .
                        '<a target="_blank" href="' . $url2 . $reg->idcta_cobrar . '&idventa=' . $reg->idventa . '" title="Ticket 58mm">
                        <button class="btn btn-info"><i class="fa fa-print"></i> </button>
                    </a>' .
                        '<a target="_blank" href="' . $url3 . $reg->idcta_cobrar . '&idventa=' . $reg->idventa . '" title="Carta">
                        <button class="btn btn-warning"><i class="fa fa-print"></i> </button>
                    </a>') : (' <a target="_blank" href="' . $url . $reg->idcta_cobrar . '&idventa=' . $reg->idventa . '">
                        <button class="btn btn-success"><i class="fa fa-print"></i> </button>
                    </a>' .
                        '<a target="_blank" href="' . $url2 . $reg->idcta_cobrar . '&idventa=' . $reg->idventa . '" title="Ticket 58mm">
                        <button class="btn btn-info"><i class="fa fa-print"></i> </button>
                    </a>' .
                        '<a target="_blank" href="' . $url3 . $reg->idcta_cobrar . '&idventa=' . $reg->idventa . '" title="Carta">
                        <button class="btn btn-warning"><i class="fa fa-print"></i> </button>
                    </a>'),
                "1" => $reg->idcta_cobrar,
                "2" => $reg->fecha_creacion,
                "3" => $reg->user,
                "4" => $reg->clientes,
                "5" => $reg->total_venta,
                "6" => $reg->total_abono,
                "7" => $reg->saldo_venta,
                "8" => $reg->idventa
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



    case 'listarVentaxlote':
        $idsector = $_REQUEST["idsector"];
        $idruta = $_REQUEST["idruta"];
        $rspta = $cuentasporcobrar->listarVentaxlote($idsector, $idruta);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="btn btn-warning btn-block" onclick="agregarDetalle(' . $reg->idventa . ',\'' . $reg->idcliente . '\',\'' . $reg->nombre_cliente . '\',\'' . $reg->tipo_comprobante . '\',\'' . $reg->numero_ecoFactura . '\',\'' . $reg->fecha . '\',\'' . $reg->total_venta . '\',\'' . $reg->total_abono . '\',\'' . $reg->saldo_venta . '\')"><span class="fa fa-plus"></span></button>',
                "1" => $reg->idventa,
                "2" => $reg->nombRuta,
                "3" => $reg->nomSector,
                "4" => $reg->nomSucursal,
                "5" => $reg->nomusuario,
                "6" => $reg->nombre_cliente,
                "7" => $reg->tipo_comprobante,
                "8" => $reg->numero_ecoFactura,
                "9" => $reg->fecha,
                "10" => $reg->total_venta,
                "11" => $reg->total_abono,
                "12" => $reg->saldo_venta,
                "13" => $reg->numero_pagos,
                "14" => ($reg->estadopago == 'Pago Aplicado') ? '<span class="label bg-green">Pago Aplicado</span>' :
                    '<span class="label bg-red">Pendiente Pago</span>'
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

    case 'listarVentaxloteGeneral':
        $rspta = $cuentasporcobrar->listarVentaxloteGeneral();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="btn btn-warning btn-block" onclick="agregarDetalle(' . $reg->idventa . ',\'' . $reg->idcliente . '\',\'' . $reg->nombre_cliente . '\',\'' . $reg->tipo_comprobante . '\',\'' . $reg->numero_ecoFactura . '\',\'' . $reg->fecha . '\',\'' . $reg->total_venta . '\',\'' . $reg->total_abono . '\',\'' . $reg->saldo_venta . '\')"><span class="fa fa-plus"></span></button>',
                "1" => $reg->idventa,
                "2" => $reg->nombRuta,
                "3" => $reg->nomSector,
                "4" => $reg->nomSucursal,
                "5" => $reg->nomusuario,
                "6" => $reg->nombre_cliente,
                "7" => $reg->tipo_comprobante,
                "8" => $reg->numero_ecoFactura,
                "9" => $reg->fecha,
                "10" => $reg->total_venta,
                "11" => $reg->total_abono,
                "12" => $reg->saldo_venta,
                "13" => $reg->numero_pagos,
                "14" => ($reg->estadopago == 'Pago Aplicado') ? '<span class="label bg-green">Pago Aplicado</span>' :
                    '<span class="label bg-red">Pendiente Pago</span>'
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

    case 'anular_abono':
        $idventa = $_REQUEST["idventa"];
        $idcta_cobrar = $_REQUEST["idcta_cobrar"];
        $rspta = $cuentasporcobrar->anular_abono($idventa, $idcta_cobrar);
        echo $rspta ? "Venta anulada" : "Venta no se puede anular";
        break;

    case 'validarBoleta':
        $numero_boleta = $_REQUEST["numero_boleta"];
        $rspta = $cuentasporcobrar->validarBoleta($numero_boleta);
        echo json_encode($rspta);
        break;
}
?>