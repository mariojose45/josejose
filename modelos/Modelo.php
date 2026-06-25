<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Modelos
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($nombre, $descripcion)
    {
        $sql = "INSERT INTO modelo (nombre,descripcion,condicion)
        VALUES ('$nombre','$descripcion','1')";
        return ejecutarConsulta($sql);
    }

    public function guardaryeditarModal($nombre_modelo, $descripcion_modelo)
    {
        $sql = "INSERT INTO modelo (nombre,descripcion,condicion,idusuario)
        VALUES ('$nombre_modelo','$descripcion_modelo','1','" . $_SESSION["idusuario"] . "')";
        $new = ejecutarConsulta_retornarID($sql);
        $option = '<option value="' . $new . '" selected>' . $nombre_modelo . '</option>';
        return $option;
    }


    //Implementamos un método para editar registros
    public function editar($idmodelo, $nombre, $descripcion)
    {
        $sql = "UPDATE modelo SET nombre='$nombre',descripcion='$descripcion' WHERE idmodelo='$idmodelo'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar categorías
    public function desactivar($idmodelo)
    {
        $sql = "UPDATE modelo SET condicion='0' WHERE idmodelo='$idmodelo'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar categorías 
    public function activar($idmodelo)
    {
        $sql = "UPDATE modelo SET condicion='1' WHERE idmodelo='$idmodelo'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idmodelo)
    {
        $sql = "SELECT * FROM modelo WHERE idmodelo='$idmodelo'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar()
    {
        $sql = "SELECT * FROM modelo";
        return ejecutarConsulta($sql);
    }
    //Implementar un método para listar los registros y mostrar en el select
    public function select()
    {
        $sql = "SELECT * FROM modelo where condicion=1";
        return ejecutarConsulta($sql);
    }
}

?>