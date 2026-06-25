<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";



/*

Password: sUSwj20xHwlSTDF@
*/


/*
Datos de acceso a portal de guatefacturas produccion
https://dte.guatefacturas.com/app/gf/f?p=FEL

Usuario: AD_18111866
Clave: AD_18111866
Clave nueva 01: SOL_18111866
*/

// Datos de sucursal
$sqlEstablecimientoNum = "SELECT 
    s.num_establecimiento,
    s.nombre_fel,
    s.nombre_comercial,
    s.direccion_fiscal,
    c._CLIENTE_,
    c._USUARIO_,
    c._PASS_,
    c._NIT_,
    s.certificador,
    c.prueba_produccion
 FROM sucursal s
INNER JOIN certificador c ON c.idsucursal=s.idsucursal
WHERE c.condicion=1 AND s.idsucursal='" . $_SESSION["idsucursal"] . "'";
$numestable = ejecutarConsultaSimpleFila($sqlEstablecimientoNum);

// Define constantes
define('NUM_ESTABLECIMIENTO', $numestable["num_establecimiento"]);
define('NOMBRE_FEL', $numestable["nombre_fel"]);
define('NOMBRE_COMERCIAL', $numestable["nombre_comercial"]);
define('DIRECCION_FISCAL', $numestable["direccion_fiscal"]);
define('_CLIENTE_', $numestable["_CLIENTE_"]);
define('_USUARIO_', $numestable["_USUARIO_"]);
define('_PASS_', $numestable["_PASS_"]);
define('_NIT_', $numestable["_NIT_"]);
define('CERTIFICADOR', $numestable["certificador"]);
define('PRUEBA_PRODUCCION', $numestable["prueba_produccion"]);

/*
$sqlTipoDoc = "SELECT 
                t.idtipo_documentos,
                t.idsucursal,
                t.factura,
                t.tipoDocumentoIntFactura,
                t.factura_cambiaria,
                t.tipoDocumentoIntFacturaCambiaria,
                t.nota_credito,
                t.tipoDocumentoIntNotaCredito,
                t.resvalidarimpuesto,
                t.condicion
                FROM tipo_documentos t
                WHERE t.idsucursal='" . $_SESSION["idsucursal"] . "'";
$numestable = ejecutarConsultaSimpleFila($sqlTipoDoc);

define('RES_FACTURA', $numestable["factura"]);
define('RES_TIPODOCUMENTOINTFACTURA', $numestable["tipoDocumentoIntFactura"]);
define('RES_FACTURACAMBIARIA', $numestable["factura_cambiaria"]);
define('RES_TIPODOCUMENTOINTFACTURACAMBIARIA', $numestable["tipoDocumentoIntFacturaCambiaria"]);
define('RES_NOTACREDITO', $numestable["nota_credito"]);
define('RES_TIPODOCUMENTOINTNOTACREDITO', $numestable["tipoDocumentoIntNotaCredito"]);
define('RES_VALIDARIMPUESTO', $numestable["resvalidarimpuesto"]);
*/

class Venta_rapida
{
    //Implementamos nuestro constructor 
    public function __construct() {}

    //Implementamos un método para insertar registros
    public function insertar($idcliente,$codigo_cliente,$nit,$nombre_cliente,$telefono_cliente,
    $direccion_cliente,$correo_cliente,$tipo_documento_cliente,$idusuario,$idcotizacion,$fecha_hora,$forma_pago,
        $tipo_comprobante,$total_venta,$total_ventades,$cefectivo,$ccredito,$ctarjeta,$ctransferencia,
        $rescambio,$valor_tarjeta,$tipo_pagoBacVisaNet,$opcionesAdicionales,$observacion_credito,
        $datosArticulos,$total_venta_r,$total_ventades_r,$tipo_entrega,
        $numero_pagos,$fecha_hora_pago,$fecha_hora_vencimiento_factura,$monto_abono,
        $idtransporte,$idmensajero,$idvendedor,$descuento_general,$valor_descuentoGeneral,$tipo_cliente,$numero_deposito_transferencia)
    { 

        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');
        /////CAPTURA DE CLIENTE NUEVO Y UPDATE
        if ($idcliente == '0') {
            $sqlcorrelativo = "UPDATE add_correlativo SET codigo_cliente=codigo_cliente+1 
            WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
            ejecutarConsulta($sqlcorrelativo);

            $sqlCorre = "SELECT * FROM add_correlativo WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
            $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
            $corre = $correlativo["codigo_cliente"];
            $codigo_cliente = 'COD' . $corre;

            $sqlcliente = "INSERT INTO persona (tipo_persona,nombre,tipo_documento,num_documento,direccion,
                                                telefono,email,tipo_cliente,codigo_cliente,fechaCreacion)
                                VALUES ('Cliente','$nombre_cliente','$tipo_documento_cliente','$nit',
                                '$direccion_cliente','$telefono_cliente','$correo_cliente','$tipo_cliente','$codigo_cliente','$fechaHora')";
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

                $sqlcorrelativo = "UPDATE add_correlativo SET codigo_cliente=codigo_cliente+1 
                WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
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
            nombre='$nombre_cliente',
            tipo_cliente='$tipo_cliente'
            WHERE idpersona='$idcliente'";

            ejecutarConsulta($sqlcorrelativo);
            $residcliente = $idcliente;
        }
        ///////   


        ////datos establecimiento y persona




        $sqlPersona = "SELECT * FROM persona WHERE idpersona='$residcliente'";
        $Persona = ejecutarConsultaSimpleFila($sqlPersona);
        #echo json_encode($Persona);
        $nit = "CF";
        $tipoidentificador = "1";
        if ($Persona["num_documento"] == "C/F") {
        } else {
            if ($Persona["tipo_documento"] == "NIT") {
                $flagNit = str_replace("-", "", $Persona["num_documento"]);
                if (strlen($flagNit) <= 15) {
                    $nit = $Persona["num_documento"];
                    $tipoidentificador = "1";
                } else {
                    $nit = "CF";
                    $tipoidentificador = "1";
                }
            } elseif ($Persona["tipo_documento"] == "DPI") {
                $nit = $Persona["num_documento"];
                $tipoidentificador = "2";
            } elseif ($Persona["tipo_documento"] == "PASAPORTE") {
                $nit = $Persona["num_documento"];
                $tipoidentificador = "3";
            }
        }

        ////fin datos establecimiento y persona        


        if ($tipo_comprobante == "Envio") {

            $sqlcorrelativo = "UPDATE add_correlativo SET num_envio=num_envio+1 WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
            ejecutarConsulta($sqlcorrelativo);

            $sqlCorre="SELECT * FROM add_correlativo WHERE idsucursal='".$_SESSION["idsucursal"]."'";
            $correlativo= ejecutarConsultaSimpleFila($sqlCorre);
            $corre=$correlativo["num_envio"];        
     
            $sql="INSERT INTO venta (idcliente,idusuario,idsucursal,tipo_comprobante,num_comprobante,fecha_hora,total_venta,estado,cefectivo,rescambio,forma_pago,total_ventades,
            tipo_pagoBacVisaNet,opcionesAdicionales,valor_tarjeta,ccredito,observacion_credito,ctarjeta,ctransferencia,fecha_creacion,tipo_entrega,idvendedor,descuento_general,valor_descuentoGeneral,numero_deposito_transferencia)
            VALUES ('$residcliente','$idusuario','".$_SESSION["idsucursal"]."','$tipo_comprobante','$corre','$fecha_hora','$total_venta_r','Aceptado','$cefectivo','$rescambio','$forma_pago','$total_ventades_r',
            '$tipo_pagoBacVisaNet','$opcionesAdicionales','$valor_tarjeta','$ccredito','$observacion_credito','$ctarjeta','$ctransferencia','$fechaHora','$tipo_entrega','$idvendedor','$descuento_general','$valor_descuentoGeneral','$numero_deposito_transferencia')";   
            $idventanew=ejecutarConsulta_retornarID($sql); 

            if ($tipo_entrega == "Tienda") {
                $sqlCredito = "UPDATE venta SET estado_venta='COMPLETO' WHERE idventa='$idventanew' ";
                ejecutarConsulta($sqlCredito);
            } else if ($tipo_entrega == "Transporte") {
                $sqlCredito = "UPDATE venta SET idtransporte='$idtransporte',estado_venta='ENPROCESO' WHERE idventa='$idventanew' ";
                ejecutarConsulta($sqlCredito);
            } else if ($tipo_entrega == "Mensajero") {
                $sqlCredito = "UPDATE venta SET idmensajero='$idmensajero',estado_venta='ENPROCESO' WHERE idventa='$idventanew' ";
                ejecutarConsulta($sqlCredito);
            }

            if ($forma_pago == "Credito") {
                $sqlCredito = "UPDATE venta SET saldo_venta='$ccredito' WHERE idventa='$idventanew' ";
                ejecutarConsulta($sqlCredito);
            }

            if ($idcotizacion == "") {
                $residcotizacion = 0;
            } else {
                $residcotizacion = $idcotizacion;
                $sqlcorrelativo = "UPDATE cotizacion SET idventa='$idventanew', cobradosino='SI' WHERE idcotizacion ='$idcotizacion'";
                ejecutarConsulta($sqlcorrelativo);
            }


            $num_elementos = 0;
            $sw = true;



            if ($idventanew) {
                $articulos = $datosArticulos['articulos'];
                $numArticulos = count($articulos['idarticulo']);
                for ($i = 0; $i < $numArticulos; $i++) {
                    $idarticulo = $articulos['idarticulo'][$i];
                    $precio_compra = $articulos['precio_compra'][$i];
                    $stockinven = $articulos['stockinven'][$i];
                    $cantidadpresentacion = $articulos['cantidadpresentacion'][$i];
                    $cantidad = $articulos['cantidad'][$i];
                    $totalcantidadpresentacion = $articulos['totalcantidadpresentacion'][$i];
                    $presentacion = $articulos['presentacion'][$i];
                    $presen = $articulos['presen'][$i];
                    $precio_ventaSistema = $articulos['precio_ventaSistema'][$i];
                    $precio_ventaSistema2 = $articulos['precio_ventaSistema2'][$i];
                    $q_ref = $articulos['q_ref'][$i];
                    $precio_venta = $articulos['precio_venta'][$i];
                    $precio_recargoPV = $articulos['precio_recargoPV'][$i];
                    $precio_recargoQRef = $articulos['precio_recargoQRef'][$i];
                    $descuento_permitido = $articulos['descuento_permitido'][$i];
                    $descuento_porcentaje = $articulos['descuento_porcentaje'][$i];
                    $descripcion_detalle = $articulos['descripcion_detalle'][$i];
                    $subtotal1 = $articulos['subtotal1'][$i];
                    $subtotaldes1 = $articulos['subtotaldes1'][$i];

                    //STOCK ANTES
                    $sqlStockAntes = "SELECT stocksucursal FROM articuloxsucursal WHERE idarticulo = '$idarticulo' AND idsucursal = '" . $_SESSION["idsucursal"] . "'";
                    $stockData = ejecutarConsultaSimpleFila($sqlStockAntes);
                    $stock_anterior = $stockData ? $stockData['stocksucursal'] : 0;

                    $stocksucursal_nuevo = $stock_anterior - $totalcantidadpresentacion;

                    //REGISTRO DE OPERACIONES
                        $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(
                            idingreso, idventa, idtraladosucursal, idtraladosucursal_entrada, iddevolucion, idnota_debito, 
                            cantidad_compras, cantidad_ventas, cantidad_entrada, cantidad_devolucion, cantidad_salida, 
                            stock_anterior, stock_nuevo, cantidad, fecha_horaCreacion, tipo_operacion, 
                            idarticulo, idusuario, idsucursal
                        )
                        VALUES (
                            '0', '$idventanew', '0', '0', '0', '0',
                            '0', '$totalcantidadpresentacion', '0', '0', '0',
                            '$stock_anterior', '$stocksucursal_nuevo', '$totalcantidadpresentacion', NOW(), 'Resta',
                            '$idarticulo', '" . $_SESSION["idusuario"] . "', '" . $_SESSION["idsucursal"] . "'
                        )";
                        ejecutarConsulta($sql_detalleoperaciones);
                    //REGISTRO DE OPERACIONES

                    $sqlPCcompra = "SELECT 
                                    asu.precio_compra as pc_compra, 
                                    asu.stocksucursal,
                                    a.tipo_producto
                                FROM articuloxsucursal asu
                                inner join articulo a on a.idarticulo=asu.idarticulo
                                WHERE asu.idarticulo='$idarticulo'  and asu.idsucursal='" . $_SESSION["idsucursal"] . "' ";
                    $respc = ejecutarConsultaSimpleFila($sqlPCcompra);
                    $pc_compra = $respc["pc_compra"];
                    $tipoproducto = $respc["tipo_producto"];


                    $sql_detalle = "INSERT INTO detalle_venta(idventa,idarticulo,cantidad,precio_venta,descuento,stockinven,subtotaldes1,
                    precio_ventaSistema,precio_ventaSistema2,subtotal1,cantidadpresentacion,totalcantidadpresentacion,presen,precio_recargo,q_ref,precio_recargoPV,precio_recargoQRef,precio_compra) 
                    VALUES ('$idventanew','$idarticulo','$cantidad','$precio_venta','$descuento_porcentaje','$stockinven','$subtotaldes1','$precio_ventaSistema','$precio_ventaSistema2',
                    '$subtotal1','$cantidadpresentacion',
                    '$totalcantidadpresentacion','$presen','0','$q_ref','$precio_recargoPV',
                    '$precio_recargoQRef','$pc_compra')";
                    ejecutarConsulta($sql_detalle) or $sw = false;

                    //DESCONTAR PARA EL COMBO
                    $sqlArticusqlArticuloStockComboloStock = "UPDATE articuloxsucursal SET stocksucursal = stocksucursal - " . $totalcantidadpresentacion . " WHERE idarticulo =$idarticulo  
                            and idsucursal='" . $_SESSION["idsucursal"] . "' ";
                    ejecutarConsulta($sqlArticusqlArticuloStockComboloStock);

                    if ($tipoproducto == "Productos") {
                        # code...
                        $sqlArticuloStock = "UPDATE articuloxsucursal SET stocksucursal = stocksucursal - " . $totalcantidadpresentacion . " WHERE idarticulo =$idarticulo  
                            and idsucursal='" . $_SESSION["idsucursal"] . "' ";
                        ejecutarConsulta($sqlArticuloStock);
                    } else {
                        # code...

                    }


                    $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idingreso,idventa,idtraladosucursal,idtraladosucursal_entrada,iddevolucion,cantidad_compras,cantidad_ventas,
                    cantidad_entrada,cantidad_devolucion,cantidad_salida,stock_inventario,fecha_horaCreacion,idarticulo,idusuario,
                    idsucursal) 
                    VALUES ('0',
                    '$idventanew','0','0','0','0','$totalcantidadpresentacion','0','0','0','$stockinven','$fechaHora',
                    '$idarticulo','$idusuario',
                    '" . $_SESSION["idsucursal"] . "')";
                    //ejecutarConsulta($sql_detalleoperaciones);

