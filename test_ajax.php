<?php
require_once 'c:/xampp/htdocs/josejose/config/Conexion.php';
require_once 'c:/xampp/htdocs/josejose/modelos/Consultas.php';
$consulta = new Consultas();
$rsptacap = $consulta->capitalRecuperadoM('');
$regcap = $rsptacap->fetch_object();
echo json_encode(array('totalcapital' => ($regcap ? $regcap->totalcapital : 0)));
?>
