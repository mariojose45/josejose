<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Consultas
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    public function cuentasxfecha($fecha_inicio,$fecha_fin,$idcuenta)  
    {
        $sql="SELECT 
                o.idoperaciones_entre_bancos,
                o.idcheque,
                DATE(o.fecha_hora_operacion_cheque) as feche_cheque,
                o.valor_cheque,
                o.iddeposito,
                DATE(o.fecha_hora_deposito) as fecha_deposito,
                o.valor_deposito,
                o.idcuenta,
                c.num_cta,
                c.cta_nombre,
                c.saldo_inicial,
                o.saldo_cuenta, 
                o.saldo_final_cuenta,
                DATE(o.fecha_hora) as fecha,
                o.condicion
                FROM operaciones_entre_bancos o
                INNER JOIN cuenta c ON c.idcuenta=o.idcuenta
                WHERE DATE(o.fecha_hora)>='$fecha_inicio' AND DATE(o.fecha_hora)<='$fecha_fin' and o.idcuenta='$idcuenta'";
        return ejecutarConsulta($sql);      
    }
 

       
}
 
?>