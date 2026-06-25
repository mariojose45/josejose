<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


 
require_once "../modelos/Toma_orden.php"; 
 
$tomaorddenes=new TomaoOrdenes();                 
      
$id_add_orden=isset($_POST["id_add_orden"])? limpiarCadena($_POST["id_add_orden"]):"";
$idmesa=isset($_POST["idmesa"])? limpiarCadena($_POST["idmesa"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$codigo_cliente=isset($_POST["codigo_cliente"])? limpiarCadena($_POST["codigo_cliente"]):""; 
$nit=isset($_POST["nit"])? limpiarCadena($_POST["nit"]):"";
$nombre_cliente=isset($_POST["nombre_cliente"])? limpiarCadena($_POST["nombre_cliente"]):"";
$telefono_cliente=0;
$direccion_cliente=isset($_POST["direccion_cliente"])? limpiarCadena($_POST["direccion_cliente"]):"";
$correo_cliente=isset($_POST["correo_cliente"])? limpiarCadena($_POST["correo_cliente"]):"";
$tipo_documento_cliente=isset($_POST["tipo_documento_cliente"])? limpiarCadena($_POST["tipo_documento_cliente"]):"";
$idusuario=$_SESSION["idusuario"]; 
$idcotizacion=0;
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$propina=isset($_POST["propina"])? limpiarCadena($_POST["propina"]):"";
$forma_pago='Efectivo';
$tipo_comprobante='Envio';
$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";
$total_ventades=isset($_POST["total_ventades"])? limpiarCadena($_POST["total_ventades"]):"";
$cefectivo=0; 
$ccredito=0; 
$ctarjeta=0; 
$ctransferencia=0;  
$rescambio=0;
///datos de tarejta
$valor_tarjeta=isset($_POST["valor_tarjeta"])? limpiarCadena($_POST["valor_tarjeta"]):"";
$tipo_pagoBacVisaNet="Seleccione Uno";
$opcionesAdicionales="";
$observacion_credito="";

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
        $comentarios = $datos['comentarios'];

    } else {
        // Manejo del error o asignación de un valor por defecto
        $idarticulo = []; // o cualquier otro valor predeterminado
    } 

 
    
switch ($_GET["op"]){    
    case 'guardaryeditar':
        if (empty($id_add_orden)){ 
            $rspta=$tomaorddenes->insertar($idmesa,$idcliente,$codigo_cliente,$nit,$nombre_cliente,$telefono_cliente,$direccion_cliente,$correo_cliente,$tipo_documento_cliente,$idusuario,$idcotizacion,
                $fecha_hora,$propina,$forma_pago,$tipo_comprobante,$total_venta,$total_ventades,$cefectivo,$ccredito,$ctarjeta,$ctransferencia,$rescambio,$valor_tarjeta,$tipo_pagoBacVisaNet,$opcionesAdicionales,$observacion_credito,$idarticulo,$stockinven,$cantidadpresentacion,$cantidad,$totalcantidadpresentacion,$presen,$precio_ventaSistema,$precio_ventaSistema2,$q_ref,$precio_venta,$precio_recargoPV,
                $precio_recargoQRef,$descuento_porcentaje,$subtotal1,$subtotaldes1,$comentarios);
            echo json_encode($rspta); 
        }
        else {     
            $rspta=$tomaorddenes->editar($id_add_orden,$idmesa,$idcliente,$codigo_cliente,$nit,$nombre_cliente,$telefono_cliente,$direccion_cliente,$correo_cliente,$tipo_documento_cliente,$idusuario,$idcotizacion,
                $fecha_hora,$propina,$forma_pago,$tipo_comprobante,$total_venta,$total_ventades,$cefectivo,$ccredito,$ctarjeta,$ctransferencia,$rescambio,$valor_tarjeta,$tipo_pagoBacVisaNet,$opcionesAdicionales,$observacion_credito,$idarticulo,$stockinven,$cantidadpresentacion,$cantidad,$totalcantidadpresentacion,$presen,$precio_ventaSistema,$precio_ventaSistema2,$q_ref,$precio_venta,$precio_recargoPV,
                $precio_recargoQRef,$descuento_porcentaje,$subtotal1,$subtotaldes1,$comentarios);
            echo json_encode($rspta);              
        }
    break;
 
    case 'anular':
        $motivo=$_POST["motivo"];
        $rspta=$tomaorddenes->anular($id_add_orden,$motivo);
        echo $rspta ? "Orden de Mesa anulada" : "Orden de Mesa no se puede anular";
    break;

    case 'eliminarOrdendetallebitacora':
    $iddetalle_add_orden=$_POST["iddetalle_add_orden"];
    $valormotivo=$_POST["valormotivo"]; 
        $rspta=$tomaorddenes->eliminarOrdendetallebitacora($iddetalle_add_orden,$valormotivo);
        //Codificar el resultado utilizando json
        echo json_encode($rspta); 
    break;      

    case 'mostrarCobro':
        $id_add_orden=$_POST["idcotizacion"];
        $rspta=$tomaorddenes->mostrar($id_add_orden); 
        //Codificar el resultado utilizando json
        echo json_encode($rspta);  
    break;  

    case 'mostrar':
        $rspta=$tomaorddenes->mostrar($id_add_orden); 
        //Codificar el resultado utilizando json
        echo json_encode($rspta);  
    break;   
    

    case 'detalleorden':
        $cotiz=$tomaorddenes->detalleorden($id_add_orden);
        echo json_encode($cotiz); 
    break;        
 

    case 'detalleorden2':
        $id_add_orden=$_POST["idcotizacion"];
        $cotiz=$tomaorddenes->detalleorden($id_add_orden);
        echo json_encode($cotiz); 
    break;       
 
 
 
    case 'listarMesas':
        $rspta=$tomaorddenes->listarMesas(); 
        //Vamos a declarar un array
 
        $data= Array(); 
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?
                '<button class="btn btn-app" onclick="asignaciontomaorden('.$reg->idmesa.')">
                    <img class="iconos-tama efecto" src="../public/iconos/comedor.png" alt="Mesa" style="width:64px; height:64px;">
                </button>':
                '<button class="btn btn-app" onclick="asignaciontomaorden('.$reg->idmesa.')">
                    <img class="iconos-tama efecto" src="../public/iconos/comedor.png" alt="Mesa Ocupada" style="width:64px; height:64px;">
                </button>',
                "1"=>$reg->nombre,
                "2"=>($reg->condicion=='1')?'<span class="label bg-green">DISPONIBLE</span>':
        '<span class="label bg-red">OCUPADA</span>'
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;

    case 'listarOrddenes':
        $rspta=$tomaorddenes->listarOrddenes();
        //Vamos a declarar un array
        $data= Array();   
 
        while ($reg=$rspta->fetch_object()){ 
            $data[]=array(
                "0"=>
                '<button class="btn btn-app" onclick="obtenerOrden('.$reg->id_add_orden.')">
                    <img class="iconos-tama efecto" src="../public/iconos/mesa2.png" alt="Mesa" style="width:60px; height:60px;">
                </button>'.
                '<button class="btn btn-app" onclick="anular('.$reg->id_add_orden.')">
                    <img class="iconos-tama efecto" src="../public/iconos/borrar.png" alt="Eliminar Mesa" style="width:60px; height:60px;">
                </button>'.
                '<button class="btn btn-app" onclick="impresiondeformatos('.$reg->id_add_orden.')">
                    <img class="iconos-tama efecto" src="../public/iconos/impresoras.png" alt="Imprimir Comanda" style="width:60px; height:60px;">
                </button>',
                "1"=>$reg->nombre,
                "2"=>"Q ".$reg->total_venta, 
                "3"=>($reg->condicion=='1')?'<span class="label bg-green">DISPONIBLE</span>':
        '<span class="label bg-red">OCUPADA</span>',
                "4"=>$reg->id_add_orden,
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;  
  



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