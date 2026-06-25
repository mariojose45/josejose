<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Hora_produccion
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($hora,$hora2,$descripcion)
    {
        $sql="INSERT INTO horarios_produccion (hora,hora2,descripcion,condicion)
        VALUES ('$hora','$hora2','$descripcion','1')";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para editar registros
    public function editar($idhora_produccion,$hora,$hora2,$descripcion)
    {
        $sql="UPDATE horarios_produccion SET hora='$hora',hora2='$hora2',descripcion='$descripcion' WHERE idhora_produccion='$idhora_produccion'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para desactivar categorías
    public function desactivar($idhora_produccion)
    {
        $sql="UPDATE horarios_produccion SET condicion='0' WHERE idhora_produccion='$idhora_produccion'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar categorías
    public function activar($idhora_produccion)
    {
        $sql="UPDATE horarios_produccion SET condicion='1' WHERE idhora_produccion='$idhora_produccion'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idhora_produccion)
    {
        $sql="SELECT * FROM horarios_produccion WHERE idhora_produccion='$idhora_produccion'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT * FROM horarios_produccion";
        return ejecutarConsulta($sql);      
    }
    //Implementar un método para listar los registros y mostrar en el select
    public function select()
    {
        $sql="SELECT * FROM horarios_produccion where condicion=1";
        return ejecutarConsulta($sql);      
    }


    public function selecthora()
    {
        $sql="SELECT * FROM horarios_produccion where condicion=1";
        return ejecutarConsulta($sql);      
    }
        
}
 
?>