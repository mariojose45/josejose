<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class VentasxfechaDetalle
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    public function ventasdetalle($fecha_inicio,$fecha_fin,$idsucursal)
    {
        $sql="SELECT 
                d.iddetalle_venta,
                d.idventa,
                d.idarticulo,
                d.cantidad,
                d.precio_venta,
                d.descuento,
                d.descripcion_detalle,
                d.stockinven,
                d.subtotaldes1,
                d.precio_ventaSistema,
                d.subtotal1,
                a.codigo,
                a.nombre AS articulo,
                date(v.fecha_hora) as fecha,
                v.num_comprobante,
                v.valor_tarjeta,
                v.estado,
                asu.aplica_impuestos,
                asu.producto_consignacion
             FROM detalle_venta d
             INNER JOIN venta v ON v.idventa=d.idventa
             INNER JOIN articulo a ON a.idarticulo=d.idarticulo
             inner join articuloxsucursal asu on asu.idarticulo=d.idarticulo
        WHERE DATE(v.fecha_hora)>='$fecha_inicio' 
        AND DATE(v.fecha_hora)<='$fecha_fin' 
        and v.idsucursal='$idsucursal' and asu.idsucursal='$idsucursal' ";
        return ejecutarConsulta($sql);      
    }
       
}
 
?>