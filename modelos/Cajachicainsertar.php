<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

$conexion=mysqli_connect('localhost','root','','siliezardb');

    $valueQ = $_POST['initialValue'];
    $idusuario=$_SESSION["idusuario"];

    $sql="INSERT into caja_chica (fecha_hora, valor_inicial,tipo_comprobante,estado,id_usuario)
            values (now(),'$valueQ','CajaChica','Aceptado','$idusuario')";


    $idCaja=ejecutarConsulta_retornarID($sql,$conexion);

        echo $idCaja;


function ejecutarConsulta_retornarID($sql, $conexion)
{
    $query = $conexion->query($sql);
    return $conexion->insert_id;
}

 ?>