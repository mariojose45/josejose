<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

if (!isset($_SESSION["nombre"])) {
    die('No tienes acceso favor volver a logearte');
}

class Salidaprosucursal
{
    //Implementamos nuestro constructor
    public function __construct() {}

    //Implementamos un método para insertar registros
    public function insertar(
        $idsucursal,
        $fecha_hora,
        $descripcion_salida_producto,
        $idusuario,
        $idsucursalorigen,
        $datosArticulos,
        $total_venta_r
    ) {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        // Obtener nombre del usuario para el Kardex
        $sqlUsuarioK = "SELECT nombre FROM usuario WHERE idusuario='" . $idusuario . "'";
        $resUser = ejecutarConsultaSimpleFila($sqlUsuarioK);
        $nombreUser = $resUser ? $resUser["nombre"] : 'Sistema';

        $sql = "INSERT INTO traslado_sucursal (idsucursaldestino,idusuario,idsucursalorigen,fecha_hora,descripcion_salida_producto,
        estado,total_venta)
        VALUES ('$idsucursal','$idusuario','$idsucursalorigen','$fecha_hora','$descripcion_salida_producto',
        'PRODUCTO INGRESADO A SUCURSAL','$total_venta_r')";
        //return ejecutarConsulta($sql);
        $idtraladosucursalnew = ejecutarConsulta_retornarID($sql);




        $num_elementos = 0;
        $sw = true;

