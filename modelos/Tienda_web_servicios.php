<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 


 
Class TiendawebServicios
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    public function insertar($nombre,$tipo,$descripcion_servicio,$imagen_servicio)
    {
        $sql_categoria = "INSERT INTO tienda_web_servicios (nombre, tipo, descripcion_servicio, imagen_servicio, fecha_creacion, idusuario_create, idsucursal_create, condicion)
            VALUES ('$nombre', '$tipo', '$descripcion_servicio', '$imagen_servicio', '".date("Y-m-d H:i:s")."', '".$_SESSION["idusuario"]."', '4', '1')";
        $id_categoria = ejecutarConsulta_retornarID($sql_categoria);


        return $id_categoria; 
    }
 
    //Implementamos un método para editar registros
    public function editar($idservicios,$nombre,$tipo,$descripcion_servicio,$imagen_servicio)
    {
        $sql="UPDATE tienda_web_servicios SET 
        nombre='$nombre',tipo='$tipo',
        descripcion_servicio='$descripcion_servicio',imagen_servicio='$imagen_servicio',
        idusuario_update='".$_SESSION["idusuario"]."',fecha_update='".date("Y-m-d H:i:s")."',idsucursal_update='4'
        WHERE idservicios='$idservicios'";
        return ejecutarConsulta($sql);
    }
 

 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idservicios)
    {
        $sql="SELECT * FROM tienda_web_servicios WHERE idservicios='$idservicios'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT t.*,u.nombre as usuario_update
        FROM tienda_web_servicios t
        LEFT JOIN usuario u ON u.idusuario=t.idusuario_update
        WHERE t.condicion=1
        ORDER BY t.idservicios DESC";
        return ejecutarConsulta($sql);      
    }




}
 
?>