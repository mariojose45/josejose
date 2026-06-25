<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Avance_produccion.php";
 
$avance_produccion=new Avance_produccion();
 
$idavance_produccion=isset($_POST["idavance_produccion"])? limpiarCadena($_POST["idavance_produccion"]):"";
$idficha_empleado=isset($_POST["idficha_empleado"])? limpiarCadena($_POST["idficha_empleado"]):"";
$idcategoria=isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
$idarticulo=isset($_POST["idarticulo"])? limpiarCadena($_POST["idarticulo"]):"";
$idarticulos_proceso=isset($_POST["idarticulos_proceso"])? limpiarCadena($_POST["idarticulos_proceso"]):"";
$idhora_produccion=isset($_POST["idhora_produccion"])? limpiarCadena($_POST["idhora_produccion"]):"";
$cantidad=isset($_POST["cantidad"])? limpiarCadena($_POST["cantidad"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idavance_produccion)){
            $rspta=$avance_produccion->insertar($idficha_empleado,$idcategoria,$idarticulo,$idarticulos_proceso,$idhora_produccion,$cantidad,$descripcion,$fecha_hora);
            echo $rspta ? "Avance a produccion  registrado" : "Avance a produccion no se pudo registrar";
        }
        else {
            $rspta=$avance_produccion->editar($idficha_empleado,$idcategoria,$idarticulo);
            echo $rspta ? "Categoría actualizada" : "Categoría no se pudo actualizar";
        }
    break;
 
 
    case 'listar':
        $rspta=$avance_produccion->listar(); 
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->fecha,
                "1"=>$reg->empleado,
                "2"=>$reg->categoria,                
                "3"=>$reg->articulo,
                "4"=>$reg->proceso,
                "5"=>$reg->hora,
                "6"=>$reg->cantidad,
                "7"=>$reg->descripcion,
                "8"=>($reg->condicion)?'<span class="label bg-green">Ingresado</span>':
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

    case 'selectcategoria':
        $rspta=$avance_produccion->selectcategoria(); 
        $dbdata = array();
        //Fetch into associative array
        while ( $row = $rspta->fetch_assoc())  {
            $dbdata[]=$row;
        }
        echo json_encode($dbdata);
    break;

    case 'selectarticulo':
        $rspta=$avance_produccion->selectarticulo($_POST["categoria"]); 
        $dbdata = array();
        //Fetch into associative array
        while ( $row = $rspta->fetch_assoc())  {
            $dbdata[]=$row;
        }
        echo json_encode($dbdata);
    break;

    case 'selectavanceproduccion':
        $rspta=$avance_produccion->selectavanceproduccion($_POST["producto"]);
        $dbdata = array();
        //Fetch into associative array
        while ( $row = $rspta->fetch_assoc())  {
            $dbdata[]=$row;
        }
        echo json_encode($dbdata); 
    break;  



    case "selectfichaempleado":
        require_once "../modelos/Ficha_empleado.php";
        $fichaempleado = new Fichaempleado();
 
        $rspta = $fichaempleado->selectfichaempleado();
 
        while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idficha_empleado . '>' . $reg->nombre . '</option>';
                }
    break;  

    case "selecthora":
        require_once "../modelos/Horarios_produccion.php";
        $horaproduccion = new Hora_produccion();
 
        $rspta = $horaproduccion->selecthora();
 
        while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idhora_produccion . '>' . $reg->hora . '--' . $reg->hora2 . '</option>';
                }
    break;        

}
?>