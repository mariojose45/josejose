
<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Rpt_cuentaxfecha.php";
 
$consulta=new Consultas(); 
 
 
switch ($_GET["op"]){


    case 'cuentasxfecha':
        $fecha_inicio=$_REQUEST["fecha_inicio"]; 
        $fecha_fin=$_REQUEST["fecha_fin"];
        $idcuenta=$_REQUEST["idcuenta"];
 
        $rspta=$consulta->cuentasxfecha($fecha_inicio,$fecha_fin,$idcuenta);
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){  
            $data[]=array(   
                "0"=>$reg->fecha,
                "1"=>$reg->cta_nombre,
                "2"=>$reg->num_cta,
                "3"=>$reg->saldo_inicial,
                "4"=>$reg->valor_deposito,
                "5"=>$reg->valor_cheque,
                "6"=>$reg->saldo_final_cuenta,
                "7"=>$reg->fecha_deposito,
                "8"=>$reg->feche_cheque,
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