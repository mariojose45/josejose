<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';

if (!isset($_SESSION["nombre"])) exit("Error de sesión");
if ($_SESSION["salidaproducto"] != 1) exit("Sin permisos");

require_once "../modelos/Salida_pro_sucursal.php";
$salidaprosucursal = new Salidaprosucursal();

require("FormatoMuchoshojas.php");

$id = $_GET["id"];
$rspta = $salidaprosucursal->salidaprosucursalcabecera($id);
$reg = $rspta->fetch_object();

// --------------------------------------
$ANCHO = 58;
$MARGEN = 1;
$UTIL = $ANCHO - 2;
// --------------------------------------

$pdf = new FPDF('P','mm',array($ANCHO,2000));
$pdf->AddPage();

// ================= LOGO =====================
$logo = '../files/articulos/'.$reg->sucursal_imagen;
if ($reg->sucursal_imagen == "" || $reg->sucursal_imagen == "0") {
    $logo = '../files/articulos/1590204245.jpg';
}
$pdf->Image($logo, 9, 4, 40);

// ================= TITULO ===================
$pdf->Ln(36); // ← CORREGIDO
$pdf->SetFont('Arial','B',10);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,5,utf8_decode("SALIDA DE PRODUCTO"),0,1,'C');

$pdf->SetFont('Arial','',8);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,4,"Salida No: ".$reg->idtraladosucursal,0,1,'C');
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,4,"Fecha: ".$reg->fecha,0,1,'C');

$pdf->Ln(1);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,3,"","B",1);

// ============= DATOS SUCURSAL ================
$pdf->SetFont('Arial','',8);

$pdf->SetX($MARGEN);
$pdf->MultiCell($UTIL,4,utf8_decode($reg->sucursal_nombre),0,'C');

$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,4,"Nit: ".$reg->sucursal_nit,0,1,'C');

$pdf->SetX($MARGEN);
$pdf->MultiCell($UTIL,4,utf8_decode("Direc: ".$reg->sucursal_direccion),0,'C');

$pdf->SetX($MARGEN);
$pdf->MultiCell($UTIL,4,"Tel: ".$reg->sucursal_telefono,0,'C');

$pdf->Ln(1);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,3,"","B",1);

// ============== INFO TRASLADO ==================
$pdf->SetFont('Arial','',8);

$pdf->SetX($MARGEN);
$pdf->MultiCell($UTIL,4,"Origen: ".$reg->nombresucursalorigen,0,'L');

$pdf->SetX($MARGEN);
$pdf->MultiCell($UTIL,4,"Destino: ".$reg->nombresucursaldestino,0,'L');

$pdf->SetX($MARGEN);
$pdf->MultiCell($UTIL,4,utf8_decode("Descripción: ".$reg->descripcion_salida_producto),0,'L');

$pdf->Ln(2);
$pdf->SetFont('Arial','B',8);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,5,"PRODUCTOS",0,1,'C');

$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,3,"","B",1);

// ================= DETALLE ======================
$rsptad = $salidaprosucursal->salidaprosucursaltadetalle($id);
$totalCant = 0;

while ($d = $rsptad->fetch_object()) {

    $pdf->SetFont('Arial','',8);
    $pdf->SetX($MARGEN);
    $pdf->MultiCell($UTIL,4,utf8_decode($d->articulo." ".$d->descripcion_detalle),0,'L');

    $pdf->SetFont('Arial','',8);
    $pdf->SetX($MARGEN);
    $linea = "Cant: ".$d->cantidad."   PU: ".number_format($d->precio_venta,2)."   Sub: ".number_format($d->subtotal,2);
    $pdf->Cell($UTIL,4,$linea,0,1,'L');

    $pdf->SetX($MARGEN);
    $pdf->Cell($UTIL,3,"","B",1);

    $totalCant += $d->cantidad;
}

// ================= TOTALES =======================
$pdf->Ln(2);
$pdf->SetFont('Arial','',8);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,4,utf8_decode("Total Artículos: ").$totalCant,0,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,5,"TOTAL: Q ".number_format($reg->total_venta,2),0,1,'R');

// ============== TOTAL EN LETRAS ==================
require_once "num2letras.php";
$letras = num2letras($reg->total_venta);

$pdf->SetFont('Arial','',7);
$pdf->SetX($MARGEN);
$pdf->MultiCell($UTIL,4,utf8_decode("Total en Letras: ".$letras." Quetzales"),0,'C');

$pdf->Ln(1);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,3,"","B",1);

// ================== FIRMAS ========================
$pdf->Ln(2);
$pdf->SetFont('Arial','',8);

$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,4,"Firma Salida: ____________________",0,1,'L');

$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,4,"Firma Recibido: __________________",0,1,'L');

// ================== PIE ===========================
$pdf->Ln(3);
$pdf->SetFont('Arial','',7);
$pdf->SetX($MARGEN);
$pdf->MultiCell($UTIL,4,utf8_decode($reg->empresadesarrollo),0,'C');

$pdf->Ln(1);
$pdf->SetFont('Arial','B',7);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,5,".:: ULTIMA LINEA ::.",1,1,'C');

$pdf->Output("Salida_58mm_No_".$reg->idtraladosucursal.".pdf","I");

?>
