<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Tecnicos
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($nombre, $descripcion, $comision)
    {
        $sql = "INSERT INTO tecnico (nombre,descripcion,condicion,comision)
        VALUES ('$nombre','$descripcion','1','$comision')";
        return ejecutarConsulta($sql);
    }

    public function guardaryeditarModal($nombre_tecnico, $descripcion_tecnico, $comision_tecnico)
    {
        $sql = "INSERT INTO tecnico (nombre,descripcion,condicion,comision,fecha_creacion)
        VALUES ('$nombre_tecnico','$descripcion_tecnico','1','$comision_tecnico',NOW())";
        $new = ejecutarConsulta_retornarID($sql);
        $option = '<option value="' . $new . '" selected>' . $nombre_tecnico . '</option>';
        return $option;
    }

    //Implementamos un método para editar registros
    public function editar($idtecnico, $nombre, $descripcion, $comision)
    {
        $sql = "UPDATE tecnico SET nombre='$nombre',descripcion='$descripcion',comision='$comision' WHERE idtecnico='$idtecnico'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar categorías
    public function desactivar($idtecnico)
    {
        $sql = "UPDATE tecnico SET condicion='0' WHERE idtecnico='$idtecnico'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar categorías
    public function activar($idtecnico)
    {
        $sql = "UPDATE tecnico SET condicion='1' WHERE idtecnico='$idtecnico'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idtecnico)
    {
        $sql = "SELECT * FROM tecnico WHERE idtecnico='$idtecnico'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar()
    {
        $sql = "SELECT * FROM tecnico";
        return ejecutarConsulta($sql);
    }
    //Implementar un método para listar los registros y mostrar en el select
    public function selectTeccnico()
    {
        $sql = "SELECT * FROM tecnico where condicion=1";
        return ejecutarConsulta($sql);
    }
}

?>