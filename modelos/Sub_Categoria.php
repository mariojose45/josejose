<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class SubCategoria
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($nombre,$descripcion)
    {
        $sql="INSERT INTO subcategoria (nombre,descripcion,condicion)
        VALUES ('$nombre','$descripcion','1')";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para editar registros
    public function editar($idsubcategoria,$nombre,$descripcion)
    {
        $sql="UPDATE subcategoria SET nombre='$nombre',descripcion='$descripcion' WHERE idsubcategoria='$idsubcategoria'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para desactivar categorías
    public function desactivar($idsubcategoria)
    {
        $sql="UPDATE subcategoria SET condicion='0' WHERE idsubcategoria='$idsubcategoria'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar categorías
    public function activar($idsubcategoria)
    {
        $sql="UPDATE subcategoria SET condicion='1' WHERE idsubcategoria='$idsubcategoria'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idsubcategoria)
    {
        $sql="SELECT * FROM subcategoria WHERE idsubcategoria='$idsubcategoria'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT * FROM subcategoria WHERE condicion = '1'";
        return ejecutarConsulta($sql);      
    }
    //Implementar un método para listar los registros y mostrar en el select
    public function select($idcategoria)
    {
        $sql="SELECT 
                s.idsubcategoria,
                s.nombre 
            FROM asociar_subcategoriadetalle a
            INNER JOIN subcategoria s ON s.idsubcategoria=a.idsubcategoria
            WHERE a.idcategoria='$idcategoria'";
        return ejecutarConsulta($sql);      
    }


    
}
 
?>