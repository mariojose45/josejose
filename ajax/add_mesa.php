<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Mesa.php";
 
$mesas=new Mesas();
 
$idmesa=isset($_POST["idmesa"])? limpiarCadena($_POST["idmesa"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idmesa)){
            $rspta=$mesas->insertar($nombre,$descripcion);
            echo $rspta ? "Mesa registrada" : "Mesa no se pudo registrar";
        }
        else {
            $rspta=$mesas->editar($idmesa,$nombre,$descripcion);
            echo $rspta ? "Mesa actualizada" : "Mesa no se pudo actualizar";
        }
    break;
 
    case 'desactivar':
        $rspta=$mesas->desactivar($idmesa);
        echo $rspta ? "Mesa Desactivada" : "Mesa no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$mesas->activar($idmesa);
        echo $rspta ? "Mesa activada" : "Mesa no se puede activar";
        break;
    break;
 
    case 'mostrar':
        $rspta=$mesas->mostrar($idmesa);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar':
        $rspta=$mesas->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idmesa.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idmesa.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idmesa.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idmesa.')"><i class="fa fa-check"></i></button>',
                "1"=>$reg->nombre,
                "2"=>$reg->descripcion,
                "3"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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