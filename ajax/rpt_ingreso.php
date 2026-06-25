<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Rpt_ingreso.php";
 
$rptingreso=new RptIngreso();
 
switch ($_GET["op"]){
    case 'rptingresoxfechas':
        $fecha_inicio=$_REQUEST["fecha_inicio"];
        $fecha_fin=$_REQUEST["fecha_fin"];
 
        $rspta=$rptingreso->rptingresoxfechas($fecha_inicio,$fecha_fin);
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->idingreso,
                "1"=>$reg->proveedor,
                "2"=>$reg->usuario,
                "3"=>$reg->tipo_comprobante.' '.$reg->serie_comprobante.' '.$reg->num_comprobante,
                "4"=>$reg->fechahora, 
                "5"=>$reg->total_compra,
                "6"=>$reg->forma_pago,
                "7"=>$reg->dias_credito,
                "8"=>$reg->fechahorapagocredito,
                "9"=>$reg->tipo_pago,
                "10"=>$reg->valor_pagar,
                "11"=>$reg->no_cheque,
                "12"=>$reg->fechahorageneracionpago,
                "13"=>$reg->tipo_banco,
                "14"=>$reg->numero_boleta,
                "15"=>$reg->usuarui_modificacion,
                "16"=>$reg->fecha_modificacion,
                "17"=>$reg->motivo_modificacion,
                "18"=>$reg->estado
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