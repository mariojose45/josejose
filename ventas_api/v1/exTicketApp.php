<?php
//Activamos el almacenamiento en el buffer
//TODAS LAS DEMAS SUCURSALES
//Incluímos el archivo Factura.php
require('../../reportes/FormatoCartaConFacturaTickektApp.php');

//Establecemos los datos de la empresa
include '../../reportes/empresa.php';

//Incluímos la clase Cotizaciones
require_once "../../modelos/Cotizaciones.php";
$cotizaciones = new Cotizaciones();

$rsptav = $cotizaciones->ventacabecera2($_GET["id"]);
//Recorremos todos los valores obtenidos
$regv = $rsptav->fetch_object();

// Establecemos la configuración del ticket con tamaño reducido y papel más corto
$pdf = new PDF_Invoice('P', 'mm', array(57, 500));
// Reducimos los márgenes para no usar SetX
$pdf->SetMargins(2, 5, 2);
$pdf->AddPage();

$url = '../../files/articulos/';
$color_r_texto = 255;
$color_g_texto = 255;
$color_b_texto = 255;

$color_r = 0;
$color_g = 0;
$color_b = 0;

$ancchodefial = 53; // Ajustado por el nuevo margen y tamaño

if ($regv->estado == 'Anulado') {
    $pdf->Image($anulado, 10, 150, 40, 25);
}

// Usamos Helvetica para mejor rendimiento en RawBT y menos peso
$pdf->SetFont('Helvetica', '', 7.5);

$pdf->Cell($ancchodefial, 4, utf8_decode($regv->nombre_comercial), 0, 1, "C");

$pdf->Cell($ancchodefial, 4, "Tels: " . $regv->sucursal_telefono, 0, 1, "C");
$pdf->Cell($ancchodefial, 4, "  Email: " . $regv->sucursal_email, 0, 1, "C");

$pdf->Cell($ancchodefial, 2, "-----------------------------------------", 0, 1, "C");
$pdf->Ln(2);

$pdf->Cell($ancchodefial, 4, "ENVIO", 0, 1, "C");
$pdf->Cell($ancchodefial, 4, utf8_decode("Fecha Emision: " . date("d/m/Y H:i:s", strtotime($regv->fecha_creacion))), 0, 1, "C");
$pdf->Cell($ancchodefial, 4, utf8_decode("# Venta: " . $regv->num_comprobante), 0, 1, "C");
$pdf->Cell($ancchodefial, 2, "-----------------------------------------", 0, 1, "C");
$pdf->Ln(2);

$pdf->Cell($ancchodefial, 4, "DATOS CLIENTE", 0, 1, "C");

$pdf->Cell($ancchodefial, 2, "-----------------------------------------", 0, 1, "C");
$pdf->Ln(2);

// Cliente puede ser largo
$pdf->MultiCell($ancchodefial, 3, utf8_decode("Clie: " . $regv->cliente), 0, "L");

$pdf->Cell($ancchodefial, 2, "-----------------------------------------", 0, 1, "C");
$pdf->Ln(2);

$pdf->Cell($ancchodefial, 4, ".::DATOS DE PRODUCTOS::.", 0, 1, 'C');

$pdf->Cell($ancchodefial, 2, "-----------------------------------------", 0, 1, "C");
$pdf->Ln(2);

$pdf->Cell(7, 4, "CAN", 0, 0, 'L');
$pdf->Cell(22, 4, "ARTICULO", 0, 0, 'L');
$pdf->Cell(12, 4, "P.U.", 0, 0, 'R');
$pdf->Cell(12, 4, "SUB", 0, 1, 'R');

$pdf->Cell($ancchodefial, 2, "-----------------------------------------", 0, 1, "C");
$pdf->Ln(2);

$rsptad = $cotizaciones->ventadetalle2($_GET["id"]);
setlocale(LC_MONETARY, "en_US");

