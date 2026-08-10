<?php
require_once 'c:/xampp/htdocs/josejose/config/Conexion.php';
require_once 'c:/xampp/htdocs/josejose/modelos/Consultas.php';
$consulta = new Consultas();
$idsucursal_filtro = "";

$rsptac = $consulta->totalcomprahoy($idsucursal_filtro);
$regc = $rsptac->fetch_object();
$totalc = $regc ? $regc->total_compra : 0;

$rsptav = $consulta->totalventahoy($idsucursal_filtro);
$regv = $rsptav->fetch_object();
$totalv = $regv ? $regv->total_venta : 0;

$rsptavm = $consulta->totalventaM($idsucursal_filtro);
$regvm = $rsptavm->fetch_object();
$totalvm = $regvm ? $regvm->total_venta : 0;

$rsptavcobrar = $consulta->totalventaCobrar($idsucursal_filtro);
$regvcobrar = $rsptavcobrar->fetch_object();
$totalitem = $regvcobrar ? $regvcobrar->numerodeitems : 0;
$totalcobrar = $regvcobrar ? $regvcobrar->totalcobrar : 0;
$totalabonos = $regvcobrar ? $regvcobrar->totalabonos : 0;

$rsptacap = $consulta->capitalRecuperadoM($idsucursal_filtro);
$regcap = $rsptacap->fetch_object();
$totalcapital = $regcap ? $regcap->totalcapital : 0;

echo json_encode(array(
    "totalc" => $totalc,
    "totalv" => $totalv,
    "totalvm" => $totalvm,
    "totalitem" => $totalitem,
    "totalcobrar" => $totalcobrar,
    "totalabonos" => $totalabonos,
    "totalcapital" => $totalcapital
));
?>
