<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Marca_vehiculo.php";
 
$marca_vehiculo=new Marca_vehiculo();
 
$idmarca=isset($_POST["idmarca"])? limpiarCadena($_POST["idmarca"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idmarca)){
            $rspta=$marca_vehiculo->insertar($nombre,$descripcion);
            echo $rspta ? "Marca Vehiculo registrada" : "Marca Vehiculo no se pudo registrar";
        }
        else {
            $rspta=$marca_vehiculo->editar($idmarca,$nombre,$descripcion);
            echo $rspta ? "Marca Vehiculo actualizada" : "Marca Vehiculo no se pudo actualizar";
        }
    break;
 
    case 'desactivar':
        $rspta=$marca_vehiculo->desactivar($idmarca);
        echo $rspta ? "Marca Vehiculo Desactivada" : "Marca Vehiculo no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$marca_vehiculo->activar($idmarca);
        echo $rspta ? "Marca Vehiculo activada" : "Marca Vehiculo no se puede activar";
        break;
    break;
 
    case 'mostrar':
        $rspta=$marca_vehiculo->mostrar($idmarca);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;
 
    case 'listar':
        $rspta=$marca_vehiculo->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idmarca.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idmarca.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idmarca.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idmarca.')"><i class="fa fa-check"></i></button>',
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