while ($regd = $rsptad->fetch_object()) {
    $descripcion = utf8_decode("{$regd->descripcion} {$regd->descripcion_detalle} {$regd->presen}");

    $yInicio = $pdf->GetY();
    
    // Imprimimos la Cantidad
    $pdf->Cell(7, 3, $regd->cantidad, 0, 0, 'L');
    $xDesc = $pdf->GetX(); // Guardamos donde inicia la descripción
    
    // Movemos el cursor al espacio de los precios y subtotal
    $pdf->SetXY($xDesc + 22, $yInicio);
    $pdf->Cell(12, 3, number_format($regd->q_ref, 2, '.', ','), 0, 0, 'R');
    $pdf->Cell(12, 3, number_format($regd->subtotal, 2, '.', ','), 0, 1, 'R');
    $yFinCols = $pdf->GetY();
    
    // Volvemos a la posición de la descripción y dibujamos la celda multilinea
    $pdf->SetXY($xDesc, $yInicio);
    $pdf->MultiCell(22, 3, $descripcion, 0, 'L');
    $yFinDesc = $pdf->GetY();
    
    // Nos aseguramos que el cursor baje más allá de la descripción o los precios (el mayor)
    $pdf->SetY(max($yFinCols, $yFinDesc));
    
    $pdf->Ln(1); // Reducido en lugar de Ln(5)
}

$pdf->Cell($ancchodefial, 2, "-----------------------------------------", 0, 1, "C");
$pdf->Ln(2);

require_once "../../reportes/num2letras.php";
$conletras = $regv->total_venta;
$conletrasresultado = num2letras($conletras);

$pdf->Cell($ancchodefial, 4, utf8_decode("SUBTOTAL: " . $regv->totalgeneral), 0, 1, "R");
$pdf->Cell($ancchodefial, 4, utf8_decode("DESCUENTO: " . $regv->total_ventades), 0, 1, "R");
$pdf->Cell($ancchodefial, 4, utf8_decode("TOTAL: " . $regv->total_venta), 0, 1, "R");

$pdf->MultiCell($ancchodefial, 3, utf8_decode("Total en Letras: " . $conletrasresultado . " Quetzales"), 0, "C");



$pdf->Ln(1);
$pdf->Cell($ancchodefial, 2, "-----------------------------------------", 0, 1, "C");
$pdf->Ln(2);

$pdf->Cell($ancchodefial, 4, utf8_decode("Forma pago: " . $regv->forma_pago), 0, 1, "C");

$pdf->Cell($ancchodefial, 4, utf8_decode("¡Gracias por su compra! "), 0, 1, "C");
$pdf->Cell($ancchodefial, 4, utf8_decode($regv->sucursal_nombre), 0, 1, "C");
$pdf->Cell($ancchodefial, 4, utf8_decode("Le atendio: " . $regv->usuario), 0, 1, "C");
$pdf->Cell($ancchodefial, 4, utf8_decode("# Venta: " . $regv->num_comprobante), 0, 1, "C");
$pdf->Cell($ancchodefial, 4, utf8_decode("Nº int: " . $regv->idventa), 0, 1, "C");

$pdf->Cell($ancchodefial, 2, "-----------------------------------------", 0, 1, "C");
$pdf->Ln(2);


$pdf->Cell($ancchodefial, 2, "-----------------------------------------", 0, 1, "C");
$pdf->Ln(2);
$pdf->Cell($ancchodefial, 4, ".::ULTIMA LINEA::.", 0, 1, 'C');
$pdf->Cell($ancchodefial, 2, "-----------------------------------------", 0, 1, "C");
$pdf->Ln(2);

// Añadir margen extra al final imprimiendo un pequeño punto para que RawBT no recorte el espacio y la impresora expulse el papel
$pdf->Ln(12);
$pdf->SetFont('Helvetica', '', 4);
$pdf->Cell($ancchodefial, 2, ".", 0, 1, 'C');

// Salida a memoria directamente (sin nombre)
$pdf->Output('I');
