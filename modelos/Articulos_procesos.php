<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Articulos_procesos
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($idarticulo,$proceso,$descripcion)
    {
        $sql="INSERT INTO articulos_procesos (idarticulo,proceso,descripcion,condicion)
        VALUES ('$idarticulo','$proceso','$descripcion','1')";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para editar registros
    public function editar($idarticulos_proceso,$idarticulo,$proceso,$descripcion)
    {
        $sql="UPDATE articulos_procesos SET idarticulo='$idarticulo',proceso='$proceso',descripcion='$descripcion' WHERE idarticulos_proceso='$idarticulos_proceso'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para desactivar categorías
    public function desactivar($idarticulos_proceso)
    {
        $sql="UPDATE articulos_procesos SET condicion='0' WHERE idarticulos_proceso='$idarticulos_proceso'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar categorías
    public function activar($idarticulos_proceso)
    {
        $sql="UPDATE articulos_procesos SET condicion='1' WHERE idarticulos_proceso='$idarticulos_proceso'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idarticulos_proceso)
    {
        $sql="SELECT * FROM articulos_procesos WHERE idarticulos_proceso='$idarticulos_proceso'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT 
                ap.idarticulos_proceso,
                ap.idarticulo,
                a.nombre as articulo,
                ap.proceso,
                ap.descripcion,
                ap.condicion
                FROM articulos_procesos ap
                INNER JOIN articulo a ON ap.idarticulo=a.idarticulo";
        return ejecutarConsulta($sql);      
    }

}
 
?>