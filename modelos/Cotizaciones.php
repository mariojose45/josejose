<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once __DIR__ . "/../config/Conexion.php";


class Cotizaciones
{
    //Implementamos nuestro constructor  
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar(
        $idcliente,
        $codigo_cliente,
        $nit,
        $nombre_cliente,
        $telefono_cliente,
        $direccion_cliente,
        $correo_cliente,
        $valor_tarjeta,
        $tipo_documento_cliente,
        $idusuario,
        $fecha_hora,
        $forma_pago,
        $tipo_comprobante,
        $tipo_pagoBacVisaNet,
        $opcionesAdicionales,
        $total_venta,
        $total_ventades,
        $idvendedor,
        $tipo_cliente,
        $forma_productos,
        $comentario_cotizacion,
        $destino,
        $datosArticulos
    ) {

        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');
        /////CAPTURA DE CLIENTE NUEVO Y UPDATE
        if ($idcliente == '0') {
            $sqlcorrelativo = "UPDATE add_correlativo SET 
                                    codigo_cliente=codigo_cliente+1 
                                    WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
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
                                                    '$nombre_cliente',
                                                    '$tipo_documento_cliente',
                                                    '$nit',
                                                    '$direccion_cliente',
                                                    '$telefono_cliente',
                                                    '$correo_cliente',
                                                    '$tipo_cliente',
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
                                            nombre='$nombre_cliente',
                                            tipo_cliente='$tipo_cliente'
                                    WHERE idpersona='$idcliente'";

            ejecutarConsulta($sqlcorrelativo);
            $residcliente = $idcliente;
        }
        ///////           

        $sqlcorrelativo = "UPDATE add_correlativo SET num_cotizacion=num_cotizacion+1 WHERE idsucursal='" . $_SESSION["idsucursal"] . "'";
        ejecutarConsulta($sqlcorrelativo);

        $sqlCorre = "SELECT * FROM add_correlativo WHERE idsucursal='" . $_SESSION["idsucursal"] . "'";
        $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
        $corre = $correlativo["num_cotizacion"];

        $sql = "INSERT INTO cotizacion (idcliente,
                                    idusuario,
                                    fecha_hora,
                                    total_venta,
                                    total_ventades,
                                    estado,
                                    tipo_comprobante,
                                    num_comprobante,
                                    fechaCreacion,
                                    idsucursal,
                                    forma_pago,
                                    valor_tarjeta,
                                    tipo_pagoBacVisaNet,
                                    opcionesAdicionales,
                                    idvendedor,
                                    forma_productos,
                                    comentario_cotizacion,
                                    destino)
                            VALUES ('$residcliente',
                                    '$idusuario',
                                    '$fecha_hora',
                                    '$total_venta',
                                    '$total_ventades',
                                    'Aceptado',
                                    'Cotizacion',
                                    '$corre',
                                    '$fechaHora',
                                    '" . $_SESSION["idsucursal"] . "',
                                    '$forma_pago',
                                    '$valor_tarjeta',
                                    '$tipo_pagoBacVisaNet',
                                    '$opcionesAdicionales',
                                    '$idvendedor',
                                    '$forma_productos',
                                    '$comentario_cotizacion',
                                    '$destino'
                                    )";

        $idcotizacionnew = ejecutarConsulta_retornarID($sql);

        $num_elementos = 0;
        $sw = true;


