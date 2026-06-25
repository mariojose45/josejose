<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';
 
require_once "../modelos/Venta_rapida.php"; 
  
$venta=new Venta_rapida();                       
      
$idventa=isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$codigo_cliente=isset($_POST["codigo_cliente"])? limpiarCadena($_POST["codigo_cliente"]):""; 
$nit=isset($_POST["nit"])? limpiarCadena($_POST["nit"]):"";
$nombre_cliente=isset($_POST["nombre_cliente"])? limpiarCadena($_POST["nombre_cliente"]):"";
$telefono_cliente=isset($_POST["telefono_cliente"])? limpiarCadena($_POST["telefono_cliente"]):"";
$direccion_cliente=isset($_POST["direccion_cliente"])? limpiarCadena($_POST["direccion_cliente"]):"";
$correo_cliente=isset($_POST["correo_cliente"])? limpiarCadena($_POST["correo_cliente"]):"";
$tipo_cliente=isset($_POST["tipo_cliente"])? limpiarCadena($_POST["tipo_cliente"]):"";
$tipo_documento_cliente=isset($_POST["tipo_documento_cliente"])? limpiarCadena($_POST["tipo_documento_cliente"]):"";
$idusuario=$_SESSION["idusuario"]; 
$idcotizacion=isset($_POST["idcotizacion"])? limpiarCadena($_POST["idcotizacion"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$forma_pago=isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
  
 
$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";
$total_ventades=isset($_POST["total_ventades"])? limpiarCadena($_POST["total_ventades"]):"";
$cefectivo=isset($_POST["cefectivo"])? limpiarCadena($_POST["cefectivo"]):""; 
$ccredito=isset($_POST["ccredito"])? limpiarCadena($_POST["ccredito"]):""; 
$ctarjeta=isset($_POST["ctarjeta"])? limpiarCadena($_POST["ctarjeta"]):""; 
$ctransferencia=isset($_POST["ctransferencia"])? limpiarCadena($_POST["ctransferencia"]):"";  
$rescambio=isset($_POST["rescambio"])? limpiarCadena($_POST["rescambio"]):"";
 
///datos de tarejta
$valor_tarjeta=isset($_POST["valor_tarjeta"])? limpiarCadena($_POST["valor_tarjeta"]):"";
$tipo_pagoBacVisaNet=isset($_POST["tipo_pagoBacVisaNet"])? limpiarCadena($_POST["tipo_pagoBacVisaNet"]):"";
$opcionesAdicionales=isset($_POST["opcionesAdicionales"])? limpiarCadena($_POST["opcionesAdicionales"]):"";
$observacion_credito=isset($_POST["observacion_credito"])? limpiarCadena($_POST["observacion_credito"]):"";
//restaurante
$id_add_orden=isset($_POST["id_add_orden"])? limpiarCadena($_POST["id_add_orden"]):"";
$propina=isset($_POST["propina"])? limpiarCadena($_POST["propina"]):"";


 

//////NOTAS DE CREDITO
$autorizacionEcoFactura_venta=isset($_POST["autorizacionEcoFactura_venta"])? limpiarCadena($_POST["autorizacionEcoFactura_venta"]):"";
$serie_comprobante_venta=isset($_POST["serie_comprobante_venta"])? limpiarCadena($_POST["serie_comprobante_venta"]):"";
$numero_ecoFactura_venta=isset($_POST["numero_ecoFactura_venta"])? limpiarCadena($_POST["numero_ecoFactura_venta"]):"";
$fecha_hora_nc=isset($_POST["fecha_hora_nc"])? limpiarCadena($_POST["fecha_hora_nc"]):"";
$motivo_nc=isset($_POST["motivo_nc"])? limpiarCadena($_POST["motivo_nc"]):"";
//////

///GastoaVenta
$TotalEfectivoDisponible_GastoaVenta=isset($_POST["TotalEfectivoDisponible_GastoaVenta"])? limpiarCadena($_POST["TotalEfectivoDisponible_GastoaVenta"]):"";
$totalAcumuladoGasots_GastoaVenta=isset($_POST["totalAcumuladoGasots_GastoaVenta"])? limpiarCadena($_POST["totalAcumuladoGasots_GastoaVenta"]):"";
$disponibleparaGastos_GastoaVenta=isset($_POST["disponibleparaGastos_GastoaVenta"])? limpiarCadena($_POST["disponibleparaGastos_GastoaVenta"]):"";
$serie_no_GastoaVenta=isset($_POST["serie_no_GastoaVenta"])? limpiarCadena($_POST["serie_no_GastoaVenta"]):"";
$factura_no_GastoaVenta=isset($_POST["factura_no_GastoaVenta"])? limpiarCadena($_POST["factura_no_GastoaVenta"]):"";
$tipo_factura_GastoaVenta=isset($_POST["tipo_factura_GastoaVenta"])? limpiarCadena($_POST["tipo_factura_GastoaVenta"]):"";
$tipo_comprobante_GastoaVenta=isset($_POST["tipo_comprobante_GastoaVenta"])? limpiarCadena($_POST["tipo_comprobante_GastoaVenta"]):"";
$idcliente_GastoaVenta=isset($_POST["idcliente_GastoaVenta"])? limpiarCadena($_POST["idcliente_GastoaVenta"]):"";
$nit_no_GastoaVenta=isset($_POST["nit_no_GastoaVenta"])? limpiarCadena($_POST["nit_no_GastoaVenta"]):"";
$tipo_documento_cliente_GastoaVenta=isset($_POST["tipo_documento_cliente_GastoaVenta"])? limpiarCadena($_POST["tipo_documento_cliente_GastoaVenta"]):"";
$nombreproveedor_GastoaVenta=isset($_POST["nombreproveedor_GastoaVenta"])? limpiarCadena($_POST["nombreproveedor_GastoaVenta"]):"";
$direccion__GastoaVenta=isset($_POST["direccion__GastoaVenta"])? limpiarCadena($_POST["direccion__GastoaVenta"]):"";
$concepto_fac_GastoaVenta=isset($_POST["concepto_fac_GastoaVenta"])? limpiarCadena($_POST["concepto_fac_GastoaVenta"]):"";
$fecha_hora_GastoaVenta=isset($_POST["fecha_hora_GastoaVenta"])? limpiarCadena($_POST["fecha_hora_GastoaVenta"]):"";
$valor_q_GastoaVenta=isset($_POST["valor_q_GastoaVenta"])? limpiarCadena($_POST["valor_q_GastoaVenta"]):"";
$tipo_compra_GastoaVenta=isset($_POST["tipo_compra_GastoaVenta"])? limpiarCadena($_POST["tipo_compra_GastoaVenta"]):"";
$tipo_combustible_GastoaVenta=isset($_POST["tipo_combustible_GastoaVenta"])? limpiarCadena($_POST["tipo_combustible_GastoaVenta"]):"";
$num_galonaje_GastoaVenta=isset($_POST["num_galonaje_GastoaVenta"])? limpiarCadena($_POST["num_galonaje_GastoaVenta"]):"";



$tipo_entrega=isset($_POST["tipo_entrega"])? limpiarCadena($_POST["tipo_entrega"]):"";



$numero_pagos=isset($_POST["numero_pagos"])? limpiarCadena($_POST["numero_pagos"]):"";
$fecha_hora_pago=isset($_POST["fecha_hora_pago"])? limpiarCadena($_POST["fecha_hora_pago"]):"";
$fecha_hora_vencimiento_factura=isset($_POST["fecha_hora_vencimiento_factura"])? limpiarCadena($_POST["fecha_hora_vencimiento_factura"]):"";
$monto_abono=isset($_POST["monto_abono"])? limpiarCadena($_POST["monto_abono"]):"";
////
 
//TRANSPORTES Y MENSAJEROS
$idtransporte=isset($_POST["idtransporte"])? limpiarCadena($_POST["idtransporte"]):"";
$idmensajero=isset($_POST["idmensajero"])? limpiarCadena($_POST["idmensajero"]):"";

$idvendedor=isset($_POST["idvendedor"])? limpiarCadena($_POST["idvendedor"]):"";

$descuento_general=isset($_POST["descuento_general"])? limpiarCadena($_POST["descuento_general"]):"";
$valor_descuentoGeneral=isset($_POST["valor_descuentoGeneral"])? limpiarCadena($_POST["valor_descuentoGeneral"]):"";

$total_venta_r=isset($_POST["total_venta_r"])? limpiarCadena($_POST["total_venta_r"]):"";
$total_ventades_r=isset($_POST["total_ventades_r"])? limpiarCadena($_POST["total_ventades_r"]):"";



$numero_deposito_transferencia=isset($_POST["numero_deposito_transferencia"])? limpiarCadena($_POST["numero_deposito_transferencia"]):"";  
  
   
switch ($_GET["op"]){    
    case 'guardaryeditar':
        if(isset($_POST['datosArticulosv'])){
            $datosArticulos = json_decode($_POST['datosArticulosv'], true);
        }
        if (empty($idventa)){ 
            $rspta=$venta->insertar($idcliente,$codigo_cliente,$nit,$nombre_cliente,$telefono_cliente,
            $direccion_cliente,$correo_cliente,$tipo_documento_cliente,$idusuario,$idcotizacion,$fecha_hora,
            $forma_pago,$tipo_comprobante,$total_venta,$total_ventades,$cefectivo,$ccredito,$ctarjeta,$ctransferencia,
            $rescambio,$valor_tarjeta,$tipo_pagoBacVisaNet,$opcionesAdicionales,
            $observacion_credito,$datosArticulos,$total_venta_r,$total_ventades_r,
            $tipo_entrega,$numero_pagos,$fecha_hora_pago,$fecha_hora_vencimiento_factura,$monto_abono,
            $idtransporte,$idmensajero,$idvendedor,$descuento_general,$valor_descuentoGeneral,$tipo_cliente,
            $numero_deposito_transferencia);
            echo json_encode($rspta); 
        }
        else {            
        }
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


        if (empty($idventa)){ 
            $rspta=$venta->insertarCobro($idcliente,$codigo_cliente,$nit,
            $nombre_cliente,$telefono_cliente,$direccion_cliente,$correo_cliente,
            $tipo_documento_cliente,$idusuario,$idcotizacion,$fecha_hora,
            $forma_pago,$tipo_comprobante,$total_venta,$total_ventades,
            $cefectivo,$ccredito,$ctarjeta,$ctransferencia,$rescambio,
            $valor_tarjeta,$tipo_pagoBacVisaNet,$opcionesAdicionales,$observacion_credito,
            $idarticulo,$stockinven,$cantidadpresentacion,$cantidad,$totalcantidadpresentacion,
            $presen,$precio_ventaSistema,$precio_ventaSistema2,$q_ref,$precio_venta,
            $precio_recargoPV,$precio_recargoQRef,$descuento_porcentaje,
            $subtotal1,$subtotaldes1,$id_add_orden,$propina,$descripcion_detalle);
            echo json_encode($rspta); 
        }
        else {           
        }
    break;  
 
 
    case 'guardaryeditarnc':
        if(isset($_POST['datosArticulosNC'])){
            $datosArticulos = json_decode($_POST['datosArticulosNC'], true);
        }
            $rspta=$venta->guardaryeditarnc($idventa,$idcliente,$codigo_cliente,$nit,$nombre_cliente,$telefono_cliente,$direccion_cliente,$correo_cliente,$tipo_documento_cliente,
            $idusuario,$idcotizacion,$fecha_hora,$forma_pago,$tipo_comprobante,$total_venta,$total_ventades,$cefectivo,$ccredito,$ctarjeta,$ctransferencia,$rescambio,$valor_tarjeta,$tipo_pagoBacVisaNet,
            $opcionesAdicionales,$observacion_credito,
                $datosArticulos,
                $autorizacionEcoFactura_venta,$serie_comprobante_venta,$numero_ecoFactura_venta,$fecha_hora_nc,$motivo_nc);
            echo json_encode($rspta); 
    break;      
 
    case 'anular':
        $rspta=$venta->anular($idventa);
        echo $rspta ? "Venta anulada" : "Venta no se puede anular";
    break;



    case 'cambiarestadodespacho':
        $rspta=$venta->cambiarestadodespacho($idventa);
        echo $rspta ? "Venta Actualizada" : "Venta no se puede anular";
    break;
 

    case 'cambiarestadodespachoVenta':
        $rspta=$venta->cambiarestadodespachoVenta($idventa);
        echo $rspta ? "Venta Liquidada" : "Venta no se puede Liquidar";
    break;    




    case 'guardarGastoaVenta':
        $rspta=$venta->guardarGastoaVenta($TotalEfectivoDisponible_GastoaVenta,
        $totalAcumuladoGasots_GastoaVenta,$disponibleparaGastos_GastoaVenta,
        $serie_no_GastoaVenta,$factura_no_GastoaVenta,$tipo_factura_GastoaVenta,
        $tipo_comprobante_GastoaVenta,$idcliente_GastoaVenta,$nit_no_GastoaVenta,
        $tipo_documento_cliente_GastoaVenta,$nombreproveedor_GastoaVenta,
        $direccion__GastoaVenta,$concepto_fac_GastoaVenta,$fecha_hora_GastoaVenta,
        $valor_q_GastoaVenta,$tipo_compra_GastoaVenta,$tipo_combustible_GastoaVenta,
        $num_galonaje_GastoaVenta); 
        echo $rspta ? "Gasto de Venta Registrado" : "Gasto de Venta no se puede Registrar";
    break;    
 

    case 'selecttransporte':
        require_once "../modelos/Transporte.php";
        $trans = new Transporte();
 
        $rspta = $trans->select(); 
 
        while ($reg = $rspta->fetch_object())
                { 
                echo '<option value=' . $reg->idtransporte . '>'.$reg->nombre.'</option>';
                }
    break;  


    case 'selecttransporte25':
        require_once "../modelos/Transporte.php";
        $trans = new Transporte();
 
        $rspta = $trans->select(); 
 
        while ($reg = $rspta->fetch_object())
                { 
                echo '<option value=' . $reg->idtransporte . '>'.$reg->nombre.'</option>';
                }
    break;  

 
    case 'listarNC':

        $fecha_inicio_reporte=$_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte=$_REQUEST["fecha_fin_reporte"];

        $rspta=$venta->listarNC($fecha_inicio_reporte,$fecha_fin_reporte);
        //Vamos a declarar un array
        $data= Array();  
  
        while ($reg=$rspta->fetch_object()){ 
 
                if ($reg->tipo_comprobante=='Factura' || $reg->tipo_comprobante=='Cambiaria') {
                    # code... 
                    $url='../reportes/exTicket_Fel_NC.php?id=';
                    $url2='../reportes/exTicket_Fel58mm_NC.php?id=';
                    $url3='../reportes/exVentaFormatoCarta_Fel_NC.php?id=';

                }                 
                else 
                { 
                    $url='../reportes/exTicket_NC.php?id='; 
                    $url2='../reportes/exTicket58mmNC.php?id=';
                    $url3='../reportes/exVentaFormatoCartaNC.php?id=';
                }                                
  

                    # code...  
                    $resventa=$reg->total_venta; 
                    $resventades=$reg->total_ventades;  

                    // Validamos si la fecha de certificación es '0000-00-00 00:00:00'
                $fecha_certificacionNc = ($reg->fechaCertificacion_ecoFactura == '0000-00-00 00:00:00') ? 'No certificado' : $reg->fechaCertificacion_ecoFactura;
                $fecha_certificacionVenta = ($reg->fechaCertificacion_ecoFactura_venta == '0000-00-00 00:00:00') ? 'No certificado' : $reg->fechaCertificacion_ecoFactura_venta;
                                
 
           $data[]=array(
                "0"=>($reg->estado=='Aceptado')?
                    '<a target="_blank" href="'.$url.$reg->idnota_credito.'" title="Ticket 79mm"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url2.$reg->idnota_credito.'"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url3.$reg->idnota_credito.'"  title="Carta"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>':
                    '<a target="_blank" href="'.$url.$reg->idnota_credito.'"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url2.$reg->idnota_credito.'"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url3.$reg->idnota_credito.'"  title="Carta"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>',
                "1"=>$reg->idnota_credito,
                "2"=>$reg->cliente,
                "3"=>$reg->usuarioNc,
                "4"=>$reg->tipo_comprobante,
                "5"=>$reg->num_comprobante,
                "6"=>$resventa, 
                "7"=>$resventades, 
                "8"=>$reg->forma_pago,
                "9"=>$reg->fecha_hora_nc,
                "10"=>$fecha_certificacionNc,
                "11"=>$reg->serie_ecoFactura,
                "12"=>$reg->numero_ecoFactura,
                "13"=>$reg->autorizacionEcoFactura,
                "14"=>$reg->idventa,
                "15"=>$reg->fechaVenta,
                "16"=>$reg->usuarioVenta,               
                "17"=>$fecha_certificacionVenta,                
                "18"=>$reg->serie_comprobante_venta,                
                "19"=>$reg->numero_ecoFactura_venta,                
                "20"=>$reg->autorizacionEcoFactura_venta,                
                "21"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
                '<span class="label bg-red">Anulado</span>'
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;


 
    case 'listar':

        $fecha_inicio_reporte=$_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte=$_REQUEST["fecha_fin_reporte"];

        $rspta=$venta->listar($fecha_inicio_reporte,$fecha_fin_reporte);
        //Vamos a declarar un array
        $data= Array(); 
 
        while ($reg=$rspta->fetch_object()){ 
 
             
                if ($reg->tipo_comprobante=='Factura') {
                    # code... 
                    $url='../reportes/exTicket_Fel.php?id=';
                    $url2='../reportes/exTicket_Fel58mm.php?id=';
                    $url3='../reportes/exVentaFormatoCarta_Fel.php?id=';
                    $url4='../reportes/exVentaBlancoFAC.php?id=';

                }
                elseif ($reg->tipo_comprobante=='Cambiaria') {
                    # code... 
                    $url='../reportes/exTicket_FelFCAM.php?id=';
                    $url2='../reportes/exTicket_Fel_FCAM58mm.php?id=';
                    $url3='../reportes/exVentaFormatoCarta_FelFCAM.php?id=';
                    $url4='../reportes/exVentaBlancoCAM.php?id=';

                }              
                else 
                { 
                    $url='../reportes/exTicket.php?id='; 
                    $url2='../reportes/exTicket58mm.php?id=';
                    $url3='../reportes/exVentaFormatoCarta.php?id=';
                    $url4='../reportes/exVentaBlancoENVIO.php?id=';
                }                                
 
                if ($reg->estado=='Aceptado') {
                    # code... 
                    $resventa=$reg->total_venta; 
                    $resventades=$reg->total_ventades;  
 
                }                 
                else
                { 
                    $resventa=0;
                    $resventades=0;
                }
 
                if ($reg->forma_pago=='Tarjeta') {
                    # code... 
                    $resDatostarjeta=$reg->tipo_pagoBacVisaNet." / ".$reg->opcionesAdicionales." / ".$reg->valor_tarjeta;

                }else{
                    $resDatostarjeta=" ";
                } 

                    // Validamos si la fecha de certificación es '0000-00-00 00:00:00'
                $fecha_certificacion = ($reg->fechaCertificacion_ecoFactura == '0000-00-00 00:00:00') ? 'No certificado' : $reg->fechaCertificacion_ecoFactura;
                                
 
                $numero_trasferencia_deposito = "";
                if ($reg->numero_deposito_transferencia != "0") {
                    $numero_trasferencia_deposito = $reg->numero_deposito_transferencia;
                } else {
                    $numero_trasferencia_deposito = "";
                }

           $data[]=array(
                "0"=>($reg->estado=='Aceptado')?' <button class="btn btn-danger" onclick="anular('.$reg->idventa.')"><i class="fa fa-close"></i></button>' .
                    '<a target="_blank" href="'.$url.$reg->idventa.'" title="Ticket 79mm"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url2.$reg->idventa.'"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url4.$reg->idventa.'"  title="Carta en Blanco"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url3.$reg->idventa.'"  title="Carta Colores"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>':
                    '<a target="_blank" href="'.$url.$reg->idventa.'"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url2.$reg->idventa.'"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url3.$reg->idventa.'"  title="Carta"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>',
                "1"=>$reg->idventa,
                "2"=>$reg->cliente,
                "3"=>$reg->usuario,
                "4"=>$reg->tipo_comprobante,
                "5"=>$reg->num_comprobante,
                "6"=>$resventa, 
                "7"=>$resventades, 
                "8"=>$reg->forma_pago." - ".$resDatostarjeta. " - ".$numero_trasferencia_deposito,
                "9"=>$reg->cefectivo,
                "10"=>$reg->ctarjeta,
                "11"=>$reg->ccredito,
                "12"=>$reg->ctransferencia,
                "13"=>$reg->rescambio,
                "14"=>$reg->fecha,
                "15"=>$fecha_certificacion,
                "16"=>$reg->serie_ecoFactura,
                "17"=>$reg->numero_ecoFactura,
                "18"=>$reg->tipo_entrega,
                "19"=>$reg->nom_venedor,
                "20"=>$reg->idcotizacion,
                "21"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
                '<span class="label bg-red">Anulado</span>'
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;

    case 'listarRestaurante':

        $fecha_inicio_reporte=$_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte=$_REQUEST["fecha_fin_reporte"];

        $rspta=$venta->listarRestaurante($fecha_inicio_reporte,$fecha_fin_reporte);
        //Vamos a declarar un array
        $data= Array(); 
 
        while ($reg=$rspta->fetch_object()){ 
 
              
                if ($reg->tipo_comprobante=='Factura') {
                    # code... 
                    $url='../reportes/exTicket_Fel.php?id=';
                    $url2='../reportes/exTicket_Fel58mm.php?id=';
                    $url3='../reportes/exVentaFormatoCarta_Fel.php?id=';
                    $url4='../reportes/exVentaBlancoFAC.php?id=';

                }
                elseif ($reg->tipo_comprobante=='Cambiaria') {
                    # code... 
                    $url='../reportes/exTicket_FelFCAM.php?id=';
                    $url2='../reportes/exTicket_Fel_FCAM58mm.php?id=';
                    $url3='../reportes/exVentaFormatoCarta_FelFCAM.php?id=';
                    $url4='../reportes/exVentaBlancoCAM.php?id=';

                }              
                else 
                { 
                    $url='../reportes/exTicket.php?id='; 
                    $url2='../reportes/exTicket58mm.php?id=';
                    $url3='../reportes/exVentaFormatoCarta.php?id=';
                    $url4='../reportes/exVentaBlancoENVIO.php?id=';
                }                                
 
                if ($reg->estado=='Aceptado') {
                    # code... 
                    $resventa=$reg->total_venta; 
                    $resventades=$reg->total_ventades;  
 
                }                 
                else
                { 
                    $resventa=0;
                    $resventades=0;
                }
 
                if ($reg->forma_pago=='Tarjeta') {
                    # code... 
                    $resDatostarjeta=$reg->tipo_pagoBacVisaNet." / ".$reg->opcionesAdicionales." / ".$reg->valor_tarjeta;

                }else{
                    $resDatostarjeta=" ";
                } 

                    // Validamos si la fecha de certificación es '0000-00-00 00:00:00'
                $fecha_certificacion = ($reg->fechaCertificacion_ecoFactura == '0000-00-00 00:00:00') ? 'No certificado' : $reg->fechaCertificacion_ecoFactura;
                                
 
           $data[]=array(
                "0"=>($reg->estado=='Aceptado')?' <button class="btn btn-danger" onclick="anular('.$reg->idventa.')"><i class="fa fa-close"></i></button>' .
                    '<a target="_blank" href="'.$url.$reg->idventa.'" title="Ticket 79mm"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url2.$reg->idventa.'"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url4.$reg->idventa.'"  title="Carta en Blanco"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url3.$reg->idventa.'"  title="Carta Colores"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>':
                    '<a target="_blank" href="'.$url.$reg->idventa.'"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url2.$reg->idventa.'"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url3.$reg->idventa.'"  title="Carta"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>',
                "1"=>$reg->nombre_mesa." / ".$reg->id_add_orden,
                "2"=>$reg->idventa,
                "3"=>$reg->cliente,
                "4"=>$reg->usuario,
                "5"=>$reg->usuario_mesero,
                "6"=>$reg->tipo_comprobante,
                "7"=>$reg->num_comprobante,
                "8"=>$resventa, 
                "9"=>$resventades, 
                "10"=>$reg->forma_pago." - ".$resDatostarjeta,
                "11"=>$reg->cefectivo,
                "12"=>$reg->ctarjeta,
                "13"=>$reg->ccredito,
                "14"=>$reg->ctransferencia,
                "15"=>$reg->rescambio,
                "16"=>$reg->fecha,
                "17"=>$fecha_certificacion,
                "18"=>$reg->serie_ecoFactura,
                "19"=>$reg->numero_ecoFactura,
                "20"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
                '<span class="label bg-red">Anulado</span>'
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;

    case 'listar_despacho':  

        $fecha_inicio_reporte=$_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte=$_REQUEST["fecha_fin_reporte"];
        $tipo_entrega=$_REQUEST["tipo_entrega"];

        $rspta=$venta->listar_despacho($fecha_inicio_reporte,$fecha_fin_reporte,$tipo_entrega);
        //Vamos a declarar un array
        $data= Array();  
  
        while ($reg=$rspta->fetch_object()){ 
                $opciones = "";
                if($reg->despachosino == "SI"){
                    $opciones = "Venta Despachada";
                }else{
                    $opciones = '<button class="btn btn-info" onclick="cambiarestado('.$reg->idventa.')"><i class="fa fa-check">Pendiente Despacho</i></button>';
                }
                if ($reg->tipo_comprobante=='Factura') {
                    # code... 
                    $url='../reportes/exTicket_Fel.php?id=';
                    $url2='../reportes/exTicket_Fel58mm.php?id=';
                    $url3='../reportes/exVentaFormatoCarta_Fel.php?id=';

                }
                elseif ($reg->tipo_comprobante=='Cambiaria') {
                    # code... 
                    $url='../reportes/exTicket_FelFCAM.php?id=';
                    $url2='../reportes/exTicket_Fel_FCAM58mm.php?id=';
                    $url3='../reportes/exVentaFormatoCarta_FelFCAM.php?id=';

                }              
                else 
                { 
                    $url='../reportes/exTicket.php?id='; 
                    $url2='../reportes/exTicket58mm.php?id=';
                    $url3='../reportes/exVentaFormatoCarta.php?id=';
                }                                
 
                if ($reg->estado=='Aceptado') {
                    # code... 
                    $resventa=$reg->total_venta; 
                    $resventades=$reg->total_ventades;  
 
                }                 
                else
                { 
                    $resventa=0;
                    $resventades=0;  
                } 
 
                if ($reg->forma_pago=='Tarjeta') {
                    # code... 
                    $resDatostarjeta=$reg->tipo_pagoBacVisaNet." / ".$reg->opcionesAdicionales." / ".$reg->valor_tarjeta;

                }else{
                    $resDatostarjeta=" ";
                } 
                $fecha_certificacion = ($reg->fechaCertificacion_ecoFactura == '0000-00-00 00:00:00') ? 'No certificado' : $reg->fechaCertificacion_ecoFactura;
                   
                if($reg->estado_venta == "COMPLETO"){
                    $estadoVenta = "Venta Liquidada";
                }else{
                    $estadoVenta = '<button class="btn btn-info" onclick="cambiarestadoVenta('.$reg->idventa.',\''.$reg->despachosino.'\')"><i class="fa fa-check"> ENPROCESO</i></button>';
                }                                
 
           $data[]=array(
                "0"=>($reg->estado=='Aceptado')?
                    '<a target="_blank" href="'.$url.$reg->idventa.'" title="Ticket 79mm"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url2.$reg->idventa.'"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url3.$reg->idventa.'"  title="Carta"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>':
                    '<a target="_blank" href="'.$url.$reg->idventa.'"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url2.$reg->idventa.'"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url3.$reg->idventa.'"  title="Carta"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>',
                "1"=>$opciones,
                "2"=>$reg->despachosino,
                "3"=>$reg->idventa,
                "4"=>$reg->tipo_entrega. " ".$reg->comentario_mensajero." ".$reg->guia_transporte,
                "5"=>$reg->cliente,
                "6"=>$reg->usuario,
                "7"=>$reg->tipo_comprobante,
                "8"=>$reg->num_comprobante,
                "9"=>$resventa, 
                "10"=>$resventades, 
                "11"=>$reg->forma_pago." - ".$resDatostarjeta,
                "12"=>$reg->cefectivo,
                "13"=>$reg->ctarjeta,
                "14"=>$reg->ccredito,
                "15"=>$reg->ctransferencia,
                "16"=>$reg->rescambio,
                "17"=>$reg->fecha,
                "18"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
                '<span class="label bg-red">Anulado</span>',
                "19"=>$estadoVenta
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;
  
    case 'selectCliente':
        require_once "../modelos/Persona.php";
        $persona = new Persona();
 
        $rspta = $persona->listarclientes();
 
        while ($reg = $rspta->fetch_object())
                {
                echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '--'.$reg->direccion.'--'.$reg->num_documento.'</option>';
                }
    break;

   /* case 'selectClientep':
        require_once "../modelos/Persona.php";
        $persona = new Persona();
 
        $rspta = $persona->listarp();
 
        while ($reg = $rspta->fetch_object())
                {
                echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '--'.$reg->direccion.'</option>';
                }
    break;    */


 



    case 'buscararticulocodebarCompras':
        require_once "../modelos/Articulo.php";
        $articulo=new Articulo();
        $codigo=$_REQUEST["codigo"];
        $rspta=$articulo->ObtenerProductoBarCode($codigo);
        while ($reg=$rspta->fetch_object()){ 
            echo $reg->idarticulo.'@'.$reg->nombre.'@'.$reg->precio_venta.'@'.$reg->precio_compra.'@'.$reg->stock.'@'.
            $reg->precio_ventaNocturno.'@'.$reg->precio_rango1.'@'.$reg->precio_rango2.'@'.$reg->precio_rango3.'@'.
            $reg->precio_unidad.'@'.$reg->precio_blister.'@'.$reg->precio_caja.'@'.$reg->precio_fardo.'@'.
            $reg->precio_sacos.'@'.$reg->precio_paquete.'@'.$reg->stock_unidad.'@'.$reg->stock_blister.'@'.
            $reg->stock_caja.'@'.$reg->stock_fardo.'@'.$reg->stock_sacos.'@'.$reg->stock_paquete.'@'.
            $reg->precio_rango1_Dos.'@'.$reg->precio_rango2_Dos.'@'.$reg->precio_rango3_Dos.'@'.
            $reg->precio_rango1_Mecanico.'@'.$reg->precio_rango2_MecanicoDos.'@'.$reg->precio_rango3_MecanicoTres.'@'.
            $reg->precio_rango1_Distribuidor.'@'.$reg->precio_rango2_DistribuidorDos.'@'.$reg->precio_rango3_DistribuidorTres.'@'.
            $reg->precio_rango1_Mayorista.'@'.$reg->precio_rango2_MayoristaDos.'@'.$reg->precio_rango3_MayoristaTres;
        }
    break; 
    
    /*function agregarDetalle(idarticulo, articulo, precio_venta, precio_compra, stock, precio_ventaNocturno,
    precio_rango1, precio_rango2, precio_rango3, precio_unidad, precio_blister, precio_caja, precio_fardo, precio_sacos, precio_paquete,
    stock_unidad, stock_blister, stock_caja, stock_fardo, stock_sacos, stock_paquete)*/


    
 
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
            "0" => '<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.str_replace('"', 't.t', $reg->nombre).'\',
                                                                            \''.$reg->precio_venta.'\',
                                                                            \''.$reg->stock.'\',
                                                                            \''.$reg->descuento_porcentaje.'\',\''.$reg->stock_unidad.'\',\''.$reg->precio_unidad.'\',
                                                                            \''.$reg->stock_blister.'\',\''.$reg->precio_blister.'\',\''.$reg->stock_caja.'\',
                                                                            \''.$reg->precio_caja.'\',\''.$reg->stock_fardo.'\',\''.$reg->precio_fardo.'\',\''.$reg->stock_sacos.'\',
                                                                            \''.$reg->precio_sacos.'\',\''.$reg->stock_paquete.'\',\''.$reg->precio_paquete.'\',\''.$reg->precio_rango1.'\',
                                                                            \''.$reg->precio_rango2.'\',\''.$reg->precio_rango3.'\',\''.$reg->precio_compra.'\',\''.$reg->precio_activado.'\',
                                                                            \''.$reg->precio_rango1_Dos.'\',\''.$reg->precio_rango2_Dos.'\',\''.$reg->precio_rango3_Dos.'\',
                                                                            \''.$reg->precio_rango1_Mecanico.'\',\''.$reg->precio_rango2_MecanicoDos.'\',\''.$reg->precio_rango3_MecanicoTres.'\',
                                                                            \''.$reg->precio_rango1_Distribuidor.'\',\''.$reg->precio_rango2_DistribuidorDos.'\',\''.$reg->precio_rango3_DistribuidorTres.'\',
                                                                            \''.$reg->precio_rango1_Mayorista.'\',\''.$reg->precio_rango2_MayoristaDos.'\',
                                                                            \''.$reg->precio_rango3_MayoristaTres.'\'
                                                                            )"><span class="fa fa-plus"> Agregar Item</span></button>',
            "1" => $reg->nombre,
            "2" => $reg->precio_venta,
            "3" => "<img src='" . $imagen . "' height='150px' width='150px'>"
        );
    }
 
    echo json_encode($data);
    break;

   
    case 'buscararticulocodebar':
        require_once "../modelos/Articulo.php";
        $articulo=new Articulo();
        $codigo=$_REQUEST["codigo"];
        $rspta=$articulo->ObtenerProductoBarCode($codigo);
        while ($reg=$rspta->fetch_object()){ 
            echo $reg->idarticulo.'@'.$reg->nombre.'@'.$reg->precio_venta.'@'.
            $reg->stock.'@'.$reg->descuento_porcentaje.'@'.$reg->stock_unidad.'@'.
            $reg->precio_unidad.'@'.$reg->stock_blister.'@'.$reg->precio_blister.'@'.
            $reg->stock_caja.'@'.$reg->precio_caja.'@'.$reg->stock_fardo.'@'.$reg->precio_fardo.'@'.
            $reg->stock_sacos.'@'.$reg->precio_sacos.'@'.$reg->stock_paquete.'@'.$reg->precio_paquete.'@'.
            $reg->precio_rango1.'@'.$reg->precio_rango2.'@'.$reg->precio_rango3.'@'.$reg->precio_compra.'@'.
            $reg->precio_activado.'@'.$reg->precio_rango1_Dos.'@'.$reg->precio_rango2_Dos.'@'.$reg->precio_rango3_Dos.'@'.
            $reg->precio_rango1_Mecanico.'@'.$reg->precio_rango2_MecanicoDos.'@'.$reg->precio_rango3_MecanicoTres.'@'.
            $reg->precio_rango1_Distribuidor.'@'.$reg->precio_rango2_DistribuidorDos.'@'.$reg->precio_rango3_DistribuidorTres.'@'.
            $reg->precio_rango1_Mayorista.'@'.$reg->precio_rango2_MayoristaDos.'@'.$reg->precio_rango3_MayoristaTres;
        }
    break;
    
    case 'listarArticulosVenta':
        require_once "../modelos/Articulo.php";
        $articulo=new Articulo();
        $rspta=$articulo->listarActivosVenta();
        //Vamos a declarar un array
        $data= Array();   
        while ($reg=$rspta->fetch_object()){ 
            //Calcular Stock Fardo y Paquete
            $stock_fardo = "";
            $stock_paquete = "";

            if($reg->stock_fardo > 0){
                $stock_fardo = round($reg->stock / $reg->stock_fardo, 2);
            }else{
                $stock_fardo = "N/A";
            }
            if($reg->stock_paquete > 0){
                $stock_paquete = round($reg->stock / $reg->stock_paquete, 2);
            }else{
                $stock_paquete = "N/A";
            }
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.str_replace('"', 't.t', $reg->nombre).'\',
                                                                            \''.$reg->precio_venta.'\',
                                                                            \''.$reg->stock.'\',
                                                                            \''.$reg->descuento_porcentaje.'\',\''.$reg->stock_unidad.'\',\''.$reg->precio_unidad.'\',
                                                                            \''.$reg->stock_blister.'\',\''.$reg->precio_blister.'\',\''.$reg->stock_caja.'\',
                                                                            \''.$reg->precio_caja.'\',\''.$reg->stock_fardo.'\',\''.$reg->precio_fardo.'\',\''.$reg->stock_sacos.'\',
                                                                            \''.$reg->precio_sacos.'\',\''.$reg->stock_paquete.'\',\''.$reg->precio_paquete.'\',\''.$reg->precio_rango1.'\',
                                                                            \''.$reg->precio_rango2.'\',\''.$reg->precio_rango3.'\',\''.$reg->precio_compra.'\',\''.$reg->precio_activado.'\',
                                                                            \''.$reg->precio_rango1_Dos.'\',\''.$reg->precio_rango2_Dos.'\',\''.$reg->precio_rango3_Dos.'\',
                                                                            \''.$reg->precio_rango1_Mecanico.'\',\''.$reg->precio_rango2_MecanicoDos.'\',\''.$reg->precio_rango3_MecanicoTres.'\',
                                                                            \''.$reg->precio_rango1_Distribuidor.'\',\''.$reg->precio_rango2_DistribuidorDos.'\',\''.$reg->precio_rango3_DistribuidorTres.'\',
                                                                            \''.$reg->precio_rango1_Mayorista.'\',\''.$reg->precio_rango2_MayoristaDos.'\',
                                                                            \''.$reg->precio_rango3_MayoristaTres.'\'
                                                                            )"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->nombre,
                "2"=>$reg->categoria,
                "3"=>$reg->codigo,
                "4"=>$reg->codigo_sku,
                "5"=>$reg->descripcion_2,
                "6"=>$reg->stock,
                "7"=>($reg->stockminimo <=$reg->stock )?'<span class="label bg-green">Stock Normal</span>':
                '<span class="label bg-red">Stock Bajo</span>',
                "8"=>$stock_fardo,
                "9"=>$stock_paquete,
                "10"=>round($reg->precio_venta, 2),
                "11"=>($reg->imagen != "" && file_exists("../files/articulos/".$reg->imagen)) ? 
                    "<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px'>" :
                    "<img src='../files/articulos/nofoto.jpg' height='50px' width='50px'>",
                );
        } 
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
    break;

    case 'listarArticulosVentaKardex':
        require_once "../modelos/Articulo.php";
        $articulo=new Articulo();
        $rspta=$articulo->listarActivosVenta();
        //Vamos a declarar un array
        $data= Array(); 

        while ($reg=$rspta->fetch_object()){ 
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarDetalleKardex(\''.addslashes($reg->codigo).'\')"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->nombre,
                "2"=>$reg->descripcion,
                "3"=>$reg->descripcion_2,
                "4"=>$reg->categoria,
                "5"=>$reg->codigo,
                "6"=>$reg->stock,
                "7"=>($reg->stockminimo <=$reg->stock )?'<span class="label bg-green">Stock Normal</span>':
                '<span class="label bg-red">Stock Bajo</span>',
                "8"=>$reg->precio_venta,
                "9"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >"
                );
        } 
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
    break;    





    case 'listarArticulosVenta2':
        require_once "../modelos/Articulo.php";
        $articulo=new Articulo();
        $rspta=$articulo->listarActivosVenta();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){ 
            $data[]=array(
                "value"=>$reg->nombre,
                "label"=>$reg->nombre,
                "desc"=>$reg->nombre, 
                "icon"=>$reg->imagen,
                "idarticulo"=>$reg->idarticulo,
                "nombre"=>$reg->nombre,
                "precio_venta"=>$reg->precio_venta,
                "descuento_porcentaje"=>$reg->descuento_porcentaje,
                "stock"=>$reg->stock,
                "img"=>$reg->imagen,
                "stock_unidad"=>$reg->stock_unidad,
                "precio_unidad"=>$reg->precio_unidad,
                "stock_blister"=>$reg->stock_blister,
                "precio_blister"=>$reg->precio_blister,
                "stock_caja"=>$reg->stock_caja,
                "precio_caja"=>$reg->precio_caja,
                "stock_fardo"=>$reg->stock_fardo,
                "precio_fardo"=>$reg->precio_fardo,
                "stock_sacos"=>$reg->stock_sacos,
                "precio_sacos"=>$reg->precio_sacos,
                "stock_paquete"=>$reg->stock_paquete,
                "precio_paquete"=>$reg->precio_paquete,
                "precio_rango1"=>$reg->precio_rango1,
                "precio_rango2"=>$reg->precio_rango2,
                "precio_rango3"=>$reg->precio_rango3,
                "precio_compra"=>$reg->precio_compra,
                "precio_activado"=>$reg->precio_activado
                );
        }
        $results = $data;
        echo json_encode($results);
    break;



    case "selectMensajero":
        require_once "../modelos/Mensajero.php";
        $mensajero = new Mensajero();
 
        $rspta = $mensajero->select();
 
        while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idmensajero . '>' . $reg->nombre . ' -- ' . $reg->telefono . '</option>';
                }
    break;

    case 'listarArticulosExtras':
        $idarticulo=$_REQUEST["idarticulo"];
        require_once "../modelos/Articulo.php";
        $articulo=new Articulo();
        $rspta=$articulo->listarArticulosExtras($idarticulo);
        //Vamos a declarar un array
        $data= Array();  
        while ($reg=$rspta->fetch_object()){ 
            $data[]=array(
                "0"=>$reg->articulo_extra,
                "1"=>$reg->idarticulo_extra,
                "2"=>$reg->idproducto,
                "3"=>$reg->cantidad_extra,
                "4"=>$reg->tipo_item,
                "5"=>$reg->precio_venta
                );
        } 
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
    break;


    case 'listarArticulosExtrasYSeleccionados':
        $idarticulo = isset($_POST["idarticulo"]) ? limpiarCadena($_POST["idarticulo"]) : "";
        $idcotizacion = isset($_POST["idcotizacion"]) ? limpiarCadena($_POST["idcotizacion"]) : "";

        $rsptaArticulos = $venta->listarArticulosExtras($idarticulo); 

        $dataArticulos = [];
        while ($reg = $rsptaArticulos->fetch_row()) {
            $dataArticulos[] = $reg; 
        }
        $articulosDisponiblesJSON = ["aaData" => $dataArticulos];


        $rsptaExtrasSeleccionados = $venta->listarExtrasSeleccionados($idcotizacion); 

        $idsSeleccionados = [];
        while ($reg = $rsptaExtrasSeleccionados->fetch_object()) {
            $idsSeleccionados[] = $reg->idarticulo;
        }

        $respuesta = [
            "articulosDisponibles" => $articulosDisponiblesJSON, 
            "extrasSeleccionados" => $idsSeleccionados
        ];

        header('Content-Type: application/json');
        echo json_encode($respuesta);
    break;

}
?> 