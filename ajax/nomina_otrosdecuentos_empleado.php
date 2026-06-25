<?php 
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Nomina_otrosdecuentos_empleado.php";
 
$resobject=new Nominaotrosdecuentosempleado();
 
$idotrosdecuentosempleado=isset($_POST["idotrosdecuentosempleado"])? limpiarCadena($_POST["idotrosdecuentosempleado"]):"";
$idempleado=isset($_POST["idempleado"])? limpiarCadena($_POST["idempleado"]):"";
$monto_prestamo=isset($_POST["monto_prestamo"])? limpiarCadena($_POST["monto_prestamo"]):"";
$no_cuotas=isset($_POST["no_cuotas"])? limpiarCadena($_POST["no_cuotas"]):"";
$fecha_prestamo=isset($_POST["fecha_prestamo"])? limpiarCadena($_POST["fecha_prestamo"]):"";
$fecha_ultimo_abono=isset($_POST["fecha_ultimo_abono"])? limpiarCadena($_POST["fecha_ultimo_abono"]):"";
$concepto_prestamo=isset($_POST["concepto_prestamo"])? limpiarCadena($_POST["concepto_prestamo"]):"";
$tipo_operacion=isset($_POST["tipo_operacion"])? limpiarCadena($_POST["tipo_operacion"]):"";

