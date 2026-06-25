<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Cotejacionvetas_guias.php";
 
$cotejamiento=new Cotijamiento();
 
$idguias_excel=isset($_POST["idguias_excel"])? limpiarCadena($_POST["idguias_excel"]):"";
$idguias_excel2=isset($_POST["idguias_excel2"])? limpiarCadena($_POST["idguias_excel2"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$total_liquidacion=isset($_POST["total_liquidacion"])? limpiarCadena($_POST["total_liquidacion"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

$idventa=isset($_POST["idventa"])==true?$_POST["idventa"]:"";
$estado_venta=isset($_POST["estado_venta"])==true?$_POST["estado_venta"]:"";
$fechaventa=isset($_POST["fechaventa"])==true?$_POST["fechaventa"]:"";
$total_venta=isset($_POST["total_venta"])==true?$_POST["total_venta"]:""; 
$guia_transporte=isset($_POST["guia_transporte"])==true?$_POST["guia_transporte"]:"";
$iddetalle_guias_excel=isset($_POST["iddetalle_guias_excel"])==true?$_POST["iddetalle_guias_excel"]:"";
$idguia=isset($_POST["idguia"])==true?$_POST["idguia"]:"";
$mventa=isset($_POST["mventa"])==true?$_POST["mventa"]:"";
$comision=isset($_POST["comision"])==true?$_POST["comision"]:"";
$vcomision=isset($_POST["vcomision"])==true?$_POST["vcomision"]:""; 
$mliquido=isset($_POST["mliquido"])==true?$_POST["mliquido"]:"";
$autorizacion=isset($_POST["autorizacion"])==true?$_POST["autorizacion"]:"";
$ctabanco=isset($_POST["ctabanco"])==true?$_POST["ctabanco"]:"";
$vflete=isset($_POST["vflete"])==true?$_POST["vflete"]:"";
$idtransporte=isset($_POST["idtransporte"])==true?$_POST["idtransporte"]:"";
$subtotal=isset($_POST["subtotal"])==true?$_POST["subtotal"]:"";
$idusuario=$_SESSION["idusuario"]; 
  
switch ($_GET["op"]){ 
    case 'guardaryeditar':  
            $rspta=$cotejamiento->insertar($idguias_excel,$idguias_excel2,$fecha_hora,$total_liquidacion,
                $descripcion,$idventa,$fechaventa,$total_venta,$guia_transporte,$iddetalle_guias_excel,
                $idguia,$mventa,$comision,$vcomision,$mliquido,$autorizacion,$ctabanco,$vflete,
                $idusuario,$idtransporte,$subtotal,$estado_venta);
            echo $rspta;
        
    break; 
 
  
 
    case 'mostrar':
        $rspta=$cotejamiento->mostrar($idguias_excel); 
        //Codificar el resultado utilizando json
        echo json_encode($rspta);  
    break;  
 
    case 'mostrardetalle':   
        $cotiz=$cotejamiento->mostrardetalle($idguias_excel);
        echo json_encode($cotiz);
    break;      
 
    case 'listar':
        $fecha_inicio=$_REQUEST["fecha_inicio"];
        $fecha_fin=$_REQUEST["fecha_fin"];     
        $rspta=$cotejamiento->listar($fecha_inicio,$fecha_fin);
        //Vamos a declarar un array
        $url='../reportes/exCotejacionventas.php?id='; 
        $data= Array();  
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<a target="_blank" href="'.$url.$reg->idcotejamientoventas_guias.'"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>',
                "1"=>$reg->idcotejamientoventas_guias,
                "2"=>$reg->fecha,
                "3"=>$reg->total_liquidacion,
                "4"=>$reg->descripcion,
                "5"=>$reg->nombre_usuario
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;

    case 'listarVentasparcotijamientoxfecha': 
 
        $fecha_inicio=$_REQUEST["fecha_inicio"];   
        $fecha_fin=$_REQUEST["fecha_fin"];  

        $rspta=$cotejamiento->listarVentasparcotijamientoxfecha($fecha_inicio,$fecha_fin);
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){ 
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarDetalle2('.$reg->idventa.',\''.$reg->fecha.'\',\''.$reg->total_venta.'\',\''.$reg->transportes.'\',\''.$reg->correlativo_trasporte.'\',\''.$reg->total_venta2.'\')"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->idventa,
                "2"=>$reg->fecha,
                "3"=>$reg->transportes.' #'.$reg->correlativo_trasporte,
                "4"=>$reg->guia_transporte,
                "5"=>$reg->telefono_entregaproductos,
                "6"=>$reg->usuario
                );
        } 
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
    break;    
}
?>