<?php
$file = 'c:\\xampp\\htdocs\\josejose\\reportes\\exTicket58mm.php';
$content = file_get_contents($file);

$replaces = [
    [
        "// Ticket de 78 mm\n\$pdf = new PDF_Invoice(\n    'P',\n    'mm',\n    array(78, 2500)\n);",
        "// Ticket de 58 mm\n\$pdf = new PDF_Invoice(\n    'P',\n    'mm',\n    array(58, 2500)\n);"
    ],
    [
        "\$ancchodefial = 76;",
        "\$ancchodefial = 58;"
    ],
    [
        "\$pdf->Image(\n        \$url . \$regv->sucursal_imagen,\n        19,\n        3,\n        40,",
        "\$pdf->Image(\n        \$url . \$regv->sucursal_imagen,\n        9,\n        3,\n        40,"
    ],
    [
        "// ============================================================\n// COLUMNAS\n// 10 + 38 + 14 + 14 = 76 mm\n// ============================================================\n\$pdf->Cell(\n    10,\n    4,",
        "// ============================================================\n// COLUMNAS\n// 8 + 26 + 12 + 12 = 58 mm\n// ============================================================\n\$pdf->Cell(\n    8,\n    4,"
    ],
    [
        "\$pdf->Cell(\n    38,\n    4,\n    utf8_decode(\"DESCRIPCION\"),",
        "\$pdf->Cell(\n    26,\n    4,\n    utf8_decode(\"DESCRIPCION\"),"
    ],
    [
        "\$pdf->Cell(\n    14,\n    4,\n    \"P.U.\",",
        "\$pdf->Cell(\n    12,\n    4,\n    \"P.U.\","
    ],
    [
        "\$pdf->Cell(\n    14,\n    4,\n    \"SUB\",",
        "\$pdf->Cell(\n    12,\n    4,\n    \"SUB\","
    ],
    [
        "\$pdf->Cell(\n        10,\n        4,\n        floatval(\$p->cantidad),",
        "\$pdf->Cell(\n        8,\n        4,\n        floatval(\$p->cantidad),"
    ],
    [
        "\$pdf->SetXY(\n        \$xDescripcion + 38,\n        \$yInicio\n    );",
        "\$pdf->SetXY(\n        \$xDescripcion + 26,\n        \$yInicio\n    );"
    ],
    [
        "\$pdf->Cell(\n        14,\n        4,\n        number_format(\n            \$p->q_ref,",
        "\$pdf->Cell(\n        12,\n        4,\n        number_format(\n            \$p->q_ref,"
    ],
    [
        "\$pdf->Cell(\n        14,\n        4,\n        \"Q \" .\n            number_format(\n                \$p->subtotal,",
        "\$pdf->Cell(\n        12,\n        4,\n        \"Q \" .\n            number_format(\n                \$p->subtotal,"
    ],
    [
        "\$pdf->MultiCell(\n        38,\n        4,\n        \$descripcion,",
        "\$pdf->MultiCell(\n        26,\n        4,\n        \$descripcion,"
    ],
    [
        "\$pdf->MultiCell(\n                    66,\n                    4,\n                    \$descripcionHijo,",
        "\$pdf->MultiCell(\n                    48,\n                    4,\n                    \$descripcionHijo,"
    ],
    [
        "\$pdf->Cell(\n                    10,\n                    4,\n                    floatval(\$cantidadHijo),",
        "\$pdf->Cell(\n                    8,\n                    4,\n                    floatval(\$cantidadHijo),"
    ],
    [
        "\$pdf->SetXY(\n                    \$xDescH + 38,\n                    \$yH\n                );",
        "\$pdf->SetXY(\n                    \$xDescH + 26,\n                    \$yH\n                );"
    ],
    [
        "\$pdf->Cell(\n                    14,\n                    4,\n                    number_format(\n                        \$puHijo,",
        "\$pdf->Cell(\n                    12,\n                    4,\n                    number_format(\n                        \$puHijo,"
    ],
    [
        "\$pdf->Cell(\n                    14,\n                    4,\n                    \"Q \" .\n                        number_format(\n                            \$subHijo,",
        "\$pdf->Cell(\n                    12,\n                    4,\n                    \"Q \" .\n                        number_format(\n                            \$subHijo,"
    ],
    [
        "\$pdf->MultiCell(\n                    38,\n                    4,\n                    \$descripcionHijo,",
        "\$pdf->MultiCell(\n                    26,\n                    4,\n                    \$descripcionHijo,"
    ],
    [
        "\$pdf->Cell(\n    50,\n    4,\n    \"SUBTOTAL:\",",
        "\$pdf->Cell(\n    35,\n    4,\n    \"SUBTOTAL:\","
    ],
    [
        "\$pdf->Cell(\n    26,\n    4,\n    \"Q \" .\n        number_format(\n            \$regv->totalgeneral,",
        "\$pdf->Cell(\n    23,\n    4,\n    \"Q \" .\n        number_format(\n            \$regv->totalgeneral,"
    ],
    [
        "\$pdf->Cell(\n    50,\n    4,\n    \"DESCUENTO:\",",
        "\$pdf->Cell(\n    35,\n    4,\n    \"DESCUENTO:\","
    ],
    [
        "\$pdf->Cell(\n    26,\n    4,\n    \"Q \" .\n        number_format(\n            \$regv->total_ventades,",
        "\$pdf->Cell(\n    23,\n    4,\n    \"Q \" .\n        number_format(\n            \$regv->total_ventades,"
    ],
    [
        "\$pdf->Cell(\n    50,\n    5,\n    \"TOTAL:\",",
        "\$pdf->Cell(\n    35,\n    5,\n    \"TOTAL:\","
    ],
    [
        "\$pdf->Cell(\n    26,\n    5,\n    \"Q \" .\n        number_format(\n            \$regv->total_venta,",
        "\$pdf->Cell(\n    23,\n    5,\n    \"Q \" .\n        number_format(\n            \$regv->total_venta,"
    ]
];

// Replaces in windows \r\n and unix \n mode
foreach ($replaces as $r) {
    // Try exact
    $content = str_replace($r[0], $r[1], $content);
    // Try with \r\n
    $search = str_replace("\n", "\r\n", $r[0]);
    $replace = str_replace("\n", "\r\n", $r[1]);
    $content = str_replace($search, $replace, $content);
}

file_put_contents($file, $content);
echo "Replaced successfully 58mm";
?>