        if ($idcotizacionnew) {
            $articulos = $datosArticulos['articulos'];
            $numArticulos = count($articulos['idarticulo']);
            $sw = true;

            // ===== PRIMERA PARTE: insertar los artículos principales =====
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

                // Insertar artículo principal
                $sql_detalle = "INSERT INTO detalle_cotizacion (
                    idcotizacion, 
                    idarticulo, 
                    cantidad, 
                    precio_venta, 
                    descuento, 
                    descripcion_detalle,
                    stockinven, 
                    cantidadpresentacion, 
                    totalcantidadpresentacion, 
                    presen, 
                    precio_ventaSistema, 
                    precio_ventaSistema2, 
                    q_ref, 
                    precio_recargoPV, 
                    precio_recargoQRef, 
                    subtotal1, 
                    subtotaldes1
                ) VALUES (
                    '$idcotizacionnew', '$idarticulo', '$cantidad', '$precio_venta', '$descuento_porcentaje', 
                    '$descripcion_detalle', '$stockinven', '$cantidadpresentacion', '$totalcantidadpresentacion', 
                    '$presen', '$precio_ventaSistema', '$precio_ventaSistema2', '$q_ref', '$precio_recargoPV', 
                    '$precio_recargoQRef', '$subtotal1', '$subtotaldes1'
                )";
                ejecutarConsulta($sql_detalle) or $sw = false;
            }

            // ===== SEGUNDA PARTE: procesar los extras y toppings globales =====
            if (isset($articulos['idarticuloExtra_extras']) && is_array($articulos['idarticuloExtra_extras'])) {

                $extras = $articulos['idarticuloExtra_extras'];
                $tipos = $articulos['tipo_item_extras'] ?? [];
                $cantidades = $articulos['cantidad_extra'] ?? [];
                $productos = $articulos['idproducto_extra'] ?? [];
                $checks = $articulos['check_extras'] ?? [];

                $n = count($extras);
                for ($j = 0; $j < $n; $j++) {

                    $tipo = $tipos[$j] ?? 'Topping';
                    $idextra = (int) $extras[$j];
                    $cantidad_extra = (float) ($cantidades[$j] ?? 0);
                    $idproducto = (int) ($productos[$j] ?? 0);

                    // 🔹 Ignorar si no pertenece a ningún artículo o está desmarcado
                    if ($idextra <= 0 || $cantidad_extra <= 0 || $idproducto <= 0)
                        continue;
                    if (isset($checks[$j]) && !$checks[$j])
                        continue;

                    /* $sqlVerificacionExistencia="SELECT 
                     p.idproducto,
                     dp.cantidad as cantmateriaprima,
                     dp.idarticulo as idarticulo_costo
                     FROM produccion p 
                     INNER JOIN detalle_produccion dp ON p.idproduccion=dp.idproduccion
                     WHERE p.idproducto='$idextra' and  and dp.tipo_item='Producto'";
                     $EXIS=ejecutarConsulta($sqlVerificacionExistencia);   */


                    $total_a_descontar = $cantidad_extra * $totalcantidadpresentacion;

                    // 🔹 Precio del extra (solo si aplica)
                    $sqlPrecio = "SELECT *
                                  FROM articuloxsucursal 
                                  WHERE idarticulo = '$idextra' 
                                  AND idsucursal = '" . $_SESSION["idsucursal"] . "' LIMIT 1";
                    $resPrecio = ejecutarConsultaSimpleFila($sqlPrecio) ?: [];
                    $precio_venta_extra = (float) ($resPrecio['precio_unidad'] ?? 0);
                    $precio_compra_extra = (float) ($resPrecio['precio_compra'] ?? 0);
                    $stocksucursal_extra = (float) ($resPrecio['stocksucursal'] ?? 0);
                    $subtotaleExtra = $tipo === 'Extra' ? ($total_a_descontar * $precio_venta_extra) : 0;

                    $ressubtotal1 = $tipo === 'Extra' ? ($total_a_descontar * $precio_venta_extra) : 0;

                    // 🔹 Insertar en detalle_cotizacion sin duplicar
                    $sql_extra = "INSERT INTO detalle_cotizacion(
                        idcotizacion, idarticulo, cantidad, 
                        precio_venta, descuento, descripcion_detalle,presen,
                    stockinven, cantidadpresentacion, totalcantidadpresentacion,
                    precio_ventaSistema, precio_ventaSistema2, q_ref, precio_recargoPV, 
                    precio_recargoQRef, subtotal1, subtotaldes1, checks,tipo,idarticulopadre
                    ) VALUES (
                        '$idcotizacionnew', '$idextra', '$cantidad_extra', 
                        '$precio_venta_extra','0','.','UNIDAD', 
                         '$stocksucursal_extra', '1','$total_a_descontar',
                         '$precio_venta_extra','$precio_venta_extra','$precio_venta_extra','$precio_venta_extra',
                         '$precio_venta_extra', '$ressubtotal1', '0', '1', '$tipo', '$idproducto'
                    )";
                    ejecutarConsulta($sql_extra) or $sw = false;
                }
            }
        }



        return $idcotizacionnew;
    }


    public function insertarApi(
        $codigo_cliente,
        $nit,
        $nombre_cliente,
        $telefono_cliente,
        $direccion_cliente,
        $correo_cliente,
        $valor_tarjeta,
        $tipo_documento_cliente,
        $fecha_hora,
        $forma_pago,
        $tipo_comprobante,
        $tipo_pagoBacVisaNet,
        $opcionesAdicionales,
        $total_venta,
        $total_ventades,
        $idvendedor,
        $tipo_cliente,
        $forma_productos,
        $comentario_cotizacion,
        $destino,
        $datosArticulos
    ) {

        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');
        /////CAPTURA DE CLIENTE NUEVO Y UPDATE

        $sqlBuscarCliente = "
        SELECT idpersona 
        FROM persona 
        WHERE tipo_persona = 'Cliente'
        AND (
            num_documento = '$nit'
            OR telefono = '$telefono_cliente'
            OR nombre = '$nombre_cliente'
        )
        LIMIT 1";
        $clienteExiste = ejecutarConsultaSimpleFila($sqlBuscarCliente);

        if ($clienteExiste) {

            $idpersona = $clienteExiste["idpersona"];

            $sqlUpdateCliente = "
                UPDATE persona SET
                    nombre = '$nombre_cliente',
                    tipo_documento = '$tipo_documento_cliente',
                    direccion = '$direccion_cliente',
                    telefono = '$telefono_cliente',
                    email = '$correo_cliente',
                    tipo_cliente = '$tipo_cliente'
                WHERE idpersona = '$idpersona'
            ";

            ejecutarConsulta($sqlUpdateCliente);

        } else {

            // 1. Aumentar correlativo
            $sqlcorrelativo = "
                UPDATE add_correlativo 
                SET codigo_cliente = codigo_cliente + 1 
                WHERE idsucursal = '4'
            ";
            ejecutarConsulta($sqlcorrelativo);

            // 2. Obtener correlativo
            $sqlCorre = "
                SELECT codigo_cliente 
                FROM add_correlativo 
                WHERE idsucursal = '4'
            ";
            $correlativo = ejecutarConsultaSimpleFila($sqlCorre);

            $codigo_cliente = 'COD' . $correlativo["codigo_cliente"];

            // 3. Insertar cliente
            $sqlcliente = "
                INSERT INTO persona (
                    tipo_persona,
                    nombre,
                    tipo_documento,
                    num_documento,
                    direccion,
                    telefono,
                    email,
                    tipo_cliente,
                    codigo_cliente,
                    fechaCreacion
                ) VALUES (
                    'Cliente',
                    '$nombre_cliente',
                    '$tipo_documento_cliente',
                    '$nit',
                    '$direccion_cliente',
                    '$telefono_cliente',
                    '$correo_cliente',
                    '$tipo_cliente',
                    '$codigo_cliente',
                    '$fechaHora'
                )
            ";

            $idpersona = ejecutarConsulta_retornarID($sqlcliente);

            if (!$idpersona) {
                throw new Exception("Error al insertar nuevo cliente.");
            }
        }


        ///////           

        $sqlcorrelativo = "UPDATE add_correlativo SET num_cotizacion=num_cotizacion+1 WHERE idsucursal='4'";
        ejecutarConsulta($sqlcorrelativo);

        $sqlCorre = "SELECT * FROM add_correlativo WHERE idsucursal='4'";
        $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
        $corre = $correlativo["num_cotizacion"];

        $sql = "INSERT INTO cotizacion (idcliente,idusuario,fecha_hora,total_venta,total_ventades,
                                    estado,tipo_comprobante,num_comprobante,fechaCreacion,idsucursal,forma_pago,
                                    valor_tarjeta,tipo_pagoBacVisaNet,opcionesAdicionales,idvendedor,forma_productos,
                                    comentario_cotizacion,destino)
                            VALUES ('$idpersona','34','$fecha_hora','$total_venta','$total_ventades','Aceptado','Cotizacion Tienda','$corre',
                            '$fechaHora','4','$forma_pago','$valor_tarjeta','$tipo_pagoBacVisaNet','$opcionesAdicionales','$idvendedor',
                            '$forma_productos','$comentario_cotizacion','$destino')";

        $idcotizacionnew = ejecutarConsulta_retornarID($sql);

        $num_elementos = 0;
        $sw = true;


        if ($idcotizacionnew) {
            $articulos = $datosArticulos['articulos'];
            $numArticulos = count($articulos['idarticulo']);
            $sw = true;

            // ===== PRIMERA PARTE: insertar los artículos principales =====
            for ($i = 0; $i < $numArticulos; $i++) {
                $idarticulo = $articulos['idarticulo'][$i];

                $sqlArt = "SELECT * FROM articuloxsucursal WHERE idsucursal='4' and idarticulo='$idarticulo'";
                $resp = ejecutarConsultaSimpleFila($sqlArt);
                $precio_compra = $resp["precio_compra"];
                $stockinven = $resp["stocksucursal"];
                $cantidadpresentacion = 1;
                $cantidad = $articulos['cantidad'][$i];
                $totalcantidadpresentacion = $articulos['cantidad'][$i];
                $presen = 'UNIDAD';
                $precio_ventaSistema = $articulos['precio_venta'][$i];
                $precio_ventaSistema2 = $articulos['precio_venta'][$i];
                $q_ref = $articulos['precio_venta'][$i];
                $precio_venta = $articulos['precio_venta'][$i];
                $precio_recargoPV = $articulos['precio_venta'][$i];
                $precio_recargoQRef = $articulos['precio_venta'][$i];
                $descuento_porcentaje = '0';
                $descripcion_detalle = '.';
                $subtotal1 = $articulos['subtotal1'][$i];
                $subtotaldes1 = $articulos['subtotaldes1'][$i];

                // Insertar artículo principal
                $sql_detalle = "INSERT INTO detalle_cotizacion (
                    idcotizacion, idarticulo, cantidad, precio_venta, descuento, descripcion_detalle,
                    stockinven, cantidadpresentacion, totalcantidadpresentacion, presen, 
                    precio_ventaSistema, precio_ventaSistema2, q_ref, precio_recargoPV, 
                    precio_recargoQRef, subtotal1, subtotaldes1
                ) VALUES (
                    '$idcotizacionnew', '$idarticulo', '$cantidad', '$precio_venta', '$descuento_porcentaje', 
                    '$descripcion_detalle', '$stockinven', '$cantidadpresentacion', '$totalcantidadpresentacion', 
                    '$presen', '$precio_ventaSistema', '$precio_ventaSistema2', '$q_ref', '$precio_recargoPV', 
                    '$precio_recargoQRef', '$subtotal1', '$subtotaldes1'
                )";
                ejecutarConsulta($sql_detalle) or $sw = false;
            }

        }



        return $idcotizacionnew;
    }

    public function editar(
        $idcotizacion,
        $idcliente,
        $codigo_cliente,
        $nit,
        $nombre_cliente,
        $telefono_cliente,
        $direccion_cliente,
        $correo_cliente,
        $valor_tarjeta,
        $tipo_documento_cliente,
        $idusuario,
        $fecha_hora,
        $forma_pago,
        $tipo_comprobante,
        $tipo_pagoBacVisaNet,
        $opcionesAdicionales,
        $total_venta,
        $total_ventades,
        $idvendedor,
        $tipo_cliente,
        $forma_productos,
        $comentario_cotizacion,
        $destino,
        $datosArticulos
    ) {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');
        /////CAPTURA DE CLIENTE NUEVO Y UPDATE
        if ($idcliente == '0') {
            $sqlcorrelativo = "UPDATE add_correlativo SET 
                codigo_cliente=codigo_cliente+1 WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
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
                                                    '$tipo_cliente',
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
                                            nombre='$nombre_cliente',
                                            tipo_cliente='$tipo_cliente'
                                    WHERE idpersona='$idcliente'";

            ejecutarConsulta($sqlcorrelativo);
            $residcliente = $idcliente;
        }
        ///////   





        $sqlUpdate = "UPDATE cotizacion 
                        SET 
                            idcliente = '$residcliente',
                            idusuario_update = '$idusuario',
                            fecha_hora = '$fecha_hora',
                            total_venta = '$total_venta',
                            total_ventades = '$total_ventades',
                            fechaModificacion = '$fechaHora',
                            forma_pago = '$forma_pago',
                            valor_tarjeta='$valor_tarjeta',
                            tipo_pagoBacVisaNet='$tipo_pagoBacVisaNet',
                            opcionesAdicionales='$opcionesAdicionales',
                            idvendedor='$idvendedor',
                            forma_productos='$forma_productos',
                            comentario_cotizacion='$comentario_cotizacion',
                            destino='$destino'
                             WHERE idcotizacion='$idcotizacion'";
        ejecutarConsulta($sqlUpdate);


        $sql = "DELETE from detalle_cotizacion where idcotizacion=" . $idcotizacion . "";
        ejecutarConsulta($sql);

        $num_elementos = 0;
        $sw = true;

        if ($idcotizacion) {
            $articulos = $datosArticulos['articulos'];
            $numArticulos = count($articulos['idarticulo']);
            $sw = true;

            // ===== PRIMERA PARTE: insertar los artículos principales =====
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

                // Insertar artículo principal
                $sql_detalle = "INSERT INTO detalle_cotizacion (
                    idcotizacion, idarticulo, cantidad, precio_venta, descuento, descripcion_detalle,
                    stockinven, cantidadpresentacion, totalcantidadpresentacion, presen, 
                    precio_ventaSistema, precio_ventaSistema2, q_ref, precio_recargoPV, 
                    precio_recargoQRef, subtotal1, subtotaldes1
                ) VALUES (
                    '$idcotizacion', '$idarticulo', '$cantidad', '$precio_venta', '$descuento_porcentaje', 
                    '$descripcion_detalle', '$stockinven', '$cantidadpresentacion', '$totalcantidadpresentacion', 
                    '$presen', '$precio_ventaSistema', '$precio_ventaSistema2', '$q_ref', '$precio_recargoPV', 
                    '$precio_recargoQRef', '$subtotal1', '$subtotaldes1'
                )";
                ejecutarConsulta($sql_detalle) or $sw = false;
            }

            // ===== SEGUNDA PARTE: procesar los extras y toppings globales =====
            if (isset($articulos['idarticuloExtra_extras']) && is_array($articulos['idarticuloExtra_extras'])) {

                $extras = $articulos['idarticuloExtra_extras'];
                $tipos = $articulos['tipo_item_extras'] ?? [];
                $cantidades = $articulos['cantidad_extra'] ?? [];
                $productos = $articulos['idproducto_extra'] ?? [];
                $checks = $articulos['check_extras'] ?? [];

                $n = count($extras);
                for ($j = 0; $j < $n; $j++) {

                    $tipo = $tipos[$j] ?? 'Topping';
                    $idextra = (int) $extras[$j];
                    $cantidad_extra = (float) ($cantidades[$j] ?? 0);
                    $idproducto = (int) ($productos[$j] ?? 0);

                    // 🔹 Ignorar si no pertenece a ningún artículo o está desmarcado
                    if ($idextra <= 0 || $cantidad_extra <= 0 || $idproducto <= 0)
                        continue;
                    if (isset($checks[$j]) && !$checks[$j])
                        continue;

                    /* $sqlVerificacionExistencia="SELECT 
                     p.idproducto,
                     dp.cantidad as cantmateriaprima,
                     dp.idarticulo as idarticulo_costo
                     FROM produccion p 
                     INNER JOIN detalle_produccion dp ON p.idproduccion=dp.idproduccion
                     WHERE p.idproducto='$idextra' and  and dp.tipo_item='Producto'";
                     $EXIS=ejecutarConsulta($sqlVerificacionExistencia);   */


                    $total_a_descontar = $cantidad_extra * $totalcantidadpresentacion;

                    // 🔹 Precio del extra (solo si aplica)
                    $sqlPrecio = "SELECT *
                                  FROM articuloxsucursal 
                                  WHERE idarticulo = '$idextra' 
                                  AND idsucursal = '" . $_SESSION["idsucursal"] . "' LIMIT 1";
                    $resPrecio = ejecutarConsultaSimpleFila($sqlPrecio) ?: [];
                    $precio_venta_extra = (float) ($resPrecio['precio_unidad'] ?? 0);
                    $precio_compra_extra = (float) ($resPrecio['precio_compra'] ?? 0);
                    $stocksucursal_extra = (float) ($resPrecio['stocksucursal'] ?? 0);
                    $subtotaleExtra = $tipo === 'Extra' ? ($total_a_descontar * $precio_venta_extra) : 0;

                    $ressubtotal1 = $tipo === 'Extra' ? ($total_a_descontar * $precio_venta_extra) : 0;

                    // 🔹 Insertar en detalle_cotizacion sin duplicar
                    $sql_extra = "INSERT INTO detalle_cotizacion(
                        idcotizacion, idarticulo, cantidad, 
                        precio_venta, descuento, descripcion_detalle,presen,
                    stockinven, cantidadpresentacion, totalcantidadpresentacion,
                    precio_ventaSistema, precio_ventaSistema2, q_ref, precio_recargoPV, 
                    precio_recargoQRef, subtotal1, subtotaldes1, checks,tipo,idarticulopadre
                    ) VALUES (
                        '$idcotizacion', '$idextra', '$cantidad_extra', 
                        '$precio_venta_extra','0','.','UNIDAD', 
                         '$stocksucursal_extra', '1','$total_a_descontar',
                         '$precio_venta_extra','$precio_venta_extra','$precio_venta_extra','$precio_venta_extra',
                         '$precio_venta_extra', '$ressubtotal1', '0', '1', '$tipo', '$idproducto'
                    )";
                    ejecutarConsulta($sql_extra) or $sw = false;
                }
            }
        }

        return $idcotizacion;
    }


    //Implementamos un método para anular la venta
    public function anular($idcotizacion)
    {
        $sql = "UPDATE cotizacion SET estado='Anulado' WHERE idcotizacion='$idcotizacion' AND cobradosino='NO' ";
        ejecutarConsulta($sql);
        return ($sql);
    }

    public function listo($idcotizacion)
    {
        $sql = "UPDATE cotizacion SET tipo_envioPedidos='Listo' WHERE idcotizacion='$idcotizacion' ";
        ejecutarConsulta($sql);
        return ($sql);
    }


    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idcotizacion)
    {
        $sql = "SELECT 
            c.idcotizacion,
            c.idcliente,
            date(c.fecha_hora) AS fecha,
            c.total_venta,
            c.tipo_comprobante,
            c.total_ventades,
            c.tipo_pagoBacVisaNet,
            c.opcionesAdicionales,
            c.cobradosino,
            c.forma_pago,
            p.codigo_cliente,
            p.num_documento AS nit,
            p.nombre as nombre_cliente,
            p.telefono AS telefono_cliente,
            p.direccion AS direccion_cliente,
            p.email AS correo_cliente,
            p.tipo_documento AS tipo_documento_cliente,
            vv.idvendedor,
            c.estado
         FROM cotizacion c
         INNER JOIN persona p ON p.idpersona=c.idcliente
         left JOIN vendedor vv ON c.idvendedor=vv.idvendedor
         WHERE c.cobradosino='NO' and c.estado='Aceptado' AND c.idcotizacion='$idcotizacion'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function mostrarVenta($idcotizacion)
    {
        $sql = "SELECT 
            c.idventa,
            c.idcliente,
            date(c.fecha_hora) AS fecha,
            c.total_venta,
            c.tipo_comprobante,
            c.total_ventades,
            c.tipo_pagoBacVisaNet,
            c.opcionesAdicionales,
            c.notacredito,
            c.forma_pago,
            p.codigo_cliente,
            p.num_documento AS nit,
            p.nombre as nombre_cliente,
            p.telefono AS telefono_cliente,
            p.direccion AS direccion_cliente,
            p.email AS correo_cliente,
            p.tipo_documento AS tipo_documento_cliente,
            c.estado,
            c.autorizacionEcoFactura,
            c.serie_ecoFactura,
            c.numero_ecoFactura
         FROM venta c
         INNER JOIN persona p ON p.idpersona=c.idcliente
         WHERE c.notacredito='NO' and c.estado='Aceptado' AND c.idventa='$idcotizacion'";
        return ejecutarConsultaSimpleFila($sql);
    }


    public function listarDetalle($idcotizacion)
    {
        $sql = "SELECT 
            dc.iddetalle_cotizacion,
            dc.idcotizacion,
            dc.idarticulo,
            a.nombre,
            dc.cantidad,
            dc.precio_venta,
            dc.descuento,
            (dc.cantidad*dc.precio_venta-dc.descuento) as subtotal,
            dc.descripcion_detalle
            FROM detalle_cotizacion dc 
            INNER JOIN articulo a on dc.idarticulo=a.idarticulo
            where dc.idcotizacion='$idcotizacion'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros
    public function listar($fecha_inicio_reporte, $fecha_fin_reporte)
    {
        $sql = "SELECT 
                c.idcotizacion,
                c.idcliente,
                p.nombre as cliente,
                c.idusuario,
                u.nombre as usuario,
                c.nombre_empresa,
                c.telefono_empresa,
                DATE(c.fecha_hora) as fecha,
                c.impuesto,
                c.total_venta,
                c.total_ventades,
                c.estado, 
                c.tipo_comprobante,
                c.num_comprobante,
                c.cobradosino,
                c.forma_pago,
                c.comentario_cotizacion,
                c.destino,
                c.forma_productos
                FROM cotizacion c
                INNER JOIN persona p ON p.idpersona=c.idcliente
                INNER JOIN usuario u ON u.idusuario=c.idusuario
                where DATE(c.fecha_hora)>='$fecha_inicio_reporte' 
                AND DATE(c.fecha_hora)<='$fecha_fin_reporte' 
                and c.idsucursal='" . $_SESSION["idsucursal"] . "'
                ORDER by c.idcotizacion desc ";
        return ejecutarConsulta($sql);
    }

    public function listarxfechasucursal($fecha_inicio, $fecha_fin, $idsucursal)
    {
        $sql = "SELECT 
                c.idcotizacion,
                c.idcliente,
                p.nombre as cliente,
                c.idusuario,
                u.nombre as usuario,
                c.nombre_empresa,
                c.telefono_empresa,
                DATE(c.fecha_hora) as fecha, 
                c.impuesto,
                c.total_venta,
                c.total_ventades,
                c.estado,
                c.tipo_comprobante,
                c.num_comprobante,
                c.cobradosino,
                c.forma_pago
                FROM cotizacion c
                INNER JOIN persona p ON p.idpersona=c.idcliente
                INNER JOIN usuario u ON u.idusuario=c.idusuario
                where DATE(c.fecha_hora)>='$fecha_inicio' AND DATE(c.fecha_hora)<='$fecha_fin' and c.idsucursal='$idsucursal'
                 ";
        return ejecutarConsulta($sql);
    }



    public function listarxfechasucursalDetalle($fecha_inicio, $fecha_fin, $idsucursal)
    {
        $sql = "SELECT 
                a.nombre as articulo,
                a.codigo,
                d.cantidad,
                d.precio_venta,
                d.descuento,
                round(((d.precio_venta-((d.precio_venta*d.descuento)/100))*d.cantidad),2) as subtotal,
                d.descripcion_detalle,
                c.num_comprobante,
                c.idcotizacion
                FROM detalle_cotizacion d 
                inner join cotizacion c on c.idcotizacion=d.idcotizacion
                INNER JOIN articulo a ON d.idarticulo=a.idarticulo
                where DATE(c.fecha_hora)>='$fecha_inicio' AND DATE(c.fecha_hora)<='$fecha_fin' and c.idsucursal='$idsucursal'
                 ";
        return ejecutarConsulta($sql);
    }




    public function listarCabeceraspantalla($fecha_inicio, $fecha_fin, $tipo_envioPedidos)
    {
        $sql = "SELECT 
                c.idcotizacion,
                c.idcliente,
                p.tipo_persona,
                p.nombre as cliente,
                p.tipo_documento,
                p.num_documento,
                p.direccion,
                p.telefono,
                p.email,
                p.tipo_cliente,
                c.idusuario,
                u.nombre as usuario,
                u.telefono as  usuario_telefono,
                u.email as usuario_email,
                c.nombre_empresa,
                c.telefono_empresa,
                DATE(c.fecha_hora) as fecha,
                c.impuesto,
                c.total_venta,
                c.total_ventades,
                (c.total_venta + c.total_ventades) as total_general,
                c.forma_pago,
                c.cobradosino,
                c.estado,
                c.tipo_comprobante,
                s.idsucursal,
                s.nombre as sucursal_nombre,
                s.nombre_comercial,
                s.nombre_fel,
                s.direccion_fiscal,
                s.direccion as sucursal_direccion,
                s.telefono as sucursal_telefono,
                s.nit as sucursal_nit,
                s.email as sucursal_email,
                s.imagen as sucursal_imagen,
                s.condicion as sucursal_condicion,
                COALESCE(vv.nombre, 'Sin vendedor') as nombre_vendedor,
                c.num_comprobante,
                c.comentario_cotizacion,
                c.destino,
                c.forma_productos,
                c.tipo_envioPedidos
                FROM cotizacion c
                INNER JOIN persona p ON p.idpersona=c.idcliente
                INNER JOIN usuario u ON u.idusuario=c.idusuario
                INNER JOIN sucursal s ON s.idsucursal=c.idsucursal
                left JOIN vendedor vv ON c.idvendedor=vv.idvendedor
            where DATE(c.fecha_hora)>='$fecha_inicio' 
            AND DATE(c.fecha_hora)<='$fecha_fin' 
            and c.idsucursal='" . $_SESSION["idsucursal"] . "' and c.destino='PANTALLA' and c.tipo_envioPedidos='$tipo_envioPedidos'";
        return ejecutarConsulta($sql);
    }


    public function cotizaciondetallepantalla($idcotizacion)
    {
        $sql = "SELECT 
                d.iddetalle_cotizacion,
                d.idarticulo,
                a.nombre as articulo,
                a.codigo,
                round(d.cantidad,2) as cantidad,
                a.descripcion,
                d.precio_venta,
                d.descuento,
                d.presen,
                d.tipo,
                round(d.q_ref,2) as q_ref,
                round(d.subtotal1,2) as subtotal,
                d.descripcion_detalle,
                d.idarticulopadre
                FROM detalle_cotizacion d 
                INNER JOIN articulo a ON d.idarticulo=a.idarticulo 
            WHERE d.idcotizacion='$idcotizacion'";
        return ejecutarConsulta($sql);
    }

    public function cotizaciondetalle($idcotizacion)
    {
        $sql = "SELECT 
                a.nombre as articulo,
                a.codigo,
                round(d.cantidad,2) as cantidad,
                a.descripcion,
                d.precio_venta,
                d.tipo,
                d.idarticulopadre,
                d.idarticulo,
                d.descuento,
                d.presen,
                round(d.q_ref,2) as q_ref,
                round(d.subtotal1,2) as subtotal,
                d.descripcion_detalle
                FROM detalle_cotizacion d 
                INNER JOIN articulo a ON d.idarticulo=a.idarticulo 
            WHERE d.idcotizacion='$idcotizacion'";
        return ejecutarConsulta($sql);
    }


    public function detallecotizacionparaventa($cotizacion)
    {

        $sqldetalle = "SELECT 
        dc.iddetalle_cotizacion,
        dc.idcotizacion,
        dc.idarticulo,
        dc.cantidad,
        dc.precio_venta,
        dc.descuento,
        dc.descripcion_detalle,
        dc.stockinven,
        dc.cantidadpresentacion,
        dc.totalcantidadpresentacion,
        dc.presen,
        dc.precio_ventaSistema,
        dc.precio_ventaSistema2,
        dc.q_ref,
        dc.precio_recargoPV,
        dc.precio_recargoQRef,
        dc.subtotal1,
        dc.subtotaldes1,
        a.nombre,
        asu.idsucursal,
        asu.stocksucursal as stock,
        asu.stockminimo,
        asu.precio_compra,
        asu.precio_ventaNocturno,
        asu.precio_descuento,
        asu.precio_rango1,
        asu.precio_rango2,
        asu.precio_rango3,
        asu.nombre_01,asu.stock_unidad,asu.precio_unidad,
        asu.nombre_02,asu.stock_blister,asu.precio_blister,
        asu.nombre_03,asu.stock_caja,asu.precio_caja,
        asu.nombre_04,asu.stock_fardo,asu.precio_fardo,
        asu.nombre_05,asu.stock_sacos,asu.precio_sacos,
        asu.nombre_06,asu.stock_paquete,asu.precio_paquete,
        asu.nombre_07,asu.stock_07,asu.precio_07,
        asu.nombre_08,asu.stock_08,asu.precio_08,
        asu.nombre_09,asu.stock_09,asu.precio_09,
        asu.nombre_10,asu.stock_10,asu.precio_10,
        asu.nombre_11,asu.stock_11,asu.precio_11,
        asu.nombre_12,asu.stock_12,asu.precio_12,
        asu.nombre_13,asu.stock_13,asu.precio_13,
        asu.nombre_14,asu.stock_14,asu.precio_14,
        asu.nombre_15,asu.stock_15,asu.precio_15,
        asu.nombre_16,asu.stock_16,asu.precio_16,
        asu.nombre_17,asu.stock_17,asu.precio_17,
        asu.nombre_18,asu.stock_18,asu.precio_18,
        asu.nombre_19,asu.stock_19,asu.precio_19,
        asu.nombre_20,asu.stock_20,asu.precio_20,
        asu.precio_rango1,
        asu.precio_rango2,
        asu.precio_rango3,
        asu.precio_rango1_Dos,  
        asu.precio_rango2_Dos,
        asu.precio_rango3_Dos,
        asu.precio_rango1_Mecanico,
        asu.precio_rango2_MecanicoDos,
        asu.precio_rango3_MecanicoTres,
        asu.precio_rango1_Distribuidor,
        asu.precio_rango2_DistribuidorDos,
        asu.precio_rango3_DistribuidorTres,
        asu.precio_rango1_Mayorista,
        asu.precio_rango2_MayoristaDos,
        asu.precio_rango3_MayoristaTres,
        asu.condicion,
        asu.precio_activado,
        asu.descuento_porcentaje               
        from detalle_cotizacion dc 
        INNER JOIN articulo a on dc.idarticulo=a.idarticulo 
        INNER JOIN articuloxsucursal asu ON asu.idarticulo=a.idarticulo
        where asu.idsucursal='" . $_SESSION["idsucursal"] . "'  
        and dc.idcotizacion='$cotizacion' 
        AND dc.tipo = '0'
        order by dc.iddetalle_cotizacion desc ";

        #echo $sql;
        $rspta = ejecutarConsulta($sqldetalle);
        $rows = array();
        while ($reg = $rspta->fetch_object()) {
            $rows[] = $reg;
        }
        return $rows;
    }

    public function cotizacioncabecera($idcotizacion)
    {
        $sql = "SELECT 
                c.idcotizacion,
                c.idcliente,
                p.tipo_persona,
                p.nombre as cliente,
                p.tipo_documento,
                p.num_documento,
                p.direccion,
                p.telefono,
                p.email,
                p.tipo_cliente,
                c.idusuario,
                u.nombre as usuario,
                u.telefono as  usuario_telefono,
                u.email as usuario_email,
                c.nombre_empresa,
                c.telefono_empresa,
                DATE(c.fecha_hora) as fecha,
                c.impuesto,
                c.total_venta,
                c.total_ventades,
                (c.total_venta + c.total_ventades) as total_general,
                c.forma_pago,
                c.cobradosino,
                c.estado,
                c.tipo_comprobante,
                s.idsucursal,
                s.nombre as sucursal_nombre,
                s.nombre_comercial,
                s.nombre_fel,
                s.direccion_fiscal,
                s.direccion as sucursal_direccion,
                s.telefono as sucursal_telefono,
                s.nit as sucursal_nit,
                s.email as sucursal_email,
                s.imagen as sucursal_imagen,
                s.condicion as sucursal_condicion,
                COALESCE(vv.nombre, 'Sin vendedor') as nombre_vendedor,
                c.num_comprobante
                FROM cotizacion c
                INNER JOIN persona p ON p.idpersona=c.idcliente
                INNER JOIN usuario u ON u.idusuario=c.idusuario
                INNER JOIN sucursal s ON s.idsucursal=c.idsucursal
                left JOIN vendedor vv ON c.idvendedor=vv.idvendedor
            WHERE c.idcotizacion='$idcotizacion'";
        return ejecutarConsulta($sql);
    }



    public function detallecotizacionparaventaVenta($cotizacion)
    {

        $sqldetalle = "SELECT 
        dc.iddetalle_venta,
        dc.idventa,
        dc.idarticulo,
        dc.cantidad,
        dc.precio_venta,
        dc.descuento,
        dc.descripcion_detalle,
        dc.stockinven,
        dc.cantidadpresentacion,
        dc.totalcantidadpresentacion,
        dc.presen,
        dc.precio_ventaSistema,
        dc.precio_ventaSistema2,
        dc.q_ref,
        dc.precio_recargoPV,
        dc.precio_recargoQRef,
        dc.subtotal1,
        dc.subtotaldes1,
        a.nombre,
        asu.idsucursal,
        asu.stocksucursal as stock,
        asu.stockminimo,
        asu.precio_compra,
        asu.precio_ventaNocturno,
        asu.precio_descuento,
        asu.precio_rango1,
        asu.precio_rango2,
        asu.precio_rango3,
        asu.stock_unidad,
        asu.precio_unidad,
        asu.stock_blister,
        asu.precio_blister,
        asu.stock_caja,
        asu.precio_caja,
        asu.stock_fardo,
        asu.precio_fardo,
        asu.stock_sacos,
        asu.precio_sacos,
        asu.stock_paquete,
        asu.precio_paquete,
        asu.precio_rango1,
        asu.precio_rango2,
        asu.precio_rango3,
        asu.condicion                   
        from detalle_venta dc 
        INNER JOIN articulo a on dc.idarticulo=a.idarticulo 
        INNER JOIN articuloxsucursal asu ON asu.idarticulo=a.idarticulo
        where asu.idsucursal='" . $_SESSION["idsucursal"] . "'  and dc.idventa=" . $cotizacion;

        #echo $sql;
        $rspta = ejecutarConsulta($sqldetalle);
        $rows = array();
        while ($reg = $rspta->fetch_object()) {
            $rows[] = $reg;
        }
        return $rows;
    }



    public function mostrarVentaAdministrador($idventa_administrador)
    {

        $sql = "SELECT date(v.fecha_hora) AS fecha,v.idventa,v.* FROM venta v  WHERE v.idventa='$idventa_administrador'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function detallecotizacionparaventaadministrador($idventa_administrador)
    {

        $sqldetalle = "SELECT 
                dv.iddetalle_venta,
            dv.idventa,
            dv.idarticulo,
            dv.cantidad as stock,
            dv.precio_venta,
            dv.descuento as descuento_porcentaje,
            dv.descripcion_detalle,
            a.nombre AS articulo,
            (SELECT axt.stocksucursal FROM articuloxsucursal axt WHERE axt.idarticulo=dv.idarticulo LIMIT 1) AS stockreal
             FROM detalle_venta dv
             INNER JOIN articulo a ON a.idarticulo=dv.idarticulo
              where dv.idventa=" . $idventa_administrador;

        #echo $sql;
        $rspta = ejecutarConsulta($sqldetalle);
        $rows = array();
        while ($reg = $rspta->fetch_object()) {
            $rows[] = $reg;
        }
        return $rows;
    }

    public function ventacabecera2($idventa)
    {
        $sql = "SELECT 
        v.idventa,
        v.idcliente,
        p.nombre as cliente,
        p.tipo_documento,
        p.num_documento,
        p.direccion,
        p.telefono,
        p.email,
        v.idusuario,
        v.tipo_comprobante,
        v.serie_comprobante,
        v.num_comprobante,
        DATE(v.fecha_hora) as fecha,
        v.impuesto,
        v.total_venta,
        v.total_ventades,
        (v.total_venta+v.total_ventades) AS totalgeneral,
        v.estado,
        v.cefectivo,
        v.rescambio,
        v.ctarjeta,
        v.ccredito,
        v.tipo_pagoBacVisaNet,
        v.opcionesAdicionales,
        v.condicion,
        u.nombre as usuario,
        v.forma_pago,
        s.idsucursal,
        s.nombre as sucursal_nombre, 
        s.direccion as sucursal_direccion,
        s.direccion_fiscal,
        s.telefono as sucursal_telefono,
        s.nit as sucursal_nit,
        s.email as sucursal_email,
        s.imagen as sucursal_imagen,
        s.condicion as sucursal_condicion,
        c.dato_sat,
        c.nombrecertificador,
        c.empresadesarrollo,
        s.nombre_comercial,
        s.nombre_fel,
        v.autorizacionEcoFactura,
        v.serie_ecoFactura,
        v.numero_ecoFactura,
        v.fechaCertificacion_ecoFactura,
        v.nombre_vendedor,
        v.numero_pagos,
        date(v.fecha_hora_pago) as fechahorapago,
        date(v.fecha_hora_vencimiento_factura) as fechahoravencimientofactura,
        v.monto_abono,
        v.despachosino,
        v.guia_transporte,
        v.tipo_entrega,
        vv.nombre as nombre_vendedor,
        CASE 
                WHEN mc.comentario_mensajero IS NULL OR mc.comentario_mensajero = '' 
                THEN 'SIN COMENTARIOS' 
                ELSE mc.comentario_mensajero 
         END AS comentario_mensajero,
         aa.id_add_orden,
         (SELECT mm.nombre FROM mesa mm WHERE mm.idmesa=aa.idmesa LIMIT 1) mesa_orden,
         (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=aa.idusuario LIMIT 1) usuario_orden
        from venta v
        INNER JOIN persona p ON p.idpersona=v.idcliente
        inner join usuario u on u.idusuario=v.idusuario
        INNER JOIN sucursal s ON s.idsucursal=v.idsucursal
        INNER JOIN certificador c ON c.idsucursal=s.idsucursal
        left join vendedor vv on vv.idvendedor=v.idvendedor
        LEFT JOIN mensajero_comentarios mc ON mc.idventa = v.idventa
        left join add_orden aa ON aa.idventa=v.idventa
        WHERE  c.condicion=1 and v.idventa='$idventa' ";
        return ejecutarConsulta($sql);
    }




    public function ventacabeceraNC2($idnota_credito)
    {
        $sql = "SELECT 
                v.idnota_credito,
                p.nombre as cliente,
                p.direccion,
                p.tipo_documento,
                p.num_documento,
                p.telefono,
                p.email,
                u.nombre AS usuarioNc,
                v.tipo_comprobante,
                v.num_comprobante,
                v.total_venta, 
                v.total_ventades,
                (v.total_venta+v.total_ventades) AS totalgeneral,
                v.forma_pago,   
                v.fecha_hora_nc,   
                DATE(v.fecha_hora_nc) as fecha_hora_nc,
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
                v.motivo_nc,
                s.nombre as sucursal_nombre, 
                s.direccion as sucursal_direccion,
                s.direccion_fiscal,
                s.telefono as sucursal_telefono,
                s.nit as sucursal_nit,
                s.email as sucursal_email,
                s.imagen as sucursal_imagen,
                s.condicion as sucursal_condicion,
                s.nombre_fel,
                s.nombre_comercial,
                c.dato_sat,
                c.nombrecertificador,
                c.empresadesarrollo                  
        FROM nota_credito v 
        INNER JOIN venta vv ON v.idnota_credito=vv.idnota_credito
        INNER JOIN persona p ON v.idcliente=p.idpersona 
        INNER JOIN usuario u ON v.idusuario=u.idusuario
        INNER JOIN sucursal s ON s.idsucursal=v.idsucursal
        inner join certificador c on c.idsucursal=s.idsucursal
        WHERE v.idnota_credito='$idnota_credito' AND c.condicion=1 ";
        return ejecutarConsulta($sql);
    }


    public function ventadetalle2($idventa)
    {
        $sql = "SELECT 
                dv.iddetalle_venta,
                dv.idventa,
                dv.idarticulo,
                a.nombre as articulo,
                COALESCE(a.codigo, '') AS codigo,
                ROUND(dv.cantidad,2) as cantidad,
                a.precio_compra,
                a.descripcion_2,
                dv.precio_venta,
                dv.descuento,
                dv.presen,
                ROUND(dv.q_ref,2) as q_ref,
                ROUND(dv.subtotal1+dv.subtotaldes1,2) as totalsindescuento,
                ROUND(dv.subtotal1, 2) as subtotal, -- Redondear subtotal a 2 decimales
                ROUND(dv.subtotaldes1,2) as total_descuento,
                dv.descripcion_detalle,
                dv.tipo,
                dv.idarticulopadre
                from detalle_venta dv
                INNER JOIN articulo a ON dv.idarticulo=a.idarticulo 
                WHERE dv.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }


    public function ventadetalleNC2($idventa)
    {
        $sql = "SELECT 
                dv.iddetalle_nota_credito,
                dv.idnota_credito,
                dv.idarticulo,
                a.nombre as articulo,
                a.codigo,
                ROUND(dv.cantidad,2) as cantidad,
                dv.precio_venta,
                dv.descuento,
                dv.presen,
                ROUND(dv.q_ref,2) as q_ref,
                ROUND(dv.subtotal1, 2) as subtotal, -- Redondear subtotal a 2 decimales
                ROUND(dv.subtotaldes1,2) as total_descuento,
                dv.descripcion_detalle
                from detalle_nota_credito dv
                INNER JOIN articulo a ON dv.idarticulo=a.idarticulo 
                WHERE dv.idnota_credito='$idventa'";
        return ejecutarConsulta($sql);
    }

    public function ventadetalle3($idventa)
    {
        $sql = "SELECT 
    cc.idcta_cobrar,
    IFNULL(cc.idventa, 0) AS idventa,
    IFNULL(cc.idcliente, 0) AS idcliente,
    IFNULL(cc.total_venta, 0) AS total_venta,
    IFNULL(cc.total_abono, 0) AS total_abono,
    IFNULL(cc.saldo_venta, 0) AS saldo_venta,
    IFNULL(cc.condicion, 0) AS condicion,
    IFNULL(cc.idusuario, 0) AS idusuario,
    CASE 
        WHEN cc.tipo_pago IS NULL OR cc.tipo_pago = '' THEN '0'
        ELSE cc.tipo_pago
    END AS tipo_pago,
    CASE 
        WHEN cc.tipo_banco IS NULL OR cc.tipo_banco = '' THEN '0'
        ELSE cc.tipo_banco
    END AS tipo_banco,    
    
    CASE 
        WHEN cc.numero_boleta IS NULL OR cc.numero_boleta = '' THEN '0'
        ELSE cc.numero_boleta
    END AS numero_boleta,      
    
    CASE 
        WHEN cc.recibo_caja_numero IS NULL OR cc.recibo_caja_numero = '' THEN '0'
        ELSE cc.recibo_caja_numero
    END AS recibo_caja_numero,
	       
    CASE 
        WHEN cc.descripcion IS NULL OR cc.descripcion = '' THEN '0'
        ELSE cc.descripcion
    END AS descripcion,  

    CASE 
        WHEN cc.fechapago IS NULL OR cc.fechapago = '' THEN '' 
        ELSE DATE(cc.fechapago)
    END AS fechapago

FROM cta_cobrar cc
WHERE cc.idventa ='$idventa'";
        return ejecutarConsulta($sql);
    }


    public function detalle_abonosCtasxcobrar($idcta_cobrar)
    {
        $sql = "SELECT 
                cc.idcta_cobrar,
                cc.idventa,
                cc.idcliente,
                IFNULL(cc.total_venta, 0) AS total_venta,
                IFNULL(cc.total_abono, 0) AS total_abono,
                IFNULL(cc.saldo_venta, 0) AS saldo_venta,
                IFNULL(cc.tipo_pago, '') AS tipo_pago,

                CASE 
                    WHEN cc.fechapago IS NULL OR cc.fechapago = '' THEN ''
                    ELSE DATE(cc.fechapago)
                END AS fechapago,

                CASE 
                    WHEN cc.tipo_banco IS NULL OR cc.tipo_banco = '' THEN 'N/A'
                    ELSE cc.tipo_banco
                END AS tipo_banco,

                CASE 
                    WHEN cc.numero_boleta IS NULL OR cc.numero_boleta = '' THEN '0'
                    ELSE cc.numero_boleta
                END AS numero_boleta,

                CASE 
                    WHEN cc.recibo_caja_numero IS NULL OR cc.recibo_caja_numero = '' THEN '0'
                    ELSE cc.recibo_caja_numero
                END AS recibo_caja_numero,

                CASE 
                    WHEN cc.descripcion IS NULL OR cc.descripcion = '' THEN ''
                    ELSE cc.descripcion
                END AS descripcion,

                IFNULL(cc.condicion, 0) AS condicion,
                IFNULL(cc.idusuario, 0) AS idusuario

            FROM cta_cobrar cc
            WHERE cc.idcta_cobrar ='$idcta_cobrar'";
        return ejecutarConsulta($sql);
    }



    public function ventacabecera5($idventa)
    {
        $sql = "SELECT 
                i.idingreso,
                i.idproveedor,
                p.nombre as proveedor,
                p.direccion,
                p.telefono,
                p.num_documento,
                p.email,
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
                DATE(i.fecha_hora_pago_credito) as fechahorapagocredito,
                s.idsucursal,
                s.nombre as sucursal_nombre,
                s.direccion as sucursal_direccion,
                s.telefono as sucursal_telefono,
                s.nit as sucursal_nit,
                s.email as sucursal_email,
                s.imagen as sucursal_imagen,
                s.condicion as sucursal_condicion,
                s.nombre_fel,
                p.codigo_cliente              
                FROM ingreso i
                INNER JOIN persona p ON p.idpersona=i.idproveedor
                INNER JOIN usuario u ON u.idusuario=i.idusuario
                INNER JOIN sucursal s ON s.idsucursal=i.idsucursal
                WHERE i.idingreso='$idventa' AND s.idsucursal='" . $_SESSION["idsucursal"] . "' ";
        return ejecutarConsulta($sql);
    }

    public function ventacabecera5_ordencompra($idventa)
    {
        $sql = "SELECT 
                i.idorden_compra,
                i.idproveedor,
                p.nombre as proveedor,
                p.direccion,
                p.telefono,
                p.num_documento,
                p.email,
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
                DATE(i.fecha_hora_pago_credito) as fechahorapagocredito,
                s.idsucursal,
                s.nombre as sucursal_nombre,
                s.direccion as sucursal_direccion,
                s.telefono as sucursal_telefono,
                s.nit as sucursal_nit,
                s.email as sucursal_email,
                s.imagen as sucursal_imagen,
                s.condicion as sucursal_condicion,
                s.nombre_fel,
                p.codigo_cliente              
                FROM orden_compra i
                INNER JOIN persona p ON p.idpersona=i.idproveedor
                INNER JOIN usuario u ON u.idusuario=i.idusuario
                INNER JOIN sucursal s ON s.idsucursal=i.idsucursal
                WHERE i.idorden_compra='$idventa' AND i.idsucursal='" . $_SESSION["idsucursal"] . "' ";
        return ejecutarConsulta($sql);
    }

    public function cabeceraNotaDebito($idnota_debito)
    {
        $sql = "SELECT 
                i.idnota_debito,
                i.idingreso,
                i.idproveedor,
                p.nombre as proveedor,
                p.direccion,
                p.telefono,
                p.num_documento,
                p.email,
                i.idusuario,
                u.nombre as usuarioND,
                i.tipo_comprobante,
                i.serie_comprobante,
                i.num_comprobante,
                DATE(i.fecha_hora) as fechaIngreso,
                DATE(i.fecha_hora_ND) as fechaND,
                i.motivo_ND,
                i.impuesto,
                i.total_compra,
                i.estado,
                i.forma_pago,
                i.dias_credito,
                i.direccion_entrega_orden_compra,
                DATE(i.fecha_entrega_orden_compra) as fechaentregaordencompra,
                i.observacion_orden_compra,
                DATE(i.fecha_hora_pago_credito) as fechahorapagocredito,
                s.idsucursal,
                s.nombre as sucursal_nombre,
                s.direccion as sucursal_direccion,
                s.telefono as sucursal_telefono,
                s.nit as sucursal_nit,
                s.email as sucursal_email,
                s.imagen as sucursal_imagen,
                s.condicion as sucursal_condicion,
                s.nombre_fel,
                p.codigo_cliente,
                (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=ii.idusuario LIMIT 1) AS usuarioIngreso
                FROM nota_debito i
                INNER JOIN ingreso ii ON ii.idnota_debito=i.idnota_debito
                INNER JOIN persona p ON p.idpersona=i.idproveedor
                INNER JOIN usuario u ON u.idusuario=i.idusuario
                INNER JOIN sucursal s ON s.idsucursal=i.idsucursal
                WHERE i.idnota_debito='$idnota_debito' AND i.idsucursal='" . $_SESSION["idsucursal"] . "'  ";

        return ejecutarConsulta($sql);
    }

    public function ventadetalle6($idventa)
    {
        $sql = "SELECT 
                d.iddetalle_ingreso,
                d.idingreso,
                a.nombre as articulo,
                a.codigo,
                d.cantidad,
                art.idarticulo,
                art.stocksucursal,
                d.precio_compra,
                d.presentacion,
                d.totalcantidadpresentacion,
                ROUND(d.totalcantidadpresentacion * (d.precio_compra - ((d.precio_compra * d.descuento_porcentaje) / 100)), 2) AS subtotal,
                s.nombre as nombre_sucursal
                FROM detalle_ingreso d 
                INNER JOIN articulo a ON d.idarticulo=a.idarticulo  
                INNER JOIN articuloxsucursal art ON art.idarticulo=d.idarticulo
                INNER JOIN ingreso i ON i.idingreso=d.idingreso
                INNER JOIN usuario u ON u.idusuario=i.idusuario
                inner join sucursal s on s.idsucursal=d.idsucursalDestino
                WHERE d.idingreso='$idventa'  AND art.idsucursal='" . $_SESSION["idsucursal"] . "' 
                ORDER by d.iddetalle_ingreso asc";
        return ejecutarConsulta($sql);
    }

    public function ventadetalle6_ordencompra($idventa)
    {
        $sql = "SELECT 
                d.iddetalle_orden_compra,
                d.idorden_compra,
                a.nombre as articulo,
                a.codigo,
                d.cantidad,
                art.idarticulo,
                art.stocksucursal,
                d.precio_compra,
                d.presentacion,
                d.totalcantidadpresentacion,
                ROUND(d.totalcantidadpresentacion * (d.precio_compra - ((d.precio_compra * d.descuento_porcentaje) / 100)), 2) AS subtotal,
                (select s1.nombre from sucursal s1 where s1.idsucursal=d.idsucursalDestino limit 1) as nombre_sucursal
                FROM detalle_orden_compra d 
                INNER JOIN articulo a ON d.idarticulo=a.idarticulo  
                INNER JOIN articuloxsucursal art ON art.idarticulo=d.idarticulo
                INNER JOIN orden_compra i ON i.idorden_compra=d.idorden_compra
                INNER JOIN usuario u ON u.idusuario=i.idusuario
                WHERE d.idorden_compra='$idventa'  AND art.idsucursal='" . $_SESSION["idsucursal"] . "' 
                ORDER by d.iddetalle_orden_compra asc";
        return ejecutarConsulta($sql);
    }

    public function notadebitodetalle($idnota_debito)
    {
        $sql = "SELECT 
                d.iddetalle_nota_debito,
                d.idnota_debito,
                a.nombre as articulo,
                a.codigo,
                d.cantidad,
                art.idarticulo,
                art.stocksucursal,
                d.precio_compra,
                ROUND(d.cantidad * (d.precio_compra - ((d.precio_compra * d.descuento_porcentaje) / 100)), 2) AS subtotal
                FROM detalle_nota_debito d 
                INNER JOIN articulo a ON d.idarticulo=a.idarticulo  
                INNER JOIN articuloxsucursal art ON art.idarticulo=d.idarticulo
                INNER JOIN nota_debito i ON i.idnota_debito=d.idnota_debito
                INNER JOIN usuario u ON u.idusuario=i.idusuario
                WHERE d.idnota_debito='$idnota_debito'  AND art.idsucursal='" . $_SESSION["idsucursal"] . "' ";
        return ejecutarConsulta($sql);
    }

    public function contrato_cliente($idpersona)
    {
        $sql = "SELECT
            p.nombre AS nombre_cliente,
            p.codigo_cliente,
            p.direccion,
            p.trabajo,
            p.num_documento,
            p.tipo_documento,
            p.telefono,
            s.nombre AS nombre_sector,
            p2.nombre AS nombre_fiador,
            p2.trabajo AS trabajo_fiador,
            p2.direccion AS direccion_fiador,
            p2.telefono AS telefono_fiador,
            p2.tipo_documento AS tipo_documento_fiador,
            p2.num_documento AS num_documento_fiador
        FROM persona p
        LEFT JOIN sector s ON p.idsector = s.idsector
        LEFT JOIN persona p2 ON p.idfiador = p2.idpersona
        WHERE p.idpersona = '$idpersona'";
        return ejecutarConsulta($sql);
    }

    public function mostrarTaller($idcotizacion)
    {
        $sql = "SELECT 
                iv.idingreso_vehiculo,
                iv.no_placa, 
                iv.no_chasis, 
                iv.serie, 
                iv.no_motor, 
                iv.modelo, 
                iv.km,
                iv.trabajos_detalle,
                iv.observaciones_adicionales,
                iv.fechaCreacion AS fecha,
                iv.estado,
                iv.idcliente,
                iv.imagen1,
                iv.imagen2,
                iv.imagen3,
                iv.imagen4,
                iv.imagen5,
                iv.imagen6,
                iv.imagen7,
                iv.imagen8,
                iv.descripcion1,
                iv.descripcion2,
                iv.descripcion3,
                iv.descripcion4,
                iv.descripcion5,
                iv.descripcion6,
                iv.descripcion7,
                iv.descripcion8,
                iv.facturado,
                p.nombre as nombre_cliente,
                p.codigo_cliente,
                p.num_documento as nit,
                p.telefono as telefono_cliente,
                p.direccion as direccion_cliente,
                p.email as correo_cliente,
                p.tipo_documento as tipo_documento_cliente
            FROM ingreso_vehiculo iv
            INNER JOIN persona p ON p.idpersona = iv.idcliente
            WHERE iv.idingreso_vehiculo = '$idcotizacion'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function detalle_taller($cotizacion)
    {
        $sqldetalle = "SELECT 
                dive.iddetalle_ingreso_vehiculo,
                dive.idingreso_vehiculo,
                dive.idarticulo,
                dive.cantidad,
                a.nombre,
                asu.idsucursal,
                asu.stocksucursal as stock,
                asu.stockminimo,
                asu.precio_compra,
                asu.precio_ventaNocturno,
                asu.precio_descuento,
                asu.precio_rango1,
                asu.precio_rango2,
                asu.precio_rango3,
                asu.stock_unidad,
                asu.precio_unidad,
                asu.stock_blister,
                asu.precio_blister,
                asu.stock_caja,
                asu.precio_caja,
                asu.stock_fardo,
                asu.precio_fardo,
                asu.stock_sacos,
                asu.precio_sacos,
                asu.stock_paquete,
                asu.precio_paquete,
                asu.precio_rango1,
                asu.precio_rango2,
                asu.precio_rango3,
                asu.precio_rango1_Dos, 
                asu.precio_venta,
                asu.precio_rango2_Dos,
                asu.precio_rango3_Dos,
                asu.precio_rango1_Mecanico,
                asu.precio_rango2_MecanicoDos,
                asu.precio_rango3_MecanicoTres,
                asu.precio_rango1_Distribuidor,
                asu.precio_rango2_DistribuidorDos,
                asu.precio_rango3_DistribuidorTres,
                asu.precio_rango1_Mayorista,
                asu.precio_rango2_MayoristaDos,
                asu.precio_rango3_MayoristaTres,
                asu.condicion,
                asu.precio_activado,
                asu.descuento_porcentaje
            FROM detalle_ingreso_vehiculo dive
            INNER JOIN articulo a ON dive.idarticulo = a.idarticulo 
            INNER JOIN articuloxsucursal asu ON asu.idarticulo = a.idarticulo
            WHERE asu.idsucursal = '" . $_SESSION["idsucursal"] . "'
            AND dive.idingreso_vehiculo = '$cotizacion'
            ORDER BY dive.iddetalle_ingreso_vehiculo DESC";

        $rspta = ejecutarConsulta($sqldetalle);
        $rows = array();
        while ($reg = $rspta->fetch_object()) {
            $rows[] = $reg;
        }
        return $rows;
    }

    public function ventadetalleExtra($idventa)
    {
        $sql = "SELECT 
                    de.iddetalle_ventaExtra,
                    de.idventa,
                    de.idarticulo AS idextra,
                    de.cantidad AS cantidad_extra,
                    de.precio_venta AS precio_extra,
                    de.idproducto AS idproducto_extra,
                    a.nombre AS articulo,
                    COALESCE(a.codigo, '') AS codigo
                FROM detalle_ventaExtra de
                INNER JOIN articulo a ON de.idarticulo = a.idarticulo
                WHERE de.idventa = '$idventa'";

        return ejecutarConsulta($sql);
    }

    public function cotizaciondetalleExtra($idcotizacion)
    {
        $sql = "SELECT 
                    de.iddetalle_cotizacionExtra,
                    de.idcotizacion,
                    de.idarticulo AS idextra,
                    de.cantidad AS cantidad_extra,
                    de.precio_venta AS precio_extra,
                    de.idproducto AS idproducto_extra,
                    a.nombre AS articulo,
                    COALESCE(a.codigo, '') AS codigo,
                    dp.tipo_item
                FROM detalle_cotizacionExtra de
                INNER JOIN articulo a ON de.idarticulo = a.idarticulo
                INNER JOIN detalle_produccion dp ON de.idarticulo = dp.idarticulo
                WHERE de.idcotizacion = '$idcotizacion'";
        return ejecutarConsulta($sql);
    }

    public function venta_servicio_rpt($idventa){
        $sql = "SELECT
            v.idventas_servicios,
            v.idventa,
            v.idusuario,
            v.idsucursal,
            v.descripcion_comentario,
            v.fecha_hora,
            v.ip_instalacion,
            v.estado_servicio_venta,
            u.nombre AS usuario_operacion,
            s.nombre AS sucursal_operacion
        FROM ventas_servicios v
        INNER JOIN usuario u ON v.idusuario = u.idusuario
        INNER JOIN sucursal s ON v.idsucursal = s.idsucursal
        WHERE v.idventa = '$idventa'";
        return ejecutarConsulta($sql);
    }

}
?>