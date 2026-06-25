<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Cta_bancaria.php";
 
$ctabancaria=new CtaBancaria();
 
$idcuenta=isset($_POST["idcuenta"])? limpiarCadena($_POST["idcuenta"]):"";
$cta_cod=isset($_POST["cta_cod"])? limpiarCadena($_POST["cta_cod"]):"";
$cta_nombre=isset($_POST["cta_nombre"])? limpiarCadena($_POST["cta_nombre"]):"";
$num_cta=isset($_POST["num_cta"])? limpiarCadena($_POST["num_cta"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$saldo_inicial=isset($_POST["saldo_inicial"])? limpiarCadena($_POST["saldo_inicial"]):"";
$tipo_banco=isset($_POST["tipo_banco"])? limpiarCadena($_POST["tipo_banco"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idcuenta)){
            $rspta=$ctabancaria->insertar($cta_cod,$cta_nombre,$num_cta,$descripcion,$saldo_inicial,$tipo_banco);
            echo $rspta ? "Cuenta  registrada" : "Cuenta  no se pudo registrar";
        }
        else {
            $rspta=$ctabancaria->editar($idcuenta,$cta_cod,$cta_nombre,$num_cta,$descripcion,$saldo_inicial,$tipo_banco);
            echo $rspta ? "Cuenta  actualizada" : "Cuenta  no se pudo actualizar";
        }
    break; 
 
    case 'desactivar':
        $rspta=$ctabancaria->desactivar($idcuenta);
        echo $rspta ? "Cuenta  Desactivada" : "Cuenta  no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$ctabancaria->activar($idcuenta);
        echo $rspta ? "Cuenta  activada" : "Cuenta  no se puede activar";
        break;
    break;
 
    case 'mostrar':
        $rspta=$ctabancaria->mostrar($idcuenta);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar':
        $rspta=$ctabancaria->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idcuenta.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idcuenta.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idcuenta.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idcuenta.')"><i class="fa fa-check"></i></button>',
                "1"=>$reg->cta_cod,
                "2"=>$reg->cta_nombre,
                "3"=>$reg->num_cta,
                "4"=>$reg->descripcion,
                "5"=>$reg->saldo_inicial,
                "6"=>$reg->saldo_cuenta,
                "7"=>$reg->tipo_banco,
                "8"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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