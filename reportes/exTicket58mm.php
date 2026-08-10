<?php

ob_start();

// Archivos necesarios
require('FormatoCartaConFacturaTickekt.php');
include 'empresa.php';
require_once "../modelos/Cotizaciones.php";
require_once "num2letras.php";

// Objeto Cotizaciones
$cotizaciones = new Cotizaciones();

// Cabecera de venta
$rsptav = $cotizaciones->ventacabecera2($_GET["id"]);
$regv = $rsptav->fetch_object();

// Configuración PDF 58mm
$pdf = new PDF_Invoice('P', 'mm', array(58, 2500));
$pdf->AddPage();
$pdf->temporaire("");

// Configuración general
$url = '../files/articulos/';

$color_r_texto = 255;
$color_g_texto = 255;
$color_b_texto = 255;

$color_r = 0;
$color_g = 0;
$color_b = 0;

$xposision = 1;
$ancchodefial = 58;

// Venta anulada
if ($regv->estado == 'Anulado') {
    $pdf->Image($anulado, 10, 150, 40, 25);
}

// Logo
if (!empty($regv->sucursal_imagen)) {
    $pdf->SetXY($xposision, 1);
    $pdf->Image($url . $regv->sucursal_imagen, 9, 3, 40, 25);
}

// Empresa
$pdf->SetFont('Arial', '', 11);
$pdf->SetXY($xposision, 32);
$pdf->MultiCell($ancchodefial, 4, utf8_decode($regv->nombre_comercial), 0, "C");

// Teléfono
$pdf->Ln(1);
$pdf->SetX($xposision);
$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode("Tels: " . $regv->sucursal_telefono),
    0,
    "C"
);

// Separador
$pdf->Ln(1);
$pdf->SetX($xposision);
$pdf->Cell($ancchodefial, 5, "", "B", 0, "C");

// ENVIO
$pdf->Ln(7);
$pdf->SetX($xposision);
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell($ancchodefial, 5, utf8_decode("ENVIO"), 0, 1, "C");
$pdf->SetFont('Arial', '', 10);

// Fecha operación
$pdf->Ln(2);
$pdf->SetX($xposision);
$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode(
        "Fecha Operacion: " .
            date("d/m/Y H:i:s", strtotime($regv->fecha_creacion))
    ),
    0,
    "C"
);

// Número de venta
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetX($xposision);
$pdf->MultiCell(
    $ancchodefial,
    5,
    utf8_decode("# Venta: " . $regv->num_comprobante),
    0,
    "C"
);
$pdf->SetFont('Arial', '', 10);

// Separador
$pdf->Ln(1);
$pdf->SetX($xposision);
$pdf->Cell($ancchodefial, 5, "", "B", 0, "C");

// Datos cliente
$pdf->Ln(7);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetX($xposision);
$pdf->Cell($ancchodefial, 4, "DATOS CLIENTE", 0, 1, "C");
$pdf->SetFont('Arial', '', 10);

// Cliente
$pdf->Ln(3);
$pdf->SetX($xposision);
$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode("Cliente: " . $regv->cliente),
    0,
    "L"
);

// Forma de pago
$pdf->Ln(2);
$pdf->SetX($xposision);
$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode("Forma Pago: " . $regv->forma_pago),
    0,
    "L"
);

// Separador
$pdf->Ln(1);
$pdf->SetX($xposision);
$pdf->Cell($ancchodefial, 5, "", "B", 0, "C");

// Cabecera productos
$pdf->Ln(3);
$pdf->SetXY($xposision, $pdf->GetY() + 2);

// Columnas: dos líneas para 58mm
$pdf->Cell(58, 4, utf8_decode("DESCRIPCION"), 0, 1, 'L');
$pdf->SetX($xposision);
$pdf->Cell(15, 4, "CAN", 0, 0, 'L');
$pdf->Cell(20, 4, "PU", 0, 0, 'C');
$pdf->Cell(23, 4, "SUB", 0, 1, 'C');

// Línea separadora
$pdf->Ln(2);
$pdf->SetX($xposision);
$pdf->Cell($ancchodefial, 2, "", "T", 1, "C");
$pdf->Ln(1);

// Obtener detalle
$rsptad = $cotizaciones->ventadetalle2($_GET["id"]);

$articulos_map = [];
$cantidad_total = 0;

// Agrupar padres e hijos
while ($regd = $rsptad->fetch_object()) {

    $id_padre = !empty($regd->idarticulopadre)
        ? (int)$regd->idarticulopadre
        : 0;

    if (!isset($articulos_map[$id_padre])) {
        $articulos_map[$id_padre] = [];
    }

    $articulos_map[$id_padre][] = $regd;
}

// Productos principales
$productos_principales = $articulos_map[0] ?? [];

