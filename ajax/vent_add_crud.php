<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Vent_add_crud.php";
 
$ventaadmin=new VentaAdmin();
 
$idventa=isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idusuario=isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$serie_comprobante=isset($_POST["serie_comprobante"])? limpiarCadena($_POST["serie_comprobante"]):"";
$num_comprobante=isset($_POST["num_comprobante"])? limpiarCadena($_POST["num_comprobante"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";
$total_ventades=isset($_POST["total_ventades"])? limpiarCadena($_POST["total_ventades"]):"";
$estado=isset($_POST["estado"])? limpiarCadena($_POST["estado"]):"";
$condicion=isset($_POST["condicion"])? limpiarCadena($_POST["condicion"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idventa)){
            $rspta=$ventaadmin->insertar();
            echo $rspta ? "Venta registrada" : "Venta no se pudo registrar";
        }
        else {
            $rspta=$ventaadmin->editar($idventa,$idcliente,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$total_venta,$total_ventades,$estado,$condicion);
            echo $rspta ? "Venta actualizada" : "Venta no se pudo actualizar";
        }
    break;
 

 
    case 'mostrar':
        $rspta=$ventaadmin->mostrar($idventa);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar':
        $rspta=$ventaadmin->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-pencil"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-pencil"></i></button>',
                "1"=>$reg->idventa,
                "2"=>$reg->fecha_hora,
                "3"=>$reg->serie_comprobante.$reg->num_comprobante,
                "4"=>$reg->total_venta
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