<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';

require_once "../modelos/Ruta_visita.php";
 
$ruta = new Ruta_visita();
 
$idruta = isset($_POST["idruta"]) ? limpiarCadena($_POST["idruta"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
 
switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idruta)) {
            $rspta = $ruta->insertar($nombre, $descripcion);
            echo $rspta ? "Ruta registrada" : "Ruta no se pudo registrar";
        } else {
            $rspta = $ruta->editar($idruta, $nombre, $descripcion);
            echo $rspta ? "Ruta actualizada" : "Ruta no se pudo actualizar";
        }
    break;
 
    case 'desactivar':
        $rspta = $ruta->desactivar($idruta);
        echo $rspta ? "Ruta Desactivada" : "Ruta no se puede desactivar";
    break;
 
    case 'activar':
        $rspta = $ruta->activar($idruta);
        echo $rspta ? "Ruta activada" : "Ruta no se puede activar";
    break;
 
    case 'mostrar':
        $rspta = $ruta->mostrar($idruta);
        echo json_encode($rspta);
    break;
 
    case 'listar':
        $rspta = $ruta->listar();
        $data = Array();
 
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => ($reg->condicion) ? '<button class="btn btn-warning" onclick="mostrar('.$reg->idruta.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idruta.')"><i class="fa fa-close"></i></button>' :
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idruta.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idruta.')"><i class="fa fa-check"></i></button>',
                "1" => $reg->nombre,
                "2" => $reg->descripcion,
                "3" => ($reg->condicion) ? '<span class="label bg-green">Activado</span>' :
                '<span class="label bg-red">Desactivado</span>'
            );
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
    break;
}
?>
