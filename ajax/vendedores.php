<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Vendedores.php";
 
$vendedores=new Vendedores();
 
$idvendedor=isset($_POST["idvendedor"])? limpiarCadena($_POST["idvendedor"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idvendedor)){
            $rspta=$vendedores->insertar($nombre,$descripcion,$telefono,$email);
            echo $rspta ? "Vendedor registrada" : "Vendedor no se pudo registrar";
        }
        else {
            $rspta=$vendedores->editar($idvendedor,$nombre,$descripcion,$telefono,$email);
            echo $rspta ? "Vendedor actualizada" : "Vendedor no se pudo actualizar";
        }
    break;
 
    case 'desactivar':
        $rspta=$vendedores->desactivar($idvendedor);
        echo $rspta ? "Vendedor Desactivada" : "Vendedor no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$vendedores->activar($idvendedor);
        echo $rspta ? "Vendedor activada" : "Vendedor no se puede activar";
    break;  
 
    case 'mostrar':
        $rspta=$vendedores->mostrar($idvendedor);
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
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idvendedor.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idvendedor.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idvendedor.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idvendedor.')"><i class="fa fa-check"></i></button>',
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