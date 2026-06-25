<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 


 
Class Tiendawebinicio
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 

 
    //Implementamos un método para editar registros
    public function editar($idinicio,
    $titulo_1,$sub_titulo_1,$descripcion_titulo_1,$imagen_1,
    $titulo_2,$sub_titulo_2,$descripcion_titulo_2,$imagen_2,
    $titulo_3,$sub_titulo_3,$descripcion_titulo_3,$imagen_3)
    {
        $sql="UPDATE tienda_web_inicio SET titulo_1='$titulo_1',sub_titulo_1='$sub_titulo_1',descripcion_titulo_1='$descripcion_titulo_1',imagen_1='$imagen_1',
        titulo_2='$titulo_2',sub_titulo_2='$sub_titulo_2',descripcion_titulo_2='$descripcion_titulo_2',imagen_2='$imagen_2',
        titulo_3='$titulo_3',sub_titulo_3='$sub_titulo_3',descripcion_titulo_3='$descripcion_titulo_3',imagen_3='$imagen_3',
        idusuario_update='".$_SESSION["idusuario"]."',fecha_update='".date("Y-m-d H:i:s")."',idsucursal_update='4'
        WHERE idinicio='$idinicio'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para desactivar categorías
    public function desactivar($idinicio)
    {
        $sql="UPDATE tienda_web_inicio SET condicion='0' WHERE idinicio='$idinicio'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar categorías
    public function activar($idinicio)
    {
        $sql="UPDATE tienda_web_inicio SET condicion='1' WHERE idinicio='$idinicio'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idinicio)
    {
        $sql="SELECT * FROM tienda_web_inicio WHERE idinicio='$idinicio'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT t.*,u.nombre as usuario_update
        FROM tienda_web_inicio t
        INNER JOIN usuario u ON u.idusuario=t.idusuario_update";
        return ejecutarConsulta($sql);      
    }




}
 
?>