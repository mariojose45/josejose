<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Cuentasxcobrar.php";
 
$cuentasxcobrar=new CuentasXcobrar();
 
$idventa=isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";
$tipo_pago=isset($_POST["tipo_pago"])? limpiarCadena($_POST["tipo_pago"]):"";
$numero_boleta=isset($_POST["numero_boleta"])? limpiarCadena($_POST["numero_boleta"]):"";
$idusuario=$_SESSION["idusuario"];
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':

            $rspta=$cuentasxcobrar->editar($idventa,$tipo_pago,$numero_boleta,$idusuario,$fecha_hora);
            echo $rspta ? "Pago registrado2" : "Problema al Regsitrar el Pago";
        
    break;
 
    case 'desactivar':
        $rspta=$categoria->desactivar($idcategoria);
        echo $rspta ? "Categoría Desactivada" : "Categoría no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$categoria->activar($idcategoria);
        echo $rspta ? "Categoría activada" : "Categoría no se puede activar";
        break;
    break;
 
    case 'mostrar':
        $rspta=$cuentasxcobrar->mostrar($idventa);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar':
        $rspta=$cuentasxcobrar->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idventa.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idventa.')"><i class="fa fa-check"></i></button>',
                "1"=>$reg->nombre_cliente,
                "2"=>$reg->telefono_cliente,    
                "3"=>$reg->tipo_comprobante,
                "4"=>$reg->serie_comprobante.'-'.$reg->num_comprobante,
                "5"=>$reg->total_venta,
                "6"=>($reg->estadopago==' ')?'<span class="label bg-green">Pago Aplicado</span>':
                '<span class="label bg-red">Pago Pendiente</span>'
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