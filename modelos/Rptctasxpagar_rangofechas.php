<?php

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Rptctaxpagar
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    public function comprasfecha($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT 
                cp.idcta_pagar,
                cp.idingreso,
                (SELECT i.total_compra FROM ingreso i where i.idingreso=cp.idingreso) as total_compra,
                cp.idusuario,
                u.nombre as usuario,
                cp.idcliente,
                p.nombre as proveedor,
                cp.idcuenta, 
                cp.valor_pagar,
                cp.saldo_ingreso,
                cp.tipo_pago,
                cp.tipo_banco,
                cp.numero_boleta,
                cp.recibo_caja_numero,
                cp.no_cheque,
                DATE(cp.fecha_hora_generacion_pago) as fechahorageneracionpago,
                cp.desp_cheque,
                cp.estado
                FROM cta_pagar cp
                INNER JOIN usuario u ON u.idusuario=cp.idusuario
                INNER JOIN persona p ON p.idpersona=cp.idcliente
                WHERE DATE(cp.fecha_hora_generacion_pago)>='$fecha_inicio' AND DATE(cp.fecha_hora_generacion_pago)<='$fecha_fin'";
        return ejecutarConsulta($sql);
    }



}

?>