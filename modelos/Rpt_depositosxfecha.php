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
 
    public function depositosxfecha($fecha_inicio,$fecha_fin)  
    {
        $sql="SELECT 
                d.iddeposito,
                d.idusuario,
                u.nombre as usuario,
                d.idcliente,
                p.nombre as cliente,
                d.idcuenta,
                c.num_cta,
                c.cta_nombre,
                DATE(d.fecha_hora) as fecha,
                d.tipo_banco,
                d.nombre_agencia,
                d.deposito_no,
                d.valor_deposito,
                d.descripcion,
                d.condicion
                FROM deposito d 
                INNER JOIN usuario u ON u.idusuario=d.idusuario
                INNER JOIN persona p ON p.idpersona=d.idcliente
                INNER JOIN cuenta c ON c.idcuenta=d.idcuenta
                WHERE DATE(d.fecha_hora)>='$fecha_inicio' AND DATE(d.fecha_hora)<='$fecha_fin'";
        return ejecutarConsulta($sql);      
    }
 

       
}
 
?>