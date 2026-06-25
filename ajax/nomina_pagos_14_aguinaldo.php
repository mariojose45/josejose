<?php 
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Nomina_pagos_14_aguinaldo.php";
 
$nominapagos14aguinaldo=new NominaPagos14Aguinaldo();
 
$idnomina_pagos_14_aguinaldo=isset($_POST["idnomina_pagos_14_aguinaldo"])? limpiarCadena($_POST["idnomina_pagos_14_aguinaldo"]):"";
$fecha_inicio=isset($_POST["fecha_inicio"])? limpiarCadena($_POST["fecha_inicio"]):"";
$fecha_fin=isset($_POST["fecha_fin"])? limpiarCadena($_POST["fecha_fin"]):"";
$tipo_operacion=isset($_POST["tipo_operacion"])? limpiarCadena($_POST["tipo_operacion"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$detalles_json = isset($_POST["detalles_json"]) ? json_decode($_POST["detalles_json"], true) : [];
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idnomina_pagos_14_aguinaldo)){
            $rspta=$nominapagos14aguinaldo->insertar($fecha_inicio,$fecha_fin,$tipo_operacion,$descripcion,$detalles_json);
            echo $rspta;
        }
        else {
            $rspta=$nominapagos14aguinaldo->editar($idnomina_pagos_14_aguinaldo,$fecha_inicio,$fecha_fin,$tipo_operacion,$descripcion,$detalles_json);
            echo $rspta;
        }
    break;
 
    case 'desactivar':
        $rspta=$nominapagos14aguinaldo->desactivar($idnomina_pagos_14_aguinaldo);
        echo $rspta ? "Nomina Pago Desactivada" : "Nomina Pago no se puede desactivar";
    break;
 

    case 'mostrar':
        $rspta=$nominapagos14aguinaldo->mostrar($idnomina_pagos_14_aguinaldo);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'detallenominapagos':
        $rspta=$nominapagos14aguinaldo->detallenominapagos($idnomina_pagos_14_aguinaldo);
        echo json_encode($rspta); 
    break;      
 
    case 'listar':
        $rspta=$nominapagos14aguinaldo->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $url='../reportes/exNominapagosBono14Aguinaldo.php?id=';
            $url1='../reportes/exNominapagosComprobanteBono14Aguinaldo.php?id=';
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idnomina_pagos_14_aguinaldo.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idnomina_pagos_14_aguinaldo.')"><i class="fa fa-close"></i></button>'.
                    '<a target="_blank" title="Nomina de Pagos" href="'.$url.$reg->idnomina_pagos_14_aguinaldo.'"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" title="Comprobante de Pagos" href="'.$url1.$reg->idnomina_pagos_14_aguinaldo.'"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>':
                    '<a target="_blank" title="Nomina de Pagos" href="'.$url.$reg->idnomina_pagos_14_aguinaldo.'"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" title="Comprobante de Pagos" href="'.$url1.$reg->idnomina_pagos_14_aguinaldo.'"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>',
                "1"=>$reg->idnomina_pagos_14_aguinaldo,
                "2"=>$reg->tipo_operacion,
                "3"=>$reg->fechainicio,
                "4"=>$reg->fechafin,
                "5"=>$reg->descripcion,
                "6"=>$reg->nombre_usuario,
                "7"=>$reg->fecha_creacion,
                "8"=>$reg->usuario_mod,
                "9"=>$reg->fecha_modificacion,
                "10"=>$reg->usuario_delete,
                "11"=>$reg->fecha_delete,
                "12"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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

    case 'listarEmpleados2': 
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $rspta=$nominapagos14aguinaldo->listarEmpleados2($fecha_inicio, $fecha_fin);
        echo json_encode($rspta); 
    break;      
}
?>