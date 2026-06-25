<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Asociar_sub_categoria.php";
 
$AsociarSubCate=new AsociarSubCategoria();
 
$idasociar_subcategoria=isset($_POST["idasociar_subcategoria"])? limpiarCadena($_POST["idasociar_subcategoria"]):"";
$idcategoria=isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
$idsubcategoria=isset($_POST["idsubcategoria"])==true?$_POST["idsubcategoria"]:"";
date_default_timezone_set('America/Guatemala');
$fechaHora = date('Y-m-d H:i:s'); 
  
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idasociar_subcategoria)){
            $rspta=$AsociarSubCate->insertar($idcategoria,$idsubcategoria,$fechaHora);
            echo $rspta ? "Asociación registrada" : "Asociación  no se pudo registrar";
        }
        else {
            $rspta=$AsociarSubCate->editar($idasociar_subcategoria,$idcategoria,$idsubcategoria,$fechaHora);
            echo $rspta ? "Asociación Actualizada" : "Asociación  no se pudo Actualizar";
        }
    break;

    case 'mostrar':
        $rspta=$AsociarSubCate->mostrar($idasociar_subcategoria);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'obtenerDetalle':
        $cotiz=$AsociarSubCate->obtenerDetalle($idasociar_subcategoria);
        echo json_encode($cotiz);
    break;   
    
    case 'desactivar':
        $rspta=$AsociarSubCate->desactivar($idasociar_subcategoria);
        echo $rspta ? "Asociación Desactivada" : "Asociación no se puede desactivar";
    break;
 
    case 'activar':
        $rspta=$AsociarSubCate->activar($idasociar_subcategoria);
        echo $rspta ? "Asociación activada" : "Asociación no se puede activar";
    break;    
 
    case 'listar':
        $rspta=$AsociarSubCate->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion=='1')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idasociar_subcategoria.')"><i class="fa fa-eye"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idasociar_subcategoria.')"><i class="fa fa-close"></i></button>':
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idasociar_subcategoria.')"><i class="fa fa-check"></i></button>',
                "1"=>$reg->nombre_categoria,
                "2"=>$reg->user_creacion,
                "3"=>$reg->nombre_sucursal,
                "4"=>$reg->fecha_creacion,
                "5"=>$reg->user_modificacion,
                "6"=>$reg->fecha_modificacion,
                "7"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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