                    //VALIDAR SI ES TOPPING O EXTRA
                        $extras = $articulos['idarticuloExtra_extras'];
                        $tipos = $articulos['tipo_item_extras'];
                        $cantidades = $articulos['cantidad_extra'];
                        $productos = $articulos['idproducto_extra'];
                        $check = $articulos['check_extras'];

                        for ($j = 0; $j < count($extras); $j++) {
                            $tipo = $tipos[$j];
                            $idextra = $extras[$j];
                            $cantidad_extra = $cantidades[$j];
                            $idproducto = $productos[$j];

                            // Calcular el total a descontar (cantidad_extra * totalcantidadpresentacion)
                            $total_a_descontar = $cantidad_extra * $totalcantidadpresentacion;

                            // Actualizar el stock en la tabla articuloxsucursal, descontando la cantidad correspondiente
                            $sqlArticuloStock = "UPDATE articuloxsucursal 
                                                SET stocksucursal = stocksucursal - $total_a_descontar 
                                                WHERE idarticulo = $idextra 
                                                AND idsucursal = '" . $_SESSION["idsucursal"] . "'";
                            // Ejecutar la consulta
                            ejecutarConsulta($sqlArticuloStock) or $sw = false;

                            if ($tipo === 'Topping') {
                                // Insertar en detalle_venta (como topping)
                                $sql_topping = "INSERT INTO detalle_venta(
                                    idventa, idarticulo, cantidad, precio_venta, descuento, stockinven, subtotaldes1,
                                    precio_ventaSistema, precio_ventaSistema2, subtotal1, cantidadpresentacion,
                                    totalcantidadpresentacion, presen, precio_recargo, q_ref, precio_recargoPV, 
                                    precio_recargoQRef, precio_compra
                                ) VALUES (
                                    '$idventanew','$idextra','$total_a_descontar','0','0','0','0',
                                    '0','0','0','$total_a_descontar','$total_a_descontar','0','0','0','0','0','0'
                                )";
                                ejecutarConsulta($sql_topping) or $sw = false;
                            } elseif ($tipo === 'Extra') {
                                // Obtener precio_venta desde articuloxsucursal
                                $sqlPrecio = "SELECT precio_venta 
                                            FROM articuloxsucursal 
                                            WHERE idarticulo = '$idextra' 
                                            AND idsucursal = '" . $_SESSION["idsucursal"] . "' 
                                            LIMIT 1";
                                
                                $resPrecio = ejecutarConsultaSimpleFila($sqlPrecio);
                                $precio_venta_extra = isset($resPrecio["precio_venta"]) ? $resPrecio["precio_venta"] : 0;

                                // Insertar en detalle_ventaExtra
                                $sql_extra = "INSERT INTO detalle_ventaExtra(
                                    idventa, idarticulo, idproducto, cantidad, precio_venta
                                ) VALUES (
                                    '$idventanew', '$idextra', '$idproducto', '$total_a_descontar', '$precio_venta_extra'
                                )";
                                
