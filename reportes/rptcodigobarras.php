<?php
// Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

 
if (!isset($_SESSION["nombre"])) {
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
  if ($_SESSION['almacen'] == 1) {
    require_once('barcode.php');

    $pdf = new PDF_Code128('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 10);

    // Datos del código
    $code = $_GET["id"];
    $nombre = $_GET["nombre"];
    $pv = $_GET["pv"];

    // Limitar el ancho del nombre a un máximo de 20 caracteres (puedes ajustar el límite según lo necesites)
$nombre = (strlen($nombre) > 20) ? substr($nombre, 0, 17) . '...' : $nombre;


    // Definir las posiciones iniciales en X para las columnas
    $columns = [10, 68, 120, 170]; // Posiciones X de las columnas
    $startY = 14; // Posición Y inicial
    $stepY = 20; // Incremento en Y entre cada fila
    $barcodeWidth = 30;
    $barcodeHeight = 5;

    // Generar las filas y columnas dinámicamente
    for ($row = 0; $row < 12; $row++) { // 19 filas
        for ($col = 0; $col < count($columns); $col++) { // 4 columnas
            $currentX = $columns[$col];
            $currentY = $startY + ($row * $stepY); // Calcular Y dinámico
            
            // Generar código de barras
            $pdf->Code128($currentX, $currentY, $code, $barcodeWidth, $barcodeHeight);

            // Añadir texto del código
            $pdf->SetXY($currentX, $currentY + $barcodeHeight);
            $pdf->Cell($barcodeWidth, 4, $code, 0, 0, 'C');

            // Añadir nombre del producto
            $pdf->SetXY($currentX, $currentY + $barcodeHeight + 4);
            $pdf->Cell($barcodeWidth, 4, $nombre, 0, 0, 'C');

            // Añadir precio de venta
            $pdf->SetXY($currentX, $currentY + $barcodeHeight + 8);
            $pdf->Cell($barcodeWidth, 4, 'Q' . number_format($pv, 2), 0, 0, 'C');
        }
    }

    $pdf->Output();
  }
}
?>
