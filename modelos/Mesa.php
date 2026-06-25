<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Mesas
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($nombre,$descripcion)
    {
        $sql="INSERT INTO mesa (nombre,descripcion,condicion)
        VALUES ('$nombre','$descripcion','1')";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para editar registros
    public function editar($idmesa,$nombre,$descripcion)
    {
        $sql="UPDATE mesa SET nombre='$nombre',descripcion='$descripcion' WHERE idmesa='$idmesa'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para desactivar categorías
    public function desactivar($idmesa)
    {
        $sql="UPDATE mesa SET condicion='0' WHERE idmesa='$idmesa'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar categorías
    public function activar($idmesa)
    {
        $sql="UPDATE mesa SET condicion='1' WHERE idmesa='$idmesa'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idmesa)
    {
        $sql="SELECT * FROM mesa WHERE idmesa='$idmesa'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT * FROM mesa";
        return ejecutarConsulta($sql);      
    }

}
 
?>