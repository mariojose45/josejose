<?php
$file = 'c:\\xampp\\htdocs\\josejose\\reportes\\exTicket58mm.php';
$content = file_get_contents($file);

$replaces = [
    [
        "// Ticket de 78 mm\r\n\$pdf = new PDF_Invoice(\r\n    'P',\r\n    'mm',\r\n    array(78, 2500)\r\n);",
        "// Ticket de 58 mm\n\$pdf = new PDF_Invoice(\n    'P',\n    'mm',\n    array(58, 2500)\n);"
    ],
    [
        "\$ancchodefial = 76;",
        "\$ancchodefial = 58;"
    ],
    [
        "// ============================================================\r\n// COLUMNAS\r\n// 10 + 38 + 14 + 14 = 76 mm\r\n// ============================================================\r\n\$pdf->Cell(\r\n    10,\r\n    4,",
        "// ============================================================\n// COLUMNAS\n// 8 + 26 + 12 + 12 = 58 mm\n// ============================================================\n\$pdf->Cell(\n    8,\n    4,"
    ],
    [
        "\$pdf->Cell(\r\n    38,\r\n    4,",
        "\$pdf->Cell(\n    26,\n    4,"
    ],
    [
        "\$pdf->Cell(\r\n    14,\r\n    4,\r\n    \"P.U.\",",
        "\$pdf->Cell(\n    12,\n    4,\n    \"P.U.\","
    ],
    [
        "\$pdf->Cell(\r\n    14,\r\n    4,\r\n    \"SUB\",",
        "\$pdf->Cell(\n    12,\n    4,\n    \"SUB\","
    ],
    [
        "\$pdf->Cell(\r\n        10,\r\n        4,\r\n        \$p->cantidad,",
        "\$pdf->Cell(\n        8,\n        4,\n        \$p->cantidad,"
    ],
    [
        "\$pdf->SetXY(\r\n        \$xDescripcion + 38,\r\n        \$yInicio\r\n    );",
        "\$pdf->SetXY(\n        \$xDescripcion + 26,\n        \$yInicio\n    );"
    ],
    [
        "\$pdf->Cell(\r\n        14,\r\n        4,\r\n        number_format(\r\n            \$p->q_ref,",
        "\$pdf->Cell(\n        12,\n        4,\n        number_format(\n            \$p->q_ref,"
    ],
    [
        "\$pdf->Cell(\r\n        14,\r\n        4,\r\n        \"Q \" .\r\n            number_format(\r\n                \$p->subtotal,",
        "\$pdf->Cell(\n        12,\n        4,\n        \"Q \" .\n            number_format(\n                \$p->subtotal,"
    ],
    [
        "\$pdf->MultiCell(\r\n        38,\r\n        4,\r\n        \$descripcion,",
        "\$pdf->MultiCell(\n        26,\n        4,\n        \$descripcion,"
    ],
    [
        "\$pdf->MultiCell(\r\n                    66,\r\n                    4,\r\n                    \$descripcionHijo,",
        "\$pdf->MultiCell(\n                    48,\n                    4,\n                    \$descripcionHijo,"
    ],
    [
        "\$pdf->Cell(\r\n                    10,\r\n                    4,\r\n                    \$cantidadHijo,",
        "\$pdf->Cell(\n                    8,\n                    4,\n                    \$cantidadHijo,"
    ],
    [
        "\$pdf->SetXY(\r\n                    \$xDescH + 38,\r\n                    \$yH\r\n                );",
        "\$pdf->SetXY(\n                    \$xDescH + 26,\n                    \$yH\n                );"
    ],
    [
        "\$pdf->Cell(\r\n                    14,\r\n                    4,\r\n                    number_format(\r\n                        \$puHijo,",
        "\$pdf->Cell(\n                    12,\n                    4,\n                    number_format(\n                        \$puHijo,"
    ],
    [
        "\$pdf->Cell(\r\n                    14,\r\n                    4,\r\n                    \"Q \" .\r\n                        number_format(\r\n                            \$subHijo,",
        "\$pdf->Cell(\n                    12,\n                    4,\n                    \"Q \" .\n                        number_format(\n                            \$subHijo,"
    ],
    [
        "\$pdf->MultiCell(\r\n                    38,\r\n                    4,\r\n                    \$descripcionHijo,",
        "\$pdf->MultiCell(\n                    26,\n                    4,\n                    \$descripcionHijo,"
    ],
    [
        "\$pdf->Cell(\r\n    50,\r\n    4,\r\n    \"SUBTOTAL:\",",
        "\$pdf->Cell(\n    35,\n    4,\n    \"SUBTOTAL:\","
    ],
    [
        "\$pdf->Cell(\r\n    26,\r\n    4,\r\n    \"Q \" .\r\n        number_format(\r\n            \$regv->totalgeneral,",
        "\$pdf->Cell(\n    23,\n    4,\n    \"Q \" .\n        number_format(\n            \$regv->totalgeneral,"
    ],
    [
        "\$pdf->Cell(\r\n    50,\r\n    4,\r\n    \"DESCUENTO:\",",
        "\$pdf->Cell(\n    35,\n    4,\n    \"DESCUENTO:\","
    ],
    [
        "\$pdf->Cell(\r\n    26,\r\n    4,\r\n    \"Q \" .\r\n        number_format(\r\n            \$regv->total_ventades,",
        "\$pdf->Cell(\n    23,\n    4,\n    \"Q \" .\n        number_format(\n            \$regv->total_ventades,"
    ],
    [
        "\$pdf->Cell(\r\n    50,\r\n    5,\r\n    \"TOTAL:\",",
        "\$pdf->Cell(\n    35,\n    5,\n    \"TOTAL:\","
    ],
    [
        "\$pdf->Cell(\r\n    26,\r\n    5,\r\n    \"Q \" .\r\n        number_format(\r\n            \$regv->total_venta,",
        "\$pdf->Cell(\n    23,\n    5,\n    \"Q \" .\n        number_format(\n            \$regv->total_venta,"
    ]
];

foreach ($replaces as $r) {
    $content = str_replace($r[0], $r[1], $content);
}

// Ensure the line endings are correct if str_replace didn't catch them
$content = preg_replace("/\r\n/", "\n", $content);

file_put_contents($file, $content);
echo "Replaced successfully";
?>
