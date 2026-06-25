<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 


 
Class Empresa_in
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($nombre,$descripcion)
    {
        $sql="INSERT INTO empresa (nombre,descripcion,condicion)
        VALUES ('$nombre','$descripcion','1')";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para editar registros
    public function editar($idempresa,$nombre,$descripcion)
    {
        $sql="UPDATE empresa SET nombre='$nombre',descripcion='$descripcion'
        WHERE idempresa='$idempresa'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para desactivar categorías
    public function desactivar($idempresa)
    {
        $sql="UPDATE empresa SET condicion='0' WHERE idempresa='$idempresa'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar categorías
    public function activar($idempresa)
    {
        $sql="UPDATE empresa SET condicion='1' WHERE idempresa='$idempresa'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idempresa)
    {
        $sql="SELECT * FROM empresa WHERE idempresa='$idempresa'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT * FROM empresa";
        return ejecutarConsulta($sql);      
    }
    //Implementar un método para listar los registros y mostrar en el select
    public function select()
    {
        $sql="SELECT * FROM empresa where condicion=1";
        return ejecutarConsulta($sql);      
    }

}
 
?>