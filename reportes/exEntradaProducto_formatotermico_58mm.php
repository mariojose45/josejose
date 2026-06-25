<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';

if (!isset($_SESSION["nombre"])) exit("Error de sesión");
if ($_SESSION["entradaproducto"] != 1) exit("Sin permisos");

require_once "../modelos/Entrada_pro_sucursal.php";
$entradaprosucursal = new Entradaprosucursal();

require("FormatoMuchoshojas.php");

$id = $_GET["id"];
$rspta = $entradaprosucursal->entradaprosucursalcabecera($id);
$reg = $rspta->fetch_object();

// ===============================================
$ANCHO = 78;        // Ancho real del ticket
$MARGEN = 1;
$UTIL = $ANCHO - 2;
// ===============================================

$pdf = new FPDF('P','mm',array($ANCHO,2000));
$pdf->AddPage();

// =============== LOGO ===============
$logo = '../files/articulos/'.$reg->sucursal_imagen;
if ($reg->sucursal_imagen == "" || $reg->sucursal_imagen == "0") {
    $logo = '../files/articulos/1590204245.jpg';
}
$pdf->Image($logo, 17, 4, 40); // centrado en 78mm

// =============== TITULO ================
$pdf->Ln(36);
$pdf->SetFont('Arial','B',11);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,5,utf8_decode("ENTRADA DE PRODUCTO"),0,1,'C');

$pdf->SetFont('Arial','',9);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,4,"Entrada No: ".$reg->idtraladosucursal,0,1,'C');

$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,4,"Fecha: ".$reg->fecha,0,1,'C');

$pdf->Ln(1);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,3,"","B",1);

// =============== DATOS DE LA EMPRESA ===============
$pdf->SetFont('Arial','',9);

$pdf->SetX($MARGEN);
$pdf->MultiCell($UTIL,4,utf8_decode($reg->sucursal_nombre),0,'C');

$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,4,"Nit: ".$reg->sucursal_nit,0,1,'C');

$pdf->SetX($MARGEN);
$pdf->MultiCell($UTIL,4,utf8_decode("Direc: ".$reg->sucursal_direccion),0,'C');

$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,4,"Tel: ".$reg->sucursal_telefono,0,1,'C');

$pdf->Ln(1);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,3,"","B",1);

// =============== INFORMACIÓN DE TRASLADO ===============
$pdf->SetFont('Arial','',8);

$pdf->SetX($MARGEN);
$pdf->MultiCell($UTIL,4,utf8_decode("Origen: ".$reg->nombresucursalorigen),0,'L');

$pdf->SetX($MARGEN);
$pdf->MultiCell($UTIL,4,utf8_decode("Destino: ".$reg->nombresucursaldestino),0,'L');

$pdf->SetX($MARGEN);
$pdf->MultiCell($UTIL,4,utf8_decode("Descripción: ".$reg->descripcion_entrada_producto),0,'L');

$pdf->Ln(2);
$pdf->SetFont('Arial','B',9);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,5,"PRODUCTOS",0,1,'C');

$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,3,"","B",1);

// =============== DETALLE DE PRODUCTOS =================
$rsptad = $entradaprosucursal->entradaprosucursaltadetalle($id);

$totalCant = 0;
$totalVenta = 0;

while ($d = $rsptad->fetch_object()) {
    $subtotal=$d->cantidad * $d->precio_venta;
    // Nombre del artículo
    $pdf->SetFont('Arial','',8);
    $pdf->SetX($MARGEN);
    $pdf->MultiCell($UTIL,4,
        utf8_decode($d->articulo." ".$d->descripcion_detalle),
        0,'L'
    );

    // Línea de cantidades
    $linea = "Cant: ".$d->cantidad."   PU: ".number_format($d->precio_venta,2)."   Sub: ".number_format($subtotal,2);

    $pdf->SetFont('Arial','',8);
    $pdf->SetX($MARGEN);
    $pdf->Cell($UTIL,4,$linea,0,1,'L');

    // Separador
    $pdf->SetX($MARGEN);
    $pdf->Cell($UTIL,3,"","B",1);

    $totalCant += $d->cantidad;
    $totalVenta += $subtotal;
}

// ================= TOTALES ====================
$pdf->Ln(2);
$pdf->SetFont('Arial','',9);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,4,utf8_decode("Total Artículos: ").$totalCant,0,1,'L');

$pdf->SetFont('Arial','B',12);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,6,"TOTAL: Q ".number_format($totalVenta,2),0,1,'R');

// ================= TOTAL EN LETRAS =================
require_once "num2letras.php";
$letras = num2letras($totalVenta);

$pdf->Ln(1);
$pdf->SetFont('Arial','',8);
$pdf->SetX($MARGEN);
$pdf->MultiCell($UTIL,4,utf8_decode("Total en Letras: ".$letras." Quetzales"),0,'C');

$pdf->Ln(1);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,3,"","B",1);

// ================== FIRMAS ======================
$pdf->Ln(2);
$pdf->SetFont('Arial','',8);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,5,"Firma Entrada: ______________________",0,1,'L');

$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,5,"Firma Recibido: _____________________",0,1,'L');

// ================== PIE DEL TICKET ===================
$pdf->Ln(4);
$pdf->SetFont('Arial','',7);
$pdf->SetX($MARGEN);
$pdf->MultiCell($UTIL,4,utf8_decode($reg->empresadesarrollo),0,'C');

$pdf->Ln(1);
$pdf->SetFont('Arial','B',8);
$pdf->SetX($MARGEN);
$pdf->Cell($UTIL,5,".:: ULTIMA LINEA ::.",1,1,'C');

$pdf->Output("Entrada_78mm_".$reg->idtraladosucursal.".pdf","I");

?>
