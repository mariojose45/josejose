<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Marca.php";
 
$marca=new Marca();
 
$idmarca=isset($_POST["idmarca"])? limpiarCadena($_POST["idmarca"]):"";
$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$idusuario=$_SESSION["idusuario"];


$codigo_marca=isset($_POST["codigo_marca"])? limpiarCadena($_POST["codigo_marca"]):"";
$nombre_marca=isset($_POST["nombre_marca"])? limpiarCadena($_POST["nombre_marca"]):"";
$descripcion_marca=isset($_POST["descripcion_marca"])? limpiarCadena($_POST["descripcion_marca"]):"";
  
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idmarca)){
            $rspta=$marca->insertar($codigo,$nombre,$descripcion,$idusuario);
            echo $rspta ? "Marca registrada" : "Marca no se pudo registrar";
        }
        else {
            $rspta=$marca->editar($idmarca,$codigo,$nombre,$descripcion,$idusuario);
            echo $rspta ? "Marca actualizada" : "Marca no se pudo actualizar";
        }
    break;

    case 'guardaryeditarModal':

        $rspta=$marca->guardaryeditarModal($codigo_marca,$nombre_marca,$descripcion_marca,$idusuario);
        echo $rspta;
    break;     
 
    case 'desactivar':
        $rspta=$marca->desactivar($idmarca);
        echo $rspta ? "Marca Desactivada" : "Marca no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$marca->activar($idmarca);
        echo $rspta ? "Marca activada" : "Marca no se puede activar";
        break;
    break;
 
    case 'mostrar':
        $rspta=$marca->mostrar($idmarca);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar':
        $rspta=$marca->listar();
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

    case "selectMarca":
        $rspta = $marca->selectMarca();
        while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idmarca . '>' . $reg->nombre . '</option>';
                }
    break;
}
?>