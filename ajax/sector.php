<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Sector.php";
 
$categoria=new Sector();
 
$idsector=isset($_POST["idsector"])? limpiarCadena($_POST["idsector"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idsector)){
            $rspta=$categoria->insertar($nombre,$descripcion);
            echo $rspta ? "Sector registrada" : "Sector no se pudo registrar";
        }
        else {
            $rspta=$categoria->editar($idsector,$nombre,$descripcion);
            echo $rspta ? "Sector actualizada" : "Sector no se pudo actualizar";
        }
    break;
 
    case 'desactivar':
        $rspta=$categoria->desactivar($idsector);
        echo $rspta ? "Sector Desactivada" : "Sector no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$categoria->activar($idsector);
        echo $rspta ? "Sector activada" : "Sector no se puede activar";
        break;
    break;
 
    case 'mostrar':
        $rspta=$categoria->mostrar($idsector);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;
 
    case 'listar':
        $rspta=$categoria->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idsector.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idsector.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idsector.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idsector.')"><i class="fa fa-check"></i></button>',
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