<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Linea.php";
 
$linea=new Linea();
 
$idlinea=isset($_POST["idlinea"])? limpiarCadena($_POST["idlinea"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idlinea)){
            $rspta=$linea->insertar($nombre,$descripcion);
            echo $rspta ? "Linea registrada" : "Linea no se pudo registrar";
        }
        else {
            $rspta=$linea->editar($idlinea,$nombre,$descripcion);
            echo $rspta ? "Linea actualizada" : "Linea no se pudo actualizar";
        }
    break;
 
    case 'desactivar':
        $rspta=$linea->desactivar($idlinea);
        echo $rspta ? "Linea Desactivada" : "Linea no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$linea->activar($idlinea);
        echo $rspta ? "Linea activada" : "Linea no se puede activar";
        break;
    break;
 
    case 'mostrar':
        $rspta=$linea->mostrar($idlinea);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar':
        $rspta=$linea->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idlinea.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idlinea.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idlinea.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idlinea.')"><i class="fa fa-check"></i></button>',
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