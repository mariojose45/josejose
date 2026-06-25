<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Sub_Categoria.php";
 
$categoria=new SubCategoria();
 
$idsubcategoria=isset($_POST["idsubcategoria"])? limpiarCadena($_POST["idsubcategoria"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
  
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idsubcategoria)){
            $rspta=$categoria->insertar($nombre,$descripcion);
            echo $rspta ? "Sub Categoría registrada" : "Sub Categoría no se pudo registrar";
        }
        else {
            $rspta=$categoria->editar($idsubcategoria,$nombre,$descripcion);
            echo $rspta ? "Sub Categoría actualizada" : "Sub Categoría no se pudo actualizar";
        }
    break;
 
    case 'desactivar':
        $rspta=$categoria->desactivar($idsubcategoria);
        echo $rspta ? "Sub Categoría Desactivada" : "Sub Categoría no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$categoria->activar($idsubcategoria);
        echo $rspta ? "Sub Categoría activada" : "Sub Categoría no se puede activar";
        break;
    break;
 
    case 'mostrar':
        $rspta=$categoria->mostrar($idsubcategoria);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar':
        $rspta=$categoria->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idsubcategoria.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idsubcategoria.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idsubcategoria.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idsubcategoria.')"><i class="fa fa-check"></i></button>',
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


    case 'listarSubCategoria':
 
        $rspta=$categoria->listar();
        //Vamos a declarar un array
        $data= Array(); 
 
        while ($reg=$rspta->fetch_object()){    
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="event.preventDefault(); agregarDetalle('.$reg->idsubcategoria.',\''.$reg->nombre.'\',\''.$reg->descripcion.'\')" type="button"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->nombre,
                "2"=>$reg->descripcion
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