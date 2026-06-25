<?php 
require_once "../modelos/Tipo_equipo.php";
 
$tipoequipo=new TipoEquipo();
 
$idtipo_equipo=isset($_POST["idtipo_equipo"])? limpiarCadena($_POST["idtipo_equipo"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

$nombre_tipoequipo=isset($_POST["nombre_tipoequipo"])? limpiarCadena($_POST["nombre_tipoequipo"]):"";
$descripcion_tipoequipo=isset($_POST["descripcion_tipoequipo"])? limpiarCadena($_POST["descripcion_tipoequipo"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idtipo_equipo)){
            $rspta=$tipoequipo->insertar($nombre,$descripcion);
            echo $rspta ? "Tipo Modelo registrado" : "Tipo Modelo no se pudo registrar";
        }
        else {
            $rspta=$tipoequipo->editar($idtipo_equipo,$nombre,$descripcion);
            echo $rspta ? "Tipo Modelo actualizado" : "Tipo Modelo no se pudo actualizar";
        }
    break;

    case 'guardaryeditarModal':
            $rspta=$tipoequipo->guardaryeditarModal($nombre_tipoequipo,$descripcion_tipoequipo);
            echo $rspta;
        
    break;    
   
    case 'desactivar':
        $rspta=$tipoequipo->desactivar($idtipo_equipo);
        echo $rspta ? "Tipo Modelo Desactivado" : "Tipo Modelo no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$tipoequipo->activar($idtipo_equipo);
        echo $rspta ? "Tipo Modelo activado" : "Tipo Modelo no se puede activar";
        break;
    break;
  
    case 'mostrar':
        $rspta=$tipoequipo->mostrar($idtipo_equipo);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar':
        $rspta=$tipoequipo->listar();
        //Vamos a declarar un array
        $data= Array(); 
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idtipo_equipo.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idtipo_equipo.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idtipo_equipo.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idtipo_equipo.')"><i class="fa fa-check"></i></button>',
                "1"=>$reg->idtipo_equipo,
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

    case 'selectTipoequipo':
        $rspta = $tipoequipo->listar();
 
        while ($reg = $rspta->fetch_object())
                {
                echo '<option value=' . $reg->idtipo_equipo . '>' . $reg->nombre . '</option>';
                }
    break;

}
?>