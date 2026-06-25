<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/ConsultasIngresoEmpleado.php";
 
$consulta=new Consultas();   
 
 
switch ($_GET["op"]){
    case 'comprasfecha':
        $fecha_inicio=$_REQUEST["fecha_inicio"];
        $fecha_fin=$_REQUEST["fecha_fin"];
 
        $rspta=$consulta->comprasfecha($fecha_inicio,$fecha_fin);
        //Vamos a declarar un array
        $data= Array(); 
 
        while ($reg=$rspta->fetch_object()){  
            $data[]=array(
                "0"=>$reg->fecha,
                "1"=>($reg->accion=='0')?'<span class="label bg-green">Entrada</span>':
                '<span class="label bg-red">Salida</span>',
                "2"=>$reg->codigo,
                "3"=>"<img src='../apiregistro/".$reg->foto."' height='50px' width='50px' >"
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