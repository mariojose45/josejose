<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/ConexionservidorCloud.php";
require "../config/Conexion.php"; 
 
Class Consultas
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    public function comprasfecha($fecha_inicio,$fecha_fin)

    {
    	$this->sincronizarTablaRegistro();
        $sql="SELECT  v.id,v.codigo,v.fecha,v.foto,v.accion FROM registro v  WHERE DATE(v.fecha)>='$fecha_inicio' AND DATE(v.fecha)<='$fecha_fin'";
        //sincronizarTablaRegistro(); 
        return ejecutarConsulta($sql); 

    }

    //esta fucnion la tenes que corre antes de consultar las tablas locales
    public function sincronizarTablaRegistro(){

        $sql1="select ifnull(max(id),0) as ultimoid from registro";
        $req1=ejecutarConsultaSimpleFila($sql1);
        $ultimoid=$req1["ultimoid"];

        $sqlcloud="select * from registro where id>".$ultimoid;
        $rspta=_ejecutarConsulta($sqlcloud);
        #echo "numero: ".mysqli_num_rows($rspta);
        while ($reg=$rspta->fetch_object()){

            $sqlinsert="insert into registro(id,foto,nombre,fecha,codigo,accion,codsubida)
            values('".$reg->id."','".$reg->foto."','".$reg->nombre."','".$reg->fecha."',
            '".$reg->codigo."','".$reg->accion."','".$reg->codsubida."')
            ";
            #echo $sqlinsert;
            ejecutarConsulta($sqlinsert);
        }
    }


 
 
}
 
?>