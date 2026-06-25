<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';

if (!isset($_SESSION["nombre"])) exit("Debe iniciar sesión");
if ($_SESSION["salidaproducto"] != 1) exit("Sin permisos");

require('FormatoMuchoshojasBlanco.php');
require_once "../modelos/Salida_pro_sucursal.php";

$salida = new Salidaprosucursal();
$id = $_GET["id"];

$rspta = $salida->salidaprosucursalcabecera($id);
$reg = $rspta->fetch_object();

$pdf = new PDF_Invoice('P', 'mm', 'A4');
$pdf->AddPage();

$y = 10;

/* ===========================================================
   FUNCIÓN: CABECERA (SE REPITE EN CADA PÁGINA)
=========================================================== */
function imprimirCabecera($pdf, $reg)
{
    global $y;

    $pdf->SetFont('Arial','B',14);
    $pdf->SetXY(10,$y);
    $pdf->Cell(190,8,utf8_decode("SALIDA DE PRODUCTO - SUCURSAL"),0,1,"C");

    $pdf->SetFont('Arial','',10);
    $pdf->SetXY(10,$y+10);
    $pdf->Cell(190,6,"Salida No: ".$reg->idtraladosucursal,0,1,"C");

    $pdf->SetXY(10,$y+16);
    $pdf->Cell(190,6,"Fecha: ".$reg->fecha,0,1,"C");

    // Logo
    $logo = "../files/articulos/".$reg->sucursal_imagen;
    if ($reg->sucursal_imagen == "" || $reg->sucursal_imagen == "0") {
        $logo = "../files/articulos/1590204245.jpg";
    }
    $pdf->Image($logo, 15, 10, 30);

    // Datos sucursal
    $pdf->SetFont('Arial','',9);
    $pdf->SetXY(50,32);
    $pdf->MultiCell(150,5,
        utf8_decode(
            $reg->sucursal_nombre."\n".
            "Nit: ".$reg->sucursal_nit."\n".
            "Dirección: ".$reg->sucursal_direccion."\n".
            "Tel: ".$reg->sucursal_telefono."    ".$reg->sucursal_email
        ),0,"L"
    );

    $pdf->Ln(4);
    $pdf->SetX(10);
    $pdf->Cell(190,0,"","B",1);

    // Segunda sección
    $pdf->Ln(3);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(190,5,"Sucursal Origen: ".$reg->nombresucursalorigen,0,1);
    $pdf->Cell(190,5,"Sucursal Destino: ".$reg->nombresucursaldestino,0,1);
    $pdf->Cell(190,5,"Descripción: ".$reg->descripcion_salida_producto,0,1);

    $pdf->Ln(3);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(190,7,"DATOS DE PRODUCTOS",0,1,"C");

    $pdf->SetFont('Arial','B',9);

    $pdf->Cell(15,7,"CANT",1,0,'C');
    $pdf->Cell(105,7,"DESCRIPCION",1,0,'C');
    $pdf->Cell(30,7,"CODIGO",1,0,'C');
    $pdf->Cell(20,7,"P.U.",1,0,'C');
    $pdf->Cell(20,7,"TOTAL",1,1,'C');
}

/* ===========================================================
   IMPRIMIR PRIMERA CABECERA
=========================================================== */
imprimirCabecera($pdf, $reg);

/* ===========================================================
   DETALLES CON CONTROL DE SALTO DE PÁGINA
=========================================================== */

$y = $pdf->GetY();
$rsptad = $salida->salidaprosucursaltadetalle($id);
$totalCant = 0;

while ($d = $rsptad->fetch_object()) {

    // Validar espacio restante antes de agregar otra línea
    if ($pdf->GetY() > 240) {  
        $pdf->AddPage();
        imprimirCabecera($pdf, $reg);
    }

    $pdf->SetFont('Arial','',9);

    $pdf->Cell(15,6,$d->cantidad,1,0,'C');
    $pdf->Cell(105,6,utf8_decode($d->articulo." ".$d->descripcion_detalle),1,0,'L');
    $pdf->Cell(30,6,$d->codigo,1,0,'C');
    $pdf->Cell(20,6,number_format($d->precio_venta,2),1,0,'R');
    $pdf->Cell(20,6,number_format($d->subtotal,2),1,1,'R');

    $totalCant += $d->cantidad;
}

/* ===========================================================
   FOOTER SIEMPRE ABAJO
=========================================================== */
$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,6,"Total Cantidad: ".$totalCant,0,1,"R");

$pdf->Ln(10);
$pdf->SetFont('Arial','',10);
$pdf->Cell(190,8,"Firma Salida: ___________________________",0,1);
$pdf->Cell(190,8,"Firma Recibido: ___________________________",0,1);

$pdf->Ln(10);
$pdf->SetFont('Arial','I',9);
$pdf->Cell(190,6,utf8_decode($reg->empresadesarrollo),0,1,"C");

$pdf->Ln(5);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(190,8,".:: ULTIMA LINEA ::.",1,1,"C");

$pdf->Output("Salida_Carta_".$reg->idtraladosucursal.".pdf","I");

ob_end_flush();
?>
