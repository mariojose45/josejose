<?php

// ============================================================
// ACTIVAR BUFFER
// ============================================================
ob_start();

// ============================================================
// ARCHIVOS NECESARIOS
// ============================================================
require('FormatoCartaConFacturaTickekt.php');

include 'empresa.php';

require_once "../modelos/Cotizaciones.php";

require_once "num2letras.php";

// ============================================================
// OBJETO COTIZACIONES
// ============================================================
$cotizaciones = new Cotizaciones();

// ============================================================
// CABECERA DE VENTA
// ============================================================
$rsptav = $cotizaciones->ventacabecera2($_GET["id"]);

$regv = $rsptav->fetch_object();

// ============================================================
// CONFIGURACIÓN PDF
// ============================================================
// Ticket de 78 mm
$pdf = new PDF_Invoice(
    'P',
    'mm',
    array(78, 2500)
);

$pdf->AddPage();

// ============================================================
// CONFIGURACIÓN GENERAL
// ============================================================

$pdf->temporaire("");

// Ruta imágenes
$url = '../files/articulos/';

// Colores
$color_r_texto = 255;
$color_g_texto = 255;
$color_b_texto = 255;

$color_r = 0;
$color_g = 0;
$color_b = 0;

// Posiciones
$xposision = 1;

$ancchodefial = 76;

// ============================================================
// VENTA ANULADA
// ============================================================
if ($regv->estado == 'Anulado') {

    $pdf->Image(
        $anulado,
        10,
        150,
        40,
        25
    );
}

// ============================================================
// LOGO
// ============================================================
if (!empty($regv->sucursal_imagen)) {

    $pdf->SetXY(
        $xposision,
        1
    );

    $pdf->Image(
        $url . $regv->sucursal_imagen,
        19,
        3,
        40,
        25
    );
}

// ============================================================
// EMPRESA
// ============================================================
$pdf->SetFont(
    'Arial',
    '',
    11
);

$pdf->SetXY(
    $xposision,
    32
);

$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode($regv->nombre_comercial),
    0,
    "C"
);

// ============================================================
// TELÉFONO
// ============================================================
$pdf->Ln(1);

$pdf->SetX($xposision);

$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode(
        "Tels: " .
            $regv->sucursal_telefono
    ),
    0,
    "C"
);

// ============================================================
// SEPARADOR
// ============================================================
$pdf->Ln(1);

$pdf->SetX($xposision);

$pdf->Cell(
    $ancchodefial,
    5,
    "",
    "B",
    0,
    "C"
);

// ============================================================
// ENVÍO
// ============================================================
$pdf->Ln(7);

$pdf->SetX($xposision);

$pdf->SetFont(
    'Arial',
    'B',
    13
);

$pdf->Cell(
    $ancchodefial,
    5,
    utf8_decode("ENVIO"),
    0,
    1,
    "C"
);

// Volvemos a fuente normal
$pdf->SetFont(
    'Arial',
    '',
    10
);

// ============================================================
// FECHA OPERACIÓN
// ============================================================
$pdf->Ln(2);

$pdf->SetX($xposision);

$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode(
        "Fecha Operacion: " .
            date(
                "d/m/Y H:i:s",
                strtotime($regv->fecha_creacion)
            )
    ),
    0,
    "C"
);

// ============================================================
// NÚMERO VENTA
// ============================================================
$pdf->Ln(2);

$pdf->SetFont(
    'Arial',
    'B',
    14
);

$pdf->SetX($xposision);

$pdf->MultiCell(
    $ancchodefial,
    5,
    utf8_decode(
        "# Venta: " .
            $regv->num_comprobante
    ),
    0,
    "C"
);

$pdf->SetFont(
    'Arial',
    '',
    10
);

// ============================================================
// SEPARADOR
// ============================================================
$pdf->Ln(1);

$pdf->SetX($xposision);

$pdf->Cell(
    $ancchodefial,
    5,
    "",
    "B",
    0,
    "C"
);

// ============================================================
// DATOS CLIENTE
// ============================================================
$pdf->Ln(7);

$pdf->SetFont(
    'Arial',
    'B',
    11
);

$pdf->SetX($xposision);

$pdf->Cell(
    $ancchodefial,
    4,
    "DATOS CLIENTE",
    0,
    1,
    "C"
);

$pdf->SetFont(
    'Arial',
    '',
    10
);

// ============================================================
// CLIENTE
// ============================================================
$pdf->Ln(3);

$pdf->SetX($xposision);

$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode(
        "Cliente: " .
            $regv->cliente
    ),
    0,
    "L"
);

// ============================================================
// FORMA DE PAGO
// ============================================================
$pdf->Ln(2);

$pdf->SetX($xposision);

$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode(
        "Forma Pago: " .
            $regv->forma_pago
    ),
    0,
    "L"
);

