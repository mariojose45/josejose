<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 


 
Class Marca_vehiculo
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($nombre,$descripcion)
    {
        $sql="INSERT INTO marca (nombre,descripcion,condicion)
        VALUES ('$nombre','$descripcion','1')";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para editar registros
    public function editar($idmarca,$nombre,$descripcion)
    {
        $sql="UPDATE marca SET nombre='$nombre',descripcion='$descripcion'
        WHERE idmarca='$idmarca'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para desactivar categorías
    public function desactivar($idmarca)
    {
        $sql="UPDATE marca SET condicion='0' WHERE idmarca='$idmarca'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar categorías
    public function activar($idmarca)
    {
        $sql="UPDATE marca SET condicion='1' WHERE idmarca='$idmarca'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idmarca)
    {
        $sql="SELECT * FROM marca WHERE idmarca='$idmarca'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT * FROM marca";
        return ejecutarConsulta($sql);      
    }
    //Implementar un método para listar los registros y mostrar en el select
    public function select()
    {
        $sql="SELECT * FROM marca where condicion=1";
        return ejecutarConsulta($sql);      
    }
    public function selectmarcaSubmarca()
    {
        $sql="SELECT c.*
                FROM marca c
                WHERE c.idmarca NOT IN (
                    SELECT a.idmarca
                    FROM asociar_submarca a
                )
                AND c.condicion = 1;  -- opcional, si solo quieres activas
                ";
        return ejecutarConsulta($sql);      
    }
}
 
?>