<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Correla
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($num_cotizacion,$num_envio,$idsucursal)
    {
        $sql="INSERT INTO add_correlativo (num_cotizacion,num_envio,idsucursal,condicion)
        VALUES ('$num_cotizacion','$num_envio','$idsucursal','1')";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para editar registros
    public function editar($idcorrelativo,$num_cotizacion,$num_envio,$idsucursal)
    {
        $sql="UPDATE add_correlativo SET num_cotizacion='$num_cotizacion',num_envio='$num_envio',idsucursal='$idsucursal' WHERE idcorrelativo='$idcorrelativo'";
        return ejecutarConsulta($sql);
    }
 

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idcorrelativo)
    {
        $sql="SELECT * FROM add_correlativo  WHERE idcorrelativo='$idcorrelativo'";
        
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT
ac.idcorrelativo,
ac.num_cotizacion,
ac.num_envio,
s.nombre AS sucursal_su,
ac.condicion
 FROM add_correlativo ac
INNER JOIN sucursal s ON s.idsucursal=ac.idsucursal";
        return ejecutarConsulta($sql);      
    }

}
 
?>