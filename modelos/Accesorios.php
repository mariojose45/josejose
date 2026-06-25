<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Accesorios
{
    //Implementamos nuestro constructor
    public function __construct()
    {
   
    }
 
    //Implementamos un método para insertar registros
    public function insertar($idmarca,$idlinea,$codaccesorio,$descripcion_accesorio)
    {
        $sql="INSERT INTO accesorios (idmarca,idlinea,estado)
        VALUES ($idmarca,$idlinea,'Aceptado')";
        //return ejecutarConsulta($sql);
        $idaccesoriosnew=ejecutarConsulta_retornarID($sql);
 
        $num_elementos=0;
        $sw=true;
 
        while ($num_elementos < count($codaccesorio))
        {
            $sql_detalle = "INSERT INTO detalle_accesorios(idaccesorios,codaccesorio,descripcion_accesorio) VALUES ('$idaccesoriosnew', '$codaccesorio[$num_elementos]','$descripcion_accesorio[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos=$num_elementos + 1;
        }
 
        return $sw;
    }
 
    //Implementamos un método para editar registros
    public function editar($idcategoria,$nombre,$descripcion)
    {
        $sql="UPDATE categoria SET nombre='$nombre',descripcion='$descripcion' WHERE idcategoria='$idcategoria'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para desactivar categorías
    public function desactivar($idcategoria)
    {
        $sql="UPDATE categoria SET condicion='0' WHERE idcategoria='$idcategoria'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar categorías
    public function activar($idcategoria)
    {
        $sql="UPDATE categoria SET condicion='1' WHERE idcategoria='$idcategoria'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idcategoria)
    {
        $sql="SELECT * FROM categoria WHERE idcategoria='$idcategoria'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT * FROM categoria";
        return ejecutarConsulta($sql);      
    }
    //Implementar un método para listar los registros y mostrar en el select
    public function selectMarca()
    {
        $sql="SELECT * FROM marca where condicion=1";
        return ejecutarConsulta($sql);      
    }

    public function selectLinea()
    {
        $sql="SELECT * FROM linea where condicion=1";
        return ejecutarConsulta($sql);      
    }    
}
 
?>