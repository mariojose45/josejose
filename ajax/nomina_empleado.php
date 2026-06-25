<?php 
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Nomina_empleado.php";
 
$categoria=new Nomina_empleado();

$codigo_empleado = isset($_POST["codigo_empleado"]) ? limpiarCadena($_POST["codigo_empleado"]) : "";
$idempleado_r = isset($_POST["idempleado_r"]) ? limpiarCadena($_POST["idempleado_r"]) : "";
$nombres = isset($_POST["nombres"]) ? limpiarCadena($_POST["nombres"]) : "";
$cui = isset($_POST["cui"]) ? limpiarCadena($_POST["cui"]) : "";
$nit = isset($_POST["nit"]) ? limpiarCadena($_POST["nit"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
$fecha_nacimiento = isset($_POST["fecha_nacimiento"]) ? limpiarCadena($_POST["fecha_nacimiento"]) : "";
$hijos = isset($_POST["hijos"]) ? intval(limpiarCadena($_POST["hijos"])) : 0; 
$sexo = isset($_POST["sexo"]) ? limpiarCadena($_POST["sexo"]) : "";
$estado_civil = isset($_POST["estado_civil"]) ? limpiarCadena($_POST["estado_civil"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
$nacionalidad = isset($_POST["nacionalidad"]) ? limpiarCadena($_POST["nacionalidad"]) : "";
$nivel_educativo = isset($_POST["nivel_educativo"]) ? limpiarCadena($_POST["nivel_educativo"]) : "";
$puesto = isset($_POST["puesto"]) ? limpiarCadena($_POST["puesto"]) : "";
$fecha_inicio_laboral = isset($_POST["fecha_inicio_laboral"]) ? limpiarCadena($_POST["fecha_inicio_laboral"]) : "";
$salario_base = isset($_POST["salario"]) ? floatval(limpiarCadena($_POST["salario"])) : 0.00;
$bonificacion = isset($_POST["bonificacion"]) ? floatval(limpiarCadena($_POST["bonificacion"])) : 0.00;
$bono_productividad = isset($_POST["bono_productividad"]) ? floatval(limpiarCadena($_POST["bono_productividad"])) : 0.00;
$salario_extra = isset($_POST["salario_extra"]) ? floatval(limpiarCadena($_POST["salario_extra"])) : 0.00;
$descuento_igss = isset($_POST["igss"]) ? floatval(limpiarCadena($_POST["igss"])) : 0.00;
$descuento_isr = isset($_POST["isr"]) ? floatval(limpiarCadena($_POST["isr"])) : 0.00;
$descuento_prestamo = isset($_POST["prestamo"]) ? floatval(limpiarCadena($_POST["prestamo"])) : 0.00;
$otros_descuentos = isset($_POST["otros_descuentos"]) ? floatval(limpiarCadena($_POST["otros_descuentos"])) : 0.00;
$anticipo_salarial = isset($_POST["anticipo_salarial"]) ? floatval(limpiarCadena($_POST["anticipo_salarial"])) : 0.00;
$fecha_fin_laboral = isset($_POST["fecha_fin_laboral"]) ? limpiarCadena($_POST["fecha_fin_laboral"]) : NULL; 
$edad = isset($_POST["edad"]) ? limpiarCadena($_POST["edad"]) : "";


switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idempleado_r)){
            $rspta=$categoria->insertar($codigo_empleado,$nombres, $cui, $nit, $direccion, $fecha_nacimiento, $hijos, $sexo, $estado_civil, $telefono,
            $nacionalidad, $nivel_educativo, $puesto, $fecha_inicio_laboral, $salario_base, $bonificacion, $bono_productividad, 
            $salario_extra, $descuento_igss, $descuento_isr, $descuento_prestamo, $otros_descuentos, $anticipo_salarial, 
            $fecha_fin_laboral,$edad);
            echo $rspta;
        }
        else {
            $rspta=$categoria->editar($idempleado_r,$codigo_empleado,$nombres, $cui, $nit, $direccion, $fecha_nacimiento, $hijos, $sexo, $estado_civil, $telefono,
            $nacionalidad, $nivel_educativo, $puesto, $fecha_inicio_laboral, $salario_base, $bonificacion, $bono_productividad, 
            $salario_extra, $descuento_igss, $descuento_isr, $descuento_prestamo, $otros_descuentos, $anticipo_salarial, 
            $fecha_fin_laboral,$edad);
            echo $rspta;
        }
    break;
 
    case 'desactivar':
        $rspta=$categoria->desactivar($idempleado_r);
        echo $rspta ? "Categoría Desactivada" : "Categoría no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$categoria->activar($idempleado_r);
        echo $rspta ? "Categoría activada" : "Categoría no se puede activar";
        break;
    break;
 
    case 'mostrar':
        $rspta=$categoria->mostrar($idempleado_r);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar':
        $rspta=$categoria->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idempleado.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idempleado.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idempleado.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idempleado.')"><i class="fa fa-check"></i></button>',
                "1"=>$reg->codigo,
                "2"=>$reg->nombres,
                "3" => $reg->cui . " / " . $reg->nit,
                "4"=>$reg->direccion,
                "5"=>$reg->edad,
                "6"=>$reg->sexo,
                "7"=>$reg->puesto,
                "8"=>$reg->salario_base,
                "9"=>$reg->bonificacion,
                "10"=>$reg->bono_productividad,
                "11"=>$reg->salario_extra,
                "12"=>$reg->descuento_igss,
                "13"=>$reg->descuento_isr,
                "14"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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

    case "selectEmpleado":
        $rspta = $categoria->select();
 
        while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idempleado . '>' . $reg->nombres . '</option>';
                }
    break;    
}
?>