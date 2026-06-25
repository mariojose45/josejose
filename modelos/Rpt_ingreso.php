<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class RptIngreso
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    public function rptingresoxfechas($fecha_inicio,$fecha_fin)
    {
        $sql="SELECT 
                i.idingreso,
                i.idproveedor,
                p.nombre AS proveedor,
                i.idusuario,
                u.nombre AS usuario,
                i.tipo_comprobante,
                i.serie_comprobante,
                i.num_comprobante,
                DATE(i.fecha_hora) AS fechahora,
                i.impuesto,
                i.total_compra,
                i.estado,
                i.forma_pago,
                i.dias_credito,
                DATE(i.fecha_hora_pago_credito) AS fechahorapagocredito,
                i.valor_pagar,
                i.saldo_ingreso,
                i.tipo_pago,
                i.no_cheque,
                DATE(i.fecha_hora_generacion_pago) AS fechahorageneracionpago,
                i.tipo_banco,
                i.numero_boleta,
                i.recibo_caja_numero,
                i.direccion_entrega_orden_compra,
                DATE(i.fecha_entrega_orden_compra) AS fechaentrgaordencompra,
                i.observacion_orden_compra,
                i.usuarui_modificacion,
                i.fecha_modificacion,
                i.motivo_modificacion
                FROM ingreso i
                INNER JOIN persona p ON i.idproveedor=p.idpersona
                INNER JOIN usuario u ON i.idusuario=u.idusuario
         WHERE DATE(i.fecha_hora)>='$fecha_inicio' AND DATE(i.fecha_hora)<='$fecha_fin'";
        return ejecutarConsulta($sql);      
    }
}
 
?>