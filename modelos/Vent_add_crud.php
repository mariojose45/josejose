<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class VentaAdmin
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar()
    {

    }
 
    //Implementamos un método para editar registros
    public function editar($idventa,$idcliente,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$total_venta,$total_ventades,$estado,$condicion)
    {
        $sql="UPDATE venta SET idcliente='$idcliente',idusuario='$idusuario',tipo_comprobante='$tipo_comprobante',serie_comprobante='$serie_comprobante',num_comprobante='$num_comprobante',fecha_hora='$fecha_hora',total_venta='$total_venta',total_ventades='$total_ventades',estado='$estado',condicion='$condicion' WHERE idventa='$idventa'";
        return ejecutarConsulta($sql);
    }
 

 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idventa)
    {
        $sql="SELECT 
                v.idventa,
                v.idcliente,
                v.idusuario,
                v.tipo_comprobante,
                v.serie_comprobante,
                v.num_comprobante,
                v.fecha_hora,
                v.impuesto,
                v.total_venta,
                v.total_ventades,
                v.estado,
                v.condicion,
                v.tipo_pago,
                v.numero_boleta,
                v.usuariopago,
                v.fechapago,
                v.estadopago,
                v.forma_pago,
                v.dias_credito,
                v.fecha_hora_cobro,
                v.tipo_banco,
                v.recibo_caja_numero,
                v.idcuenta,
                v.fecha_hora_siguiente_pago,
                v.observacion_credito,
                v.total_abono,
                v.saldo_venta
                FROM venta v 
                WHERE v.idventa='$idventa'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT * FROM venta ORDER BY idventa DESC LIMIT 100";
        return ejecutarConsulta($sql);      
    }

}
 
?>