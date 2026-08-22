<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión



require_once "../modelos/Venta.php";

$venta = new Venta();

$idventa = isset($_POST["idventa"]) ? limpiarCadena($_POST["idventa"]) : "";
$idcliente = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
$codigo_cliente = isset($_POST["codigo_cliente"]) ? limpiarCadena($_POST["codigo_cliente"]) : "";
$nit = isset($_POST["nit"]) ? limpiarCadena($_POST["nit"]) : "";
$nombre_cliente = isset($_POST["nombre_cliente"]) ? limpiarCadena($_POST["nombre_cliente"]) : "";
$telefono_cliente = isset($_POST["telefono_cliente"]) ? limpiarCadena($_POST["telefono_cliente"]) : "";
$direccion_cliente = isset($_POST["direccion_cliente"]) ? limpiarCadena($_POST["direccion_cliente"]) : "";
$correo_cliente = isset($_POST["correo_cliente"]) ? limpiarCadena($_POST["correo_cliente"]) : "";
$tipo_cliente = isset($_POST["tipo_cliente"]) ? limpiarCadena($_POST["tipo_cliente"]) : "";
$tipo_documento_cliente = isset($_POST["tipo_documento_cliente"]) ? limpiarCadena($_POST["tipo_documento_cliente"]) : "";
$idusuario = $_SESSION["idusuario"];
$idcotizacion = isset($_POST["idcotizacion"]) ? limpiarCadena($_POST["idcotizacion"]) : "";
$fecha_hora = isset($_POST["fecha_hora"]) ? limpiarCadena($_POST["fecha_hora"]) : "";
$forma_pago = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
$tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";


$total_venta = isset($_POST["total_venta"]) ? limpiarCadena($_POST["total_venta"]) : "";
$total_ventades = isset($_POST["total_ventades"]) ? limpiarCadena($_POST["total_ventades"]) : "";
$cefectivo = isset($_POST["cefectivo"]) ? limpiarCadena($_POST["cefectivo"]) : "";
$ccredito = isset($_POST["ccredito"]) ? limpiarCadena($_POST["ccredito"]) : "";
$ctarjeta = isset($_POST["ctarjeta"]) ? limpiarCadena($_POST["ctarjeta"]) : "";
$ctransferencia = isset($_POST["ctransferencia"]) ? limpiarCadena($_POST["ctransferencia"]) : "";
$rescambio = isset($_POST["rescambio"]) ? limpiarCadena($_POST["rescambio"]) : "";

///datos de tarejta
$valor_tarjeta = isset($_POST["valor_tarjeta"]) ? limpiarCadena($_POST["valor_tarjeta"]) : "";
$tipo_pagoBacVisaNet = isset($_POST["tipo_pagoBacVisaNet"]) ? limpiarCadena($_POST["tipo_pagoBacVisaNet"]) : "";
$opcionesAdicionales = isset($_POST["opcionesAdicionales"]) ? limpiarCadena($_POST["opcionesAdicionales"]) : "";
$observacion_credito = isset($_POST["observacion_credito"]) ? limpiarCadena($_POST["observacion_credito"]) : "";
//restaurante
$id_add_orden = isset($_POST["id_add_orden"]) ? limpiarCadena($_POST["id_add_orden"]) : "";
$propina = isset($_POST["propina"]) ? limpiarCadena($_POST["propina"]) : "";




//////NOTAS DE CREDITO
$autorizacionEcoFactura_venta = isset($_POST["autorizacionEcoFactura_venta"]) ? limpiarCadena($_POST["autorizacionEcoFactura_venta"]) : "";
$serie_comprobante_venta = isset($_POST["serie_comprobante_venta"]) ? limpiarCadena($_POST["serie_comprobante_venta"]) : "";
$numero_ecoFactura_venta = isset($_POST["numero_ecoFactura_venta"]) ? limpiarCadena($_POST["numero_ecoFactura_venta"]) : "";
$fecha_hora_nc = isset($_POST["fecha_hora_nc"]) ? limpiarCadena($_POST["fecha_hora_nc"]) : "";
$motivo_nc = isset($_POST["motivo_nc"]) ? limpiarCadena($_POST["motivo_nc"]) : "";
//////

///GastoaVenta
$TotalEfectivoDisponible_GastoaVenta = isset($_POST["TotalEfectivoDisponible_GastoaVenta"]) ? limpiarCadena($_POST["TotalEfectivoDisponible_GastoaVenta"]) : "";
$totalAcumuladoGasots_GastoaVenta = isset($_POST["totalAcumuladoGasots_GastoaVenta"]) ? limpiarCadena($_POST["totalAcumuladoGasots_GastoaVenta"]) : "";
$disponibleparaGastos_GastoaVenta = isset($_POST["disponibleparaGastos_GastoaVenta"]) ? limpiarCadena($_POST["disponibleparaGastos_GastoaVenta"]) : "";
$serie_no_GastoaVenta = isset($_POST["serie_no_GastoaVenta"]) ? limpiarCadena($_POST["serie_no_GastoaVenta"]) : "";
$factura_no_GastoaVenta = isset($_POST["factura_no_GastoaVenta"]) ? limpiarCadena($_POST["factura_no_GastoaVenta"]) : "";
$tipo_factura_GastoaVenta = isset($_POST["tipo_factura_GastoaVenta"]) ? limpiarCadena($_POST["tipo_factura_GastoaVenta"]) : "";
$tipo_comprobante_GastoaVenta = isset($_POST["tipo_comprobante_GastoaVenta"]) ? limpiarCadena($_POST["tipo_comprobante_GastoaVenta"]) : "";
$idcliente_GastoaVenta = isset($_POST["idcliente_GastoaVenta"]) ? limpiarCadena($_POST["idcliente_GastoaVenta"]) : "";
$nit_no_GastoaVenta = isset($_POST["nit_no_GastoaVenta"]) ? limpiarCadena($_POST["nit_no_GastoaVenta"]) : "";
$tipo_documento_cliente_GastoaVenta = isset($_POST["tipo_documento_cliente_GastoaVenta"]) ? limpiarCadena($_POST["tipo_documento_cliente_GastoaVenta"]) : "";
$nombreproveedor_GastoaVenta = isset($_POST["nombreproveedor_GastoaVenta"]) ? limpiarCadena($_POST["nombreproveedor_GastoaVenta"]) : "";
$direccion__GastoaVenta = isset($_POST["direccion__GastoaVenta"]) ? limpiarCadena($_POST["direccion__GastoaVenta"]) : "";
$concepto_fac_GastoaVenta = isset($_POST["concepto_fac_GastoaVenta"]) ? limpiarCadena($_POST["concepto_fac_GastoaVenta"]) : "";
$fecha_hora_GastoaVenta = isset($_POST["fecha_hora_GastoaVenta"]) ? limpiarCadena($_POST["fecha_hora_GastoaVenta"]) : "";
$valor_q_GastoaVenta = isset($_POST["valor_q_GastoaVenta"]) ? limpiarCadena($_POST["valor_q_GastoaVenta"]) : "";
$tipo_compra_GastoaVenta = isset($_POST["tipo_compra_GastoaVenta"]) ? limpiarCadena($_POST["tipo_compra_GastoaVenta"]) : "";
$tipo_combustible_GastoaVenta = isset($_POST["tipo_combustible_GastoaVenta"]) ? limpiarCadena($_POST["tipo_combustible_GastoaVenta"]) : "";
$num_galonaje_GastoaVenta = isset($_POST["num_galonaje_GastoaVenta"]) ? limpiarCadena($_POST["num_galonaje_GastoaVenta"]) : "";
$tipo_entrega = isset($_POST["tipo_entrega"]) ? limpiarCadena($_POST["tipo_entrega"]) : "";



$numero_pagos = isset($_POST["numero_pagos"]) ? limpiarCadena($_POST["numero_pagos"]) : "";
$fecha_hora_pago = isset($_POST["fecha_hora_pago"]) ? limpiarCadena($_POST["fecha_hora_pago"]) : "";
$fecha_hora_vencimiento_factura = isset($_POST["fecha_hora_vencimiento_factura"]) ? limpiarCadena($_POST["fecha_hora_vencimiento_factura"]) : "";
$monto_abono = isset($_POST["monto_abono"]) ? limpiarCadena($_POST["monto_abono"]) : "";
////

//TRANSPORTES Y MENSAJEROS
$idtransporte = isset($_POST["idtransporte"]) ? limpiarCadena($_POST["idtransporte"]) : "";
$idmensajero = isset($_POST["idmensajero"]) ? limpiarCadena($_POST["idmensajero"]) : "";

$idvendedor = isset($_POST["idvendedor"]) ? limpiarCadena($_POST["idvendedor"]) : "";

$descuento_general = isset($_POST["descuento_general"]) ? limpiarCadena($_POST["descuento_general"]) : "";
$valor_descuentoGeneral = isset($_POST["valor_descuentoGeneral"]) ? limpiarCadena($_POST["valor_descuentoGeneral"]) : "";

$total_venta_r = isset($_POST["total_venta_r"]) ? limpiarCadena($_POST["total_venta_r"]) : "";
$total_ventades_r = isset($_POST["total_ventades_r"]) ? limpiarCadena($_POST["total_ventades_r"]) : "";

$idtaller = isset($_POST["idtaller"]) ? limpiarCadena($_POST["idtaller"]) : "";

$destino = isset($_POST["destino"]) ? limpiarCadena($_POST["destino"]) : "";
$forma_productos = isset($_POST["forma_productos"]) ? limpiarCadena($_POST["forma_productos"]) : "";
$comentario_venta = isset($_POST["comentario_venta"]) ? limpiarCadena($_POST["comentario_venta"]) : "";


$tipo_venta_operacion = isset($_POST["tipo_venta_operacion"]) ? limpiarCadena($_POST["tipo_venta_operacion"]) : "";
$idcobradores = isset($_POST["idcobradores"]) ? limpiarCadena($_POST["idcobradores"]) : "";
$idtecnico = isset($_POST["idtecnico"]) ? limpiarCadena($_POST["idtecnico"]) : "";

$venta_lote = '0';


