<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Correlativos.php";
 
$corre=new Correla();
 
$idcorrelativo=isset($_POST["idcorrelativo"])? limpiarCadena($_POST["idcorrelativo"]):"";
$num_cotizacion=isset($_POST["num_cotizacion"])? limpiarCadena($_POST["num_cotizacion"]):"";
$num_envio=isset($_POST["num_envio"])? limpiarCadena($_POST["num_envio"]):"";
$idsucursal=isset($_POST["idsucursal"])? limpiarCadena($_POST["idsucursal"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idcorrelativo)){
            $rspta=$corre->insertar($num_cotizacion,$num_envio,$idsucursal);
            echo $rspta ? "Correlativo registrad0" : "Categoría no se pudo registrar";
        }
        else {
            $rspta=$corre->editar($idcorrelativo,$num_cotizacion,$num_envio,$idsucursal);
            echo $rspta ? "Categoría actualizada" : "Categoría no se pudo actualizar";
        }
    break;

    case 'mostrar':
        $rspta=$corre->mostrar($idcorrelativo);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
   
    break;
 
    case 'listar':
        $rspta=$corre->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idcorrelativo.')"><i class="fa fa-pencil"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idcorrelativo.')"><i class="fa fa-pencil"></i></button>',
                "1"=>$reg->num_cotizacion,
                "2"=>$reg->num_envio,
                "3"=>$reg->sucursal_su,
                "4"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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