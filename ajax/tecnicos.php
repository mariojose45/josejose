<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Tecnicos.php";
 
$vendedores=new Tecnicos();
 
$idtecnico=isset($_POST["idtecnico"])? limpiarCadena($_POST["idtecnico"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idtecnico)){
            $rspta=$vendedores->insertar($nombre,$descripcion,$telefono,$email);
            echo $rspta ? "Tecnico registrada" : "Tecnico no se pudo registrar";
        }
        else {
            $rspta=$vendedores->editar($idtecnico,$nombre,$descripcion,$telefono,$email);
            echo $rspta ? "Tecnico actualizada" : "Tecnico no se pudo actualizar";
        }
    break;
 
    case 'desactivar':
        $rspta=$vendedores->desactivar($idtecnico);
        echo $rspta ? "Tecnico Desactivada" : "Tecnico no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$vendedores->activar($idtecnico);
        echo $rspta ? "Tecnico activada" : "Tecnico no se puede activar";
    break;  
 
    case 'mostrar':
        $rspta=$vendedores->mostrar($idtecnico);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar':
        $rspta=$vendedores->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idtecnico.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idtecnico.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idtecnico.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idtecnico.')"><i class="fa fa-check"></i></button>',
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

    case 'selectVendedor':
        $rspta=$vendedores->listar(); 
        $dbdata = array();
        //Fetch into associative array
        while ( $row = $rspta->fetch_assoc())  {
            $dbdata[]=$row;
        }
        echo json_encode($dbdata);
    break; 
}
?>