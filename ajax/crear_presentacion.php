<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Crear_presentacion.php";
 
$crear_presentacion=new Crearpresentacion();
 
$idpresentacion=isset($_POST["idpresentacion"])? limpiarCadena($_POST["idpresentacion"]):"";
$nombre_presentacion1=isset($_POST["nombre_presentacion1"])? limpiarCadena($_POST["nombre_presentacion1"]):"";
$nombre_presentacion2=isset($_POST["nombre_presentacion2"])? limpiarCadena($_POST["nombre_presentacion2"]):"";
$nombre_presentacion3=isset($_POST["nombre_presentacion3"])? limpiarCadena($_POST["nombre_presentacion3"]):"";
$nombre_presentacion4=isset($_POST["nombre_presentacion4"])? limpiarCadena($_POST["nombre_presentacion4"]):"";
$nombre_presentacion5=isset($_POST["nombre_presentacion5"])? limpiarCadena($_POST["nombre_presentacion5"]):"";
$nombre_presentacion6=isset($_POST["nombre_presentacion6"])? limpiarCadena($_POST["nombre_presentacion6"]):"";
$nombre_presentacion7=isset($_POST["nombre_presentacion7"])? limpiarCadena($_POST["nombre_presentacion7"]):"";
$nombre_presentacion8=isset($_POST["nombre_presentacion8"])? limpiarCadena($_POST["nombre_presentacion8"]):"";
$nombre_presentacion9=isset($_POST["nombre_presentacion9"])? limpiarCadena($_POST["nombre_presentacion9"]):"";
$nombre_presentacion10=isset($_POST["nombre_presentacion10"])? limpiarCadena($_POST["nombre_presentacion10"]):"";
$nombre_presentacion11=isset($_POST["nombre_presentacion11"])? limpiarCadena($_POST["nombre_presentacion11"]):"";
$nombre_presentacion12=isset($_POST["nombre_presentacion12"])? limpiarCadena($_POST["nombre_presentacion12"]):"";
$nombre_presentacion13=isset($_POST["nombre_presentacion13"])? limpiarCadena($_POST["nombre_presentacion13"]):"";
$nombre_presentacion14=isset($_POST["nombre_presentacion14"])? limpiarCadena($_POST["nombre_presentacion14"]):"";
$nombre_presentacion15=isset($_POST["nombre_presentacion15"])? limpiarCadena($_POST["nombre_presentacion15"]):"";
$nombre_presentacion16=isset($_POST["nombre_presentacion16"])? limpiarCadena($_POST["nombre_presentacion16"]):"";
$nombre_presentacion17=isset($_POST["nombre_presentacion17"])? limpiarCadena($_POST["nombre_presentacion17"]):"";
$nombre_presentacion18=isset($_POST["nombre_presentacion18"])? limpiarCadena($_POST["nombre_presentacion18"]):"";
$nombre_presentacion19=isset($_POST["nombre_presentacion19"])? limpiarCadena($_POST["nombre_presentacion19"]):"";
$nombre_presentacion20=isset($_POST["nombre_presentacion20"])? limpiarCadena($_POST["nombre_presentacion20"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idpresentacion)){
            $rspta=$crear_presentacion->insertar($nombre_presentacion1,$nombre_presentacion2,$nombre_presentacion3,
            $nombre_presentacion4,$nombre_presentacion5,$nombre_presentacion6,$nombre_presentacion7,$nombre_presentacion8,
            $nombre_presentacion9,$nombre_presentacion10,$nombre_presentacion11,$nombre_presentacion12,$nombre_presentacion13,
            $nombre_presentacion14,$nombre_presentacion15,$nombre_presentacion16,$nombre_presentacion17,$nombre_presentacion18,
            $nombre_presentacion19,$nombre_presentacion20);
            echo $rspta ? "Presentacion registrada" : "Presentacion no se pudo registrar";
        }
        else {
            $rspta=$crear_presentacion->editar($idpresentacion,$nombre_presentacion1,$nombre_presentacion2,$nombre_presentacion3,
            $nombre_presentacion4,$nombre_presentacion5,$nombre_presentacion6,$nombre_presentacion7,$nombre_presentacion8,
            $nombre_presentacion9,$nombre_presentacion10,$nombre_presentacion11,$nombre_presentacion12,$nombre_presentacion13,
            $nombre_presentacion14,$nombre_presentacion15,$nombre_presentacion16,$nombre_presentacion17,$nombre_presentacion18,
            $nombre_presentacion19,$nombre_presentacion20);
            echo $rspta ? "Presentacion actualizada" : "Presentacion no se pudo actualizar";
        }
    break;
 

 
    case 'mostrar':
        $rspta=$crear_presentacion->mostrar($idpresentacion);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;
 
    case 'listar':
        $rspta=$crear_presentacion->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idpresentacion.')"><i class="fa fa-pencil"></i></button>',
                "1"=>$reg->nombre_presentacion1,
                "2"=>$reg->nombre_presentacion2,
                "3"=>$reg->nombre_presentacion3,
                "4"=>$reg->nombre_presentacion4,
                "5"=>$reg->nombre_presentacion5,
                "6"=>$reg->nombre_presentacion6,
                "7"=>$reg->nombre_presentacion7,
                "8"=>$reg->nombre_presentacion8,
                "9"=>$reg->nombre_presentacion9,
                "10"=>$reg->nombre_presentacion10,
                "11"=>$reg->nombre_presentacion11,
                "12"=>$reg->nombre_presentacion12,
                "13"=>$reg->nombre_presentacion13,
                "14"=>$reg->nombre_presentacion14,
                "15"=>$reg->nombre_presentacion15,
                "16"=>$reg->nombre_presentacion16,
                "17"=>$reg->nombre_presentacion17,
                "18"=>$reg->nombre_presentacion18,
                "19"=>$reg->nombre_presentacion19,
                "20"=>$reg->nombre_presentacion20
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