<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Accesorios.php";
  
$accesorios=new Accesorios();
  
$idaccesorios=isset($_POST["idaccesorios"])? limpiarCadena($_POST["idaccesorios"]):"";
$idmarca=isset($_POST["idmarca"])? limpiarCadena($_POST["idmarca"]):"";
$idlinea=isset($_POST["idlinea"])? limpiarCadena($_POST["idlinea"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idaccesorios)){
            $rspta=$accesorios->insertar($idmarca,$idlinea,$_POST["codaccesorio"],$_POST["descripcion_accesorio"]);
            echo $rspta ? "Accesorios registrados" : "Accesorios no se pudo registrar";
        }
        else {
            $rspta=$accesorios->editar($idaccesorios,$nombre,$descripcion);
            echo $rspta ? "Accesorios actualizados" : "Accesorios no se pudo actualizar";
        }
    break;
 
    case 'desactivar':
        $rspta=$accesorios->desactivar($idaccesorios);
        echo $rspta ? "Categoría Desactivada" : "Categoría no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$accesorios->activar($idaccesorios);
        echo $rspta ? "Categoría activada" : "Categoría no se puede activar";
        break;
    break;
 
    case 'mostrar':
        $rspta=$accesorios->mostrar($idaccesorios);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar':
        $rspta=$accesorios->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idaccesorios.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idaccesorios.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idaccesorios.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idaccesorios.')"><i class="fa fa-check"></i></button>',
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

    case "selectMarca":
        $rspta = $accesorios->selectMarca();
        while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idmarca . '>' . $reg->codigo . '-' . $reg->nombre . '</option>';
                }
    break; 
    case "selectLinea":
        $rspta = $accesorios->selectLinea();
        while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idlinea . '>' . $reg->nombre . '</option>';
                }
    break;         
}
?>