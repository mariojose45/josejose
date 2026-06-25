<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 


 
Class Crearpresentacion
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($nombre_presentacion1,$nombre_presentacion2,$nombre_presentacion3,
    $nombre_presentacion4,$nombre_presentacion5,$nombre_presentacion6,$nombre_presentacion7,$nombre_presentacion8,
    $nombre_presentacion9,$nombre_presentacion10,$nombre_presentacion11,$nombre_presentacion12,$nombre_presentacion13,
    $nombre_presentacion14,$nombre_presentacion15,$nombre_presentacion16,$nombre_presentacion17,$nombre_presentacion18,
    $nombre_presentacion19,$nombre_presentacion20)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s'); 

            // Verificar cuántos registros hay
            $sqlCheck = "SELECT COUNT(*) as total FROM presentacion_precios";
            $res = ejecutarConsultaSimpleFila($sqlCheck);

            if ($res['total'] >= 20) {
                return "Error: Ya existen 20 presentaciones, no se puede agregar más.";
            }

        $sql="INSERT INTO presentacion_precios (nombre_presentacion1,nombre_presentacion2,nombre_presentacion3,
            nombre_presentacion4,nombre_presentacion5,nombre_presentacion6,nombre_presentacion7,nombre_presentacion8,
            nombre_presentacion9,nombre_presentacion10,nombre_presentacion11,nombre_presentacion12,nombre_presentacion13,
            nombre_presentacion14,nombre_presentacion15,nombre_presentacion16,nombre_presentacion17,nombre_presentacion18,
            nombre_presentacion19,nombre_presentacion20,fecha_creacion,idusuario,condicion)
        VALUES ('$nombre_presentacion1','$nombre_presentacion2','$nombre_presentacion3',
            '$nombre_presentacion4','$nombre_presentacion5','$nombre_presentacion6','$nombre_presentacion7','$nombre_presentacion8',
            '$nombre_presentacion9','$nombre_presentacion10','$nombre_presentacion11','$nombre_presentacion12','$nombre_presentacion13',
            '$nombre_presentacion14','$nombre_presentacion15','$nombre_presentacion16','$nombre_presentacion17','$nombre_presentacion18',
            '$nombre_presentacion19','$nombre_presentacion20','$fechaHora','".$_SESSION["idusuario"]."','1')";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para editar registros
    public function editar($idpresentacion,$nombre_presentacion1,$nombre_presentacion2,$nombre_presentacion3,
    $nombre_presentacion4,$nombre_presentacion5,$nombre_presentacion6,$nombre_presentacion7,$nombre_presentacion8,
    $nombre_presentacion9,$nombre_presentacion10,$nombre_presentacion11,$nombre_presentacion12,$nombre_presentacion13,
    $nombre_presentacion14,$nombre_presentacion15,$nombre_presentacion16,$nombre_presentacion17,$nombre_presentacion18,
    $nombre_presentacion19,$nombre_presentacion20)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s'); 

        $sql="UPDATE presentacion_precios SET 
        nombre_presentacion1='$nombre_presentacion1',
        nombre_presentacion2='$nombre_presentacion2',
        nombre_presentacion3='$nombre_presentacion3',
        nombre_presentacion4='$nombre_presentacion4',
        nombre_presentacion5='$nombre_presentacion5',
        nombre_presentacion6='$nombre_presentacion6',
        nombre_presentacion7='$nombre_presentacion7',
        nombre_presentacion8='$nombre_presentacion8',
        nombre_presentacion9='$nombre_presentacion9',
        nombre_presentacion10='$nombre_presentacion10',
        nombre_presentacion11='$nombre_presentacion11',
        nombre_presentacion12='$nombre_presentacion12',
        nombre_presentacion13='$nombre_presentacion13',
        nombre_presentacion14='$nombre_presentacion14',
        nombre_presentacion15='$nombre_presentacion15',
        nombre_presentacion16='$nombre_presentacion16',
        nombre_presentacion17='$nombre_presentacion17',
        nombre_presentacion18='$nombre_presentacion18',
        nombre_presentacion19='$nombre_presentacion19',
        nombre_presentacion20='$nombre_presentacion20',
        fecha_actualizacion='$fechaHora',
        idusuario_update='".$_SESSION["idusuario"]."'
        WHERE idpresentacion='$idpresentacion'";
        return ejecutarConsulta($sql);
    }
 

 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idpresentacion)
    {
        $sql="SELECT * FROM presentacion_precios WHERE idpresentacion='$idpresentacion'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT * FROM presentacion_precios";
        return ejecutarConsulta($sql);      
    }

   
}
 
?>