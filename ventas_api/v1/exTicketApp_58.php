<?php
//Activamos el almacenamiento en el buffer
// Activamos el almacenamiento en el buffer
ob_start();
define('BYPASS_SESSION', true);


//TODAS LAS DEMAS SUCURSALES
//Incluímos el archivo Factura.php
require('../../reportes/FormatoCartaConFacturaTickekt.php');

//Establecemos los datos de la empresa
include '../../reportes/empresa.php';

//Incluímos la clase Cotizaciones
require_once "../../modelos/Cotizaciones.php";
$cotizaciones = new Cotizaciones();

$rsptav = $cotizaciones->ventacabecera2($_GET["id"]);
//Recorremos todos los valores obtenidos
$regv = $rsptav->fetch_object();

//Establecemos la configuración del ticket
$pdf = new PDF_Invoice('P', 'mm', array(58, 2500));
$pdf->AddPage();

$pdf->fact_dev(utf8_decode("ENVIO"), "");
$pdf->temporaire("");

$pdf->SetXY(110, 1);
$pdf->Multicell(100, 4, utf8_decode(""), 0, "C");

$url = '../../files/articulos/';
$color_r_texto = 255;
$color_g_texto = 255;
$color_b_texto = 255;

$color_r = 0;
$color_g = 0;
$color_b = 0;

$xposision = 1;
$ancchodefial = 58;

if ($regv->estado == 'Anulado') {
    $pdf->Image($anulado, 10, 150, 40, 25);
}

$pdf->SetXY($xposision, 1);
$pdf->Image($url . $regv->sucursal_imagen, 10, 3, 40, 25);
$pdf->SetFont('Arial', '', 11);
$pdf->SetXY($xposision, 42);
$pdf->Multicell($ancchodefial, 4, utf8_decode($regv->nombre_comercial), 0, "C");

$pdf->Ln(1);
$pdf->SetX($xposision);
$pdf->Multicell($ancchodefial, 4, utf8_decode("Direc: " . $regv->direccion_fiscal), 0, "C");
$pdf->Ln(1);
$pdf->SetX($xposision);
$pdf->Multicell($ancchodefial, 4, "Tels: " . $regv->sucursal_telefono, 0, "C");
$pdf->Ln(1);
$pdf->SetX($xposision);
$pdf->Multicell($ancchodefial, 4, "  Email: " . $regv->sucursal_email, 0, "C");

$pdf->Ln(1);
$pdf->SetX($xposision);
$pdf->Multicell($ancchodefial, 4, utf8_decode("Fecha Emision: " . $regv->fecha), 0, "C");

$pdf->Ln(1);
$pdf->SetX($xposision);
$pdf->Cell($ancchodefial, 5, "", "B", 0, "C");

$pdf->Ln(6);
$pdf->SetX($xposision);
$pdf->Multicell($ancchodefial, 4, utf8_decode("Clie: " . $regv->cliente), 0, "L");
$pdf->Ln(5);
$pdf->SetX($xposision);
$pdf->Multicell($ancchodefial, 4, utf8_decode("Direc: " . $regv->direccion), 0, "L");
$pdf->Ln(5);
$pdf->SetX($xposision);
$pdf->Cell($ancchodefial, 4, utf8_decode($regv->tipo_documento . ": " . $regv->num_documento), 0, "L");
$pdf->Ln(5);
$pdf->SetX($xposision);
$pdf->Multicell($ancchodefial, 4, utf8_decode("Tels: " . $regv->telefono), 0, "L");

$pdf->Ln(5);
$pdf->SetX($xposision);
$pdf->Cell($ancchodefial, 5, "", "B", 0, "C");

$pdf->Ln(5);
$pdf->SetX($xposision);
$pdf->SetTextColor($color_r_texto, $color_g_texto, $color_b_texto);
$pdf->SetFillColor($color_r, $color_g, $color_b);
$pdf->Cell($ancchodefial, 3.5, ".::DATOS DE PRODUCTOS::.", 1, 0, 'C', 1);
$pdf->SetTextColor(0, 0, 0);

