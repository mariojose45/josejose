<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Fichaempleado
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($cod_empleado,$nombre,$direccion,$dpi_no,$telefono,$celular,$tipo_sexo,$estado_civil,$forma_pago,$email,$fecha_nacimiento,$cta_no,$tipo_banco,$sueldo_base,$bonificacion,$fecha_ingreso,$jefe_immediato_1,$tiempo_trabajo_1,$fecha_finalizacion_labora_1,$telefono_1,$jefe_immediato_2,$tiempo_trabajo_2,$fecha_finalizacion_labora_2,$telefono_2,$jefe_immediato_3,$tiempo_trabajo_3,$fecha_finalizacion_labora_3,$telefono_3,$nombre_refe_laboral_1,$telefono_refe_laboral_1,$parentesco_refe_laboral_1,$nombre_refe_laboral_2,$telefono_refe_laboral_2,$parentesco_refe_laboral_2,$nombre_refe_laboral_3,$telefono_refe_laboral_3,$parentesco_refe_laboral_3,$dpi_Lado1_imagen,$dpi_lado2_imagen,$carta_recomendacion1_imagen,$carta_recomendacion2_imagen,$carta_recomendacion3_imagen,$carta_trabajo1_imagen,$carta_trabajo2_imagen,$carta_trabajo3_imagen,$horaentrada,$horarefaccion,$horaalmuerzo,$horasalida,$horaextra)
    {
        $sql="INSERT INTO ficha_empleado (cod_empleado,nombre,direccion,dpi_no,telefono,celular,tipo_sexo,estado_civil,forma_pago,email,fecha_nacimiento,cta_no,tipo_banco,sueldo_base,bonificacion,fecha_ingreso,jefe_immediato_1,tiempo_trabajo_1,fecha_finalizacion_labora_1,telefono_1,jefe_immediato_2,tiempo_trabajo_2,fecha_finalizacion_labora_2,telefono_2,jefe_immediato_3,tiempo_trabajo_3,fecha_finalizacion_labora_3,telefono_3,nombre_refe_laboral_1,telefono_refe_laboral_1,parentesco_refe_laboral_1,nombre_refe_laboral_2,telefono_refe_laboral_2,parentesco_refe_laboral_2,nombre_refe_laboral_3,telefono_refe_laboral_3,parentesco_refe_laboral_3,dpi_Lado1_imagen,dpi_lado2_imagen,carta_recomendacion1_imagen,carta_recomendacion2_imagen,carta_recomendacion3_imagen,carta_trabajo1_imagen,carta_trabajo2_imagen,carta_trabajo3_imagen,condicion,horaextra)
        VALUES ('$cod_empleado','$nombre','$direccion','$dpi_no','$telefono','$celular','$tipo_sexo','$estado_civil','$forma_pago','$email','$fecha_nacimiento','$cta_no','$tipo_banco','$sueldo_base','$bonificacion','$fecha_ingreso','$jefe_immediato_1','$tiempo_trabajo_1','$fecha_finalizacion_labora_1','$telefono_1','$jefe_immediato_2','$tiempo_trabajo_2','$fecha_finalizacion_labora_2','$telefono_2','$jefe_immediato_3','$tiempo_trabajo_3','$fecha_finalizacion_labora_3','$telefono_3','$nombre_refe_laboral_1','$telefono_refe_laboral_1','$parentesco_refe_laboral_1','$nombre_refe_laboral_2','$telefono_refe_laboral_2','$parentesco_refe_laboral_2','$nombre_refe_laboral_3','$telefono_refe_laboral_3','$parentesco_refe_laboral_3','$dpi_Lado1_imagen','$dpi_lado2_imagen','$carta_recomendacion1_imagen','$carta_recomendacion2_imagen','$carta_recomendacion3_imagen','$carta_trabajo1_imagen','$carta_trabajo2_imagen','$carta_trabajo3_imagen','1','$horaextra')";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para editar registros
    public function editar($idficha_empleado,$cod_empleado,$nombre,$direccion,
    $dpi_no,$telefono,$celular,$tipo_sexo,$estado_civil,$forma_pago,$email,
    $fecha_nacimiento,$cta_no,$tipo_banco,$sueldo_base,$bonificacion,$fecha_ingreso,
    $jefe_immediato_1,$tiempo_trabajo_1,$fecha_finalizacion_labora_1,$telefono_1,
    $jefe_immediato_2,$tiempo_trabajo_2,$fecha_finalizacion_labora_2,$telefono_2,
    $jefe_immediato_3,$tiempo_trabajo_3,$fecha_finalizacion_labora_3,$telefono_3,
    $nombre_refe_laboral_1,$telefono_refe_laboral_1,$parentesco_refe_laboral_1,
    $nombre_refe_laboral_2,$telefono_refe_laboral_2,$parentesco_refe_laboral_2,
    $nombre_refe_laboral_3,$telefono_refe_laboral_3,$parentesco_refe_laboral_3,
    $dpi_Lado1_imagen,$horaentrada,$horarefaccion,$horaalmuerzo,$horasalida,
    $dpi_lado2_imagen,
    $carta_recomendacion1_imagen,
    $carta_recomendacion2_imagen,
    $carta_recomendacion3_imagen,
    $carta_trabajo1_imagen,
    $carta_trabajo2_imagen,
    $carta_trabajo3_imagen,
    $horaextra)
    {
        $sql="UPDATE ficha_empleado SET cod_empleado='$cod_empleado',nombre='$nombre',
        direccion='$direccion',dpi_no='$dpi_no',telefono='$telefono',celular='$celular',
        tipo_sexo='$tipo_sexo',estado_civil='$estado_civil',forma_pago='$forma_pago',
        email='$email',fecha_nacimiento='$fecha_nacimiento',cta_no='$cta_no',
        tipo_banco='$tipo_banco',sueldo_base='$sueldo_base',bonificacion='$bonificacion',
        fecha_ingreso='$fecha_ingreso',jefe_immediato_1='$jefe_immediato_1',
        tiempo_trabajo_1='$tiempo_trabajo_1',
        fecha_finalizacion_labora_1='$fecha_finalizacion_labora_1',
        telefono_1='$telefono_1',jefe_immediato_2='$jefe_immediato_2',
        tiempo_trabajo_2='$tiempo_trabajo_2',
        fecha_finalizacion_labora_2='$fecha_finalizacion_labora_2',
        telefono_2='$telefono_2',jefe_immediato_3='$jefe_immediato_3',
        nombre_refe_laboral_2='$nombre_refe_laboral_2',
        telefono_refe_laboral_2='$telefono_refe_laboral_2',
        parentesco_refe_laboral_2='$parentesco_refe_laboral_2',
        nombre_refe_laboral_3='$nombre_refe_laboral_3',
        telefono_refe_laboral_3='$telefono_refe_laboral_3',
        parentesco_refe_laboral_3='$parentesco_refe_laboral_3',
        horaextra='$horaextra',
        ".($dpi_Lado1_imagen!=""?"dpi_Lado1_imagen='$dpi_Lado1_imagen',":"")."
        ".($dpi_lado2_imagen!=""?"dpi_lado2_imagen='$dpi_lado2_imagen',":"")."
        ".($carta_recomendacion1_imagen!=""?"carta_recomendacion1_imagen='$carta_recomendacion1_imagen',":"")."
        ".($carta_recomendacion2_imagen!=""?"carta_recomendacion2_imagen='$carta_recomendacion2_imagen',":"")."
        ".($carta_recomendacion3_imagen!=""?"carta_recomendacion3_imagen='$carta_recomendacion3_imagen',":"")."
        ".($carta_trabajo1_imagen!=""?"carta_trabajo1_imagen='$carta_trabajo1_imagen',":"")."
        ".($carta_trabajo2_imagen!=""?"carta_trabajo2_imagen='$carta_trabajo2_imagen',":"")."
        ".($carta_trabajo3_imagen!=""?"carta_trabajo3_imagen='$carta_trabajo3_imagen',":"")."

        hora_entrada='".$horaentrada."',hora_refaccion='".$horarefaccion."',
        hora_almuerzo='".$horaalmuerzo."',hora_salida='".$horasalida."' 
        WHERE idficha_empleado='$idficha_empleado'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para desactivar registros
    public function desactivar($idarticulo)
    {
        $sql="UPDATE articulo SET condicion='0' WHERE idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar registros
    public function activar($idarticulo)
    {
        $sql="UPDATE articulo SET condicion='1' WHERE idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idficha_empleado)
    {
        $sql="SELECT 
                fe.idficha_empleado,
                fe.cod_empleado,
                fe.nombre,
                fe.direccion,
                fe.dpi_no,
                fe.telefono,
                fe.celular,
                fe.tipo_sexo,
                fe.estado_civil,
                fe.forma_pago,
                fe.email,
                DATE(fe.fecha_nacimiento) as fechanacimiento,
                fe.cta_no,
                fe.tipo_banco,
                fe.sueldo_base,
                fe.bonificacion,
                fe.horaextra,
                fe.hora_entrada,
                fe.hora_refaccion,
                fe.hora_almuerzo,
                fe.hora_salida,
                DATE(fe.fecha_ingreso) as fechaingreso,
                dpi_Lado1_imagen,
                dpi_lado2_imagen,
                carta_recomendacion1_imagen,
                carta_recomendacion2_imagen,
                carta_recomendacion3_imagen,
                carta_trabajo1_imagen,
                carta_trabajo2_imagen,
                carta_trabajo3_imagen
            FROM ficha_empleado fe
                WHERE fe.idficha_empleado='$idficha_empleado'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT * FROM  ficha_empleado";
        return ejecutarConsulta($sql);      
    }
 
    //Implementar un método para listar los registros 
    public function listaficha()
    {
        $sql="SELECT * FROM ficha_empleado ";
        return ejecutarConsulta($sql);      
    }

    //Implementar un método para listar los registros 
    public function selectfichaempleado()
    {
        $sql="SELECT * FROM ficha_empleado ";
        return ejecutarConsulta($sql);      
    }


    
}
 
?>