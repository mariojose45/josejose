<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Cheque.php";
 
$cheque=new Cheque(); 
 
$idcheque=isset($_POST["idcheque"])? limpiarCadena($_POST["idcheque"]):"";
$idusuario=$_SESSION["idusuario"];
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idcuenta=isset($_POST["idcuenta"])? limpiarCadena($_POST["idcuenta"]):"";
$fac_serie=isset($_POST["fac_serie"])? limpiarCadena($_POST["fac_serie"]):"";
$fac_documento=isset($_POST["fac_documento"])? limpiarCadena($_POST["fac_documento"]):"";
$fecha_hora_factura=isset($_POST["fecha_hora_factura"])? limpiarCadena($_POST["fecha_hora_factura"]):"";
$fecha_hora_operacion=isset($_POST["fecha_hora_operacion"])? limpiarCadena($_POST["fecha_hora_operacion"]):"";
$valor_cheque=isset($_POST["valor_cheque"])? limpiarCadena($_POST["valor_cheque"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$saldo_cuenta=isset($_POST["saldo_cuenta"])? limpiarCadena($_POST["saldo_cuenta"]):"";


  
switch ($_GET["op"]){   
    case 'guardaryeditar':
        if (empty($idcheque)){
            $rspta=$cheque->insertar($idusuario,$idcliente,$idcuenta,$fac_serie,$fac_documento,$fecha_hora_factura,$fecha_hora_operacion,$valor_cheque,$descripcion,$saldo_cuenta);
            echo $rspta ? "Cheque registrado" : "Cheque no se pudo registrar";
        }
        else {
            $rspta=$cheque->editar($idcheque);
            echo $rspta ? "Deposito actualizado" : "Deposito no se pudo actualizar"; 
        }
    break;
 
    case 'desactivar':
        $rspta=$cheque->desactivar($idcheque);
        echo $rspta ? "Deposito Desactivado" : "Deposito no se puede desactivar";
        break;
    break;
 


 
    case 'listar':
        $rspta=$cheque->listar();
        //Vamos a declarar un arrayiddeposito
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){

            $url='../reportes/exCheque2.php?id='; 
            $data[]=array(
                "0"=>(($reg->condicion=='1')?'<button class="btn btn-danger" onclick="desactivar('.$reg->idcheque.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-danger" ><i class="fa fa-close"></i></button>').
                '<a target="_blank" href="'.$url.$reg->idcheque.'"><button class="btn btn-info"><i class="fa fa-file"></i> </button> </a>',
                "1"=>$reg->usuario,
                "2"=>$reg->cliente,
                "3"=>$reg->cta_nombre,
                "4"=>$reg->fecha_factura,
                "5"=>$reg->fecha_operacion,
                "6"=>$reg->valor_cheque,
                "7"=>$reg->descripcion,
                "8"=>($reg->condicion=='1')?'<span class="label bg-green">Activado</span>':
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



}
?>