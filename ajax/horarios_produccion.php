<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Horarios_produccion.php";
 
$hora_produccion=new Hora_produccion();
 
$idhora_produccion=isset($_POST["idhora_produccion"])? limpiarCadena($_POST["idhora_produccion"]):"";
$hora=isset($_POST["hora"])? limpiarCadena($_POST["hora"]):"";
$hora2=isset($_POST["hora2"])? limpiarCadena($_POST["hora2"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idhora_produccion)){
            $rspta=$hora_produccion->insertar($hora,$hora2,$descripcion);
            echo $rspta ? "Hora registrada" : "Hora no se pudo registrar";
        }
        else {
            $rspta=$hora_produccion->editar($idhora_produccion,$hora,$hora2,$descripcion);
            echo $rspta ? "Hora actualizada" : "Hora no se pudo actualizar";
        }
    break;
 
    case 'desactivar':
        $rspta=$hora_produccion->desactivar($idhora_produccion);
        echo $rspta ? "Hora Desactivada" : "Categoría no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$hora_produccion->activar($idhora_produccion);
        echo $rspta ? "Categoría activada" : "Categoría no se puede activar";
        break;
    break;
 
    case 'mostrar':
        $rspta=$hora_produccion->mostrar($idhora_produccion);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar':
        $rspta=$hora_produccion->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idhora_produccion.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idhora_produccion.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idhora_produccion.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idhora_produccion.')"><i class="fa fa-check"></i></button>',
                "1"=>$reg->hora,
                "2"=>$reg->hora2,                
                "3"=>$reg->descripcion,
                "4"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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