
<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Ventasxfecha_Detalle.php";
 
$ventasxfechadetalle=new VentasxfechaDetalle(); 
 
 
switch ($_GET["op"]){
    case 'ventasdetalle':
        $fecha_inicio=$_REQUEST["fecha_inicio"];
        $fecha_fin=$_REQUEST["fecha_fin"];
        $idsucursal=$_REQUEST["idsucursal"];
 
        $rspta=$ventasxfechadetalle->ventasdetalle($fecha_inicio,$fecha_fin,$idsucursal);
        //Vamos a declarar un array
        $data= Array();
  
        while ($reg=$rspta->fetch_object()){  
            $data[]=array( 
                "0"=>$reg->fecha,
                "1"=>$reg->idventa,
                "2"=>$reg->num_comprobante,
                "3"=>$reg->codigo,
                "4"=>$reg->cantidad,
                "5"=>$reg->articulo,  
                "6"=>$reg->precio_venta,
                "7"=>$reg->subtotaldes1,
                "8"=>$reg->subtotal1,               
                "9"=>$reg->valor_tarjeta,
                "10"=>$reg->producto_consignacion,
                "11"=>$reg->aplica_impuestos,
                "12"=>$reg->estado                     
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