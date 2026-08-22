<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Ingreso
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
        $total_comprades_r,
        $idcotizacion
    ) {

        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        $sqlUsuarioK = "SELECT nombre FROM usuario WHERE idusuario='" . $idusuario . "'";
        $resUser = ejecutarConsultaSimpleFila($sqlUsuarioK);
        $nombreUser = $resUser ? $resUser["nombre"] : 'Sistema';

        $sql_sp_guardar_cliente = "CALL sp_guardar_cliente(
                $idcliente,
                '$nombre_cliente',
                '$tipo_documento_cliente',
                '$nit',
                '$direccion_cliente',
                '$telefono_cliente',
                '$correo_cliente',
                '$fechaHora',
                '" . $_SESSION["idsucursal"] . "',
                @residcliente
            )";
        ejecutarConsulta($sql_sp_guardar_cliente);
        // Obtener el ID del cliente generado o actualizado
        $sql_get_id = "SELECT @residcliente AS idcliente";
        $result = ejecutarConsultaSimpleFila($sql_get_id);
        $residcliente = $result['idcliente'];

        ///////           

        $idcompra_r = "";
        if ($idcotizacion) {
            $idcompra_r = $idcotizacion;
        } else {
            $idcompra_r = 0;
        }
        $sql = "INSERT INTO ingreso (idproveedor,
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
                                        total_comprades,fechacreacion,tipo_ingreso_producion,idsucursal,
                                        idorden_compra)
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
                                        '$total_comprades_r','$fechaHora','$tipo_ingreso_producion','" . $_SESSION["idsucursal"] . "',
                                        '$idcompra_r')";
        $idingresonew = ejecutarConsulta_retornarID($sql);

        $num_elementos = 0;
        $sw = true;


        if ($idingresonew) {
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
                $descripcion_detalle = $articulos['descripcion_detalle'][$i];
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

                $sql_detalle = "INSERT INTO detalle_ingreso(idingreso,idarticulo,cantidad,precio_compra,precio_venta,descuento_porcentaje,stock_inventario,
                                                                precio_ventaNocturno,precio_rango1,precio_rango1_Distribuidor,precio_rango1_Mayorista,
                                                                precio_rango2,precio_rango2_DistribuidorDos,precio_rango2_MayoristaDos,
                                                                precio_rango3,precio_rango3_DistribuidorTres,precio_rango3_MayoristaTres,
                                                                precio_unidad,precio_blister,precio_caja,precio_fardo,precio_sacos,precio_paquete,
                                                                precio_07,precio_08,precio_09,precio_10,precio_11,precio_12,precio_13,precio_14,precio_15,precio_16,
                                                                precio_17,precio_18,precio_19,precio_20,cantidadpresentacion,totalcantidadpresentacion,presentacion,
                                                                fechavencimiento,pc_anterior_anterior,descripcion_detalle,idsucursalDestino) 
                                                    VALUES ('$idingresonew','$idarticulo','$cantidad','$precio_compra','$precio_venta','$descuento_porcentaje',
                                                            '$stocksucursal_anterior','$precio_ventaNocturno','$precio_rango1_Mecanico',
                                                            '$precio_rango1_Distribuidor','$precio_rango1_Mayorista','$precio_rango2_MecanicoDos',
                                                            '$precio_rango2_DistribuidorDos','$precio_rango2_MayoristaDos','$precio_rango3_MecanicoTres',
                                                            '$precio_rango3_DistribuidorTres','$precio_rango3_MayoristaTres','$precio_unidad',
                                                            '$precio_blister','$precio_caja','$precio_fardo','$precio_sacos',
                                                            '$precio_paquete','$precio_07','$precio_08','$precio_09','$precio_10',
                                                            '$precio_11','$precio_12','$precio_13','$precio_14','$precio_15',
                                                            '$precio_16','$precio_17','$precio_18','$precio_19',
                                                            '$precio_20','$cantidadpresentacion','$totalcantidadpresentacion','$presentacion','$fechavencimiento','$pc_anterior_anterior',
                                                            '$descripcion_detalle','$idsucursalDestino' )";
                //print_r($sql_detalle);
                ejecutarConsulta($sql_detalle) or $sw = false;
                ////INGRETEGRACION DE INVENTARIO CON PROCEDIMIENTO ALMACENADO
                $sql = "CALL sp_actualizar_stock_y_registrar_operacion_ingreso(
                    $idarticulo,
                    $totalcantidadpresentacion,
                    $precio_compra,
                    $precio_venta,
                    $precio_ventaNocturno,
                    $precio_rango1_Mecanico,
                    $precio_rango1_Distribuidor,
                    $precio_rango1_Mayorista,
                    $precio_rango2_MecanicoDos,
                    $precio_rango2_DistribuidorDos,
                    $precio_rango2_MayoristaDos,
                    $precio_rango3_MecanicoTres,
                    $precio_rango3_DistribuidorTres,
                    $precio_rango3_MayoristaTres,
                    $precio_unidad,
                    $precio_blister,
                    $precio_caja,
                    $precio_fardo,
                    $precio_sacos,
                    $precio_paquete,
                    $precio_07,
                    $precio_08,
                    $precio_09,
                    $precio_10,
                    $precio_11,
                    $precio_12,
                    $precio_13,
                    $precio_14,
                    $precio_15,
                    $precio_16,
                    $precio_17,
                    $precio_18,
                    $precio_19,
                    $precio_20,
                    $stockinven,
                    $idingresonew,
                    '$fechaHora',
                    '$fechavencimiento',
                    $idusuario,
                    $idsucursalDestino
                )";
                ejecutarConsulta($sql);

                $kardex_stock_final = $stocksucursal_anterior + $totalcantidadpresentacion;
                $sqlInsertKardex = "INSERT INTO kardex_movimientos 
                (idarticulo, idsucursal, fecha_hora, concepto, num_documento, cantidad_existente, cantidad_modificacion, 
                tipo_modificacion, cantidad_final, precio, responsable)
                VALUES 
                ('$idarticulo', '$idsucursalDestino', '$fechaHora', 'Ingreso por Compra', '$idingresonew', 
                '$stocksucursal_anterior', '$totalcantidadpresentacion', 'Ingreso', '$kardex_stock_final', 
                '$precio_compra', '$nombreUser')";
                ejecutarConsulta($sqlInsertKardex);
            }
        }

        return $idingresonew;
    }



    //Implementamos un método para insertar registros
    public function insetar_nd(
        $idingreso,
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

        @session_start();
        $idusuario_session = $_SESSION["idusuario"];
        $sqlUsuarioK = "SELECT nombre FROM usuario WHERE idusuario='" . $idusuario_session . "'";
        $resUser = ejecutarConsultaSimpleFila($sqlUsuarioK);
        $nombreUser = $resUser ? $resUser["nombre"] : 'Sistema';

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
                                        total_comprades,fechacreacion,tipo_ingreso_producion,idsucursal,idingreso,motivo_ND,fecha_hora_ND)
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
                                        '$total_comprades','$fechaHora','$tipo_ingreso_producion','" . $_SESSION["idsucursal"] . "','$idingreso','$motivo_ND','$fecha_hora_ND')";
        //return ejecutarConsulta($sql);
        $idingresonew = ejecutarConsulta_retornarID($sql);


        $sqlUpdateingreso = "UPDATE ingreso SET notadebito='SI',idnota_debito='$idingresonew' WHERE idingreso='$idingreso' ";
        ejecutarConsulta($sqlUpdateingreso);


        $num_elementos = 0;
        $sw = true;


        if ($idingresonew) {
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
                $idsucursalDestino = $articulos['idsucursalDestino'][$i];

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
                                                    VALUES ('$idingresonew',
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
                                    WHERE asu.idarticulo='$idarticulo'  and asu.idsucursal='$idsucursalDestino' ";
                $Articulo = ejecutarConsultaSimpleFila($sqlArticulo);

                $t_cantodad = $totalcantidadpresentacion;
                $pc_compras = $precio_compra;
                $stoInventi = $stockinven;
                $stocksucursal_anterior = $Articulo["stocksucursal"];
                $pc_anterior_anterior = $Articulo["pc_anterior"];


                if (($stocksucursal_anterior - $t_cantodad) > 0) {
                    $pc_nuevo = ((($stocksucursal_anterior * $pc_anterior_anterior) - ($t_cantodad * $pc_compras)) / ($stocksucursal_anterior - $t_cantodad));
                } else {
                    // Si el stock queda en 0 o menos (denominador es 0 o negativo), asignamos el precio anterior
                    $pc_nuevo = $pc_anterior_anterior;
                }


                #Actualizar Stock 
                $sqlArticuloStock = "UPDATE articuloxsucursal SET 
                                                stocksucursal = stocksucursal - " . $totalcantidadpresentacion . ",
                                                precio_compra=" . $pc_nuevo . "
                                                WHERE idarticulo ='$idarticulo'  and idsucursal='$idsucursalDestino' ";
                // print_r($sqlArticuloStock);
                ejecutarConsulta($sqlArticuloStock);

                $kardex_stock_final = $stocksucursal_anterior - $totalcantidadpresentacion;
                $sqlInsertKardex = "INSERT INTO kardex_movimientos 
                (idarticulo, idsucursal, fecha_hora, concepto, num_documento, cantidad_existente, cantidad_modificacion, 
                tipo_modificacion, cantidad_final, precio, responsable)
                VALUES 
                ('$idarticulo', '$idsucursalDestino', '$fechaHora', 'Salida por Nota de Débito', '$idingresonew', 
                '$stocksucursal_anterior', '$totalcantidadpresentacion', 'Salida', '$kardex_stock_final', 
                '$pc_compras', '$nombreUser')";
                ejecutarConsulta($sqlInsertKardex);
            }
        }

        return $idingresonew;
    }

    //Implementamos un método para editar registros
    public function editar(
        $idingreso,
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

        $sqlUsuarioK = "SELECT nombre FROM usuario WHERE idusuario='" . $idusuario . "'";
        $resUser = ejecutarConsultaSimpleFila($sqlUsuarioK);
        $nombreUser = $resUser ? $resUser["nombre"] : 'Sistema';

        $sql_sp_guardar_cliente = "CALL sp_guardar_cliente(
                $idcliente,
                '$nombre_cliente',
                '$tipo_documento_cliente',
                '$nit',
                '$direccion_cliente',
                '$telefono_cliente',
                '$correo_cliente',
                '$fechaHora',
                '" . $_SESSION["idsucursal"] . "',
                @residcliente
            )";
        ejecutarConsulta($sql_sp_guardar_cliente);
        // Obtener el ID del cliente generado o actualizado
        $sql_get_id = "SELECT @residcliente AS idcliente";
        $result = ejecutarConsultaSimpleFila($sql_get_id);
        $residcliente = $result['idcliente'];
        ///////           

        $sqlUpdate = "UPDATE ingreso SET 
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
                        WHERE idingreso = '$idingreso'";
        ejecutarConsulta($sqlUpdate);

        $num_elementos = 0;
        $sw = true;

        ////AN ULACION DE OPERACIONES COMPRAS
        $sqlDetalleingreso = "SELECT * FROM detalle_ingreso WHERE idingreso = '$idingreso'";
        $Detalle = ejecutarConsulta($sqlDetalleingreso);

        while ($reg = $Detalle->fetch_object()) {

            // ==========================================
            // 1. STOCK Y PRECIO ACTUAL EN SUCURSAL
            // ==========================================
            $sqlArticulo = "SELECT stocksucursal, precio_compra 
                                FROM articuloxsucursal 
                                WHERE idarticulo = {$reg->idarticulo} 
                                AND idsucursal = '{$reg->idsucursalDestino}'";
            $Articulo = ejecutarConsultaSimpleFila($sqlArticulo);

            $stock_actual = floatval($Articulo["stocksucursal"]);
            $precio_actual = floatval($Articulo["precio_compra"]);

            // ==========================================
            // 2. DATOS DEL INGRESO (ANTES DE EDITAR / ANULAR)
            // ==========================================
            $stock_anterior_compra = floatval($reg->stock_inventario); // stock antes de esta compra
            $cantidad_anulada = floatval($reg->totalcantidadpresentacion);
            $precio_anulado = floatval($reg->precio_compra);
            $pc_anterior_anterior = floatval($reg->pc_anterior_anterior);

            // ==========================================
            // 3. NUEVO STOCK
            // ==========================================
            // Se permite stock negativo (ventas sin stock disponible)
            $nuevo_stock = $stock_actual - $cantidad_anulada;

            // ==========================================
            // 4. NUEVO PRECIO PROMEDIO (LÓGICA CORRECTA)
            // ==========================================

            /*
             * CASO 1:
             * Si antes de la compra el stock era 0,
             * entonces NO hay promedio que recalcular.
             * El precio debe ser el del ingreso que queda
             * o el precio anterior si el stock queda en 0.
             */
            if ($stock_anterior_compra == 0) {

                if ($nuevo_stock > 0) {
                    // Sigue existiendo stock de este ingreso
                    $nuevo_precio = $precio_anulado;
                } else {
                    // Ya no hay stock
                    $nuevo_precio = $pc_anterior_anterior;
                }
            }
            /*
             * CASO 2:
             * Hay compras anteriores → recalcular promedio
             */ elseif ($nuevo_stock > 0) {

                $valor_total_actual = $stock_actual * $precio_actual;
                $valor_anulado = $cantidad_anulada * $precio_anulado;
                $valor_restante = $valor_total_actual - $valor_anulado;

                if ($valor_restante > 0) {
                    $nuevo_precio = $valor_restante / $nuevo_stock;
                } else {
                    $nuevo_precio = $pc_anterior_anterior;
                }
            }
            /*
             * CASO 3:
             * Stock queda en 0 o negativo
             */ else {
                $nuevo_precio = $pc_anterior_anterior;
            }

            // ==========================================
            // 5. ACTUALIZAR ARTICULO X SUCURSAL
            // ==========================================
            $updateArticuloDetalle = "
                    UPDATE articuloxsucursal SET 
                        stocksucursal = {$nuevo_stock},
                        precio_compra = {$nuevo_precio}
                    WHERE idarticulo = {$reg->idarticulo} 
                    AND idsucursal = '{$reg->idsucursalDestino}'
                ";

            ejecutarConsulta($updateArticuloDetalle);

            $sqlInsertKardex = "INSERT INTO kardex_movimientos 
            (idarticulo, idsucursal, fecha_hora, concepto, num_documento, cantidad_existente, cantidad_modificacion, 
            tipo_modificacion, cantidad_final, precio, responsable)
            VALUES 
            ('{$reg->idarticulo}', '{$reg->idsucursalDestino}', '$fechaHora', 'Salida por Reversión de Compra', '$idingreso', 
            '$stock_actual', '{$cantidad_anulada}', 'Salida', '$nuevo_stock', 
            '{$nuevo_precio}', '$nombreUser')";
            ejecutarConsulta($sqlInsertKardex);
        }


        $sqlDetalleIngresoElimminar = "DELETE from detalle_ingreso where idingreso=" . $idingreso . "";
        ejecutarConsulta($sqlDetalleIngresoElimminar);

        $sqlDetalleIngresoElimminar = "DELETE from operaciones_compras_ventas where idingreso=" . $idingreso . "";
        ejecutarConsulta($sqlDetalleIngresoElimminar);
        ////AN ULACION DE OPERACIONES COMPRAS


        if ($idingreso) {
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
                $descripcion_detalle = $articulos['descripcion_detalle'][$i];
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
                $stocksucursal_anterior = floatval($Articulo["stocksucursal"]);
                $pc_anterior_anterior = floatval($Articulo["pc_anterior"]);
                $totalcantidadpresentacion = floatval($totalcantidadpresentacion);
                $precio_compra = floatval($precio_compra);

                // ==========================================
                // CALCULO DEL PRECIO PROMEDIO PONDERADO
                // ==========================================
                // Fórmula: precio_promedio = (valor_total_nuevo + valor_total_anterior) / (cantidad_nueva + stock_anterior)
                // Esta fórmula es correcta y equivalente a la lógica de anulación pero en sentido inverso

                $valor_total_actual = $totalcantidadpresentacion * $precio_compra;
                $valor_total_anterior = $stocksucursal_anterior * $pc_anterior_anterior;
                $stock_total = $totalcantidadpresentacion + $stocksucursal_anterior;

                // Validación para evitar división por cero
                if ($stock_total > 0) {
                    $precio_promedio = ($valor_total_actual + $valor_total_anterior) / $stock_total;
                } else {
                    // Si ambos son 0, usar el precio de compra actual
                    $precio_promedio = $precio_compra;
                }


                $sql_detalle = "INSERT INTO detalle_ingreso(idingreso,idarticulo,cantidad,
                        precio_compra,precio_venta,descuento_porcentaje,stock_inventario,
                        precio_ventaNocturno,precio_rango1,precio_rango1_Distribuidor,precio_rango1_Mayorista,
                        precio_rango2,precio_rango2_DistribuidorDos,precio_rango2_MayoristaDos,precio_rango3,
                        precio_rango3_DistribuidorTres,precio_rango3_MayoristaTres,precio_unidad,precio_blister,
                        precio_caja,precio_fardo,precio_sacos,precio_paquete,precio_07,
                        precio_08,precio_09,precio_10,precio_11,precio_12,precio_13,precio_14,
                        precio_15,precio_16,precio_17,precio_18,precio_19,precio_20,
                        cantidadpresentacion,totalcantidadpresentacion,presentacion,
                        fechavencimiento,pc_anterior_anterior,descripcion_detalle,idsucursalDestino) 
                    VALUES ('$idingreso','$idarticulo','$cantidad','$precio_compra','$precio_venta',
                            '$descuento_porcentaje','$stocksucursal_anterior','$precio_ventaNocturno','$precio_rango1_Mecanico',
                            '$precio_rango1_Distribuidor','$precio_rango1_Mayorista','$precio_rango2_MecanicoDos','$precio_rango2_DistribuidorDos',
                            '$precio_rango2_MayoristaDos','$precio_rango3_MecanicoTres','$precio_rango3_DistribuidorTres','$precio_rango3_MayoristaTres',
                            '$precio_unidad','$precio_blister','$precio_caja','$precio_fardo','$precio_sacos','$precio_paquete',
                            '$precio_07','$precio_08','$precio_09','$precio_10','$precio_11','$precio_12','$precio_13','$precio_14',
                            '$precio_15','$precio_16','$precio_17','$precio_18','$precio_19','$precio_20','$cantidadpresentacion',
                            '$totalcantidadpresentacion','$presentacion','$fechavencimiento','$pc_anterior_anterior','$descripcion_detalle','$idsucursalDestino' )";
                //print_r($sql_detalle);
                ejecutarConsulta($sql_detalle) or $sw = false;


                /////para actualizar stock y precio en articuloxsucursal
                $sqlArticulo = "SELECT a.tipo_producto
                                FROM articulo a
                                WHERE a.idarticulo='$idarticulo'  ";
                $Articulo = ejecutarConsultaSimpleFila($sqlArticulo);
                $tipo_producto = $Articulo["tipo_producto"];

                if ($tipo_producto != "Servicios" && $tipo_producto != "Combos") {
                    // Actualizar stock y precio promedio en articuloxsucursal
                    $nuevo_stock = $stocksucursal_anterior + $totalcantidadpresentacion;

                    $sqlUpdateArticulo = "UPDATE articuloxsucursal SET 
                                        stocksucursal = stocksucursal + '$totalcantidadpresentacion',
                                        precio_compra = '$precio_promedio',
                                        precio_venta = '$precio_venta',
                                        precio_rango1_Mecanico = '$precio_rango1_Mecanico',
                                        precio_rango1_Distribuidor = '$precio_rango1_Distribuidor',
                                        precio_rango1_Mayorista = '$precio_rango1_Mayorista',
                                        precio_rango2_MecanicoDos = '$precio_rango2_MecanicoDos',
                                        precio_rango2_DistribuidorDos = '$precio_rango2_DistribuidorDos',
                                        precio_rango2_MayoristaDos = '$precio_rango2_MayoristaDos',
                                        precio_rango3_MecanicoTres = '$precio_rango3_MecanicoTres',
                                        precio_rango3_DistribuidorTres = '$precio_rango3_DistribuidorTres',
                                        precio_rango3_MayoristaTres = '$precio_rango3_MayoristaTres',
                                        precio_unidad = '$precio_unidad',
                                        precio_blister = '$precio_blister',
                                        precio_caja = '$precio_caja',
                                        precio_fardo = '$precio_fardo',
                                        precio_sacos = '$precio_sacos',
                                        precio_paquete = '$precio_paquete',
                                        precio_07 = '$precio_07',
                                        precio_08 = '$precio_08',
                                        precio_09 = '$precio_09',
                                        precio_10 = '$precio_10',
                                        precio_11 = '$precio_11',
                                        precio_12 = '$precio_12',
                                        precio_13 = '$precio_13',
                                        precio_14 = '$precio_14',
                                        precio_15 = '$precio_15',
                                        precio_16 = '$precio_16',
                                        precio_17 = '$precio_17',
                                        precio_18 = '$precio_18',
                                        precio_19 = '$precio_19',
                                        precio_20 = '$precio_20'
                                            WHERE idarticulo = '$idarticulo' 
                                            AND idsucursal = '$idsucursalDestino'";
                    ejecutarConsulta($sqlUpdateArticulo);

                    $sqlInsertKardex = "INSERT INTO kardex_movimientos 
                    (idarticulo, idsucursal, fecha_hora, concepto, num_documento, cantidad_existente, cantidad_modificacion, 
                    tipo_modificacion, cantidad_final, precio, responsable)
                    VALUES 
                    ('$idarticulo', '$idsucursalDestino', '$fechaHora', 'Ingreso por Edición de Compra', '$idingreso', 
                    '$stocksucursal_anterior', '$totalcantidadpresentacion', 'Ingreso', '$nuevo_stock', 
                    '$precio_compra', '$nombreUser')";
                    ejecutarConsulta($sqlInsertKardex);
                }

                $sqlInsertOperacionesComprasVentas = "INSERT INTO operaciones_compras_ventas (
                    idingreso, idventa, idtraladosucursal, idtraladosucursal_entrada,
                    iddevolucion, cantidad_compras, cantidad_ventas, cantidad_entrada,
                    cantidad_devolucion, cantidad_salida, stock_inventario, fecha_horaCreacion,
                    idarticulo, idusuario, idsucursal, estado, fecha_vencimiento, saldo
                    ) VALUES (
                        '$idingreso', 0, 0, 0,
                        0, '$totalcantidadpresentacion', 0, 0,
                        0, 0, '$stockinven', '$fechaHora',
                        '$idarticulo', '$idusuario', '$idsucursalDestino', 'Aceptado', 
                        '$fechavencimiento', '$totalcantidadpresentacion')";
                ejecutarConsulta($sqlInsertOperacionesComprasVentas);


                /////FIN INTEGRACION DE INVENTARIO CON PROCEDIMIENTO ALMACENADO
            }
        }

        return $idingreso;
    }
    //Implementamos un método para anular categorías
    public function anular($idingreso)
    {
        // Verificar si existe un ingreso posterior
        $sqlValidar = "SELECT COUNT(*) as total FROM ingreso WHERE idingreso > '$idingreso'";
        $resultadoValidar = ejecutarConsultaSimpleFila($sqlValidar);

        if ($resultadoValidar['total'] > 0) {
            return [
                "status" => false,
                "message" => "No se puede anular la operación porque ya existe un ingreso posterior registrado.",
            ];
        }

        // Continuar con la anulación si no hay ingresos posteriores
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        @session_start();
        $idusuario_session = $_SESSION["idusuario"];
        $sqlUsuarioK = "SELECT nombre FROM usuario WHERE idusuario='" . $idusuario_session . "'";
        $resUser = ejecutarConsultaSimpleFila($sqlUsuarioK);
        $nombreUser = $resUser ? $resUser["nombre"] : 'Sistema';

        $sql = "UPDATE ingreso SET estado='Anulado' WHERE idingreso='$idingreso'";
        ejecutarConsulta($sql);

        $sqlOperacionesCompraVenta = "UPDATE operaciones_compras_ventas SET estado='Anulado' WHERE idingreso='$idingreso'";
        ejecutarConsulta($sqlOperacionesCompraVenta);

        $sqlDetalleingreso = "SELECT * FROM detalle_ingreso WHERE idingreso='$idingreso'";
        $Detalle = ejecutarConsulta($sqlDetalleingreso);

        while ($reg = $Detalle->fetch_object()) {
            $sqlArticulo1 = "SELECT stocksucursal FROM articuloxsucursal WHERE idarticulo='{$reg->idarticulo}' AND idsucursal='{$reg->idsucursalDestino}'";
            $Articulo1 = ejecutarConsultaSimpleFila($sqlArticulo1);
            $stock_actual = $Articulo1 ? floatval($Articulo1["stocksucursal"]) : 0;

            $updateArticuloDetalle = "UPDATE articuloxsucursal SET 
                                            stocksucursal = stocksucursal - " . $reg->totalcantidadpresentacion . ",
                                            precio_compra = " . $reg->pc_anterior_anterior . "
                                        WHERE idarticulo = " . $reg->idarticulo . " 
                                        AND idsucursal = '" . $reg->idsucursalDestino . "'";
            ejecutarConsulta($updateArticuloDetalle);

            $kardex_stock_final = $stock_actual - floatval($reg->totalcantidadpresentacion);
            $sqlInsertKardex = "INSERT INTO kardex_movimientos 
            (idarticulo, idsucursal, fecha_hora, concepto, num_documento, cantidad_existente, cantidad_modificacion, 
            tipo_modificacion, cantidad_final, precio, responsable)
            VALUES 
            ('{$reg->idarticulo}', '{$reg->idsucursalDestino}', '$fechaHora', 'Salida por Anulación de Compra', '$idingreso', 
            '$stock_actual', '{$reg->totalcantidadpresentacion}', 'Salida', '$kardex_stock_final', 
            '{$reg->precio_compra}', '$nombreUser')";
            ejecutarConsulta($sqlInsertKardex);
        }

        return [
            "status" => true,
            "message" => "Operación anulada con éxito.",
        ];
    }



    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idingreso)
    {

        $sql = "SELECT 
                i.idingreso,
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
                i.observacion_orden_compra
            FROM ingreso i 
            INNER JOIN persona p ON i.idproveedor=p.idpersona 
            INNER JOIN usuario u ON i.idusuario=u.idusuario 
            WHERE i.idingreso='$idingreso' and i.notadebito='NO' and i.estado='Aceptado'";
        // print_r($sql);
        return ejecutarConsultaSimpleFila($sql);
    }

    public function detalleingreso($idingreso)
    {

        $sqldetalle = "SELECT  
                d.iddetalle_ingreso,
                d.idingreso,
                d.idarticulo, 
                d.cantidad,
                d.precio_compra,
                d.precio_venta,
                d.descuento_porcentaje,
                asu.stocksucursal as stock,
                a.nombre as articulo,
                d.precio_ventaNocturno,
                d.precio_rango1 AS detalle_precio_rango1_Mecanico,
                d.precio_rango1_Distribuidor AS detalle_precio_rango1_Distribuidor,
                d.precio_rango1_Mayorista AS detalle_precio_rango1_Mayorista,
                d.precio_rango2 as detalle_precio_rango2_MecanicoDos,
                d.precio_rango2_DistribuidorDos as detalle_precio_rango2_DistribuidorDos,
                d.precio_rango2_MayoristaDos as detalle_precio_rango2_MayoristaDos,
                d.precio_rango3 as detalle_precio_rango3_MecanicoTres,
                d.precio_rango3_DistribuidorTres as detalle_precio_rango3_DistribuidorTres,
                d.precio_rango3_MayoristaTres as detalle_precio_rango3_MayoristaTres,
                d.precio_unidad as detalle_precio_unidad, 
                d.precio_blister as detalle_precio_blister,
                d.precio_caja as detalle_precio_caja,
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
                d.descripcion_detalle,
                DATE(d.fechavencimiento) as fechavencimiento,
                d.cantidadpresentacion,
                d.totalcantidadpresentacion,
                d.presentacion,
                d.idsucursalDestino,
                s.nombre as sucursalDestino,
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
                asu.precio_13,
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
                asu.precio_20
            FROM detalle_ingreso d
            INNER JOIN articulo a ON a.idarticulo=d.idarticulo
            INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
            INNER JOIN sucursal s ON s.idsucursal=d.idsucursalDestino
             where  asu.idsucursal='" . $_SESSION["idsucursal"] . "' and  d.idingreso='$idingreso'
             ORDER BY d.iddetalle_ingreso asc";

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
                i.idingreso,
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
                i.estado 
        FROM ingreso i 
        INNER JOIN persona p ON i.idproveedor=p.idpersona 
        INNER JOIN usuario u ON i.idusuario=u.idusuario  
        where i.idsucursal='" . $_SESSION["idsucursal"] . "'  and  
                DATE(i.fecha_hora)>='$fecha_inicio_reporte' 
                AND DATE(i.fecha_hora)<='$fecha_fin_reporte' 
        ORDER BY i.idingreso DESC ";
        return ejecutarConsulta($sql);
    }


    public function listarND($fecha_inicio_reporte, $fecha_fin_reporte)
    {
        $sql = "SELECT 
                i.idnota_debito,
                i.idingreso,
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
        where i.idsucursal='" . $_SESSION["idsucursal"] . "' and  
                DATE(i.fecha_hora_ND)>='$fecha_inicio_reporte' 
                AND DATE(i.fecha_hora_ND)<='$fecha_fin_reporte'

        ORDER BY i.idnota_debito DESC";
        return ejecutarConsulta($sql);
    }


    public function ingresocabecera($idingreso)
    {
        $sql = "SELECT 
                i.idingreso,
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
                FROM ingreso i
                INNER JOIN persona p ON p.idpersona=i.idproveedor
                INNER JOIN usuario u ON u.idusuario=i.idusuario
                WHERE i.idingreso='$idingreso'";
        return ejecutarConsulta($sql);
    }

    public function ingresodetalle($idingreso)
    {
        $sql = "SELECT 
                d.iddetalle_ingreso,
                d.idingreso,
                a.nombre as articulo,
                a.codigo,
                d.cantidad,
                d.precio_compra,
                (d.cantidad*d.precio_compra) as subtotal 
                FROM detalle_ingreso d 
                INNER JOIN articulo a ON d.idarticulo=a.idarticulo  
                WHERE d.idingreso='$idingreso'";
        return ejecutarConsulta($sql);
    }

    public function listar_preciosxproveedor()
    {
        $sql = "SELECT
            di.cantidad,
            di.precio_compra,
            ar.nombre AS nombre_articulo,
            ar.codigo,
            ca.nombre AS nombre_categoria,
            p.nombre AS nombre_proveedor,
            p.num_documento,
            p.telefono,
            di.totalcantidadpresentacion,
            DATE(i.fecha_hora) AS fecha
        FROM detalle_ingreso di
        INNER JOIN ingreso i ON di.idingreso = i.idingreso
        INNER JOIN persona p ON i.idproveedor = p.idpersona
        INNER JOIN articulo ar ON di.idarticulo = ar.idarticulo
        INNER JOIN categoria ca ON ar.idcategoria = ca.idcategoria";
        return ejecutarConsulta($sql);
    }
}