// ============================================================
// SEPARADOR
// ============================================================
$pdf->Ln(1);

$pdf->SetX($xposision);

$pdf->Cell(
    $ancchodefial,
    5,
    "",
    "B",
    0,
    "C"
);

// ============================================================
// CABECERA PRODUCTOS
// ============================================================
$pdf->Ln(3);

$pdf->SetX($xposision);

$pdf->SetXY(
    $xposision,
    $pdf->GetY() + 2
);

// ============================================================
// COLUMNAS
// 10 + 38 + 14 + 14 = 76 mm
// ============================================================
$pdf->Cell(
    10,
    4,
    "CANT.",
    0,
    0,
    'L'
);

$pdf->Cell(
    38,
    4,
    utf8_decode("DESCRIPCION"),
    0,
    0,
    'L'
);

$pdf->Cell(
    14,
    4,
    "P.U.",
    0,
    0,
    'R'
);

$pdf->Cell(
    14,
    4,
    "SUB",
    0,
    1,
    'R'
);

// eliminado

$pdf->Ln(2);
$pdf->SetX($xposision);
$pdf->Cell(
    $ancchodefial,
    2,
    "",
    "T", // Borde superior para que haga de línea
    1,
    "C"
);
$pdf->Ln(1);

// ============================================================
// OBTENER DETALLE
// ============================================================
$rsptad = $cotizaciones->ventadetalle2(
    $_GET["id"]
);

$articulos_map = [];

$cantidad_total = 0;

// ============================================================
// AGRUPAR PADRES E HIJOS
// MISMA LÓGICA DE TU HTML
// ============================================================
while ($regd = $rsptad->fetch_object()) {

    $id_padre =
        !empty($regd->idarticulopadre)
        ? (int)$regd->idarticulopadre
        : 0;

    if (!isset($articulos_map[$id_padre])) {

        $articulos_map[$id_padre] = [];
    }

    $articulos_map[$id_padre][] = $regd;
}

// Productos principales
$productos_principales =
    $articulos_map[0] ?? [];

