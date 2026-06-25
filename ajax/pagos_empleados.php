<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Pagos_empleados.php";
  
$pagoempleados=new Pagoempleados(); 
$idusuario=$_SESSION["idusuario"];
$idpagoempleado=isset($_POST["idpagoempleado"])? limpiarCadena($_POST["idpagoempleado"]):"";
$idficha_empleado=isset($_POST["idficha_empleado"])? limpiarCadena($_POST["idficha_empleado"]):"";
$fecha_hora_ini=isset($_POST["fecha_hora_ini"])? limpiarCadena($_POST["fecha_hora_ini"]):"";
$fecha_hora_fin=isset($_POST["fecha_hora_fin"])? limpiarCadena($_POST["fecha_hora_fin"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$horas_acumuladas=isset($_POST["horas_acumuladas"])? limpiarCadena($_POST["horas_acumuladas"]):"";
$horas_tarde=isset($_POST["horas_tarde"])? limpiarCadena($_POST["horas_tarde"]):""; 
$horas_extra=isset($_POST["horas_extra"])? limpiarCadena($_POST["horas_extra"]):"";
$horas_feriado=isset($_POST["horas_feriado"])? limpiarCadena($_POST["horas_feriado"]):"";
$total_horas_pagar=isset($_POST["total_horas_pagar"])? limpiarCadena($_POST["total_horas_pagar"]):"";
$valor_hora=isset($_POST["valor_hora"])? limpiarCadena($_POST["valor_hora"]):"";
$sueldo_pagar=isset($_POST["sueldo_pagar"])? limpiarCadena($_POST["sueldo_pagar"]):"";
$bonificacion=isset($_POST["bonificacion"])? limpiarCadena($_POST["bonificacion"]):"";
$bonificacion_extra=isset($_POST["bonificacion_extra"])? limpiarCadena($_POST["bonificacion_extra"]):"";
$descuento=isset($_POST["descuento"])? limpiarCadena($_POST["descuento"]):"";
$sueldo_liquido_recibir=isset($_POST["sueldo_liquido_recibir"])? limpiarCadena($_POST["sueldo_liquido_recibir"]):"";

$idcuenta=isset($_POST["idcuenta"])? limpiarCadena($_POST["idcuenta"]):"";
$forma_pago=isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
$cheque_auto_no=isset($_POST["cheque_auto_no"])? limpiarCadena($_POST["cheque_auto_no"]):"";
$fecha_generacion_pago=isset($_POST["fecha_generacion_pago"])? limpiarCadena($_POST["fecha_generacion_pago"]):"";  


$bono14=isset($_POST["bono14"])? limpiarCadena($_POST["bono14"]):"";
$aguinaldo=isset($_POST["aguinaldo"])? limpiarCadena($_POST["aguinaldo"]):"";
$vacaciones=isset($_POST["vacaciones"])? limpiarCadena($_POST["vacaciones"]):""; 
$fecha_hora_de=isset($_POST["fecha_hora_de"])? limpiarCadena($_POST["fecha_hora_de"]):"";
$fecha_hora_asta=isset($_POST["fecha_hora_asta"])? limpiarCadena($_POST["fecha_hora_asta"]):"";
$prestacion_a_sumar=isset($_POST["prestacion_a_sumar"])? limpiarCadena($_POST["prestacion_a_sumar"]):"";
$prestacion_a_pagar=isset($_POST["prestacion_a_pagar"])? limpiarCadena($_POST["prestacion_a_pagar"]):"";
$dtrabajados=isset($_POST["dtrabajados"])? limpiarCadena($_POST["dtrabajados"]):"";


 
switch ($_GET["op"]){ 
    case 'guardaryeditar':
        if (empty($idpagoempleado)){
            $rspta=$pagoempleados->insertar($idusuario,$idficha_empleado,$fecha_hora_ini,$fecha_hora_fin,$descripcion,$horas_acumuladas,$horas_tarde,$horas_extra,$horas_feriado,$total_horas_pagar,$valor_hora,$sueldo_pagar,$bonificacion,$bonificacion_extra,$descuento,$sueldo_liquido_recibir,$idcuenta,$forma_pago,$cheque_auto_no,$fecha_generacion_pago,$bono14,$aguinaldo,$vacaciones,$fecha_hora_de,$fecha_hora_asta,$prestacion_a_sumar,$prestacion_a_pagar,$dtrabajados);
            echo $rspta ? "Pago Empleado registrado" : "Pago Empleado no se pudo registrar";
        } 
        else {
            $rspta=$pagoempleados->editar($idpagoempleado,$cheque_auto_no);
            echo $rspta ? "Pago Empleado actualizado" : "Pago Empleado no se pudo actualizar";
        }
    break; 

    case 'mostrar':
        $rspta=$pagoempleados->mostrar($idpagoempleado);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;    
  
    case 'desactivar':
        $rspta=$pagoempleados->desactivar($idpagoempleado);
        echo $rspta ? "Categoría Desactivada" : "Categoría no se puede desactivar";
        break;
    break;
 

    case 'registroshoras':
        $rspta=$pagoempleados->ObtenerRegistros($_REQUEST["fechaini"],
        $_REQUEST["fechafin"],$_REQUEST["idempleado"]);

        while ($reg=$rspta->fetch_object()){
            $totime=strtotime($reg->fecha);
            $todate=date('Y-m-d',$totime);
            $tohora=date('H:i',$totime); 
            echo "
                <tr>
                    <td>".$reg->codigo."</td>
                    <td>".($reg->accion==0?"Entrada":"Salida")."</td>
                    <td>".$reg->fecha."</td>

                    <td>
                        <button type='button' 
                            data-toggle='modal' data-target='#myModal'
                            class='btn btn-primary'>Agregar</button>
                        <button type='button' 
                        data-toggle='modal' data-target='#myModal".$reg->id."'
                        class='btn btn-info'>Editar</button>
                        <button type='button' 
                        onclick='eliminarRegistro(".$reg->id.")'
                        class='btn btn-danger'>Eliminar</button>


                        <!-- Modal -->
                        <div class='modal fade' id='myModal".$reg->id."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                          <div class='modal-dialog' role='document'>
                            <div class='modal-content'>
                              <div class='modal-header'>
                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                <h4 class='modal-title' id='myModalLabel'>Editar Registro</h4>
                              </div>
                              <div class='modal-body'>
                                <div class='form-group col-lg-8 col-md-8 col-sm-8 col-xs-12'>
                                  <label>Codigo:</label>
                                  <input type='text' class='form-control' 
                                  value='".$reg->codigo."'
                                  name='codigo' id='codigo".$reg->id."'  placeholder='Codigo'>
                                </div>
                                <div class='form-group col-lg-8 col-md-8 col-sm-8 col-xs-12'>
                                  <label>Tipo:</label>
                                  <select class='form-control' name='tipo' id='tipo".$reg->id."'>
                                    <option ".($reg->accion==0?"selected":"")." value='0'>Entrada</option>
                                    <option ".($reg->accion==1?"selected":"")." value='1'>Salida</option>
                                  </select>
                                </div>
                                <div class='form-group col-lg-8 col-md-8 col-sm-8 col-xs-12'>
                                  <label>Fecha y Hora:</label>
                                  <input type='date' value='".$todate."' class='form-control' name='fecha".$reg->id."' id='fecha".$reg->id."'  placeholder='Fecha'>
                                  <input type='time' value='".$tohora."' class='form-control' name='hora".$reg->id."' id='hora".$reg->id."'  placeholder='Hora'>
                                </div>
                              </div>
                              <div class='modal-footer'>
                                <button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
                                <button type='button' onclick='modificarRegistro(".$reg->id.")' class='btn btn-primary'>Guardar Cambios</button>
                              </div>
                            </div>
                          </div>
                        </div>

                    </td>
                </tr>


                


            ";
        }

    break;

    case 'addregistro': 

        $pagoempleados->AgregarRegistros($_REQUEST["codigo"],
        $_REQUEST["tipo"],$_REQUEST["fecha"]." ".$_REQUEST["hora"]);
 
    break;

    case 'deleteregistro':

        $pagoempleados->EliminarRegistros($_REQUEST["id"]);
 
    break;

    case 'modificarregistro':
        $pagoempleados->modificarregistro($_REQUEST["id"],$_REQUEST["tipo"],$_REQUEST["fecha"]." ".$_REQUEST["hora"]);
    break;

    case 'listar':
        $rspta=$pagoempleados->listar();
        //Vamos a declarar un array
       
        $data= Array();
        while ($reg=$rspta->fetch_object()){

                if ($reg->forma_pago=='Cheque') {
                    # code...
                    $url='../reportes/exCheque3.php?id=';

                }
                else if ($reg->forma_pago=='Transferencia') {
                    # code...
                    $url='../reportes/exCheque3transferencia.php?id=';

                } 
                else if ($reg->forma_pago=='Efectivo') {
                    # code...
                    $url='../reportes/exCheque3efectivo.php?id=';

                }                 
                else{
                    $url='#';
                }      
      
            $data[]=array(
                "0"=>(($reg->estado=='Aceptado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idpagoempleado.')"><i class="fa fa-eye"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idpagoempleado.')"><i class="fa fa-eye"></i></button>').
                '<a target="_blank" href="'.$url.$reg->idpagoempleado.'"><button class="btn btn-info"><i class="fa fa-file"></i> </button> </a>',
                "1"=>$reg->idpagoempleado,
                "2"=>$reg->empleado,
                "3"=>$reg->fecha_creacion,
                "4"=>$reg->descripcion,
                "5"=>$reg->horas_acumuladas, 
                "6"=>$reg->horas_tarde, 
                "7"=>$reg->horas_extra,
                "8"=>$reg->sueldo_liquido_recibir,
                "9"=>$reg->forma_pago,
                "10"=>$reg->num_cta,
                "11"=>$reg->cheque_auto_no,
                "12"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
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

    case 'selectEmpleado':
        require_once "../modelos/Ficha_empleado.php"; 
        $fichaempleado = new Fichaempleado();
  
        $rspta = $fichaempleado->listaficha();
 
        while ($reg = $rspta->fetch_object()) 
                {
                    echo '<option data-hora-extra="'.$reg->horaextra.'" data-sueldo_base="'. $reg->sueldo_base.'"  data-bonificacion="'. $reg->bonificacion.'"  value=' . $reg->idficha_empleado . '>' . $reg->nombre . '--'.$reg->direccion.'</option>';
                }
    break;   

  
    case 'selectBanco':
        require_once "../modelos/Cta_bancaria.php"; 
        $ctabancaria = new CtaBancaria();
 
        $rspta = $ctabancaria->selectBanco();
 
        while ($reg = $rspta->fetch_object()) 
                {
                echo '<option data-saldo_cuenta="'.$reg->saldo_cuenta.'"  value=' . $reg->idcuenta . '>' .$reg->cta_cod. '-No.-'.$reg->num_cta.'--'.$reg->saldo_cuenta.'</option>';
                }
    break;        
    
    case 'ConsultaHora':
        $pagoempleados->CalculoHoras($_REQUEST["fechaini"],$_REQUEST["fechafin"],$_REQUEST["idempleado"]);         
    break;

}
?>