$no_cuota=isset($_POST["no_cuota"])==true?$_POST["no_cuota"]:"";
$fecha_abono=isset($_POST["fecha_abono"])==true?$_POST["fecha_abono"]:"";
$monto_abono=isset($_POST["monto_abono"])==true?$_POST["monto_abono"]:"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idotrosdecuentosempleado)){
            $rspta=$resobject->insertar($idempleado,$monto_prestamo,$no_cuotas,$fecha_prestamo,
            $fecha_ultimo_abono,$concepto_prestamo,$no_cuota,$fecha_abono,$monto_abono,$tipo_operacion);
            echo $rspta ? "Otros Descuentos registrado" : "Otros Descuentos no se pudo registrar";
        }
        else {
            $rspta=$resobject->editar($idotrosdecuentosempleado,$idempleado,$monto_prestamo,$no_cuotas,$fecha_prestamo,
            $fecha_ultimo_abono,$concepto_prestamo,$no_cuota,$fecha_abono,$monto_abono,$tipo_operacion);
            echo $rspta ? "Otros Descuentos actualizado" : "Otros Descuentos no se pudo actualizar";
        }
    break;

    case 'guardaryeditarAbono':
        $idotrosdecuentosempleado_abono=$_REQUEST["idotrosdecuentosempleado_abono"]; 
        $idempleadoAbono=$_REQUEST["idempleadoAbono"]; 
        $monto_prestamoAbono=$_REQUEST["monto_prestamoAbono"]; 
        $monto_abonoAbono=$_REQUEST["monto_abonoAbono"]; 
        $saldo_prestamoAbono=$_REQUEST["saldo_prestamoAbono"]; 
        $idcuenta=$_REQUEST["idcuenta"]; 
        $descripcion_abono=$_REQUEST["descripcion_abono"]; 
        $fecha_abono=$_REQUEST["fecha_abono"]; 
        $tipo_operacion_abono=$_REQUEST["tipo_operacion_abono"]; 

            $rspta=$resobject->insertarAbonoPrestamo($idotrosdecuentosempleado_abono,$idempleadoAbono,
            $monto_prestamoAbono,$monto_abonoAbono,$saldo_prestamoAbono,$fecha_abono,$idcuenta,$descripcion_abono,$tipo_operacion_abono);
            echo $rspta;
    break;    
 
    case 'desactivar':
        $rspta=$resobject->desactivar($idotrosdecuentosempleado);
        echo $rspta;
    break;
 

    case 'mostrar':
        $rspta=$resobject->mostrar($idotrosdecuentosempleado);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'mostrarAbonoPrestamo':
        $rspta=$resobject->mostrarAbonoPrestamo($idotrosdecuentosempleado);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;    

    case 'detallePrestamo':
        $idotrosdecuentosempleado = $_POST['idotrosdecuentosempleado'];
        $rspta = $resobject->listarDetalle($idotrosdecuentosempleado);
        echo json_encode($rspta);
    break;    
 
    case 'listar':
        $rspta = $resobject->listar();
        $data = array();
    
        while ($reg = $rspta->fetch_object()) {

            $url = '../reportes/exEstadoCuentaPrestamo.php?id=';
    
         
    
            // ✅ 2. Si el préstamo aún tiene saldo, mostrar los botones normales
            if (round((float)$reg->abono_prestamo, 2) >= round((float)$reg->monto_prestamo, 2)) {
                    // ✅ 1. Por defecto sin botones
                    $botones = '<span class="label bg-blue">Pagado</span>'.'<a target="_blank" href="'.$url.$reg->idotrosdecuentosempleado.'"><button class="btn btn-info"><i class="fa fa-print"></i></button></a>';
            }else{
                    if ($reg->condicion) {
                        $botones =
                            '<button class="btn btn-warning" title="Editar préstamo" onclick="mostrar(' . $reg->idotrosdecuentosempleado . ')"><i class="fa fa-pencil"></i></button>' .
                            ' <button class="btn btn-danger" title="Desactivar préstamo" onclick="desactivar(' . $reg->idotrosdecuentosempleado . ')"><i class="fa fa-close"></i></button>' .
                            ' <button class="btn btn-success" title="Abonos préstamo"  onclick="abonosPrestamo(' . $reg->idotrosdecuentosempleado . ')"><i class="fa fa-gear"></i></button>'.
                            '<a target="_blank" href="'.$url.$reg->idotrosdecuentosempleado.'"><button class="btn btn-info"><i class="fa fa-print"></i></button></a>';
                    } else {
                        $botones =
                            '<a target="_blank" href="'.$url.$reg->idotrosdecuentosempleado.'"><button class="btn btn-info"><i class="fa fa-print"></i></button></a>';
                    }                    
            }
    
            $data[] = array(
                "0" => $botones,
                "1" => $reg->idotrosdecuentosempleado,
                "2" => $reg->tipo_operacion,                
                "3" => $reg->nombre_empleado,
                "4" => number_format($reg->monto_prestamo, 2),
                "5" => number_format($reg->abono_prestamo, 2),
                "6" => number_format($reg->saldo_prestamos, 2),
                "7" => $reg->no_cuotas,
                "8" => $reg->no_cuotas_pagadas,
                "9" => ($reg->no_cuotas - $reg->no_cuotas_pagadas),
                "10" => $reg->fecha_prestamo,
                "11" => $reg->fecha_ultimo_abono,
                "12" => $reg->concepto_prestamo,
                "13" => ($reg->condicion)
                    ? '<span class="label bg-green">Activado</span>'
                    : '<span class="label bg-red">Desactivado</span>'
            );
        }
    
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
    break;

    case 'listarAbonosPrestamo':
        $fecha_inicio_reporte = $_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte = $_REQUEST["fecha_fin_reporte"];
        $rspta = $resobject->listarAbonosPrestamo($fecha_inicio_reporte,$fecha_fin_reporte);
        $data = array();
    
        while ($reg = $rspta->fetch_object()) {

                    $url = '../reportes/exReporteAbonoPrestamoEmpleado.php?idabono_otrosdecuentos_empleado=';
                    $botones ='<a target="_blank" href="'.$url.$reg->idabono_otrosdecuentos_empleado. '&idotrosdecuentosempleado=' . $reg->idotrosdecuentosempleado . '" title="Ticket 79mm"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>';
    
            $data[] = array(
                "0" => $botones,
                "1" => $reg->tipo_operacion,
                "2" => $reg->nombre_empleado,
                "3" => $reg->fecha_hora,
                "4" => number_format($reg->monto_prestamo, 2),
                "5" => number_format($reg->abono_prestamo, 2),
                "6" => number_format($reg->saldo_prestamos, 2),
                "7" => ($reg->condicion)
                    ? '<span class="label bg-green">Activado</span>'
                    : '<span class="label bg-red">Desactivado</span>'
            );
        }
    
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
    break;    

}
?>