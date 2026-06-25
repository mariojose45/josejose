<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Deposito.php";
 
$deposito=new Deposito();
 
$iddeposito=isset($_POST["iddeposito"])? limpiarCadena($_POST["iddeposito"]):"";
$idusuario=$_SESSION["idusuario"];
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idcuenta=isset($_POST["idcuenta"])? limpiarCadena($_POST["idcuenta"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$tipo_banco=isset($_POST["tipo_banco"])? limpiarCadena($_POST["tipo_banco"]):"";
$nombre_agencia=isset($_POST["nombre_agencia"])? limpiarCadena($_POST["nombre_agencia"]):"";
$deposito_no=isset($_POST["deposito_no"])? limpiarCadena($_POST["deposito_no"]):"";
$valor_deposito=isset($_POST["valor_deposito"])? limpiarCadena($_POST["valor_deposito"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$saldo_cuenta=isset($_POST["saldo_cuenta"])? limpiarCadena($_POST["saldo_cuenta"]):"";

   
switch ($_GET["op"]){  
    case 'guardaryeditar':
        if (empty($iddeposito)){
            $rspta=$deposito->insertar($idusuario,$idcliente,$idcuenta,$fecha_hora,$tipo_banco,$nombre_agencia,$deposito_no,$valor_deposito,$descripcion,$saldo_cuenta);
            echo $rspta ? "Deposito registrado" : "Deposito no se pudo registrar";
        }
        else {
            $rspta=$deposito->editar($iddeposito);
            echo $rspta ? "Deposito actualizado" : "Deposito no se pudo actualizar"; 
        }
    break;
 
    case 'desactivar':
        $rspta=$deposito->desactivar($iddeposito);
        echo $rspta ? "Deposito Desactivado" : "Deposito no se puede desactivar";
        break;
    break;
 


 
    case 'listar':
        $rspta=$deposito->listar();
        //Vamos a declarar un arrayiddeposito
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->iddeposito.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary"><i class="fa fa-check"></i></button>',
                "1"=>$reg->usuario,
                "2"=>$reg->cliente,
                "3"=>$reg->cta_nombre,
                "4"=>$reg->fecha,
                "5"=>$reg->tipo_banco,
                "6"=>$reg->nombre_agencia,
                "7"=>$reg->deposito_no,
                "8"=>$reg->valor_deposito,
                "9"=>$reg->descripcion,
                "10"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
                '<span class="label bg-red">Anulado</span>'
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;

    case 'selectCliente':
        require_once "../modelos/Persona.php"; 
        $persona = new Persona();
 
        $rspta = $persona->listarCp();
 
        while ($reg = $rspta->fetch_object())
                {
                echo '<option  value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
                }
    break; 

}
?>