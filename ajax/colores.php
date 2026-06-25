<?php 
require_once "../modelos/Colores.php";
 
$color=new Colores();
 
$idcolor=isset($_POST["idcolor"])? limpiarCadena($_POST["idcolor"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";


$nombre_color=isset($_POST["nombre_color"])? limpiarCadena($_POST["nombre_color"]):"";
$descripcion_color=isset($_POST["descripcion_color"])? limpiarCadena($_POST["descripcion_color"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idcolor)){
            $rspta=$color->insertar($nombre,$descripcion);
            echo $rspta ? "Color registrado" : "Color no se pudo registrar";
        }
        else {
            $rspta=$color->editar($idcolor,$nombre,$descripcion);
            echo $rspta ? "Color actualizado" : "Color no se pudo actualizar";
        }
    break;

    case 'guardaryeditarModal':
            $rspta=$color->guardaryeditarModal($nombre_color,$descripcion_color);
            echo $rspta;
        
    break;     
   
    case 'desactivar':
        $rspta=$color->desactivar($idcolor);
        echo $rspta ? "Color Desactivado" : "Color no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$color->activar($idcolor);
        echo $rspta ? "Color activado" : "Color no se puede activar";
        break;
    break;
  
    case 'mostrar':
        $rspta=$color->mostrar($idcolor);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar':
        $rspta=$color->listar();
        //Vamos a declarar un array 
        $data= Array();  
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idcolor.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idcolor.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idcolor.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idcolor.')"><i class="fa fa-check"></i></button>',
                "1"=>$reg->idcolor,
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

    case 'selectColores':
        $rspta = $color->listar();
 
        while ($reg = $rspta->fetch_object())
                {
                echo '<option value=' . $reg->idcolor . '>' . $reg->nombre . '</option>';
                }
    break;

}
?>