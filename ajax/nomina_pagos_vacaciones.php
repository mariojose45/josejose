<?php 
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Nomina_pagos_vacaciones.php";
 
$vacaciones=new nominapagosvacaciones();
 
$idvacacion=isset($_POST["idvacacion"])? limpiarCadena($_POST["idvacacion"]):"";
$idempleado=isset($_POST["idempleado"])? limpiarCadena($_POST["idempleado"]):"";
$fecha_solicitud=isset($_POST["fecha_solicitud"])? limpiarCadena($_POST["fecha_solicitud"]):"";
$fecha_inicio=isset($_POST["fecha_inicio"])? limpiarCadena($_POST["fecha_inicio"]):"";
$fecha_fin=isset($_POST["fecha_fin"])? limpiarCadena($_POST["fecha_fin"]):"";
$dias_solicitados=isset($_POST["dias_solicitados"])? limpiarCadena($_POST["dias_solicitados"]):"";
$motivo=isset($_POST["motivo"])? limpiarCadena($_POST["motivo"]):"";

 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idvacacion)){
            $rspta=$vacaciones->insertar($idempleado,$fecha_solicitud,$fecha_inicio,$fecha_fin,$dias_solicitados,$motivo);
            echo $rspta?"Vacaciones registradas" : "Vacaciones no se pudo registrar";
        } else {
            $rspta=$vacaciones->editar($idvacacion,$idempleado,$fecha_solicitud,$fecha_inicio,$fecha_fin,$dias_solicitados,$motivo);
            echo $rspta?"Vacaciones actualizadas" : "Vacaciones no se pudo actualizar";            
        }
    break;
 
    case 'desactivar':
        $rspta=$vacaciones->desactivar($idvacacion);
        echo $rspta ? "Vacaciones Desactivadas" : "Vacaciones no se puede desactivar";
        break;
    break;
 
    case 'mostrar':
        $rspta=$vacaciones->mostrar($idvacacion);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;
 
    case 'listar':
        $rspta=$vacaciones->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $url='../reportes/exNominapagosVacaciones.php?id=';
            $url1='../reportes/exNominapagosComprobanteVacaciones.php?id=';            
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idvacacion.')"><i class="fa fa-pencil"></i></button>'.
                ' <button class="btn btn-danger" onclick="desactivar('.$reg->idvacacion.')"><i class="fa fa-close"></i></button>'.
                '<a target="_blank" title="Detalle Vacaciones" href="'.$url.$reg->idempleado.'"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                '<a target="_blank" title="Comprobante de Pagos" href="'.$url1.$reg->idvacacion.'"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>':
                '<a target="_blank" title="Detalle Vacaciones" href="'.$url.$reg->idempleado.'"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                '<a target="_blank" title="Comprobante de Pagos" href="'.$url1.$reg->idvacacion.'"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->idvacacion,
                "2" => $reg->empleado,
                "3" => $reg->fecha_inicio." al ".$reg->fecha_fin,
                "4" => $reg->dias_solicitados,
                "5" => $reg->estado
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;

    case 'infoVacaciones':
        echo json_encode($vacaciones->infoVacaciones($_POST["idempleado"]));
    break;    
}
?>