<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Ordenes_compra
{
    //Implementamos nuestro constructor
    public function __construct() {}

    //Implementamos un método para insertar registros
    public function insertar(
        $idcliente,
        $codigo_cliente,
        $nit,
        $nombre_cliente,
        $telefono_cliente,
        $direccion_cliente,
        $correo_cliente,
        $tipo_documento_cliente,
        $idusuario,
        $tipo_comprobante,
        $serie_comprobante,
        $num_comprobante,
        $fecha_hora,
        $impuesto,
        $total_compra,
        $total_comprades,
        $forma_pago,
        $dias_credito,
        $fecha_hora_pago_credito,
        $direccion_entrega_orden_compra,
        $fecha_entrega_orden_compra,
        $observacion_orden_compra,
        $tipo_ingreso_producion,
        $datosArticulos,
        $total_compra_r,
        $total_comprades_r
    ) {

        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');
        /////CAPTURA DE CLIENTE NUEVO Y UPDATE 
        if ($idcliente == '0') {
            $sqlcorrelativo = "UPDATE add_correlativo SET codigo_cliente=codigo_cliente+1 WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
            ejecutarConsulta($sqlcorrelativo);

            $sqlCorre = "SELECT * FROM add_correlativo WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
            $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
            $corre = $correlativo["codigo_cliente"];
            $codigo_cliente = 'COD' . $corre;

            $sqlcliente = "INSERT INTO persona (tipo_persona,
                                                    nombre,
                                                    tipo_documento,
                                                    num_documento,
                                                    direccion,
                                                    telefono, 
                                                    email,
                                                    tipo_cliente,
                                                    codigo_cliente,fechaCreacion)
                                            VALUES ('Proveedor',
                                                    '$nombre_cliente',
                                                    '$tipo_documento_cliente',
                                                    '$nit',
                                                    '$direccion_cliente',
                                                    '$telefono_cliente',
                                                    '$correo_cliente',
                                                    'PUBLICO',
                                                    '$codigo_cliente','$fechaHora')";
            $residcliente = ejecutarConsulta_retornarID($sqlcliente);

            if (!$residcliente) {
                throw new Exception("Error al insertar nuevo cliente.");
            }
        } else {
            $sqlCorre = "SELECT * FROM persona WHERE idpersona='$idcliente'";
            $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
            $corre = $correlativo["codigo_cliente"];

            // Verificamos si $corre es '0', está vacío o es null
            if (empty($corre) || $corre == '0') {
                // Si está vacío, null, o es '0', ejecutamos la lógica de actualización del código cliente

                $sqlcorrelativo = "UPDATE add_correlativo SET codigo_cliente=codigo_cliente+1 WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
                ejecutarConsulta($sqlcorrelativo);

                $sqlCorrelativo = "SELECT * FROM add_correlativo WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
                $correlativos = ejecutarConsultaSimpleFila($sqlCorrelativo);
                $corress = $correlativos["codigo_cliente"];
                $codigo_clientes = 'COD' . $corress;

                $sqlupdadtepersona = "UPDATE persona SET codigo_cliente='$codigo_clientes' WHERE idpersona='$idcliente'";
                ejecutarConsulta($sqlupdadtepersona);
            }

            $sqlcorrelativo = "UPDATE persona SET 
                                            direccion='$direccion_cliente',
                                            telefono='$telefono_cliente',
                                            email='$correo_cliente',
                                            tipo_documento='$tipo_documento_cliente',
                                            nombre='$nombre_cliente'
                                    WHERE idpersona='$idcliente'";

            ejecutarConsulta($sqlcorrelativo);
            $residcliente = $idcliente;
        }
        ///////           

        $sql = "INSERT INTO orden_compra (idproveedor,idusuario,tipo_comprobante,serie_comprobante,num_comprobante,
                                        fecha_hora,impuesto,total_compra,forma_pago,dias_credito,fecha_hora_pago_credito,direccion_entrega_orden_compra,
                                        fecha_entrega_orden_compra,observacion_orden_compra,estado,saldo_ingreso,
                                        total_comprades,fechacreacion,tipo_ingreso_producion,idsucursal)
                                VALUES ('$residcliente',
                                        '$idusuario','$tipo_comprobante','$serie_comprobante','$num_comprobante',
                                        '$fecha_hora','$impuesto','$total_compra_r','$forma_pago','$dias_credito',
                                        '$fecha_hora_pago_credito','$direccion_entrega_orden_compra','$fecha_entrega_orden_compra',
                                        '$observacion_orden_compra','Aceptado','$total_compra_r',
                                        '$total_comprades_r','$fechaHora','$tipo_ingreso_producion','" . $_SESSION["idsucursal"] . "')";
        $idorden_compranew = ejecutarConsulta_retornarID($sql);

        $num_elementos = 0;
        $sw = true;
        if ($idorden_compranew) {
            $articulos = $datosArticulos['articulos'];
            $numArticulos = count($articulos['idarticulo']);
            for ($i = 0; $i < $numArticulos; $i++) {
                $idarticulo = $articulos['idarticulo'][$i];
                $stockinven = $articulos['stockinven'][$i];
                $fechavencimiento = $articulos['fechavencimiento'][$i];
                $cantidadpresentacion = $articulos['cantidadpresentacion'][$i];
                $cantidad = $articulos['cantidad'][$i];
                $totalcantidadpresentacion = $articulos['totalcantidadpresentacion'][$i];
                $presentacion = $articulos['presentacion'][$i];
                $precio_compra = $articulos['precio_compra'][$i];
                $descuento_porcentaje = $articulos['descuento_porcentaje'][$i];
                $precio_venta = $articulos['precio_venta'][$i];
                $precio_ventaNocturno = $articulos['precio_ventaNocturno'][$i];
                $precio_rango1_Mecanico = $articulos['precio_rango1_Mecanico'][$i];
                $precio_rango1_Distribuidor = $articulos['precio_rango1_Distribuidor'][$i];
                $precio_rango1_Mayorista = $articulos['precio_rango1_Mayorista'][$i];
                $precio_rango2_MecanicoDos = $articulos['precio_rango2_MecanicoDos'][$i];
                $precio_rango2_DistribuidorDos = $articulos['precio_rango2_DistribuidorDos'][$i];
                $precio_rango2_MayoristaDos = $articulos['precio_rango2_MayoristaDos'][$i];
                $precio_rango3_MecanicoTres = $articulos['precio_rango3_MecanicoTres'][$i];
                $precio_rango3_DistribuidorTres = $articulos['precio_rango3_DistribuidorTres'][$i];
                $precio_rango3_MayoristaTres = $articulos['precio_rango3_MayoristaTres'][$i];
                $precio_unidad = $articulos['precio_unidad'][$i];
                $precio_blister = $articulos['precio_blister'][$i];
                $precio_caja = $articulos['precio_caja'][$i];
                $precio_fardo = $articulos['precio_fardo'][$i];
                $precio_sacos = $articulos['precio_sacos'][$i];
                $precio_paquete = $articulos['precio_paquete'][$i];
                $precio_07 = $articulos['precio_07'][$i];
                $precio_08 = $articulos['precio_08'][$i];
                $precio_09 = $articulos['precio_09'][$i];
                $precio_10 = $articulos['precio_10'][$i];
                $precio_11 = $articulos['precio_11'][$i];
                $precio_12 = $articulos['precio_12'][$i];
                $precio_13 = $articulos['precio_13'][$i];
                $precio_14 = $articulos['precio_14'][$i];
                $precio_15 = $articulos['precio_15'][$i];
                $precio_16 = $articulos['precio_16'][$i];
                $precio_17 = $articulos['precio_17'][$i];
                $precio_18 = $articulos['precio_18'][$i];
                $precio_19 = $articulos['precio_19'][$i];
                $precio_20 = $articulos['precio_20'][$i];
                $idsucursalDestino = $articulos['idsucursalDestino'][$i];


                $sqlArticulo = "SELECT 
                                    asu.precio_compra as pc_anterior, 
                                    asu.stocksucursal 
                                FROM articuloxsucursal asu
                                WHERE asu.idarticulo='$idarticulo'  and asu.idsucursal='$idsucursalDestino' ";
                $Articulo = ejecutarConsultaSimpleFila($sqlArticulo);


                $stocksucursal_anterior = $Articulo["stocksucursal"];
                $pc_anterior_anterior = $Articulo["pc_anterior"];

                $sql_detalle = "INSERT INTO detalle_orden_compra(idorden_compra,idarticulo,cantidad,precio_compra,precio_venta,
                    descuento_porcentaje,stock_inventario,precio_ventaNocturno,
                    precio_rango1,
                    precio_rango1_Distribuidor,
                    precio_rango1_Mayorista,
                    precio_rango2,
                    precio_rango2_DistribuidorDos,
                    precio_rango2_MayoristaDos,
                    precio_rango3,
                    precio_rango3_DistribuidorTres,
                    precio_rango3_MayoristaTres,
                    precio_unidad,
                    precio_blister,
                    precio_caja,
                    precio_fardo,
                    precio_sacos,
                    precio_paquete,
                    precio_07,
                    precio_08,
                    precio_09,
                    precio_10,
                    precio_11,
                    precio_12,
                    precio_13,
                    precio_14,
                    precio_15,
                    precio_16,
                    precio_17,
                    precio_18,
                    precio_19,
                    precio_20,
                    cantidadpresentacion,
                    totalcantidadpresentacion,
                    presentacion,fechavencimiento,pc_anterior_anterior,idsucursalDestino) 
                                                    VALUES ('$idorden_compranew',
                                                            '$idarticulo',
                                                            '$cantidad',
                                                            '$precio_compra',
                                                            '$precio_venta',
                                                            '$descuento_porcentaje',
                                                            '$stocksucursal_anterior',
                                                            '$precio_ventaNocturno',
                                                            '$precio_rango1_Mecanico',
                                                            '$precio_rango1_Distribuidor',
                                                            '$precio_rango1_Mayorista',
                                                            '$precio_rango2_MecanicoDos',
                                                            '$precio_rango2_DistribuidorDos',
                                                            '$precio_rango2_MayoristaDos',
                                                            '$precio_rango3_MecanicoTres',
                                                            '$precio_rango3_DistribuidorTres',
                                                            '$precio_rango3_MayoristaTres',
                                                            '$precio_unidad',
                                                            '$precio_blister',
                                                            '$precio_caja',
                                                            '$precio_fardo',
                                                            '$precio_sacos',
                                                            '$precio_paquete',
                                                            '$precio_07',
                                                            '$precio_08',
                                                            '$precio_09',
                                                            '$precio_10',
                                                            '$precio_11',
                                                            '$precio_12',
                                                            '$precio_13',
                                                            '$precio_14',
                                                            '$precio_15',
                                                            '$precio_16',
                                                            '$precio_17',
                                                            '$precio_18',
                                                            '$precio_19',
                                                            '$precio_20',
                                                            '$cantidadpresentacion',
                                                            '$totalcantidadpresentacion',
                                                            '$presentacion','$fechavencimiento','$pc_anterior_anterior' ,'$idsucursalDestino' )";
                //  print_r($sql_detalle);

                ejecutarConsulta($sql_detalle) or $sw = false;

                $sqlBusquedaarticuloTipoproducto = "SELECT * FROM articulo WHERE  idarticulo =$idarticulo ";
                $correlativo = ejecutarConsultaSimpleFila($sqlBusquedaarticuloTipoproducto);
                $tipo_producto = $correlativo["tipo_producto"];

                if ($tipo_producto != "Servicios" && $tipo_producto != "Combos") {

                    $sqlArticulo = "SELECT 
                                        asu.precio_compra as pc_anterior, 
                                        asu.stocksucursal 
                                    FROM articuloxsucursal asu
                                    WHERE asu.idarticulo='$idarticulo'  and asu.idsucursal='$idsucursalDestino' ";
                    $Articulo = ejecutarConsultaSimpleFila($sqlArticulo);

                    $t_cantodad = $totalcantidadpresentacion;
                    $pc_compras = $precio_compra;
                    $stoInventi = $stockinven;
                    $stocksucursal_anterior = $Articulo["stocksucursal"];
                    $pc_anterior_anterior = $Articulo["pc_anterior"];

                    if (($t_cantodad + $stoInventi) != 0) {
                        $pc_nuevo = ((($stocksucursal_anterior * $pc_anterior_anterior)  + ($t_cantodad * $pc_compras)) / ($t_cantodad + $stoInventi));
                    } else {
                        // Si el denominador es 0, asignamos el precio de compra actual
                        $pc_nuevo = $pc_compras;
                    }
                }





                $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idorden_compra,
                                                                                    idventa,
                                                                                    idtraladosucursal,
                                                                                    idtraladosucursal_entrada,
                                                                                    iddevolucion,
                                                                                    cantidad_compras,
                                                                                    cantidad_ventas,
                                                                                    cantidad_entrada,
                                                                                    cantidad_devolucion,
                                                                                    cantidad_salida,
                                                                                    stock_inventario,
                                                                                    fecha_horaCreacion,
                                                                                    idarticulo,
                                                                                    idusuario,
                                                                                    idsucursal) 
                                                                            VALUES ('$idorden_compranew',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '$totalcantidadpresentacion',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '$stockinven',
                                                                                    '$fechaHora',
                                                                                    '$idarticulo',
                                                                                    '$idusuario',
                                                                                    '$idsucursalDestino')";
                ejecutarConsulta($sql_detalleoperaciones);
            }
        }

        $this->registrarAuditoria($idorden_compranew, 'CREACIÓN', 'Se registró una nueva orden de compra', $idusuario, $datosArticulos);

        return $idorden_compranew;
    }


    public function insertarApi(
        $idcliente,
        $codigo_cliente,
        $nit,
        $nombre_cliente,
        $telefono_cliente,
        $direccion_cliente,
        $correo_cliente,
        $tipo_documento_cliente,
        $tipo_comprobante,
        $serie_comprobante,
        $num_comprobante,
        $fecha_hora,
        $impuesto,
        $total_compra,
        $total_comprades,
        $forma_pago,
        $dias_credito,
        $fecha_hora_pago_credito,
        $direccion_entrega_orden_compra,
        $fecha_entrega_orden_compra,
        $observacion_orden_compra,
        $tipo_ingreso_producion,
        $datosArticulos,
        $total_compra_r,
        $total_comprades_r,
        $idusuario,
        $idsucursal
    ) {

        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');
        /////CAPTURA DE CLIENTE NUEVO Y UPDATE 
        if ($idcliente == '0') {
            $sqlcorrelativo = "UPDATE add_correlativo SET codigo_cliente=codigo_cliente+1 WHERE idsucursal='$idsucursal' ";
            ejecutarConsulta($sqlcorrelativo);

            $sqlCorre = "SELECT * FROM add_correlativo WHERE idsucursal='$idsucursal' ";
            $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
            $corre = $correlativo["codigo_cliente"];
            $codigo_cliente = 'COD' . $corre;

            $sqlcliente = "INSERT INTO persona (tipo_persona,
                                                    nombre,
                                                    tipo_documento,
                                                    num_documento,
                                                    direccion,
                                                    telefono, 
                                                    email,
                                                    tipo_cliente,
                                                    codigo_cliente,fechaCreacion)
                                            VALUES ('Proveedor',
                                                    '$nombre_cliente',
                                                    '$tipo_documento_cliente',
                                                    '$nit',
                                                    '$direccion_cliente',
                                                    '$telefono_cliente',
                                                    '$correo_cliente',
                                                    'PUBLICO',
                                                    '$codigo_cliente','$fechaHora')";
            $residcliente = ejecutarConsulta_retornarID($sqlcliente);

            if (!$residcliente) {
                throw new Exception("Error al insertar nuevo cliente.");
            }
        } else {
            $sqlCorre = "SELECT * FROM persona WHERE idpersona='$idcliente'";
            $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
            $corre = $correlativo["codigo_cliente"];

            // Verificamos si $corre es '0', está vacío o es null
            if (empty($corre) || $corre == '0') {
                // Si está vacío, null, o es '0', ejecutamos la lógica de actualización del código cliente

                $sqlcorrelativo = "UPDATE add_correlativo SET codigo_cliente=codigo_cliente+1 WHERE idsucursal='$idsucursal' ";
                ejecutarConsulta($sqlcorrelativo);

                $sqlCorrelativo = "SELECT * FROM add_correlativo WHERE idsucursal='$idsucursal' ";
                $correlativos = ejecutarConsultaSimpleFila($sqlCorrelativo);
                $corress = $correlativos["codigo_cliente"];
                $codigo_clientes = 'COD' . $corress;

                $sqlupdadtepersona = "UPDATE persona SET codigo_cliente='$codigo_clientes' WHERE idpersona='$idcliente'";
                ejecutarConsulta($sqlupdadtepersona);
            }

            $sqlcorrelativo = "UPDATE persona SET 
                                            direccion='$direccion_cliente',
                                            telefono='$telefono_cliente',
                                            email='$correo_cliente',
                                            tipo_documento='$tipo_documento_cliente',
                                            nombre='$nombre_cliente'
                                    WHERE idpersona='$idcliente'";

            ejecutarConsulta($sqlcorrelativo);
            $residcliente = $idcliente;
        }
        ///////           

        $sql = "INSERT INTO orden_compra (idproveedor,
                                        idusuario,
                                        tipo_comprobante,
                                        serie_comprobante,
                                        num_comprobante,
                                        fecha_hora,
                                        impuesto,
                                        total_compra,
                                        forma_pago,
                                        dias_credito,
                                        fecha_hora_pago_credito,
                                        direccion_entrega_orden_compra,
                                        fecha_entrega_orden_compra,
                                        observacion_orden_compra,
                                        estado,
                                        saldo_ingreso,
                                        total_comprades,fechacreacion,tipo_ingreso_producion,idsucursal)
                                VALUES ('$residcliente',
                                        '$idusuario',
                                        '$tipo_comprobante',
                                        '$serie_comprobante',
                                        '$num_comprobante',
                                        '$fecha_hora',
                                        '$impuesto',
                                        '$total_compra_r',
                                        '$forma_pago',
                                        '$dias_credito',
                                        '$fecha_hora_pago_credito',
                                        '$direccion_entrega_orden_compra',
                                        '$fecha_entrega_orden_compra',
                                        '$observacion_orden_compra',
                                        'Aceptado',
                                        '$total_compra_r',
                                        '$total_comprades_r','$fechaHora','$tipo_ingreso_producion','$idsucursal')";
        $idorden_compranew = ejecutarConsulta_retornarID($sql);

        $num_elementos = 0;
        $sw = true;
        if ($idorden_compranew) {
            $articulos = $datosArticulos['articulos'];
            $numArticulos = count($articulos['idarticulo']);
            for ($i = 0; $i < $numArticulos; $i++) {
                $idarticulo = $articulos['idarticulo'][$i];
                $stockinven = $articulos['stockinven'][$i];
                $fechavencimiento = $articulos['fechavencimiento'][$i];
                $cantidadpresentacion = $articulos['cantidadpresentacion'][$i];
                $cantidad = $articulos['cantidad'][$i];
                $totalcantidadpresentacion = $articulos['totalcantidadpresentacion'][$i];
                $presentacion = $articulos['presentacion'][$i];
                $precio_compra = $articulos['precio_compra'][$i];
                $descuento_porcentaje = $articulos['descuento_porcentaje'][$i];
                $precio_venta = $articulos['precio_venta'][$i];
                $precio_ventaNocturno = $articulos['precio_ventaNocturno'][$i];
                $precio_rango1_Mecanico = $articulos['precio_rango1_Mecanico'][$i];
                $precio_rango1_Distribuidor = $articulos['precio_rango1_Distribuidor'][$i];
                $precio_rango1_Mayorista = $articulos['precio_rango1_Mayorista'][$i];
                $precio_rango2_MecanicoDos = $articulos['precio_rango2_MecanicoDos'][$i];
                $precio_rango2_DistribuidorDos = $articulos['precio_rango2_DistribuidorDos'][$i];
                $precio_rango2_MayoristaDos = $articulos['precio_rango2_MayoristaDos'][$i];
                $precio_rango3_MecanicoTres = $articulos['precio_rango3_MecanicoTres'][$i];
                $precio_rango3_DistribuidorTres = $articulos['precio_rango3_DistribuidorTres'][$i];
                $precio_rango3_MayoristaTres = $articulos['precio_rango3_MayoristaTres'][$i];
                $precio_unidad = $articulos['precio_unidad'][$i];
                $precio_blister = $articulos['precio_blister'][$i];
                $precio_caja = $articulos['precio_caja'][$i];
                $precio_fardo = $articulos['precio_fardo'][$i];
                $precio_sacos = $articulos['precio_sacos'][$i];
                $precio_paquete = $articulos['precio_paquete'][$i];
                $precio_07 = $articulos['precio_07'][$i];
                $precio_08 = $articulos['precio_08'][$i];
                $precio_09 = $articulos['precio_09'][$i];
                $precio_10 = $articulos['precio_10'][$i];
                $precio_11 = $articulos['precio_11'][$i];
                $precio_12 = $articulos['precio_12'][$i];
                $precio_13 = $articulos['precio_13'][$i];
                $precio_14 = $articulos['precio_14'][$i];
                $precio_15 = $articulos['precio_15'][$i];
                $precio_16 = $articulos['precio_16'][$i];
                $precio_17 = $articulos['precio_17'][$i];
                $precio_18 = $articulos['precio_18'][$i];
                $precio_19 = $articulos['precio_19'][$i];
                $precio_20 = $articulos['precio_20'][$i];


                $sqlArticulo = "SELECT 
                                    asu.precio_compra as pc_anterior, 
                                    asu.stocksucursal 
                                FROM articuloxsucursal asu
                                WHERE asu.idarticulo='$idarticulo'  and asu.idsucursal='$idsucursal' ";
                $Articulo = ejecutarConsultaSimpleFila($sqlArticulo);


                $stocksucursal_anterior = $Articulo["stocksucursal"];
                $pc_anterior_anterior = $Articulo["pc_anterior"];

                $sql_detalle = "INSERT INTO detalle_orden_compra(idorden_compra,idarticulo,cantidad,precio_compra,precio_venta,
                    descuento_porcentaje,stock_inventario,precio_ventaNocturno,
                    precio_rango1,
                    precio_rango1_Distribuidor,
                    precio_rango1_Mayorista,
                    precio_rango2,
                    precio_rango2_DistribuidorDos,
                    precio_rango2_MayoristaDos,
                    precio_rango3,
                    precio_rango3_DistribuidorTres,
                    precio_rango3_MayoristaTres,
                    precio_unidad,
                    precio_blister,
                    precio_caja,
                    precio_fardo,
                    precio_sacos,
                    precio_paquete,
                    precio_07,
                    precio_08,
                    precio_09,
                    precio_10,
                    precio_11,
                    precio_12,
                    precio_13,
                    precio_14,
                    precio_15,
                    precio_16,
                    precio_17,
                    precio_18,
                    precio_19,
                    precio_20,
                    cantidadpresentacion,
                    totalcantidadpresentacion,
                    presentacion,fechavencimiento,pc_anterior_anterior) 
                                                    VALUES ('$idorden_compranew',
                                                            '$idarticulo',
                                                            '$cantidad',
                                                            '$precio_compra',
                                                            '$precio_venta',
                                                            '$descuento_porcentaje',
                                                            '$stocksucursal_anterior',
                                                            '$precio_ventaNocturno',
                                                            '$precio_rango1_Mecanico',
                                                            '$precio_rango1_Distribuidor',
                                                            '$precio_rango1_Mayorista',
                                                            '$precio_rango2_MecanicoDos',
                                                            '$precio_rango2_DistribuidorDos',
                                                            '$precio_rango2_MayoristaDos',
                                                            '$precio_rango3_MecanicoTres',
                                                            '$precio_rango3_DistribuidorTres',
                                                            '$precio_rango3_MayoristaTres',
                                                            '$precio_unidad',
                                                            '$precio_blister',
                                                            '$precio_caja',
                                                            '$precio_fardo',
                                                            '$precio_sacos',
                                                            '$precio_paquete',
                                                            '$precio_07',
                                                            '$precio_08',
                                                            '$precio_09',
                                                            '$precio_10',
                                                            '$precio_11',
                                                            '$precio_12',
                                                            '$precio_13',
                                                            '$precio_14',
                                                            '$precio_15',
                                                            '$precio_16',
                                                            '$precio_17',
                                                            '$precio_18',
                                                            '$precio_19',
                                                            '$precio_20',
                                                            '$cantidadpresentacion',
                                                            '$totalcantidadpresentacion',
                                                            '$presentacion','$fechavencimiento','$pc_anterior_anterior' )";
                ejecutarConsulta($sql_detalle) or $sw = false;

                $sqlBusquedaarticuloTipoproducto = "SELECT * FROM articulo WHERE  idarticulo =$idarticulo ";
                $correlativo = ejecutarConsultaSimpleFila($sqlBusquedaarticuloTipoproducto);
                $tipo_producto = $correlativo["tipo_producto"];

                if ($tipo_producto != "Servicios" && $tipo_producto != "Combos") {

                    $sqlArticulo = "SELECT 
                                        asu.precio_compra as pc_anterior, 
                                        asu.stocksucursal 
                                    FROM articuloxsucursal asu
                                    WHERE asu.idarticulo='$idarticulo'  and asu.idsucursal='$idsucursal' ";
                    $Articulo = ejecutarConsultaSimpleFila($sqlArticulo);

                    $t_cantodad = $totalcantidadpresentacion;
                    $pc_compras = $precio_compra;
                    $stoInventi = $stockinven;
                    $stocksucursal_anterior = $Articulo["stocksucursal"];
                    $pc_anterior_anterior = $Articulo["pc_anterior"];

                    if (($t_cantodad + $stoInventi) != 0) {
                        $pc_nuevo = ((($stocksucursal_anterior * $pc_anterior_anterior)  + ($t_cantodad * $pc_compras)) / ($t_cantodad + $stoInventi));
                    } else {
                        // Si el denominador es 0, asignamos el precio de compra actual
                        $pc_nuevo = $pc_compras;
                    }
                }





                $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idorden_compra,
                                                                                    idventa,
                                                                                    idtraladosucursal,
                                                                                    idtraladosucursal_entrada,
                                                                                    iddevolucion,
                                                                                    cantidad_compras,
                                                                                    cantidad_ventas,
                                                                                    cantidad_entrada,
                                                                                    cantidad_devolucion,
                                                                                    cantidad_salida,
                                                                                    stock_inventario,
                                                                                    fecha_horaCreacion,
                                                                                    idarticulo,
                                                                                    idusuario,
                                                                                    idsucursal) 
                                                                            VALUES ('$idorden_compranew',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '$totalcantidadpresentacion',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '$stockinven',
                                                                                    '$fechaHora',
                                                                                    '$idarticulo',
                                                                                    '$idusuario',
                                                                                    '$idsucursal')";
                ejecutarConsulta($sql_detalleoperaciones);
            }
        }

        return $idorden_compranew;
    }



    //Implementamos un método para insertar registros
    public function insetar_nd(
        $idorden_compra,
        $idcliente,
        $codigo_cliente,
        $nit,
        $nombre_cliente,
        $telefono_cliente,
        $direccion_cliente,
        $correo_cliente,
        $tipo_documento_cliente,
        $idusuario,
        $tipo_comprobante,
        $serie_comprobante,
        $num_comprobante,
        $fecha_hora,
        $impuesto,
        $total_compra,
        $total_comprades,
        $forma_pago,
        $dias_credito,
        $fecha_hora_pago_credito,
        $direccion_entrega_orden_compra,
        $fecha_entrega_orden_compra,
        $observacion_orden_compra,
        $tipo_ingreso_producion,

        $datosArticulos,

        $motivo_ND,
        $fecha_hora_ND,
        $idcotizacion
    ) {

        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        $sql = "INSERT INTO nota_debito (idproveedor,
                                        idusuario,
                                        tipo_comprobante,
                                        serie_comprobante,
                                        num_comprobante,
                                        fecha_hora,
                                        impuesto,
                                        total_compra,
                                        forma_pago,
                                        dias_credito,
                                        fecha_hora_pago_credito,
                                        direccion_entrega_orden_compra,
                                        fecha_entrega_orden_compra,
                                        observacion_orden_compra,
                                        estado,
                                        saldo_ingreso,
                                        total_comprades,fechacreacion,tipo_ingreso_producion,idsucursal,idorden_compra,motivo_ND,fecha_hora_ND)
                                VALUES ('$idcliente',
                                        '$idusuario',
                                        '$tipo_comprobante',
                                        '$serie_comprobante',
                                        '$num_comprobante',
                                        '$fecha_hora',
                                        '$impuesto', 
                                        '$total_compra',
                                        '$forma_pago',
                                        '$dias_credito',
                                        '$fecha_hora_pago_credito',
                                        '$direccion_entrega_orden_compra',
                                        '$fecha_entrega_orden_compra',
                                        '$observacion_orden_compra',
                                        'Aceptado',
                                        '$total_compra',
                                        '$total_comprades','$fechaHora','$tipo_ingreso_producion','" . $_SESSION["idsucursal"] . "','$idorden_compra','$motivo_ND','$fecha_hora_ND')";
        //return ejecutarConsulta($sql);
        $idorden_compranew = ejecutarConsulta_retornarID($sql);


        $sqlUpdateingreso = "UPDATE orden_compra SET notadebito='SI',idnota_debito='$idorden_compranew' WHERE idorden_compra='$idorden_compra' ";
        ejecutarConsulta($sqlUpdateingreso);

        $num_elementos = 0;
        $sw = true;
        if ($idorden_compranew) {
            $articulos = $datosArticulos['articulos'];
            $numArticulos = count($articulos['idarticulo']);
            for ($i = 0; $i < $numArticulos; $i++) {
                $idarticulo = $articulos['idarticulo'][$i];
                $stockinven = $articulos['stockinven'][$i];
                $cantidadpresentacion = $articulos['cantidadpresentacion'][$i];
                $cantidad = $articulos['cantidad'][$i];
                $totalcantidadpresentacion = $articulos['totalcantidadpresentacion'][$i];
                $presentacion = $articulos['presentacion'][$i];
                $precio_compra = $articulos['precio_compra'][$i];
                $descuento_porcentaje = $articulos['descuento_porcentaje'][$i];
                $precio_venta = $articulos['precio_venta'][$i];
                $precio_ventaNocturno = $articulos['precio_ventaNocturno'][$i];
                $precio_rango1 = $articulos['precio_rango1'][$i];
                $precio_rango2 = $articulos['precio_rango2'][$i];
                $precio_rango3 = $articulos['precio_rango3'][$i];
                $precio_unidad = $articulos['precio_unidad'][$i];
                $precio_blister = $articulos['precio_blister'][$i];
                $precio_caja = $articulos['precio_caja'][$i];
                $precio_fardo = $articulos['precio_fardo'][$i];
                $precio_sacos = $articulos['precio_sacos'][$i];
                $precio_paquete = $articulos['precio_paquete'][$i];

                $sql_detalle = "INSERT INTO detalle_nota_debito(idnota_debito,
                                                            idarticulo,
                                                            cantidad,
                                                            precio_compra,
                                                            precio_venta,
                                                            descuento_porcentaje,
                                                            stock_inventario,
                                                            precio_ventaNocturno,
                                                            precio_rango1,
                                                            precio_rango2,
                                                            precio_rango3,
                                                            precio_unidad,
                                                            precio_blister,
                                                            precio_caja,
                                                            precio_fardo,
                                                            precio_sacos,
                                                            precio_paquete,
                                                            cantidadpresentacion,
                                                            totalcantidadpresentacion,
                                                            presentacion) 
                                                    VALUES ('$idorden_compranew',
                                                            '$idarticulo',
                                                            '$cantidad',
                                                            '$precio_compra',
                                                            '$precio_venta',
                                                            '$descuento_porcentaje',
                                                            '$stockinven',
                                                            '$precio_ventaNocturno',
                                                            '$precio_rango1',
                                                            '$precio_rango2',
                                                            '$precio_rango3',
                                                            '$precio_unidad',
                                                            '$precio_blister',
                                                            '$precio_caja',
                                                            '$precio_fardo',
                                                            '$precio_sacos',
                                                            '$precio_paquete',
                                                            '$cantidadpresentacion',
                                                            '$totalcantidadpresentacion',
                                                            '$presentacion' )";
                ejecutarConsulta($sql_detalle) or $sw = false;


                $sqlArticulo = "SELECT 
                                        asu.precio_compra as pc_anterior, 
                                        asu.stocksucursal 
                                    FROM articuloxsucursal asu
                                    WHERE asu.idarticulo='$idarticulo'  and asu.idsucursal='" . $_SESSION["idsucursal"] . "' ";
                $Articulo = ejecutarConsultaSimpleFila($sqlArticulo);

                $t_cantodad = $totalcantidadpresentacion;
                $pc_compras = $precio_compra;
                $stoInventi = $stockinven;
                $stocksucursal_anterior = $Articulo["stocksucursal"];
                $pc_anterior_anterior = $Articulo["pc_anterior"];


                if (($t_cantodad + $stoInventi) != 0) {
                    $pc_nuevo = ((($stocksucursal_anterior * $pc_anterior_anterior)  - ($t_cantodad * $pc_compras)) / ($stocksucursal_anterior - $t_cantodad));
                } else {
                    // Si el denominador es 0, asignamos el precio de compra actual
                    $pc_nuevo = $pc_compras;
                }
                $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idorden_compra,
                                                                                    idventa,
                                                                                    idtraladosucursal,
                                                                                    idtraladosucursal_entrada,
                                                                                    iddevolucion,
                                                                                    cantidad_compras,
                                                                                    cantidad_ventas,
                                                                                    cantidad_entrada,
                                                                                    cantidad_devolucion,
                                                                                    cantidad_salida,
                                                                                    stock_inventario,
                                                                                    fecha_horaCreacion,
                                                                                    idarticulo,
                                                                                    idusuario,
                                                                                    idsucursal,
                                                                                    idnota_debito,cantidad_notaDebito) 
                                                                            VALUES ('0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0', 
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '$stockinven',
                                                                                    '$fechaHora',
                                                                                    '$idarticulo',
                                                                                    '$idusuario',
                                                                                    '" . $_SESSION["idsucursal"] . "','$idorden_compranew','$totalcantidadpresentacion')";
                ejecutarConsulta($sql_detalleoperaciones);
            }
        }

        return $idorden_compranew;
    }

    //Implementamos un método para editar registros
    public function editar(
        $idorden_compra,
        $idcliente,
        $codigo_cliente,
        $nit,
        $nombre_cliente,
        $telefono_cliente,
        $direccion_cliente,
        $correo_cliente,
        $tipo_documento_cliente,
        $idusuario,
        $tipo_comprobante,
        $serie_comprobante,
        $num_comprobante,
        $fecha_hora,
        $impuesto,
        $total_compra,
        $total_comprades,
        $forma_pago,
        $dias_credito,
        $fecha_hora_pago_credito,
        $direccion_entrega_orden_compra,
        $fecha_entrega_orden_compra,
        $observacion_orden_compra,
        $tipo_ingreso_producion,
        $datosArticulos,
        $total_compra_r,
        $total_comprades_r

    ) {


        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');
        /////CAPTURA DE CLIENTE NUEVO Y UPDATE
        if ($idcliente == '0') {
            $sqlcorrelativo = "UPDATE add_correlativo SET codigo_cliente=codigo_cliente+1 WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
            ejecutarConsulta($sqlcorrelativo);

            $sqlCorre = "SELECT * FROM add_correlativo WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
            $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
            $corre = $correlativo["codigo_cliente"];
            $codigo_cliente = 'COD' . $corre;

            $sqlcliente = "INSERT INTO persona (tipo_persona,
                                                    nombre,
                                                    tipo_documento,
                                                    num_documento,
                                                    direccion,
                                                    telefono,
                                                    email,
                                                    tipo_cliente,
                                                    codigo_cliente)
                                            VALUES ('Proveedor',
                                                    '$nombre_cliente',
                                                    '$tipo_documento_cliente',
                                                    '$nit',
                                                    '$direccion_cliente',
                                                    '$telefono_cliente',
                                                    '$correo_cliente',
                                                    'PUBLICO',
                                                    '$codigo_cliente')";
            $residcliente = ejecutarConsulta_retornarID($sqlcliente);

            if (!$residcliente) {
                throw new Exception("Error al insertar nuevo cliente.");
            }
        } else {
            $sqlCorre = "SELECT * FROM persona WHERE idpersona='$idcliente'";
            $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
            $corre = $correlativo["codigo_cliente"];

            // Verificamos si $corre es '0', está vacío o es null
            if (empty($corre) || $corre == '0') {
                // Si está vacío, null, o es '0', ejecutamos la lógica de actualización del código cliente

                $sqlcorrelativo = "UPDATE add_correlativo SET codigo_cliente=codigo_cliente+1 WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
                ejecutarConsulta($sqlcorrelativo);

                $sqlCorrelativo = "SELECT * FROM add_correlativo WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
                $correlativos = ejecutarConsultaSimpleFila($sqlCorrelativo);
                $corress = $correlativos["codigo_cliente"];
                $codigo_clientes = 'COD' . $corress;

                $sqlupdadtepersona = "UPDATE persona SET codigo_cliente='$codigo_clientes' WHERE idpersona='$idcliente'";
                ejecutarConsulta($sqlupdadtepersona);
            }

            $sqlcorrelativo = "UPDATE persona SET 
                                            direccion='$direccion_cliente',
                                            telefono='$telefono_cliente',
                                            email='$correo_cliente',
                                            tipo_documento='$tipo_documento_cliente',
                                            nombre='$nombre_cliente'
                                    WHERE idpersona='$idcliente'";

            ejecutarConsulta($sqlcorrelativo);
            $residcliente = $idcliente;
        }
        ///////           



        $sqlUpdate = "UPDATE orden_compra SET 
                            idproveedor = '$residcliente',
                            idusuario_update = '$idusuario',
                            tipo_comprobante = '$tipo_comprobante',
                            serie_comprobante = '$serie_comprobante',
                            num_comprobante = '$num_comprobante',
                            fecha_hora = '$fecha_hora',
                            impuesto = '$impuesto',
                            total_compra = '$total_compra_r',
                            forma_pago = '$forma_pago',
                            dias_credito = '$dias_credito',
                            fecha_hora_pago_credito = '$fecha_hora_pago_credito',
                            direccion_entrega_orden_compra = '$direccion_entrega_orden_compra',
                            fecha_entrega_orden_compra = '$fecha_entrega_orden_compra',
                            observacion_orden_compra = '$observacion_orden_compra',
                            estado = 'Aceptado',
                            saldo_ingreso = '$total_compra_r',
                            total_comprades = '$total_comprades_r',
                            fechaUpdate = '$fechaHora',
                            tipo_ingreso_producion = '$tipo_ingreso_producion'
                        WHERE idorden_compra = '$idorden_compra'";
        ejecutarConsulta($sqlUpdate);

        $num_elementos = 0;
        $sw = true;

        ////AN ULACION DE OPERACIONES COMPRAS
        $sqlDetalleingreso = "SELECT * FROM detalle_orden_compra WHERE idorden_compra='$idorden_compra'";
        $Detalle = ejecutarConsulta($sqlDetalleingreso);


        $sqlDetalleIngresoElimminar = "DELETE from detalle_orden_compra where idorden_compra=" . $idorden_compra . "";
        ejecutarConsulta($sqlDetalleIngresoElimminar);

        $sqlDetalleIngresoElimminar = "DELETE from operaciones_compras_ventas where idorden_compra=" . $idorden_compra . "";
        ejecutarConsulta($sqlDetalleIngresoElimminar);
        ////AN ULACION DE OPERACIONES COMPRAS
        if ($idorden_compra) {
            $articulos = $datosArticulos['articulos'];
            $numArticulos = count($articulos['idarticulo']);
            for ($i = 0; $i < $numArticulos; $i++) {
                $idarticulo = $articulos['idarticulo'][$i];
                $stockinven = $articulos['stockinven'][$i];
                $fechavencimiento = $articulos['fechavencimiento'][$i];
                $cantidadpresentacion = $articulos['cantidadpresentacion'][$i];
                $cantidad = $articulos['cantidad'][$i];
                $totalcantidadpresentacion = $articulos['totalcantidadpresentacion'][$i];
                $presentacion = $articulos['presentacion'][$i];
                $precio_compra = $articulos['precio_compra'][$i];
                $descuento_porcentaje = $articulos['descuento_porcentaje'][$i];
                $precio_venta = $articulos['precio_venta'][$i];
                $precio_ventaNocturno = $articulos['precio_ventaNocturno'][$i];
                $precio_rango1_Mecanico = $articulos['precio_rango1_Mecanico'][$i];
                $precio_rango1_Distribuidor = $articulos['precio_rango1_Distribuidor'][$i];
                $precio_rango1_Mayorista = $articulos['precio_rango1_Mayorista'][$i];
                $precio_rango2_MecanicoDos = $articulos['precio_rango2_MecanicoDos'][$i];
                $precio_rango2_DistribuidorDos = $articulos['precio_rango2_DistribuidorDos'][$i];
                $precio_rango2_MayoristaDos = $articulos['precio_rango2_MayoristaDos'][$i];
                $precio_rango3_MecanicoTres = $articulos['precio_rango3_MecanicoTres'][$i];
                $precio_rango3_DistribuidorTres = $articulos['precio_rango3_DistribuidorTres'][$i];
                $precio_rango3_MayoristaTres = $articulos['precio_rango3_MayoristaTres'][$i];
                $precio_unidad = $articulos['precio_unidad'][$i];
                $precio_blister = $articulos['precio_blister'][$i];
                $precio_caja = $articulos['precio_caja'][$i];
                $precio_fardo = $articulos['precio_fardo'][$i];
                $precio_sacos = $articulos['precio_sacos'][$i];
                $precio_paquete = $articulos['precio_paquete'][$i];
                $precio_07 = $articulos['precio_07'][$i];
                $precio_08 = $articulos['precio_08'][$i];
                $precio_09 = $articulos['precio_09'][$i];
                $precio_10 = $articulos['precio_10'][$i];
                $precio_11 = $articulos['precio_11'][$i];
                $precio_12 = $articulos['precio_12'][$i];
                $precio_13 = $articulos['precio_13'][$i];
                $precio_14 = $articulos['precio_14'][$i];
                $precio_15 = $articulos['precio_15'][$i];
                $precio_16 = $articulos['precio_16'][$i];
                $precio_17 = $articulos['precio_17'][$i];
                $precio_18 = $articulos['precio_18'][$i];
                $precio_19 = $articulos['precio_19'][$i];
                $precio_20 = $articulos['precio_20'][$i];
                $idsucursalDestino = $articulos['idsucursalDestino'][$i];


                $sqlArticulo = "SELECT 
                                    asu.precio_compra as pc_anterior, 
                                    asu.stocksucursal 
                                FROM articuloxsucursal asu
                                WHERE asu.idarticulo='$idarticulo'  and asu.idsucursal='$idsucursalDestino' ";
                $Articulo = ejecutarConsultaSimpleFila($sqlArticulo);


                $stocksucursal_anterior = $Articulo["stocksucursal"];
                $pc_anterior_anterior = $Articulo["pc_anterior"];

                $sql_detalle = "INSERT INTO detalle_orden_compra(idorden_compra,idarticulo,cantidad,precio_compra,precio_venta,
                    descuento_porcentaje,stock_inventario,precio_ventaNocturno,
                    precio_rango1,
                    precio_rango1_Distribuidor,
                    precio_rango1_Mayorista,
                    precio_rango2,
                    precio_rango2_DistribuidorDos,
                    precio_rango2_MayoristaDos,
                    precio_rango3,
                    precio_rango3_DistribuidorTres,
                    precio_rango3_MayoristaTres,
                    precio_unidad,
                    precio_blister,
                    precio_caja,
                    precio_fardo,
                    precio_sacos,
                    precio_paquete,
                    precio_07,
                    precio_08,
                    precio_09,
                    precio_10,
                    precio_11,
                    precio_12,
                    precio_13,
                    precio_14,
                    precio_15,
                    precio_16,
                    precio_17,
                    precio_18,
                    precio_19,
                    precio_20,
                    cantidadpresentacion,
                    totalcantidadpresentacion,
                    presentacion,fechavencimiento,pc_anterior_anterior,idsucursalDestino) 
                                                    VALUES ('$idorden_compra',
                                                           '$idarticulo',
                                                            '$cantidad',
                                                            '$precio_compra',
                                                            '$precio_venta',
                                                            '$descuento_porcentaje',
                                                            '$stocksucursal_anterior',
                                                            '$precio_ventaNocturno',
                                                            '$precio_rango1_Mecanico',
                                                            '$precio_rango1_Distribuidor',
                                                            '$precio_rango1_Mayorista',
                                                            '$precio_rango2_MecanicoDos',
                                                            '$precio_rango2_DistribuidorDos',
                                                            '$precio_rango2_MayoristaDos',
                                                            '$precio_rango3_MecanicoTres',
                                                            '$precio_rango3_DistribuidorTres',
                                                            '$precio_rango3_MayoristaTres',
                                                            '$precio_unidad',
                                                            '$precio_blister',
                                                            '$precio_caja',
                                                            '$precio_fardo',
                                                            '$precio_sacos',
                                                            '$precio_paquete',
                                                            '$precio_07',
                                                            '$precio_08',
                                                            '$precio_09',
                                                            '$precio_10',
                                                            '$precio_11',
                                                            '$precio_12',
                                                            '$precio_13',
                                                            '$precio_14',
                                                            '$precio_15',
                                                            '$precio_16',
                                                            '$precio_17',
                                                            '$precio_18',
                                                            '$precio_19',
                                                            '$precio_20',
                                                            '$cantidadpresentacion',
                                                            '$totalcantidadpresentacion',
                                                            '$presentacion','$fechavencimiento','$pc_anterior_anterior', '$idsucursalDestino' )";
                ejecutarConsulta($sql_detalle) or $sw = false;

                $sqlBusquedaarticuloTipoproducto = "SELECT * FROM articulo WHERE  idarticulo =$idarticulo ";
                $correlativo = ejecutarConsultaSimpleFila($sqlBusquedaarticuloTipoproducto);
                $tipo_producto = $correlativo["tipo_producto"];

                if ($tipo_producto != "Servicios" && $tipo_producto != "Combos") {

                    $sqlArticulo = "SELECT 
                                        asu.precio_compra as pc_anterior, 
                                        asu.stocksucursal 
                                    FROM articuloxsucursal asu
                                    WHERE asu.idarticulo='$idarticulo'  and asu.idsucursal='$idsucursalDestino' ";
                    $Articulo = ejecutarConsultaSimpleFila($sqlArticulo);

                    $t_cantodad = $totalcantidadpresentacion;
                    $pc_compras = $precio_compra;
                    $stoInventi = $stockinven;
                    $stocksucursal_anterior = $Articulo["stocksucursal"];
                    $pc_anterior_anterior = $Articulo["pc_anterior"];

                    if (($t_cantodad + $stoInventi) != 0) {
                        $pc_nuevo = ((($stocksucursal_anterior * $pc_anterior_anterior)  + ($t_cantodad * $pc_compras)) / ($t_cantodad + $stoInventi));
                    } else {
                        // Si el denominador es 0, asignamos el precio de compra actual
                        $pc_nuevo = $pc_compras;
                    }
                }


                $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idorden_compra,
                                                                                    idventa,
                                                                                    idtraladosucursal,
                                                                                    idtraladosucursal_entrada,
                                                                                    iddevolucion,
                                                                                    cantidad_compras,
                                                                                    cantidad_ventas,
                                                                                    cantidad_entrada,
                                                                                    cantidad_devolucion,
                                                                                    cantidad_salida,
                                                                                    stock_inventario,
                                                                                    fecha_horaCreacion,
                                                                                    idarticulo,
                                                                                    idusuario,
                                                                                    idsucursal) 
                                                                            VALUES ('$idorden_compra',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '$totalcantidadpresentacion',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '$stockinven',
                                                                                    '$fechaHora',
                                                                                    '$idarticulo',
                                                                                    '$idusuario',
                                                                                    '$idsucursalDestino')";
                ejecutarConsulta($sql_detalleoperaciones);
            }
        }

        $this->registrarAuditoria($idorden_compra, 'EDICIÓN', 'Se editó la orden de compra', $idusuario, $datosArticulos);

        return $idorden_compra;
    }

    public function editar_ingreso_orden_compra(
        $idorden_compra,
        $idcliente,
        $codigo_cliente,
        $nit,
        $nombre_cliente,
        $telefono_cliente,
        $direccion_cliente,
        $correo_cliente,
        $tipo_documento_cliente,
        $idusuario,
        $tipo_comprobante,
        $serie_comprobante,
        $num_comprobante,
        $fecha_hora,
        $impuesto,
        $total_compra,
        $total_comprades,
        $forma_pago,
        $dias_credito,
        $fecha_hora_pago_credito,
        $direccion_entrega_orden_compra,
        $fecha_entrega_orden_compra,
        $observacion_orden_compra,
        $tipo_ingreso_producion,

        $datosArticulos,
        $total_compra_r,
        $total_comprades_r,
        $estado_orden_compra

    ) {


        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');
        $sqlUpdate = "UPDATE orden_compra SET 
                            estado_compra = '$estado_orden_compra',
                            fecha_autorizacion = NOW(),
                            idusuario_autorizacion = '" . $_SESSION["idusuario"] . "'
                        WHERE idorden_compra = '$idorden_compra'";
        ejecutarConsulta($sqlUpdate);

        $num_elementos = 0;
        $sw = true;

        ////AN ULACION DE OPERACIONES COMPRAS
        $sqlDetalleingreso = "SELECT * FROM detalle_orden_compra WHERE idorden_compra='$idorden_compra'";
        $Detalle = ejecutarConsulta($sqlDetalleingreso);



        ////AN ULACION DE OPERACIONES COMPRAS
        if ($idorden_compra) {
            $articulos = $datosArticulos['articulos'];
            $numArticulos = count($articulos['idarticulo']);
            for ($i = 0; $i < $numArticulos; $i++) {
                $idarticulo = $articulos['idarticulo'][$i];
                $stockinven = $articulos['stockinven'][$i];
                $fechavencimiento = $articulos['fechavencimiento'][$i];
                $cantidadpresentacion = $articulos['cantidadpresentacion'][$i];
                $cantidad = $articulos['cantidad'][$i];
                $totalcantidadpresentacion = $articulos['totalcantidadpresentacion'][$i];
                $presentacion = $articulos['presentacion'][$i];
                $precio_compra = $articulos['precio_compra'][$i];
                $descuento_porcentaje = $articulos['descuento_porcentaje'][$i];
                $precio_venta = $articulos['precio_venta'][$i];
                $precio_ventaNocturno = $articulos['precio_ventaNocturno'][$i];
                $precio_rango1 = $articulos['precio_rango1'][$i];
                $precio_rango2 = $articulos['precio_rango2'][$i];
                $precio_rango3 = $articulos['precio_rango3'][$i];
                $precio_unidad = $articulos['precio_unidad'][$i];
                $precio_blister = $articulos['precio_blister'][$i];
                $precio_caja = $articulos['precio_caja'][$i];
                $precio_fardo = $articulos['precio_fardo'][$i];
                $precio_sacos = $articulos['precio_sacos'][$i];
                $precio_paquete = $articulos['precio_paquete'][$i];
                $descripcion_detalle = $articulos['descripcion_detalle'][$i];
                $iddetalle_orden_compra = $articulos['iddetalle_orden_compra'][$i];



                $sqlUpdateDetalleOrdenCompra = "UPDATE detalle_orden_compra SET 
                    descripcion_detalle = '$descripcion_detalle',
                    fechavencimiento = '$fechavencimiento'
                    WHERE idorden_compra ='$idorden_compra'  and iddetalle_orden_compra='$iddetalle_orden_compra' ";
                ejecutarConsulta($sqlUpdateDetalleOrdenCompra);
            }
        }

        return $idorden_compra;
    }

    //Implementamos un método para anular categorías
    public function anular($idorden_compra)
    {
        // Verificar si existe un ingreso posterior
        $sqlValidar = "SELECT COUNT(*) as total FROM orden_compra WHERE idorden_compra > '$idorden_compra'";
        $resultadoValidar = ejecutarConsultaSimpleFila($sqlValidar);

        if ($resultadoValidar['total'] > 0) {
            return [
                "status" => false,
                "message" => "No se puede anular la operación porque ya existe un ingreso posterior registrado.",
            ];
        }

        $sqlValidar2 = "SELECT * FROM orden_compra WHERE idorden_compra = '$idorden_compra'";
        $resultadoValidar2 = ejecutarConsultaSimpleFila($sqlValidar2);

        if ($resultadoValidar2['estado_compra'] == "Ingreso de Compra") {
            return [
                "status" => false,
                "message" => "No se puede anular la operación, Orden de compra ya registrada",
            ];
        }

        // Continuar con la anulación si no hay ingresos posteriores
        $sql = "UPDATE orden_compra SET estado='Anulado' WHERE idorden_compra='$idorden_compra'";
        ejecutarConsulta($sql);

        $sqlOperacionesCompraVenta = "UPDATE operaciones_compras_ventas SET estado='Anulado' WHERE idorden_compra='$idorden_compra'";
        ejecutarConsulta($sqlOperacionesCompraVenta);

        $sqlDetalleingreso = "SELECT * FROM detalle_orden_compra WHERE idorden_compra='$idorden_compra'";
        $Detalle = ejecutarConsulta($sqlDetalleingreso);

        $idusuario_auditoria = $_SESSION["idusuario"];
        $this->registrarAuditoria($idorden_compra, 'ANULACIÓN', 'Se anuló la orden de compra', $idusuario_auditoria, null);

        return [
            "status" => true,
            "message" => "Operación anulada con éxito.",
        ];
    }



    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idorden_compra)
    {

        // Verificar si existe un ingreso posterior
        $sqlValidar = "SELECT COUNT(*) as total FROM orden_compra WHERE estado='Aceptado' and idorden_compra > '$idorden_compra'";
        $resultadoValidar = ejecutarConsultaSimpleFila($sqlValidar);

        if ($resultadoValidar['total'] > 0) {
            return [
                "status" => false,
                "message" => "No se puede mostrar la operación porque ya existe un ingreso posterior registrado.",
            ];
        }

        $sql = "SELECT 
                i.idorden_compra,
                p.codigo_cliente,
                p.num_documento as nit,
                p.nombre as nombre_cliente,
                p.telefono as telefono_cliente,
                p.direccion as direccion_cliente,
                p.email as correo_cliente,
                p.idpersona as idcliente,
                p.tipo_documento as tipo_documento_cliente,
                DATE(i.fecha_hora) as fecha,
                i.idproveedor,
                p.nombre as proveedor,
                u.idusuario,
                u.nombre as usuario,
                i.tipo_comprobante, 
                i.serie_comprobante,
                i.num_comprobante,
                i.total_compra,
                i.impuesto,
                i.estado,
                i.total_comprades,
                i.forma_pago,
                i.dias_credito,
                DATE(i.fecha_hora_pago_credito) as fechahorapagocredito,
                i.direccion_entrega_orden_compra as direccion_entrega_orden_compra,
                Date(i.fecha_entrega_orden_compra) as fechaentregaordencompra,
                i.observacion_orden_compra,
                i.estado_compra
            FROM orden_compra i 
            INNER JOIN persona p ON i.idproveedor=p.idpersona 
            INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE i.idorden_compra='$idorden_compra' and i.notadebito='NO' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function detalleingreso($idorden_compra)
    {

        $sqldetalle = "SELECT  
                d.iddetalle_orden_compra,
                d.idorden_compra,
                d.idarticulo, 
                d.cantidad,
                d.precio_compra,
                d.precio_venta,
                d.descuento_porcentaje,
                asu.stocksucursal as stock,
                a.nombre as articulo,
                d.precio_ventaNocturno,
                d.descripcion_detalle,
                d.precio_rango1 as detalle_precio_rango1_Mecanico,
                d.precio_rango1_Distribuidor as detalle_precio_rango1_Distribuidor,
                d.precio_rango1_Mayorista as detalle_precio_rango1_Mayorista,
                d.precio_rango2 as detalle_precio_rango2_MecanicoDos,
                d.precio_rango2_DistribuidorDos as detalle_precio_rango2_DistribuidorDos,
                d.precio_rango2_MayoristaDos as detalle_precio_rango2_MayoristaDos,
                d.precio_rango3 as detalle_precio_rango3_MecanicoTres,
                d.precio_rango3_DistribuidorTres as detalle_precio_rango3_DistribuidorTres,
                d.precio_rango3_MayoristaTres as detalle_precio_rango3_MayoristaTres,
                d.precio_unidad as detalle_precio_unidad,
                d.precio_blister as detalle_precio_blister,
                d.precio_caja as detalle_precio_caja,
                d.descripcion_detalle as detalle_descripcion_detalle,
                d.precio_fardo as detalle_precio_fardo,
                d.precio_sacos as detalle_precio_sacos,
                d.precio_paquete as detalle_precio_paquete,
                d.precio_07 as detalle_precio_07,
                d.precio_08 as detalle_precio_08,
                d.precio_09 as detalle_precio_09,
                d.precio_10 as detalle_precio_10,
                d.precio_11 as detalle_precio_11,
                d.precio_12 as detalle_precio_12,
                d.precio_13 as detalle_precio_13,
                d.precio_14 as detalle_precio_14,
                d.precio_15 as detalle_precio_15,
                d.precio_16 as detalle_precio_16,
                d.precio_17 as detalle_precio_17,
                d.precio_18 as detalle_precio_18,
                d.precio_19 as detalle_precio_19,
                d.precio_20 as detalle_precio_20,
                d.cantidadpresentacion,
                d.totalcantidadpresentacion,
                d.presentacion,
                DATE(d.fechavencimiento) as fechavencimiento,
                asu.precio_rango1_Mecanico,
                asu.precio_rango1_Distribuidor,
                asu.precio_rango1_Mayorista,
                asu.precio_rango2_MecanicoDos,
                asu.precio_rango2_DistribuidorDos,
                asu.precio_rango2_MayoristaDos,
                asu.precio_rango3_MecanicoTres,
                asu.precio_rango3_DistribuidorTres,
                asu.precio_rango3_MayoristaTres,
                asu.nombre_01,
                asu.stock_unidad,
                asu.precio_unidad,
                asu.nombre_02,
                asu.stock_blister,
                asu.precio_blister,
                asu.nombre_03,
                asu.stock_caja,
                asu.precio_caja,
                asu.nombre_04,
                asu.stock_fardo,
                asu.precio_fardo,
                asu.nombre_05,
                asu.stock_sacos,
                asu.precio_sacos,
                asu.nombre_06,
                asu.stock_paquete,
                asu.precio_paquete,
                asu.nombre_07,
                asu.stock_07,
                asu.precio_07,
                asu.nombre_08,
                asu.stock_08,
                asu.precio_08,
                asu.nombre_09,
                asu.stock_09,
                asu.precio_09,
                asu.nombre_10,
                asu.stock_10,
                asu.precio_10,
                asu.nombre_11,
                asu.stock_11,
                asu.precio_11,
                asu.nombre_12,
                asu.stock_12,
                asu.precio_12,
                asu.nombre_13,
                asu.stock_13,
                asu.nombre_14,
                asu.stock_14,
                asu.precio_14,
                asu.nombre_15,
                asu.stock_15,
                asu.precio_15,
                asu.nombre_16,
                asu.stock_16,
                asu.precio_16,
                asu.nombre_17,
                asu.stock_17,
                asu.precio_17,
                asu.nombre_18,
                asu.stock_18,
                asu.precio_18,
                asu.nombre_19,
                asu.stock_19,
                asu.precio_19,
                asu.nombre_20,
                asu.stock_20,
                asu.precio_20,
                a.facturar_cero,
                d.idsucursalDestino,
                s.nombre as sucursalDestino
            FROM detalle_orden_compra d
            INNER JOIN articulo a ON a.idarticulo=d.idarticulo
            INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
            inner join sucursal s on s.idSucursal=d.idsucursalDestino
             where  asu.idsucursal='" . $_SESSION["idsucursal"] . "' and  d.idorden_compra='$idorden_compra'
             ORDER BY d.iddetalle_orden_compra asc";

        #echo $sql;
        $rspta = ejecutarConsulta($sqldetalle);
        $rows = array();
        while ($reg = $rspta->fetch_object()) {
            $rows[] = $reg;
        }
        return $rows;
    }

    //Implementar un método para listar los registros
    public function listar($fecha_inicio_reporte, $fecha_fin_reporte)
    {
        $sql = "SELECT 
                i.idorden_compra,
                DATE(i.fecha_hora) as fecha,
                i.idproveedor,
                p.nombre as proveedor,
                u.idusuario,
                u.nombre as usuario,
                i.tipo_comprobante,
                i.serie_comprobante,
                i.num_comprobante,
                i.total_compra,
                i.impuesto,
                i.estado,
                i.estado_compra
        FROM orden_compra i 
        INNER JOIN persona p ON i.idproveedor=p.idpersona 
        INNER JOIN usuario u ON i.idusuario=u.idusuario  
        where i.idsucursal='" . $_SESSION["idsucursal"] . "'  and  
                DATE(i.fecha_hora)>='$fecha_inicio_reporte' 
                AND DATE(i.fecha_hora)<='$fecha_fin_reporte'
        ORDER BY i.idorden_compra DESC";
        return ejecutarConsulta($sql);
    }


    public function listarND($fecha_inicio_reporte, $fecha_fin_reporte)
    {
        $sql = "SELECT 
                i.idnota_debito,
                i.idorden_compra,
                DATE(i.fecha_hora) as fecha,
                i.idproveedor,
                p.nombre as proveedor,
                u.idusuario,
                u.nombre as usuario,
                i.tipo_comprobante,
                i.serie_comprobante,
                i.num_comprobante,
                i.total_compra,
                i.impuesto,
                i.estado,
                (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=ii.idusuario LIMIT 1) AS usuarioIngreso,
                date(i.fecha_hora_ND) AS fechand,
                i.motivo_ND
        FROM nota_debito i 
        INNER JOIN ingreso ii ON ii.idnota_debito=i.idnota_debito
        INNER JOIN persona p ON i.idproveedor=p.idpersona 
        INNER JOIN usuario u ON i.idusuario=u.idusuario  
        where u.idsucursal='" . $_SESSION["idsucursal"] . "'  and  
                DATE(i.fecha_hora_ND)>='$fecha_inicio_reporte' 
                AND DATE(i.fecha_hora_ND)<='$fecha_fin_reporte'

        ORDER BY i.idnota_debito DESC";
        return ejecutarConsulta($sql);
    }


    public function ingresocabecera($idorden_compra)
    {
        $sql = "SELECT 
                i.idorden_compra,
                i.idproveedor,
                p.nombre as proveedor,
                p.direccion,
                p.telefono,
                p.num_documento,
                P.email,
                i.idusuario,
                u.nombre as usuario,
                i.tipo_comprobante,
                i.serie_comprobante,
                i.num_comprobante,
                DATE(i.fecha_hora) as fecha,
                i.impuesto,
                i.total_compra,
                i.estado,
                i.forma_pago,
                i.dias_credito,
                i.direccion_entrega_orden_compra,
                DATE(i.fecha_entrega_orden_compra) as fechaentregaordencompra,
                i.observacion_orden_compra,
                DATE(i.fecha_hora_pago_credito) as fechahorapagocredito
                FROM orden_compra i
                INNER JOIN persona p ON p.idpersona=i.idproveedor
                INNER JOIN usuario u ON u.idusuario=i.idusuario
                WHERE i.idorden_compra='$idorden_compra'";
        return ejecutarConsulta($sql);
    }

    public function ingresodetalle($idorden_compra)
    {
        $sql = "SELECT 
                d.iddetalle_orden_compra,
                d.idorden_compra,
                a.nombre as articulo,
                a.codigo,
                d.cantidad,
                d.precio_compra,
                (d.cantidad*d.precio_compra) as subtotal 
                FROM detalle_orden_compra d 
                INNER JOIN articulo a ON d.idarticulo=a.idarticulo  
                WHERE d.idorden_compra='$idorden_compra'";
        return ejecutarConsulta($sql);
    }

    public function selectSucursal()
    {
        $sql = "SELECT * FROM sucursal WHERE condicion='1' ";
        return ejecutarConsulta($sql);
    }

    // Método para registrar auditoría
    public function registrarAuditoria($idorden_compra, $accion, $descripcion, $idusuario, $datosArticulos = null)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        // Convertimos el arreglo de artículos a formato de texto JSON para guardarlo
        $detalle_json = $datosArticulos ? json_encode($datosArticulos) : '';

        $sql = "INSERT INTO auditoria_orden_compra (idorden_compra, accion, descripcion, detalle_articulos, idusuario, fecha_hora) 
                VALUES ('$idorden_compra', '$accion', '$descripcion', '$detalle_json', '$idusuario', '$fechaHora')";

        return ejecutarConsulta($sql);
    }
}
