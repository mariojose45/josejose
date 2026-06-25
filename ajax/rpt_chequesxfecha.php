
<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Rpt_depositosxfecha.php";
 
$consulta=new Consultas(); 
 
 
switch ($_GET["op"]){


    case 'depositosxfecha':
        $fecha_inicio=$_REQUEST["fecha_inicio"]; 
        $fecha_fin=$_REQUEST["fecha_fin"];
 
        $rspta=$consulta->depositosxfecha($fecha_inicio,$fecha_fin);
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){ 
            $data[]=array(  
                "0"=>$reg->fecha,
                "1"=>$reg->usuario,
                "2"=>$reg->cliente,
                "3"=>$reg->tipo_banco,
                "4"=>$reg->cta_nombre,
                "5"=>$reg->num_cta,
                "6"=>$reg->deposito_no,
                "7"=>$reg->valor_deposito,
                "8"=>$reg->descripcion, 
                "9"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
                '<span class="label bg-red">Desactivado</span>'
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