$pdf->Ln(5);
$pdf->SetX($xposision);
$pdf->SetFillColor($color_r, $color_g, $color_b);
$pdf->Cell($ancchodefial, 10, " ", 1, 0, 'C', 1);
$pdf->SetTextColor($color_r_texto, $color_g_texto, $color_b_texto);
$pdf->Ln(1);
$pdf->SetX($xposision);
$pdf->cell($ancchodefial, 4, "ARTICULO", 0, 0, 'C');
$pdf->Ln(1);
$pdf->SetX($xposision);
$pdf->cell(19, 10, "CAN");
$pdf->cell(19, 10, "P.U.");
$pdf->cell(19, 10, "SUB");
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(10);
$pdf->SetX($xposision);

// ===========================
// ==== INICIO CAMBIO (agrupación padre/hijos) ====
// Traemos todas las líneas (padres + hijos) SOLO desde ventadetalle2().
// Asegúrate que ventadetalle2() devuelva: tipo, idarticulopadre, idarticulo, cantidad, precio_venta (o q_ref), subtotal, articulo, presen, descripcion_detalle.
$rsptad = $cotizaciones->ventadetalle2($_GET["id"]);

$items = [];
while ($regd = $rsptad->fetch_object()) {
    $items[] = $regd;
}

// Separar líneas padre e indexar hijos por idarticulopadre
$parents  = [];
$children = [];
foreach ($items as $it) {
    $tipo = isset($it->tipo) ? trim((string)$it->tipo) : '';
    if ($tipo === 'Topping' || $tipo === 'Extra') {
        $padre = (int)$it->idarticulopadre;
        if (!isset($children[$padre])) $children[$padre] = [];
        $children[$padre][] = $it;
    } else {
        $parents[] = $it;
    }
}

setlocale(LC_MONETARY, "en_US");

foreach ($parents as $regd) {
    // --------- LÍNEA PADRE ----------
    $descripcion = utf8_decode("{$regd->articulo} {$regd->presen} {$regd->descripcion_detalle}");

    $yInicio = $pdf->GetY();
    $pdf->MultiCell($ancchodefial, 4, $descripcion, 0, 'L');
    $yFin = $pdf->GetY();
    $yFin += 2; // espacio adicional
    $pdf->SetY($yFin);
    $pdf->SetX(1);

    // Cantidad, P.U., Subtotal del padre
    $pdf->Cell(15, 4, $regd->cantidad, 0, 0, 'L');
    $pdf->Cell(21, 4, number_format($regd->q_ref, 2, '.', ','), 0, 0, 'C');     // P.U.
    $pdf->Cell(21, 4, number_format($regd->subtotal, 2, '.', ','), 0, 0, 'R');  // Sub
    $pdf->Ln(5);
    $pdf->SetX(1);

    // --------- HIJOS (TOPPINGS / EXTRAS) ----------
    $idPadre = (int)$regd->idarticulo;
    if (!empty($children[$idPadre])) {

        // Ordenar: primero Topping, luego Extra (y opcionalmente por nombre)
        $kids = $children[$idPadre];
        $order = ['Topping' => 0, 'Extra' => 1];
        usort($kids, function ($a, $b) use ($order) {
            $ta = isset($a->tipo) ? $a->tipo : '';
            $tb = isset($b->tipo) ? $b->tipo : '';
            $pa = $order[$ta] ?? 99;
            $pb = $order[$tb] ?? 99;
            if ($pa !== $pb) return $pa <=> $pb;
            return strcasecmp((string)$a->articulo, (string)$b->articulo);
        });

        foreach ($kids as $hijo) {

            // Descripción con sangría (usa "--"; si quieres la flecha, cambia por "  \xE2\x86\xB3 ")
            $descHijo = "  -- " . utf8_decode("{$hijo->articulo} {$hijo->presen} {$hijo->descripcion_detalle}");

            if (isset($hijo->tipo) && $hijo->tipo === 'Topping') {
                // ======= TOPPING: SOLO DESCRIPCIÓN, SIN CAN / P.U. / SUB =======
                $pdf->MultiCell($ancchodefial, 4, $descHijo, 0, 'L');
                // pequeño espacio visual
                $yH = $pdf->GetY();
                $pdf->SetY($yH + 1);
                $pdf->SetX(1);
            } else {
                // ======= EXTRA: CON CANTIDAD, P.U. Y SUBTOTAL =======
                $pdf->MultiCell($ancchodefial, 4, $descHijo, 0, 'L');
                $yH = $pdf->GetY();
                $pdf->SetY($yH + 1);
                $pdf->SetX(1);

                $cantidadHijo = isset($hijo->cantidad) ? (float)$hijo->cantidad : 0.0;
                $puHijo  = isset($hijo->precio_venta) ? (float)$hijo->precio_venta
                    : (isset($hijo->q_ref) ? (float)$hijo->q_ref : 0.0);
                $subHijo = isset($hijo->subtotal) ? (float)$hijo->subtotal : ($puHijo * $cantidadHijo);

                $pdf->Cell(15, 4, number_format($cantidadHijo, 2, '.', ','), 0, 0, 'L');
                $pdf->Cell(21, 4, number_format($puHijo,      2, '.', ','), 0, 0, 'C');
                $pdf->Cell(21, 4, number_format($subHijo,     2, '.', ','), 0, 0, 'R');
                $pdf->Ln(5);
                $pdf->SetX(1);
            }
        }
    }
}
// ==== FIN CAMBIO (agrupación padre/hijos) ====