switch ($_GET["op"]) {
    case 'guardaryeditar':
        //PARA LOS CREDITOS
        $detalles_credito = isset($_POST['detalles_credito']) ? json_decode($_POST['detalles_credito'], true) : [];
        if (isset($_POST['datosArticulosv'])) {
            $datosArticulos = json_decode($_POST['datosArticulosv'], true);
        }
        if (empty($idventa)) {
            $rspta = $venta->insertar(
                $idcliente,
                $codigo_cliente,
                $nit,
                $nombre_cliente,
                $telefono_cliente,
                $direccion_cliente,
                $correo_cliente,
                $tipo_documento_cliente,
                $idusuario,
                $idcotizacion,
                $fecha_hora,
                $forma_pago,
                $tipo_comprobante,
                $total_venta,
                $total_ventades,
                $cefectivo,
                $ccredito,
                $ctarjeta,
                $ctransferencia,
                $rescambio,
                $valor_tarjeta,
                $tipo_pagoBacVisaNet,
                $opcionesAdicionales,
                $observacion_credito,
                $datosArticulos,
                $total_venta_r,
                $total_ventades_r,
                $tipo_entrega,
                $numero_pagos,
                $fecha_hora_pago,
                $fecha_hora_vencimiento_factura,
                $monto_abono,
                $idtransporte,
                $idmensajero,
                $idvendedor,
                $descuento_general,
                $valor_descuentoGeneral,
                $tipo_cliente,
                $detalles_credito,
                $idtaller,
                $destino,
                $forma_productos,
                $comentario_venta,
                $tipo_venta_operacion,
                $idcobradores,
                $idtecnico,
                $venta_lote
            );
            echo json_encode($rspta);
        } else {
        }
        break;

    case 'guardaryeditarSolicitudProductos':

        if (isset($_POST['datosArticulosC_SP'])) {
            $datosArticulosSp = json_decode($_POST['datosArticulosC_SP'], true);
        }
        $rspta = $venta->insertarSolicitudProductos($datosArticulosSp);
        echo $rspta ? "Solicitud registrado" : "No se pudieron registrar todos los datos";


        break;

    case 'MostrarPedidosHechos':
        $fecha_inicio_reporte = $_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte = $_REQUEST["fecha_fin_reporte"];
        $rspta = $venta->MostrarPedidosHechos($fecha_inicio_reporte, $fecha_fin_reporte);
        //Vamos a declarar un array 
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $url = '../reportes/exSolicitudProductos.php?id=';
            $data[] = array(
                "0" => '<a target="_blank" href="' . $url . $reg->idsolicitud_productos . '"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->fecha_creacion,
                "2" => $reg->idsolicitud_productos,
                "3" => $reg->usuario,
                "4" => $reg->estado
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

    case 'guardaryeditarCobroOrden':

        if (isset($_POST['datos1'])) {
            $datos = json_decode($_POST['datos1'], true);
            $idarticulo = $datos['idarticulo'];
            $stockinven = $datos['stockinven'];
            $cantidadpresentacion = $datos['cantidadpresentacion'];
            $cantidad = $datos['cantidad'];
            $totalcantidadpresentacion = $datos['totalcantidadpresentacion'];
            $presen = $datos['presen'];
            $precio_ventaSistema = $datos['precio_ventaSistema'];
            $precio_ventaSistema2 = $datos['precio_ventaSistema2'];
            $q_ref = $datos['q_ref'];
            $precio_venta = $datos['precio_venta'];
            $precio_recargoPV = $datos['precio_recargoPV'];
            $precio_recargoQRef = $datos['precio_recargoQRef'];
            $descuento_porcentaje = $datos['descuento_porcentaje'];
            $subtotal1 = $datos['subtotal1'];
            $subtotaldes1 = $datos['subtotaldes1'];
            $descripcion_detalle = $datos['descripcion_detalle'];
        } else {
            // Manejo del error o asignación de un valor por defecto
            $idarticulo = []; // o cualquier otro valor predeterminado
        }


        if (empty($idventa)) {
            $rspta = $venta->insertarCobro(
                $idcliente,
                $codigo_cliente,
                $nit,
                $nombre_cliente,
                $telefono_cliente,
                $direccion_cliente,
                $correo_cliente,
                $tipo_documento_cliente,
                $idusuario,
                $idcotizacion,
                $fecha_hora,
                $forma_pago,
                $tipo_comprobante,
                $total_venta,
                $total_ventades,
                $cefectivo,
                $ccredito,
                $ctarjeta,
                $ctransferencia,
                $rescambio,
                $valor_tarjeta,
                $tipo_pagoBacVisaNet,
                $opcionesAdicionales,
                $observacion_credito,
                $idarticulo,
                $stockinven,
                $cantidadpresentacion,
                $cantidad,
                $totalcantidadpresentacion,
                $presen,
                $precio_ventaSistema,
                $precio_ventaSistema2,
                $q_ref,
                $precio_venta,
                $precio_recargoPV,
                $precio_recargoQRef,
                $descuento_porcentaje,
                $subtotal1,
                $subtotaldes1,
                $id_add_orden,
                $propina,
                $descripcion_detalle
            );
            echo json_encode($rspta);
        } else {
        }
        break;


    case 'guardaryeditarnc':
        if (isset($_POST['datosArticulosNC'])) {
            $datosArticulos = json_decode($_POST['datosArticulosNC'], true);
        }
        $rspta = $venta->guardaryeditarnc(
            $idventa,
            $idcliente,
            $codigo_cliente,
            $nit,
            $nombre_cliente,
            $telefono_cliente,
            $direccion_cliente,
            $correo_cliente,
            $tipo_documento_cliente,
            $idusuario,
            $idcotizacion,
            $fecha_hora,
            $forma_pago,
            $tipo_comprobante,
            $total_venta,
            $total_ventades,
            $cefectivo,
            $ccredito,
            $ctarjeta,
            $ctransferencia,
            $rescambio,
            $valor_tarjeta,
            $tipo_pagoBacVisaNet,
            $opcionesAdicionales,
            $observacion_credito,
            $datosArticulos,
            $autorizacionEcoFactura_venta,
            $serie_comprobante_venta,
            $numero_ecoFactura_venta,
            $fecha_hora_nc,
            $motivo_nc
        );
        echo json_encode($rspta);
        break;

    case 'anular':
        $rspta = $venta->anular($idventa);
        echo $rspta ? "Venta anulada" : "Venta no se puede anular";
        break;

    case 'listarDasboardVentasMensajeroApp':
        $rspta = $venta->listarDasboardVentasMensajeroApp($idusuario);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;

    case 'cambiarestadodespacho':
        $rspta = $venta->cambiarestadodespacho($idventa);
        echo $rspta ? "Venta Actualizada" : "Venta no se puede anular";
        break;


    case 'cambiarestadodespachoVenta':
        $rspta = $venta->cambiarestadodespachoVenta($idventa);
        echo $rspta ? "Venta Liquidada" : "Venta no se puede Liquidar";
        break;

    case 'guardarGastoaVenta':
        $rspta = $venta->guardarGastoaVenta($TotalEfectivoDisponible_GastoaVenta, $totalAcumuladoGasots_GastoaVenta, $disponibleparaGastos_GastoaVenta, $serie_no_GastoaVenta, $factura_no_GastoaVenta, $tipo_factura_GastoaVenta, $tipo_comprobante_GastoaVenta, $idcliente_GastoaVenta, $nit_no_GastoaVenta, $tipo_documento_cliente_GastoaVenta, $nombreproveedor_GastoaVenta, $direccion__GastoaVenta, $concepto_fac_GastoaVenta, $fecha_hora_GastoaVenta, $valor_q_GastoaVenta, $tipo_compra_GastoaVenta, $tipo_combustible_GastoaVenta, $num_galonaje_GastoaVenta);
        echo $rspta ? "Gasto de Venta Registrado" : "Gasto de Venta no se puede Registrar";
        break;


    case 'selecttransporte':
        require_once "../modelos/Transporte.php";
        $trans = new Transporte();

        $rspta = $trans->select();

        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idtransporte . '>' . $reg->idtransporte . ' ' . $reg->nombre . '</option>';
        }
        break;


    case 'selecttransporte25':
        require_once "../modelos/Transporte.php";
        $trans = new Transporte();

        $rspta = $trans->select();

        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idtransporte . '>' . $reg->nombre . '</option>';
        }
        break;


    case 'listarNC':

        $fecha_inicio_reporte = $_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte = $_REQUEST["fecha_fin_reporte"];

        $rspta = $venta->listarNC($fecha_inicio_reporte, $fecha_fin_reporte);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {

            if ($reg->tipo_comprobante == 'Factura') {
                # code... 
                $url = '../reportes/exTicket_Fel_NC.php?id=';
                $url2 = '../reportes/exTicket_Fel58mm_NC.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta_Fel_NC.php?id=';
            } else {
                $url = '../reportes/exTicket_NC.php?id=';
                $url2 = '../reportes/exTicket58mmNC.php?id=';
                $url3 = '../reportes/exVentaFormatoCartaNC.php?id=';
            }


            # code...  
            $resventa = $reg->total_venta;
            $resventades = $reg->total_ventades;

            // Validamos si la fecha de certificación es '0000-00-00 00:00:00'
            $fecha_certificacionNc = ($reg->fechaCertificacion_ecoFactura == '0000-00-00 00:00:00') ? 'No certificado' : $reg->fechaCertificacion_ecoFactura;
            $fecha_certificacionVenta = ($reg->fechaCertificacion_ecoFactura_venta == '0000-00-00 00:00:00') ? 'No certificado' : $reg->fechaCertificacion_ecoFactura_venta;


            $data[] = array(
                "0" => ($reg->estado == 'Aceptado') ?
                    '<a target="_blank" href="' . $url . $reg->idnota_credito . '" title="Ticket 79mm"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url2 . $reg->idnota_credito . '"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url3 . $reg->idnota_credito . '"  title="Carta"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>' :
                    '<a target="_blank" href="' . $url . $reg->idnota_credito . '"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url2 . $reg->idnota_credito . '"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url3 . $reg->idnota_credito . '"  title="Carta"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->idnota_credito,
                "2" => $reg->cliente,
                "3" => $reg->usuarioNc,
                "4" => $reg->tipo_comprobante,
                "5" => $reg->num_comprobante,
                "6" => $resventa,
                "7" => $resventades,
                "8" => $reg->forma_pago,
                "9" => $reg->fecha_hora_nc,
                "10" => $fecha_certificacionNc,
                "11" => $reg->serie_ecoFactura,
                "12" => $reg->numero_ecoFactura,
                "13" => $reg->autorizacionEcoFactura,
                "14" => $reg->idventa,
                "15" => $reg->fechaVenta,
                "16" => $reg->usuarioVenta,
                "17" => $fecha_certificacionVenta,
                "18" => $reg->serie_comprobante_venta,
                "19" => $reg->numero_ecoFactura_venta,
                "20" => $reg->autorizacionEcoFactura_venta,
                "21" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
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



    case 'listar':

        $fecha_inicio_reporte = $_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte = $_REQUEST["fecha_fin_reporte"];

        $rspta = $venta->listar($fecha_inicio_reporte, $fecha_fin_reporte);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {


            if ($reg->tipo_comprobante == 'Factura') {
                # code... 
                $url = '../reportes/exTicket_Fel.php?id=';
                $url2 = '../reportes/exTicket_Fel58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta_Fel.php?id=';
                $url4 = '../reportes/exVentaBlancoFAC.php?id=';
            } elseif ($reg->tipo_comprobante == 'Cambiaria') {
                # code... 
                $url = '../reportes/exTicket_FelFCAM.php?id=';
                $url2 = '../reportes/exTicket_Fel_FCAM58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta_FelFCAM.php?id=';
                $url4 = '../reportes/exVentaBlancoCAM.php?id=';
            } else {
                $url = '../reportes/exTicket.php?id=';
                $url2 = '../reportes/exTicket58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta.php?id=';
                $url4 = '../reportes/exVentaBlancoENVIO.php?id=';
            }

            if ($reg->estado == 'Aceptado') {
                # code... 
                $resventa = $reg->total_venta;
                $resventades = $reg->total_ventades;
            } else {
                $resventa = 0;
                $resventades = 0;
            }

            if ($reg->forma_pago == 'Tarjeta') {
                # code... 
                $resDatostarjeta = $reg->tipo_pagoBacVisaNet . " / " . $reg->opcionesAdicionales . " / " . $reg->valor_tarjeta;
            } else {
                $resDatostarjeta = " ";
            }

            // Validamos si la fecha de certificación es '0000-00-00 00:00:00'
            $fecha_certificacion = ($reg->fechaCertificacion_ecoFactura == '0000-00-00 00:00:00') ? 'No certificado' : $reg->fechaCertificacion_ecoFactura;


            $urlContrato = '../reportes/exContrato.php?id=';
            $urlOrdenSalida = '../reportes/exOrdenSalida.php?id=';
            $data[] = array(
                "0" => ($reg->estado == 'Aceptado') ? ' <button class="btn btn-danger" title="Anular Venta" onclick="anular(' . $reg->idventa . ')"><i class="fa fa-close"></i></button>' .
                    '<a target="_blank" href="' . $url . $reg->idventa . '" title="Ticket 79mm"><button class="btn btn-success" title="Imprimir Ticket 79mm"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url2 . $reg->idventa . '"  title="Ticket 58mm"><button class="btn btn-info" title="Imprimir Ticket 58mm"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url4 . $reg->idventa . '"  title="Carta en Blanco"><button class="btn btn-info" title="Imprimir Carta en Blanco"><i class="fa fa-print"></i> </button> </a>' .

                    '<a target="_blank" href="' . $url3 . $reg->idventa . '"  title="Carta Colores"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>' :
                    '<a target="_blank" href="' . $url . $reg->idventa . '"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url2 . $reg->idventa . '"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url3 . $reg->idventa . '"  title="Carta"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->idventa,
                "2" => $reg->cliente,
                "3" => $reg->usuario,
                "4" => $reg->tipo_comprobante,
                "5" => $reg->num_comprobante,
                "6" => $resventa,
                "7" => $resventades,
                "8" => $reg->forma_pago . " - " . $resDatostarjeta,
                "9" => $reg->cefectivo,
                "10" => $reg->ctarjeta,
                "11" => $reg->ccredito,
                "12" => $reg->ctransferencia,
                "13" => $reg->rescambio,
                "14" => $reg->fecha,
                "15" => $fecha_certificacion,
                "16" => $reg->serie_ecoFactura,
                "17" => $reg->numero_ecoFactura,
                "18" => $reg->tipo_entrega,
                "19" => $reg->nom_venedor,
                "20" => $reg->idcotizacion,
                "21" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
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

    case 'listarRestaurante':

        $fecha_inicio_reporte = $_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte = $_REQUEST["fecha_fin_reporte"];

        $rspta = $venta->listarRestaurante($fecha_inicio_reporte, $fecha_fin_reporte);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {


            if ($reg->tipo_comprobante == 'Factura') {
                # code... 
                $url = '../reportes/exTicket_Fel.php?id=';
                $url2 = '../reportes/exTicket_Fel58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta_Fel.php?id=';
                $url4 = '../reportes/exVentaBlancoFAC.php?id=';
            } elseif ($reg->tipo_comprobante == 'Cambiaria') {
                # code... 
                $url = '../reportes/exTicket_FelFCAM.php?id=';
                $url2 = '../reportes/exTicket_Fel_FCAM58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta_FelFCAM.php?id=';
                $url4 = '../reportes/exVentaBlancoCAM.php?id=';
            } else {
                $url = '../reportes/exTicket.php?id=';
                $url2 = '../reportes/exTicket58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta.php?id=';
                $url4 = '../reportes/exVentaBlancoENVIO.php?id=';
            }

            if ($reg->estado == 'Aceptado') {
                # code... 
                $resventa = $reg->total_venta;
                $resventades = $reg->total_ventades;
            } else {
                $resventa = 0;
                $resventades = 0;
            }

            if ($reg->forma_pago == 'Tarjeta') {
                # code... 
                $resDatostarjeta = $reg->tipo_pagoBacVisaNet . " / " . $reg->opcionesAdicionales . " / " . $reg->valor_tarjeta;
            } else {
                $resDatostarjeta = " ";
            }

            // Validamos si la fecha de certificación es '0000-00-00 00:00:00'
            $fecha_certificacion = ($reg->fechaCertificacion_ecoFactura == '0000-00-00 00:00:00') ? 'No certificado' : $reg->fechaCertificacion_ecoFactura;


            $data[] = array(
                "0" => ($reg->estado == 'Aceptado') ? ' <button class="btn btn-danger" onclick="anular(' . $reg->idventa . ')"><i class="fa fa-close"></i></button>' .
                    '<a target="_blank" href="' . $url . $reg->idventa . '" title="Ticket 79mm"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url2 . $reg->idventa . '"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url4 . $reg->idventa . '"  title="Carta en Blanco"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url3 . $reg->idventa . '"  title="Carta Colores"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>' :
                    '<a target="_blank" href="' . $url . $reg->idventa . '"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url2 . $reg->idventa . '"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url3 . $reg->idventa . '"  title="Carta"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->nombre_mesa . " / " . $reg->id_add_orden,
                "2" => $reg->idventa,
                "3" => $reg->cliente,
                "4" => $reg->usuario,
                "5" => $reg->usuario_mesero,
                "6" => $reg->tipo_comprobante,
                "7" => $reg->num_comprobante,
                "8" => $resventa,
                "9" => $resventades,
                "10" => $reg->forma_pago . " - " . $resDatostarjeta,
                "11" => $reg->cefectivo,
                "12" => $reg->ctarjeta,
                "13" => $reg->ccredito,
                "14" => $reg->ctransferencia,
                "15" => $reg->rescambio,
                "16" => $reg->fecha,
                "17" => $fecha_certificacion,
                "18" => $reg->serie_ecoFactura,
                "19" => $reg->numero_ecoFactura,
                "20" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
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

    case 'listar_despacho':

        $fecha_inicio_reporte = $_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte = $_REQUEST["fecha_fin_reporte"];
        $tipo_entrega = $_REQUEST["tipo_entrega"];

        $rspta = $venta->listar_despacho($fecha_inicio_reporte, $fecha_fin_reporte, $tipo_entrega);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $opciones = "";
            if ($reg->despachosino == "SI") {
                $opciones = "Venta Despachada";
            } else {
                $opciones = '<button class="btn btn-info" onclick="cambiarestado(' . $reg->idventa . ')"><i class="fa fa-check">Pendiente Despacho</i></button>';
            }
            if ($reg->tipo_comprobante == 'Factura') {
                # code... 
                $url = '../reportes/exTicket_Fel.php?id=';
                $url2 = '../reportes/exTicket_Fel58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta_Fel.php?id=';
            } elseif ($reg->tipo_comprobante == 'Cambiaria') {
                # code... 
                $url = '../reportes/exTicket_FelFCAM.php?id=';
                $url2 = '../reportes/exTicket_Fel_FCAM58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta_FelFCAM.php?id=';
            } else {
                $url = '../reportes/exTicket.php?id=';
                $url2 = '../reportes/exTicket58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta.php?id=';
            }

            if ($reg->estado == 'Aceptado') {
                # code... 
                $resventa = $reg->total_venta;
                $resventades = $reg->total_ventades;
            } else {
                $resventa = 0;
                $resventades = 0;
            }

            if ($reg->forma_pago == 'Tarjeta') {
                # code... 
                $resDatostarjeta = $reg->tipo_pagoBacVisaNet . " / " . $reg->opcionesAdicionales . " / " . $reg->valor_tarjeta;
            } else {
                $resDatostarjeta = " ";
            }
            $fecha_certificacion = ($reg->fechaCertificacion_ecoFactura == '0000-00-00 00:00:00') ? 'No certificado' : $reg->fechaCertificacion_ecoFactura;

            if ($reg->estado_venta == "COMPLETO") {
                $estadoVenta = "Venta Liquidada";
            } else {
                $estadoVenta = '<button class="btn btn-info" onclick="cambiarestadoVenta(' . $reg->idventa . ',\'' . $reg->despachosino . '\')"><i class="fa fa-check"> ENPROCESO</i></button>';
            }

            $data[] = array(
                "0" => ($reg->estado == 'Aceptado') ?
                    '<a target="_blank" href="' . $url . $reg->idventa . '" title="Ticket 79mm"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url2 . $reg->idventa . '"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url3 . $reg->idventa . '"  title="Carta"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>' :
                    '<a target="_blank" href="' . $url . $reg->idventa . '"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url2 . $reg->idventa . '"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url3 . $reg->idventa . '"  title="Carta"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>',
                "1" => $opciones,
                "2" => $reg->despachosino,
                "3" => $reg->estadoventamensajero,
                "4" => $reg->idventa,
                "5" => $reg->tipo_entrega . " " . $reg->comentario_mensajero . " " . $reg->guia_transporte . " " . $reg->comentarioVentaEntregaMensajero,
                "6" => $reg->cliente,
                "7" => $reg->usuario,
                "8" => $reg->tipo_comprobante,
                "9" => $reg->num_comprobante,
                "10" => $resventa,
                "11" => $resventades,
                "12" => $reg->forma_pago . " - " . $resDatostarjeta,
                "13" => $reg->cefectivo,
                "14" => $reg->ctarjeta,
                "15" => $reg->ccredito,
                "16" => $reg->ctransferencia,
                "17" => $reg->rescambio,
                "18" => $reg->fecha,
                "19" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>',
                "20" => $estadoVenta
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

    case 'selectCliente':
        require_once "../modelos/Persona.php";
        $persona = new Persona();

        $rspta = $persona->listarclientes();

        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '--' . $reg->direccion . '--' . $reg->num_documento . '</option>';
        }
        break;

    case 'selectClienteppp':
        require_once "../modelos/Persona.php";
        $persona = new Persona();

        $rspta = $persona->listarp2();

        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '--' . $reg->direccion . '</option>';
        }
        break;






    case 'buscararticulocodebarCompras':
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $codigo = $_REQUEST["codigo"];
        $rspta = $articulo->ObtenerProductoBarCode2($codigo);
        while ($reg = $rspta->fetch_object()) {
            echo $reg->idarticulo . '@' . $reg->nombre . '@' . $reg->precio_venta . '@' . $reg->precio_compra . '@' . $reg->stock . '@' .
                $reg->precio_ventaNocturno . '@' .
                $reg->precio_rango1_Mecanico . '@' . $reg->precio_rango1_Distribuidor . '@' . $reg->precio_rango1_Mayorista . '@' .
                $reg->precio_rango2_MecanicoDos . '@' . $reg->precio_rango2_DistribuidorDos . '@' . $reg->precio_rango2_MayoristaDos . '@' .
                $reg->precio_rango3_MecanicoTres . '@' . $reg->precio_rango3_DistribuidorTres . '@' . $reg->precio_rango3_MayoristaTres . '@' .
                $reg->nombre_01 . '@' . $reg->stock_unidad . '@' . $reg->precio_unidad . '@' .
                $reg->nombre_02 . '@' . $reg->stock_blister . '@' . $reg->precio_blister . '@' .
                $reg->nombre_03 . '@' . $reg->stock_caja . '@' . $reg->precio_caja . '@' .
                $reg->nombre_04 . '@' . $reg->stock_fardo . '@' . $reg->precio_fardo . '@' .
                $reg->nombre_05 . '@' . $reg->stock_sacos . '@' . $reg->precio_sacos . '@' .
                $reg->nombre_06 . '@' . $reg->stock_paquete . '@' . $reg->precio_paquete . '@' .
                $reg->nombre_07 . '@' . $reg->stock_07 . '@' . $reg->precio_07 . '@' .
                $reg->nombre_08 . '@' . $reg->stock_08 . '@' . $reg->precio_08 . '@' .
                $reg->nombre_09 . '@' . $reg->stock_09 . '@' . $reg->precio_09 . '@' .
                $reg->nombre_10 . '@' . $reg->stock_10 . '@' . $reg->precio_10 . '@' .
                $reg->nombre_11 . '@' . $reg->stock_11 . '@' . $reg->precio_11 . '@' .
                $reg->nombre_12 . '@' . $reg->stock_12 . '@' . $reg->precio_12 . '@' .
                $reg->nombre_13 . '@' . $reg->stock_13 . '@' . $reg->precio_13 . '@' .
                $reg->nombre_14 . '@' . $reg->stock_14 . '@' . $reg->precio_14 . '@' .
                $reg->nombre_15 . '@' . $reg->stock_15 . '@' . $reg->precio_15 . '@' .
                $reg->nombre_16 . '@' . $reg->stock_16 . '@' . $reg->precio_16 . '@' .
                $reg->nombre_17 . '@' . $reg->stock_17 . '@' . $reg->precio_17 . '@' .
                $reg->nombre_18 . '@' . $reg->stock_18 . '@' . $reg->precio_18 . '@' .
                $reg->nombre_19 . '@' . $reg->stock_19 . '@' . $reg->precio_19 . '@' .
                $reg->nombre_20 . '@' . $reg->stock_20 . '@' . $reg->precio_20;
        }
        break;



    case 'buscararticulocodebarComprasxSucursal':
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $codigo = $_REQUEST["codigo"];
        $idsucursal = $_REQUEST["idsucursal"];
        $rspta = $articulo->ObtenerProductoBarCode_Sucursal($codigo, $idsucursal);
        while ($reg = $rspta->fetch_object()) {
            echo $reg->idarticulo . '@' . $reg->nombre . '@' . $reg->precio_venta . '@' . $reg->precio_compra . '@' . $reg->stock . '@' .
                $reg->precio_ventaNocturno . '@' .
                $reg->precio_rango1_Mecanico . '@' . $reg->precio_rango1_Distribuidor . '@' . $reg->precio_rango1_Mayorista . '@' .
                $reg->precio_rango2_MecanicoDos . '@' . $reg->precio_rango2_DistribuidorDos . '@' . $reg->precio_rango2_MayoristaDos . '@' .
                $reg->precio_rango3_MecanicoTres . '@' . $reg->precio_rango3_DistribuidorTres . '@' . $reg->precio_rango3_MayoristaTres . '@' .
                $reg->nombre_01 . '@' . $reg->stock_unidad . '@' . $reg->precio_unidad . '@' .
                $reg->nombre_02 . '@' . $reg->stock_blister . '@' . $reg->precio_blister . '@' .
                $reg->nombre_03 . '@' . $reg->stock_caja . '@' . $reg->precio_caja . '@' .
                $reg->nombre_04 . '@' . $reg->stock_fardo . '@' . $reg->precio_fardo . '@' .
                $reg->nombre_05 . '@' . $reg->stock_sacos . '@' . $reg->precio_sacos . '@' .
                $reg->nombre_06 . '@' . $reg->stock_paquete . '@' . $reg->precio_paquete . '@' .
                $reg->nombre_07 . '@' . $reg->stock_07 . '@' . $reg->precio_07 . '@' .
                $reg->nombre_08 . '@' . $reg->stock_08 . '@' . $reg->precio_08 . '@' .
                $reg->nombre_09 . '@' . $reg->stock_09 . '@' . $reg->precio_09 . '@' .
                $reg->nombre_10 . '@' . $reg->stock_10 . '@' . $reg->precio_10 . '@' .
                $reg->nombre_11 . '@' . $reg->stock_11 . '@' . $reg->precio_11 . '@' .
                $reg->nombre_12 . '@' . $reg->stock_12 . '@' . $reg->precio_12 . '@' .
                $reg->nombre_13 . '@' . $reg->stock_13 . '@' . $reg->precio_13 . '@' .
                $reg->nombre_14 . '@' . $reg->stock_14 . '@' . $reg->precio_14 . '@' .
                $reg->nombre_15 . '@' . $reg->stock_15 . '@' . $reg->precio_15 . '@' .
                $reg->nombre_16 . '@' . $reg->stock_16 . '@' . $reg->precio_16 . '@' .
                $reg->nombre_17 . '@' . $reg->stock_17 . '@' . $reg->precio_17 . '@' .
                $reg->nombre_18 . '@' . $reg->stock_18 . '@' . $reg->precio_18 . '@' .
                $reg->nombre_19 . '@' . $reg->stock_19 . '@' . $reg->precio_19 . '@' .
                $reg->nombre_20 . '@' . $reg->stock_20 . '@' . $reg->precio_20 . '@' . $reg->idsucursal . '@' . $reg->nom_sucursal;
        }
        break;


    /*function agregarDetalle(idarticulo, articulo, precio_venta, precio_compra, stock, precio_ventaNocturno,
    precio_rango1, precio_rango2, precio_rango3, precio_unidad, precio_blister, precio_caja, precio_fardo, precio_sacos, precio_paquete,
    stock_unidad, stock_blister, stock_caja, stock_fardo, stock_sacos, stock_paquete)*/







    case 'buscararticulocodebar':
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $codigo = $_REQUEST["codigo"];
        $rspta = $articulo->ObtenerProductoBarCode2($codigo);
        while ($reg = $rspta->fetch_object()) {
            echo $reg->idarticulo . '@' . $reg->nombre . '@' . $reg->precio_venta . '@' .
                $reg->stock . '@' . $reg->descuento_porcentaje . '@' .
                $reg->precio_rango1 . '@' . $reg->precio_rango1_Dos . '@' .
                $reg->precio_rango2 . '@' . $reg->precio_rango2_Dos . '@' .
                $reg->precio_rango3 . '@' . $reg->precio_rango3_Dos . '@' .
                $reg->precio_rango1_Mecanico . '@' . $reg->precio_rango1_Distribuidor . '@' . $reg->precio_rango1_Mayorista . '@' .
                $reg->precio_rango2_MecanicoDos . '@' . $reg->precio_rango2_DistribuidorDos . '@' . $reg->precio_rango2_MayoristaDos . '@' .
                $reg->precio_rango3_MecanicoTres . '@' . $reg->precio_rango3_DistribuidorTres . '@' . $reg->precio_rango3_MayoristaTres . '@' .
                $reg->nombre_01 . '@' . $reg->stock_unidad . '@' . $reg->precio_unidad . '@' .
                $reg->nombre_02 . '@' . $reg->stock_blister . '@' . $reg->precio_blister . '@' .
                $reg->nombre_03 . '@' . $reg->stock_caja . '@' . $reg->precio_caja . '@' .
                $reg->nombre_04 . '@' . $reg->stock_fardo . '@' . $reg->precio_fardo . '@' .
                $reg->nombre_05 . '@' . $reg->stock_sacos . '@' . $reg->precio_sacos . '@' .
                $reg->nombre_06 . '@' . $reg->stock_paquete . '@' . $reg->precio_paquete . '@' .
                $reg->nombre_07 . '@' . $reg->stock_07 . '@' . $reg->precio_07 . '@' .
                $reg->nombre_08 . '@' . $reg->stock_08 . '@' . $reg->precio_08 . '@' .
                $reg->nombre_09 . '@' . $reg->stock_09 . '@' . $reg->precio_09 . '@' .
                $reg->nombre_10 . '@' . $reg->stock_10 . '@' . $reg->precio_10 . '@' .
                $reg->nombre_11 . '@' . $reg->stock_11 . '@' . $reg->precio_11 . '@' .
                $reg->nombre_12 . '@' . $reg->stock_12 . '@' . $reg->precio_12 . '@' .
                $reg->nombre_13 . '@' . $reg->stock_13 . '@' . $reg->precio_13 . '@' .
                $reg->nombre_14 . '@' . $reg->stock_14 . '@' . $reg->precio_14 . '@' .
                $reg->nombre_15 . '@' . $reg->stock_15 . '@' . $reg->precio_15 . '@' .
                $reg->nombre_16 . '@' . $reg->stock_16 . '@' . $reg->precio_16 . '@' .
                $reg->nombre_17 . '@' . $reg->stock_17 . '@' . $reg->precio_17 . '@' .
                $reg->nombre_18 . '@' . $reg->stock_18 . '@' . $reg->precio_18 . '@' .
                $reg->nombre_19 . '@' . $reg->stock_19 . '@' . $reg->precio_19 . '@' .
                $reg->nombre_20 . '@' . $reg->stock_20 . '@' . $reg->precio_20 . '@' . $reg->precio_activado . '@' . $reg->facturar_cero . '@' . $reg->precio_compra . '@';
        }
        break;


    case 'buscararticulocodebar_descuento':
        $idcliente = $_REQUEST["idcliente"];
        $descuento_cliente = $_REQUEST["descuento_cliente"];
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $codigo = $_REQUEST["codigo"];
        $rspta = $articulo->ObtenerProductoBarCode2_descuento($codigo, $idcliente, $descuento_cliente);
        while ($reg = $rspta->fetch_object()) {
            echo $reg->idarticulo . '@' . $reg->nombre . '@' . $reg->precio_venta . '@' .
                $reg->stock . '@' . $reg->descuento_porcentaje . '@' .
                $reg->precio_rango1 . '@' . $reg->precio_rango1_Dos . '@' .
                $reg->precio_rango2 . '@' . $reg->precio_rango2_Dos . '@' .
                $reg->precio_rango3 . '@' . $reg->precio_rango3_Dos . '@' .
                $reg->precio_rango1_Mecanico . '@' . $reg->precio_rango1_Distribuidor . '@' . $reg->precio_rango1_Mayorista . '@' .
                $reg->precio_rango2_MecanicoDos . '@' . $reg->precio_rango2_DistribuidorDos . '@' . $reg->precio_rango2_MayoristaDos . '@' .
                $reg->precio_rango3_MecanicoTres . '@' . $reg->precio_rango3_DistribuidorTres . '@' . $reg->precio_rango3_MayoristaTres . '@' .
                $reg->nombre_01 . '@' . $reg->stock_unidad . '@' . $reg->precio_unidad . '@' .
                $reg->nombre_02 . '@' . $reg->stock_blister . '@' . $reg->precio_blister . '@' .
                $reg->nombre_03 . '@' . $reg->stock_caja . '@' . $reg->precio_caja . '@' .
                $reg->nombre_04 . '@' . $reg->stock_fardo . '@' . $reg->precio_fardo . '@' .
                $reg->nombre_05 . '@' . $reg->stock_sacos . '@' . $reg->precio_sacos . '@' .
                $reg->nombre_06 . '@' . $reg->stock_paquete . '@' . $reg->precio_paquete . '@' .
                $reg->nombre_07 . '@' . $reg->stock_07 . '@' . $reg->precio_07 . '@' .
                $reg->nombre_08 . '@' . $reg->stock_08 . '@' . $reg->precio_08 . '@' .
                $reg->nombre_09 . '@' . $reg->stock_09 . '@' . $reg->precio_09 . '@' .
                $reg->nombre_10 . '@' . $reg->stock_10 . '@' . $reg->precio_10 . '@' .
                $reg->nombre_11 . '@' . $reg->stock_11 . '@' . $reg->precio_11 . '@' .
                $reg->nombre_12 . '@' . $reg->stock_12 . '@' . $reg->precio_12 . '@' .
                $reg->nombre_13 . '@' . $reg->stock_13 . '@' . $reg->precio_13 . '@' .
                $reg->nombre_14 . '@' . $reg->stock_14 . '@' . $reg->precio_14 . '@' .
                $reg->nombre_15 . '@' . $reg->stock_15 . '@' . $reg->precio_15 . '@' .
                $reg->nombre_16 . '@' . $reg->stock_16 . '@' . $reg->precio_16 . '@' .
                $reg->nombre_17 . '@' . $reg->stock_17 . '@' . $reg->precio_17 . '@' .
                $reg->nombre_18 . '@' . $reg->stock_18 . '@' . $reg->precio_18 . '@' .
                $reg->nombre_19 . '@' . $reg->stock_19 . '@' . $reg->precio_19 . '@' .
                $reg->nombre_20 . '@' . $reg->stock_20 . '@' . $reg->precio_20 . '@' . $reg->precio_activado . '@' . $reg->facturar_cero . '@' . $reg->precio_compra . '@';
        }
        break;

    case 'listarArticulosVenta2':
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $rspta = $articulo->listarActivosVenta();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "value" => $reg->nombre,
                "label" => $reg->nombre,
                "desc" => $reg->nombre,
                "icon" => $reg->imagen,
                "idarticulo" => $reg->idarticulo,
                "nombre" => $reg->nombre,
                "precio_venta" => $reg->precio_venta,
                "stock" => $reg->stock,
                "descuento_porcentaje" => $reg->descuento_porcentaje,
                "stock_unidad" => $reg->stock_unidad,
                "precio_unidad" => $reg->precio_unidad,
                "stock_blister" => $reg->stock_blister,
                "precio_blister" => $reg->precio_blister,
                "stock_caja" => $reg->stock_caja,
                "precio_caja" => $reg->precio_caja,
                "stock_fardo" => $reg->stock_fardo,
                "precio_fardo" => $reg->precio_fardo,
                "stock_sacos" => $reg->stock_sacos,
                "precio_sacos" => $reg->precio_sacos,
                "stock_paquete" => $reg->stock_paquete,
                "precio_paquete" => $reg->precio_paquete,
                "precio_rango1" => $reg->precio_rango1,
                "precio_rango2" => $reg->precio_rango2,
                "precio_rango3" => $reg->precio_rango3,
                "precio_compra" => $reg->precio_compra,
                "precio_activado" => $reg->precio_activado,
                "precio_rango1_Dos" => $reg->precio_rango1_Dos,
                "precio_rango2_Dos" => $reg->precio_rango2_Dos,
                "precio_rango3_Dos" => $reg->precio_rango3_Dos,
                "precio_rango1_Mecanico" => $reg->precio_rango1_Mecanico,
                "precio_rango2_MecanicoDos" => $reg->precio_rango2_MecanicoDos,
                "precio_rango3_MecanicoTres" => $reg->precio_rango3_MecanicoTres,
                "precio_rango1_Distribuidor" => $reg->precio_rango1_Distribuidor,
                "precio_rango2_DistribuidorDos" => $reg->precio_rango2_DistribuidorDos,
                "precio_rango3_DistribuidorTres" => $reg->precio_rango3_DistribuidorTres,
                "precio_rango1_Mayorista" => $reg->precio_rango1_Mayorista,
                "precio_rango2_MayoristaDos" => $reg->precio_rango2_MayoristaDos,
                "precio_rango3_MayoristaTres" => $reg->precio_rango3_MayoristaTres,
                "tipo_producto" => $reg->tipo_producto,
                "cantidad" => 1
            );
        }
        $results = $data;
        echo json_encode($results);
        break;


    case 'listarArticulosVentaCantidad':
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $rspta = $articulo->listarActivosVenta();
        //Vamos a declarar un array
        $data = array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<div class="input-group" style="display: inline-flex; margin-right: 5px;">
                        <input type="number" step="any" class="form-control input-sm" id="cantidad_' . $reg->idarticulo . '" 
                        style="width: 70px;" min="1" value="1">
                        <button class="btn btn-warning" onclick="agregarDetalleCantidad(' . $reg->idarticulo . ',\'' . str_replace('"', 't.t', $reg->nombre) . '\',
                                                                            \'' . $reg->precio_venta . '\', \'' . $reg->stock . '\',
                                                                            \'' . $reg->descuento_porcentaje . '\',
                                                                            \'' . $reg->precio_rango1 . '\',\'' . $reg->precio_rango1_Dos . '\',
                                                                            \'' . $reg->precio_rango2 . '\',\'' . $reg->precio_rango2_Dos . '\',
                                                                            \'' . $reg->precio_rango3 . '\',\'' . $reg->precio_rango3_Dos . '\',
                                                                            \'' . $reg->precio_rango1_Mecanico . '\',\'' . $reg->precio_rango1_Distribuidor . '\',\'' . $reg->precio_rango1_Mayorista . '\',
                                                                            \'' . $reg->precio_rango2_MecanicoDos . '\',\'' . $reg->precio_rango2_DistribuidorDos . '\',\'' . $reg->precio_rango2_MayoristaDos . '\',
                                                                            \'' . $reg->precio_rango3_MecanicoTres . '\',\'' . $reg->precio_rango3_DistribuidorTres . '\',\'' . $reg->precio_rango3_MayoristaTres . '\',
                                                                            \'' . $reg->nombre_01 . '\',\'' . $reg->stock_unidad . '\',\'' . $reg->precio_unidad . '\',
                                                                            \'' . $reg->nombre_02 . '\',\'' . $reg->stock_blister . '\',\'' . $reg->precio_blister . '\',
                                                                            \'' . $reg->nombre_03 . '\',\'' . $reg->stock_caja . '\',\'' . $reg->precio_caja . '\',
                                                                            \'' . $reg->nombre_04 . '\',\'' . $reg->stock_fardo . '\',\'' . $reg->precio_fardo . '\',
                                                                            \'' . $reg->nombre_05 . '\',\'' . $reg->stock_sacos . '\',\'' . $reg->precio_sacos . '\',
                                                                            \'' . $reg->nombre_06 . '\',\'' . $reg->stock_paquete . '\',\'' . $reg->precio_paquete . '\',
                                                                            \'' . $reg->nombre_07 . '\',\'' . $reg->stock_07 . '\',\'' . $reg->precio_07 . '\',
                                                                            \'' . $reg->nombre_08 . '\',\'' . $reg->stock_08 . '\',\'' . $reg->precio_08 . '\',
                                                                            \'' . $reg->nombre_09 . '\',\'' . $reg->stock_09 . '\',\'' . $reg->precio_09 . '\',
                                                                            \'' . $reg->nombre_10 . '\',\'' . $reg->stock_10 . '\',\'' . $reg->precio_10 . '\',
                                                                            \'' . $reg->nombre_11 . '\',\'' . $reg->stock_11 . '\',\'' . $reg->precio_11 . '\',
                                                                            \'' . $reg->nombre_12 . '\',\'' . $reg->stock_12 . '\',\'' . $reg->precio_12 . '\',
                                                                            \'' . $reg->nombre_13 . '\',\'' . $reg->stock_13 . '\',\'' . $reg->precio_13 . '\',
                                                                            \'' . $reg->nombre_14 . '\',\'' . $reg->stock_14 . '\',\'' . $reg->precio_14 . '\',
                                                                            \'' . $reg->nombre_15 . '\',\'' . $reg->stock_15 . '\',\'' . $reg->precio_15 . '\',
                                                                            \'' . $reg->nombre_16 . '\',\'' . $reg->stock_16 . '\',\'' . $reg->precio_16 . '\',
                                                                            \'' . $reg->nombre_17 . '\',\'' . $reg->stock_17 . '\',\'' . $reg->precio_17 . '\',
                                                                            \'' . $reg->nombre_18 . '\',\'' . $reg->stock_18 . '\',\'' . $reg->precio_18 . '\',
                                                                            \'' . $reg->nombre_19 . '\',\'' . $reg->stock_19 . '\',\'' . $reg->precio_19 . '\',
                                                                            \'' . $reg->nombre_20 . '\',\'' . $reg->stock_20 . '\',\'' . $reg->precio_20 . '\',
                                                                            \'' . $reg->precio_activado . '\',
                                                                            \'' . $reg->facturar_cero . '\',\'' . $reg->precio_compra . '\',
                                                                            document.getElementById(\'cantidad_' . $reg->idarticulo . '\').value)">
                                <span class="fa fa-plus"></span></button>
                            </button>
                    </div>',
                "1" => $reg->nombre,
                "2" => (
                    $reg->dias_vencimiento === null || $reg->dias_vencimiento === '' ?
                    '<span class="label" style="background-color: #00c0ef; font-size: 16px;">Sin días</span>' : ($reg->dias_vencimiento <= -90 ?
                        '<span class="label bg-green" style="font-size: 16px;">' . $reg->dias_vencimiento . '</span>' : ($reg->dias_vencimiento <= -60 ?
                            '<span class="label bg-yellow" style="font-size: 16px;">' . $reg->dias_vencimiento . '</span>' : ($reg->dias_vencimiento <= -30 ?
                                '<span class="label bg-red" style="font-size: 16px;">' . $reg->dias_vencimiento . '</span>' :
                                '<span class="label bg-gray" style="font-size: 16px;">' . $reg->dias_vencimiento . '</span>')))
                ),
                "3" => $reg->categoria,
                "4" => $reg->codigo,
                "5" => $reg->codigo_sku,
                "6" => $reg->descripcion_2,
                "7" => $reg->stock,
                "8" => ($reg->stockminimo <= $reg->stock) ? '<span class="label bg-green">Stock Normal</span>' :
                    '<span class="label bg-red">Stock Bajo</span>',
                "9" => $reg->precio_venta,
                "10" => ($reg->imagen != "" && file_exists("../files/articulos/" . $reg->imagen)) ?
                    "<img src='../files/articulos/" . $reg->imagen . "' height='50px' width='50px'>" :
                    "<img src='../files/articulos/nofoto.jpg' height='50px' width='50px'>",
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

    case 'listarArticulosVentaCantidad_v2':
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $rspta = $articulo->listarActivosVenta();
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $json_data = base64_encode(json_encode($reg));

            $data[] = array(
                "0" => '<button type="button" class="btn btn-warning btn-sm btn-seleccionar" onclick="abrirModalPresentacion(\'' . $json_data . '\')"><i class="fa fa-plus"></i> Seleccionar</button>',
                "1" => $reg->nombre,
                "2" => $reg->categoria,
                "3" => $reg->descripcion_2,
                "4" => $reg->stock,
                "5" => $reg->precio_venta
            );
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    case 'listarArticulosVentaCantidad_descuento':
        $idcliente = $_REQUEST["idcliente"];
        $descuento_cliente = $_REQUEST["descuento_cliente"];
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $rspta = $articulo->listarActivosVenta_descuento($idcliente);
        //Vamos a declarar un array
        $data = array();
        while ($reg = $rspta->fetch_object()) {


            $data[] = array(
                "0" => '<div class="input-group" style="display: inline-flex; margin-right: 5px;">
                        <input type="number" step="any" class="form-control input-sm" id="cantidad_' . $reg->idarticulo . '" 
                        style="width: 70px;" min="1" value="1">
                        <button class="btn btn-warning" onclick="agregarDetalleCantidad(' . $reg->idarticulo . ',\'' . str_replace('"', 't.t', $reg->nombre) . '\',
                                                                            \'' . $reg->precio_venta . '\', \'' . $reg->stock . '\',
                                                                            \'' . $reg->descuento_porcentaje . '\',
                                                                            \'' . $reg->precio_rango1 . '\',\'' . $reg->precio_rango1_Dos . '\',
                                                                            \'' . $reg->precio_rango2 . '\',\'' . $reg->precio_rango2_Dos . '\',
                                                                            \'' . $reg->precio_rango3 . '\',\'' . $reg->precio_rango3_Dos . '\',
                                                                            \'' . $reg->precio_rango1_Mecanico . '\',\'' . $reg->precio_rango1_Distribuidor . '\',\'' . $reg->precio_rango1_Mayorista . '\',
                                                                            \'' . $reg->precio_rango2_MecanicoDos . '\',\'' . $reg->precio_rango2_DistribuidorDos . '\',\'' . $reg->precio_rango2_MayoristaDos . '\',
                                                                            \'' . $reg->precio_rango3_MecanicoTres . '\',\'' . $reg->precio_rango3_DistribuidorTres . '\',\'' . $reg->precio_rango3_MayoristaTres . '\',
                                                                            \'' . $reg->nombre_01 . '\',\'' . $reg->stock_unidad . '\',\'' . $reg->precio_unidad . '\',
                                                                            \'' . $reg->nombre_02 . '\',\'' . $reg->stock_blister . '\',\'' . $reg->precio_blister . '\',
                                                                            \'' . $reg->nombre_03 . '\',\'' . $reg->stock_caja . '\',\'' . $reg->precio_caja . '\',
                                                                            \'' . $reg->nombre_04 . '\',\'' . $reg->stock_fardo . '\',\'' . $reg->precio_fardo . '\',
                                                                            \'' . $reg->nombre_05 . '\',\'' . $reg->stock_sacos . '\',\'' . $reg->precio_sacos . '\',
                                                                            \'' . $reg->nombre_06 . '\',\'' . $reg->stock_paquete . '\',\'' . $reg->precio_paquete . '\',
                                                                            \'' . $reg->nombre_07 . '\',\'' . $reg->stock_07 . '\',\'' . $reg->precio_07 . '\',
                                                                            \'' . $reg->nombre_08 . '\',\'' . $reg->stock_08 . '\',\'' . $reg->precio_08 . '\',
                                                                            \'' . $reg->nombre_09 . '\',\'' . $reg->stock_09 . '\',\'' . $reg->precio_09 . '\',
                                                                            \'' . $reg->nombre_10 . '\',\'' . $reg->stock_10 . '\',\'' . $reg->precio_10 . '\',
                                                                            \'' . $reg->nombre_11 . '\',\'' . $reg->stock_11 . '\',\'' . $reg->precio_11 . '\',
                                                                            \'' . $reg->nombre_12 . '\',\'' . $reg->stock_12 . '\',\'' . $reg->precio_12 . '\',
                                                                            \'' . $reg->nombre_13 . '\',\'' . $reg->stock_13 . '\',\'' . $reg->precio_13 . '\',
                                                                            \'' . $reg->nombre_14 . '\',\'' . $reg->stock_14 . '\',\'' . $reg->precio_14 . '\',
                                                                            \'' . $reg->nombre_15 . '\',\'' . $reg->stock_15 . '\',\'' . $reg->precio_15 . '\',
                                                                            \'' . $reg->nombre_16 . '\',\'' . $reg->stock_16 . '\',\'' . $reg->precio_16 . '\',
                                                                            \'' . $reg->nombre_17 . '\',\'' . $reg->stock_17 . '\',\'' . $reg->precio_17 . '\',
                                                                            \'' . $reg->nombre_18 . '\',\'' . $reg->stock_18 . '\',\'' . $reg->precio_18 . '\',
                                                                            \'' . $reg->nombre_19 . '\',\'' . $reg->stock_19 . '\',\'' . $reg->precio_19 . '\',
                                                                            \'' . $reg->nombre_20 . '\',\'' . $reg->stock_20 . '\',\'' . $reg->precio_20 . '\',
                                                                            \'' . $reg->precio_activado . '\',
                                                                            \'' . $reg->facturar_cero . '\',\'' . $reg->precio_compra . '\',
                                                                            document.getElementById(\'cantidad_' . $reg->idarticulo . '\').value)">
                                <span class="fa fa-plus"></span></button>
                            </button>
                    </div>',
                "1" => $reg->nombre,
                "2" => (
                    $reg->dias_vencimiento === null || $reg->dias_vencimiento === '' ?
                    '<span class="label" style="background-color: #00c0ef; font-size: 16px;">Sin días</span>' : ($reg->dias_vencimiento <= -90 ?
                        '<span class="label bg-green" style="font-size: 16px;">' . $reg->dias_vencimiento . '</span>' : ($reg->dias_vencimiento <= -60 ?
                            '<span class="label bg-yellow" style="font-size: 16px;">' . $reg->dias_vencimiento . '</span>' : ($reg->dias_vencimiento <= -30 ?
                                '<span class="label bg-red" style="font-size: 16px;">' . $reg->dias_vencimiento . '</span>' :
                                '<span class="label bg-gray" style="font-size: 16px;">' . $reg->dias_vencimiento . '</span>')))
                ),
                "3" => $reg->categoria,
                "4" => $reg->codigo,
                "5" => $reg->codigo_sku,
                "6" => $reg->descripcion_2,
                "7" => $reg->stock,
                "8" => ($reg->stockminimo <= $reg->stock) ? '<span class="label bg-green">Stock Normal</span>' :
                    '<span class="label bg-red">Stock Bajo</span>',
                "9" => $reg->precio_venta,
                "10" => ($reg->imagen != "" && file_exists("../files/articulos/" . $reg->imagen)) ?
                    "<img src='../files/articulos/" . $reg->imagen . "' height='50px' width='50px'>" :
                    "<img src='../files/articulos/nofoto.jpg' height='50px' width='50px'>",
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


    case 'listarActivosVentacategoria22':
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $idcategoria = $_GET["idcategoria"];

        $rspta = $articulo->listarActivosVentacategoria22($idcategoria);

        // Array de datos
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $imagen = !empty($reg->imagen) && file_exists('../files/articulos/' . $reg->imagen)
                ? '../files/articulos/' . $reg->imagen
                : '../files/articulos/nofoto.jpg';
            $nombre = str_replace(array('"', "'"), array('t.t', " "), $reg->nombre);



            $data[] = array(

                "0" => '',
                "1" => $reg->nombre,
                "2" => number_format($reg->precio_venta, 2, '.', ','),
                "3" => "<img src='" . $imagen . "' height='150px' width='150px' 
                        style='cursor:pointer;' 
                        onclick=\"agregarDetalle(" . $reg->idarticulo . ",'" . $nombre . "',
                        '" . $reg->precio_venta . "', '" . $reg->stock . "',
                        '" . $reg->descuento_porcentaje . "',
                        '" . $reg->precio_rango1 . "','" . $reg->precio_rango1_Dos . "',
                        '" . $reg->precio_rango2 . "','" . $reg->precio_rango2_Dos . "',
                        '" . $reg->precio_rango3 . "','" . $reg->precio_rango3_Dos . "',
                        '" . $reg->precio_rango1_Mecanico . "','" . $reg->precio_rango1_Distribuidor . "','" . $reg->precio_rango1_Mayorista . "',
                        '" . $reg->precio_rango2_MecanicoDos . "','" . $reg->precio_rango2_DistribuidorDos . "','" . $reg->precio_rango2_MayoristaDos . "',
                        '" . $reg->precio_rango3_MecanicoTres . "','" . $reg->precio_rango3_DistribuidorTres . "','" . $reg->precio_rango3_MayoristaTres . "',
                        '" . $reg->nombre_01 . "','" . $reg->stock_unidad . "','" . $reg->precio_unidad . "',
                        '" . $reg->nombre_02 . "','" . $reg->stock_blister . "','" . $reg->precio_blister . "',
                        '" . $reg->nombre_03 . "','" . $reg->stock_caja . "','" . $reg->precio_caja . "',
                        '" . $reg->nombre_04 . "','" . $reg->stock_fardo . "','" . $reg->precio_fardo . "',
                        '" . $reg->nombre_05 . "','" . $reg->stock_sacos . "','" . $reg->precio_sacos . "',
                        '" . $reg->nombre_06 . "','" . $reg->stock_paquete . "','" . $reg->precio_paquete . "',
                        '" . $reg->nombre_07 . "','" . $reg->stock_07 . "','" . $reg->precio_07 . "',
                        '" . $reg->nombre_08 . "','" . $reg->stock_08 . "','" . $reg->precio_08 . "',
                        '" . $reg->nombre_09 . "','" . $reg->stock_09 . "','" . $reg->precio_09 . "',
                        '" . $reg->nombre_10 . "','" . $reg->stock_10 . "','" . $reg->precio_10 . "',
                        '" . $reg->nombre_11 . "','" . $reg->stock_11 . "','" . $reg->precio_11 . "',
                        '" . $reg->nombre_12 . "','" . $reg->stock_12 . "','" . $reg->precio_12 . "',
                        '" . $reg->nombre_13 . "','" . $reg->stock_13 . "','" . $reg->precio_13 . "',
                        '" . $reg->nombre_14 . "','" . $reg->stock_14 . "','" . $reg->precio_14 . "',
                        '" . $reg->nombre_15 . "','" . $reg->stock_15 . "','" . $reg->precio_15 . "',
                        '" . $reg->nombre_16 . "','" . $reg->stock_16 . "','" . $reg->precio_16 . "',
                        '" . $reg->nombre_17 . "','" . $reg->stock_17 . "','" . $reg->precio_17 . "',
                        '" . $reg->nombre_18 . "','" . $reg->stock_18 . "','" . $reg->precio_18 . "',
                        '" . $reg->nombre_19 . "','" . $reg->stock_19 . "','" . $reg->precio_19 . "',
                        '" . $reg->nombre_20 . "','" . $reg->stock_20 . "','" . $reg->precio_20 . "',
                        '" . $reg->precio_activado . "',
                        '" . $reg->facturar_cero . "','" . $reg->precio_compra . "')\">",
                "4" => '<span class="label bg-green">' . $reg->stock . ' </span>',
                "5" => "<button type='button' class='btn btn-info' onclick='abrirModalArticulo(" . $reg->idarticulo . ")'><span class='fa fa-bars'></span></button>",
                "6" => '<span class="label bg-green">' . $reg->categoria . ' </span>'
            );
        }

        echo json_encode($data);
        break;


    case 'listarActivosVentacategoria22_descuento':
        $idcliente = $_REQUEST["idcliente"];
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $idcategoria = $_GET["idcategoria"];

        $rspta = $articulo->listarActivosVentacategoria22_descuento($idcategoria, $idcliente);

        // Array de datos
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $imagen = !empty($reg->imagen) && file_exists('../files/articulos/' . $reg->imagen)
                ? '../files/articulos/' . $reg->imagen
                : '../files/articulos/nofoto.jpg';
            $nombre = str_replace(array('"', "'"), array('t.t', " "), $reg->nombre);



            $data[] = array(

                "0" => '',
                "1" => $reg->nombre,
                "2" => number_format($reg->precio_venta, 2, '.', ','),
                "3" => "<img src='" . $imagen . "' height='150px' width='150px' 
                        style='cursor:pointer;' 
                        onclick=\"agregarDetalle(" . $reg->idarticulo . ",'" . $nombre . "',
                        '" . $reg->precio_venta . "', '" . $reg->stock . "',
                        '" . $reg->descuento_porcentaje . "',
                        '" . $reg->precio_rango1 . "','" . $reg->precio_rango1_Dos . "',
                        '" . $reg->precio_rango2 . "','" . $reg->precio_rango2_Dos . "',
                        '" . $reg->precio_rango3 . "','" . $reg->precio_rango3_Dos . "',
                        '" . $reg->precio_rango1_Mecanico . "','" . $reg->precio_rango1_Distribuidor . "','" . $reg->precio_rango1_Mayorista . "',
                        '" . $reg->precio_rango2_MecanicoDos . "','" . $reg->precio_rango2_DistribuidorDos . "','" . $reg->precio_rango2_MayoristaDos . "',
                        '" . $reg->precio_rango3_MecanicoTres . "','" . $reg->precio_rango3_DistribuidorTres . "','" . $reg->precio_rango3_MayoristaTres . "',
                        '" . $reg->nombre_01 . "','" . $reg->stock_unidad . "','" . $reg->precio_unidad . "',
                        '" . $reg->nombre_02 . "','" . $reg->stock_blister . "','" . $reg->precio_blister . "',
                        '" . $reg->nombre_03 . "','" . $reg->stock_caja . "','" . $reg->precio_caja . "',
                        '" . $reg->nombre_04 . "','" . $reg->stock_fardo . "','" . $reg->precio_fardo . "',
                        '" . $reg->nombre_05 . "','" . $reg->stock_sacos . "','" . $reg->precio_sacos . "',
                        '" . $reg->nombre_06 . "','" . $reg->stock_paquete . "','" . $reg->precio_paquete . "',
                        '" . $reg->nombre_07 . "','" . $reg->stock_07 . "','" . $reg->precio_07 . "',
                        '" . $reg->nombre_08 . "','" . $reg->stock_08 . "','" . $reg->precio_08 . "',
                        '" . $reg->nombre_09 . "','" . $reg->stock_09 . "','" . $reg->precio_09 . "',
                        '" . $reg->nombre_10 . "','" . $reg->stock_10 . "','" . $reg->precio_10 . "',
                        '" . $reg->nombre_11 . "','" . $reg->stock_11 . "','" . $reg->precio_11 . "',
                        '" . $reg->nombre_12 . "','" . $reg->stock_12 . "','" . $reg->precio_12 . "',
                        '" . $reg->nombre_13 . "','" . $reg->stock_13 . "','" . $reg->precio_13 . "',
                        '" . $reg->nombre_14 . "','" . $reg->stock_14 . "','" . $reg->precio_14 . "',
                        '" . $reg->nombre_15 . "','" . $reg->stock_15 . "','" . $reg->precio_15 . "',
                        '" . $reg->nombre_16 . "','" . $reg->stock_16 . "','" . $reg->precio_16 . "',
                        '" . $reg->nombre_17 . "','" . $reg->stock_17 . "','" . $reg->precio_17 . "',
                        '" . $reg->nombre_18 . "','" . $reg->stock_18 . "','" . $reg->precio_18 . "',
                        '" . $reg->nombre_19 . "','" . $reg->stock_19 . "','" . $reg->precio_19 . "',
                        '" . $reg->nombre_20 . "','" . $reg->stock_20 . "','" . $reg->precio_20 . "',
                        '" . $reg->precio_activado . "',
                        '" . $reg->facturar_cero . "','" . $reg->precio_compra . "')\">",
                "4" => '<span class="label bg-green">' . $reg->stock . ' </span>',
                "5" => "<button type='button' class='btn btn-info' onclick='abrirModalArticulo(" . $reg->idarticulo . ")'><span class='fa fa-bars'></span></button>",
                "6" => '<span class="label bg-green">' . $reg->categoria . ' </span>'
            );
        }

        echo json_encode($data);
        break;

    case 'listarArticulosVentaCantidadVenta2':
        $idarticulo = $_REQUEST["idarticulo"];
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $rspta = $articulo->listarActivosVentaxsucursalVenta2($idarticulo);
        //Vamos a declarar un array
        $data = array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(

                "0" => $reg->nombre_sucursal . '   ' . $reg->direccion_sucursal,
                "1" => $reg->nombre,
                "2" => (
                    $reg->dias_vencimiento === null || $reg->dias_vencimiento === '' ?
                    '<span class="label" style="background-color: #00c0ef; font-size: 16px;">Sin días</span>' : ($reg->dias_vencimiento <= -90 ?
                        '<span class="label bg-green" style="font-size: 16px;">' . $reg->dias_vencimiento . '</span>' : ($reg->dias_vencimiento <= -60 ?
                            '<span class="label bg-yellow" style="font-size: 16px;">' . $reg->dias_vencimiento . '</span>' : ($reg->dias_vencimiento <= -30 ?
                                '<span class="label bg-red" style="font-size: 16px;">' . $reg->dias_vencimiento . '</span>' :
                                '<span class="label bg-gray" style="font-size: 16px;">' . $reg->dias_vencimiento . '</span>')))
                ),
                "3" => $reg->categoria,
                "4" => $reg->codigo,
                "5" => $reg->codigo_sku,
                "6" => $reg->descripcion_2,
                "7" => number_format($reg->stock, 2, '.', ','),
                "8" => ($reg->stockminimo <= $reg->stock) ? '<span class="label bg-green">Stock Normal</span>' :
                    '<span class="label bg-red">Stock Bajo</span>',
                "9" => number_format($reg->precio_venta, 2, '.', ',')
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





    case 'listarArticulosVenta':
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $rspta = $articulo->listarActivosVenta();
        //Vamos a declarar un array
        $data = array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="btn btn-warning" onclick="agregarDetalle(' . $reg->idarticulo . ',\'' . str_replace('"', 't.t', $reg->nombre) . '\',
                                                                            \'' . $reg->precio_venta . '\',
                                                                            \'' . $reg->stock . '\',
                                                                            \'' . $reg->descuento_porcentaje . '\',\'' . $reg->stock_unidad . '\',\'' . $reg->precio_unidad . '\',
                                                                            \'' . $reg->stock_blister . '\',\'' . $reg->precio_blister . '\',\'' . $reg->stock_caja . '\',
                                                                            \'' . $reg->precio_caja . '\',\'' . $reg->stock_fardo . '\',\'' . $reg->precio_fardo . '\',\'' . $reg->stock_sacos . '\',
                                                                            \'' . $reg->precio_sacos . '\',\'' . $reg->stock_paquete . '\',\'' . $reg->precio_paquete . '\',\'' . $reg->precio_rango1 . '\',
                                                                            \'' . $reg->precio_rango2 . '\',\'' . $reg->precio_rango3 . '\',\'' . $reg->precio_compra . '\',\'' . $reg->precio_activado . '\',
                                                                            \'' . $reg->precio_rango1_Dos . '\',\'' . $reg->precio_rango2_Dos . '\',\'' . $reg->precio_rango3_Dos . '\',
                                                                            \'' . $reg->precio_rango1_Mecanico . '\',\'' . $reg->precio_rango2_MecanicoDos . '\',\'' . $reg->precio_rango3_MecanicoTres . '\',
                                                                            \'' . $reg->precio_rango1_Distribuidor . '\',\'' . $reg->precio_rango2_DistribuidorDos . '\',\'' . $reg->precio_rango3_DistribuidorTres . '\',
                                                                            \'' . $reg->precio_rango1_Mayorista . '\',\'' . $reg->precio_rango2_MayoristaDos . '\',
                                                                            \'' . $reg->precio_rango3_MayoristaTres . '\'
                                                                            )"><span class="fa fa-plus"></span></button>',
                "1" => $reg->nombre,
                "2" => $reg->categoria,
                "3" => $reg->codigo,
                "4" => $reg->codigo_sku,
                "5" => $reg->descripcion_2,
                "6" => $reg->stock,
                "7" => ($reg->stockminimo <= $reg->stock) ? '<span class="label bg-green">Stock Normal</span>' :
                    '<span class="label bg-red">Stock Bajo</span>',
                "8" => $reg->precio_venta,
                "9" => ($reg->imagen != "" && file_exists("../files/articulos/" . $reg->imagen)) ?
                    "<img src='../files/articulos/" . $reg->imagen . "' height='50px' width='50px'>" :
                    "<img src='../files/articulos/nofoto.jpg' height='50px' width='50px'>",
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

    case 'listarArticulosVentaKardex':
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $rspta = $articulo->listarActivosVenta();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="btn btn-warning" onclick="agregarDetalleKardex(\'' . addslashes($reg->codigo) . '\')"><span class="fa fa-plus"></span></button>',
                "1" => $reg->nombre,
                "2" => $reg->descripcion,
                "3" => $reg->descripcion_2,
                "4" => $reg->categoria,
                "5" => $reg->codigo,
                "6" => ($reg->stockminimo <= $reg->stock) ? '<span class="label bg-green">Stock Normal</span>' :
                    '<span class="label bg-red">Stock Bajo</span>',
                "7" => $reg->precio_venta,
                "8" => "<img src='../files/articulos/" . $reg->imagen . "' height='50px' width='50px' >"
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


    case 'listarArticulosxcategoria':
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $idcategoria = $_GET["idcategoria"];

        $rspta = $articulo->listarActivosVentacategoria($idcategoria);

        // Array de datos
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $imagen = !empty($reg->imagen) && file_exists('../files/articulos/' . $reg->imagen)
                ? '../files/articulos/' . $reg->imagen
                : '../files/articulos/nofoto.jpg';

            $data[] = array(
                "0" => '<button class="btn btn-warning" onclick="agregarDetalle(' . $reg->idarticulo . ',\'' . str_replace('"', 't.t', $reg->nombre) . '\',
                                                                                \'' . $reg->precio_venta . '\',
                                                                                \'' . $reg->stock . '\',
                                                                                \'' . $reg->descuento_porcentaje . '\',
                                                                                \'' . $reg->stock_unidad . '\',
                                                                                \'' . $reg->precio_unidad . '\',
                                                                                \'' . $reg->stock_blister . '\',
                                                                                \'' . $reg->precio_blister . '\',
                                                                                \'' . $reg->stock_caja . '\',
                                                                                \'' . $reg->precio_caja . '\',
                                                                                \'' . $reg->stock_fardo . '\',
                                                                                \'' . $reg->precio_fardo . '\',
                                                                                \'' . $reg->stock_sacos . '\',
                                                                                \'' . $reg->precio_sacos . '\',
                                                                                \'' . $reg->stock_paquete . '\',
                                                                                \'' . $reg->precio_paquete . '\',
                                                                                \'' . $reg->precio_rango1 . '\',
                                                                                \'' . $reg->precio_rango2 . '\',
                                                                                \'' . $reg->precio_rango3 . '\')"><span class="fa fa-plus"> Agregar Item</span></button>',
                "1" => $reg->nombre,
                "2" => $reg->precio_venta,
                "3" => "<img src='" . $imagen . "' height='150px' width='150px'>"
            );
        }

        echo json_encode($data);
        break;





    case "selectMensajero":
        require_once "../modelos/Mensajero.php";
        $mensajero = new Mensajero();

        $rspta = $mensajero->select();

        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idmensajero . '>' . $reg->nombre . ' -- ' . $reg->telefono . '</option>';
        }
        break;

    //PARA MECANICO
    case 'listarArticulosMecanico':
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $rspta = $articulo->listarActivosVenta();
        //Vamos a declarar un array
        $data = array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="btn btn-warning" onclick="agregarDetalle(' . $reg->idarticulo . ',\'' . str_replace('"', 't.t', $reg->nombre) . '\',
                                                                            \'' . $reg->precio_venta . '\',
                                                                            \'' . $reg->stock . '\',
                                                                            \'' . $reg->descuento_porcentaje . '\',\'' . $reg->stock_unidad . '\',\'' . $reg->precio_unidad . '\',
                                                                            \'' . $reg->stock_blister . '\',\'' . $reg->precio_blister . '\',\'' . $reg->stock_caja . '\',
                                                                            \'' . $reg->precio_caja . '\',\'' . $reg->stock_fardo . '\',\'' . $reg->precio_fardo . '\',\'' . $reg->stock_sacos . '\',
                                                                            \'' . $reg->precio_sacos . '\',\'' . $reg->stock_paquete . '\',\'' . $reg->precio_paquete . '\',\'' . $reg->precio_rango1 . '\',
                                                                            \'' . $reg->precio_rango2 . '\',\'' . $reg->precio_rango3 . '\',\'' . $reg->precio_compra . '\',\'' . $reg->precio_activado . '\',
                                                                            \'' . $reg->precio_rango1_Dos . '\',\'' . $reg->precio_rango2_Dos . '\',\'' . $reg->precio_rango3_Dos . '\',
                                                                            \'' . $reg->precio_rango1_Mecanico . '\',\'' . $reg->precio_rango2_MecanicoDos . '\',\'' . $reg->precio_rango3_MecanicoTres . '\',
                                                                            \'' . $reg->precio_rango1_Distribuidor . '\',\'' . $reg->precio_rango2_DistribuidorDos . '\',\'' . $reg->precio_rango3_DistribuidorTres . '\',
                                                                            \'' . $reg->precio_rango1_Mayorista . '\',\'' . $reg->precio_rango2_MayoristaDos . '\',
                                                                            \'' . $reg->precio_rango3_MayoristaTres . '\'
                                                                            )"><span class="fa fa-plus"></span></button>',
                "1" => $reg->nombre,
                "2" => $reg->categoria,
                "3" => $reg->codigo,
                "4" => $reg->stock,
                "5" => $reg->precio_venta,
                "6" => ($reg->imagen != "" && file_exists("../files/articulos/" . $reg->imagen)) ?
                    "<img src='../files/articulos/" . $reg->imagen . "' height='50px' width='50px'>" :
                    "<img src='../files/articulos/nofoto.jpg' height='50px' width='50px'>",
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

    case 'listarpantallaventas':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $tipo_envioPedidos = $_REQUEST["tipo_envioPedidos"];

        $rspta = $venta->listarCabeceraspantalla($fecha_inicio, $fecha_fin, $tipo_envioPedidos);
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            // 🔹 Traer el detalle de esta cabecera
            $detalle = array();
            $rsptaDetalle = $venta->ventadetallepantalla($reg->idventa);
            while ($det = $rsptaDetalle->fetch_object()) {
                $detalle[] = array(
                    "iddetalle_venta" => $det->iddetalle_venta,
                    "cantidad" => $det->cantidad,
                    "nombre_articulo" => $det->articulo,
                    "descripcion_detalle" => $det->presen . ' - ' . $det->descripcion_detalle,
                    "tipo" => $det->tipo
                );
            }

            // 🔹 Cabecera + detalle
            $data[] = array(
                "idventa" => $reg->idventa,
                "fecha" => $reg->fecha,
                "nombre" => $reg->cliente,
                "direccion" => $reg->direccion,
                "telefono" => $reg->telefono,
                "detalle" => $detalle
            );
        }

        echo json_encode($data);
        break;


    case 'listo':
        $rspta = $venta->listo($idventa);
        echo $rspta ? "Pedido Enviada" : "Pedido no se puede enviar";
        break;

    case 'cambiarformapago':
        $rspta = $venta->cambiarformapago($idventa);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;

    case 'obtenerdetalleventarefacturado':
        $cotiz = $venta->obtenerdetalleventarefacturado($idventa);
        echo json_encode($cotiz);
        break;

    case 'guardaryeditar_formapago':
        // ✅ Recepción segura de variables (evita Undefined index)
        $idventa_formapago = isset($_REQUEST["idventa_formapago"]) ? $_REQUEST["idventa_formapago"] : "";
        $forma_pago_formapago = isset($_REQUEST["forma_pago_formapago"]) ? $_REQUEST["forma_pago_formapago"] : "";
        $destino_formapago = isset($_REQUEST["destino_formapago"]) ? $_REQUEST["destino_formapago"] : "";
        $tipo_entrega_formapago = isset($_REQUEST["tipo_entrega_formapago"]) ? $_REQUEST["tipo_entrega_formapago"] : "";
        $idvendedor_formapago = isset($_REQUEST["idvendedor_formapago"]) ? $_REQUEST["idvendedor_formapago"] : "";
        $tipo_pagoBacVisaNet_formapago = isset($_REQUEST["tipo_pagoBacVisaNet_formapago"]) ? $_REQUEST["tipo_pagoBacVisaNet_formapago"] : "";
        $opcionesAdicionales_formapago = isset($_REQUEST["opcionesAdicionales_formapago"]) ? $_REQUEST["opcionesAdicionales_formapago"] : "";
        $total_venta_formapago = isset($_REQUEST["total_venta_formapago"]) ? floatval($_REQUEST["total_venta_formapago"]) : 0;
        $total_ventades_formapago = isset($_REQUEST["total_ventades_formapago"]) ? floatval($_REQUEST["total_ventades_formapago"]) : 0;
        $cefectivo_formapago = isset($_REQUEST["cefectivo_formapago"]) ? floatval($_REQUEST["cefectivo_formapago"]) : 0;
        $ccredito_formapago = isset($_REQUEST["ccredito_formapago"]) ? floatval($_REQUEST["ccredito_formapago"]) : 0;
        $ctransferencia_formapago = isset($_REQUEST["ctransferencia_formapago"]) ? floatval($_REQUEST["ctransferencia_formapago"]) : 0;
        $observacion_credito_formapago = isset($_REQUEST["observacion_credito_formapago"]) ? $_REQUEST["observacion_credito_formapago"] : "";
        $ctarjeta_formapago = isset($_REQUEST["ctarjeta_formapago"]) ? floatval($_REQUEST["ctarjeta_formapago"]) : 0;
        $valor_tarjeta_formapago = isset($_REQUEST["valor_tarjeta_formapago"]) ? floatval($_REQUEST["valor_tarjeta_formapago"]) : 0;
        $numero_pagos_formapago = isset($_REQUEST["numero_pagos_formapago"]) ? intval($_REQUEST["numero_pagos_formapago"]) : 0;
        $fecha_hora_pago_formapago = isset($_REQUEST["fecha_hora_pago_formapago"]) ? $_REQUEST["fecha_hora_pago_formapago"] : "";
        $fecha_hora_vencimiento_factura_formapago = isset($_REQUEST["fecha_hora_vencimiento_factura_formapago"]) ? $_REQUEST["fecha_hora_vencimiento_factura_formapago"] : "";
        $monto_abono_formapago = isset($_REQUEST["monto_abono_formapago"]) ? floatval($_REQUEST["monto_abono_formapago"]) : 0;
        $rescambio_formapago = isset($_REQUEST["rescambio_formapago"]) ? floatval($_REQUEST["rescambio_formapago"]) : 0;

        $rspta = $venta->guardaryeditar_formapago(
            $idventa_formapago,
            $forma_pago_formapago,
            $destino_formapago,
            $tipo_entrega_formapago,
            $idvendedor_formapago,
            $tipo_pagoBacVisaNet_formapago,
            $opcionesAdicionales_formapago,
            $total_venta_formapago,
            $total_ventades_formapago,
            $cefectivo_formapago,
            $ccredito_formapago,
            $ctransferencia_formapago,
            $observacion_credito_formapago,
            $ctarjeta_formapago,
            $valor_tarjeta_formapago,
            $numero_pagos_formapago,
            $fecha_hora_pago_formapago,
            $fecha_hora_vencimiento_factura_formapago,
            $monto_abono_formapago,
            $rescambio_formapago
        );
        echo json_encode($rspta);
        break;

    case 'listar_ventas_servicios':

        $fecha_inicio_reporte = $_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte = $_REQUEST["fecha_fin_reporte"];

        $rspta = $venta->listar_ventas_servicios($fecha_inicio_reporte, $fecha_fin_reporte);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {


            if ($reg->tipo_comprobante == 'Factura') {
                # code... 
                $url = '../reportes/exTicket_Fel.php?id=';
                $url2 = '../reportes/exTicket_Fel58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta_Fel.php?id=';
                $url4 = '../reportes/exVentaBlancoFAC.php?id=';
            } elseif ($reg->tipo_comprobante == 'Cambiaria') {
                # code... 
                $url = '../reportes/exTicket_FelFCAM.php?id=';
                $url2 = '../reportes/exTicket_Fel_FCAM58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta_FelFCAM.php?id=';
                $url4 = '../reportes/exVentaBlancoCAM.php?id=';
            } else {
                $url = '../reportes/exTicket.php?id=';
                $url2 = '../reportes/exTicket58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta.php?id=';
                $url4 = '../reportes/exVentaBlancoENVIO.php?id=';
            }

            if ($reg->estado == 'Aceptado') {
                # code... 
                $resventa = $reg->total_venta;
                $resventades = $reg->total_ventades;
            } else {
                $resventa = 0;
                $resventades = 0;
            }

            if ($reg->forma_pago == 'Tarjeta') {
                # code... 
                $resDatostarjeta = $reg->tipo_pagoBacVisaNet . " / " . $reg->opcionesAdicionales . " / " . $reg->valor_tarjeta;
            } else {
                $resDatostarjeta = " ";
            }

            // Validamos si la fecha de certificación es '0000-00-00 00:00:00'
            $fecha_certificacion = ($reg->fechaCertificacion_ecoFactura == '0000-00-00 00:00:00') ? 'No certificado' : $reg->fechaCertificacion_ecoFactura;


            $urlContrato = '../reportes/exContrato.php?id=';
            $urlOrdenSalida = '../reportes/exOrdenSalida.php?id=';
            $data[] = array(
                "0" => ($reg->estado == 'Aceptado') ? ' <button class="btn btn-danger" title="Anular Venta" onclick="anular(' . $reg->idventa . ')"><i class="fa fa-close"></i></button>' .
                    ' <button class="btn btn-info" title="Cambiar forma de pago" onclick="cambiarformapago(' . $reg->idventa . ')"><i class="fa fa-edit"></i></button>' .
                    '<a target="_blank" href="' . $url . $reg->idventa . '" title="Ticket 79mm"><button class="btn btn-success" title="Imprimir Ticket 79mm"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url2 . $reg->idventa . '"  title="Ticket 58mm"><button class="btn btn-info" title="Imprimir Ticket 58mm"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url4 . $reg->idventa . '"  title="Carta en Blanco"><button class="btn btn-info" title="Imprimir Carta en Blanco"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url3 . $reg->idventa . '"  title="Carta Colores"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>' :
                    '<a target="_blank" href="' . $url . $reg->idventa . '"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url2 . $reg->idventa . '"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url3 . $reg->idventa . '"  title="Carta"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->idventa,
                "2" => $reg->cliente,
                "3" => $reg->usuario,
                "4" => $reg->tipo_comprobante,
                "5" => $reg->num_comprobante,
                "6" => $resventa,
                "7" => $resventades,
                "8" => $reg->forma_pago . " - " . $resDatostarjeta,
                "9" => $reg->cefectivo,
                "10" => $reg->ctarjeta,
                "11" => $reg->ccredito,
                "12" => $reg->ctransferencia,
                "13" => $reg->rescambio,
                "14" => $reg->fecha,
                "15" => $fecha_certificacion,
                "16" => $reg->serie_ecoFactura,
                "17" => $reg->numero_ecoFactura,
                "18" => $reg->tipo_entrega,
                "19" => $reg->nom_venedor,
                "20" => $reg->idcotizacion,
                "21" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
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

    case 'selectCobradores':
        $rspta = $venta->selectCobradores();
        echo '<option value="">Seleccione un Cobrador</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idcobradores . '>' . $reg->nombre . '--' . $reg->direccion . '</option>';
        }
        break;

    case 'selectTecnicos':
        $rspta = $venta->selectTecnicos();
        echo '<option value="">Seleccione un Tecnico</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idtecnico . '>' . $reg->nombre . '--' . $reg->direccion . '</option>';
        }
        break;

    case 'listar_ventas_servicios_usuario':

        $fecha_inicio_reporte = $_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte = $_REQUEST["fecha_fin_reporte"];

        $rspta = $venta->listar_ventas_servicios_usuario($fecha_inicio_reporte, $fecha_fin_reporte);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {


            if ($reg->tipo_comprobante == 'Factura') {
                # code... 
                $url = '../reportes/exTicket_Fel.php?id=';
                $url2 = '../reportes/exTicket_Fel58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta_Fel.php?id=';
                $url4 = '../reportes/exVentaBlancoFAC.php?id=';
            } elseif ($reg->tipo_comprobante == 'Cambiaria') {
                # code... 
                $url = '../reportes/exTicket_FelFCAM.php?id=';
                $url2 = '../reportes/exTicket_Fel_FCAM58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta_FelFCAM.php?id=';
                $url4 = '../reportes/exVentaBlancoCAM.php?id=';
            } else {
                $url = '../reportes/exTicket.php?id=';
                $url2 = '../reportes/exTicket58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta.php?id=';
                $url4 = '../reportes/exVentaBlancoENVIO.php?id=';
            }

            if ($reg->estado == 'Aceptado') {
                # code... 
                $resventa = $reg->total_venta;
                $resventades = $reg->total_ventades;
            } else {
                $resventa = 0;
                $resventades = 0;
            }

            if ($reg->forma_pago == 'Tarjeta') {
                # code... 
                $resDatostarjeta = $reg->tipo_pagoBacVisaNet . " / " . $reg->opcionesAdicionales . " / " . $reg->valor_tarjeta;
            } else {
                $resDatostarjeta = " ";
            }

            // Validamos si la fecha de certificación es '0000-00-00 00:00:00'
            $fecha_certificacion = ($reg->fechaCertificacion_ecoFactura == '0000-00-00 00:00:00') ? 'No certificado' : $reg->fechaCertificacion_ecoFactura;


            $urlContrato = '../reportes/exContrato.php?id=';
            $urlOrdenSalida = '../reportes/exOrdenSalida.php?id=';
            if ($reg->estado_venta_servicio == "PENDIENTE") {
                $estado_venta_servicio = '<span class="label bg-yellow">Pendiente</span>';
            } else if ($reg->estado_venta_servicio == "ENTREGADO") {
                $estado_venta_servicio = '<span class="label bg-green">Entregado</span>';
            } else if ($reg->estado_venta_servicio == "NO ENTREGADO") {
                $estado_venta_servicio = '<span class="label bg-red">No Entregado</span>';
            }
            $btnServicio = '';
            if ($reg->estado_venta_servicio != 'ENTREGADO') {
                $btnServicio = ' <button class="btn btn-primary" onclick="mostrarModalServicio(' . $reg->idventa . ')" title="Cambiar Estado Servicio"><i class="fa fa-cogs"></i></button>';
            }
            $url_rpt_serivcio = '../reportes/exRpt_Servicio.php?id=';
            $data[] = array(
                "0" => ($reg->estado == 'Aceptado') ?
                    '<a target="_blank" href="' . $url . $reg->idventa . '" title="Ticket 79mm"><button class="btn btn-success" title="Imprimir Ticket 79mm"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url2 . $reg->idventa . '"  title="Ticket 58mm"><button class="btn btn-info" title="Imprimir Ticket 58mm"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url4 . $reg->idventa . '"  title="Carta en Blanco"><button class="btn btn-info" title="Imprimir Carta en Blanco"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url3 . $reg->idventa . '"  title="Carta Colores"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url_rpt_serivcio . $reg->idventa . '"  title="Reporte de Servicio"><button class="btn btn-success"><i class="fa fa-file"></i> </button> </a>' .
                    $btnServicio :
                    '<a target="_blank" href="' . $url . $reg->idventa . '"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url2 . $reg->idventa . '"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url_rpt_serivcio . $reg->idventa . '"  title="Reporte de Servicio"><button class="btn btn-success"><i class="fa fa-file"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url3 . $reg->idventa . '"  title="Carta"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->idventa,
                "2" => $reg->cliente,
                "3" => $reg->usuario,
                "4" => $reg->tipo_comprobante,
                "5" => $resventa,
                "6" => $resventades,
                "7" => $reg->forma_pago . " - " . $resDatostarjeta,
                "8" => $reg->cefectivo,
                "9" => $reg->ctarjeta,
                "10" => $reg->ccredito,
                "11" => $reg->ctransferencia,
                "12" => $reg->rescambio,
                "13" => $reg->fecha,
                "14" => $fecha_certificacion,
                "15" => $reg->serie_ecoFactura,
                "16" => $reg->numero_ecoFactura,
                "17" => $reg->tipo_entrega,
                "18" => $reg->nom_venedor,
                "19" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>',
                "20" => $estado_venta_servicio,
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

    case 'registrar_venta_servicio':
        $idventa_servicio = isset($_POST["idventa_servicio"]) ? limpiarCadena($_POST["idventa_servicio"]) : "";
        $descripcion_comentario = isset($_POST["descripcion_comentario"]) ? limpiarCadena($_POST["descripcion_comentario"]) : "";
        $ip_instalacion = isset($_POST["ip_instalacion"]) ? limpiarCadena($_POST["ip_instalacion"]) : "";
        $estado_servicio_venta = isset($_POST["estado_servicio_venta"]) ? limpiarCadena($_POST["estado_servicio_venta"]) : "";
        $ubicacioncliente = isset($_POST["ubicacioncliente"]) ? limpiarCadena($_POST["ubicacioncliente"]) : "";
        $idusuario = $_SESSION['idusuario'];
        $idsucursal = $_SESSION['idsucursal'];

        $rspta = $venta->registrar_venta_servicio($idventa_servicio, $descripcion_comentario, $ip_instalacion, $estado_servicio_venta, $ubicacioncliente, $idusuario, $idsucursal);
        echo $rspta;
        break;

    case 'listar_ventas_servicios_general':

        $fecha_inicio_reporte = $_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte = $_REQUEST["fecha_fin_reporte"];

        $rspta = $venta->listar_ventas_servicios_general($fecha_inicio_reporte, $fecha_fin_reporte);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {


            if ($reg->tipo_comprobante == 'Factura') {
                # code... 
                $url = '../reportes/exTicket_Fel.php?id=';
                $url2 = '../reportes/exTicket_Fel58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta_Fel.php?id=';
                $url4 = '../reportes/exVentaBlancoFAC.php?id=';
            } elseif ($reg->tipo_comprobante == 'Cambiaria') {
                # code... 
                $url = '../reportes/exTicket_FelFCAM.php?id=';
                $url2 = '../reportes/exTicket_Fel_FCAM58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta_FelFCAM.php?id=';
                $url4 = '../reportes/exVentaBlancoCAM.php?id=';
            } else {
                $url = '../reportes/exTicket.php?id=';
                $url2 = '../reportes/exTicket58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta.php?id=';
                $url4 = '../reportes/exVentaBlancoENVIO.php?id=';
            }

            if ($reg->estado == 'Aceptado') {
                # code... 
                $resventa = $reg->total_venta;
                $resventades = $reg->total_ventades;
            } else {
                $resventa = 0;
                $resventades = 0;
            }

            if ($reg->forma_pago == 'Tarjeta') {
                # code... 
                $resDatostarjeta = $reg->tipo_pagoBacVisaNet . " / " . $reg->opcionesAdicionales . " / " . $reg->valor_tarjeta;
            } else {
                $resDatostarjeta = " ";
            }

            // Validamos si la fecha de certificación es '0000-00-00 00:00:00'
            $fecha_certificacion = ($reg->fechaCertificacion_ecoFactura == '0000-00-00 00:00:00') ? 'No certificado' : $reg->fechaCertificacion_ecoFactura;


            $urlContrato = '../reportes/exContrato.php?id=';
            $urlOrdenSalida = '../reportes/exOrdenSalida.php?id=';
            if ($reg->estado_venta_servicio == "PENDIENTE") {
                $estado_venta_servicio = '<span class="label bg-yellow">Pendiente</span>';
            } else if ($reg->estado_venta_servicio == "ENTREGADO") {
                $estado_venta_servicio = '<span class="label bg-green">Entregado</span>';
            } else if ($reg->estado_venta_servicio == "NO ENTREGADO") {
                $estado_venta_servicio = '<span class="label bg-red">No Entregado</span>';
            }
            $btnServicio = '';
            if ($reg->estado_venta_servicio != 'ENTREGADO') {
                $btnServicio = ' <button class="btn btn-primary" onclick="mostrarModalServicio(' . $reg->idventa . ')" title="Cambiar Estado Servicio"><i class="fa fa-cogs"></i></button>';
            }
            $url_rpt_serivcio = '../reportes/exRpt_Servicio.php?id=';
            $data[] = array(
                "0" => ($reg->estado == 'Aceptado') ?
                    '<a target="_blank" href="' . $url . $reg->idventa . '" title="Ticket 79mm"><button class="btn btn-success" title="Imprimir Ticket 79mm"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url2 . $reg->idventa . '"  title="Ticket 58mm"><button class="btn btn-info" title="Imprimir Ticket 58mm"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url4 . $reg->idventa . '"  title="Carta en Blanco"><button class="btn btn-info" title="Imprimir Carta en Blanco"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url3 . $reg->idventa . '"  title="Carta Colores"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url_rpt_serivcio . $reg->idventa . '"  title="Reporte de Servicio"><button class="btn btn-success"><i class="fa fa-file"></i> </button> </a>' .
                    $btnServicio :
                    '<a target="_blank" href="' . $url . $reg->idventa . '"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url2 . $reg->idventa . '"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url_rpt_serivcio . $reg->idventa . '"  title="Reporte de Servicio"><button class="btn btn-success"><i class="fa fa-file"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url3 . $reg->idventa . '"  title="Carta"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->idventa,
                "2" => $reg->cliente,
                "3" => $reg->usuario,
                "4" => $reg->tipo_comprobante,
                "5" => $resventa,
                "6" => $resventades,
                "7" => $reg->forma_pago . " - " . $resDatostarjeta,
                "8" => $reg->cefectivo,
                "9" => $reg->ctarjeta,
                "10" => $reg->ccredito,
                "11" => $reg->ctransferencia,
                "12" => $reg->rescambio,
                "13" => $reg->fecha,
                "14" => $fecha_certificacion,
                "15" => $reg->serie_ecoFactura,
                "16" => $reg->numero_ecoFactura,
                "17" => $reg->tipo_entrega,
                "18" => $reg->nom_venedor,
                "19" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>',
                "20" => $estado_venta_servicio,
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

    case 'procesar_venta_directa':
        $idcotizacion = isset($_POST["idcotizacion"]) ? limpiarCadena($_POST["idcotizacion"]) : "";
        $venta_lote   = isset($_POST["venta_lote"]) ? limpiarCadena($_POST["venta_lote"]) : "";
        $rspta = $venta->procesarVentaIndividual($idcotizacion, $venta_lote);
        echo json_encode($rspta);
        break;
}
