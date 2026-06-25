<?php

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Marca
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($codigo, $nombre, $descripcion, $idusuario)
    {
        $sql = "INSERT INTO marca (codigo,nombre,descripcion,idusuario,condicion)
        VALUES ('$codigo','$nombre','$descripcion','$idusuario','1')";
        print_r($sql);
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar($idmarca, $codigo, $nombre, $descripcion, $idusuario)
    {
        $sql = "UPDATE marca SET codigo='$codigo',nombre='$nombre',descripcion='$descripcion' WHERE idmarca='$idmarca'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar categorías
    public function desactivar($idmarca)
    {
        $sql = "UPDATE marca SET condicion='0' WHERE idmarca='$idmarca'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar categorías
    public function activar($idmarca)
    {
        $sql = "UPDATE marca SET condicion='1' WHERE idmarca='$idmarca'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idmarca)
    {
        $sql = "SELECT * FROM marca WHERE idmarca='$idmarca'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar()
    {
        $sql = "SELECT * FROM marca";
        return ejecutarConsulta($sql);
    }
    //Implementar un método para listar los registros y mostrar en el select
    public function selectMarca()
    {
        $sql = "SELECT * FROM marca where condicion=1";
        return ejecutarConsulta($sql);
    }

    public function guardaryeditarModal($codigo_marca, $nombre_marca, $descripcion_marca, $idusuario)
    {
        $sql = "INSERT INTO marca (nombre,descripcion,idusuario,condicion)
        VALUES ('$nombre_marca','$descripcion_marca','$idusuario','1')";
        $new = ejecutarConsulta_retornarID($sql);
        $option = '<option value="' . $new . '" selected>' . $nombre_marca . '</option>';
        return $option;
    }
}

?>