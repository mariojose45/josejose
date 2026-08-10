<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

if (!isset($_SESSION["nombre"])) {
    die('No tienes acceso favor volver a logearte');
}

class Salidas_inventario
{
    //Implementamos nuestro constructor
    public function __construct() {}

    //Implementamos un método para insertar registros
    public function insertar($idusuario_salida, $descripcion_salida_product, $tipo_operacion, $datosArticulos)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        $sql = "INSERT INTO venta_salida (idusuario,fecha_hora,observacion_credito,
        idusuario_creacion,idsucursal,tipo_operacion)
        VALUES ('$idusuario_salida',NOW(),'$descripcion_salida_product',
        '" . $_SESSION["idusuario"] . "','" . $_SESSION["idsucursal"] . "','$tipo_operacion')";
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

                $sql_detalle = "INSERT INTO detalle_venta_salida(idventa_salida,idarticulo,cantidad,descripcion_detalle,cantidadpresentacion,totalcantidadpresentacion,
                presen) 
                VALUES ('$idtraladosucursalnew','$idarticulo','$cantidad','$descripcion_detalle','$cantidadpresentacion','$totalcantidadpresentacion',
                '$presentacion')";
                ejecutarConsulta($sql_detalle);

                $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idventa_salida,
                cantidad_ventas_salidas,fecha_horaCreacion,idarticulo,idusuario,idsucursal,nota) 
                VALUES ('$idtraladosucursalnew','$totalcantidadpresentacion','$fechaHora',
                '$idarticulo','" . $_SESSION["idusuario"] . "','" . $_SESSION["idsucursal"] . "','Ajuste Inv - $tipo_operacion')";
                ejecutarConsulta($sql_detalleoperaciones);
                //print_r($sql_detalleoperaciones);

                if ($tipo_operacion == 'Salida') {
                    $sqlArticuloStock = "UPDATE articuloxsucursal SET 
                    stocksucursal = stocksucursal - " . $totalcantidadpresentacion . "
                    WHERE idarticulo ='$idarticulo'  and idsucursal='" . $_SESSION["idsucursal"] . "' ";
                    ejecutarConsulta($sqlArticuloStock);
                } else {
                    $sqlArticuloStock = "UPDATE articuloxsucursal SET 
                    stocksucursal = stocksucursal + " . $totalcantidadpresentacion . "
                    WHERE idarticulo ='$idarticulo'  and idsucursal='" . $_SESSION["idsucursal"] . "' ";
                    ejecutarConsulta($sqlArticuloStock);
                }
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
                    WHERE asu.idarticulo='$idarticulo'  and asu.idsucursal='" . $_SESSION["idsucursal"] . "' ";
                $Articulo1 = ejecutarConsultaSimpleFila($sqlArticulo1);
                $stocksucursal_anterior = $Articulo1["stocksucursal"];

                $sql_detalle = "INSERT INTO detalle_traslado_sucursal(idtraladosucursal, idarticulo,cantidad,descripcion_detalle,
                idsucursalorigen,idsucursaldestino,precio_venta,cantidadpresentacion,
                totalcantidadpresentacion,presentacion) 
                VALUES ('$idtraladosucursal', '$idarticulo','$cantidad','$descripcion_detalle','$idsucursalorigen',
                '$idsucursal','$precio_venta','$cantidadpresentacion','$totalcantidadpresentacion',
                '$presentacion')";
                ejecutarConsulta($sql_detalle) or $sw = false;


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
                                                                                '" . $_SESSION["idsucursal"] . "')";
                ejecutarConsulta($sql_detalleoperaciones);
            }
        }
        return $idtraladosucursal;
    }


    //Implementamos un método para anular la venta
    public function anular($idventa_salida)
    {
        $sqlVenta = "SELECT tipo_operacion FROM venta_salida WHERE idventa_salida='$idventa_salida'";
        $ventaResult = ejecutarConsultaSimpleFila($sqlVenta);
        $tipo_operacion = isset($ventaResult['tipo_operacion']) ? $ventaResult['tipo_operacion'] : 'Salida'; // Default Salida por retrocompatibilidad

        $sql = "UPDATE venta_salida SET estado='Anulado',condicion='0' WHERE idventa_salida='$idventa_salida'";
        ejecutarConsulta($sql);

        $sqlDetalleIngresoElimminar = "UPDATE operaciones_compras_ventas SET estado='Anulado' where idventa_salida=" . $idventa_salida . "";
        ejecutarConsulta($sqlDetalleIngresoElimminar);

        $sqlDetalleingreso = "SELECT * FROM detalle_venta_salida WHERE idventa_salida='$idventa_salida'";
        $Detalle = ejecutarConsulta($sqlDetalleingreso);

        while ($reg = $Detalle->fetch_object()) {
            if ($tipo_operacion == 'Salida') {
                $updateArticuloDetalle = "UPDATE articuloxsucursal SET 
                                                stocksucursal = stocksucursal + " . $reg->totalcantidadpresentacion . "
                                            WHERE idarticulo = " . $reg->idarticulo . " 
                                            AND idsucursal = '" . $_SESSION["idsucursal"] . "'";
            } else {
                $updateArticuloDetalle = "UPDATE articuloxsucursal SET 
                                                stocksucursal = stocksucursal - " . $reg->totalcantidadpresentacion . "
                                            WHERE idarticulo = " . $reg->idarticulo . " 
                                            AND idsucursal = '" . $_SESSION["idsucursal"] . "'";
            }
            ejecutarConsulta($updateArticuloDetalle);
        }

        return ($sql);
    }




    //Implementar un método para listar los registros
    public function listar($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT
            vs.idventa_salida,
            vs.idusuario,
            vs.idusuario_creacion,
            vs.observacion_credito,
            u1.nombre AS us_creacion,
            u2.nombre AS us_salida,
            vs.estado,
	        vs.condicion,
            vs.tipo_operacion,
            DATE(vs.fecha_hora) AS fecha
        FROM venta_salida vs
        INNER JOIN usuario u1 ON vs.idusuario = u1.idusuario
        INNER JOIN usuario u2 ON vs.idusuario_creacion = u2.idusuario
        WHERE DATE(vs.fecha_hora)>='$fecha_inicio' AND DATE(vs.fecha_hora)<='$fecha_fin' and vs.idsucursal='" . $_SESSION["idsucursal"] . "'";
        return ejecutarConsulta($sql);
    }

    public function salidaprosucursalcabecera($idventa_salida)
    {
        $sql = "SELECT 
                v.idventa_salida,
                v.idcliente,
                v.idusuario,
                u.nombre AS usuario,
                v.idusuario_creacion,
                (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=v.idusuario_creacion limit 1) AS user_creacion,
                v.idsucursal,
                date(v.fecha_hora) AS fecha,
                v.observacion_credito,
                v.condicion,
                v.estado,
                s.nombre as sucursal_nombre,
                v.tipo_operacion,
            s.imagen as sucursal_imagen,
            s.direccion as sucursal_direccion,
            s.telefono as sucursal_telefono,
            s.email as sucursal_email,
            s.nit as sucursal_nit,
            s.nombre_fel,
            c.empresadesarrollo
            FROM venta_salida v              
            INNER JOIN usuario u ON u.idusuario=v.idusuario
            INNER JOIN sucursal s ON s.idsucursal=v.idsucursal
            inner join certificador c on c.idsucursal=v.idsucursal
            WHERE v.idventa_salida='$idventa_salida' and c.condicion='1'";
        return ejecutarConsulta($sql);
    }

    public function salidaprosucursaltadetalle($idventa_salida)
    {
        $sql = "SELECT 
                d.iddetalle_venta_salida,
                d.idventa_salida,
                d.idarticulo,
                d.cantidad,
                d.precio_venta,
                d.precio_compra,
                d.descuento,
                d.descripcion_detalle,
                d.stockinven,
                d.subtotaldes1,
                d.precio_ventaSistema,
                d.precio_ventaSistema2,
                d.subtotal1,
                d.cantidadpresentacion,
                d.totalcantidadpresentacion,
                IF(d.presen IS NULL OR d.presen = '', '.', d.presen) AS presen,
                d.precio_recargo,
                d.q_ref,
                d.precio_recargoPV,
                d.precio_recargoQRef,
                d.valor_descuentoGeneralLista,
                a.codigo,
                a.nombre AS articulo
            FROM detalle_venta_salida d
            INNER JOIN venta_salida v ON v.idventa_salida=d.idventa_salida
            INNER JOIN articulo a ON a.idarticulo=d.idarticulo
            WHERE v.idventa_salida='$idventa_salida'";
        return ejecutarConsulta($sql);
    }

    public function selectSucursal()
    {
        $sql = "SELECT * FROM sucursal WHERE condicion='1' and idsucursal<>'" . $_SESSION["idsucursal"] . "'";
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
            aa.stock_unidad,
            aa.precio_unidad,
            aa.stock_blister,
            aa.precio_blister,
            aa.stock_caja,
            aa.precio_caja,
            aa.stock_fardo,
            aa.precio_fardo,
            aa.stock_sacos,
            aa.precio_sacos,
            aa.stock_paquete,
            aa.precio_paquete,
            aa.stocksucursal
        FROM detalle_traslado_sucursal d 
        INNER JOIN articulo a ON d.idarticulo = a.idarticulo
        inner JOIN articuloxsucursal aa ON d.idarticulo = aa.idarticulo
        WHERE d.idtraladosucursal = '$idtraladosucursal' 
        AND aa.idsucursal = '" . $_SESSION["idsucursal"] . "'";

        $rspta = ejecutarConsulta($sqldetalle);
        $rows = array();
        while ($reg = $rspta->fetch_object()) {
            $rows[] = $reg;
        }
        return $rows;
    }

    public function selecUsuarioSalida($idusuario_excluir)
    {
        $sql = "SELECT * FROM usuario WHERE condicion = 1 ";
        return ejecutarConsulta($sql);
    }
}
