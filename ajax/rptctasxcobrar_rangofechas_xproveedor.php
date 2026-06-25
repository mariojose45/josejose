
<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Rptctasxcobrar_rangofechas_xproveedor.php";
 
$rptCtasxcobrarxcliente=new RptCtasxcobrarxcliente(); 
 
 
switch ($_GET["op"]){
 
    case 'rptctaxpagarxcliente':
        $fecha_inicio=$_REQUEST["fecha_inicio"];
        $fecha_fin=$_REQUEST["fecha_fin"];
        $idcliente=$_REQUEST["idcliente"];
 
        $rspta=$rptCtasxcobrarxcliente->rptctaxcobrarxcliente($fecha_inicio,$fecha_fin,$idcliente);
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->idcta_cobrar,
                "1"=>$reg->idventa,
                "2"=>$reg->usuario,
                "3"=>$reg->cliente,
                "4"=>$reg->total_venta,
                "5"=>$reg->total_abono,
                "6"=>$reg->saldo_venta,
                "7"=>$reg->tipo_pago,
                "8"=>$reg->numero_boleta,
                "9"=>$reg->recibo_caja_numero,
                "10"=>$reg->descripcion,
                "11"=>$reg->fechapago,
                "12"=>($reg->condicion=='1')?'<span class="label bg-green">Aceptado</span>':
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