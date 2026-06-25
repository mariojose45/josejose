<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';

if (!isset($_SESSION["nombre"])) {
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
  exit;
}

if ($_SESSION['almacen'] != 1) {
  echo 'No tiene permisos para ver este reporte';
  exit;
}

require_once "../modelos/Cotizaciones.php";
require('barcode.php');
include 'empresa.php';

$cotizaciones = new Cotizaciones();
$url = '../files/articulos/';

// =======================
// DATOS DE LA VENTA
// =======================
$rsptav = $cotizaciones->ventacabecera2($_GET["id"]);
$regdv = $rsptav->fetch_object();

// =======================
// PDF CONFIGURACIÓN
// =======================
$pdf = new PDF_Code128('L', 'mm', array(148, 210));
$pdf->AddPage();
$pdf->SetFont('Arial', '', 8);

// =======================
// CAJAS PRINCIPALES
// =======================
$pdf->Rect(5, 5, 200, 22);   // Encabezado
$pdf->Rect(5, 30, 200, 18);  // Cliente
$pdf->Rect(5, 50, 200, 15);  // Comentarios
$pdf->Rect(5, 68, 200, 30);  // Código barras
$pdf->Rect(5, 100, 200, 15); // Info final

// =======================
// ENCABEZADO
// =======================
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(10, 10);
$pdf->Cell(120, 6, 'BOLETA DE MENSAJERO', 0, 0, 'L');

$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(10, 16);
$pdf->Cell(120, 4, utf8_decode($regdv->sucursal_nombre), 0, 1);

$pdf->SetXY(10, 20);
$pdf->Cell(120, 4, 'Nit: ' . $regdv->sucursal_nit, 0, 1);

$pdf->SetXY(10, 24);
$pdf->Cell(120, 4, utf8_decode($regdv->sucursal_direccion), 0, 1);

$pdf->Image($url . $regdv->sucursal_imagen, 180, 8, 18);

// =======================
// DATOS CLIENTE
// =======================
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(10, 33);
$pdf->Cell(25, 5, 'Cliente:', 0, 0);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(80, 5, utf8_decode($regdv->cliente), 0, 1);

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetX(10);
$pdf->Cell(25, 5, 'Direccion:', 0, 0);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(80, 5, utf8_decode($regdv->direccion), 0, 1);

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(120, 33);
$pdf->Cell(25, 5, 'Vendedor:', 0, 0);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(60, 5, utf8_decode($regdv->nombre_vendedor), 0, 1);

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(120, 38);
$pdf->Cell(25, 5, 'Contacto:', 0, 0);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(60, 5, $regdv->telefono, 0, 1);

// =======================
// COMENTARIOS
// =======================
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(10, 52);
$pdf->Cell(40, 5, 'Comentarios:', 0, 1);

$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(10, 57);
$pdf->MultiCell(190, 4, utf8_decode($regdv->comentario_mensajero));

// =======================
// CÓDIGO DE BARRAS
// =======================
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetXY(5, 70);
$pdf->Cell(200, 6, 'GUIA DE ENTREGA', 0, 1, 'C');

$pdf->Code128(40, 78, "GT" . $regdv->idventa, 130, 10);

$pdf->SetFont('Arial', '', 9);
$pdf->SetXY(5, 88);
$pdf->Cell(200, 5, '# IdVenta Interno ' . $regdv->idventa, 0, 1, 'C');

// =======================
// INFO FINAL
// =======================
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(10, 103);
$pdf->Cell(60, 5, 'Equipo entregado: ' . $regdv->despachosino, 0, 0);

$pdf->SetXY(80, 103);
$pdf->Cell(60, 5, 'Fecha: ' . $regdv->fecha, 0, 0);

$pdf->SetXY(140, 103);
$pdf->Cell(60, 5, '# Interno: ' . $regdv->idventa, 0, 1, 'R');

// =======================
// GUIA TRANSPORTE (SI EXISTE)
// =======================
if (!empty($regdv->guia_transporte)) {
  $pdf->Code128(40, 110, $regdv->guia_transporte, 130, 10);
  $pdf->SetXY(40, 121);
  $pdf->Cell(130, 5, $regdv->guia_transporte, 0, 1, 'C');
}

// =======================
// SALIDA PDF
// =======================
$pdf->Output();
?>