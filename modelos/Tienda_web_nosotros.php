<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 


 
Class Tiendawebnosotros
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 

 
    //Implementamos un método para editar registros
    public function editar($idnosotros,$historia_empresa,$imagen_nosotros,
    $mision_nosotros,$vision_nosotros,$diferencia_nosotros)
    {
        $sql="UPDATE tienda_web_nosotros SET historia_empresa='$historia_empresa',imagen_nosotros='$imagen_nosotros',
        mision_nosotros='$mision_nosotros',vision_nosotros='$vision_nosotros',diferencia_nosotros='$diferencia_nosotros',
        idusuario_update='".$_SESSION["idusuario"]."',fecha_update='".date("Y-m-d H:i:s")."',idsucursal_update='4'
        WHERE idnosotros='$idnosotros'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para desactivar categorías
    public function desactivar($idnosotros)
    {
        $sql="UPDATE tienda_web_nosotros SET condicion='0' WHERE idnosotros='$idnosotros'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar categorías
    public function activar($idnosotros)
    {
        $sql="UPDATE tienda_web_nosotros SET condicion='1' WHERE idnosotros='$idnosotros'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idnosotros)
    {
        $sql="SELECT * FROM tienda_web_nosotros WHERE idnosotros='$idnosotros'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT t.*,u.nombre as usuario_update
        FROM tienda_web_nosotros t
        INNER JOIN usuario u ON u.idusuario=t.idusuario_update";
        return ejecutarConsulta($sql);      
    }




}
 
?>