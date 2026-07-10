<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Ruta_visita
{
    //Implementamos nuestro constructor
    public function __construct()
    {
    }

    //Implementamos un método para insertar registros
    public function insertar($nombre, $descripcion)
    {
        $sql = "INSERT INTO ruta_visita (nombre, descripcion, condicion)
        VALUES ('$nombre', '$descripcion', '1')";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar($idruta, $nombre, $descripcion)
    {
        $sql = "UPDATE ruta_visita SET nombre='$nombre', descripcion='$descripcion' WHERE idruta='$idruta'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar categorías
    public function desactivar($idruta)
    {
        $sql = "UPDATE ruta_visita SET condicion='0' WHERE idruta='$idruta'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar categorías
    public function activar($idruta)
    {
        $sql = "UPDATE ruta_visita SET condicion='1' WHERE idruta='$idruta'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idruta)
    {
        $sql = "SELECT * FROM ruta_visita WHERE idruta='$idruta'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar()
    {
        $sql = "SELECT * FROM ruta_visita";
        return ejecutarConsulta($sql);
    }
}
?>