// Imprimir productos principales
foreach ($productos_principales as $p) {

    // Descripción igual que el HTML
    $descripcion = trim(
        $p->descripcion . " " .
            $p->descripcion_detalle . " " .
            $p->presen
    );

    $descripcion = utf8_decode($descripcion);

    // 1. Descripción multilínea (todo el ancho)
    $pdf->SetX($xposision);
    $pdf->MultiCell(58, 4, $descripcion, 0, 'L');

    // 2. Cantidad, PU y Subtotal en la siguiente línea
    $pdf->SetX($xposision);
    $pdf->Cell(15, 4, floatval($p->cantidad), 0, 0, 'L');
    $pdf->Cell(20, 4, number_format($p->q_ref, 2, '.', ','), 0, 0, 'C');
    $pdf->Cell(23, 4, number_format($p->subtotal, 2, '.', ','), 0, 1, 'C');



    // Contar solo productos principales
    $cantidad_total += $p->cantidad;

    // Espacio entre productos
    $pdf->Ln(1);
}

// Separador
$pdf->SetX($xposision);
$pdf->Cell($ancchodefial, 3, "", "B", 0, "C");

// Totales
$pdf->Ln(6);

$pdf->SetX($xposision);
$pdf->Cell(35, 4, "SUBTOTAL:", 0, 0, "R");
$pdf->Cell(
    23,
    4,
    "Q " . number_format($regv->totalgeneral, 2),
    0,
    1,
    "R"
);

// Descuento
$pdf->SetX($xposision);
$pdf->Cell(35, 4, "DESCUENTO:", 0, 0, "R");
$pdf->Cell(
    23,
    4,
    "Q " . number_format($regv->total_ventades, 2),
    0,
    1,
    "R"
);

// Total
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetX($xposision);
$pdf->Cell(35, 5, "TOTAL:", 0, 0, "R");
$pdf->Cell(
    23,
    5,
    "Q " . number_format($regv->total_venta, 2),
    0,
    1,
    "R"
);

$pdf->SetFont('Arial', '', 10);

// Total en letras
$conletras = $regv->total_venta;
$conletrasresultado = num2letras($conletras);

$pdf->Ln(2);
$pdf->SetX($xposision);
$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode(ucfirst($conletrasresultado) . " Quetzales"),
    0,
    "C"
);

// Forma de pago
$pdf->SetX($xposision);
$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode("Forma Pago: " . $regv->forma_pago),
    0,
    "C"
);

// Número de artículos
$pdf->Ln(2);
$pdf->SetX($xposision);
$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode("Nº de articulos: " . $cantidad_total),
    0,
    "L"
);

// Separador
$pdf->SetX($xposision);
$pdf->Cell($ancchodefial, 4, "", "B", 0, "C");

// Gracias
$pdf->Ln(7);
$pdf->SetX($xposision);
$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode("¡Gracias por su compra!"),
    0,
    "C"
);

// Sucursal
$pdf->SetX($xposision);
$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode($regv->sucursal_nombre),
    0,
    "C"
);

// Usuario
$pdf->SetX($xposision);
$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode("Le atendio: " . $regv->usuario),
    0,
    "C"
);

// Vendedor
$pdf->SetX($xposision);
$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode("Vendedor: " . $regv->nombre_vendedor),
    0,
    "C"
);

// Venta interna
$pdf->SetX($xposision);
$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode("Nº venta control interno: #" . $regv->idventa),
    0,
    "C"
);

// Efectivo
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetX($xposision);
$pdf->MultiCell(
    $ancchodefial,
    6,
    utf8_decode("Efectivo: " . $regv->cefectivo),
    0,
    "C"
);

// Cambio
$pdf->SetX($xposision);
$pdf->MultiCell(
    $ancchodefial,
    6,
    utf8_decode("Cambio: " . $regv->rescambio),
    0,
    "C"
);

$pdf->SetFont('Arial', '', 10);

// Orden / restaurante
if (!empty($regv->id_add_orden) && $regv->id_add_orden != null) {

    $pdf->SetFont('Arial', 'B', 13);

    // Orden
    $pdf->SetX($xposision);
    $pdf->MultiCell(
        $ancchodefial,
        5,
        utf8_decode("Orden #: " . $regv->id_add_orden),
        0,
        "C"
    );

    // Mesa
    $pdf->SetX($xposision);
    $pdf->MultiCell(
        $ancchodefial,
        5,
        utf8_decode("Mesa Orden #: " . $regv->mesa_orden),
        0,
        "C"
    );

    // Mesero
    $pdf->SetX($xposision);
    $pdf->MultiCell(
        $ancchodefial,
        5,
        utf8_decode("Mesero Orden: " . $regv->usuario_orden),
        0,
        "C"
    );

    $pdf->SetFont('Arial', '', 10);
}

// Separador
$pdf->Ln(3);
$pdf->SetX($xposision);
$pdf->Cell($ancchodefial, 4, "", "B", 0, "C");

// Empresa desarrollo
$pdf->Ln(6);
$pdf->SetX($xposision);
$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode($regv->empresadesarrollo),
    0,
    "C"
);

// Última línea
$pdf->Ln(5);
$pdf->SetX($xposision);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFillColor(0, 0, 0);
$pdf->Cell(
    $ancchodefial,
    5,
    ".::ULTIMA LINEA::.",
    1,
    1,
    'C',
    1
);

$pdf->SetTextColor(0, 0, 0);

// Espacio final para impresora
$pdf->Ln(10);

// Mostrar PDF
$pdf->Output(
    'Envio No ' . $regv->num_comprobante . '.pdf',
    'I'
);

ob_end_flush();
