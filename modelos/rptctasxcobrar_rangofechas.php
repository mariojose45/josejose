<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Rptctaxcobrar
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    public function comprasfecha($fecha_inicio,$fecha_fin) 
    {
        $sql="SELECT 
                cc.idcta_cobrar,
                cc.idventa,
                cc.idcliente,
                p.nombre as cliente,
                cc.total_venta,
                cc.total_abono,
                cc.saldo_venta,
                cc.tipo_pago,
                cc.numero_boleta,
                cc.recibo_caja_numero,
                cc.descripcion,
                cc.condicion,
                DATE(cc.fechapago) as fechapago,
                cc.idusuario,
                u.nombre as usuario
                from cta_cobrar cc 
                INNER JOIN persona p ON p.idpersona=cc.idcliente
                INNER JOIN usuario u ON u.idusuario=cc.idusuario
                WHERE DATE(cc.fechapago)>='$fecha_inicio' AND DATE(cc.fechapago)<='$fecha_fin'";
        return ejecutarConsulta($sql);      
    }

 
       
}
 
?>