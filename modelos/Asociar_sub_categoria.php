<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class AsociarSubCategoria
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($idcategoria,$idsubcategoria,$fechaHora)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');


        $sql="INSERT INTO asociar_subcategoria (idcategoria,idusuario,idsucursal,fecha_creacion,condicion)
        VALUES ('$idcategoria','".$_SESSION["idusuario"]."','".$_SESSION["idsucursal"]."','$fechaHora','1')";
        //print_r($sql);
        $idasociar_subcategorianew=ejecutarConsulta_retornarID($sql);


        $num_elementos=0;  
        $sw=true; 
 
        while ($num_elementos < count($idsubcategoria)) 
        {
            $sql_detalle = "INSERT INTO asociar_subcategoriadetalle(idasociar_subcategoria,idcategoria,idsubcategoria,
                                        idusuario,idsucursal,fecha_creacion,condicion) 
                                VALUES ('$idasociar_subcategorianew','$idcategoria','$idsubcategoria[$num_elementos]',
                                '".$_SESSION["idusuario"]."','".$_SESSION["idsucursal"]."','$fechaHora','1' )";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos=$num_elementos + 1;
        } 
        return ($idasociar_subcategorianew);
    }
 
    //Implementamos un método para editar registros
    public function editar($idasociar_subcategoria,$idcategoria,$idsubcategoria,$fechaHora)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        $sqlUpdate = "UPDATE asociar_subcategoria SET 
                            idcategoria='$idcategoria',
                            idusuario_update='".$_SESSION["idusuario"]."',
                            fecha_modificacion='$fechaHora' 
                            WHERE idasociar_subcategoria='$idasociar_subcategoria' ";
        ejecutarConsulta($sqlUpdate); 

        $sqlDetalleElimminar="DELETE from asociar_subcategoriadetalle where idasociar_subcategoria=".$idasociar_subcategoria."";
        ejecutarConsulta($sqlDetalleElimminar);


        $num_elementos=0;  
        $sw=true; 
 
        while ($num_elementos < count($idsubcategoria)) 
        {
            $sql_detalle = "INSERT INTO asociar_subcategoriadetalle(idasociar_subcategoria,
                                        idcategoria,
                                        idsubcategoria,
                                        idusuario,
                                        idsucursal,
                                        fecha_creacion,
                                        condicion) 
                                        VALUES ('$idasociar_subcategoria',
                                        '$idcategoria',
                                        '$idsubcategoria[$num_elementos]',
                                        '".$_SESSION["idusuario"]."',
                                        '".$_SESSION["idsucursal"]."',
                                        '$fechaHora','1' )";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos=$num_elementos + 1;
        } 
        return ($sw);
    }
 

 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idasociar_subcategoria)
    {
        $sql="SELECT * FROM asociar_subcategoria 
        WHERE idasociar_subcategoria='$idasociar_subcategoria'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function obtenerDetalle($idasociar_subcategoria){

        $sqldetalle="SELECT 
                    a.idasociar_subcategoriadetalle,
                    a.idasociar_subcategoria,
                    a.idcategoria,
                    a.idsubcategoria,
                    sc.nombre AS nombreSubCategoria,
                    sc.descripcion AS descripcion,
                    a.idusuario,
                    a.idsucursal,
                    a.condicion
                FROM asociar_subcategoriadetalle a
                INNER JOIN subcategoria sc ON sc.idsubcategoria=a.idsubcategoria
                WHERE a.idusuario='".$_SESSION["idusuario"]."' AND a.idsucursal='".$_SESSION["idsucursal"]."' and a.idasociar_subcategoria='$idasociar_subcategoria' ";
        //print_r($sqldetalle);
        $rspta=ejecutarConsulta($sqldetalle);
        $rows = array();
        while ($reg=$rspta->fetch_object()){
            $rows[] = $reg;
        }
        return $rows;
    }      
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT 
                a.idasociar_subcategoria,
                c.nombre AS nombre_categoria,
                (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=a.idusuario LIMIT 1) AS user_creacion,
                s.nombre AS nombre_sucursal,
                a.fecha_creacion,
                a.condicion,
                (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=a.idusuario_update LIMIT 1) AS user_modificacion,
                a.fecha_modificacion
            FROM asociar_subcategoria a 
            INNER JOIN categoria c ON a.idcategoria=c.idcategoria
            INNER JOIN sucursal s ON s.idsucursal=a.idsucursal ";
        return ejecutarConsulta($sql);      
    }

    //Implementamos un método para desactivar categorías
    public function desactivar($idasociar_subcategoria)
    {
        $sql="UPDATE asociar_subcategoria SET condicion='0' WHERE idasociar_subcategoria='$idasociar_subcategoria'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar categorías
    public function activar($idasociar_subcategoria)
    {
        $sql="UPDATE asociar_subcategoria SET condicion='1' WHERE idasociar_subcategoria='$idasociar_subcategoria'";
        return ejecutarConsulta($sql);
    }

    
}
 
?>