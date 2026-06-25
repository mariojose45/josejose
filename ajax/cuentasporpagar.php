
<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Cuentasporpagar.php";
 
$cuentasporpagar=new Cuentasporpagar();  
 
$idingreso=isset($_POST["idingreso"])? limpiarCadena($_POST["idingreso"]):"";
$idusuario=$_SESSION["idusuario"];
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$serie_comprobante=isset($_POST["serie_comprobante"])? limpiarCadena($_POST["serie_comprobante"]):"";
$num_comprobante=isset($_POST["num_comprobante"])? limpiarCadena($_POST["num_comprobante"]):"";
$fecha_operacion=isset($_POST["fecha_operacion"])? limpiarCadena($_POST["fecha_operacion"]):"";
$idcuenta=isset($_POST["idcuenta"])? limpiarCadena($_POST["idcuenta"]):"";

$total_compra=isset($_POST["total_compra"])? limpiarCadena($_POST["total_compra"]):"";
$valor_pagar=isset($_POST["valor_pagar"])? limpiarCadena($_POST["valor_pagar"]):"";
$saldo_ingreso=isset($_POST["saldo_ingreso"])? limpiarCadena($_POST["saldo_ingreso"]):""; 
$tipo_pago=isset($_POST["tipo_pago"])? limpiarCadena($_POST["tipo_pago"]):"";
$tipo_banco=isset($_POST["tipo_banco"])? limpiarCadena($_POST["tipo_banco"]):"";
$numero_boleta=isset($_POST["numero_boleta"])? limpiarCadena($_POST["numero_boleta"]):"";
$recibo_caja_numero=isset($_POST["recibo_caja_numero"])? limpiarCadena($_POST["recibo_caja_numero"]):""; 

$no_cheque=isset($_POST["no_cheque"])? limpiarCadena($_POST["no_cheque"]):"";
$fecha_hora_generacion_pago=isset($_POST["fecha_hora_generacion_pago"])? limpiarCadena($_POST["fecha_hora_generacion_pago"]):"";
$desp_cheque=isset($_POST["desp_cheque"])? limpiarCadena($_POST["desp_cheque"]):""; 

  
switch ($_GET["op"]){ 
    case 'guardaryeditar':
        if (empty($idingreso)){
            $rspta=$cuentasporpagar->insertar($idingreso);
            echo $rspta ? "Cuenta por Cobrar  registrada" : "Cuenta por Cobrar  no se pudo registrar";
        }
        else {  
            $rspta=$cuentasporpagar->editar($idingreso,$idusuario,$idcliente,$serie_comprobante,$num_comprobante,$fecha_operacion,$idcuenta,$total_compra,$valor_pagar,$saldo_ingreso,$tipo_pago,$tipo_banco,$numero_boleta,$recibo_caja_numero,$no_cheque,$fecha_hora_generacion_pago,$desp_cheque);
            echo $rspta ? "Cuenta por Cobrar  actualizada" : "Cuenta por Cobrar  no se pudo actualizar";
        }
    break;
 

 
    case 'mostrar':
        $rspta=$cuentasporpagar->mostrar($idingreso); 
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar': 
        $rspta=$cuentasporpagar->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){ 


                   $url='../reportes/exRptCtaxpagar.php?id=';        
            $data[]=array(
                "0"=>(($reg->estado=='Pago Aplicado')?'<button class="btn btn-danger" onclick="desactivar('.$reg->idingreso.')"><i class="fa fa-eye"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idingreso.')"><i class="fa fa-eye"></i></button>').
                '<a target="_blank" href="'.$url.$reg->idingreso.'"><button class="btn btn-success"><i class="fa fa-money"></i> </button> </a>',
                "1"=>$reg->idingreso,                    
                "2"=>$reg->proveedor,                           
                "3"=>$reg->fechaingreso,
                "4"=>$reg->serie_comprobante.'-'.$reg->num_comprobante,
                "5"=>$reg->total_compra,
                "6"=>$reg->no_cheque,
                "7"=>$reg->fecha_hora_generacion_pago,
                "8"=>$reg->valor_pagar,
                "9"=>$reg->saldo_ingreso,
                "10"=>$reg->tipo_pago,
                "11"=>($reg->estado=='Pago Aplicado')?'<span class="label bg-green">Pago Aplicado</span>':
                '<span class="label bg-red">Pendiente Pago</span>' 
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results); 
 
    break;

    case 'selectBanco':
        require_once "../modelos/Cta_bancaria.php"; 
        $ctabancaria = new CtaBancaria();
 
        $rspta = $ctabancaria->selectBanco();
 
        while ($reg = $rspta->fetch_object()) 
                {
                echo '<option   value=' . $reg->idcuenta . '>' .$reg->cta_cod. '-No.-'.$reg->num_cta.'--'.$reg->saldo_cuenta.'</option>';
                }
    break;    
}
?>