        if ($idtraladosucursalnew) {
            $articulos = $datosArticulos['articulos'];
            $numArticulos = count($articulos['idarticulo']);
            for ($i = 0; $i < $numArticulos; $i++) {
                $idarticulo = $articulos['idarticulo'][$i];
                $cantidadpresentacion = $articulos['cantidadpresentacion'][$i];
                $cantidad = $articulos['cantidad'][$i];
                $totalcantidadpresentacion = $articulos['totalcantidadpresentacion'][$i];
                $presentacion = $articulos['presentacion'][$i];
                $descripcion_detalle = $articulos['descripcion_detalle'][$i];
                $precio_venta = $articulos['precio_venta'][$i];

                $sqlArticulo1 = "SELECT 
                            asu.precio_compra as pc_anterior, 
                            asu.stocksucursal 
                        FROM articuloxsucursal asu
                    WHERE asu.idarticulo='$idarticulo'  and asu.idsucursal='$idsucursalorigen' ";
                $Articulo1 = ejecutarConsultaSimpleFila($sqlArticulo1);
                $stocksucursal_anterior = $Articulo1["stocksucursal"];
                $precio_compra = $Articulo1["pc_anterior"] ? $Articulo1["pc_anterior"] : 0;

                $sql_detalle = "INSERT INTO detalle_traslado_sucursal(idtraladosucursal, idarticulo,cantidad,descripcion_detalle,
                idsucursalorigen,idsucursaldestino,precio_venta,cantidadpresentacion,
                totalcantidadpresentacion,presentacion) 
                VALUES ('$idtraladosucursalnew', '$idarticulo','$cantidad','$descripcion_detalle','$idsucursalorigen',
                '$idsucursal','$precio_venta','$cantidadpresentacion','$totalcantidadpresentacion',
                '$presentacion')";
                ejecutarConsulta($sql_detalle) or $sw = false;


                $sqlArticuloStockSalida = "UPDATE articuloxsucursal SET stocksucursal = stocksucursal - " . $totalcantidadpresentacion . " ,
                        precio_venta = " . $precio_venta . " ,precio_unidad=" . $precio_venta . "  
                        WHERE idarticulo =$idarticulo    and idsucursal='$idsucursalorigen' ";
                ejecutarConsulta($sqlArticuloStockSalida);

                // KARDEX ORIGEN (SALIDA)
                $kardex_stock_final_origen = $stocksucursal_anterior - $totalcantidadpresentacion;
                $sqlInsertKardexOrigen = "INSERT INTO kardex_movimientos 
                (idarticulo, idsucursal, fecha_hora, concepto, num_documento, cantidad_existente, cantidad_modificacion, 
                tipo_modificacion, cantidad_final, precio, responsable)
                VALUES 
                ('$idarticulo', '$idsucursalorigen', '$fechaHora', 'Salida por Traslado', '$idtraladosucursalnew', 
                '$stocksucursal_anterior', '$totalcantidadpresentacion', 'Salida', '$kardex_stock_final_origen', 
                '$precio_compra', '$nombreUser')";
                ejecutarConsulta($sqlInsertKardexOrigen);

                $sqlCheckDestino = "SELECT idarticuloxsucursal, stocksucursal FROM articuloxsucursal WHERE idarticulo='$idarticulo' AND idsucursal='$idsucursal'";
                $checkDestino = ejecutarConsultaSimpleFila($sqlCheckDestino);
                $stock_anterior_destino = 0;

                if ($checkDestino) {
                    $stock_anterior_destino = $checkDestino["stocksucursal"];
                    $sqlArticuloStockEntrada = "UPDATE articuloxsucursal SET stocksucursal = stocksucursal + " . $totalcantidadpresentacion . " ,
                            precio_venta = " . $precio_venta . " ,precio_unidad=" . $precio_venta . "  
                            WHERE idarticulo =$idarticulo    and idsucursal='$idsucursal' ";
                    ejecutarConsulta($sqlArticuloStockEntrada);
                } else {
                    $sqlArticuloStockEntrada = "INSERT INTO articuloxsucursal (idarticulo, idsucursal, idusuario, stocksucursal, 
                        stockminimo, precio_compra, precio_venta, precio_ventaNocturno, descuento_porcentaje, 
                        precio_descuento, precio_rango1, precio_rango2, precio_rango3, 
                        nombre_01, stock_unidad, precio_unidad, 
                        nombre_02, stock_blister, precio_blister, 
                        nombre_03, stock_caja, precio_caja, 
                        nombre_04, stock_fardo, precio_fardo, 
                        nombre_05, stock_sacos, precio_sacos, 
                        nombre_06, stock_paquete, precio_paquete, 
                        nombre_07, stock_07, precio_07, 
                        nombre_08, stock_08, precio_08, 
                        nombre_09, stock_09, precio_09, 
                        nombre_10, stock_10, precio_10, 
                        nombre_11, stock_11, precio_11, 
                        nombre_12, stock_12, precio_12, 
                        nombre_13, stock_13, precio_13, 
                        nombre_14, stock_14, precio_14, 
                        nombre_15, stock_15, precio_15, 
                        nombre_16, stock_16, precio_16, 
                        nombre_17, stock_17, precio_17, 
                        nombre_18, stock_18, precio_18, 
                        nombre_19, stock_19, precio_19, 
                        nombre_20, stock_20, precio_20, 
                        condicion, ganacia_articulo, 
                        tipo_ganacia, fecha_creacion, producto_consignacion, aplica_impuestos, precio_rango1_Dos,
                        precio_rango2_Dos, precio_rango3_Dos, precio_rango1_Mecanico, precio_rango2_MecanicoDos,
                        precio_rango3_MecanicoTres, precio_rango1_Distribuidor, precio_rango2_DistribuidorDos,
                        precio_rango3_DistribuidorTres, precio_rango1_Mayorista, precio_rango2_MayoristaDos,
                        precio_rango3_MayoristaTres, codigo_sku, stockmaximo, precio_activado, descripcion_2, pocentaje_ganacia) 
                        SELECT idarticulo, '$idsucursal', '$idusuario', '$totalcantidadpresentacion', 
                        stockminimo, precio_compra, '$precio_venta', precio_ventaNocturno, descuento_porcentaje, 
                        precio_descuento, precio_rango1, precio_rango2, precio_rango3, 
                        nombre_01, stock_unidad, '$precio_venta', 
                        nombre_02, stock_blister, precio_blister, 
                        nombre_03, stock_caja, precio_caja, 
                        nombre_04, stock_fardo, precio_fardo, 
                        nombre_05, stock_sacos, precio_sacos, 
                        nombre_06, stock_paquete, precio_paquete, 
                        nombre_07, stock_07, precio_07, 
                        nombre_08, stock_08, precio_08, 
                        nombre_09, stock_09, precio_09, 
                        nombre_10, stock_10, precio_10, 
                        nombre_11, stock_11, precio_11, 
                        nombre_12, stock_12, precio_12, 
                        nombre_13, stock_13, precio_13, 
                        nombre_14, stock_14, precio_14, 
                        nombre_15, stock_15, precio_15, 
                        nombre_16, stock_16, precio_16, 
                        nombre_17, stock_17, precio_17, 
                        nombre_18, stock_18, precio_18, 
                        nombre_19, stock_19, precio_19, 
                        nombre_20, stock_20, precio_20, 
                        '1', ganacia_articulo, 
                        tipo_ganacia, '$fechaHora', producto_consignacion, aplica_impuestos, precio_rango1_Dos,
                        precio_rango2_Dos, precio_rango3_Dos, precio_rango1_Mecanico, precio_rango2_MecanicoDos,
                        precio_rango3_MecanicoTres, precio_rango1_Distribuidor, precio_rango2_DistribuidorDos,
                        precio_rango3_DistribuidorTres, precio_rango1_Mayorista, precio_rango2_MayoristaDos,
                        precio_rango3_MayoristaTres, codigo_sku, stockmaximo, precio_activado, descripcion_2, pocentaje_ganacia
                        FROM articuloxsucursal WHERE idarticulo='$idarticulo' AND idsucursal='$idsucursalorigen'";
                    ejecutarConsulta($sqlArticuloStockEntrada);
                }

