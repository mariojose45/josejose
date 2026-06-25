
<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Ventas_mensajero.php"; 
 
$ventas_mensajero=new Ventas_mensajero(); 

////ventas x lotes
$idventa_lote=isset($_POST["idventa_lote"])==true?$_POST["idventa_lote"]:"";
$idcliente_lote=isset($_POST["idcliente_lote"])==true?$_POST["idcliente_lote"]:"";
$total_venta_lote=isset($_POST["total_venta_lote"])==true?$_POST["total_venta_lote"]:"";
$tipo_pago_lote=isset($_POST["tipo_pago_lote"])==true?$_POST["tipo_pago_lote"]:"";
$fechapago_lote=isset($_POST["fechapago_lote"])==true?$_POST["fechapago_lote"]:"";
$tipo_banco_lote=isset($_POST["tipo_banco_lote"])==true?$_POST["tipo_banco_lote"]:"";
$numero_boleta_lote=isset($_POST["numero_boleta_lote"])==true?$_POST["numero_boleta_lote"]:"";
$recibo_caja_numero_lote=isset($_POST["recibo_caja_numero_lote"])==true?$_POST["recibo_caja_numero_lote"]:"";
$descripcion_lote=isset($_POST["descripcion_lote"])==true?$_POST["descripcion_lote"]:"";

$idventa_Mensajero=isset($_POST["idventa_Mensajero"])? limpiarCadena($_POST["idventa_Mensajero"]):"";
$comentario_mensajero=isset($_POST["comentario_mensajero"])? limpiarCadena($_POST["comentario_mensajero"]):"";



$idventa_MensajeroTransporte=isset($_POST["idventa_MensajeroTransporte"])? limpiarCadena($_POST["idventa_MensajeroTransporte"]):"";
$comentario_mensajero_transporte=isset($_POST["comentario_mensajero_transporte"])? limpiarCadena($_POST["comentario_mensajero_transporte"]):"";
$guia_transporte=isset($_POST["guia_transporte"])? limpiarCadena($_POST["guia_transporte"]):"";
  
switch ($_GET["op"]){ 
 
    case 'guardaryeditarComentariosMensajero':    
        $rspta=$ventas_mensajero->guardaryeditarComentariosMensajero($idventa_Mensajero,$comentario_mensajero);
        //echo $rspta ? "Venta Completada" : "Venta  no se pudo registrar";
        echo $rspta; 
    break;


    case 'guardaryeditarComentariosMensajeroTransporte':    
        $rspta=$ventas_mensajero->guardaryeditarComentariosMensajeroTransporte($idventa_MensajeroTransporte,$comentario_mensajero_transporte,$guia_transporte);
        //echo $rspta ? "Venta Completada" : "Venta  no se pudo registrar";
        echo $rspta; 
    break;


    case 'guardaryeditarxlote':   
        $rspta=$ventas_mensajero->guardaryeditarxlote($idventa_lote,$idcliente_lote,$total_venta_lote,$tipo_pago_lote,$fechapago_lote,$tipo_banco_lote,$numero_boleta_lote,
        $recibo_caja_numero_lote,$descripcion_lote);
        //echo $rspta ? "Venta Completada" : "Venta  no se pudo registrar";
        echo $rspta;
    break;

    case 'MostraragregarComentarioMensajero':
        $idventa=$_REQUEST["idventa"]; 
        $rspta=$ventas_mensajero->MostraragregarComentarioMensajero($idventa);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;    

    case 'listarFacturasMensajero':   

        $fecha_inicio=$_REQUEST["fecha_inicio"]; 
        $fecha_fin=$_REQUEST["fecha_fin"]; 
 
        $rspta=$ventas_mensajero->listarFacturasMensajero($fecha_inicio,$fecha_fin);
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
                $url5='../reportes/rptImpresionMesanjero.php?id=';
  
            $data[]=array(
                 "0"=>'<a target="_blank" href="'.$url.$reg->idventa.'" title="Ticket 79mm"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url2.$reg->idventa.'"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url4.$reg->idventa.'"  title="Carta en Blanco"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url3.$reg->idventa.'"  title="Carta Colores"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url5.$reg->idventa.'"  title="Impresion Mensajero"><button class="btn btn-danger"><i class="fa fa-automobile"></i> </button> </a>'.
                    // Verificamos el tipo de entrega para determinar qué función llamar
                    ($reg->tipo_entrega == 'Mensajero' 
                        ? '<button class="btn btn-warning" onclick="agregarComentarioMensajero('.$reg->idventa.')"><span class="fa fa-plus"></span></button>'
                        : '<button class="btn btn-warning" onclick="agregarComentarioMensajeroTransporte('.$reg->idventa.',\''.$reg->guia_transporte.'\')"><span class="fa fa-plus"></span></button>'
                    ),                
                "1"=>$reg->tipo_entrega,                    
                "2"=>$reg->idventa,                    
                "3"=>$reg->nombre_cliente,
                "4"=>$reg->tipo_comprobante, 
                "5"=>$reg->num_comprobante,
                "6"=>$reg->fecha,  
                "7"=>$reg->total_venta, 
                "8"=>$reg->total_abono,
                "9"=>$reg->saldo_venta,
                "10"=>($reg->estado_venta=='ENPROCESO')?'<span class="label bg-red">Pendiente Pago</span>':
                '<span class="label bg-green">Pago Aplicado</span>',
                "11"=>$reg->comentario_mensajero." ".$reg->guia_transporte
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results); 
 
    break;


    case 'listarFacturasMensajeroxusuario':   

 
        $rspta=$ventas_mensajero->listarFacturasMensajeroxusuario();
        //Vamos a declarar un array
        $data= Array();   
   
        while ($reg=$rspta->fetch_object()){   
 
                $url5='../reportes/rptImpresionMesanjero.php?id=';
  
            $data[]=array(
                /*"0"=>'<button class="btn btn-warning btn-block" onclick="agregarDetalle('.$reg->idventa.',\''.$reg->idcliente.'\',\''.$reg->nombre_cliente.'\',\''.$reg->tipo_comprobante.'\',\''.$reg->numero_ecoFactura.'\',\''.$reg->fecha.'\',\''.$reg->total_venta.'\',\''.$reg->total_abono.'\',\''.$reg->saldo_venta.'\')"><span class="fa fa-plus"></span></button>', */
                    
                    '<a target="_blank" href="'.$url5.$reg->idventa.'"  title="Impresion Mensajero"><button class="btn btn-danger"><i class="fa fa-automobile"></i> </button> </a>'.
                    '<button class="btn btn-warning" onclick="agregarCheklistMensajero('.$reg->idventa.')"><span class="fa fa-plus"></span></button>',                
                "1"=>$reg->idventa,                    
                "2"=>$reg->nombre_cliente,
                "3"=>$reg->fecha
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results); 
 
    break;    

    /*case 'listarFacturasMensajero': 
        $rspta=$ventas_mensajero->listarFacturasMensajero();
        //Vamos a declarar un array
        $data= Array();  
 
        while ($reg=$rspta->fetch_object()){    
            $data[]=array(
                "0"=>$reg->idventa,   
                "1"=>$reg->nombre_cliente,                    
                "2"=>$reg->tipo_comprobante,
                "3"=>$reg->total_venta, 
                "4"=>$reg->numero_ecoFactura,
                "5"=>$reg->fecha, 
                "6"=>($reg->estado_venta=='ENPROCESO')?'<span class="label bg-red">Pendiente Pago</span>':
                '<span class="label bg-green">Pago Aplicado</span>'
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results); 
 
    break;*/
}
?>