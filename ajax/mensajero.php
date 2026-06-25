<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Mensajero.php";
 
$mensajero=new Mensajero();
 
$idmensajero=isset($_POST["idmensajero"])? limpiarCadena($_POST["idmensajero"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$idusuario=isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idmensajero)){
            $rspta=$mensajero->insertar($nombre,$descripcion,$telefono,$email,$idusuario);
            echo $rspta ? "Mensajero registrada" : "Mensajero no se pudo registrar";
        }
        else {
            $rspta=$mensajero->editar($idmensajero,$nombre,$descripcion,$telefono,$email,$idusuario);
            echo $rspta ? "Mensajero actualizada" : "Mensajero no se pudo actualizar";
        }
    break;

    case 'guardaryeditar2':
        if (empty($idmensajero)){
            $rspta=$mensajero->insertar2($nombre,$descripcion,$telefono,$email);
            echo $rspta;
        }
    break;
 
    case 'desactivar':
        $rspta=$mensajero->desactivar($idmensajero);
        echo $rspta ? "Mensajero Desactivada" : "Mensajero no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$mensajero->activar($idmensajero);
        echo $rspta ? "Mensajero activada" : "Mensajero no se puede activar";
    break;  
 
    case 'mostrar':
        $rspta=$mensajero->mostrar($idmensajero);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar':
        $rspta=$mensajero->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idmensajero.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idmensajero.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idmensajero.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idmensajero.')"><i class="fa fa-check"></i></button>',
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

    case 'selectMensajero':
        $rspta=$mensajero->listar(); 
        $dbdata = array();
        //Fetch into associative array
        while ( $row = $rspta->fetch_assoc())  {
            $dbdata[]=$row;
        }
        echo json_encode($dbdata);
    break; 
}
?>