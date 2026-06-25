<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';

if (!isset($_SESSION["nombre"])) {
    echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
    if ($_SESSION['almacen'] == 1) {
        require_once "../modelos/Articulo.php";
        $arti = new Articulo();

        $codigo = $_GET["id"];
        $numerocodigos = (int)$_GET["id2"];
        $precio_venta = $_GET["id3"];
        $articulo = $_GET["id4"];

        require('FacturaEnvio.php');

        $pdf = new PDF_Invoice('P', 'mm', 'A4');
        $pdf->AddPage();

        $pdf->Output('Reporte de Etiqueta', 'I');
    } else {
        echo 'No tiene permiso para visualizar el reporte';
    }
}
ob_end_flush();
?>