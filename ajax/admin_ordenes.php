<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';
require_once "../modelos/Admin_ordenes.php";
 
$adminordenes=new AdminOrdenes();          
  
$idnueva_orden=isset($_POST["idnueva_orden"])? limpiarCadena($_POST["idnueva_orden"]):"";
$idtecnico=isset($_POST["idtecnico"])? limpiarCadena($_POST["idtecnico"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$imei_cel=isset($_POST["imei_cel"])? limpiarCadena($_POST["imei_cel"]):"";
$idmarca=isset($_POST["idmarca"])? limpiarCadena($_POST["idmarca"]):"";
$idmodelo=isset($_POST["idmodelo"])? limpiarCadena($_POST["idmodelo"]):"";
$idtipo_equipo=isset($_POST["idtipo_equipo"])? limpiarCadena($_POST["idtipo_equipo"]):"";
$idcolor=isset($_POST["idcolor"])? limpiarCadena($_POST["idcolor"]):"";
$enciende=isset($_POST["enciende"])? limpiarCadena($_POST["enciende"]):"";
$golpes=isset($_POST["golpes"])? limpiarCadena($_POST["golpes"]):""; 
$puerto_carga=isset($_POST["puerto_carga"])? limpiarCadena($_POST["puerto_carga"]):"";
$password_orden=isset($_POST["password_orden"])? limpiarCadena($_POST["password_orden"]):"";
$falla_equipo=isset($_POST["falla_equipo"])? limpiarCadena($_POST["falla_equipo"]):"";
$diagnostico_equipo=isset($_POST["diagnostico_equipo"])? limpiarCadena($_POST["diagnostico_equipo"]):"";
$presupuesto=isset($_POST["presupuesto"])? limpiarCadena($_POST["presupuesto"]):""; 
$repuestos=isset($_POST["repuestos"])? limpiarCadena($_POST["repuestos"]):"";
$anticipo=isset($_POST["anticipo"])? limpiarCadena($_POST["anticipo"]):"";
$total_orden=isset($_POST["total_orden"])? limpiarCadena($_POST["total_orden"]):"";
$codigo_ordennueva=isset($_POST["codigo_ordennueva"])? limpiarCadena($_POST["codigo_ordennueva"]):"";


//////
$forma_pago=isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
$Entrega_presupuesto=isset($_POST["Entrega_presupuesto"])? limpiarCadena($_POST["Entrega_presupuesto"]):"";
$Entrega_repuestos=isset($_POST["Entrega_repuestos"])? limpiarCadena($_POST["Entrega_repuestos"]):"";
$Entrega_anticipo=isset($_POST["Entrega_anticipo"])? limpiarCadena($_POST["Entrega_anticipo"]):"";
$Entrega_SaldoPendientexpagar=isset($_POST["Entrega_SaldoPendientexpagar"])? limpiarCadena($_POST["Entrega_SaldoPendientexpagar"]):"";
$Entrega_total_orden=isset($_POST["Entrega_total_orden"])? limpiarCadena($_POST["Entrega_total_orden"]):"";
/////

$fechaHoraActual = date('Y-m-d H:i:s');
 
$idnueva_orden_cambiar_estado=isset($_POST["idnueva_orden_cambiar_estado"])? limpiarCadena($_POST["idnueva_orden_cambiar_estado"]):"";
$cambiar_estado=isset($_POST["cambiar_estado"])? limpiarCadena($_POST["cambiar_estado"]):"";
$idusuario=$_SESSION["idusuario"];
  
switch ($_GET["op"]){
    case 'guardaryeditar': 
        if (empty($idnueva_orden)){
            $rspta=$adminordenes->insertar($idtecnico,$idcliente,$fecha_hora,$imei_cel,$idmarca,$idmodelo,$idtipo_equipo,$idcolor,$enciende,$golpes,$puerto_carga,$password_orden,$falla_equipo,$diagnostico_equipo,$presupuesto,$repuestos,$anticipo,$total_orden,$codigo_ordennueva,$idusuario);
            echo $rspta ? "Orden Creada de Forma correcta" : "Orden  no se puede crear";
        }
        else {
            $rspta=$adminordenes->editar($idnueva_orden,$idtecnico,$idcliente,$fecha_hora,$imei_cel,$idmarca,$idmodelo,$idtipo_equipo,$idcolor,$enciende,$golpes,$puerto_carga,$password_orden,$falla_equipo,$diagnostico_equipo,$presupuesto,$repuestos,$anticipo,$total_orden,$codigo_ordennueva,$idusuario);
            echo $rspta;
        }
    break;

    case 'selectUsuario': 
        require_once "../modelos/Usuario.php";
        $user = new Usuario();
 
        $rspta = $user->selectUsuario();
 
        while ($reg = $rspta->fetch_object())
                {
                echo '<option value=' . $reg->idusuario . '>' . $reg->nombre . '</option>';
                }
    break;     

    case 'guardaryeditarCambiarEstado':   
            $rspta=$adminordenes->guardaryeditarCambiarEstado($idnueva_orden_cambiar_estado,$cambiar_estado,$idusuario,$fechaHoraActual,$Entrega_presupuesto,$Entrega_repuestos,$Entrega_anticipo,$Entrega_SaldoPendientexpagar,$Entrega_total_orden,$forma_pago,$presupuesto);
             echo $rspta;
    break;     
  
    case 'desactivar':  
        $rspta=$adminordenes->desactivar($idnueva_orden);
        echo $rspta ? "Orden Eliminada" : "Orden no se puede eliminar";
    break;
 
   
    case 'mostrar':
        $rspta=$adminordenes->mostrar($idnueva_orden);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'mostrarSaldoOrden':
        $rspta=$adminordenes->mostrarSaldoOrden($idnueva_orden_cambiar_estado);
        //Codificar el resultado utilizando json
        echo json_encode($rspta); 
    break;    
 
    case 'mostrar_cambiarestado': 
        $rspta=$adminordenes->mostrar_cambiarestado($idnueva_orden);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;    
 
    case 'listar': 
        $rspta=$adminordenes->listar();
        //Vamos a declarar un array
        $data= Array();    

        while ($reg=$rspta->fetch_object()){ 

            $url='../reportes/exEtiqueta_orden.php?id='; 

            $url_2='../reportes/exEtiqueta_orden_carta.php?id='; 
            $url_3='../reportes/exEtiqueta_orden_tiket.php?id='; 

            if ($reg->estado=='EN REPARACION') { 
                # code...
                $res_estado='<span class="label bg-aqua">EN REPARACION</span>';
            }
            else if ($reg->estado=='ESPERA') {
                # code...
                $res_estado='<span class="label bg-red">ESPERA</span>';
            }
            else if ($reg->estado=='ENTREGADO') {  
                $res_estado='<span class="label bg-green">ENTREGADO</span>';
            }
            else if ($reg->estado=='GARANTIA') {
                # code...
                $res_estado='<span class="label bg-yellow">GARANTIA</span>';
            }
            else if ($reg->estado=='SIN REPARACION') {
                # code...
                $res_estado='<span class="label bg-aqua">SIN REPARACION</span>';
            }
            else if ($reg->estado=='REPARADOS') {
                # code...
                $res_estado='<span class="label bg-aqua">REPARADOS</span>';
            }                        
            else  {
                # code... 
            }  
  
            $data[]=array(
                "0"=>'<div onclick="detalles()">'. $reg->idnueva_orden .'</div>',
                "1"=>'<button class="btn btn-warning btn-block" data-toggle="tooltip" data-placement="top" title="Etiqueta" onclick="abrirVentanaetiqueta(\'' . $url . $reg->idnueva_orden . '\')"><i class="fa fa-sticky-note"></i></button>',
                "2"=>'<div class="btn-group dropright" data-toggle="tooltip" data-placement="top" title="Pdf Carta, Tickket">
                          <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Etiqueta" data-placement="top">
                            <i class="fa fa-print"></i>
                          </button> 
                          <div class="dropdown-menu">
                            <li><a onclick="abrirVentanaetiqueta(\'' . $url_2 . $reg->idnueva_orden . '\')">Carta</a></li>
                            <li><a onclick="abrirVentanaetiqueta(\'' . $url_3 . $reg->idnueva_orden . '\')">Tickket</a></li>
                          </div> 
                        </div>',
                "3"=>' <button class="btn btn-warning btn-block"  data-toggle="tooltip" data-placement="top" title="Editar Orden" onclick="mostrar('.$reg->idnueva_orden.')"><i class="fa fa-pencil"></i></button>',
                "4"=>'<button class="btn btn-success btn-block" data-toggle="tooltip" data-placement="top" title="Etiqueta" onclick="window.open(\'https://wa.me/' . $reg->nombre_telefono . '\')"><i class="fa fa-whatsapp"></i></button>'.' <button class="btn btn-danger btn-block" onclick="desactivar('.$reg->idnueva_orden.')"><i class="fa fa-close"></i></button>',
                "5"=>$reg->nombre_cliente, 
                "6"=>$reg->nombre_tipoequipo, 
                "7"=>$reg->nombre_marca,
                "8"=>$reg->nombre_modelo,
                "9"=>' <button class="btn btn-default btn-block"  data-toggle="tooltip" data-placement="top" title="Cambiar Estado" onclick="cambiarestado('.$reg->idnueva_orden.')">'.$res_estado.'</button>',
                "10"=>$reg->fecha_creacion_ingreso,
                "11"=>$reg->falla_equipo,
                "12"=>$reg->diagnostico_equipo,
                "13"=>$reg->nombre_usuariocreacion, 
                "14"=>$reg->fecha_add,                
                "15"=>$reg->codigo_ordennueva,
                "16"=>$reg->num_nueva_orden,
                "17"=>number_format($reg->presupuesto,2,'.',','),
                "18"=>number_format($reg->repuestos,2,'.',','),
                "19"=>number_format($reg->anticipo,2,'.',','),
                "20"=>number_format($reg->total_orden,2,'.',','),
                "21"=>($reg->condicion)?'<span class="label bg-green">Aceptado</span>':
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

    case 'listarxusuario':
        $rspta=$adminordenes->listarxusuario(); 
        //Vamos a declarar un array
        $data= Array(); 
 
        while ($reg=$rspta->fetch_object()){
            $url='../reportes/exEtiqueta_orden.php?id='; 

            $url_2='../reportes/exEtiqueta_orden_carta.php?id='; 
            $url_3='../reportes/exEtiqueta_orden_tiket.php?id='; 

            if ($reg->estado=='EN REPARACION') { 
                # code...
                $res_estado='<span class="label bg-aqua">EN REPARACION</span>';
            }
            else if ($reg->estado=='ESPERA') {
                # code...
                $res_estado='<span class="label bg-red">ESPERA</span>';
            }
            else if ($reg->estado=='ENTREGADO') {  
                $res_estado='<span class="label bg-green">ENTREGADO</span>';
            }
            else if ($reg->estado=='GARANTIA') {
                # code...
                $res_estado='<span class="label bg-yellow">GARANTIA</span>';
            }
            else if ($reg->estado=='SIN REPARACION') {
                # code...
                $res_estado='<span class="label bg-aqua">SIN REPARACION</span>';
            }
            else if ($reg->estado=='REPARADOS') {
                # code...
                $res_estado='<span class="label bg-aqua">REPARADOS</span>';
            }                        
            else  {
                # code... 
            }   
 
            $data[]=array(
                "0"=>'<div onclick="detalles()">'. $reg->idnueva_orden .'</div>',
                "1"=>'<button class="btn btn-warning btn-block" data-toggle="tooltip" data-placement="top" title="Etiqueta" onclick="abrirVentanaetiqueta(\'' . $url . $reg->idnueva_orden . '\')"><i class="fa fa-sticky-note"></i></button>',
                "2"=>'<div class="btn-group dropright" data-toggle="tooltip" data-placement="top" title="Pdf Carta, Tickket">
                          <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Etiqueta" data-placement="top">
                            <i class="fa fa-print"></i>
                          </button> 
                          <div class="dropdown-menu">
                            <li><a onclick="abrirVentanaetiqueta(\'' . $url_2 . $reg->idnueva_orden . '\')">Carta</a></li>
                            <li><a onclick="abrirVentanaetiqueta(\'' . $url_3 . $reg->idnueva_orden . '\')">Tickket</a></li>
                          </div> 
                        </div>',
                "3"=>' <button class="btn btn-warning btn-block"  data-toggle="tooltip" data-placement="top" title="Editar Orden" onclick="mostrar('.$reg->idnueva_orden.')"><i class="fa fa-pencil"></i></button>',
                "4"=>'<button class="btn btn-success btn-block" data-toggle="tooltip" data-placement="top" title="Etiqueta" onclick="window.open(\'https://wa.me/' . $reg->nombre_telefono . '\')"><i class="fa fa-whatsapp"></i></button>',
                "5"=>$reg->nombre_cliente,
                "6"=>$reg->nombre_tipoequipo, 
                "7"=>$reg->nombre_marca,
                "8"=>$reg->nombre_modelo,
                "9"=>' <button class="btn btn-default btn-block"  data-toggle="tooltip" data-placement="top" title="Cambiar Estado" onclick="cambiarestado('.$reg->idnueva_orden.')">'.$res_estado.'</button>',
                "10"=>$reg->fecha_creacion_ingreso,
                "11"=>$reg->falla_equipo,
                "12"=>$reg->diagnostico_equipo,
                "13"=>$reg->nombre_usuariocreacion,
                "14"=>$reg->fecha_add,                
                "15"=>$reg->codigo_ordennueva,
                "16"=>$reg->num_nueva_orden,
                "17"=>number_format($reg->presupuesto,2,'.',','),
                "18"=>number_format($reg->repuestos,2,'.',','),
                "19"=>number_format($reg->anticipo,2,'.',','),
                "20"=>number_format($reg->total_orden,2,'.',','),
                "21"=>($reg->condicion)?'<span class="label bg-green">Aceptado</span>':
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
 
 
    case 'ordenesxfecha':  
        $fecha_inicio=$_REQUEST["fecha_inicio"];
        $fecha_fin=$_REQUEST["fecha_fin"];
        $cambiar_estado=$_REQUEST["cambiar_estado"];  
   
        $rspta=$adminordenes->ordenesxfecha($fecha_inicio,$fecha_fin,$cambiar_estado);
        //Vamos a declarar un array
        $data= Array();
  
        while ($reg=$rspta->fetch_object()){ 
 
            $data[]=array(
                "0"=>$reg->idnueva_orden,
                "1"=>$reg->nombre_cliente,
                "2"=>$reg->codigo_ordennueva,
                "3"=>$reg->nombre_tipoequipo,
                "4"=>$reg->nombre_marca,
                "5"=>$reg->falla_equipo,
                "6"=>$reg->diagnostico_equipo,
                "7"=>number_format($reg->presupuesto,2,'.',','),
                "8"=>number_format($reg->repuestos,2,'.',','),
                "9"=>number_format($reg->anticipo,2,'.',','),
                "10"=>number_format($reg->total_orden,2,'.',','),
                "11"=>$reg->nombre_usuariocreacion,
                "12"=>$reg->nombre_usuarioupdate,
                "13"=>$reg->fecha_update,
                "14"=>$reg->fecha_add,
                "15"=>$reg->estado,
                "16"=>$reg->fecha_entrega
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;
 

    case 'ordenesxcliente':  
        $idcliente=$_REQUEST["idcliente"];
        $fecha_inicio=$_REQUEST["fecha_inicio"];
        $fecha_fin=$_REQUEST["fecha_fin"];    
   
        $rspta=$adminordenes->ordenesxcliente($fecha_inicio,$fecha_fin,$idcliente);
        //Vamos a declarar un array
        $data= Array(); 
  
        while ($reg=$rspta->fetch_object()){
 
            $data[]=array(
                "0"=>$reg->idnueva_orden,
                "1"=>$reg->nombre_cliente,
                "2"=>$reg->codigo_ordennueva,
                "3"=>$reg->nombre_tipoequipo,
                "4"=>$reg->nombre_marca, 
                "5"=>$reg->falla_equipo,
                "6"=>$reg->diagnostico_equipo,
                "7"=>number_format($reg->presupuesto,2,'.',','),
                "8"=>number_format($reg->repuestos,2,'.',','),
                "9"=>number_format($reg->anticipo,2,'.',','),
                "10"=>number_format($reg->total_orden,2,'.',','),
                "11"=>$reg->nombre_usuariocreacion,
                "12"=>$reg->nombre_usuarioupdate,
                "13"=>$reg->fecha_update,
                "14"=>$reg->fecha_add,
                "15"=>$reg->estado,
                "16"=>$reg->fecha_entrega
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;

    case 'ordenesxcliente_xestado':  
        $idcliente=$_REQUEST["idcliente"];
        $fecha_inicio=$_REQUEST["fecha_inicio"];
        $fecha_fin=$_REQUEST["fecha_fin"];  
        $cambiar_estado=$_REQUEST["cambiar_estado"];   
    
        $rspta=$adminordenes->ordenesxcliente_xestado($fecha_inicio,$fecha_fin,$idcliente,$cambiar_estado);
        //Vamos a declarar un array
        $data= Array();
  
        while ($reg=$rspta->fetch_object()){ 
 
            $data[]=array(
                "0"=>$reg->idnueva_orden,
                "1"=>$reg->nombre_cliente,
                "2"=>$reg->codigo_ordennueva,
                "3"=>$reg->nombre_tipoequipo,
                "4"=>$reg->nombre_marca, 
                "5"=>$reg->falla_equipo,
                "6"=>$reg->diagnostico_equipo,
                "7"=>number_format($reg->presupuesto,2,'.',','),
                "8"=>number_format($reg->repuestos,2,'.',','),
                "9"=>number_format($reg->anticipo,2,'.',','),
                "10"=>number_format($reg->total_orden,2,'.',','),
                "11"=>$reg->nombre_usuariocreacion,
                "12"=>$reg->nombre_usuarioupdate,
                "13"=>$reg->fecha_update,
                "14"=>$reg->fecha_add,
                "15"=>$reg->estado,
                "16"=>$reg->fecha_entrega
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;     
//////////
    case 'ordenesxcliente_reparacion':  
   
        $rspta=$adminordenes->ordenesxcliente_reparacion();
        //Vamos a declarar un array
        $data= Array();
   
        while ($reg=$rspta->fetch_object()){
 
            $data[]=array(
                "0"=>$reg->idnueva_orden,
                "1"=>$reg->nombre_cliente,
                "2"=>$reg->codigo_ordennueva,
                "3"=>$reg->nombre_tipoequipo,
                "4"=>$reg->nombre_marca, 
                "5"=>$reg->falla_equipo,
                "6"=>$reg->diagnostico_equipo,
                "7"=>number_format($reg->presupuesto,2,'.',','),
                "8"=>number_format($reg->repuestos,2,'.',','),
                "9"=>number_format($reg->anticipo,2,'.',','),
                "10"=>number_format($reg->total_orden,2,'.',','),
                "11"=>$reg->nombre_usuariocreacion,
                "12"=>$reg->nombre_usuarioupdate,
                "13"=>$reg->fecha_update,
                "14"=>$reg->fecha_add,
                "15"=>$reg->estado
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
    break; 

    case 'ordenesxcliente_espera':  
   
        $rspta=$adminordenes->ordenesxcliente_espera();
        //Vamos a declarar un array
        $data= Array();
  
        while ($reg=$rspta->fetch_object()){
 
            $data[]=array(
                "0"=>$reg->idnueva_orden,
                "1"=>$reg->nombre_cliente,
                "2"=>$reg->codigo_ordennueva,
                "3"=>$reg->nombre_tipoequipo,
                "4"=>$reg->nombre_marca, 
                "5"=>$reg->falla_equipo,
                "6"=>$reg->diagnostico_equipo,
                "7"=>number_format($reg->presupuesto,2,'.',','),
                "8"=>number_format($reg->repuestos,2,'.',','),
                "9"=>number_format($reg->anticipo,2,'.',','),
                "10"=>number_format($reg->total_orden,2,'.',','),
                "11"=>$reg->nombre_usuariocreacion,
                "12"=>$reg->nombre_usuarioupdate,
                "13"=>$reg->fecha_update,
                "14"=>$reg->fecha_add,
                "15"=>$reg->estado
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;

    case 'ordenesxcliente_entregados':  
   
        $rspta=$adminordenes->ordenesxcliente_entregado();
        //Vamos a declarar un array
        $data= Array();
  
        while ($reg=$rspta->fetch_object()){
 
            $data[]=array(
                "0"=>$reg->idnueva_orden,
                "1"=>$reg->nombre_cliente,
                "2"=>$reg->codigo_ordennueva,
                "3"=>$reg->nombre_tipoequipo,
                "4"=>$reg->nombre_marca, 
                "5"=>$reg->falla_equipo,
                "6"=>$reg->diagnostico_equipo,
                "7"=>number_format($reg->presupuesto,2,'.',','),
                "8"=>number_format($reg->repuestos,2,'.',','),
                "9"=>number_format($reg->anticipo,2,'.',','),
                "10"=>number_format($reg->total_orden,2,'.',','),
                "11"=>$reg->nombre_usuariocreacion,
                "12"=>$reg->nombre_usuarioupdate,
                "13"=>$reg->fecha_update,
                "14"=>$reg->fecha_add,
                "15"=>$reg->estado
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break; 

    case 'ordenesxcliente_garantia':  
   
        $rspta=$adminordenes->ordenesxcliente_garantia();
        //Vamos a declarar un array
        $data= Array();
  
        while ($reg=$rspta->fetch_object()){
 
            $data[]=array(
                "0"=>$reg->idnueva_orden,
                "1"=>$reg->nombre_cliente,
                "2"=>$reg->codigo_ordennueva,
                "3"=>$reg->nombre_tipoequipo,
                "4"=>$reg->nombre_marca, 
                "5"=>$reg->falla_equipo,
                "6"=>$reg->diagnostico_equipo,
                "7"=>number_format($reg->presupuesto,2,'.',','),
                "8"=>number_format($reg->repuestos,2,'.',','),
                "9"=>number_format($reg->anticipo,2,'.',','),
                "10"=>number_format($reg->total_orden,2,'.',','),
                "11"=>$reg->nombre_usuariocreacion,
                "12"=>$reg->nombre_usuarioupdate,
                "13"=>$reg->fecha_update,
                "14"=>$reg->fecha_add,
                "15"=>$reg->estado
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;         


    case 'ordenesxcliente_SINREPARAR':  
   
        $rspta=$adminordenes->ordenesxcliente_SINREPARAR();
        //Vamos a declarar un array
        $data= Array();
  
        while ($reg=$rspta->fetch_object()){
 
            $data[]=array(
                "0"=>$reg->idnueva_orden,
                "1"=>$reg->nombre_cliente,
                "2"=>$reg->codigo_ordennueva,
                "3"=>$reg->nombre_tipoequipo,
                "4"=>$reg->nombre_marca, 
                "5"=>$reg->falla_equipo,
                "6"=>$reg->diagnostico_equipo,
                "7"=>number_format($reg->presupuesto,2,'.',','),
                "8"=>number_format($reg->repuestos,2,'.',','),
                "9"=>number_format($reg->anticipo,2,'.',','),
                "10"=>number_format($reg->total_orden,2,'.',','),
                "11"=>$reg->nombre_usuariocreacion,
                "12"=>$reg->nombre_usuarioupdate,
                "13"=>$reg->fecha_update,
                "14"=>$reg->fecha_add,
                "15"=>$reg->estado
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;         


    case 'ordenesxcliente_REPADOSS':  
   
        $rspta=$adminordenes->ordenesxcliente_REPADOSS();
        //Vamos a declarar un array
        $data= Array(); 
  
        while ($reg=$rspta->fetch_object()){
 
            $data[]=array(
                "0"=>$reg->idnueva_orden,
                "1"=>$reg->nombre_cliente,
                "2"=>$reg->codigo_ordennueva,
                "3"=>$reg->nombre_tipoequipo,
                "4"=>$reg->nombre_marca, 
                "5"=>$reg->falla_equipo,
                "6"=>$reg->diagnostico_equipo,
                "7"=>number_format($reg->presupuesto,2,'.',','),
                "8"=>number_format($reg->repuestos,2,'.',','),
                "9"=>number_format($reg->anticipo,2,'.',','),
                "10"=>number_format($reg->total_orden,2,'.',','),
                "11"=>$reg->nombre_usuariocreacion,
                "12"=>$reg->nombre_usuarioupdate,
                "13"=>$reg->fecha_update,
                "14"=>$reg->fecha_add,
                "15"=>$reg->estado
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;         

/////////


//////////
    case 'ordenesxcliente_reparacion_user':  
   
        $rspta=$adminordenes->ordenesxcliente_reparacion_user();
        //Vamos a declarar un array
        $data= Array();  
  
        while ($reg=$rspta->fetch_object()){
 
            $data[]=array(
                "0"=>$reg->idnueva_orden,
                "1"=>$reg->nombre_cliente,
                "2"=>$reg->codigo_ordennueva,
                "3"=>$reg->nombre_tipoequipo,
                "4"=>$reg->nombre_marca, 
                "5"=>$reg->falla_equipo,
                "6"=>$reg->diagnostico_equipo,
                "7"=>number_format($reg->presupuesto,2,'.',','),
                "8"=>number_format($reg->repuestos,2,'.',','),
                "9"=>number_format($reg->anticipo,2,'.',','),
                "10"=>number_format($reg->total_orden,2,'.',','),
                "11"=>$reg->nombre_usuariocreacion,
                "12"=>$reg->nombre_usuarioupdate,
                "13"=>$reg->fecha_update,
                "14"=>$reg->fecha_add,
                "15"=>$reg->estado
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
    break; 

    case 'ordenesxcliente_espera_user':  
   
        $rspta=$adminordenes->ordenesxcliente_espera_user();
        //Vamos a declarar un array
        $data= Array();
  
        while ($reg=$rspta->fetch_object()){
 
            $data[]=array(
                "0"=>$reg->idnueva_orden,
                "1"=>$reg->nombre_cliente,
                "2"=>$reg->codigo_ordennueva,
                "3"=>$reg->nombre_tipoequipo,
                "4"=>$reg->nombre_marca, 
                "5"=>$reg->falla_equipo,
                "6"=>$reg->diagnostico_equipo,
                "7"=>number_format($reg->presupuesto,2,'.',','),
                "8"=>number_format($reg->repuestos,2,'.',','),
                "9"=>number_format($reg->anticipo,2,'.',','),
                "10"=>number_format($reg->total_orden,2,'.',','),
                "11"=>$reg->nombre_usuariocreacion,
                "12"=>$reg->nombre_usuarioupdate,
                "13"=>$reg->fecha_update,
                "14"=>$reg->fecha_add,
                "15"=>$reg->estado
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;

    case 'ordenesxcliente_entregados_user':  
   
        $rspta=$adminordenes->ordenesxcliente_entregado_user();
        //Vamos a declarar un array
        $data= Array();
  
        while ($reg=$rspta->fetch_object()){
 
            $data[]=array(
                "0"=>$reg->idnueva_orden,
                "1"=>$reg->nombre_cliente,
                "2"=>$reg->codigo_ordennueva,
                "3"=>$reg->nombre_tipoequipo,
                "4"=>$reg->nombre_marca, 
                "5"=>$reg->falla_equipo,
                "6"=>$reg->diagnostico_equipo,
                "7"=>number_format($reg->presupuesto,2,'.',','),
                "8"=>number_format($reg->repuestos,2,'.',','),
                "9"=>number_format($reg->anticipo,2,'.',','),
                "10"=>number_format($reg->total_orden,2,'.',','),
                "11"=>$reg->nombre_usuariocreacion,
                "12"=>$reg->nombre_usuarioupdate,
                "13"=>$reg->fecha_update,
                "14"=>$reg->fecha_add,
                "15"=>$reg->estado
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break; 

    case 'ordenesxcliente_garantia_user':  
   
        $rspta=$adminordenes->ordenesxcliente_garantia_user();
        //Vamos a declarar un array
        $data= Array();
  
        while ($reg=$rspta->fetch_object()){
 
            $data[]=array(
                "0"=>$reg->idnueva_orden,
                "1"=>$reg->nombre_cliente,
                "2"=>$reg->codigo_ordennueva,
                "3"=>$reg->nombre_tipoequipo,
                "4"=>$reg->nombre_marca, 
                "5"=>$reg->falla_equipo,
                "6"=>$reg->diagnostico_equipo,
                "7"=>number_format($reg->presupuesto,2,'.',','),
                "8"=>number_format($reg->repuestos,2,'.',','),
                "9"=>number_format($reg->anticipo,2,'.',','),
                "10"=>number_format($reg->total_orden,2,'.',','),
                "11"=>$reg->nombre_usuariocreacion,
                "12"=>$reg->nombre_usuarioupdate,
                "13"=>$reg->fecha_update,
                "14"=>$reg->fecha_add,
                "15"=>$reg->estado
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;         


    case 'ordenesxcliente_SINREPARAR_user':  
   
        $rspta=$adminordenes->ordenesxcliente_SINREPARAR_user();
        //Vamos a declarar un array
        $data= Array();
  
        while ($reg=$rspta->fetch_object()){
 
            $data[]=array(
                "0"=>$reg->idnueva_orden,
                "1"=>$reg->nombre_cliente,
                "2"=>$reg->codigo_ordennueva,
                "3"=>$reg->nombre_tipoequipo,
                "4"=>$reg->nombre_marca, 
                "5"=>$reg->falla_equipo,
                "6"=>$reg->diagnostico_equipo,
                "7"=>number_format($reg->presupuesto,2,'.',','),
                "8"=>number_format($reg->repuestos,2,'.',','),
                "9"=>number_format($reg->anticipo,2,'.',','),
                "10"=>number_format($reg->total_orden,2,'.',','),
                "11"=>$reg->nombre_usuariocreacion,
                "12"=>$reg->nombre_usuarioupdate,
                "13"=>$reg->fecha_update,
                "14"=>$reg->fecha_add,
                "15"=>$reg->estado
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;         


    case 'ordenesxcliente_REPADOSS_user':  
   
        $rspta=$adminordenes->ordenesxcliente_REPADOSS_user();
        //Vamos a declarar un array
        $data= Array(); 
  
        while ($reg=$rspta->fetch_object()){
 
            $data[]=array(
                "0"=>$reg->idnueva_orden,
                "1"=>$reg->nombre_cliente,
                "2"=>$reg->codigo_ordennueva,
                "3"=>$reg->nombre_tipoequipo,
                "4"=>$reg->nombre_marca, 
                "5"=>$reg->falla_equipo,
                "6"=>$reg->diagnostico_equipo,
                "7"=>number_format($reg->presupuesto,2,'.',','),
                "8"=>number_format($reg->repuestos,2,'.',','),
                "9"=>number_format($reg->anticipo,2,'.',','),
                "10"=>number_format($reg->total_orden,2,'.',','),
                "11"=>$reg->nombre_usuariocreacion,
                "12"=>$reg->nombre_usuarioupdate,
                "13"=>$reg->fecha_update,
                "14"=>$reg->fecha_add,
                "15"=>$reg->estado
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;         

/////////




}
?>