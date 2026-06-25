<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

date_default_timezone_set('America/Guatemala');
require_once "../modelos/Cuadres_caja.php";
  
$cuadreinicio=new CuadreInicio();   
 
$idcuadre_caja=isset($_POST["idcuadre_caja"])? limpiarCadena($_POST["idcuadre_caja"]):"";
$fecha_hora_inicio=isset($_POST["fecha_hora_inicio"])? limpiarCadena($_POST["fecha_hora_inicio"]):"";

$total_efectivo=isset($_POST["total_efectivo"])? limpiarCadena($_POST["total_efectivo"]):"";
$idusuario=$_SESSION["idusuario"];

$fecha_hora_apertura = date('Y-m-d H:i:s');
$hora_inicio=date("H:i:s");

//NUECO CAMPO DE TIPO CAMBIO
switch ($_GET["op"]){

 
    case 'guardaryeditar2':
            $rspta=$cuadreinicio->insertar2($fecha_hora_apertura,$total_efectivo,$idusuario,$hora_inicio);
            echo $rspta;

    break;    
 

}
?>