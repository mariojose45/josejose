
<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Rptctasxpagar_rangofechas_xproveedor.php";
 
$rptCtasxpagarxcliente=new RptCtasxpagarxcliente(); 
 
 
switch ($_GET["op"]){
 
    case 'rptctaxpagarxcliente':
        $fecha_inicio=$_REQUEST["fecha_inicio"];
        $fecha_fin=$_REQUEST["fecha_fin"];
        $idcliente=$_REQUEST["idcliente"];
 
        $rspta=$rptCtasxpagarxcliente->rptctaxpagarxcliente($fecha_inicio,$fecha_fin,$idcliente);
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->idcta_pagar,
                "1"=>$reg->idingreso,
                "2"=>$reg->usuario,
                "3"=>$reg->proveedor,
                "4"=>$reg->idcuenta,
                "5"=>$reg->total_compra,
                "6"=>$reg->valor_pagar,
                "7"=>$reg->saldo_ingreso,
                "8"=>$reg->tipo_pago,
                "9"=>$reg->tipo_banco,
                "10"=>$reg->numero_boleta,
                "11"=>$reg->recibo_caja_numero,
                "12"=>$reg->no_cheque,
                "13"=>$reg->fecha_hora_generacion_pago,
                "14"=>$reg->desp_cheque,
                "15"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
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