<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Colores
{
    //Implementamos nuestro constructor
    public function __construct()
    {
  
    }
 
    //Implementamos un método para insertar registros
    public function insertar($nombre,$descripcion)
    {
        $sql="INSERT INTO color (nombre,descripcion,condicion)
        VALUES ('$nombre','$descripcion','1')";
        return ejecutarConsulta($sql);
    }

    public function guardaryeditarModal($nombre_color,$descripcion_color)
    {
        $sql="INSERT INTO color (nombre,descripcion,condicion)
        VALUES ('$nombre_color','$descripcion_color','1')";
        $new=ejecutarConsulta_retornarID($sql);
        $option='<option value="'.$new.'" selected>'.$nombre_color.'</option>'; 
        return $option;
    }      
 
    //Implementamos un método para editar registros
    public function editar($idcolor,$nombre,$descripcion)
    {
        $sql="UPDATE color SET nombre='$nombre',descripcion='$descripcion' WHERE idcolor='$idcolor'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para desactivar categorías
    public function desactivar($idcolor)
    {
        $sql="UPDATE color SET condicion='0' WHERE idcolor='$idcolor'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar categorías
    public function activar($idcolor)
    {
        $sql="UPDATE color SET condicion='1' WHERE idcolor='$idcolor'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idcolor)
    {
        $sql="SELECT * FROM color WHERE idcolor='$idcolor'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT * FROM color";
        return ejecutarConsulta($sql);      
    }
    //Implementar un método para listar los registros y mostrar en el select
    public function select()
    {
        $sql="SELECT * FROM color where condicion=1";
        return ejecutarConsulta($sql);      
    }
}
 
?>