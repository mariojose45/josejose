<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class TipoEquipo
{
    //Implementamos nuestro constructor
    public function __construct()
    {
   
    }
 
    //Implementamos un método para insertar registros
    public function insertar($nombre,$descripcion)
    {
        $sql="INSERT INTO tipo_equipo (nombre,descripcion,condicion)
        VALUES ('$nombre','$descripcion','1')";
        return ejecutarConsulta($sql);
    }

    public function guardaryeditarModal($nombre_tipoequipo,$descripcion_tipoequipo)
    {
        $sql="INSERT INTO tipo_equipo (nombre,descripcion,condicion)
        VALUES ('$nombre_tipoequipo','$descripcion_tipoequipo','1')";
        $new=ejecutarConsulta_retornarID($sql);
        $option='<option value="'.$new.'" selected>'.$nombre_tipoequipo.'</option>'; 
        return $option;
    }     

 
    //Implementamos un método para editar registros
    public function editar($idtipo_equipo,$nombre,$descripcion)
    {
        $sql="UPDATE tipo_equipo SET nombre='$nombre',descripcion='$descripcion' WHERE idtipo_equipo='$idtipo_equipo'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para desactivar categorías
    public function desactivar($idtipo_equipo)
    {
        $sql="UPDATE tipo_equipo SET condicion='0' WHERE idtipo_equipo='$idtipo_equipo'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar categorías
    public function activar($idtipo_equipo)
    {
        $sql="UPDATE tipo_equipo SET condicion='1' WHERE idtipo_equipo='$idtipo_equipo'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idtipo_equipo)
    {
        $sql="SELECT * FROM tipo_equipo WHERE idtipo_equipo='$idtipo_equipo'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT * FROM tipo_equipo";
        return ejecutarConsulta($sql);      
    }
    //Implementar un método para listar los registros y mostrar en el select
    public function select()
    {
        $sql="SELECT * FROM tipo_equipo where condicion=1";
        return ejecutarConsulta($sql);      
    }
}
 
?>