                                ejecutarConsulta($sql_extra) or $sw = false;
                            }
                            ////////materia prima
                                        $sqlVerificacionExistencia = "SELECT 
                                        p.idproducto,
                                        dp.cantidad as cantmateriaprima,
                                        dp.idarticulo as idarticulo_costo
                                        FROM produccion p 
                                        INNER JOIN detalle_produccion dp ON p.idproduccion=dp.idproduccion
                                        WHERE p.idproducto='$idarticulo' and dp.tipo_item='Producto'";
                                    $EXIS = ejecutarConsulta($sqlVerificacionExistencia);

                                    $numexis = 0;

                                    while ($reeeq = $EXIS->fetch_object()) {
                                        $Tcan = $cantidad * $reeeq->cantmateriaprima;

                                        $updateArticuloDetalle = "UPDATE articuloxsucursal SET stocksucursal=stocksucursal-$Tcan
                                                                WHERE idarticulo='" . $reeeq->idarticulo_costo . "'   and idsucursal='" . $_SESSION["idsucursal"] . "' ";
                                        ejecutarConsulta($updateArticuloDetalle);

                                        $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idingreso,idventa,idtraladosucursal,idtraladosucursal_entrada,iddevolucion,cantidad_compras,cantidad_ventas,cantidad_entrada,cantidad_devolucion,cantidad_salida,stock_inventario,fecha_horaCreacion,idarticulo,idusuario,idsucursal) 
                                                                VALUES ('0','$idventanew','0','0','0','0','$Tcan','0','0','0','$stockinven','$fechaHora','$idarticulo','$idusuario','" . $_SESSION["idsucursal"] . "')";
                                        ejecutarConsulta($sql_detalleoperaciones);

                                        $numexis++;
                                    }
                                    if ($numexis == 0) {
                                    }
                                //////fin materia prima                            

                        }
                    //
                }
            }
        } else {
            if (CERTIFICADOR == "GUATEFACTURAS") 
            {

                $sqlcorrelativo = "UPDATE add_correlativo SET num_factura=num_factura+1 WHERE idsucursal='" . $_SESSION["idsucursal"] . "'";
                ejecutarConsulta($sqlcorrelativo);

                            $sqlCorre="SELECT * FROM add_correlativo WHERE idsucursal='".$_SESSION["idsucursal"]."'";
                            $correlativo= ejecutarConsultaSimpleFila($sqlCorre);
                            $corre=$correlativo["num_factura"];        

                        ////GUARDA VENTA
                            $sql="INSERT INTO venta (idcliente,idusuario,idsucursal,tipo_comprobante,num_comprobante,fecha_hora,total_venta,estado,cefectivo,rescambio,forma_pago,total_ventades,
                            tipo_pagoBacVisaNet,opcionesAdicionales,valor_tarjeta,ccredito,observacion_credito,ctarjeta,ctransferencia,fecha_creacion,tipo_entrega,numero_pagos,fecha_hora_pago,fecha_hora_vencimiento_factura,monto_abono,idvendedor,descuento_general,valor_descuentoGeneral,numero_deposito_transferencia)
                            VALUES ('$residcliente','$idusuario','".$_SESSION["idsucursal"]."','$tipo_comprobante','$corre','$fecha_hora','$total_venta_r','Aceptado','$cefectivo','$rescambio','$forma_pago','$total_ventades_r',
                            '$tipo_pagoBacVisaNet','$opcionesAdicionales','$valor_tarjeta','$ccredito','$observacion_credito','$ctarjeta','$ctransferencia','$fechaHora','$tipo_entrega','$numero_pagos','$fecha_hora_pago','$fecha_hora_vencimiento_factura','$monto_abono','$idvendedor','$descuento_general','$valor_descuentoGeneral','$numero_deposito_transferencia')";        
                            $idventanew=ejecutarConsulta_retornarID($sql); 
                        ////FIN GUARDA VENTA

                        ////TIPO ENTREGA
                            if($tipo_entrega == "Tienda"){
                                $sqlCredito="UPDATE venta SET estado_venta='COMPLETO' WHERE idventa='$idventanew' ";
                                ejecutarConsulta($sqlCredito);
                            }else if($tipo_entrega == "Transporte"){
                                $sqlCredito="UPDATE venta SET idtransporte='$idtransporte',estado_venta='ENPROCESO' WHERE idventa='$idventanew' ";
                                ejecutarConsulta($sqlCredito);
                            }else if($tipo_entrega == "Mensajero"){
                                $sqlCredito="UPDATE venta SET idmensajero='$idmensajero',estado_venta='ENPROCESO' WHERE idventa='$idventanew' ";
                                ejecutarConsulta($sqlCredito);
                            }

                if ($forma_pago == "Credito") {
                    $sqlCredito = "UPDATE venta SET saldo_venta='$ccredito' WHERE idventa='$idventanew' ";
                    ejecutarConsulta($sqlCredito);
                }

                if ($idcotizacion == "") {
                    $residcotizacion = 0;
                } else {
                    $residcotizacion = $idcotizacion;
                    $sqlcorrelativo = "UPDATE cotizacion SET idventa='$idventanew', cobradosino='SI' WHERE idcotizacion ='$idcotizacion'";
                    ejecutarConsulta($sqlcorrelativo);
                }

                $nombreCliente = $Persona["nombre"]; // Suponiendo que este es tu nombre

                //iniciamos la factura
                date_default_timezone_set("America/Guatemala");
                $nombreCliente = $Persona["nombre"]; // Suponiendo que este es tu nombre

                if ($tipo_comprobante == "Factura") {
                    # code...
                    $tipoDocumento = RES_FACTURA;
                    $tipoDocumentoInt=RES_TIPODOCUMENTOINTFACTURA;
                } elseif ($tipo_comprobante == "Cambiaria") {
                    # code...
                    $tipoDocumento = RES_FACTURACAMBIARIA;
                    $tipoDocumentoInt=RES_TIPODOCUMENTOINTFACTURACAMBIARIA;
                }
                // Escapar las comillas dobles
                $nombreClienteEscapado = str_replace('"', '\"', $nombreCliente);

                $JsonIntegracionEcoFactura = '{
                                "usuario":"' . _CLIENTE_ . '",
                                "clave":"' . _PASS_ . '",   
                                "nit":"' . _NIT_ . '",                                      
                                "tipoDocumento": "' . $tipoDocumento . '",
                                "establecimiento": "' . NUM_ESTABLECIMIENTO . '",
                                "tipoDocumentoInt": "' . $tipoDocumentoInt . '",
                                "maquina": "1",
                                "tipoVenta": "B",
                                "bruto": "' . $total_venta . '",
                                "descuento": "' . $total_ventades . '",
                                "exento":"0",
                                "otros":"0",
                                "neto":"0",
                                "isr":"0",
                                "total":"' . $total_venta . '",                   
                                "numeroTransaccion": "' . $idventanew . '",
                                "fechaTransaccion": "' . date("d/m/Y", strtotime($fecha_hora)) . '",
                                "tipoMoneda":"1",
                                "nitCliente": "' . $nit . '",
                                "codigoCliente": "' . $Persona["idpersona"] . '",
                                "nombreCliente": "' . $Persona["nombre"] . '",
                                "direccionCliente": "' . $Persona["direccion"] . '",
                                "correoCliente": "' . $Persona["email"] . '",
                                "NumeroAbonoFCAM":"'.$numero_pagos.'",
                                "FechaVencimientoFCAM": "' . date("Ymd", strtotime($fecha_hora_vencimiento_factura)) . '",         
                                "MontoAbonosFCAM":  "'.$monto_abono.'",
                                "detallesDocumento":[{DetalleFactura}]
                    }';


                $num_elementos = 0;
                $sw = true;
                $JsonDetalleFacturaIntegracion = "";


                if ($idventanew) {
                    $articulos = $datosArticulos['articulos'];
                    $numArticulos = count($articulos['idarticulo']);
                    for ($i = 0; $i < $numArticulos; $i++) {
                        $idarticulo = $articulos['idarticulo'][$i];
                        $precio_compra = $articulos['precio_compra'][$i];
                        $stockinven = $articulos['stockinven'][$i];
                        $cantidadpresentacion = $articulos['cantidadpresentacion'][$i];
                        $cantidad = $articulos['cantidad'][$i];
                        $totalcantidadpresentacion = $articulos['totalcantidadpresentacion'][$i];
                        $presentacion = $articulos['presentacion'][$i];
                        $presen = $articulos['presen'][$i];
                        $precio_ventaSistema = $articulos['precio_ventaSistema'][$i];
                        $precio_ventaSistema2 = $articulos['precio_ventaSistema2'][$i];
                        $q_ref = $articulos['q_ref'][$i];
                        $precio_venta = $articulos['precio_venta'][$i];
                        $precio_recargoPV = $articulos['precio_recargoPV'][$i];
                        $precio_recargoQRef = $articulos['precio_recargoQRef'][$i];
                        $descuento_permitido = $articulos['descuento_permitido'][$i];
                        $descuento_porcentaje = $articulos['descuento_porcentaje'][$i];
                        $descripcion_detalle = $articulos['descripcion_detalle'][$i];
                        $subtotal1 = $articulos['subtotal1'][$i];
                        $subtotaldes1 = $articulos['subtotaldes1'][$i];

                        //STOCK ANTES
                        $sqlStockAntes = "SELECT stocksucursal FROM articuloxsucursal WHERE idarticulo = '$idarticulo' AND idsucursal = '" . $_SESSION["idsucursal"] . "'";
                        $stockData = ejecutarConsultaSimpleFila($sqlStockAntes);
                        $stock_anterior = $stockData ? $stockData['stocksucursal'] : 0;

                        $stocksucursal_nuevo = $stock_anterior - $totalcantidadpresentacion;

                        //REGISTRO DE OPERACIONES
                            $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(
                                idingreso, idventa, idtraladosucursal, idtraladosucursal_entrada, iddevolucion, idnota_debito, 
                                cantidad_compras, cantidad_ventas, cantidad_entrada, cantidad_devolucion, cantidad_salida, 
                                stock_anterior, stock_nuevo, cantidad, fecha_horaCreacion, tipo_operacion, 
                                idarticulo, idusuario, idsucursal
                            )
                            VALUES (
                                '0', '$idventanew', '0', '0', '0', '0',
                                '0', '$totalcantidadpresentacion', '0', '0', '0',
                                '$stock_anterior', '$stocksucursal_nuevo', '$totalcantidadpresentacion', NOW(), 'Resta',
                                '$idarticulo', '" . $_SESSION["idusuario"] . "', '" . $_SESSION["idsucursal"] . "'
                            )";
                            ejecutarConsulta($sql_detalleoperaciones);
                        //REGISTRO DE OPERACIONES

                        $sqlPCcompra = "SELECT 
                                            asu.precio_compra as pc_compra, 
                                            asu.stocksucursal,
                                            a.tipo_producto
                                        FROM articuloxsucursal asu
                                        inner join articulo a on a.idarticulo=asu.idarticulo
                                        WHERE asu.idarticulo='$idarticulo'  and asu.idsucursal='" . $_SESSION["idsucursal"] . "' ";
                        $respc = ejecutarConsultaSimpleFila($sqlPCcompra);
                        $pc_compra = $respc["pc_compra"];
                        $tipoproducto = $respc["tipo_producto"];


                        $sql_detalle = "INSERT INTO detalle_venta(idventa,idarticulo,cantidad,precio_venta,descuento,stockinven,subtotaldes1,
                            precio_ventaSistema,precio_ventaSistema2,subtotal1,cantidadpresentacion,totalcantidadpresentacion,presen,precio_recargo,q_ref,precio_recargoPV,precio_recargoQRef,precio_compra) 
                            VALUES ('$idventanew','$idarticulo','$cantidad','$precio_venta','$descuento_porcentaje','$stockinven','$subtotaldes1','$precio_ventaSistema','$precio_ventaSistema2',
                            '$subtotal1','$cantidadpresentacion',
                            '$totalcantidadpresentacion','$presen','0','$q_ref','$precio_recargoPV',
                            '$precio_recargoQRef','$pc_compra')";
                        ejecutarConsulta($sql_detalle) or $sw = false;

                        //DESCONTAR PARA EL COMBO
                        $sqlArticusqlArticuloStockComboloStock = "UPDATE articuloxsucursal SET stocksucursal = stocksucursal - " . $totalcantidadpresentacion . " WHERE idarticulo =$idarticulo  
                                and idsucursal='" . $_SESSION["idsucursal"] . "' ";
                        ejecutarConsulta($sqlArticusqlArticuloStockComboloStock);

                        if ($tipoproducto == "Productos") {
                            # code...
                            $sqlArticuloStock = "UPDATE articuloxsucursal SET stocksucursal = stocksucursal - " . $totalcantidadpresentacion . " WHERE idarticulo =$idarticulo  
                                    and idsucursal='" . $_SESSION["idsucursal"] . "' ";
                            ejecutarConsulta($sqlArticuloStock);
                        } else {
                            # code...

                        }


                        $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idingreso,idventa,idtraladosucursal,idtraladosucursal_entrada,iddevolucion,cantidad_compras,cantidad_ventas,
                            cantidad_entrada,cantidad_devolucion,cantidad_salida,stock_inventario,fecha_horaCreacion,idarticulo,idusuario,
                            idsucursal) 
                            VALUES ('0',
                            '$idventanew','0','0','0','0','$totalcantidadpresentacion','0','0','0','$stockinven','$fechaHora',
                            '$idarticulo','$idusuario',
                            '" . $_SESSION["idsucursal"] . "')";
                        //ejecutarConsulta($sql_detalleoperaciones);

                        $sqlArticulo = "SELECT * FROM articulo WHERE idarticulo='$idarticulo'";
                        $Articulo = ejecutarConsultaSimpleFila($sqlArticulo);

                        ///detalle factura
                        if ($i == 0) {
                            $JsonDetalleFacturaIntegracion .= '{
                                "numeroLinea": "' . ($i + 1) . '",
                                "codigoArticulo": "' . $Articulo["codigo"] . '",
                                "nombreArticulo": "' . $Articulo["nombre"] . ' ' . $descripcion_detalle . '",
                                "cantidadArticulo": "' . $cantidad . '",
                                "valorUnitario": "' . $q_ref . '",
                                "unidadMedida": "1",
                                "valorDescuento": "0",
                                "tipoItem": "B",
                                "impBruto": "0",
                                "impDescuento": "' . $subtotaldes1 . '",
                                "impExento": "0",
                                "impOtros": "0",
                                "impTotal": "' . $q_ref . '",
                                 "isExcepto": '.RES_VALIDARIMPUESTO.'
                            }';
                        } else {
                            $JsonDetalleFacturaIntegracion .= ',{
                                "numeroLinea": "' . ($i + 1) . '",
                                "codigoArticulo": "' . $Articulo["codigo"] . '",
                                "nombreArticulo": "' . $Articulo["nombre"] . ' ' . $descripcion_detalle . '",
                                "cantidadArticulo": "' . $cantidad . '",
                                "valorUnitario": "' . $q_ref . '",
                                "unidadMedida": "1",
                                "valorDescuento": "0",
                                "tipoItem": "B",
                                "impBruto": "0",
                                "impDescuento": "' . $subtotaldes1 . '",
                                "impExento": "0",
                                "impOtros": "0",
                                "impTotal": "' . $q_ref . '",
                                "isExcepto": '.RES_VALIDARIMPUESTO.'
                            }';
                        }
                        ///fin
                        //VALIDAR SI ES TOPPING O EXTRA
                        $extras = $articulos['idarticuloExtra_extras'];
                        $tipos = $articulos['tipo_item_extras'];
                        $cantidades = $articulos['cantidad_extra'];
                        $productos = $articulos['idproducto_extra'];
                        $check = $articulos['check_extras'];

                        for ($j = 0; $j < count($extras); $j++) {
                            $tipo = $tipos[$j];
                            $idextra = $extras[$j];
                            $cantidad_extra = $cantidades[$j];
                            $idproducto = $productos[$j];

                            // Calcular el total a descontar (cantidad_extra * totalcantidadpresentacion)
                            $total_a_descontar = $cantidad_extra * $totalcantidadpresentacion;

                            // Actualizar el stock en la tabla articuloxsucursal, descontando la cantidad correspondiente
                            $sqlArticuloStock = "UPDATE articuloxsucursal 
                                                SET stocksucursal = stocksucursal - $total_a_descontar 
                                                WHERE idarticulo = $idextra 
                                                AND idsucursal = '" . $_SESSION["idsucursal"] . "'";
                            // Ejecutar la consulta
                            ejecutarConsulta($sqlArticuloStock) or $sw = false;

                            if ($tipo === 'Topping') {
                                // Insertar en detalle_venta (como topping)
                                $sql_topping = "INSERT INTO detalle_venta(
                                    idventa, idarticulo, cantidad, precio_venta, descuento, stockinven, subtotaldes1,
                                    precio_ventaSistema, precio_ventaSistema2, subtotal1, cantidadpresentacion,
                                    totalcantidadpresentacion, presen, precio_recargo, q_ref, precio_recargoPV, 
                                    precio_recargoQRef, precio_compra
                                ) VALUES (
                                    '$idventanew','$idextra','$total_a_descontar','0','0','0','0',
                                    '0','0','0','$total_a_descontar','$total_a_descontar','0','0','0','0','0','0'
                                )";
                                ejecutarConsulta($sql_topping) or $sw = false;
                            } elseif ($tipo === 'Extra') {
                                // Obtener precio_venta desde articuloxsucursal
                                $sqlPrecio = "SELECT precio_venta 
                                            FROM articuloxsucursal 
                                            WHERE idarticulo = '$idextra' 
                                            AND idsucursal = '" . $_SESSION["idsucursal"] . "' 
                                            LIMIT 1";
                                
                                $resPrecio = ejecutarConsultaSimpleFila($sqlPrecio);
                                $precio_venta_extra = isset($resPrecio["precio_venta"]) ? $resPrecio["precio_venta"] : 0;

                                // Insertar en detalle_ventaExtra
                                $sql_extra = "INSERT INTO detalle_ventaExtra(
                                    idventa, idarticulo, idproducto, cantidad, precio_venta
                                ) VALUES (
                                    '$idventanew', '$idextra', '$idproducto', '$total_a_descontar', '$precio_venta_extra'
                                )";
                                
                                ejecutarConsulta($sql_extra) or $sw = false;
                            }

                            ////////materia prima
                                    $sqlVerificacionExistencia = "SELECT 
                                    p.idproducto,
                                    dp.cantidad as cantmateriaprima,
                                    dp.idarticulo as idarticulo_costo
                                    FROM produccion p 
                                    INNER JOIN detalle_produccion dp ON p.idproduccion=dp.idproduccion
                                    WHERE p.idproducto='$idarticulo' and dp.tipo_item='Producto'";
                                $EXIS = ejecutarConsulta($sqlVerificacionExistencia);

                                $numexis = 0;

                                while ($reeeq = $EXIS->fetch_object()) {
                                    $Tcan = $cantidad * $reeeq->cantmateriaprima;

                                    $updateArticuloDetalle = "UPDATE articuloxsucursal SET stocksucursal=stocksucursal-$Tcan
                                                            WHERE idarticulo='" . $reeeq->idarticulo_costo . "'   and idsucursal='" . $_SESSION["idsucursal"] . "' ";
                                    ejecutarConsulta($updateArticuloDetalle);

                                    $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idingreso,idventa,idtraladosucursal,idtraladosucursal_entrada,iddevolucion,cantidad_compras,cantidad_ventas,cantidad_entrada,cantidad_devolucion,cantidad_salida,stock_inventario,fecha_horaCreacion,idarticulo,idusuario,idsucursal) 
                                                            VALUES ('0','$idventanew','0','0','0','0','$Tcan','0','0','0','$stockinven','$fechaHora','$idarticulo','$idusuario','" . $_SESSION["idsucursal"] . "')";
                                    ejecutarConsulta($sql_detalleoperaciones);

                                    $numexis++;
                                }
                                if ($numexis == 0) {
                                }
                            //////fin materia prima
                        }
                    //
                    }
                }
                $JsonIntegracionEcoFactura = str_replace("{DetalleFactura}", $JsonDetalleFacturaIntegracion, $JsonIntegracionEcoFactura);
            }


            //API URL
            if ($tipo_comprobante == "Factura" || $tipo_comprobante == "Cambiaria") 
            {
                //URLS de Desarrollo

                if (CERTIFICADOR == "GUATEFACTURAS") {
                    if (PRUEBA_PRODUCCION == "PRUEBAS") {
                        $url = 'http://daocastro-001-site8.itempurl.com/api/GuateFactura/generarDocumento'; //url de pruebas
                    } elseif (PRUEBA_PRODUCCION == "PRODUCCION") {

                        $url = 'http://api.fel.olintech.com/api/GuateFactura/generarDocumento'; //url de produccion
                    }

               
                    //////
                    $resultado = $this->callAPI("POST", $url, $JsonIntegracionEcoFactura);
                    $ArrayResultado = json_decode($resultado, true);

                    if (!isset($ArrayResultado['resultado']['serie'])) {
                        $sqlUpdatenovalidado = "UPDATE venta SET tipo_comprobante='Envio' WHERE idventa='$idventanew'";
                        ejecutarConsulta($sqlUpdatenovalidado);
                        
                        $sqlLgs = "INSERT INTO logs (idventa,idusuario,idsucursal,JsonIntegracionEcoFactura,resultado,ArrayResultado)
                            VALUES ('$idventanew','$idusuario','" . $_SESSION["idsucursal"] . "',
                            '$JsonIntegracionEcoFactura','$resultado','" . json_encode($ArrayResultado) . "')";
                        ejecutarConsulta($sqlLgs); 
                    } 
                        // Si existe 'resultado' con la estructura esperada, es una certificación exitosa
                    else 
                   {
                          
                        $sqlUpdate = "UPDATE venta SET 
                                autorizacionEcoFactura='" . $ArrayResultado["resultado"]["numeroAutorizacion"] . "',
                                serie_ecoFactura='" . $ArrayResultado["resultado"]["serie"] . "',
                                numero_ecoFactura='" . $ArrayResultado["resultado"]["preimpreso"] . "' WHERE idventa='$idventanew'";
                        ejecutarConsulta($sqlUpdate);
                    }

                }
            }
        }
        //return $idventanew;  
                             $sqlValidoVenta="SELECT * FROM venta WHERE  idventa='$idventanew'"; 
                            $resvalidoventa= ejecutarConsultaSimpleFila($sqlValidoVenta);
                            $restipo_comprobante=$resvalidoventa["tipo_comprobante"];        

        return [
            'idventanew' => $idventanew,
            'tipo_comprobante' => $restipo_comprobante
        ];
    }

    //HASTA ACA LLEGA EL INSERTAR LA VENTA *********************************************************************/

    //Implementamos un método para insertar registros
    public function guardaryeditarnc(
        $idventa,
        $idcliente,
        $codigo_cliente,
        $nit,
        $nombre_cliente,
        $telefono_cliente,
        $direccion_cliente,
        $correo_cliente,
        $tipo_documento_cliente,
        $idusuario,
        $idcotizacion,
        $fecha_hora,
        $forma_pago,
        $tipo_comprobante,
        $total_venta,
        $total_ventades,
        $cefectivo,
        $ccredito,
        $ctarjeta,
        $ctransferencia,
        $rescambio,
        $valor_tarjeta,
        $tipo_pagoBacVisaNet,
        $opcionesAdicionales,
        $observacion_credito,
        $datosArticulos,
        $autorizacionEcoFactura_venta,
        $serie_comprobante_venta,
        $numero_ecoFactura_venta,
        $fecha_hora_nc,
        $motivo_nc
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

            $sqlcliente = "INSERT INTO persona (tipo_persona,nombre,tipo_documento,num_documento,direccion,telefono,email,tipo_cliente,codigo_cliente,fechaCreacion)
                VALUES ('Cliente','$nombre_cliente','$tipo_documento_cliente','$nit','$direccion_cliente','$telefono_cliente','$correo_cliente','PUBLICO','$codigo_cliente','$fechaHora')";
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


        ////datos establecimiento y persona
        $sqlPersona = "SELECT * FROM persona WHERE idpersona='$residcliente'";
        $Persona = ejecutarConsultaSimpleFila($sqlPersona);
        #echo json_encode($Persona);
        $nit = "CF";
        $tipoidentificador = "1";
        if ($Persona["num_documento"] == "C/F") {
        } else {
            if ($Persona["tipo_documento"] == "NIT") {
                $flagNit = str_replace("-", "", $Persona["num_documento"]);
                if (strlen($flagNit) <= 15) {
                    $nit = $Persona["num_documento"];
                    $tipoidentificador = "1";
                } else {
                    $nit = "CF";
                    $tipoidentificador = "1";
                }
            } elseif ($Persona["tipo_documento"] == "DPI") {
                $nit = $Persona["num_documento"];
                $tipoidentificador = "2";
            } elseif ($Persona["tipo_documento"] == "PASAPORTE") {
                $nit = $Persona["num_documento"];
                $tipoidentificador = "3";
            }
        }

        ////fin datos establecimiento y persona        


        if ($tipo_comprobante == "Envio") 
        {

            $sqlcorrelativo = "UPDATE add_correlativo SET num_nc=num_nc+1 WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
            ejecutarConsulta($sqlcorrelativo);

            $sqlCorre = "SELECT * FROM add_correlativo WHERE idsucursal='" . $_SESSION["idsucursal"] . "'";
            $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
            $corre = $correlativo["num_nc"];

            $sql = "INSERT INTO nota_credito (idcliente,idusuario,idsucursal,tipo_comprobante,num_comprobante,fecha_hora,total_venta,estado,cefectivo,rescambio,forma_pago,total_ventades,
            tipo_pagoBacVisaNet,opcionesAdicionales,valor_tarjeta,ccredito,observacion_credito,ctarjeta,ctransferencia,fecha_creacion,autorizacionEcoFactura_venta,serie_comprobante_venta,numero_ecoFactura_venta,fecha_hora_nc,motivo_nc,idventa)
            VALUES ('$residcliente','$idusuario','" . $_SESSION["idsucursal"] . "','$tipo_comprobante','$corre','$fecha_hora','$total_venta','Aceptado','$cefectivo','$rescambio','$forma_pago','$total_ventades',
            '$tipo_pagoBacVisaNet','$opcionesAdicionales','$valor_tarjeta','$ccredito','$observacion_credito','$ctarjeta','$ctransferencia','$fechaHora','$autorizacionEcoFactura_venta','$serie_comprobante_venta','$numero_ecoFactura_venta','$fecha_hora_nc','$motivo_nc','$idventa')";
            $idventanew = ejecutarConsulta_retornarID($sql);

            $sqlnc = "UPDATE venta SET idnota_credito='$idventanew',notacredito='SI' WHERE idventa='$idventa' ";
            ejecutarConsulta($sqlnc);


            $num_elementos = 0;
            $sw = true;



            if ($idventanew) {
                $articulos = $datosArticulos['articulos'];
                $numArticulos = count($articulos['idarticulo']);
                for ($i = 0; $i < $numArticulos; $i++) {
                    $idarticulo = $articulos['idarticulo'][$i];
                    $descripcion_detalle = $articulos['descripcion_detalle'][$i];
                    $stockinven = $articulos['stockinven'][$i];
                    $cantidadpresentacion = $articulos['cantidadpresentacion'][$i];
                    $cantidad = $articulos['cantidad'][$i];
                    $totalcantidadpresentacion = $articulos['totalcantidadpresentacion'][$i];
                    $presentacion = $articulos['presentacion'][$i];
                    $presen = $articulos['presen'][$i];
                    $precio_ventaSistema = $articulos['precio_ventaSistema'][$i];
                    $precio_ventaSistema2 = $articulos['precio_ventaSistema2'][$i];
                    $q_ref = $articulos['q_ref'][$i];
                    $precio_venta = $articulos['precio_venta'][$i];
                    $precio_recargoPV = $articulos['precio_recargoPV'][$i];
                    $precio_recargoQRef = $articulos['precio_recargoQRef'][$i];
                    $descuento_porcentaje = $articulos['descuento_porcentaje'][$i];
                    $subtotal1 = $articulos['subtotal1'][$i];
                    $subtotaldes1 = $articulos['subtotaldes1'][$i];

                    $sqlPCcompra = "SELECT 
                                    asu.precio_compra as pc_compra, 
                                    asu.stocksucursal,
                                    a.tipo_producto
                                FROM articuloxsucursal asu
                                inner join articulo a on a.idarticulo=asu.idarticulo
                                WHERE asu.idarticulo='$idarticulo'  and asu.idsucursal='" . $_SESSION["idsucursal"] . "' ";
                    $respc = ejecutarConsultaSimpleFila($sqlPCcompra);
                    $pc_compra = $respc["pc_compra"];
                    $tipoproducto = $respc["tipo_producto"];


                    $sql_detalle = "INSERT INTO detalle_nota_credito(idnota_credito,
                    idarticulo,cantidad,precio_venta,descuento,stockinven,subtotaldes1,precio_ventaSistema,precio_ventaSistema2,subtotal1,cantidadpresentacion,
                    totalcantidadpresentacion,presen,precio_recargo,q_ref,precio_recargoPV,precio_recargoQRef) 
                    VALUES ('$idventanew','$idarticulo','$cantidad','$precio_venta',
                    '$descuento_porcentaje',
                    '$stockinven',
                    '$subtotaldes1',
                    '$precio_ventaSistema',
                    '$precio_ventaSistema2',
                    '$subtotal1',
                    '$cantidadpresentacion',
                    '$totalcantidadpresentacion',
                    '$presen',
                    '0',
                    '$q_ref',
                    '$precio_recargoPV',
                    '$precio_recargoQRef')";
                    ejecutarConsulta($sql_detalle) or $sw = false;

                    if ($tipoproducto == "Productos") {

                        $sqlArticuloStock = "UPDATE articuloxsucursal SET stocksucursal = stocksucursal + " . $totalcantidadpresentacion . " 
                            WHERE idarticulo =$idarticulo  and idsucursal='" . $_SESSION["idsucursal"] . "' ";
                        ejecutarConsulta($sqlArticuloStock);
                    } else {
                    }


                    //INICIO
                    $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idingreso,idventa,idtraladosucursal,idtraladosucursal_entrada,iddevolucion,cantidad_compras,cantidad_ventas,
                        cantidad_entrada,cantidad_devolucion,cantidad_salida,stock_inventario,fecha_horaCreacion,idarticulo,idusuario,idsucursal) 
                        VALUES ('0','0','0','0','$idventanew','0','0','0','$totalcantidadpresentacion','0','$stockinven','$fechaHora','$idarticulo',
                        '$idusuario',
                        '" . $_SESSION["idsucursal"] . "')";
                    ejecutarConsulta($sql_detalleoperaciones);
                    ////FIN 

                    //valida el descuento de la materia prima
                    $sqlVerificacionExistencia = "SELECT 
                                    p.idproducto,
                                    dp.cantidad as cantmateriaprima,
                                    dp.idarticulo as idarticulo_costo
                                    FROM produccion p 
                                    INNER JOIN detalle_produccion dp ON p.idproduccion=dp.idproduccion
                                    WHERE p.idproducto='$idarticulo' ";
                    $EXIS = ejecutarConsulta($sqlVerificacionExistencia);

                    $numexis = 0;

                    while ($reeeq = $EXIS->fetch_object()) {
                        $Tcan = $cantidad * $reeeq->cantmateriaprima;

                        $updateArticuloDetalle = "UPDATE articuloxsucursal SET stocksucursal=stocksucursal+$Tcan
                                        WHERE idarticulo='" . $reeeq->idarticulo_costo . "'   and idsucursal='" . $_SESSION["idsucursal"] . "' ";
                        ejecutarConsulta($updateArticuloDetalle);

                        $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idingreso,idventa,idtraladosucursal,idtraladosucursal_entrada,iddevolucion,cantidad_compras,
                                        cantidad_ventas,cantidad_entrada,cantidad_devolucion,cantidad_salida,stock_inventario,fecha_horaCreacion,idarticulo,idusuario,idsucursal) 
                                            VALUES ('0','0','0','0','$idventanew','0','0','0','$Tcan','0','$stockinven','$fechaHora','$idarticulo','$idusuario',
                                            '" . $_SESSION["idsucursal"] . "')";
                        ejecutarConsulta($sql_detalleoperaciones);

                        $numexis++;
                    }
                    if ($numexis == 0) {
                    }
                }
            }
        } else {
            if (CERTIFICADOR == "GUATEFACTURAS") {
                ////GUARDAR NC
                $sqlcorrelativo = "UPDATE add_correlativo SET num_nc=num_nc+1 WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
                ejecutarConsulta($sqlcorrelativo);

                $sqlCorre = "SELECT * FROM add_correlativo WHERE idsucursal='" . $_SESSION["idsucursal"] . "'";
                $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
                $corre = $correlativo["num_nc"];

                $sql = "INSERT INTO nota_credito (idcliente,idusuario,idsucursal,tipo_comprobante,num_comprobante,fecha_hora,total_venta,estado,cefectivo,rescambio,forma_pago,total_ventades,
                            tipo_pagoBacVisaNet,opcionesAdicionales,valor_tarjeta,ccredito,observacion_credito,ctarjeta,ctransferencia,fecha_creacion,autorizacionEcoFactura_venta,serie_comprobante_venta,numero_ecoFactura_venta,fecha_hora_nc,motivo_nc,idventa)
                            VALUES ('$residcliente','$idusuario','" . $_SESSION["idsucursal"] . "','$tipo_comprobante','$corre','$fecha_hora','$total_venta','Aceptado','$cefectivo','$rescambio','$forma_pago','$total_ventades',
                            '$tipo_pagoBacVisaNet','$opcionesAdicionales','$valor_tarjeta','$ccredito','$observacion_credito','$ctarjeta','$ctransferencia','$fechaHora','$autorizacionEcoFactura_venta','$serie_comprobante_venta','$numero_ecoFactura_venta','$fecha_hora_nc','$motivo_nc','$idventa')";
                $idventanew = ejecutarConsulta_retornarID($sql);

                $sqlnc = "UPDATE venta SET idnota_credito='$idventanew',notacredito='SI' WHERE idventa='$idventa' ";
                ejecutarConsulta($sqlnc);

                ////FIN GUARDAR NC

                //buscamos los datos de sat para enviar a nota de credito
                $sqlDatosVentasat = "SELECT v2.*,date(v2.fechaCertificacion_ecoFactura) as fechaCertificacion_ecoFactur FROM venta v2 WHERE v2.idventa='$idventa'";
                $numdatosSat = ejecutarConsultaSimpleFila($sqlDatosVentasat);
                $resautorizacionEcoFactura = $numdatosSat["autorizacionEcoFactura"];
                $resserie_ecoFactura = $numdatosSat["serie_ecoFactura"];
                $resnumero_ecoFactura = $numdatosSat["numero_ecoFactura"];
                $resfechaCertificacion_ecoFactura = $numdatosSat["fechaCertificacion_ecoFactur"];
                //FIN buscamos los datos de sat para enviar a nota de credito

                ////DETALLE DE VENTA
                date_default_timezone_set("America/Guatemala");
                $nombreCliente = $Persona["nombre"]; // Suponiendo que este es tu nombre

                // Escapar las comillas dobles
                $nombreClienteEscapado = str_replace('"', '\"', $nombreCliente);
                $tipoidentificadorNC=RES_VALIDARIMPUESTO;
                $JsonIntegracionEcoFactura = '{
                                     "tipoDocumento": "NCRE",
                                        "usuario":"' . _CLIENTE_ . '",
                                        "clave":"' . _PASS_ . '",   
                                        "nit":"' . _NIT_ . '",                     
                                        "establecimiento": "' . NUM_ESTABLECIMIENTO . '",
                                        "tipoDocumentoInt": "' . $tipoidentificadorNC . '",
                                        "maquina": "1",
                                        "tipoVenta": "B",
                                        "bruto": "' . $total_venta . '",
                                        "descuento": "' . $total_ventades . '",
                                        "exento":"0",
                                        "otros":"0",
                                        "neto":"0",
                                        "isr":"0",
                                        "total":"' . $total_venta . '",                   
                                        "numeroTransaccion": "' . $idventanew . '",
                                        "fechaTransaccion": "' . date("d/m/Y", strtotime($fecha_hora)) . '",
                                        "tipoMoneda":"1",
                                        "nitCliente": "' . $nit . '",
                                        "codigoCliente": "' . $Persona["idpersona"] . '",
                                        "nombreCliente": "' . $nombreClienteEscapado . '",
                                        "direccionCliente": "' . $Persona["direccion"] . '",
                                        "correoCliente": "' . $Persona["email"] . '",
                                        "DASerieNC":"'.$resserie_ecoFactura.'",
                                        "DAPreimpresoNC":"'.$resnumero_ecoFactura.'",
                                    "detallesDocumento":[{DetalleFactura}]                               
                                }';

                $num_elementos = 0;
                $sw = true;
                $JsonDetalleFacturaIntegracion = "";

                if ($idventanew) {
                    $articulos = $datosArticulos['articulos'];
                    $numArticulos = count($articulos['idarticulo']);
                    for ($i = 0; $i < $numArticulos; $i++) {
                        $idarticulo = $articulos['idarticulo'][$i];
                        $descripcion_detalle = $articulos['descripcion_detalle'][$i];
                        $stockinven = $articulos['stockinven'][$i];
                        $cantidadpresentacion = $articulos['cantidadpresentacion'][$i];
                        $cantidad = $articulos['cantidad'][$i];
                        $totalcantidadpresentacion = $articulos['totalcantidadpresentacion'][$i];
                        $presentacion = $articulos['presentacion'][$i];
                        $presen = $articulos['presen'][$i];
                        $precio_ventaSistema = $articulos['precio_ventaSistema'][$i];
                        $precio_ventaSistema2 = $articulos['precio_ventaSistema2'][$i];
                        $q_ref = $articulos['q_ref'][$i];
                        $precio_venta = $articulos['precio_venta'][$i];
                        $precio_recargoPV = $articulos['precio_recargoPV'][$i];
                        $precio_recargoQRef = $articulos['precio_recargoQRef'][$i];
                        $descuento_porcentaje = $articulos['descuento_porcentaje'][$i];
                        $subtotal1 = $articulos['subtotal1'][$i];
                        $subtotaldes1 = $articulos['subtotaldes1'][$i];

                        $sqlPCcompra = "SELECT 
                                                    asu.precio_compra as pc_compra, 
                                                    asu.stocksucursal,
                                                    a.tipo_producto
                                                FROM articuloxsucursal asu
                                                inner join articulo a on a.idarticulo=asu.idarticulo
                                                WHERE asu.idarticulo='$idarticulo'  and asu.idsucursal='" . $_SESSION["idsucursal"] . "' ";
                        $respc = ejecutarConsultaSimpleFila($sqlPCcompra);
                        $pc_compra = $respc["pc_compra"];
                        $tipoproducto = $respc["tipo_producto"];

                        $sql_detalle = "INSERT INTO detalle_nota_credito(idnota_credito,idarticulo,cantidad,precio_venta,descuento,stockinven,subtotaldes1,precio_ventaSistema,precio_ventaSistema2,subtotal1,cantidadpresentacion,totalcantidadpresentacion,presen,precio_recargo,q_ref,precio_recargoPV,precio_recargoQRef) 
                                            VALUES ('$idventanew','$idarticulo','$cantidad','$precio_venta','$descuento_porcentaje','$stockinven','$subtotaldes1','$precio_ventaSistema',
                                            '$precio_ventaSistema2','$subtotal1','$cantidadpresentacion','$totalcantidadpresentacion','$presen','0','$q_ref','$precio_recargoPV','$precio_recargoQRef')";
                        ejecutarConsulta($sql_detalle) or $sw = false;

                        $sqlArticulo = "SELECT * FROM articulo WHERE idarticulo='$idarticulo'";
                        $Articulo = ejecutarConsultaSimpleFila($sqlArticulo);

                        if ($tipoproducto == "Productos") {
                            $sqlArticuloStock = "UPDATE articuloxsucursal SET stocksucursal = stocksucursal + " . $totalcantidadpresentacion . " 
                                                WHERE idarticulo =$idarticulo  and idsucursal='" . $_SESSION["idsucursal"] . "' ";
                            ejecutarConsulta($sqlArticuloStock);
                        } else {
                        }


                        $resvalidarimpuesto = RES_VALIDARIMPUESTO;

                        if ($i == 0) {
                            $JsonDetalleFacturaIntegracion .= '{
                                                    "numeroLinea": "'.($i+1).'",
                                                    "codigoArticulo": "' . $Articulo["codigo"] . '",
                                                    "nombreArticulo": "' . $Articulo["nombre"] . ' ' . $descripcion_detalle . '",
                                                    "cantidadArticulo": "' . $cantidad . '",
                                                    "valorUnitario": "' . $q_ref . '",
                                                    "unidadMedida": "1",
                                                    "valorDescuento": "0",
                                                    "tipoItem": "B",
                                                    "impBruto": "0",
                                                    "impDescuento": "' . $subtotaldes1 . '",
                                                    "impExento": "0",
                                                    "impOtros": "0",
                                                    "impTotal": "' . $q_ref . '",
                                                    "isExcepto": '.$resvalidarimpuesto.'
                                                    }';
                        } else {
                            $JsonDetalleFacturaIntegracion .= ',{
                                                       "numeroLinea": "'.($i+1).'",
                                                    "codigoArticulo": "' . $Articulo["codigo"] . '",
                                                    "nombreArticulo": "' . $Articulo["nombre"] . ' ' . $descripcion_detalle . '",
                                                    "cantidadArticulo": "' . $cantidad . '",
                                                    "valorUnitario": "' . $q_ref . '",
                                                    "unidadMedida": "1",
                                                    "valorDescuento": "0",
                                                    "tipoItem": "B",
                                                    "impBruto": "0",
                                                    "impDescuento": "' . $subtotaldes1 . '",
                                                    "impExento": "0",
                                                    "impOtros": "0",
                                                    "impTotal": "' . $q_ref . '",
                                                    "isExcepto": '.$resvalidarimpuesto.'                          
                                                    }';
                        }

                        //INICIO
                        $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idingreso,idventa,idtraladosucursal,idtraladosucursal_entrada,iddevolucion,cantidad_compras,cantidad_ventas,cantidad_entrada,cantidad_devolucion,cantidad_salida,stock_inventario,fecha_horaCreacion,idarticulo,idusuario,idsucursal) 
                                                    VALUES ('0','0','0','0','$idventanew','0','0','0','$totalcantidadpresentacion','0','$stockinven','$fechaHora','$idarticulo','$idusuario',
                                                    '" . $_SESSION["idsucursal"] . "')";
                        ejecutarConsulta($sql_detalleoperaciones);
                        ////FIN   
                        //valida el descuento de la materia prima


                        $sqlVerificacionExistencia = "SELECT 
                                            p.idproducto,
                                            dp.cantidad as cantmateriaprima,
                                            dp.idarticulo as idarticulo_costo
                                            FROM produccion p 
                                            INNER JOIN detalle_produccion dp ON p.idproduccion=dp.idproduccion
                                            WHERE p.idproducto='$idarticulo' ";
                        $EXIS = ejecutarConsulta($sqlVerificacionExistencia);

                        $numexis = 0;

                        while ($reeeq = $EXIS->fetch_object()) {
                            $Tcan = $cantidad * $reeeq->cantmateriaprima;

                            $updateArticuloDetalle = "UPDATE articuloxsucursal SET stocksucursal=stocksucursal+$Tcan
                                                WHERE idarticulo='" . $reeeq->idarticulo_costo . "'   and idsucursal='" . $_SESSION["idsucursal"] . "' ";
                            ejecutarConsulta($updateArticuloDetalle);

                            $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idingreso,idventa,idtraladosucursal,idtraladosucursal_entrada,iddevolucion,cantidad_compras,cantidad_ventas,cantidad_entrada,cantidad_devolucion,cantidad_salida,stock_inventario,fecha_horaCreacion,idarticulo,idusuario,idsucursal) 
                                                    VALUES ('0','0','0','0','$idventanew','0','0','0','$Tcan','0','$stockinven','$fechaHora','$idarticulo','$idusuario',
                                                    '" . $_SESSION["idsucursal"] . "')";
                            ejecutarConsulta($sql_detalleoperaciones);

                            $numexis++;
                        }
                        if ($numexis == 0) {
                        }
                    }
                }


                $JsonIntegracionEcoFactura = str_replace("{DetalleFactura}", $JsonDetalleFacturaIntegracion, $JsonIntegracionEcoFactura);

                ///FIN DE DETALLE VENTA
            }
        }


            //API URL
            if ($tipo_comprobante == "Factura" || $tipo_comprobante == "Cambiaria") 
            {
                //URLS de Desarrollo

                if (CERTIFICADOR == "GUATEFACTURAS") 
                {
                    $url = 'http://api.fel.olintech.com/api/GuateFactura/generarDocumento'; //url de produccion

               
                    //////
                    $resultado = $this->callAPI("POST", $url, $JsonIntegracionEcoFactura);
                    $ArrayResultado = json_decode($resultado, true);

                   /* print_r($JsonIntegracionEcoFactura);
                    print_r($resultado);
                    print_r($ArrayResultado);*/


                    if (!isset($ArrayResultado['resultado']['serie'])) {
                        $sqlUpdatenovalidado = "UPDATE nota_credito SET tipo_comprobante='Envio' WHERE idnota_credito='$idventanew'";
                        ejecutarConsulta($sqlUpdatenovalidado);
                        
                        $sqlLgs = "INSERT INTO logs (idventa,idnota_credito,idusuario,idsucursal,JsonIntegracionEcoFactura,resultado,ArrayResultado)
                            VALUES ('$idventa','$idventanew','$idusuario','" . $_SESSION["idsucursal"] . "',
                            '$JsonIntegracionEcoFactura','$resultado','" . json_encode($ArrayResultado) . "')";
                        ejecutarConsulta($sqlLgs); 
                    } 
                        // Si existe 'resultado' con la estructura esperada, es una certificación exitosa
                    else 
                   {
                          
                        $sqlUpdate = "UPDATE nota_credito SET 
                                                    autorizacionEcoFactura='" . $ArrayResultado["resultado"]["numeroAutorizacion"] . "',
                                serie_ecoFactura='" . $ArrayResultado["resultado"]["serie"] . "',
                                numero_ecoFactura='" . $ArrayResultado["resultado"]["preimpreso"] . "' WHERE idnota_credito='$idventanew'";
                        ejecutarConsulta($sqlUpdate);
                    }

                }
            }
        
        //return $idventanew;  
        $sqlValidoVentas="SELECT * FROM nota_credito WHERE  idnota_credito='$idventanew'"; 
        $resvalidoventass= ejecutarConsultaSimpleFila($sqlValidoVentas);
        $restipo_comprobante=$resvalidoventass["tipo_comprobante"];    

        //return $idventanew;  



        return [
            'idventanew' => $idventanew,
            'tipo_comprobante' => $restipo_comprobante
        ];
    }




    //Implementamos un método para insertar registros
    public function insertarCobro(
        $idcliente,
        $codigo_cliente,
        $nit,
        $nombre_cliente,
        $telefono_cliente,
        $direccion_cliente,
        $correo_cliente,
        $tipo_documento_cliente,
        $idusuario,
        $idcotizacion,
        $fecha_hora,
        $forma_pago,
        $tipo_comprobante,
        $total_venta,
        $total_ventades,
        $cefectivo,
        $ccredito,
        $ctarjeta,
        $ctransferencia,
        $rescambio,
        $valor_tarjeta,
        $tipo_pagoBacVisaNet,
        $opcionesAdicionales,
        $observacion_credito,
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
        $subtotaldes1,
        $id_add_orden,
        $propina,
        $descripcion_detalle
    ) 
    {

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

            $sqlcliente = "INSERT INTO persona (tipo_persona,nombre,tipo_documento,num_documento,direccion,telefono,email,tipo_cliente,codigo_cliente,fechaCreacion)
                VALUES ('Cliente','$nombre_cliente','$tipo_documento_cliente','$nit','$direccion_cliente','$telefono_cliente','$correo_cliente','PUBLICO','$codigo_cliente','$fechaHora')";
            $residcliente = ejecutarConsulta_retornarID($sqlcliente);

            if (!$residcliente) {
                throw new Exception("Error al insertar nuevo cliente.");
            }
        } else 
        {
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

        ////datos establecimiento y persona
        $sqlPersona = "SELECT * FROM persona WHERE idpersona='$residcliente'";
        $Persona = ejecutarConsultaSimpleFila($sqlPersona);
        #echo json_encode($Persona);
        $nit = "CF";
        $tipoidentificador = "1";
        if ($Persona["num_documento"] == "C/F") {
        } else {
            if ($Persona["tipo_documento"] == "NIT") {
                $flagNit = str_replace("-", "", $Persona["num_documento"]);
                if (strlen($flagNit) <= 15) {
                    $nit = $Persona["num_documento"];
                    $tipoidentificador = "1";
                } else {
                    $nit = "CF";
                    $tipoidentificador = "1";
                }
            } elseif ($Persona["tipo_documento"] == "DPI") {
                $nit = $Persona["num_documento"];
                $tipoidentificador = "2";
            } elseif ($Persona["tipo_documento"] == "PASAPORTE") {
                $nit = $Persona["num_documento"];
                $tipoidentificador = "3";
            }
        }

        ////fin datos establecimiento y persona        

        if ($tipo_comprobante == "Envio") {
            ///GURADO ENVIO
            $sqlcorrelativo = "UPDATE add_correlativo SET num_envio=num_envio+1 WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
            ejecutarConsulta($sqlcorrelativo);

            $sqlCorre = "SELECT * FROM add_correlativo WHERE idsucursal='" . $_SESSION["idsucursal"] . "'";
            $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
            $corre = $correlativo["num_envio"];

            $sql = "INSERT INTO venta (idcliente,idusuario,idsucursal,tipo_comprobante,num_comprobante,fecha_hora,total_venta,estado,cefectivo,rescambio,forma_pago,total_ventades,
                tipo_pagoBacVisaNet,opcionesAdicionales,valor_tarjeta,ccredito,observacion_credito,ctarjeta,ctransferencia,fecha_creacion,propina,estado_venta)
                VALUES ('$residcliente','$idusuario','" . $_SESSION["idsucursal"] . "','$tipo_comprobante','$corre','$fecha_hora','$total_venta','Aceptado','$cefectivo','$rescambio','$forma_pago','$total_ventades',
                '$tipo_pagoBacVisaNet','$opcionesAdicionales','$valor_tarjeta','$ccredito','$observacion_credito','$ctarjeta','$ctransferencia','$fechaHora','$propina','COMPLETO')";
            $idventanew = ejecutarConsulta_retornarID($sql);

            if ($id_add_orden == "") {
            } else {
                $sqlcorrelativo = "UPDATE add_orden SET cobradosino='SI',idventa='$idventanew', estado='COBRADO' WHERE id_add_orden ='$id_add_orden'";
                ejecutarConsulta($sqlcorrelativo);

                $sqlObtenermesa = "SELECT * FROM add_orden WHERE id_add_orden='$id_add_orden'";
                $numestable = ejecutarConsultaSimpleFila($sqlObtenermesa);
                $idmesa = $numestable["idmesa"];

                $sqlMesa = "UPDATE mesa SET condicion='1'  WHERE idmesa ='$idmesa'";
                ejecutarConsulta($sqlMesa);
            }

            if ($forma_pago == "Credito") {
                $sqlCredito = "UPDATE venta SET saldo_venta='$ccredito' WHERE idventa='$idventanew' ";
                ejecutarConsulta($sqlCredito);
            }

            if ($idcotizacion == "") {
                $residcotizacion = 0;
            } else {
                $residcotizacion = $idcotizacion;
                $sqlcorrelativo = "UPDATE cotizacion SET idventa='$idventanew', cobradosino='SI' WHERE idcotizacion ='$idcotizacion'";
                ejecutarConsulta($sqlcorrelativo);
            }

            ////FIN GUARDO EL ENVIO

            $num_elementos = 0;
            $sw = true;


            while ($num_elementos < count($idarticulo)) {
                ////PRECIO COMPRA PROMEDIO
                $sqlPCcompra = "SELECT 
                                    asu.precio_compra as pc_compra, 
                                    asu.stocksucursal,
                                    a.tipo_producto
                                FROM articuloxsucursal asu
                                inner join articulo a on a.idarticulo=asu.idarticulo
                                WHERE asu.idarticulo='$idarticulo[$num_elementos]'  and asu.idsucursal='" . $_SESSION["idsucursal"] . "' ";
                $respc = ejecutarConsultaSimpleFila($sqlPCcompra);
                $pc_compra = $respc["pc_compra"];
                $tipoproducto = $respc["tipo_producto"];
                ////FIN PRECIO COMPRA PROMEDIO                    

                $sql_detalle = "INSERT INTO detalle_venta(idventa,idarticulo,cantidad,precio_venta,descuento,stockinven,subtotaldes1,precio_ventaSistema,precio_ventaSistema2,subtotal1,cantidadpresentacion,totalcantidadpresentacion,presen,precio_recargo,q_ref,precio_recargoPV,precio_recargoQRef,precio_compra) 
                VALUES ('$idventanew','$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento_porcentaje[$num_elementos]','$stockinven[$num_elementos]','$subtotaldes1[$num_elementos]','$precio_ventaSistema[$num_elementos]','$precio_ventaSistema2[$num_elementos]','$subtotal1[$num_elementos]','$cantidadpresentacion[$num_elementos]',
                '$totalcantidadpresentacion[$num_elementos]','$presen[$num_elementos]','0','$q_ref[$num_elementos]','$precio_recargoPV[$num_elementos]',
                '$precio_recargoQRef[$num_elementos]','$pc_compra')";
                ejecutarConsulta($sql_detalle) or $sw = false;


                if ($tipoproducto == "Productos") {
                    # code...
                    $sqlArticuloStock = "UPDATE articuloxsucursal SET stocksucursal = stocksucursal - " . $totalcantidadpresentacion[$num_elementos] . " WHERE idarticulo =$idarticulo[$num_elementos]  and idsucursal='" . $_SESSION["idsucursal"] . "' ";
                    ejecutarConsulta($sqlArticuloStock);
                } else {
                    # code...

                }


                $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idingreso,idventa,idtraladosucursal,idtraladosucursal_entrada,iddevolucion,cantidad_compras,cantidad_ventas,
                cantidad_entrada,cantidad_devolucion,cantidad_salida,stock_inventario,fecha_horaCreacion,idarticulo,idusuario,
                idsucursal) 
                VALUES ('0',
                '$idventanew','0','0','0','0','$totalcantidadpresentacion[$num_elementos]','0','0','0','$stockinven[$num_elementos]','$fechaHora',
                '$idarticulo[$num_elementos]','$idusuario',
                '" . $_SESSION["idsucursal"] . "')";
                ejecutarConsulta($sql_detalleoperaciones);

                //valida el descuento de la materia prima
                $sqlVerificacionExistencia = "SELECT 
                p.idproducto,
                dp.cantidad as cantmateriaprima,
                dp.idarticulo as idarticulo_costo
                FROM produccion p 
                INNER JOIN detalle_produccion dp ON p.idproduccion=dp.idproduccion
                WHERE p.idproducto='$idarticulo[$num_elementos]' ";
                $EXIS = ejecutarConsulta($sqlVerificacionExistencia);

                $numexis = 0;

                while ($reeeq = $EXIS->fetch_object()) {
                    $Tcan = $cantidad[$num_elementos] * $reeeq->cantmateriaprima;

                    $updateArticuloDetalle = "UPDATE articuloxsucursal SET stocksucursal=stocksucursal-$Tcan
                    WHERE idarticulo='" . $reeeq->idarticulo_costo . "'   and idsucursal='" . $_SESSION["idsucursal"] . "' ";
                    //print_r($updateArticuloDetalle);
                    ejecutarConsulta($updateArticuloDetalle);

                    $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idingreso,idventa,idtraladosucursal,idtraladosucursal_entrada,iddevolucion,cantidad_compras,cantidad_ventas,cantidad_entrada,cantidad_devolucion,cantidad_salida,stock_inventario,fecha_horaCreacion,idarticulo,idusuario,idsucursal) 
                    VALUES ('0','$idventanew','0','0','0','0','$Tcan','0','0','0','$stockinven[$num_elementos]','$fechaHora','$idarticulo[$num_elementos]','$idusuario','" . $_SESSION["idsucursal"] . "')";
                    ejecutarConsulta($sql_detalleoperaciones);
                    $numexis++;
                }
                if ($numexis == 0) {
                }
                //fin de validacion del descuento de la materia prima                                                                                                  


                $num_elementos = $num_elementos + 1;
            }
        } else {
            if (CERTIFICADOR == "ECOFACTURAS") 
            {
                ////GUARDA VENTA
                $sqlcorrelativo = "UPDATE add_correlativo SET num_factura=num_factura+1 WHERE idsucursal='" . $_SESSION["idsucursal"] . "'";
                ejecutarConsulta($sqlcorrelativo);

                $sqlCorre = "SELECT * FROM add_correlativo WHERE idsucursal='" . $_SESSION["idsucursal"] . "'";
                $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
                $corre = $correlativo["num_factura"];


                $sql = "INSERT INTO venta (idcliente,idusuario,idsucursal,tipo_comprobante,num_comprobante,fecha_hora,total_venta,estado,cefectivo,rescambio,forma_pago,total_ventades,
                            tipo_pagoBacVisaNet,opcionesAdicionales,valor_tarjeta,ccredito,observacion_credito,ctarjeta,ctransferencia,fecha_creacion,numero_pagos,fecha_hora_pago,fecha_hora_vencimiento_factura,monto_abono,propina,estado_venta)
                            VALUES ('$residcliente','$idusuario','" . $_SESSION["idsucursal"] . "','$tipo_comprobante','$corre','$fecha_hora','$total_venta','Aceptado','$cefectivo','$rescambio','$forma_pago','$total_ventades',
                            '$tipo_pagoBacVisaNet','$opcionesAdicionales','$valor_tarjeta','$ccredito','$observacion_credito','$ctarjeta','$ctransferencia','$fechaHora','0','0','0','0','$propina','COMPLETO')";
                $idventanew = ejecutarConsulta_retornarID($sql);
                ////FIN GUARDA VENTA

                ////TIPO ENTREGA
                if ($id_add_orden == "") {
                } else {
                    $sqlcorrelativo = "UPDATE add_orden SET cobradosino='SI',idventa='$idventanew', estado='COBRADO' WHERE id_add_orden ='$id_add_orden'";
                    ejecutarConsulta($sqlcorrelativo);

                    $sqlObtenermesa = "SELECT * FROM add_orden WHERE id_add_orden='$id_add_orden'";
                    $numestable = ejecutarConsultaSimpleFila($sqlObtenermesa);
                    $idmesa = $numestable["idmesa"];

                    $sqlMesa = "UPDATE mesa SET condicion='1'  WHERE idmesa ='$idmesa'";
                    ejecutarConsulta($sqlMesa);
                }

                if ($forma_pago == "Credito") {
                    $sqlCredito = "UPDATE venta SET saldo_venta='$ccredito' WHERE idventa='$idventanew' ";
                    ejecutarConsulta($sqlCredito);
                }

                if ($idcotizacion == "") {
                    $residcotizacion = 0;
                } else {
                    $residcotizacion = $idcotizacion;
                    $sqlcorrelativo = "UPDATE cotizacion SET idventa='$idventanew', cobradosino='SI' WHERE idcotizacion ='$idcotizacion'";
                    ejecutarConsulta($sqlcorrelativo);
                }
                ////FIN TIPO ENTREGA

                ////DETALLE DE VENTA
                date_default_timezone_set("America/Guatemala");
                $nombreCliente = $Persona["nombre"]; // Suponiendo que este es tu nombre

                if ($tipo_comprobante == "Factura") {
                    # code...
                    $tipoDocumento = 'FACT';
                } elseif ($tipo_comprobante == "Cambiaria") {
                    # code...
                    $tipoDocumento = 'FCAM';
                }

                // Escapar las comillas dobles
                $nombreClienteEscapado = str_replace('"', '\"', $nombreCliente);

                $JsonIntegracionEcoFactura = '{
                                    "tipoDocumento": "' . $tipoDocumento . '",
                                    "numeroTransaccion": "' . $idventanew . '",
                                    "fechaTransaccion": "' . $fecha_hora . '",
                                    "tipoMoneda": "GTQ",
                                    "nitCliente": "' . $nit . '",
                                    "TipoIdentificacion": "' . $tipoidentificador . '",
                                    "codigoCliente": "' . $Persona["idpersona"] . '",
                                    "nombreCliente": "' . $nombreClienteEscapado . '",
                                    "direccionCliente": "' . $Persona["direccion"] . '",
                                    "observacion": "' . $observacion_credito . '",
                                    "correoCliente": "' . $Persona["email"] . '",
                                    "detallesDocumento":[{DetalleFactura}],
                                    "cliente":"' . _CLIENTE_ . '",
                                    "usuario":"' . _USUARIO_ . '",
                                    "clave":"' . _PASS_ . '",
                                    "nit":"' . _NIT_ . '",
                                    "TrnExp":"0",
                                    "TrnExento":"0",
                                    "TrnFraseTipo":"0",
                                    "TrnEscCod":"0",
                                    "TrnEstNum":"' . NUM_ESTABLECIMIENTO . '",
                                    "TrnAbonoNum":"0",
                                    "TrnAbonoFecVen":  "0",            
                                    "TrnAbonoMonto":  "0"                                
                                }';

                $num_elementos = 0;
                $sw = true;
                $JsonDetalleFacturaIntegracion = "";
                while ($num_elementos < count($idarticulo)) {

                    $sqlPCcompra = "SELECT 
                                                    asu.precio_compra as pc_compra, 
                                                    asu.stocksucursal,
                                                    a.tipo_producto
                                                FROM articuloxsucursal asu
                                                inner join articulo a on a.idarticulo=asu.idarticulo
                                                WHERE asu.idarticulo='$idarticulo[$num_elementos]'  and asu.idsucursal='" . $_SESSION["idsucursal"] . "' ";
                    $respc = ejecutarConsultaSimpleFila($sqlPCcompra);
                    $pc_compra = $respc["pc_compra"];
                    $tipoproducto = $respc["tipo_producto"];

                    $sql_detalle = "INSERT INTO detalle_venta(idventa,idarticulo,cantidad,precio_venta,descuento,stockinven,subtotaldes1,precio_ventaSistema,precio_ventaSistema2,subtotal1,cantidadpresentacion,totalcantidadpresentacion,presen,precio_recargo,q_ref,precio_recargoPV,precio_recargoQRef,descripcion_detalle) 
                                    VALUES ('$idventanew','$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento_porcentaje[$num_elementos]','$stockinven[$num_elementos]','$subtotaldes1[$num_elementos]','$precio_ventaSistema[$num_elementos]','$precio_ventaSistema2[$num_elementos]','$subtotal1[$num_elementos]','$cantidadpresentacion[$num_elementos]','$totalcantidadpresentacion[$num_elementos]','$presen[$num_elementos]','0','$q_ref[$num_elementos]','$precio_recargoPV[$num_elementos]','$precio_recargoQRef[$num_elementos]','$descripcion_detalle[$num_elementos]')";
                    ejecutarConsulta($sql_detalle) or $sw = false;

                    $sqlArticulo = "SELECT * FROM articulo WHERE idarticulo='$idarticulo[$num_elementos]'";
                    $Articulo = ejecutarConsultaSimpleFila($sqlArticulo);

                    if ($tipoproducto == "Productos") {
                        # code...
                        $sqlArticuloStock = "UPDATE articuloxsucursal SET stocksucursal = stocksucursal - " . $totalcantidadpresentacion[$num_elementos] . " WHERE idarticulo =$idarticulo[$num_elementos]  and idsucursal='" . $_SESSION["idsucursal"] . "' ";
                        ejecutarConsulta($sqlArticuloStock);
                    } else {
                        # code...

                    }

                    $resvalidarimpuesto = 'false';

                    if ($num_elementos == 0) {
                        $JsonDetalleFacturaIntegracion .= '{
                                                "numeroLinea": "' . ($num_elementos + 1) . '",
                                                "codigoArticulo": "' . $Articulo["codigo"] . '",
                                                "nombreArticulo": "' . $Articulo["nombre"] . ' ' . $descripcion_detalle[$num_elementos] . ' ",
                                                "cantidadArticulo": "' . $cantidad[$num_elementos] . '",
                                                "valorUnitario": "' . $q_ref[$num_elementos] . '",
                                                "unidadMedida": "Unidad",
                                                "valorDescuento": "' . $subtotaldes1[$num_elementos] . '",
                                                "tipoItem": "B",
                                                "impuestoAdicional": "0",
                                                "adicionalGrabable": "0",
                                                "impuestoMontoAdicional": "0"
                                            }';
                    } else {
                        $JsonDetalleFacturaIntegracion .= ',{
                                                "numeroLinea": "' . ($num_elementos + 1) . '",
                                                "codigoArticulo": "' . $Articulo["codigo"] . '",
                                                "nombreArticulo": "' . $Articulo["nombre"] . ' ' . $descripcion_detalle[$num_elementos] . ' ",
                                                "cantidadArticulo": "' . $cantidad[$num_elementos] . '",
                                                "valorUnitario": "' . $q_ref[$num_elementos] . '",
                                                "unidadMedida": "Unidad",
                                                "valorDescuento": "' . $subtotaldes1[$num_elementos] . '",
                                                "tipoItem": "B",
                                                "impuestoAdicional": "0",
                                                "adicionalGrabable": "0",
                                                "impuestoMontoAdicional": "0"                           
                                            }';
                    }

                    $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idingreso,idventa,idtraladosucursal,idtraladosucursal_entrada,iddevolucion,cantidad_compras,cantidad_ventas,cantidad_entrada,cantidad_devolucion,cantidad_salida,stock_inventario,fecha_horaCreacion,idarticulo,idusuario,idsucursal) 
                                    VALUES ('0','$idventanew','0','0','0','0','$totalcantidadpresentacion[$num_elementos]','0','0','0','$stockinven[$num_elementos]','$fechaHora','$idarticulo[$num_elementos]','$idusuario','" . $_SESSION["idsucursal"] . "')";
                    ejecutarConsulta($sql_detalleoperaciones);
                    //valida el descuento de la materia prima
                    $sqlVerificacionExistencia = "SELECT 
                                    p.idproducto,
                                    dp.cantidad as cantmateriaprima,
                                    dp.idarticulo as idarticulo_costo
                                    FROM produccion p 
                                    INNER JOIN detalle_produccion dp ON p.idproduccion=dp.idproduccion
                                    WHERE p.idproducto='$idarticulo[$num_elementos]' ";
                    $EXIS = ejecutarConsulta($sqlVerificacionExistencia);

                    $numexis = 0;

                    while ($reeeq = $EXIS->fetch_object()) {
                        $Tcan = $cantidad[$num_elementos] * $reeeq->cantmateriaprima;

                        $updateArticuloDetalle = "UPDATE articuloxsucursal SET stocksucursal=stocksucursal-$Tcan
                                        WHERE idarticulo='" . $reeeq->idarticulo_costo . "'   and idsucursal='" . $_SESSION["idsucursal"] . "' ";
                        ejecutarConsulta($updateArticuloDetalle);

                        $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idingreso,idventa,idtraladosucursal,idtraladosucursal_entrada,iddevolucion,cantidad_compras,cantidad_ventas,cantidad_entrada,cantidad_devolucion,cantidad_salida,stock_inventario,fecha_horaCreacion,idarticulo,idusuario,idsucursal) 
                                        VALUES ('0','$idventanew','0','0','0','0','$Tcan','0','0','0','$stockinven[$num_elementos]','$fechaHora','$idarticulo[$num_elementos]','$idusuario','" . $_SESSION["idsucursal"] . "')";
                        ejecutarConsulta($sql_detalleoperaciones);

                        $numexis++;
                    }
                    if ($numexis == 0) {
                    }
                    //fin de validacion del descuento de la materia prima                                                                                                 

                    $num_elementos = $num_elementos + 1;
                }


                $JsonIntegracionEcoFactura = str_replace("{DetalleFactura}", $JsonDetalleFacturaIntegracion, $JsonIntegracionEcoFactura);

                ///FIN DE DETALLE VENTA
            }
        }




        //API URL
      /*  if ($tipo_comprobante == "Factura" || $tipo_comprobante == "Cambiaria") {
            //URLS de Desarrollo

            if (CERTIFICADOR == "ECOFACTURAS") {
                if (PRUEBA_PRODUCCION == "PRUEBAS") {
                    $url = 'http://daocastro-001-site8.itempurl.com/api/EcoFactura/generarDocumento'; //url de pruebas
                } elseif (PRUEBA_PRODUCCION == "PRODUCCION") {

                    $url = 'http://api.fel.olintech.com/api/EcoFactura/generarDocumento'; //url de produccion
                }

                //////
                $resultado = $this->callAPI("POST", $url, $JsonIntegracionEcoFactura);
                $ArrayResultado = json_decode($resultado, true);

                try {
                    $sqlUpdate = "UPDATE venta SET autorizacionEcoFactura='" . $ArrayResultado["dte"]["numeroAutorizacion"] . "',serie_ecoFactura='" . $ArrayResultado["dte"]["serie"] . "',numero_ecoFactura='" . $ArrayResultado["dte"]["numero"] . "',fechaCertificacion_ecoFactura='" . $ArrayResultado["dte"]["fechaCertificacion"] . "' WHERE idventa='$idventanew'";
                    ejecutarConsulta($sqlUpdate);

                    $sqlCorre = "SELECT * FROM venta WHERE  idventa='$idventanew'";
                    $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
                    $fechaCertificacion_ecoFactura = $correlativo["fechaCertificacion_ecoFactura"];

                    if ($fechaCertificacion_ecoFactura == "" || $fechaCertificacion_ecoFactura == "0000-00-00 00:00:00") {
                        # code...
                        $sqlUpdatenovalidado = "UPDATE venta SET tipo_comprobante='Envio' WHERE idventa='$idventanew'";
                        ejecutarConsulta($sqlUpdatenovalidado);

                        $sqlLgs = "INSERT INTO logs (idventa,idusuario,idsucursal,JsonIntegracionEcoFactura,resultado,ArrayResultado)
                                    VALUES ('$idventanew','$idusuario','" . $_SESSION["idsucursal"] . "' ,'$JsonIntegracionEcoFactura','0','0')";
                        ejecutarConsulta($sqlLgs);
                    }
                } catch (\Throwable $th) {
                }
                //////



            } elseif ($certificador == "MEGAPRINT")
            {
                if ($pruebaproduccion == "PRUEBAS") {
                    $url = 'http://daocastro-001-site8.itempurl.com/api/Megaprint/generarDocumento'; //url de pruebas
                } elseif ($pruebaproduccion == "PRODUCCION") {

                    $url = 'http://api.fel.olintech.com/api/Megaprint/generarDocumento'; //url de produccion
                }

                ////
                $resultado = $this->callAPI("POST", $url, $JsonIntegracionEcoFactura);
                $ArrayResultado = json_decode($resultado, true);

                $sqlUpdate = "UPDATE venta SET autorizacionEcoFactura='" . $ArrayResultado["numeroAutorizacion"] . "',serie_ecoFactura='" . $ArrayResultado["serie"] . "',numero_ecoFactura='" . $ArrayResultado["numero"] . "',fechaCertificacion_ecoFactura='" . $ArrayResultado["fechaCertificacion"] . "',fecha_hora='" . $fechaTransaccion . "' WHERE idventa='$idventanew'";
                ejecutarConsulta($sqlUpdate);

                $sqlCorre = "SELECT * FROM venta WHERE  idventa='$idventanew'";
                $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
                $fechaCertificacion_ecoFactura = $correlativo["fechaCertificacion_ecoFactura"];

                if ($fechaCertificacion_ecoFactura == "" || $fechaCertificacion_ecoFactura == "0000-00-00 00:00:00") {
                    # code...
                    $sqlUpdatenovalidado = "UPDATE venta SET tipo_comprobante='Envio' WHERE idventa='$idventanew'";
                    ejecutarConsulta($sqlUpdatenovalidado);

                    $sqlLgs = "INSERT INTO logs (idventa,idusuario,idsucursal,JsonIntegracionEcoFactura,resultado,ArrayResultado)
                            VALUES ('$idventanew','$idusuario','" . $_SESSION["idsucursal"] . "' ,'$JsonIntegracionEcoFactura','$resultado','$ArrayResultado')";
                    ejecutarConsulta($sqlLgs);
                }
                /////               
            }
        }*/



        //return $idventanew;  

        return [
            'idventanew' => $idventanew,
            'tipo_comprobante' => $tipo_comprobante
        ];
    }





    function callAPI($method, $url, $data)
    {
        $curl = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30000);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-type: application/json',
            'Accept: application/json'
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // EXECUTE:
        $result = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        #echo "Status Code: ".$http_status;
        if (!$result) {
            die("Status Code" . $http_status . " Error:" . curl_error($curl) . " Connection Failure");
        }
        curl_close($curl);
        return $result;
    }
    //Implementamos un método para anular la venta 
    /*
    public function anular($idventa)
    {
        $sql = "UPDATE venta SET estado='Anulado' WHERE idventa='$idventa'";
        ejecutarConsulta($sql);

        $sqlOperacionesCompraVenta = "UPDATE operaciones_compras_ventas SET estado='Anulado' WHERE idventa='$idventa'";
        ejecutarConsulta($sqlOperacionesCompraVenta);



        $sqlDetalleventa = "SELECT * FROM detalle_venta WHERE idventa='$idventa'";
        $Detalle = ejecutarConsulta($sqlDetalleventa);


        while ($reg = $Detalle->fetch_object()) {
            $sqlPCcompra = "SELECT 
                                    asu.precio_compra as pc_compra, 
                                    asu.stocksucursal,
                                    a.tipo_producto
                                FROM articuloxsucursal asu
                                inner join articulo a on a.idarticulo=asu.idarticulo
                                WHERE asu.idarticulo=" . $reg->idarticulo . "   and asu.idsucursal='" . $_SESSION["idsucursal"] . "' ";
            $respc = ejecutarConsultaSimpleFila($sqlPCcompra);
            $pc_compra = $respc["pc_compra"];
            $tipoproducto = $respc["tipo_producto"];

            if ($tipoproducto == "Productos") {
                $updateArticuloDetalle = "UPDATE articuloxsucursal SET stocksucursal=stocksucursal+" . $reg->totalcantidadpresentacion . " WHERE idarticulo=" . $reg->idarticulo . " and  idsucursal='" . $_SESSION["idsucursal"] . "' ";
                ejecutarConsulta($updateArticuloDetalle);
            } else {
            }


            $sqlVerificacionExistencia = "SELECT 
            p.idproducto,
            dp.cantidad as cantmateriaprima, 
            dp.idarticulo as idarticulo_costo
            FROM produccion p 
            INNER JOIN detalle_produccion dp ON p.idproduccion=dp.idproduccion
            WHERE p.idproducto=" . $reg->idarticulo . " ";
            $EXIS = ejecutarConsulta($sqlVerificacionExistencia);

            $numexis = 0;

            while ($reeeq = $EXIS->fetch_object()) {

                $Tcan = $reg->totalcantidadpresentacion * $reeeq->cantmateriaprima;

                $updateArticuloDetalle = "UPDATE articuloxsucursal SET stocksucursal=stocksucursal+$Tcan
                    WHERE idarticulo='" . $reeeq->idarticulo_costo . "'   and idsucursal='" . $_SESSION["idsucursal"] . "' ";
                ejecutarConsulta($updateArticuloDetalle);
                $numexis++;
            }
            if ($numexis == 0) {
            }
        }

        $sqlArticulo="SELECT v.*,p.num_documento FROM venta v
        inner join persona p on p.idpersona=v.idcliente
        WHERE idventa='$idventa'";
        $Venta= ejecutarConsultaSimpleFila($sqlArticulo);


        if ($Venta["serie_ecoFactura"]<>" ") {
                    try {
                    if($Venta["tipo_comprobante"]=="Factura"){
                        $JsonAnulacionFactrua='{

                            "cliente":"'._CLIENTE_.'",
                            "usuario":"'._USUARIO_.'",
                            "clave":"'._PASS_.'",
                            "nit":"'._NIT_.'", 
                            "serie": "'.$Venta["serie_ecoFactura"].'",
                            "preImpreso": "'.$Venta["numero_ecoFactura"].'",  
                            "nitComprador":"'.$Venta["num_documento"].'",   
                            "fechaAnulacion": "'.date("Ymd",strtotime($Venta["fecha_hora"])).'",     
                            "motivoAnulacion":"ANULACION DE DOCUMENTO"                   
                        }';
                        //URL de desarrollo
                        //$url = 'http://daocastro-001-site8.itempurl.com/api/GuateFactura/anularDocumento';
                        $url="http://api.fel.olintech.com/api/GuateFactura/anularDocumento";
                        $resultado=$this->callAPI("POST", $url, $JsonAnulacionFactrua);
                        //echo $resultado;
                        $ArrayResultado=json_decode($resultado, true);
                        //print_r($JsonAnulacionFactrua);
                        //print_r($resultado);
                        //print_r($ArrayResultado);
                    }
                } catch (\Throwable $th) {
                    #echo $th;
                } 
            # code...
        }

        return ($sql);
    }
    */

    public function anular($idventa)
    {
        // 1. Anular el estado de la venta
        $sql = "UPDATE venta SET estado='Anulado' WHERE idventa='$idventa'";
        ejecutarConsulta($sql);

        // 2. Anular las operaciones de compra/venta
        $sqlOperacionesCompraVenta = "UPDATE operaciones_compras_ventas SET estado='Anulado' WHERE idventa='$idventa'";
        ejecutarConsulta($sqlOperacionesCompraVenta);

        // 3. Devolver productos de detalle_venta al stock
        $sqlDetalleventa = "SELECT * FROM detalle_venta WHERE idventa='$idventa'";
        $Detalle = ejecutarConsulta($sqlDetalleventa);

        while ($reg = $Detalle->fetch_object()) {
            $updateArticuloDetalle = "UPDATE articuloxsucursal 
                                    SET stocksucursal = stocksucursal + $reg->totalcantidadpresentacion 
                                    WHERE idarticulo = $reg->idarticulo 
                                    AND idsucursal = '" . $_SESSION["idsucursal"] . "'";
            ejecutarConsulta($updateArticuloDetalle);
        }

        // 4. Devolver productos extra de detalle_ventaExtra al stock
        $sqlDetalleExtra = "SELECT * FROM detalle_ventaExtra WHERE idventa='$idventa'";
        $DetalleExtra = ejecutarConsulta($sqlDetalleExtra);

        while ($regExtra = $DetalleExtra->fetch_object()) {
            $updateArticuloDetalle = "UPDATE articuloxsucursal 
                                    SET stocksucursal = stocksucursal + $regExtra->cantidad 
                                    WHERE idarticulo = $regExtra->idarticulo 
                                    AND idsucursal = '" . $_SESSION["idsucursal"] . "'";
            ejecutarConsulta($updateArticuloDetalle);
        }

        // 5. Verificar si hay factura electrónica para anular en FEL
        $sqlArticulo = "SELECT v.*, p.num_documento 
                        FROM venta v
                        INNER JOIN persona p ON p.idpersona = v.idcliente
                        WHERE idventa = '$idventa'";
        $Venta = ejecutarConsultaSimpleFila($sqlArticulo);

        if (trim($Venta["serie_ecoFactura"]) !== "") {
            try {
                if ($Venta["tipo_comprobante"] == "Factura") {
                    $JsonAnulacionFactrua = '{
                        "cliente": "' . _CLIENTE_ . '",
                        "usuario": "' . _USUARIO_ . '",
                        "clave": "' . _PASS_ . '",
                        "nit": "' . _NIT_ . '",
                        "serie": "' . $Venta["serie_ecoFactura"] . '",
                        "preImpreso": "' . $Venta["numero_ecoFactura"] . '",
                        "nitComprador": "' . $Venta["num_documento"] . '",
                        "fechaAnulacion": "' . date("Ymd", strtotime($Venta["fecha_hora"])) . '",
                        "motivoAnulacion": "ANULACION DE DOCUMENTO"
                    }';

                    $url = "http://api.fel.olintech.com/api/GuateFactura/anularDocumento";
                    $resultado = $this->callAPI("POST", $url, $JsonAnulacionFactrua);
                    $ArrayResultado = json_decode($resultado, true);
                }
            } catch (\Throwable $th) {
                // Puedes loguear el error si es necesario
            }
        }

        return true;
    }



    //Implementamos un método para anular la venta 
    public function guardarGastoaVenta($TotalEfectivoDisponible_GastoaVenta,
    $totalAcumuladoGasots_GastoaVenta,$disponibleparaGastos_GastoaVenta,
    $serie_no_GastoaVenta,$factura_no_GastoaVenta,$tipo_factura_GastoaVenta,
    $tipo_comprobante_GastoaVenta,$idcliente_GastoaVenta,$nit_no_GastoaVenta,
    $tipo_documento_cliente_GastoaVenta,$nombreproveedor_GastoaVenta,
    $direccion__GastoaVenta,$concepto_fac_GastoaVenta,$fecha_hora_GastoaVenta,
    $valor_q_GastoaVenta,$tipo_compra_GastoaVenta,$tipo_combustible_GastoaVenta,
    $num_galonaje_GastoaVenta)
    {
        // Extraer día, mes y año
        $fecha_dia = date("d", strtotime($fecha_hora_GastoaVenta));
        $fecha_mes = date("m", strtotime($fecha_hora_GastoaVenta));
        $fecha_year = date("Y", strtotime($fecha_hora_GastoaVenta));

        // Arreglo de meses en español
        $meses = [
            '1' => 'Enero',
            '2' => 'Febrero',
            '3' => 'Marzo',
            '4' => 'Abril',
            '5' => 'Mayo',
            '6' => 'Junio',
            '7' => 'Julio',
            '8' => 'Agosto',
            '9' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        ];

        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');
        /////CAPTURA DE CLIENTE NUEVO Y UPDATE
        if ($idcliente_GastoaVenta == '0') {
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
        VALUES ('Cliente',
        '$nombreproveedor_GastoaVenta',
        '$tipo_documento_cliente_GastoaVenta',
        '$nit_no_GastoaVenta',
        '$direccion__GastoaVenta',
        '0',
        'sincorreo@correo.com',
        'PUBLICO',
        '$codigo_cliente','$fechaHora')";
            $residcliente = ejecutarConsulta_retornarID($sqlcliente);

            if (!$residcliente) {
                throw new Exception("Error al insertar nuevo cliente.");
            }
        } else {
            $sqlCorre = "SELECT * FROM persona WHERE idpersona='$idcliente_GastoaVenta'";
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

                $sqlupdadtepersona = "UPDATE persona SET codigo_cliente='$codigo_clientes' WHERE idpersona='$idcliente_GastoaVenta'";
                ejecutarConsulta($sqlupdadtepersona);
            }

            $sqlcorrelativo = "UPDATE persona SET 
        direccion='$direccion__GastoaVenta',
        telefono='0',
        email='sincorreo@gmail.com',
        tipo_documento='$tipo_documento_cliente_GastoaVenta',
        nombre='$nombreproveedor_GastoaVenta'
        WHERE idpersona='$idcliente_GastoaVenta'";

            ejecutarConsulta($sqlcorrelativo);
            $residcliente = $idcliente_GastoaVenta;
        }
        ///////       

        // Obtener mes en letras
        $mes = $meses[intval($fecha_mes)];
        
        $sql = "INSERT INTO compras (idusuario,
                    idsucursal,
                    serie_no,
                    factura_no,
                    mes_a_contabilizar,
                    ano_contabilizar,
                    tipo_factura,
                    tipo_factura2,
                    idpersona,
                    fecha_hora,
                    valor_q,
                    tipo_compra,
                    condicion,
                    tipo_operacion_banco,
                    num_operacion_bac,
                    tipo_combustible,
                    num_galonaje,
                    concepto_fac,
                    fecha_creacion,
                    TotalEfectivoDisponible_GastoaVenta,
                    totalAcumuladoGasots_GastoaVenta,
                    disponibleparaGastos_GastoaVenta)
                    VALUES ('" . $_SESSION["idusuario"] . "',
                    '" . $_SESSION["idsucursal"] . "',
                    '$serie_no_GastoaVenta',
                    '$factura_no_GastoaVenta',
                    '$mes',
                    '$fecha_year',
                    '$tipo_factura_GastoaVenta',
                    '$tipo_comprobante_GastoaVenta',
                    '$residcliente',
                    '$fecha_hora_GastoaVenta',
                    '$valor_q_GastoaVenta',
                    '$tipo_compra_GastoaVenta',
                    '1',
                    'Efectivo',
                    '0',
                    '$tipo_combustible_GastoaVenta',
                    '$num_galonaje_GastoaVenta',
                    '$concepto_fac_GastoaVenta',
                    '$fechaHora',
                    '$TotalEfectivoDisponible_GastoaVenta',
                    '$totalAcumuladoGasots_GastoaVenta',
                    '$disponibleparaGastos_GastoaVenta')";
                        ejecutarConsulta($sql);

        return ($sql);
    }


    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idventa)
    {
        $sql = "SELECT
    v.idventa,
    DATE(v.fecha_hora) as fecha,
    v.idcliente,
    p.nombre as cliente,
    u.idusuario,
    u.nombre as usuario,
    v.tipo_comprobante, 
    v.serie_comprobante,
    v.num_comprobante,
    v.total_venta, 
    v.impuesto,
    v.estado,
    v.forma_pago,
    DATE(v.fecha_hora_siguiente_pago) as fechahorasiguientepago,
    v.observacion_credito
    FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idventa='$idventa'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalle($idventa)
    {
        $sql = "SELECT dv.idventa,dv.idarticulo,a.nombre,dv.cantidad,dv.precio_venta,
    dv.descuento,
    ROUND((dv.cantidad*(dv.precio_venta-((dv.precio_venta*dv.descuento)/100))),2) as subtotal FROM detalle_venta dv inner join articulo a on dv.idarticulo=a.idarticulo where dv.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros
    public function listar($fecha_inicio_reporte, $fecha_fin_reporte)
    {
        $sql = "SELECT 
            v.idventa,
            DATE(v.fecha_hora) as fecha,
            v.idcliente,
            v.numero_deposito_transferencia,
            p.nombre as cliente,
            u.idusuario,
            u.nombre as usuario,
            v.tipo_comprobante,
            v.serie_comprobante,
            v.num_comprobante,
            v.total_venta,
            v.total_ventades,
            v.impuesto,  
            v.estado,
            v.cefectivo,
            v.rescambio,
            v.forma_pago,
            v.tipo_pagoBacVisaNet,
            v.opcionesAdicionales,
            ROUND(v.valor_tarjeta, 2) AS valor_tarjeta,  -- Redondeamos valor_tarjeta a 2 decimales
            v.ctarjeta,
            v.ccredito,
            v.ctransferencia,
            v.nombre_vendedor,
            v.autorizacionEcoFactura, 
            v.serie_ecoFactura,
            v.numero_ecoFactura, 
            v.fechaCertificacion_ecoFactura,
            v.tipo_entrega,
                COALESCE(vv.nombre, 'Sin operación') AS nom_venedor, -- Reemplaza NULL con 'Sin operación'
                COALESCE(c.idcotizacion, 'Sin operación') AS idcotizacion -- Reemplaza NULL con 'Sin operación'
            FROM venta v 
                INNER JOIN persona p ON v.idcliente=p.idpersona 
                INNER JOIN usuario u ON v.idusuario=u.idusuario
                LEFT JOIN vendedor vv ON vv.idvendedor=v.idvendedor
                LEFT JOIN cotizacion c ON c.idventa=v.idventa
            where u.idusuario='" . $_SESSION["idusuario"] . "' and  DATE(v.fecha_hora)>='$fecha_inicio_reporte' AND DATE(v.fecha_hora)<='$fecha_fin_reporte'
            order by  v.idventa DESC   ";
        return ejecutarConsulta($sql);
    }

    public function listarRestaurante($fecha_inicio_reporte, $fecha_fin_reporte)
    {
        $sql = "SELECT 
            v.idventa,
            DATE(v.fecha_hora) as fecha,
            v.idcliente,
            p.nombre as cliente,
            u.idusuario,
            u.nombre as usuario,
            v.tipo_comprobante,
            v.serie_comprobante,
            v.num_comprobante,
            v.total_venta,
            v.total_ventades,
            v.impuesto,  
            v.estado,
            v.cefectivo,
            v.rescambio,
            v.forma_pago,
            v.tipo_pagoBacVisaNet,
            v.opcionesAdicionales,
            ROUND(v.valor_tarjeta, 2) AS valor_tarjeta,  -- Redondeamos valor_tarjeta a 2 decimales
            v.ctarjeta,
            v.ccredito,
            v.ctransferencia,
            v.nombre_vendedor,
            v.autorizacionEcoFactura, 
            v.serie_ecoFactura,
            v.numero_ecoFactura, 
            v.fechaCertificacion_ecoFactura,
            v.tipo_entrega,
            COALESCE(vv.nombre, 'Sin operación') AS nom_venedor, -- Reemplaza NULL con 'Sin operación'
            COALESCE(c.idcotizacion, 'Sin operación') AS idcotizacion, -- Reemplaza NULL con 'Sin operación'
            (SELECT m.nombre FROM mesa m WHERE m.idmesa=ad.idmesa LIMIT 1) AS nombre_mesa,
            (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=ad.idusuario) AS usuario_mesero,
            ad.id_add_orden
            FROM venta v 
                INNER JOIN persona p ON v.idcliente=p.idpersona 
                INNER JOIN usuario u ON v.idusuario=u.idusuario
                LEFT JOIN vendedor vv ON vv.idvendedor=v.idvendedor
                LEFT JOIN cotizacion c ON c.idventa=v.idventa
                LEFT JOIN add_orden ad ON ad.idventa=v.idventa
            where u.idusuario='" . $_SESSION["idusuario"] . "' and  DATE(v.fecha_hora)>='$fecha_inicio_reporte' AND DATE(v.fecha_hora)<='$fecha_fin_reporte'
            order by  v.idventa DESC   ";
        return ejecutarConsulta($sql);
    }



    public function listarNC($fecha_inicio_reporte, $fecha_fin_reporte)
    {
        $sql = "SELECT 
                v.idnota_credito,
                p.nombre as cliente,
                u.nombre AS usuarioNc,
                v.tipo_comprobante,
                v.num_comprobante,
                v.total_venta,
                v.total_ventades,
                v.forma_pago,   
                v.fecha_hora_nc,   
                v.fechaCertificacion_ecoFactura,
                v.autorizacionEcoFactura, 
                v.serie_ecoFactura,
                v.numero_ecoFactura,                                                                     
                v.idventa,
                DATE(v.fecha_hora) as fechaVenta,
                (SELECT u1.nombre FROM usuario u1 
                WHERE u1.idusuario=vv.idusuario LIMIT 1 ) AS usuarioVenta,                
                vv.fechaCertificacion_ecoFactura AS fechaCertificacion_ecoFactura_venta,
                v.autorizacionEcoFactura_venta,
                v.serie_comprobante_venta,
                v.numero_ecoFactura_venta,                
                v.estado,
                v.motivo_nc
                FROM nota_credito v 
                INNER JOIN venta vv ON v.idnota_credito=vv.idnota_credito
                INNER JOIN persona p ON v.idcliente=p.idpersona 
                INNER JOIN usuario u ON v.idusuario=u.idusuario
                where u.idusuario='" . $_SESSION["idusuario"] . "' and  
                DATE(v.fecha_hora_nc)>='$fecha_inicio_reporte' 
                AND DATE(v.fecha_hora_nc)<='$fecha_fin_reporte'
                order by  v.idnota_credito DESC    ";
        return ejecutarConsulta($sql);
    }


    public function listarVentasCierre($idcuadre_caja)
    {
        date_default_timezone_set('America/Guatemala');
        $sql = "SELECT 
                v.idventa,
                DATE(v.fecha_hora) AS fecha,
                v.idcliente,
                p.nombre AS cliente,
                u.idusuario,
                u.nombre AS usuario, 
                v.tipo_comprobante,
                COALESCE(NULLIF(v.serie_comprobante, ''), 0) AS serie_comprobante,
                COALESCE(NULLIF(v.num_comprobante, ''), 0) AS num_comprobante,
                COALESCE(NULLIF(v.total_venta, ''), 0) AS total_venta,
                COALESCE(NULLIF(v.total_ventades, ''), 0) AS total_ventades,
                COALESCE(NULLIF(v.impuesto, ''), 0) AS impuesto,
                v.estado,
                COALESCE(NULLIF(v.cefectivo, ''), 0) AS cefectivo,
                COALESCE(NULLIF(v.rescambio, ''), 0) AS rescambio,  
                v.forma_pago,
                v.nombre_vendedor,
                COALESCE(NULLIF(v.serie_ecoFactura, ''), 0) AS autorizacionEcoFactura,
                COALESCE(NULLIF(v.serie_ecoFactura, ''), 0) AS serie_ecoFactura,
                COALESCE(NULLIF(v.numero_ecoFactura, ''), 0) AS numero_ecoFactura,
                COALESCE(NULLIF(v.fechaCertificacion_ecoFactura, ''), 0) AS fechaCertificacion_ecoFactura
                FROM venta v 
                INNER JOIN persona p ON v.idcliente = p.idpersona 
                INNER JOIN usuario u ON v.idusuario = u.idusuario
                WHERE v.idcuadre_caja = '$idcuadre_caja'
                AND v.tipo_operacion = 'CIERRE'  ";
        return ejecutarConsulta($sql);
    }


    public function ventacabecera($idventa)
    {
        $sql = "SELECT 
                v.idventa,
                v.idcliente,
                p.nombre as cliente,
                p.direccion,
                p.tipo_documento,
                p.num_documento,
                p.email,p.telefono,v.idusuario,u.nombre as usuario,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,date(v.fecha_hora) as fecha,v.impuesto,v.total_venta, v.total_ventades FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=U.idusuario WHERE v.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }

    public function ventadetalle($idventa)
    {
        $sql = "SELECT 
                a.nombre as articulo,
                a.codigo,
                d.cantidad,
                d.precio_venta,
                d.descuento,
                d.descripcion_detalle,
                ROUND((d.cantidad*(d.precio_venta-((d.precio_venta*d.descuento)/100))),2) as subtotal,
                ((d.precio_venta*d.descuento)/100) as total_descuento FROM detalle_venta d INNER JOIN articulo a ON d.idarticulo=a.idarticulo  WHERE d.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }

    public function ventadetalletotalpeso($idventa)
    {
        $sql = "SELECT SUM(d.cantidad*a.peso_producto) as peso FROM detalle_venta d INNER JOIN articulo a ON d.idarticulo=a.idarticulo WHERE d.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }

    public function listar_despacho($fecha_inicio_reporte, $fecha_fin_reporte, $tipo_entrega)
    {
        $sql = "SELECT 
                v.idventa,
                DATE(v.fecha_hora) as fecha,
                v.idcliente,
                p.nombre as cliente,
                u.idusuario,
                u.nombre as usuario,
                v.tipo_comprobante,
                v.serie_comprobante,
                v.num_comprobante,
                v.total_venta,
                v.total_ventades,
                v.impuesto,  
                v.estado,
                v.cefectivo,
                v.rescambio,
                v.forma_pago,
                v.tipo_pagoBacVisaNet,
                v.opcionesAdicionales,
                ROUND(v.valor_tarjeta, 2) AS valor_tarjeta,
                v.ctarjeta,
                v.ccredito,
                v.ctransferencia,
                v.nombre_vendedor,
                v.autorizacionEcoFactura, 
                v.serie_ecoFactura,
                v.numero_ecoFactura,
                v.fechaCertificacion_ecoFactura,
                v.despachosino,
                v.estado_venta,
                v.tipo_entrega,
                v.guia_transporte,
                CASE 
                    WHEN mc.comentario_mensajero IS NULL OR mc.comentario_mensajero = '' 
                    THEN 'SIN COMENTARIOS' 
                    ELSE mc.comentario_mensajero 
                END AS comentario_mensajero
                FROM venta v 
                INNER JOIN persona p ON v.idcliente=p.idpersona 
                INNER JOIN usuario u ON v.idusuario=u.idusuario
                LEFT JOIN mensajero_comentarios mc ON mc.idventa = v.idventa
                where  DATE(v.fecha_hora)>='$fecha_inicio_reporte' 
                        AND DATE(v.fecha_hora)<='$fecha_fin_reporte' 
                        and v.estado='Aceptado'
                        and ('*' = '$tipo_entrega' OR v.tipo_entrega = '$tipo_entrega')
                order by  v.idventa DESC   ";
        return ejecutarConsulta($sql);
    }

    public function cambiarestadodespachoVenta($idventa)
    {
        $sql = "UPDATE venta SET estado_venta='COMPLETO' WHERE idventa='$idventa'";
        return ejecutarConsulta($sql);
    }


    public function cambiarestadodespacho($idventa)
    {
        $sql = "UPDATE venta SET despachosino='SI' WHERE idventa='$idventa'";
        return ejecutarConsulta($sql);
    }

    public function ventacabecera2($idventa)
    {
        $sql = "SELECT 
                v.idventa,
                v.idcliente,
                p.nombre as cliente,
                p.direccion,
                p.tipo_documento,
                p.num_documento,
                p.email,p.telefono,v.idusuario,u.nombre as usuario,
                v.tipo_comprobante,
                v.serie_comprobante,
                v.num_comprobante,
                date(v.fecha_hora) as fecha,
                v.impuesto,v.total_venta, 
                v.total_ventades ,
                s.idsucursal,
                s.nombre as sucursal_nombre, 
                s.direccion as sucursal_direccion,
                s.direccion_fiscal,
                s.telefono as sucursal_telefono,
                s.nit as sucursal_nit,
                s.email as sucursal_email,
                s.imagen as sucursal_imagen,
                s.condicion as sucursal_condicion,
                s.nombre_comercial,
                s.nombre_fel
                FROM venta v 
                INNER JOIN persona p ON v.idcliente=p.idpersona 
                INNER JOIN sucursal s ON s.idsucursal=v.idsucursal
                INNER JOIN usuario u ON v.idusuario=u.idusuario
                WHERE v.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }


    public function listarArticulosExtras($idarticulo){
        $sql="SELECT
                a.nombre as articulo_extra,        
                d.idarticulo as idarticulo_extra,   
                p.idproducto,                      
                d.cantidad as cantidad_extra,       
                d.tipo_item,                       
                d.precio_venta                     
              FROM detalle_produccion d 
              INNER JOIN articulo a ON a.idarticulo=d.idarticulo
              INNER JOIN produccion p ON p.idproduccion=d.idproduccion
              WHERE p.idproducto='".$idarticulo."'
              AND d.tipo_item IN ('Extra', 'Topping')";
              //print_r($sql);
        return ejecutarConsulta($sql);
    }

    public function listarExtrasSeleccionados($idcotizacion) {
        //$sql = "SELECT idarticulo FROM detalle_cotizacionExtra WHERE idcotizacion = '$idcotizacion'";
        $sql = "SELECT idarticulo FROM detalle_cotizacion WHERE idcotizacion = '$idcotizacion' AND idarticulopadre > 0";
        return ejecutarConsulta($sql);
    }

}
