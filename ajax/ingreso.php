<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


 
require_once "../modelos/Ingreso.php";  
   
$ingreso=new Ingreso();                   
    
$idingreso=isset($_POST["idingreso"])? limpiarCadena($_POST["idingreso"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$codigo_cliente=isset($_POST["codigo_cliente"])? limpiarCadena($_POST["codigo_cliente"]):"";
$nit=isset($_POST["nit"])? limpiarCadena($_POST["nit"]):"";
$nombre_cliente=isset($_POST["nombre_cliente"])? limpiarCadena($_POST["nombre_cliente"]):"";
$telefono_cliente=isset($_POST["telefono_cliente"])? limpiarCadena($_POST["telefono_cliente"]):"";
$direccion_cliente=isset($_POST["direccion_cliente"])? limpiarCadena($_POST["direccion_cliente"]):"";
$correo_cliente=isset($_POST["correo_cliente"])? limpiarCadena($_POST["correo_cliente"]):"";
$tipo_documento_cliente=isset($_POST["tipo_documento_cliente"])? limpiarCadena($_POST["tipo_documento_cliente"]):"";
$idusuario=$_SESSION["idusuario"];
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$serie_comprobante=isset($_POST["serie_comprobante"])? limpiarCadena($_POST["serie_comprobante"]):"";
$num_comprobante=isset($_POST["num_comprobante"])? limpiarCadena($_POST["num_comprobante"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$total_compra=isset($_POST["total_compra"])? limpiarCadena($_POST["total_compra"]):"";
$total_comprades=isset($_POST["total_comprades"])? limpiarCadena($_POST["total_comprades"]):"";
 
$forma_pago=isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";   
$dias_credito=isset($_POST["dias_credito"])? limpiarCadena($_POST["dias_credito"]):"";
$fecha_hora_pago_credito=isset($_POST["fecha_hora_pago_credito"])? limpiarCadena($_POST["fecha_hora_pago_credito"]):"";

$direccion_entrega_orden_compra=isset($_POST["direccion_entrega_orden_compra"])? limpiarCadena($_POST["direccion_entrega_orden_compra"]):"";
$fecha_entrega_orden_compra=isset($_POST["fecha_entrega_orden_compra"])? limpiarCadena($_POST["fecha_entrega_orden_compra"]):"";
$observacion_orden_compra=isset($_POST["observacion_orden_compra"])? limpiarCadena($_POST["observacion_orden_compra"]):"";
$tipo_ingreso_producion=isset($_POST["tipo_ingreso_producion"])? limpiarCadena($_POST["tipo_ingreso_producion"]):"";

$motivo_ND=isset($_POST["motivo_ND"])? limpiarCadena($_POST["motivo_ND"]):"";
$fecha_hora_ND=isset($_POST["fecha_hora_ND"])? limpiarCadena($_POST["fecha_hora_ND"]):"";
$idcotizacion=isset($_POST["idcotizacion"])? limpiarCadena($_POST["idcotizacion"]):"";

$total_compra_r=isset($_POST["total_compra_r"])? limpiarCadena($_POST["total_compra_r"]):"";
$total_comprades_r=isset($_POST["total_comprades_r"])? limpiarCadena($_POST["total_comprades_r"]):"";


$idcotizacion=isset($_POST["idcotizacion"])? limpiarCadena($_POST["idcotizacion"]):"";

  
switch ($_GET["op"]){ 
    case 'guardaryeditar':     
        if(isset($_POST['datosArticulosC'])){
            $datosArticulos = json_decode($_POST['datosArticulosC'], true);
        }
        if (empty($idingreso)){
            $rspta=$ingreso->insertar($idcliente,$codigo_cliente,$nit,$nombre_cliente,$telefono_cliente,$direccion_cliente,$correo_cliente,$tipo_documento_cliente,$idusuario,$tipo_comprobante,
            $serie_comprobante,$num_comprobante,$fecha_hora,$impuesto,$total_compra,$total_comprades,$forma_pago,$dias_credito,$fecha_hora_pago_credito,$direccion_entrega_orden_compra,
            $fecha_entrega_orden_compra,$observacion_orden_compra,$tipo_ingreso_producion,$datosArticulos,$total_compra_r,$total_comprades_r,$idcotizacion);
            echo $rspta ? "Ingreso registrado" : "No se pudieron registrar todos los datos del ingreso";
        }
        else { 
            $rspta=$ingreso->editar($idingreso,$idcliente,$codigo_cliente,$nit,$nombre_cliente,$telefono_cliente,$direccion_cliente,$correo_cliente,$tipo_documento_cliente,$idusuario,$tipo_comprobante,
            $serie_comprobante,$num_comprobante,$fecha_hora,$impuesto,$total_compra,$total_comprades,$forma_pago,$dias_credito,$fecha_hora_pago_credito,$direccion_entrega_orden_compra,
            $fecha_entrega_orden_compra,$observacion_orden_compra,$tipo_ingreso_producion,$datosArticulos,$total_compra_r,$total_comprades_r);
            echo $rspta ? "Ingreso actualizada" : "Ingreso no se pudo actualizar";             
        }
    break; 

  
    case 'guardaryeditarND':        
        if(isset($_POST['datosArticulosND'])){
            $datosArticulos = json_decode($_POST['datosArticulosND'], true);
        }
            $rspta=$ingreso->insetar_nd($idingreso,$idcliente,$codigo_cliente,$nit,$nombre_cliente,$telefono_cliente,$direccion_cliente,$correo_cliente,$tipo_documento_cliente,$idusuario,$tipo_comprobante,
            $serie_comprobante,$num_comprobante,$fecha_hora,$impuesto,$total_compra,$total_comprades,$forma_pago,$dias_credito,$fecha_hora_pago_credito,$direccion_entrega_orden_compra,
            $fecha_entrega_orden_compra,$observacion_orden_compra,$tipo_ingreso_producion,$datosArticulos,$motivo_ND,$fecha_hora_ND,$idcotizacion);
            echo $rspta ? "Nota Debito creado" : "Nota Debito no se pudo crear";             
    break;     
 
    case 'anular':
        $rspta = $ingreso->anular($idingreso);

        if (is_array($rspta)) {
            // Si $rspta es un array con 'status' y 'message', lo devolvemos directamente como JSON
            echo json_encode($rspta);
        } else {
            // Si no es un array, asumimos éxito y devolvemos un mensaje genérico
            echo json_encode([
                "status" => true,
                "message" => "Operación anulada con éxito.",
            ]);
        }
    break;
     
    case 'mostrar':
        $rspta=$ingreso->mostrar($idingreso);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;
 

    case 'detalleingreso':
        $cotiz=$ingreso->detalleingreso($idingreso);
        echo json_encode($cotiz);
    break;       
 

 
    case 'listar':
        $fecha_inicio_reporte=$_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte=$_REQUEST["fecha_fin_reporte"];
        $rspta=$ingreso->listar($fecha_inicio_reporte,$fecha_fin_reporte);
        //Vamos a declarar un array 
        $data= Array();  
  
        while ($reg=$rspta->fetch_object()){ 
            $url='../reportes/exOrdenIngreso1.php?id=';    
            $data[]=array(
                "0"=>($reg->estado=='Aceptado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idingreso.')"><i class="fa fa-eye"></i></button>'.
                    ' <button class="btn btn-danger" onclick="anular('.$reg->idingreso.')"><i class="fa fa-close"></i></button>'.
                    '<a target="_blank" href="'.$url.$reg->idingreso.'"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>':'<a target="_blank" href="'.$url.$reg->idingreso.'"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>',
                "1"=>$reg->idingreso,
                "2"=>$reg->fecha,
                "3"=>$reg->proveedor,
                "4"=>$reg->usuario,
                "5"=>$reg->tipo_comprobante,
                "6"=>$reg->serie_comprobante.'-'.$reg->num_comprobante,
                "7"=>$reg->total_compra,
                "8"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
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
 


     case 'listarND':
     $fecha_inicio_reporte=$_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte=$_REQUEST["fecha_fin_reporte"];
        $rspta=$ingreso->listarND($fecha_inicio_reporte,$fecha_fin_reporte);
        //Vamos a declarar un array 
        $data= Array();  
  
        while ($reg=$rspta->fetch_object()){ 
            $url='../reportes/exOrdenIngresoNotaDebito.php?id=';   
            $data[]=array(
                "0"=>'<a target="_blank" href="'.$url.$reg->idnota_debito.'"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>',
                "1"=>$reg->idnota_debito,
                "2"=>$reg->fechand,
                "3"=>$reg->idingreso,
                "4"=>$reg->fecha, 
                "5"=>$reg->proveedor, 
                "6"=>$reg->usuario,
                "7"=>$reg->usuarioIngreso,
                "8"=>$reg->tipo_comprobante,
                "9"=>$reg->serie_comprobante.'-'.$reg->num_comprobante,
                "10"=>$reg->total_compra,
                "11"=>$reg->motivo_ND,
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
 
 
    case 'listarArticulos':
        $idsucursalOrigen = $_REQUEST["idsucursalOrigen"];
        require_once "../modelos/Articulo.php";
        $articulo=new Articulo();
 
        $rspta=$articulo->listarActivosSucursal($idsucursalOrigen);
        //Vamos a declarar un array
        $data= Array(); 
 
        while ($reg=$rspta->fetch_object()){    
            $nombre = $reg->codigo . ' - ' . $reg->nombre . '';
            $data[]=array(
                "0"=>'<div class="input-group" style="display: inline-flex; margin-right: 5px;">
                        <input type="number" step="any" class="form-control input-sm" id="cantidad_'.$reg->idarticulo.'" 
                        style="width: 70px;" min="1" value="1">
                        <button class="btn btn-warning" onclick="agregarDetalleCanta('.$reg->idarticulo.',
                                                                            \''.$reg->nombre.'\',
                                                                            \''.$reg->descripcion.'\',
                                                                            \''.$reg->precio_venta.'\',
                                                                            \''.$reg->precio_compra.'\',
                                                                            \''.$reg->stock.'\',
                                                                            \''.$reg->precio_ventaNocturno.'\',
                                                                            \''.$reg->precio_rango1_Mecanico.'\',
                                                                            \''.$reg->precio_rango1_Distribuidor.'\',
                                                                            \''.$reg->precio_rango1_Mayorista.'\',
                                                                            \''.$reg->precio_rango2_MecanicoDos.'\',
                                                                            \''.$reg->precio_rango2_DistribuidorDos.'\',
                                                                            \''.$reg->precio_rango2_MayoristaDos.'\',
                                                                            \''.$reg->precio_rango3_MecanicoTres.'\',
                                                                            \''.$reg->precio_rango3_DistribuidorTres.'\',
                                                                            \''.$reg->precio_rango3_MayoristaTres.'\',
                                                                            \''.$reg->nombre_01.'\',
                                                                            \''.$reg->stock_unidad.'\',
                                                                            \''.$reg->precio_unidad.'\',
                                                                            \''.$reg->nombre_02.'\',
                                                                            \''.$reg->stock_blister.'\',
                                                                            \''.$reg->precio_blister.'\',
                                                                            \''.$reg->nombre_03.'\',
                                                                            \''.$reg->stock_caja.'\',
                                                                            \''.$reg->precio_caja.'\',
                                                                            \''.$reg->nombre_04.'\',
                                                                            \''.$reg->stock_fardo.'\',
                                                                            \''.$reg->precio_fardo.'\',
                                                                            \''.$reg->nombre_05.'\',
                                                                            \''.$reg->stock_sacos.'\',
                                                                            \''.$reg->precio_sacos.'\',
                                                                            \''.$reg->nombre_06.'\',
                                                                            \''.$reg->stock_paquete.'\',
                                                                            \''.$reg->precio_paquete.'\',
                                                                            \''.$reg->nombre_07.'\',
                                                                            \''.$reg->stock_07.'\',
                                                                            \''.$reg->precio_07.'\',
                                                                            \''.$reg->nombre_08.'\',
                                                                            \''.$reg->stock_08.'\',
                                                                            \''.$reg->precio_08.'\',
                                                                            \''.$reg->nombre_09.'\',
                                                                            \''.$reg->stock_09.'\',
                                                                            \''.$reg->precio_09.'\',
                                                                            \''.$reg->nombre_10.'\',
                                                                            \''.$reg->stock_10.'\',
                                                                            \''.$reg->precio_10.'\',
                                                                            \''.$reg->nombre_11.'\',
                                                                            \''.$reg->stock_11.'\',
                                                                            \''.$reg->precio_11.'\',
                                                                            \''.$reg->nombre_12.'\',
                                                                            \''.$reg->stock_12.'\',
                                                                            \''.$reg->precio_12.'\',
                                                                            \''.$reg->nombre_13.'\',
                                                                            \''.$reg->stock_13.'\',
                                                                            \''.$reg->precio_13.'\',
                                                                            \''.$reg->nombre_14.'\',
                                                                            \''.$reg->stock_14.'\',
                                                                            \''.$reg->precio_14.'\',
                                                                            \''.$reg->nombre_15.'\',
                                                                            \''.$reg->stock_15.'\',
                                                                            \''.$reg->precio_15.'\',
                                                                            \''.$reg->nombre_16.'\',
                                                                            \''.$reg->stock_16.'\',
                                                                            \''.$reg->precio_16.'\',
                                                                            \''.$reg->nombre_17.'\',
                                                                            \''.$reg->stock_17.'\',
                                                                            \''.$reg->precio_17.'\',
                                                                            \''.$reg->nombre_18.'\',
                                                                            \''.$reg->stock_18.'\',
                                                                            \''.$reg->precio_18.'\',
                                                                            \''.$reg->nombre_19.'\',
                                                                            \''.$reg->stock_19.'\',
                                                                            \''.$reg->precio_19.'\',
                                                                            \''.$reg->nombre_20.'\',
                                                                            \''.$reg->stock_20.'\',
                                                                            \''.$reg->precio_20.'\',
                                                                            \''.$reg->idsucursal.'\',
                                                                            \''.$reg->nom_sucursal.'\',
                                                                            document.getElementById(\'cantidad_'.$reg->idarticulo.'\').value)">
                            <span class="fa fa-plus"></span>
                        </button>
                    </div>',
                "1"=>$nombre,
                "2"=>$reg->categoria,
                "3"=>$reg->codigo,
                "4"=>$reg->stock,
                "5"=>((!empty($reg->imagen) && file_exists("../files/articulos/".$reg->imagen))  ? "<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px'>" 
                            : "<img src='../files/articulos/nofoto.jpg' height='50px' width='50px'>"),    
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
    break;

    case 'listar_preciosxproveedor':
        $rspta=$ingreso->listar_preciosxproveedor();
        //Vamos a declarar un array 
        $data= Array();  
  
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->fecha,
                "1"=>$reg->codigo,
                "2"=>$reg->nombre_articulo,
                "3"=>$reg->totalcantidadpresentacion,
                "4"=>$reg->nombre_categoria,
                "5"=>$reg->precio_compra,
                "6"=>$reg->nombre_proveedor,
                "7"=>$reg->telefono,
                "8"=>$reg->num_documento,
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