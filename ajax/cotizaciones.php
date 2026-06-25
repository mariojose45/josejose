<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

 
require_once "../modelos/Cotizaciones.php";
     
$cotizaciones=new Cotizaciones();       
   
$idcotizacion=isset($_POST["idcotizacion"])? limpiarCadena($_POST["idcotizacion"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$codigo_cliente=isset($_POST["codigo_cliente"])? limpiarCadena($_POST["codigo_cliente"]):"";
$nit=isset($_POST["nit"])? limpiarCadena($_POST["nit"]):"";
$nombre_cliente=isset($_POST["nombre_cliente"])? limpiarCadena($_POST["nombre_cliente"]):"";
$telefono_cliente=isset($_POST["telefono_cliente"])? limpiarCadena($_POST["telefono_cliente"]):"";
$direccion_cliente=isset($_POST["direccion_cliente"])? limpiarCadena($_POST["direccion_cliente"]):"";
$correo_cliente=isset($_POST["correo_cliente"])? limpiarCadena($_POST["correo_cliente"]):"";
$tipo_cliente=isset($_POST["tipo_cliente"])? limpiarCadena($_POST["tipo_cliente"]):"";
$valor_tarjeta=isset($_POST["valor_tarjeta"])? limpiarCadena($_POST["valor_tarjeta"]):"";
$tipo_documento_cliente=isset($_POST["tipo_documento_cliente"])? limpiarCadena($_POST["tipo_documento_cliente"]):"";
$idusuario=$_SESSION["idusuario"]; 
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$forma_pago=isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$tipo_pagoBacVisaNet=isset($_POST["tipo_pagoBacVisaNet"])? limpiarCadena($_POST["tipo_pagoBacVisaNet"]):"";
$opcionesAdicionales=isset($_POST["opcionesAdicionales"])? limpiarCadena($_POST["opcionesAdicionales"]):"";
$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";
$total_ventades=isset($_POST["total_ventades"])? limpiarCadena($_POST["total_ventades"]):"";
$idvendedor=isset($_POST["idvendedor"])? limpiarCadena($_POST["idvendedor"]):"";


$forma_productos=isset($_POST["forma_productos"])? limpiarCadena($_POST["forma_productos"]):"";
$comentario_cotizacion=isset($_POST["comentario_cotizacion"])? limpiarCadena($_POST["comentario_cotizacion"]):"";

$destino=isset($_POST["destino"])? limpiarCadena($_POST["destino"]):"";


//Nueva Captura de datos de detalles
    if (isset($_POST['datos1'])) {
        $datos = json_decode($_POST['datos1'], true); 
        $idarticulo = $datos['idarticulo'];
        $precio_compra = $datos['precio_compra'];
        $stockinven = $datos['stockinven'];
        $cantidadpresentacion = $datos['cantidadpresentacion'];
        $cantidad = $datos['cantidad'];
        $totalcantidadpresentacion = $datos['totalcantidadpresentacion'];
        $presentacion = $datos['presentacion'];
        $presen = $datos['presen'];
        $precio_ventaSistema = $datos['precio_ventaSistema'];
        $precio_ventaSistema2 = $datos['precio_ventaSistema2'];
        $q_ref = $datos['q_ref'];        
        $precio_venta = $datos['precio_venta'];
        $precio_recargoPV = $datos['precio_recargoPV'];
        $precio_recargoQRef = $datos['precio_recargoQRef'];
        $descuento_permitido = $datos['descuento_permitido'];
        $descuento_porcentaje = $datos['descuento_porcentaje'];
        $descripcion_detalle = $datos['descripcion_detalle'];
        $subtotal1 = $datos['subtotal1'];
        $subtotaldes1 = $datos['subtotaldes1'];

    } else {
        // Manejo del error o asignación de un valor por defecto
        $idarticulo = []; // o cualquier otro valor predeterminado
    } 
 
switch ($_GET["op"]){   
    case 'guardaryeditar': 
        if(isset($_POST['datosArticuloscot'])){
            $datosArticulos = json_decode($_POST['datosArticuloscot'], true);
        }
        if (empty($idcotizacion)){ 
            $rspta=$cotizaciones->insertar($idcliente,$codigo_cliente,$nit,$nombre_cliente,$telefono_cliente,$direccion_cliente,$correo_cliente,$valor_tarjeta,
                $tipo_documento_cliente,$idusuario,$fecha_hora,$forma_pago,$tipo_comprobante,$tipo_pagoBacVisaNet,$opcionesAdicionales,$total_venta,$total_ventades,
                $idvendedor,$tipo_cliente,$forma_productos,$comentario_cotizacion,$destino,$datosArticulos);
            echo $rspta; 
        }
        else {
            $rspta=$cotizaciones->editar($idcotizacion,$idcliente,$codigo_cliente,$nit,$nombre_cliente,$telefono_cliente,$direccion_cliente,$correo_cliente,$valor_tarjeta,
                $tipo_documento_cliente,$idusuario,$fecha_hora,$forma_pago,$tipo_comprobante,$tipo_pagoBacVisaNet,$opcionesAdicionales,$total_venta,$total_ventades,
                $idvendedor,$tipo_cliente,$forma_productos,$comentario_cotizacion,$destino,$datosArticulos);
            echo $rspta;             
        }
    break;
 
    case 'anular':
        $rspta=$cotizaciones->anular($idcotizacion);
        echo $rspta ? "Cotizacion anulada" : "Cotizacion no se puede anular";
    break; 

    case 'listo':
        $rspta=$cotizaciones->listo($idcotizacion);
        echo $rspta ? "Pedido Enviada" : "Pedido no se puede enviar";
    break;     
 
    case 'mostrarVentaAdministrador':
        $rspta=$cotizaciones->mostrarVentaAdministrador($idventa_administrador); 
        //Codificar el resultado utilizando json
        echo json_encode($rspta);  
        
    break; 
 

    case 'mostrar':
        $rspta=$cotizaciones->mostrar($idcotizacion); 
        //Codificar el resultado utilizando json
        echo json_encode($rspta);  
    break;
 
    case 'mostrarVenta':
        $rspta=$cotizaciones->mostrarVenta($idcotizacion); 
        //Codificar el resultado utilizando json
        echo json_encode($rspta);  
    break;    
 
    case 'paraventa': 
        $cotiz=$cotizaciones->detallecotizacionparaventa($idcotizacion);
        echo json_encode($cotiz); 
    break;  
       

    case 'paraventaVenta':
        $cotiz=$cotizaciones->detallecotizacionparaventaVenta($idcotizacion);
        echo json_encode($cotiz); 
    break;  
       


    case 'paraventaAdministrador':
        $cotiz=$cotizaciones->detallecotizacionparaventaadministrador($idventa_administrador);
        echo json_encode($cotiz);
    break;        
 

 
    case 'listar':
        $fecha_inicio_reporte=$_REQUEST["fecha_inicio_reporte"]; 
        $fecha_fin_reporte=$_REQUEST["fecha_fin_reporte"];    

        $rspta=$cotizaciones->listar($fecha_inicio_reporte,$fecha_fin_reporte);
        //Vamos a declarar un array
        $data= Array();
  
        while ($reg=$rspta->fetch_object()){ 

                    $url='../reportes/exCotizacion.php?id='; 
                    $url2='../reportes/exCotizacion58mm.php?id=';   
                    $url3='../reportes/exCotizacionCarta.php?id=';   

            $data[]=array(
                "0"=>(($reg->estado=='Aceptado')?'<button class="btn btn-warning" onclick="obtenerClienteCotizacion('.$reg->idcotizacion.')"><i class="fa fa-eye"></i></button>'.
                    ' <button class="btn btn-danger" onclick="anular('.$reg->idcotizacion.')"><i class="fa fa-close"></i></button>'.
                    '<a target="_blank" title="Ticket 79mm" href="'.$url.$reg->idcotizacion.'"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" title="Ticket 58mm" href="'.$url2.$reg->idcotizacion.'"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                    '<a target="_blank" title="Carta" href="'.$url3.$reg->idcotizacion.'"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>':
                    '<a target="_blank" title="Ticket 79mm" href="'.$url.$reg->idcotizacion.'"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                '<a target="_blank" title="Ticket 58mm" href="'.$url2.$reg->idcotizacion.'"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'.
                '<a target="_blank" title="Carta" href="'.$url3.$reg->idcotizacion.'"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>'),
                "1"=>$reg->num_comprobante,
                "2"=>$reg->cliente,
                "3"=>$reg->usuario,
                "4"=>$reg->total_venta,
                "5"=>$reg->total_ventades,
                "6"=>$reg->fecha,
                "7"=>$reg->cobradosino,
                "8"=>$reg->idcotizacion,   
                "9"=>$reg->forma_pago,                
                "10"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
                '<span class="label bg-red">Anulado</span>',
                "11"=>$reg->destino,       
                "12"=>$reg->comentario_cotizacion
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;

    case 'listarxfechasucursal':

        $fecha_inicio=$_REQUEST["fecha_inicio"]; 
        $fecha_fin=$_REQUEST["fecha_fin"];
        $idsucursal=$_REQUEST["idsucursal"];

        $rspta=$cotizaciones->listarxfechasucursal($fecha_inicio,$fecha_fin,$idsucursal);
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){ 

                    $url='../reportes/exCotizacion.php?id=';   

            $data[]=array(
                "0"=>'<a target="_blank" href="'.$url.$reg->idcotizacion.'"><button class="btn btn-info"><i class="fa fa-file"></i> </button> </a>',
                "1"=>$reg->num_comprobante,
                "2"=>$reg->cliente,
                "3"=>$reg->usuario,
                "4"=>$reg->total_venta,
                "5"=>$reg->total_ventades,
                "6"=>$reg->fecha,
                "7"=>$reg->cobradosino,
                "8"=>$reg->idcotizacion,   
                "9"=>$reg->forma_pago,                
                "10"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
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


    case 'listarxfechasucursalDetalle':

        $fecha_inicio=$_REQUEST["fecha_inicio"]; 
        $fecha_fin=$_REQUEST["fecha_fin"];
        $idsucursal=$_REQUEST["idsucursal"];

        $rspta=$cotizaciones->listarxfechasucursalDetalle($fecha_inicio,$fecha_fin,$idsucursal);
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){ 
  

            $data[]=array(
                "0"=>$reg->idcotizacion,
                "1"=>$reg->num_comprobante,
                "2"=>$reg->codigo,
                "3"=>$reg->articulo,
                "4"=>$reg->precio_venta,
                "5"=>$reg->descuento,
                "6"=>$reg->subtotal
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
 
        $rspta = $persona->listarC();
 
        while ($reg = $rspta->fetch_object())
                {
                echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '--'.$reg->direccion.'</option>';
                }
    break;

    case 'buscararticulocodebar':
        require_once "../modelos/Articulo.php";
        $articulo=new Articulo();
        $codigo=$_REQUEST["codigo"];
        $rspta=$articulo->ObtenerProductoBarCode($codigo);
        while ($reg=$rspta->fetch_object()){
            echo $reg->idarticulo.'@'.$reg->nombre.'@'.$reg->precio_venta;
        }
    break;
    
 
    case 'listarArticulosVenta':
        require_once "../modelos/Articulo.php";
        $articulo=new Articulo();
        $idcliente=$_GET["idcliente"]; 
        $rspta=$articulo->listarActivosVenta($idcliente);
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\',\''.$reg->precio_venta.'\',\''.$reg->stock.'\',\''.$reg->descuento_porcentaje.'\')"><span class="fa fa-plus"></span></button>',
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
        $idcliente=$_POST["idcliente"];
        $rspta=$articulo->listarActivosVenta($idcliente);
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
                "img"=>$reg->imagen
                );
        }
        $results = $data;
        echo json_encode($results);
    break;

    case "selectVendedor":
        require_once "../modelos/Vendedores.php";
        $vendedor = new Vendedores();
 
        $rspta = $vendedor->select();
 
        while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idvendedor . '>' . $reg->nombre . ' -- ' . $reg->telefono . '</option>';
                }
    break;

    case 'mostrarTaller':
        $rspta=$cotizaciones->mostrarTaller($idcotizacion); 
        //Codificar el resultado utilizando json
        echo json_encode($rspta);  
    break;

    case 'detalle_taller': 
        $cotiz=$cotizaciones->detalle_taller($idcotizacion);
        echo json_encode($cotiz); 
    break;



    case 'listarpantallacotizaciones':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $tipo_envioPedidos = $_REQUEST["tipo_envioPedidos"];

        $rspta = $cotizaciones->listarCabeceraspantalla($fecha_inicio, $fecha_fin, $tipo_envioPedidos);
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            // 🔹 Traer el detalle de esta cabecera
            $detalle = array();
            $rsptaDetalle = $cotizaciones->cotizaciondetallepantalla($reg->idcotizacion);
            while ($det = $rsptaDetalle->fetch_object()) {
                $detalle[] = array(
                    "iddetalle_cotizacion" => $det->iddetalle_cotizacion,
                    "cantidad"             => $det->cantidad,
                    "nombre_articulo"      => $det->articulo,
                    "descripcion_detalle"  => $det->descripcion_detalle,
                    "tipo"                 => $det->tipo,
                    "idarticulo"           => (int)$det->idarticulo,
                    "idarticulopadre"      => (int)$det->idarticulopadre
                );
            }

            // 🔹 Cabecera + detalle
            $data[] = array(
                "idcotizacion"   => $reg->idcotizacion,
                "fecha"          => $reg->fecha,
                "nombre"         => $reg->cliente,
                "direccion"      => $reg->direccion,
                "telefono"       => $reg->telefono,
                "detalle"        => $detalle
            );
        }

        echo json_encode($data);
    break;

    case 'listarCotizacionesPendientes':
        require_once "../modelos/Consultas.php";
        $consulta = new Consultas();
        $rspta = $consulta->totalcotizaconesGeneralDetalle();
        $data = array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "idcotizacion" => $reg->idcotizacion,
                "nombre"      => $reg->nombre,
                "total_venta"  => number_format($reg->total_venta, 2)
            );
        }
        echo json_encode($data);
    break;

    case 'listarCotizacionesTiendaWeb':
        require_once "../modelos/Consultas.php";
        $consulta = new Consultas();
        $rspta = $consulta->totalcotizaconesGeneral2Detalle();
        $data = array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "idcotizacion" => $reg->idcotizacion,
                "nombre"      => $reg->nombre,
                "total_venta"  => number_format($reg->total_venta, 2)
            );
        }
        echo json_encode($data);
    break;

    case 'obtenerTotalesCotizaciones':
        require_once "../modelos/Consultas.php";
        $consulta = new Consultas();
        
        // Total cotizaciones pendientes
        $rsptacotizaciones = $consulta->totalcotizaconesGeneral();
        $regcotizaciones = $rsptacotizaciones->fetch_object();
        $totalPendientes = $regcotizaciones->total_cotizaciones;
        
        // Total cotizaciones tienda web
        $rsptacotizaciones2 = $consulta->totalcotizaconesGeneral2();
        $regcotizaciones2 = $rsptacotizaciones2->fetch_object();
        $totalTiendaWeb = $regcotizaciones2->total_cotizaciones;
        
        echo json_encode(array(
            "totalPendientes" => $totalPendientes,
            "totalTiendaWeb" => $totalTiendaWeb
        ));
    break;


}
?> 