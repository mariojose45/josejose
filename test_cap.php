<?php
require_once 'c:/xampp/htdocs/josejose/config/Conexion.php';
$sql = "SELECT IFNULL(SUM(dv.totalcantidadpresentacion * dv.precio_compra), 0) as totalcapital FROM venta v INNER JOIN detalle_venta dv ON v.idventa = dv.idventa WHERE MONTH(v.fecha_hora) = MONTH(CURDATE()) AND YEAR(v.fecha_hora) = YEAR(CURDATE()) AND v.estado = 'Aceptado'";
$rspta = ejecutarConsulta($sql);
$reg = $rspta->fetch_object();
echo 'TOTALCAP: ' . $reg->totalcapital;
?>
