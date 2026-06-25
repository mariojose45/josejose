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

        $sql = "INSERT INTO traslado_sucursal (idsucursaldestino,idusuario,idsucursalorigen,fecha_hora,descripcion_salida_producto,
        estado,total_venta)
        VALUES ('$idsucursal','$idusuario','$idsucursalorigen','$fecha_hora','$descripcion_salida_producto',
        'SALIDA PRODUCTO','$total_venta_r')";
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

                $sql_detalle = "INSERT INTO detalle_traslado_sucursal(idtraladosucursal, idarticulo,cantidad,descripcion_detalle,
                idsucursalorigen,idsucursaldestino,precio_venta,cantidadpresentacion,
                totalcantidadpresentacion,presentacion) 
                VALUES ('$idtraladosucursalnew', '$idarticulo','$cantidad','$descripcion_detalle','$idsucursalorigen',
                '$idsucursal','$precio_venta','$cantidadpresentacion','$totalcantidadpresentacion',
                '$presentacion')";
                ejecutarConsulta($sql_detalle) or $sw = false;


                $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idingreso,idventa,idtraladosucursal,
                                                                                idtraladosucursal_entrada,iddevolucion,cantidad_compras,
                                                                                cantidad_ventas,cantidad_entrada,cantidad_devolucion,
                                                                                cantidad_salida,stock_inventario,fecha_horaCreacion,
                                                                                idarticulo,idusuario,idsucursal) 
                                                                        VALUES ('0','0','$idtraladosucursalnew','0','0',
                                                                                '0','0','0','0','$totalcantidadpresentacion',
                                                                                '$stocksucursal_anterior','$fechaHora','$idarticulo',
                                                                                '$idusuario','$idsucursalorigen')";
                ejecutarConsulta($sql_detalleoperaciones);
            }
        }

        $this->registrarAuditoria($idtraladosucursalnew, 'CREACIÓN', 'Se creó una nueva salida', $idusuario, $datosArticulos);

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
                    WHERE asu.idarticulo='$idarticulo'  and asu.idsucursal='$idsucursalorigen' ";
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
        $sql = "UPDATE traslado_sucursal SET estado='Anulado' WHERE idtraladosucursal='$idtraladosucursal'";
        ejecutarConsulta($sql);

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
