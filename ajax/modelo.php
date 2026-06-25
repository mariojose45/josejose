<?php 
require_once "../modelos/Modelo.php";
 
$modelo=new Modelos();
 
$idmodelo=isset($_POST["idmodelo"])? limpiarCadena($_POST["idmodelo"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

$nombre_modelo=isset($_POST["nombre_modelo"])? limpiarCadena($_POST["nombre_modelo"]):"";
$descripcion_modelo=isset($_POST["descripcion_modelo"])? limpiarCadena($_POST["descripcion_modelo"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idmodelo)){
            $rspta=$modelo->insertar($nombre,$descripcion);
            echo $rspta ? "Modelo registrado" : "Modelo no se pudo registrar";
        }
        else {
            $rspta=$modelo->editar($idmodelo,$nombre,$descripcion);
            echo $rspta ? "Modelo actualizado" : "Modelo no se pudo actualizar";
        }
    break;


    case 'guardaryeditarModal':
            $rspta=$modelo->guardaryeditarModal($nombre_modelo,$descripcion_modelo);
            echo $rspta;

    break;    
   
    case 'desactivar':
        $rspta=$modelo->desactivar($idmodelo);
        echo $rspta ? "Modelo Desactivado" : "Modelo no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$modelo->activar($idmodelo);
        echo $rspta ? "Modelo activado" : "Modelo no se puede activar";
        break;
    break;
  
    case 'mostrar':
        $rspta=$modelo->mostrar($idmodelo);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar':
        $rspta=$modelo->listar();
        //Vamos a declarar un array
        $data= Array(); 
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idmodelo.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idmodelo.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idmodelo.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idmodelo.')"><i class="fa fa-check"></i></button>',
                "1"=>$reg->idmodelo,
                "2"=>$reg->nombre,
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
 
    case 'selectModelo':   
        $rspta = $modelo->listar();
 
        while ($reg = $rspta->fetch_object())
                {
                echo '<option value=' . $reg->idmodelo . '>' . $reg->nombre . '</option>';
                }
    break;

}
?>