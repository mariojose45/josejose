<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class CuentasXcobrar
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($nombre,$descripcion)
    {
        $sql="INSERT INTO categoria (nombre,descripcion,condicion)
        VALUES ('$nombre','$descripcion','1')";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para editar registros
    public function editar($idventa,$tipo_pago,$numero_boleta,$idusuario,$fecha_hora)
    {
        $sql="UPDATE venta SET tipo_pago='$tipo_pago',numero_boleta='$numero_boleta',usuariopago='$idusuario',fechapago='$fecha_hora' WHERE idventa='$idventa'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para desactivar categorías
    public function desactivar($idcategoria)
    {
        $sql="UPDATE categoria SET condicion='0' WHERE idcategoria='$idcategoria'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar categorías
    public function activar($idcategoria)
    {
        $sql="UPDATE categoria SET condicion='1' WHERE idcategoria='$idcategoria'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idventa)
    {
        $sql="SELECT v.idventa,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.condicion, v.estado,p.nombre as nombre_cliente, p.telefono as telefono_cliente FROM venta v INNER JOIN persona p  ON v.idcliente=p.idpersona WHERE v.idventa='$idventa'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT v.idventa,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.condicion, v.estado,p.nombre as nombre_cliente, p.telefono as telefono_cliente, v.estadopago FROM venta v INNER JOIN persona p  ON v.idcliente=p.idpersona ";
        return ejecutarConsulta($sql);      
    }
    //Implementar un método para listar los registros y mostrar en el select
    public function select()
    {
        $sql="SELECT * FROM categoria where condicion=1";
        return ejecutarConsulta($sql);      
    }
}
 
?>