<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Transporte.php";
 
$transporte=new Transporte();
 
$idtransporte=isset($_POST["idtransporte"])? limpiarCadena($_POST["idtransporte"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$placa=isset($_POST["placa"])? limpiarCadena($_POST["placa"]):"";
$modelo=isset($_POST["modelo"])? limpiarCadena($_POST["modelo"]):"";
$marca=isset($_POST["marca"])? limpiarCadena($_POST["marca"]):"";
$color=isset($_POST["color"])? limpiarCadena($_POST["color"]):"";
$tipo_transporte=isset($_POST["tipo_transporte"])? limpiarCadena($_POST["tipo_transporte"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$capacidad=isset($_POST["capacidad"])? limpiarCadena($_POST["capacidad"]):"";

  
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idtransporte)){
            $rspta=$transporte->insertar($nombre,$descripcion,$telefono,$email,$placa,$modelo,$marca,$color,$tipo_transporte,$capacidad);
            echo $rspta ? "Transporte registrada" : "Transporte no se pudo registrar";
        }
        else {
            $rspta=$transporte->editar($idtransporte,$nombre,$descripcion,$telefono,$email,$placa,$modelo,$marca,$color,$tipo_transporte,$capacidad);
            echo $rspta ? "Transporte actualizada" : "Transporte no se pudo actualizar";
        }
    break;

    case 'guardaryeditar2':
        if (empty($idtransporte)){
            $rspta=$transporte->insertar2($nombre,$descripcion,$telefono,$email,$placa,$modelo,$marca,$color,$tipo_transporte,$capacidad);
            echo $rspta;
        }
    break;
 
    case 'desactivar':
        $rspta=$transporte->desactivar($idtransporte);
        echo $rspta ? "Transporte Desactivada" : "Transporte no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$transporte->activar($idtransporte);
        echo $rspta ? "Transporte activada" : "Transporte no se puede activar";
    break;  
 
    case 'mostrar':
        $rspta=$transporte->mostrar($idtransporte);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar':
        $rspta=$transporte->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idtransporte.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idtransporte.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idtransporte.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idtransporte.')"><i class="fa fa-check"></i></button>',
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

    case 'selectTransporte':
        $rspta=$transporte->listar(); 
        $dbdata = array();
        //Fetch into associative array
        while ( $row = $rspta->fetch_assoc())  {
            $dbdata[]=$row;
        }
        echo json_encode($dbdata);
    break; 
}
?>