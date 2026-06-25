<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';

if (!isset($_SESSION["nombre"])) exit("Error de sesión");
if ($_SESSION["ventas"] != 1) exit("Sin permisos");

require("FormatoMuchoshojas.php");
require_once "../modelos/Cuadres_caja_cierre.php";  

$cuadre = new CuadreInicio();
$id = $_GET["id"];
$rspta = $cuadre->cuadrecajacabeceracierre($id);
$reg = $rspta->fetch_object();

// ---------------------------------------
// CONFIGURACIÓN TICKET 79mm
// ---------------------------------------
$ANCHO = 78;        // 79mm real
$MARGEN = 2;
$UTIL = $ANCHO - ($MARGEN * 2);

// Ticket largo dinámico
$pdf = new FPDF('P', 'mm', array($ANCHO, 2000));
$pdf->AddPage();

// ---------------------------------------
// LOGO
// ---------------------------------------
$logo = '../files/articulos/' . $reg->sucursal_imagen;
if ($reg->sucursal_imagen == "" || $reg->sucursal_imagen == "0") {
    $logo = '../files/articulos/1590204245.jpg';
}

$pdf->Image($logo, 18, 4, 40);
$pdf->Ln(40);

// ---------------------------------------
// TÍTULO
// ---------------------------------------
$pdf->SetFont('Arial','B',11);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,5,utf8_decode("CIERRE DE CAJA"),0,1,'C');

$pdf->SetFont('Arial','',9);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,4,"Cierre No: ".$reg->idcuadre_caja,0,1,'C');

$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,4,"Fecha Inicio: ".$reg->fecha_hora_inicio,0,1,'C');

$pdf->Ln(2);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,3,"","B",1);

// ---------------------------------------
// SUCURSAL
// ---------------------------------------
$pdf->SetFont('Arial','',8);
$pdf->SetX($MARGEN);
$pdf->MultiCell($UTIL,4,utf8_decode($reg->sucursal_nombre),0,'C');

$pdf->SetX($MARGEN);
$pdf->MultiCell($UTIL,4,"Tel: ".$reg->sucursal_telefono,0,'C');

$pdf->SetX($MARGEN);
$pdf->MultiCell($UTIL,4,utf8_decode($reg->sucursal_direccion),0,'C');

$pdf->Ln(2);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,3,"","B",1);

// ---------------------------------------
// RESUMEN GENERAL
// ---------------------------------------
$pdf->SetFont('Arial','B',9);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,5,utf8_decode("RESUMEN GENERAL"),0,1,'C');

$pdf->SetFont('Arial','',8);

function linea($pdf, $txt, $valor, $MARGEN, $UTIL) {
    $pdf->SetX($MARGEN);
    $pdf->Cell($UTIL/2,4,$txt,0,0,'L');
    $pdf->Cell($UTIL/2,4,$valor,0,1,'R');
}

linea($pdf,"EFECTIVO INGRESADO:",        "Q ".number_format($reg->total_efectivo,2),$MARGEN,$UTIL);
linea($pdf,"EFEC. APERTURA:",        "Q ".number_format($reg->total_efectivo_inicio,2),$MARGEN,$UTIL);
linea($pdf,"S/F:",             "Q ".number_format($reg->total_efectivo_cierre_operaciones,2),$MARGEN,$UTIL);
$pdf->Ln(2);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,3,"","B",1);
linea($pdf,"EFECTIVO:",        "Q ".number_format($reg->total_ventas_diarias_efectivo,2),$MARGEN,$UTIL);
linea($pdf,"CREDITO:",         "Q ".number_format($reg->total_ventas_diarias_credito,2),$MARGEN,$UTIL);
linea($pdf,"TARJETA:",         "Q ".number_format($reg->total_ventas_diarias_tarjeta,2),$MARGEN,$UTIL);
linea($pdf,"TRANSFERENCIA:",   "Q ".number_format($reg->total_ventas_diarias_transferencia,2),$MARGEN,$UTIL);
linea($pdf,"GASTOS:",          "Q ".number_format($reg->total_ventas_gastosEfectivo,2),$MARGEN,$UTIL);

$pdf->Ln(3);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,4,"","B",1);

// ---------------------------------------
// TABLA DE VENTAS SIMPLIFICADA 79mm
// ---------------------------------------
$pdf->SetFont('Arial','B',9);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,5,"VENTAS DEL DIA",0,1,'C');

$pdf->SetFont('Arial','B',8);
$pdf->SetX($MARGEN);
$pdf->Cell(20,5,"ID",1,0,'C');
$pdf->Cell(28,5,"TOTAL",1,0,'C');
$pdf->Cell(30,5,"DOC",1,1,'C');

// Obtener ventas
require_once "../modelos/Venta.php";  
$venta = new Venta();
$rspta2 = $venta->listarVentasCierre($id);

// Contenido
$pdf->SetFont('Arial','',8);
while ($d = $rspta2->fetch_object()) {
    $pdf->SetX($MARGEN);

    $pdf->Cell(20,5,$d->idventa,1,0,'C');
    $pdf->Cell(28,5,"Q ".number_format($d->total_venta,2),1,0,'R');
    $pdf->Cell(30,5,$d->tipo_comprobante,1,1,'C');
}

$pdf->Ln(3);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,3,"","B",1);

// ---------------------------------------
// PIE DEL DOCUMENTO
// ---------------------------------------
$pdf->Ln(3);
$pdf->SetFont('Arial','',7);
$pdf->SetX($MARGEN);
$pdf->MultiCell($UTIL,4,utf8_decode("Usuario: ".$reg->usuario),0,'L');

$pdf->Ln(4);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,5,"Firma: ____________________",0,1,'L');

$pdf->Ln(3);
$pdf->SetFont('Arial','B',7);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,5,".:: ULTIMA LINEA ::.",1,1,'C');

$pdf->Output("Cierre_Caja_79mm_".$id.".pdf","I");
?>