                // KARDEX DESTINO (INGRESO)
                $kardex_stock_final_destino = $stock_anterior_destino + $totalcantidadpresentacion;
                $sqlInsertKardexDestino = "INSERT INTO kardex_movimientos 
                (idarticulo, idsucursal, fecha_hora, concepto, num_documento, cantidad_existente, cantidad_modificacion, 
                tipo_modificacion, cantidad_final, precio, responsable)
                VALUES 
                ('$idarticulo', '$idsucursal', '$fechaHora', 'Ingreso por Traslado', '$idtraladosucursalnew', 
                '$stock_anterior_destino', '$totalcantidadpresentacion', 'Ingreso', '$kardex_stock_final_destino', 
                '$precio_compra', '$nombreUser')";
                ejecutarConsulta($sqlInsertKardexDestino);


            }
        }


        return $idtraladosucursalnew;
    }

    public function editarSalida(
        $idtraladosucursal,
        $idsucursal,
        $fecha_hora,
        $descripcion_salida_producto,
        $idusuario,
        $idsucursalorigen,
        $datosArticulos,
        $total_venta_r
    ) {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');
        
        $sqlUsuarioK = "SELECT nombre FROM usuario WHERE idusuario='" . $idusuario . "'";
        $resUser = ejecutarConsultaSimpleFila($sqlUsuarioK);
        $nombreUser = $resUser ? $resUser["nombre"] : 'Sistema';

        //ACTUALIZAR EN LA TABLA PADRE
        $sqlUpdate = "UPDATE traslado_sucursal SET 
                            idsucursaldestino='$idsucursal',
                            idusuario='$idusuario',
                            idsucursalorigen='$idsucursalorigen',
                            fecha_hora = '$fecha_hora',
                            descripcion_salida_producto = '$descripcion_salida_producto',
                            total_venta = '$total_venta_r'
                        WHERE idtraladosucursal = '$idtraladosucursal'";
        ejecutarConsulta($sqlUpdate);

        //ELIMINAR EL DETALLE e INSERTAR DE NUEVO
        // Obtener el detalle para revertir el inventario
        $sqlDetalle = "SELECT idarticulo, totalcantidadpresentacion, idsucursalorigen, idsucursaldestino 
                       FROM detalle_traslado_sucursal 
                       WHERE idtraladosucursal='$idtraladosucursal'";
        $rspta = ejecutarConsulta($sqlDetalle);

        while ($reg = $rspta->fetch_object()) {
            $idarticulo = $reg->idarticulo;
            $totalcantidadpresentacion = $reg->totalcantidadpresentacion;
            $idsucursalorigen = $reg->idsucursalorigen;
            $idsucursaldestino = $reg->idsucursaldestino;

            $sqlArticuloOrigen = "SELECT precio_compra, stocksucursal FROM articuloxsucursal WHERE idarticulo='$idarticulo' AND idsucursal='$idsucursalorigen'";
            $ArticuloOrigen = ejecutarConsultaSimpleFila($sqlArticuloOrigen);
            $stock_origen = $ArticuloOrigen ? $ArticuloOrigen["stocksucursal"] : 0;
            $precio_compra = $ArticuloOrigen ? $ArticuloOrigen["precio_compra"] : 0;

            $sqlArticuloDestino = "SELECT stocksucursal FROM articuloxsucursal WHERE idarticulo='$idarticulo' AND idsucursal='$idsucursaldestino'";
            $ArticuloDestino = ejecutarConsultaSimpleFila($sqlArticuloDestino);
            $stock_destino = $ArticuloDestino ? $ArticuloDestino["stocksucursal"] : 0;

            // Revertir salida: sumar al origen
            $sqlArticuloStockSalida = "UPDATE articuloxsucursal SET stocksucursal = stocksucursal + " . $totalcantidadpresentacion . " 
                    WHERE idarticulo = $idarticulo AND idsucursal = '$idsucursalorigen'";
            ejecutarConsulta($sqlArticuloStockSalida);

            $kardex_stock_final_origen = $stock_origen + $totalcantidadpresentacion;
            $sqlInsertKardexOrigen = "INSERT INTO kardex_movimientos 
            (idarticulo, idsucursal, fecha_hora, concepto, num_documento, cantidad_existente, cantidad_modificacion, 
            tipo_modificacion, cantidad_final, precio, responsable)
            VALUES 
            ('$idarticulo', '$idsucursalorigen', '$fechaHora', 'Ingreso por Reversión de Traslado', '$idtraladosucursal', 
            '$stock_origen', '$totalcantidadpresentacion', 'Ingreso', '$kardex_stock_final_origen', 
            '$precio_compra', '$nombreUser')";
            ejecutarConsulta($sqlInsertKardexOrigen);

            // Revertir entrada: restar al destino
            $sqlArticuloStockEntrada = "UPDATE articuloxsucursal SET stocksucursal = stocksucursal - " . $totalcantidadpresentacion . " 
                    WHERE idarticulo = $idarticulo AND idsucursal = '$idsucursaldestino'";
            ejecutarConsulta($sqlArticuloStockEntrada);

            $kardex_stock_final_destino = $stock_destino - $totalcantidadpresentacion;
            $sqlInsertKardexDestino = "INSERT INTO kardex_movimientos 
            (idarticulo, idsucursal, fecha_hora, concepto, num_documento, cantidad_existente, cantidad_modificacion, 
            tipo_modificacion, cantidad_final, precio, responsable)
            VALUES 
            ('$idarticulo', '$idsucursaldestino', '$fechaHora', 'Salida por Reversión de Traslado', '$idtraladosucursal', 
            '$stock_destino', '$totalcantidadpresentacion', 'Salida', '$kardex_stock_final_destino', 
            '$precio_compra', '$nombreUser')";
            ejecutarConsulta($sqlInsertKardexDestino);
        }


        $sqlDetalleIngresoElimminar = "DELETE from detalle_traslado_sucursal where idtraladosucursal=" . $idtraladosucursal . "";
        ejecutarConsulta($sqlDetalleIngresoElimminar);

        $sqlDetalleIngresoElimminar = "DELETE from operaciones_compras_ventas where idtraladosucursal=" . $idtraladosucursal . "";
        ejecutarConsulta($sqlDetalleIngresoElimminar);



        //INSERTAR
        $num_elementos = 0;
        $sw = true;
        if ($idtraladosucursal) {
            $articulos = $datosArticulos['articulos'];
            $numArticulos = count($articulos['idarticulo']);
            for ($i = 0; $i < $numArticulos; $i++) {
                $idarticulo = $articulos['idarticulo'][$i];
                $cantidadpresentacion = $articulos['cantidadpresentacion'][$i];
                $cantidad = $articulos['cantidad'][$i];
                $totalcantidadpresentacion = $articulos['totalcantidadpresentacion'][$i];
                $presentacion = $articulos['presentacion'][$i];
                $descripcion_detalle = $articulos['descripcion_detalle'][$i];
                $precio_venta = $articulos['precio_venta'][$i];

                $sqlArticulo1 = "SELECT 
                            asu.precio_compra as pc_anterior, 
                            asu.stocksucursal 
                        FROM articuloxsucursal asu
                    WHERE asu.idarticulo='$idarticulo'  and asu.idsucursal='$idsucursalorigen' ";
                $Articulo1 = ejecutarConsultaSimpleFila($sqlArticulo1);
                $stocksucursal_anterior = $Articulo1["stocksucursal"];
                $precio_compra = $Articulo1["pc_anterior"] ? $Articulo1["pc_anterior"] : 0;

                $sql_detalle = "INSERT INTO detalle_traslado_sucursal(idtraladosucursal, idarticulo,cantidad,descripcion_detalle,
                idsucursalorigen,idsucursaldestino,precio_venta,cantidadpresentacion,
                totalcantidadpresentacion,presentacion) 
                VALUES ('$idtraladosucursal', '$idarticulo','$cantidad','$descripcion_detalle','$idsucursalorigen',
                '$idsucursal','$precio_venta','$cantidadpresentacion','$totalcantidadpresentacion',
                '$presentacion')";
                ejecutarConsulta($sql_detalle) or $sw = false;


                $sqlArticuloStockSalida = "UPDATE articuloxsucursal SET stocksucursal = stocksucursal - " . $totalcantidadpresentacion . " ,
                        precio_venta = " . $precio_venta . " ,precio_unidad=" . $precio_venta . "  
                        WHERE idarticulo =$idarticulo    and idsucursal='$idsucursalorigen' ";
                ejecutarConsulta($sqlArticuloStockSalida);

                $kardex_stock_final_origen = $stocksucursal_anterior - $totalcantidadpresentacion;
                $sqlInsertKardexOrigen = "INSERT INTO kardex_movimientos 
                (idarticulo, idsucursal, fecha_hora, concepto, num_documento, cantidad_existente, cantidad_modificacion, 
                tipo_modificacion, cantidad_final, precio, responsable)
                VALUES 
                ('$idarticulo', '$idsucursalorigen', '$fechaHora', 'Salida por Edición de Traslado', '$idtraladosucursal', 
                '$stocksucursal_anterior', '$totalcantidadpresentacion', 'Salida', '$kardex_stock_final_origen', 
                '$precio_compra', '$nombreUser')";
                ejecutarConsulta($sqlInsertKardexOrigen);

                $sqlCheckDestino = "SELECT idarticuloxsucursal, stocksucursal FROM articuloxsucursal WHERE idarticulo='$idarticulo' AND idsucursal='$idsucursal'";
                $checkDestino = ejecutarConsultaSimpleFila($sqlCheckDestino);
                $stock_anterior_destino = 0;

                if ($checkDestino) {
                    $stock_anterior_destino = $checkDestino["stocksucursal"];
                    $sqlArticuloStockEntrada = "UPDATE articuloxsucursal SET stocksucursal = stocksucursal + " . $totalcantidadpresentacion . " ,
                            precio_venta = " . $precio_venta . " ,precio_unidad=" . $precio_venta . "  
                            WHERE idarticulo =$idarticulo    and idsucursal='$idsucursal' ";
                    ejecutarConsulta($sqlArticuloStockEntrada);
                } else {
                    $sqlArticuloStockEntrada = "INSERT INTO articuloxsucursal (idarticulo, idsucursal, idusuario, stocksucursal, 
                        stockminimo, precio_compra, precio_venta, precio_ventaNocturno, descuento_porcentaje, 
                        precio_descuento, precio_rango1, precio_rango2, precio_rango3, 
                        nombre_01, stock_unidad, precio_unidad, 
                        nombre_02, stock_blister, precio_blister, 
                        nombre_03, stock_caja, precio_caja, 
                        nombre_04, stock_fardo, precio_fardo, 
                        nombre_05, stock_sacos, precio_sacos, 
                        nombre_06, stock_paquete, precio_paquete, 
                        nombre_07, stock_07, precio_07, 
                        nombre_08, stock_08, precio_08, 
                        nombre_09, stock_09, precio_09, 
                        nombre_10, stock_10, precio_10, 
                        nombre_11, stock_11, precio_11, 
                        nombre_12, stock_12, precio_12, 
                        nombre_13, stock_13, precio_13, 
                        nombre_14, stock_14, precio_14, 
                        nombre_15, stock_15, precio_15, 
                        nombre_16, stock_16, precio_16, 
                        nombre_17, stock_17, precio_17, 
                        nombre_18, stock_18, precio_18, 
                        nombre_19, stock_19, precio_19, 
                        nombre_20, stock_20, precio_20, 
                        condicion, ganacia_articulo, 
                        tipo_ganacia, fecha_creacion, producto_consignacion, aplica_impuestos, precio_rango1_Dos,
                        precio_rango2_Dos, precio_rango3_Dos, precio_rango1_Mecanico, precio_rango2_MecanicoDos,
                        precio_rango3_MecanicoTres, precio_rango1_Distribuidor, precio_rango2_DistribuidorDos,
                        precio_rango3_DistribuidorTres, precio_rango1_Mayorista, precio_rango2_MayoristaDos,
                        precio_rango3_MayoristaTres, codigo_sku, stockmaximo, precio_activado, descripcion_2, pocentaje_ganacia) 
                        SELECT idarticulo, '$idsucursal', '$idusuario', '$totalcantidadpresentacion', 
                        stockminimo, precio_compra, '$precio_venta', precio_ventaNocturno, descuento_porcentaje, 
                        precio_descuento, precio_rango1, precio_rango2, precio_rango3, 
                        nombre_01, stock_unidad, '$precio_venta', 
                        nombre_02, stock_blister, precio_blister, 
                        nombre_03, stock_caja, precio_caja, 
                        nombre_04, stock_fardo, precio_fardo, 
                        nombre_05, stock_sacos, precio_sacos, 
                        nombre_06, stock_paquete, precio_paquete, 
                        nombre_07, stock_07, precio_07, 
                        nombre_08, stock_08, precio_08, 
                        nombre_09, stock_09, precio_09, 
                        nombre_10, stock_10, precio_10, 
                        nombre_11, stock_11, precio_11, 
                        nombre_12, stock_12, precio_12, 
                        nombre_13, stock_13, precio_13, 
                        nombre_14, stock_14, precio_14, 
                        nombre_15, stock_15, precio_15, 
                        nombre_16, stock_16, precio_16, 
                        nombre_17, stock_17, precio_17, 
                        nombre_18, stock_18, precio_18, 
                        nombre_19, stock_19, precio_19, 
                        nombre_20, stock_20, precio_20, 
                        '1', ganacia_articulo, 
                        tipo_ganacia, '$fechaHora', producto_consignacion, aplica_impuestos, precio_rango1_Dos,
                        precio_rango2_Dos, precio_rango3_Dos, precio_rango1_Mecanico, precio_rango2_MecanicoDos,
                        precio_rango3_MecanicoTres, precio_rango1_Distribuidor, precio_rango2_DistribuidorDos,
                        precio_rango3_DistribuidorTres, precio_rango1_Mayorista, precio_rango2_MayoristaDos,
                        precio_rango3_MayoristaTres, codigo_sku, stockmaximo, precio_activado, descripcion_2, pocentaje_ganacia
                        FROM articuloxsucursal WHERE idarticulo='$idarticulo' AND idsucursal='$idsucursalorigen'";
                    ejecutarConsulta($sqlArticuloStockEntrada);
                }

                $kardex_stock_final_destino = $stock_anterior_destino + $totalcantidadpresentacion;
                $sqlInsertKardexDestino = "INSERT INTO kardex_movimientos 
                (idarticulo, idsucursal, fecha_hora, concepto, num_documento, cantidad_existente, cantidad_modificacion, 
                tipo_modificacion, cantidad_final, precio, responsable)
                VALUES 
                ('$idarticulo', '$idsucursal', '$fechaHora', 'Ingreso por Edición de Traslado', '$idtraladosucursal', 
                '$stock_anterior_destino', '$totalcantidadpresentacion', 'Ingreso', '$kardex_stock_final_destino', 
                '$precio_compra', '$nombreUser')";
                ejecutarConsulta($sqlInsertKardexDestino);


                $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idingreso,
                                                                                idventa,idtraladosucursal,idtraladosucursal_entrada,
                                                                                iddevolucion,cantidad_compras,cantidad_ventas,cantidad_entrada,
                                                                                cantidad_devolucion,cantidad_salida,stock_inventario,fecha_horaCreacion,
                                                                                idarticulo,idusuario,idsucursal) 
                                                                        VALUES ('0','0','$idtraladosucursal',
                                                                                '0','0','0','0','0','0',
                                                                                '$totalcantidadpresentacion',
                                                                                '$stocksucursal_anterior',
                                                                                '$fechaHora',
                                                                                '$idarticulo',
                                                                                '$idusuario',
                                                                                '$idsucursalorigen')";
                ejecutarConsulta($sql_detalleoperaciones);
            }
        }

        $this->registrarAuditoria($idtraladosucursal, 'EDICIÓN', 'Se editó la salida (nuevas cantidades)', $idusuario, $datosArticulos);

        return $idtraladosucursal;
    }


    //Implementamos un método para anular la venta
    public function anular($idtraladosucursal)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');
        
        @session_start();
        $idusuario_auditoria = $_SESSION["idusuario"];
        $sqlUsuarioK = "SELECT nombre FROM usuario WHERE idusuario='" . $idusuario_auditoria . "'";
        $resUser = ejecutarConsultaSimpleFila($sqlUsuarioK);
        $nombreUser = $resUser ? $resUser["nombre"] : 'Sistema';

        $sql = "UPDATE traslado_sucursal SET estado='Anulado' WHERE idtraladosucursal='$idtraladosucursal'";
        ejecutarConsulta($sql);

        // Obtener el detalle para revertir el inventario
        $sqlDetalle = "SELECT idarticulo, totalcantidadpresentacion, idsucursalorigen, idsucursaldestino 
                       FROM detalle_traslado_sucursal 
                       WHERE idtraladosucursal='$idtraladosucursal'";
        $rspta = ejecutarConsulta($sqlDetalle);

        while ($reg = $rspta->fetch_object()) {
            $idarticulo = $reg->idarticulo;
            $totalcantidadpresentacion = $reg->totalcantidadpresentacion;
            $idsucursalorigen = $reg->idsucursalorigen;
            $idsucursaldestino = $reg->idsucursaldestino;

            $sqlArticuloOrigen = "SELECT precio_compra, stocksucursal FROM articuloxsucursal WHERE idarticulo='$idarticulo' AND idsucursal='$idsucursalorigen'";
            $ArticuloOrigen = ejecutarConsultaSimpleFila($sqlArticuloOrigen);
            $stock_origen = $ArticuloOrigen ? $ArticuloOrigen["stocksucursal"] : 0;
            $precio_compra = $ArticuloOrigen ? $ArticuloOrigen["precio_compra"] : 0;

            $sqlArticuloDestino = "SELECT stocksucursal FROM articuloxsucursal WHERE idarticulo='$idarticulo' AND idsucursal='$idsucursaldestino'";
            $ArticuloDestino = ejecutarConsultaSimpleFila($sqlArticuloDestino);
            $stock_destino = $ArticuloDestino ? $ArticuloDestino["stocksucursal"] : 0;

            // Revertir salida: sumar al origen
            $sqlArticuloStockSalida = "UPDATE articuloxsucursal SET stocksucursal = stocksucursal + " . $totalcantidadpresentacion . " 
                    WHERE idarticulo = $idarticulo AND idsucursal = '$idsucursalorigen'";
            ejecutarConsulta($sqlArticuloStockSalida);

            $kardex_stock_final_origen = $stock_origen + $totalcantidadpresentacion;
            $sqlInsertKardexOrigen = "INSERT INTO kardex_movimientos 
            (idarticulo, idsucursal, fecha_hora, concepto, num_documento, cantidad_existente, cantidad_modificacion, 
            tipo_modificacion, cantidad_final, precio, responsable)
            VALUES 
            ('$idarticulo', '$idsucursalorigen', '$fechaHora', 'Ingreso por Anulación de Traslado', '$idtraladosucursal', 
            '$stock_origen', '$totalcantidadpresentacion', 'Ingreso', '$kardex_stock_final_origen', 
            '$precio_compra', '$nombreUser')";
            ejecutarConsulta($sqlInsertKardexOrigen);

            // Revertir entrada: restar al destino
            $sqlArticuloStockEntrada = "UPDATE articuloxsucursal SET stocksucursal = stocksucursal - " . $totalcantidadpresentacion . " 
                    WHERE idarticulo = $idarticulo AND idsucursal = '$idsucursaldestino'";
            ejecutarConsulta($sqlArticuloStockEntrada);

            $kardex_stock_final_destino = $stock_destino - $totalcantidadpresentacion;
            $sqlInsertKardexDestino = "INSERT INTO kardex_movimientos 
            (idarticulo, idsucursal, fecha_hora, concepto, num_documento, cantidad_existente, cantidad_modificacion, 
            tipo_modificacion, cantidad_final, precio, responsable)
            VALUES 
            ('$idarticulo', '$idsucursaldestino', '$fechaHora', 'Salida por Anulación de Traslado', '$idtraladosucursal', 
            '$stock_destino', '$totalcantidadpresentacion', 'Salida', '$kardex_stock_final_destino', 
            '$precio_compra', '$nombreUser')";
            ejecutarConsulta($sqlInsertKardexDestino);
        }

        $sqlDetalleIngresoElimminar = "DELETE from operaciones_compras_ventas where idtraladosucursal=" . $idtraladosucursal . "";
        ejecutarConsulta($sqlDetalleIngresoElimminar);

        $idusuario_auditoria = $_SESSION["idusuario"];
        $this->registrarAuditoria($idtraladosucursal, 'ANULACIÓN', 'Se anuló la salida de producto', $idusuario_auditoria, null);

        return ($sql);
    }




    //Implementar un método para listar los registros
    public function listar($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT 
                ts.idtraladosucursal,
                ts.idsucursaldestino,
                (select s1.nombre from sucursal s1 where s1.idsucursal=ts.idsucursaldestino limit 0,1 ) as nombresucursaldestino,
                (select s1.direccion from sucursal s1 where s1.idsucursal=ts.idsucursaldestino limit 0,1 ) as direccionsucursadestino,
                ts.idusuario,
                u.nombre as usuario,
                ts.idsucursalorigen,
                (select s1.nombre from sucursal s1 where s1.idsucursal=ts.idsucursalorigen limit 0,1 ) as nombresucursalorigen,
                (select s1.direccion from sucursal s1 where s1.idsucursal=ts.idsucursalorigen limit 0,1 ) as direccionsucursalorigen,
                DATE(ts.fecha_hora) as fecha,
                ts.estado,
                ts.descripcion_salida_producto,
                ts.total_venta
                FROM traslado_sucursal ts 
                INNER JOIN usuario u on u.idusuario=ts.idusuario 
                where  ts.idsucursalorigen='" . $_SESSION["idsucursal"] . "' 
                and  u.idusuario='" . $_SESSION["idusuario"] . "'
                and  DATE(ts.fecha_hora)>='$fecha_inicio' AND DATE(ts.fecha_hora)<='$fecha_fin'
                ORDER by ts.idtraladosucursal desc ";
        return ejecutarConsulta($sql);
    }

    public function salidaprosucursalcabecera($idtraladosucursal)
    {
        $sql = "SELECT 
                ts.idtraladosucursal,
                ts.idsucursaldestino,
                (select s1.nombre from sucursal s1 where s1.idsucursal=ts.idsucursaldestino limit 0,1 ) as nombresucursaldestino,
                ts.idusuario,
                u.nombre as usuario,
                ts.idsucursalorigen,
                (select s1.nombre from sucursal s1 where s1.idsucursal=ts.idsucursalorigen limit 0,1 ) as nombresucursalorigen,
                DATE(ts.fecha_hora) as fecha,
                ts.estado,
                ts.total_venta,
                ts.descripcion_salida_producto,
                s.nombre as sucursal_nombre,
                s.imagen as sucursal_imagen,
                s.direccion as sucursal_direccion,
                s.telefono as sucursal_telefono,
                s.email as sucursal_email,
                s.nit as sucursal_nit,
                s.nombre_fel,
                c.empresadesarrollo
                FROM traslado_sucursal ts 
                INNER JOIN usuario u on u.idusuario=ts.idusuario 
                INNER JOIN sucursal s on s.idsucursal=ts.idsucursalorigen
                inner join certificador c on c.idsucursal=ts.idsucursalorigen
                 WHERE ts.idtraladosucursal='$idtraladosucursal' and c.condicion='1'";
        return ejecutarConsulta($sql);
    }

    public function salidaprosucursaltadetalle($idtraladosucursal)
    {
        $sql = "SELECT 
            dts.id_detalle_traslado_sucursal,
            dts.idtraladosucursal,
            dts.idarticulo,
            a.nombre as articulo,
            a.codigo,
            dts.cantidad,
            dts.descripcion_detalle,
            dts.idsucursalorigen,
            dts.idsucursaldestino,
            dts.precio_venta,
            dts.presentacion,
            dts.cantidadpresentacion,
            dts.totalcantidadpresentacion,
            ROUND((dts.cantidad*dts.precio_venta),2) AS subtotal
            FROM detalle_traslado_sucursal dts 
            INNER JOIN articulo a on a.idarticulo=dts.idarticulo
             WHERE dts.idtraladosucursal='$idtraladosucursal'";
        return ejecutarConsulta($sql);
    }

    public function selectSucursal()
    {
        $sql = "SELECT * FROM sucursal WHERE condicion='1' ";
        return ejecutarConsulta($sql);
    }



    public function listarxfechasucursal($fecha_inicio, $fecha_fin, $idsucursal)
    {
        $sql = "SELECT 
                ts.idtraladosucursal,
                ts.idsucursaldestino,
                (select s1.nombre from sucursal s1 where s1.idsucursal=ts.idsucursaldestino limit 0,1 ) as nombresucursaldestino,
                ts.idusuario,
                u.nombre as usuario,
                ts.idsucursalorigen,
                (select s1.nombre from sucursal s1 where s1.idsucursal=ts.idsucursalorigen limit 0,1 ) as nombresucursalorigen,
                DATE(ts.fecha_hora) as fecha,
                ts.estado,
                ts.descripcion_salida_producto,
                ts.total_venta
                FROM traslado_sucursal ts 
                INNER JOIN usuario u on u.idusuario=ts.idusuario 
                where  DATE(ts.fecha_hora)>='$fecha_inicio' AND DATE(ts.fecha_hora)<='$fecha_fin' and ts.idsucursalorigen='$idsucursal'
                ORDER by ts.idtraladosucursal desc ";
        return ejecutarConsulta($sql);
    }

    public function listarxfechasucursalDetalle($fecha_inicio, $fecha_fin, $idsucursal)
    {
        $sql = "SELECT 
                d.id_detalle_traslado_sucursal,
                d.idtraladosucursal,
                d.idarticulo,
                d.cantidad,
                d.descripcion_detalle,
                d.idsucursalorigen,
                d.idsucursaldestino,
                d.precio_venta,
                a.codigo,
                d.totalcantidadpresentacion,
                a.nombre AS articulo,
                ts.estado,
                date(ts.fecha_hora) AS fecha
                FROM traslado_sucursal ts 
                INNER JOIN detalle_traslado_sucursal d ON d.idtraladosucursal=ts.idtraladosucursal
                INNER JOIN articulo a ON a.idarticulo=d.idarticulo
                where  DATE(ts.fecha_hora)>='$fecha_inicio' 
                     AND DATE(ts.fecha_hora)<='$fecha_fin' 
                     and ts.idsucursalorigen='$idsucursal'
                ORDER by ts.idtraladosucursal desc ";
        return ejecutarConsulta($sql);
    }

    public function mostrar($idtraladosucursal)
    {
        $sql = "SELECT 
            t.idtraladosucursal,
            t.idsucursaldestino,
            t.idusuario,
            t.idsucursalorigen,
            DATE(t.fecha_hora) AS fecha,
            t.estado,
            t.descripcion_salida_producto,
            t.total_venta
        FROM traslado_sucursal t 	
        WHERE t.idtraladosucursal = '$idtraladosucursal'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function detallecotizacionparaventa($idtraladosucursal)
    {

        $sqldetalle = "SELECT 
            d.*,
            CONCAT(a.nombre, ' ', aa.descripcion_2) AS nombre,
            aa.precio_venta AS pv,
            aa.nombre_01,
            aa.stock_unidad,
            aa.precio_unidad,
            aa.nombre_02,
            aa.stock_blister,
            aa.precio_blister,
            aa.nombre_03,
            aa.stock_caja,
            aa.precio_caja,
            aa.nombre_04,
            aa.stock_fardo,
            aa.precio_fardo,
            aa.nombre_05,
            aa.stock_sacos,
            aa.precio_sacos,
            aa.nombre_06,
            aa.stock_paquete,
            aa.precio_paquete,
            aa.nombre_07,
            aa.stock_07,
            aa.precio_07,
            aa.nombre_08,
            aa.stock_08,
            aa.precio_08,
            aa.nombre_09,
            aa.stock_09,
            aa.precio_09,
            aa.nombre_10,
            aa.stock_10,
            aa.precio_10,
            aa.nombre_11,
            aa.stock_11,
            aa.precio_11,
            aa.nombre_12,
            aa.stock_12,
            aa.precio_12,
            aa.nombre_13,
            aa.stock_13,
            aa.precio_13,
            aa.nombre_14,
            aa.stock_14,
            aa.precio_14,
            aa.nombre_15,
            aa.stock_15,
            aa.precio_15,
            aa.nombre_16,
            aa.stock_16,
            aa.precio_16,
            aa.nombre_17,
            aa.stock_17,
            aa.precio_17,
            aa.nombre_18,
            aa.stock_18,
            aa.precio_18,
            aa.nombre_19,
            aa.stock_19,
            aa.precio_19,
            aa.nombre_20,
            aa.stock_20,
            aa.precio_20,
            a.facturar_cero,
            aa.stocksucursal
        FROM detalle_traslado_sucursal d 
        INNER JOIN articulo a ON d.idarticulo = a.idarticulo
        inner JOIN articuloxsucursal aa ON d.idarticulo = aa.idarticulo
        WHERE d.idtraladosucursal = '$idtraladosucursal' 
        AND aa.idsucursal = '" . $_SESSION["idsucursal"] . "' ";

        $rspta = ejecutarConsulta($sqldetalle);
        $rows = array();
        while ($reg = $rspta->fetch_object()) {
            $rows[] = $reg;
        }
        return $rows;
    }

    // Método para registrar auditoría
    public function registrarAuditoria($idtraladosucursal, $accion, $descripcion, $idusuario, $datosArticulos = null)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        // Convertimos el arreglo de artículos a formato de texto JSON para guardarlo
        $detalle_json = $datosArticulos ? json_encode($datosArticulos) : '';

        $sql = "INSERT INTO auditoria_traslado_sucursal (idtraladosucursal, accion, descripcion, detalle_articulos, idusuario, fecha_hora) 
                VALUES ('$idtraladosucursal', '$accion', '$descripcion', '$detalle_json', '$idusuario', '$fechaHora')";

        return ejecutarConsulta($sql);
    }
}
