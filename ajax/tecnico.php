<?php 
require_once "../modelos/Tecnico.php";
 
$tecnicos=new Tecnicos(); 
 
$idtecnico=isset($_POST["idtecnico"])? limpiarCadena($_POST["idtecnico"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$comision=isset($_POST["comision"])? limpiarCadena($_POST["comision"]):"";

$nombre_tecnico=isset($_POST["nombre_tecnico"])? limpiarCadena($_POST["nombre_tecnico"]):"";
$descripcion_tecnico=isset($_POST["descripcion_tecnico"])? limpiarCadena($_POST["descripcion_tecnico"]):"";
$comision_tecnico=isset($_POST["comision_tecnico"])? limpiarCadena($_POST["comision_tecnico"]):"";

 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idtecnico)){
            $rspta=$tecnicos->insertar($nombre,$descripcion,$comision);
            echo $rspta ? "Tecnico registrado" : "Tecnico no se pudo registrar";
        }
        else {
            $rspta=$tecnicos->editar($idtecnico,$nombre,$descripcion,$comision);
            echo $rspta ? "Tecnico actualizado" : "Tecnico no se pudo actualizar";
        }
    break;

    case 'guardaryeditarModal':

            $rspta=$tecnicos->guardaryeditarModal($nombre_tecnico,$descripcion_tecnico,$comision_tecnico);
            echo $rspta;
        

    break;  

    case 'selectTeccnico':
        $rspta = $tecnicos->selectTeccnico();

        while ($reg = $rspta->fetch_object())
                {
                echo '<option     value=' . $reg->idtecnico . '>' . $reg->nombre . '</option>';
                }
    break;       
   
    case 'desactivar': 
        $rspta=$tecnicos->desactivar($idtecnico);
        echo $rspta ? "Tecnico Desactivado" : "Tecnico no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$tecnicos->activar($idtecnico);
        echo $rspta ? "Tecnico activado" : "Tecnico no se puede activar";
        break;
    break;
  
    case 'mostrar':
        $rspta=$tecnicos->mostrar($idtecnico);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar':
        $rspta=$tecnicos->listar();
        //Vamos a declarar un array
        $data= Array(); 
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idtecnico.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idtecnico.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idtecnico.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idtecnico.')"><i class="fa fa-check"></i></button>',
                "1"=>$reg->idtecnico,
                "2"=>$reg->nombre,
                "3"=>$reg->descripcion,
                "4"=>$reg->comision,
                "5"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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