$pdf->Ln(1);
$pdf->SetX($xposision);
$pdf->Cell($ancchodefial, 5, "", "B", 0, "C");

require_once "../../reportes/num2letras.php";
$conletras = $regv->total_venta;
$conletrasresultado = num2letras($conletras);

$pdf->Ln(5);
$pdf->SetX($xposision);
$pdf->Cell($ancchodefial, 4, utf8_decode("SUBTOTAL: " . $regv->totalgeneral), 0, 0, "R");
$pdf->Ln(5);
$pdf->SetX($xposision);
$pdf->Cell($ancchodefial, 4, utf8_decode("DESCUENTO: " . $regv->total_ventades), 0, 0, "R");
$pdf->Ln(5);
$pdf->SetX($xposision);
$pdf->Cell($ancchodefial, 4, utf8_decode("TOTAL: " . $regv->total_venta), 0, 0, "R");

$pdf->Ln(5);
$pdf->SetX($xposision);
$pdf->Multicell($ancchodefial, 4, utf8_decode("Total en Letras: " . $conletrasresultado . " Quetzales"), 0, "C");

$pdf->Ln(5);
$pdf->SetX($xposision);
$pdf->Cell($ancchodefial, 5, "", "B", 0, "C");

$pdf->Ln(5);
$pdf->SetX($xposision);
$pdf->Multicell($ancchodefial, 4, utf8_decode("Le atendio: " . $regv->usuario), 0, "C");
$pdf->Ln(5);
$pdf->SetX($xposision);
$pdf->Multicell($ancchodefial, 4, utf8_decode("Vendedor: " . $regv->nombre_vendedor), 0, "C");
$pdf->Ln(5);
$pdf->SetX($xposision);
$pdf->Multicell($ancchodefial, 4, utf8_decode("# Venta: " . $regv->num_comprobante), 0, "C");
$pdf->Ln(5);
$pdf->SetX($xposision);
$pdf->Multicell($ancchodefial, 4, utf8_decode("Nº de venta control interno: #: " . $regv->idventa), 0, "C");

$pdf->Ln(5);
$pdf->SetX($xposision);
$pdf->Cell($ancchodefial, 5, "", "B", 0, "C");
$pdf->Ln(5);
$pdf->SetX($xposision);
$pdf->Multicell($ancchodefial, 4, utf8_decode($regv->empresadesarrollo), 0, "C");
$pdf->Ln(5);
$pdf->SetX($xposision);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFillColor($color_r, $color_g, $color_b);
$pdf->Cell($ancchodefial, 5, ".::ULTIMA LINEA::.", 1, 0, 'C', 1);
$pdf->SetTextColor(0, 0, 0);

$pdf->Output('Envio No ' . $regv->num_comprobante . ".pdf", 'I');


ob_end_flush();