// ============================================================
// IMPRIMIR PRODUCTOS PRINCIPALES
// ============================================================
foreach ($productos_principales as $p) {

    // ========================================================
    // DESCRIPCIÓN
    // Igual que tu HTML:
    // descripcion + descripcion_detalle + presen
    // ========================================================

    $descripcion = trim(
        $p->descripcion .
            " " .
            $p->descripcion_detalle .
            " " .
            $p->presen
    );

    $descripcion =
        utf8_decode($descripcion);

    $yInicio =
        $pdf->GetY();

    // ========================================================
    // CANTIDAD
    // ========================================================
    $pdf->SetX($xposision);

    $pdf->Cell(
        10,
        4,
        floatval($p->cantidad),
        0,
        0,
        'L'
    );

    $xDescripcion =
        $pdf->GetX();

    // ========================================================
    // PRECIO UNITARIO
    // ========================================================
    $pdf->SetXY(
        $xDescripcion + 38,
        $yInicio
    );

    $pdf->Cell(
        14,
        4,
        number_format(
            $p->q_ref,
            2,
            '.',
            ','
        ) . ' ',
        0,
        0,
        'R'
    );

    // ========================================================
    // SUBTOTAL
    // ========================================================
    $pdf->Cell(
        14,
        4,
        "Q " .
            number_format(
                $p->subtotal,
                2,
                '.',
                ','
            ),
        0,
        1,
        'R'
    );

    $yFinColumnas =
        $pdf->GetY();

    // ========================================================
    // DESCRIPCIÓN MULTILÍNEA
    // ========================================================
    $pdf->SetXY(
        $xDescripcion,
        $yInicio
    );

    $pdf->MultiCell(
        38,
        4,
        $descripcion,
        0,
        'L'
    );

    $yFinDescripcion =
        $pdf->GetY();

    // Nos colocamos debajo de la línea más alta
    $pdf->SetY(
        max(
            $yFinColumnas,
            $yFinDescripcion
        )
    );

    $pdf->SetX(
        $xposision
    );

    // ========================================================
    // HIJOS / TOPPINGS / EXTRAS
    // ========================================================

    $idPadre =
        (int)$p->idarticulo;

    $sub_articulos =
        $articulos_map[$idPadre] ?? [];

    if (!empty($sub_articulos)) {

        // Ordenar Topping primero y Extra después
        $orden = [
            'Topping' => 0,
            'Extra'    => 1
        ];

        usort(
            $sub_articulos,
            function ($a, $b) use ($orden) {

                $tipoA =
                    isset($a->tipo)
                    ? trim($a->tipo)
                    : '';

                $tipoB =
                    isset($b->tipo)
                    ? trim($b->tipo)
                    : '';

                $posA =
                    $orden[$tipoA] ?? 99;

                $posB =
                    $orden[$tipoB] ?? 99;

                if ($posA != $posB) {

                    return $posA <=> $posB;
                }

                return strcasecmp(
                    (string)$a->articulo,
                    (string)$b->articulo
                );
            }
        );

        foreach ($sub_articulos as $hijo) {

            $tipoHijo =
                isset($hijo->tipo)
                ? trim($hijo->tipo)
                : '';

            $descripcionHijo =
                utf8_decode(
                    "  -- " .
                        trim(
                            $hijo->articulo .
                                " " .
                                $hijo->presen .
                                " " .
                                $hijo->descripcion_detalle
                        )
                );

            // =================================================
            // TOPPING
            // Solo descripción
            // =================================================
            if ($tipoHijo == 'Topping') {

                $pdf->SetX(
                    $xposision + 10
                );

                $pdf->SetFont(
                    'Arial',
                    'I',
                    9
                );

                $pdf->MultiCell(
                    66,
                    4,
                    $descripcionHijo,
                    0,
                    'L'
                );

                $pdf->SetFont(
                    'Arial',
                    '',
                    10
                );
            } else {

                // =============================================
                // EXTRA
                // Cantidad + descripción + PU + subtotal
                // =============================================

                $cantidadHijo =
                    $hijo->cantidad;

                $puHijo =
                    $hijo->q_ref;

                $subHijo =
                    $hijo->subtotal;

                $yH =
                    $pdf->GetY();

                // Cantidad
                $pdf->SetX(
                    $xposision
                );

                $pdf->Cell(
                    10,
                    4,
                    floatval($cantidadHijo),
                    0,
                    0,
                    'L'
                );

                $xDescH =
                    $pdf->GetX();

                // PU
                $pdf->SetXY(
                    $xDescH + 38,
                    $yH
                );

                $pdf->Cell(
                    14,
                    4,
                    number_format(
                        $puHijo,
                        2,
                        '.',
                        ','
                    ) . ' ',
                    0,
                    0,
                    'R'
                );

                // Subtotal
                $pdf->Cell(
                    14,
                    4,
                    "Q " .
                        number_format(
                            $subHijo,
                            2,
                            '.',
                            ','
                        ),
                    0,
                    1,
                    'R'
                );

                $yFinColsH =
                    $pdf->GetY();

                // Descripción
                $pdf->SetXY(
                    $xDescH,
                    $yH
                );

                $pdf->SetFont(
                    'Arial',
                    'I',
                    9
                );

                $pdf->MultiCell(
                    38,
                    4,
                    $descripcionHijo,
                    0,
                    'L'
                );

                $pdf->SetFont(
                    'Arial',
                    '',
                    10
                );

                $yFinDescH =
                    $pdf->GetY();

                $pdf->SetY(
                    max(
                        $yFinColsH,
                        $yFinDescH
                    )
                );

                $pdf->SetX(
                    $xposision
                );
            }
        }
    }

    // ========================================================
    // CONTAR SOLO PRODUCTOS PRINCIPALES
    // Igual que tu HTML
    // ========================================================

    $cantidad_total += $p->cantidad;

    // Pequeña separación entre productos
    $pdf->Ln(1);
}

// ============================================================
// SEPARADOR
// ============================================================
$pdf->SetX($xposision);

$pdf->Cell(
    $ancchodefial,
    3,
    "",
    "B",
    0,
    "C"
);

// ============================================================
// TOTALES
// ============================================================
$pdf->Ln(6);

$pdf->SetX($xposision);

$pdf->Cell(
    50,
    4,
    "SUBTOTAL:",
    0,
    0,
    "R"
);

$pdf->Cell(
    26,
    4,
    "Q " .
        number_format(
            $regv->totalgeneral,
            2
        ),
    0,
    1,
    "R"
);

// DESCUENTO
$pdf->SetX($xposision);

$pdf->Cell(
    50,
    4,
    "DESCUENTO:",
    0,
    0,
    "R"
);

$pdf->Cell(
    26,
    4,
    "Q " .
        number_format(
            $regv->total_ventades,
            2
        ),
    0,
    1,
    "R"
);

// TOTAL
$pdf->SetFont(
    'Arial',
    'B',
    11
);

$pdf->SetX($xposision);

$pdf->Cell(
    50,
    5,
    "TOTAL:",
    0,
    0,
    "R"
);

$pdf->Cell(
    26,
    5,
    "Q " .
        number_format(
            $regv->total_venta,
            2
        ),
    0,
    1,
    "R"
);

$pdf->SetFont(
    'Arial',
    '',
    10
);

// ============================================================
// TOTAL EN LETRAS
// ============================================================
$conletras =
    $regv->total_venta;

$conletrasresultado =
    num2letras($conletras);

$pdf->Ln(2);

$pdf->SetX($xposision);

$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode(
        ucfirst($conletrasresultado) .
            " Quetzales"
    ),
    0,
    "C"
);

