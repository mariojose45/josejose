<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Articulos_procesos.php";
 
$articulos_procesos=new Articulos_procesos();
 
$idarticulos_proceso=isset($_POST["idarticulos_proceso"])? limpiarCadena($_POST["idarticulos_proceso"]):"";
$idarticulo=isset($_POST["idarticulo"])? limpiarCadena($_POST["idarticulo"]):"";
$proceso=isset($_POST["proceso"])? limpiarCadena($_POST["proceso"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idarticulos_proceso)){
            $rspta=$articulos_procesos->insertar($idarticulo,$proceso,$descripcion);
            echo $rspta ? "Creacion de articulos a procesos registrada" : "Creacion de articulos a procesos no se pudo registrar";
        }
        else {
            $rspta=$articulos_procesos->editar($idarticulos_proceso,$idarticulo,$proceso,$descripcion);
            echo $rspta ? "Creacion de articulos a procesos actualizada" : "Creacion de articulos a procesos no se pudo actualizar";
        }
    break;
 
    case 'desactivar':
        $rspta=$articulos_procesos->desactivar($idarticulos_proceso);
        echo $rspta ? "Creacion de articulos a procesos Desactivada" : "Creacion de articulos a procesos no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$articulos_procesos->activar($idarticulos_proceso);
        echo $rspta ? "Creacion de articulos a procesos activada" : "Creacion de articulos a procesos no se puede activar";
        break;
    break;
 
    case 'mostrar':
        $rspta=$articulos_procesos->mostrar($idarticulos_proceso);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar':
        $rspta=$articulos_procesos->listar(); 
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idarticulos_proceso.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idarticulos_proceso.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idarticulos_proceso.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idarticulos_proceso.')"><i class="fa fa-check"></i></button>',
                "1"=>$reg->articulo,
                "2"=>$reg->proceso,                
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

    case "selectproduccion":
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
 
        $rspta = $articulo->selectproduccion();
  
        while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idarticulo . '>' . $reg->nombre . '</option>';
                }
    break;

}
?>