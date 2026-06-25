<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Cobradores.php";
 
$vendedores=new Cobradores();
 
$idcobradores=isset($_POST["idcobradores"])? limpiarCadena($_POST["idcobradores"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idcobradores)){
            $rspta=$vendedores->insertar($nombre,$descripcion,$telefono,$email);
            echo $rspta ? "Cobrador registrada" : "Cobrador no se pudo registrar";
        }
        else {
            $rspta=$vendedores->editar($idcobradores,$nombre,$descripcion,$telefono,$email);
            echo $rspta ? "Cobrador actualizada" : "Cobrador no se pudo actualizar";
        }
    break;
 
    case 'desactivar':
        $rspta=$vendedores->desactivar($idcobradores);
        echo $rspta ? "Cobrador Desactivada" : "Cobrador no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$vendedores->activar($idcobradores);
        echo $rspta ? "Cobrador activada" : "Cobrador no se puede activar";
    break;  
 
    case 'mostrar':
        $rspta=$vendedores->mostrar($idcobradores);
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
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idcobradores.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idcobradores.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idcobradores.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idcobradores.')"><i class="fa fa-check"></i></button>',
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