// ============================================================
// FORMA DE PAGO
// ============================================================
$pdf->SetX($xposision);

$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode(
        "Forma Pago: " .
            $regv->forma_pago
    ),
    0,
    "C"
);

// ============================================================
// NÚMERO DE ARTÍCULOS
// ============================================================
$pdf->Ln(2);

$pdf->SetX($xposision);

$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode(
        "Nº de articulos: " .
            $cantidad_total
    ),
    0,
    "L"
);

// ============================================================
// SEPARADOR
// ============================================================
$pdf->SetX($xposision);

$pdf->Cell(
    $ancchodefial,
    4,
    "",
    "B",
    0,
    "C"
);

// ============================================================
// GRACIAS
// ============================================================
$pdf->Ln(7);

$pdf->SetX($xposision);

$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode(
        "¡Gracias por su compra!"
    ),
    0,
    "C"
);

// ============================================================
// SUCURSAL
// ============================================================
$pdf->SetX($xposision);

$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode(
        $regv->sucursal_nombre
    ),
    0,
    "C"
);

// ============================================================
// USUARIO
// ============================================================
$pdf->SetX($xposision);

$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode(
        "Le atendio: " .
            $regv->usuario
    ),
    0,
    "C"
);

// ============================================================
// VENDEDOR
// ============================================================
$pdf->SetX($xposision);

$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode(
        "Vendedor: " .
            $regv->nombre_vendedor
    ),
    0,
    "C"
);

// ============================================================
// VENTA INTERNA
// ============================================================
$pdf->SetX($xposision);

$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode(
        "Nº venta control interno: #" .
            $regv->idventa
    ),
    0,
    "C"
);

// ============================================================
// EFECTIVO
// ============================================================
$pdf->Ln(2);

$pdf->SetFont(
    'Arial',
    'B',
    14
);

$pdf->SetX($xposision);

$pdf->MultiCell(
    $ancchodefial,
    6,
    utf8_decode(
        "Efectivo: " .
            $regv->cefectivo
    ),
    0,
    "C"
);

// ============================================================
// CAMBIO
// ============================================================
$pdf->SetX($xposision);

$pdf->MultiCell(
    $ancchodefial,
    6,
    utf8_decode(
        "Cambio: " .
            $regv->rescambio
    ),
    0,
    "C"
);

$pdf->SetFont(
    'Arial',
    '',
    10
);

// ============================================================
// ORDEN / RESTAURANTE
// ============================================================
if (
    !empty($regv->id_add_orden) &&
    $regv->id_add_orden != null
) {

    $pdf->SetFont(
        'Arial',
        'B',
        13
    );

    // Orden
    $pdf->SetX($xposision);

    $pdf->MultiCell(
        $ancchodefial,
        5,
        utf8_decode(
            "Orden #: " .
                $regv->id_add_orden
        ),
        0,
        "C"
    );

    // Mesa
    $pdf->SetX($xposision);

    $pdf->MultiCell(
        $ancchodefial,
        5,
        utf8_decode(
            "Mesa Orden #: " .
                $regv->mesa_orden
        ),
        0,
        "C"
    );

    // Mesero
    $pdf->SetX($xposision);

    $pdf->MultiCell(
        $ancchodefial,
        5,
        utf8_decode(
            "Mesero Orden: " .
                $regv->usuario_orden
        ),
        0,
        "C"
    );

    $pdf->SetFont(
        'Arial',
        '',
        10
    );
}

// ============================================================
// SEPARADOR
// ============================================================
$pdf->Ln(3);

$pdf->SetX($xposision);

$pdf->Cell(
    $ancchodefial,
    4,
    "",
    "B",
    0,
    "C"
);

// ============================================================
// EMPRESA DESARROLLO
// ============================================================
$pdf->Ln(6);

$pdf->SetX($xposision);

$pdf->MultiCell(
    $ancchodefial,
    4,
    utf8_decode(
        $regv->empresadesarrollo
    ),
    0,
    "C"
);

// ============================================================
// ÚLTIMA LÍNEA
// ============================================================
$pdf->Ln(5);

$pdf->SetX($xposision);

$pdf->SetTextColor(
    255,
    255,
    255
);

$pdf->SetFillColor(
    0,
    0,
    0
);

$pdf->Cell(
    $ancchodefial,
    5,
    ".::ULTIMA LINEA::.",
    1,
    1,
    'C',
    1
);

$pdf->SetTextColor(
    0,
    0,
    0
);

// ============================================================
// ESPACIO FINAL PARA IMPRESORA
// ============================================================
$pdf->Ln(10);

// ============================================================
// MOSTRAR PDF
// ============================================================
$pdf->Output(
    'Envio No ' .
        $regv->num_comprobante .
        '.pdf',
    'I'
);

ob_end_flush();
