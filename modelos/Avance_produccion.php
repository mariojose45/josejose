<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Avance_produccion
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }

    //Implementamos un método para insertar registros
    public function insertar($idficha_empleado,$idcategoria,$idarticulo,$idarticulos_proceso,$idhora_produccion,$cantidad,$descripcion,$fecha_hora)
    {
        $sql="INSERT INTO avance_produccion (idficha_empleado,idcategoria,idarticulo,idarticulos_proceso,idhora_produccion,cantidad,descripcion,fecha_hora,condicion)
        VALUES ('$idficha_empleado','$idcategoria','$idarticulo','$idarticulos_proceso','$idhora_produccion','$cantidad','$descripcion','$fecha_hora','1')";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para editar registros
    public function editar($idficha_empleado,$idcategoria,$idarticulo)
    {
        $sql="UPDATE categoria SET nombre='$nombre',descripcion='$descripcion' WHERE idcategoria='$idcategoria'";
        return ejecutarConsulta($sql);
    }

    public function selectcategoria()
    {
        $sql="SELECT 
                c.idcategoria,
                c.nombre
                FROM categoria c WHERE c.condicion=1 ";
        return ejecutarConsulta($sql);
    }

    public function selectarticulo($idcategoria)
    {
        $sql="SELECT 
                a.idarticulo,
                a.idcategoria,
                a.nombre
                FROM articulo a WHERE a.idcategoria=".$idcategoria;
        return ejecutarConsulta($sql);
    } 

    public function selectavanceproduccion($idproducto)
    {
        $sql="SELECT
                ap.idarticulos_proceso,
                ap.idarticulo,
                ap.proceso,
                ap.descripcion,
                ap.condicion
                FROM articulos_procesos ap
                WHERE ap.idarticulo=".$idproducto;
        return ejecutarConsulta($sql);
    }     
 


    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT 
                ap.idavance_produccion,
                ap.idficha_empleado,
                fe.nombre as empleado,
                ap.idcategoria,
                c.nombre as categoria,
                ap.idarticulo,
                a.nombre as articulo,
                ap.idarticulos_proceso,
                apro.proceso as proceso,
                ap.idhora_produccion,
                TIME(hp.hora) AS hora,
                ap.cantidad,
                ap.descripcion,
                ap.condicion,
                DATE(ap.fecha_hora) as fecha
                FROM avance_produccion ap
                INNER JOIN articulo a ON ap.idarticulo=a.idarticulo
                INNER JOIN categoria c ON ap.idcategoria=c.idcategoria
                INNER JOIN articulos_procesos apro ON ap.idarticulos_proceso=apro.idarticulos_proceso
                INNER JOIN horarios_produccion hp ON ap.idhora_produccion=hp.idhora_produccion
                INNER JOIN ficha_empleado fe ON ap.idficha_empleado=fe.idficha_empleado";
        return ejecutarConsulta($sql);      
    }

}
 
?>