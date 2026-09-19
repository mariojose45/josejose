<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class CuadreInicio
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }



    public function insertar2($fecha_hora_apertura, $total_efectivo, $idusuario, $hora_inicio)
    {

        $fecha_solo = date('Y-m-d', strtotime($fecha_hora_apertura));
        // Consulta para verificar si ya hay una apertura de caja hoy con el mismo idusuario
        $sql_verificar = "SELECT * FROM cuadre_cajas 
                          WHERE idusuario = '$idusuario' and idsucursal='" . $_SESSION["idsucursal"] . "' 
                          AND DATE(fecha_hora_inicio) <= '$fecha_solo'
                          AND tipo_operacion = 'APERTURA'";

        $resultado = ejecutarConsulta($sql_verificar);

        // Si ya existe una apertura, retornar un mensaje indicando que la caja ya fue aperturada
        if ($resultado->num_rows > 0) {
            return "La caja ya fue aperturada por este usuario hoy.";
        } else {

            $sql = "INSERT INTO cuadre_cajas (fecha_hora_inicio,centavos_1,centavos_5,centavos_10,centavos_25,centavos_50,quetzal_1,quetzal_5,quetzal_10,quetzal_20,quetzal_50,quetzal_100,
            quetzal_200,total_efectivo,idusuario,hora_inicio,condicion,tipo_operacion,idsucursal)
            VALUES ('$fecha_hora_apertura','0','0','0','0','0','0','0','0','0','0','0','0',
            '$total_efectivo','$idusuario','$hora_inicio','1','APERTURA','" . $_SESSION["idsucursal"] . "' )";
            // print_r($sql);
            ejecutarConsulta($sql);
            return "La caja fue aperturada de forma correcta.";
        }


    }



}

?>