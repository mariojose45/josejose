<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Permiso
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
     
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT * FROM permiso WHERE condicion=1 order by nombre asc";
        return ejecutarConsulta($sql);      
    }
 
}
 
?>