<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

 
require_once "../modelos/Devolucion.php"; 
 
$devo=new Devolucio();                 
      
$idventa=isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$codigo_cliente=isset($_POST["codigo_cliente"])? limpiarCadena($_POST["codigo_cliente"]):""; 
$nit=isset($_POST["nit"])? limpiarCadena($_POST["nit"]):"";
$nombre_cliente=isset($_POST["nombre_cliente"])? limpiarCadena($_POST["nombre_cliente"]):"";
$telefono_cliente=isset($_POST["telefono_cliente"])? limpiarCadena($_POST["telefono_cliente"]):"";
$direccion_cliente=isset($_POST["direccion_cliente"])? limpiarCadena($_POST["direccion_cliente"]):"";
$correo_cliente=isset($_POST["correo_cliente"])? limpiarCadena($_POST["correo_cliente"]):"";
$tipo_documento_cliente=isset($_POST["tipo_documento_cliente"])? limpiarCadena($_POST["tipo_documento_cliente"]):"";
$idusuario=$_SESSION["idusuario"]; 
$idcotizacion=isset($_POST["idcotizacion"])? limpiarCadena($_POST["idcotizacion"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$fecha_horaNC=isset($_POST["fecha_horaNC"])? limpiarCadena($_POST["fecha_horaNC"]):"";
$forma_pago=isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$razon_devolucion=isset($_POST["razon_devolucion"])? limpiarCadena($_POST["razon_devolucion"]):"";
$tipo_comprobanteNC=isset($_POST["tipo_comprobanteNC"])? limpiarCadena($_POST["tipo_comprobanteNC"]):"";
 

$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";
$total_ventades=isset($_POST["total_ventades"])? limpiarCadena($_POST["total_ventades"]):"";
$cefectivo=0; 
$ccredito=0; 
$ctarjeta=0; 
$ctransferencia=0; 
$ctransferencia=0; 
$cNotaCredito=isset($_POST["cNotaCredito"])? limpiarCadena($_POST["cNotaCredito"]):"";
$rescambio=0;
 
///datos de tarejta
$valor_tarjeta=isset($_POST["valor_tarjeta"])? limpiarCadena($_POST["valor_tarjeta"]):"";
$tipo_pagoBacVisaNet=0;
$opcionesAdicionales=0;
$observacion_credito=0;

    /*    
        idarticulo: [],
        stockinven: [],        
        cantidadpresentacion: [],
        cantidad: [],
        totalcantidadpresentacion: [],
        presen: [],
        precio_ventaSistema: [],
        precio_ventaSistema2: [],
        q_ref: [],
        precio_venta: [],
        precio_recargoPV: [],
        precio_recargoQRef: [],
        descuento_porcentaje: [],
        subtotal1: [],
        subtotaldes1: []
    */
        

//Nueva Captura de datos de detalles
    if (isset($_POST['datos1'])) {
        $datos = json_decode($_POST['datos1'], true); 
        $idarticulo = $datos['idarticulo'];
        $stockinven = $datos['stockinven'];
        $cantidadpresentacion = $datos['cantidadpresentacion'];
        $cantidad = $datos['cantidad'];
        $totalcantidadpresentacion = $datos['totalcantidadpresentacion'];
        $presen = $datos['presen'];
        $precio_ventaSistema = $datos['precio_ventaSistema'];
        $precio_ventaSistema2 = $datos['precio_ventaSistema2'];
        $q_ref = $datos['q_ref'];        
        $precio_venta = $datos['precio_venta'];
        $precio_recargoPV = $datos['precio_recargoPV'];
        $precio_recargoQRef = $datos['precio_recargoQRef'];
        $descuento_porcentaje = $datos['descuento_porcentaje'];
        $subtotal1 = $datos['subtotal1'];
        $subtotaldes1 = $datos['subtotaldes1'];

    } else {
        // Manejo del error o asignación de un valor por defecto
        $idarticulo = []; // o cualquier otro valor predeterminado
    } 

 
   
switch ($_GET["op"]){    
    case 'guardaryeditar':
            $rspta=$devo->insertar($idventa,$idcliente,$codigo_cliente,$nit,$nombre_cliente,$telefono_cliente,$direccion_cliente,$correo_cliente,$tipo_documento_cliente,$idusuario,$idcotizacion,$fecha_hora,$forma_pago,$tipo_comprobante,$total_venta,$total_ventades,$cefectivo,$ccredito,$ctarjeta,$ctransferencia,$rescambio,$valor_tarjeta,$tipo_pagoBacVisaNet,$opcionesAdicionales,$observacion_credito,
                $idarticulo,
                $stockinven,
                $cantidadpresentacion,
                $cantidad,
                $totalcantidadpresentacion,
                $presen,
                $precio_ventaSistema,
                $precio_ventaSistema2,
                $q_ref,
                $precio_venta,
                $precio_recargoPV,
                $precio_recargoQRef,
                $descuento_porcentaje,
                $subtotal1,
                $subtotaldes1,$fecha_horaNC,$razon_devolucion,$tipo_comprobanteNC,$cNotaCredito);
            echo json_encode($rspta); 
    break;
 
    case 'anular':
        $rspta=$devo->anular($idventa);
        echo $rspta ? "Venta anulada" : "Venta no se puede anular";
    break;
  

    case 'mostrar':
        $rspta=$devo->mostrar($idcotizacion); 
        //Codificar el resultado utilizando json
        echo json_encode($rspta);  
    break;  

    case 'paraventa':
        $cotiz=$devo->detallecotizacionparaventa($idcotizacion);
        echo json_encode($cotiz);  
    break;  


 
    case 'listar':

        $fecha_inicio_reporte=$_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte=$_REQUEST["fecha_fin_reporte"];

        $rspta=$devo->listar($fecha_inicio_reporte,$fecha_fin_reporte);
        //Vamos a declarar un array
        $data= Array(); 
 
        while ($reg=$rspta->fetch_object()){ 
 
                if ($reg->tipo_comprobante=='Factura') {
                    # code... 
                    $url='../reportes/exTicket_Fel.php?id=';
                    $url2='../reportes/exTicket_Fel58mm.php?id=';
                    $url3='../reportes/exVentaFormatoCarta_Fel.php?id=';

                }                 
                else 
                { 
                    $url='../reportes/exTicket.php?id='; 
                    $url2='../reportes/exTicket58mm.php?id=';
                    $url3='../reportes/exVentaFormatoCarta.php?id=';
                }                                
 
                if ($reg->estado=='Aceptado') {
                    # code... 
                    $resventa=$reg->total_venta; 
                    $resventades=$reg->total_ventades;  
 
                }                 
                else
                { 
                    $resventa=0;
                    $resventades=0;
                }

                if ($reg->forma_pago=='Tarjeta') {
                    # code... 
                    $resDatostarjeta=$reg->tipo_pagoBacVisaNet." / ".$reg->opcionesAdicionales." / ".$reg->valor_tarjeta;

                }else{
                    $resDatostarjeta=" ";
                } 

                    // Validamos si la fecha de certificación es '0000-00-00 00:00:00'
                $fecha_certificacion = ($reg->fechaCertificacion_ecoFactura == '0000-00-00 00:00:00') ? 'No certificado' : $reg->fechaCertificacion_ecoFactura;
                                
 
           $data[]=array(
                "0"=>($reg->estado=='Aceptado')?' <button class="btn btn-danger" onclick="anular('.$reg->idventa.')"><i class="fa fa-close"></i></button>' .
                    '<a target="_blank" href="'.$url.$reg->idventa.'" title="Ticket 79mm"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url2.$reg->idventa.'"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url3.$reg->idventa.'"  title="Carta"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>':
                    '<a target="_blank" href="'.$url.$reg->idventa.'"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url2.$reg->idventa.'"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" href="'.$url3.$reg->idventa.'"  title="Carta"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>',
                "1"=>$reg->idventa,
                "2"=>$reg->cliente,
                "3"=>$reg->usuario,
                "4"=>$reg->tipo_comprobante,
                "5"=>$reg->num_comprobante,
                "6"=>$resventa, 
                "7"=>$resventades, 
                "8"=>$reg->forma_pago." - ".$resDatostarjeta,
                "9"=>$reg->cefectivo,
                "10"=>$reg->ctarjeta,
                "11"=>$reg->ccredito,
                "12"=>$reg->ctransferencia,
                "13"=>$reg->rescambio,
                "14"=>$reg->fecha,
                "15"=>$fecha_certificacion,
                "16"=>$reg->serie_ecoFactura,
                "17"=>$reg->numero_ecoFactura,
                "18"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
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
  
  /*  case 'selectCliente':
        require_once "../modelos/Persona.php";
        $persona = new Persona();
 
        $rspta = $persona->listarclientes();
 
        while ($reg = $rspta->fetch_object())
                {
                echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '--'.$reg->direccion.'--'.$reg->num_documento.'</option>';
                }
    break;

    case 'selectClientep':
        require_once "../modelos/Persona.php";
        $persona = new Persona();
 
        $rspta = $persona->listarp();
 
        while ($reg = $rspta->fetch_object())
                {
                echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '--'.$reg->direccion.'</option>';
                }
    break;    */



    case 'buscararticulocodebar':
        require_once "../modelos/Articulo.php";
        $articulo=new Articulo();
        $codigo=$_REQUEST["codigo"];
        $rspta=$articulo->ObtenerProductoBarCode($codigo);
        while ($reg=$rspta->fetch_object()){
            echo $reg->idarticulo.'@'.$reg->nombre.'@'.$reg->precio_venta.'@'.$reg->stock.'@'.$reg->descuento_porcentaje.'@'.$reg->stock_unidad.'@'.$reg->precio_unidad.'@'.$reg->stock_blister.'@'.$reg->precio_blister.'@'.$reg->stock_caja.'@'.$reg->precio_caja.'@'.$reg->stock_fardo.'@'.$reg->precio_fardo.'@'.$reg->stock_sacos.'@'.$reg->precio_sacos.'@'.$reg->stock_paquete.'@'.$reg->precio_paquete.'@'.$reg->precio_rango1.'@'.$reg->precio_rango2.'@'.$reg->precio_rango3 ;
        }
    break;

    
 
case 'listarArticulosxcategoria':
    require_once "../modelos/Articulo.php";
    $articulo = new Articulo();
    $idcategoria = $_GET["idcategoria"];

    $rspta = $articulo->listarActivosVentacategoria($idcategoria);
    
    // Array de datos
    $data = array();

    while ($reg = $rspta->fetch_object()) {
        $imagen = !empty($reg->imagen) && file_exists('../files/articulos/' . $reg->imagen) 
            ? '../files/articulos/' . $reg->imagen 
            : '../files/articulos/nofoto.jpg';
        
        $data[] = array(
            "0" => '<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.str_replace('"', 't.t', $reg->nombre).'\',
                                                                            \''.$reg->precio_venta.'\',
                                                                            \''.$reg->stock.'\',
                                                                            \''.$reg->descuento_porcentaje.'\',
                                                                            \''.$reg->stock_unidad.'\',
                                                                            \''.$reg->precio_unidad.'\',
                                                                            \''.$reg->stock_blister.'\',
                                                                            \''.$reg->precio_blister.'\',
                                                                            \''.$reg->stock_caja.'\',
                                                                            \''.$reg->precio_caja.'\',
                                                                            \''.$reg->stock_fardo.'\',
                                                                            \''.$reg->precio_fardo.'\',
                                                                            \''.$reg->stock_sacos.'\',
                                                                            \''.$reg->precio_sacos.'\',
                                                                            \''.$reg->stock_paquete.'\',
                                                                            \''.$reg->precio_paquete.'\',
                                                                            \''.$reg->precio_rango1.'\',
                                                                            \''.$reg->precio_rango2.'\',
                                                                            \''.$reg->precio_rango3.'\')"><span class="fa fa-plus"> Agregar Item</span></button>',
            "1" => $reg->nombre,
            "2" => $reg->precio_venta,
            "3" => "<img src='" . $imagen . "' height='150px' width='150px'>"
        );
    }

    echo json_encode($data);
    break;

       

    case 'listarArticulosVenta':
        require_once "../modelos/Articulo.php";
        $articulo=new Articulo();
        $rspta=$articulo->listarActivosVenta();
        //Vamos a declarar un array
        $data= Array(); 
 
        while ($reg=$rspta->fetch_object()){ 
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.str_replace('"', 't.t', $reg->nombre).'\',
                                                                            \''.$reg->precio_venta.'\',
                                                                            \''.$reg->stock.'\',
                                                                            \''.$reg->descuento_porcentaje.'\',
                                                                            \''.$reg->stock_unidad.'\',
                                                                            \''.$reg->precio_unidad.'\',
                                                                            \''.$reg->stock_blister.'\',
                                                                            \''.$reg->precio_blister.'\',
                                                                            \''.$reg->stock_caja.'\',
                                                                            \''.$reg->precio_caja.'\',
                                                                            \''.$reg->stock_fardo.'\',
                                                                            \''.$reg->precio_fardo.'\',
                                                                            \''.$reg->stock_sacos.'\',
                                                                            \''.$reg->precio_sacos.'\',
                                                                            \''.$reg->stock_paquete.'\',
                                                                            \''.$reg->precio_paquete.'\',
                                                                            \''.$reg->precio_rango1.'\',
                                                                            \''.$reg->precio_rango2.'\',
                                                                            \''.$reg->precio_rango3.'\'
                                                                            )"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->nombre,
                "2"=>$reg->categoria,
                "3"=>$reg->codigo,
                "4"=>$reg->stock,
                "5"=>($reg->stockminimo <=$reg->stock )?'<span class="label bg-green">Stock Normal</span>':
                '<span class="label bg-red">Stock Bajo</span>',
                "6"=>$reg->precio_venta,
                "7"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >"
                );
        } 
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
    break;





    case 'listarArticulosVenta2':
        require_once "../modelos/Articulo.php";
        $articulo=new Articulo();
        $rspta=$articulo->listarActivosVenta();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){ 
            $data[]=array(
                "value"=>$reg->nombre,
                "label"=>$reg->nombre,
                "desc"=>$reg->nombre, 
                "icon"=>$reg->imagen,
                "idarticulo"=>$reg->idarticulo,
                "nombre"=>$reg->nombre,
                "precio_venta"=>$reg->precio_venta,
                "descuento_porcentaje"=>$reg->descuento_porcentaje,
                "stock"=>$reg->stock,
                "img"=>$reg->imagen,
                "stock_unidad"=>$reg->stock_unidad,
                "precio_unidad"=>$reg->precio_unidad,
                "stock_blister"=>$reg->stock_blister,
                "precio_blister"=>$reg->precio_blister,
                "stock_caja"=>$reg->stock_caja,
                "precio_caja"=>$reg->precio_caja,
                "stock_fardo"=>$reg->stock_fardo,
                "precio_fardo"=>$reg->precio_fardo,
                "stock_sacos"=>$reg->stock_sacos,
                "precio_sacos"=>$reg->precio_sacos,
                "stock_paquete"=>$reg->stock_paquete,
                "precio_paquete"=>$reg->precio_paquete,
                "precio_rango1"=>$reg->precio_rango1,
                "precio_rango2"=>$reg->precio_rango2,
                "precio_rango3"=>$reg->precio_rango3
                );
        }
        $results = $data;
        echo json_encode($results);
    break;


}
?> 