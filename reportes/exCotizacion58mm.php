<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ controla inicio y expiración de sesión

if (!isset($_SESSION["nombre"])) {
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
  if ($_SESSION['ventas'] == 1) {

    require('FormatoCartaConFacturaTickekt.php');
    include 'empresa.php';

    require_once "../modelos/Cotizaciones.php";
    $cotizaciones = new Cotizaciones();

    $rsptav = $cotizaciones->cotizacioncabecera($_GET["id"]);
    $regv = $rsptav->fetch_object();

    // Configuración del PDF
    $pdf = new PDF_Invoice('P', 'mm', array(58, 2500));
    $pdf->AddPage();
    $pdf->fact_dev(utf8_decode("COTIZACIÓN"), "");
    $pdf->temporaire("");

    $url = '../files/articulos/';
    $xposision = 1;
    $ancho = 58;

    // Logo e información
    $pdf->SetXY($xposision, 1);
    $pdf->Image($url . $regv->sucursal_imagen, 10, 3, 40, 25);
    $pdf->SetFont('Arial', '', 11);
    $pdf->SetXY($xposision, 42);
    $pdf->Multicell($ancho, 4, utf8_decode($regv->nombre_comercial), 0, "C");
    $pdf->Ln(3);
    $pdf->SetX($xposision);
    $pdf->Multicell($ancho, 4, "Direc: " . $regv->direccion_fiscal, 0, "C");
    $pdf->Ln(3);
    $pdf->SetX($xposision);
    $pdf->Multicell($ancho, 4, "Tels: " . $regv->sucursal_telefono, 0, "C");
    $pdf->Ln(3);
    $pdf->SetX($xposision);
    $pdf->Multicell($ancho, 4, "Email: " . $regv->sucursal_email, 0, "C");
    $pdf->Ln(3);
    $pdf->SetX($xposision);
    $pdf->Multicell($ancho, 4, utf8_decode("Fecha Emisión: " . $regv->fecha), 0, "C");
    $pdf->Ln(2);
    $pdf->SetX($xposision);
    $pdf->Cell($ancho, 5, "", "B", 0, "C");
    $pdf->Ln(6);

    // Cliente
    $pdf->SetX($xposision);
    $pdf->Multicell($ancho, 4, utf8_decode("Cliente: " . $regv->cliente), 0, "L");
    $pdf->Ln(5);
    $pdf->SetX($xposision);
    $pdf->Multicell($ancho, 4, utf8_decode("Dirección: " . $regv->direccion), 0, "L");
    $pdf->Ln(5);
    $pdf->SetX($xposision);
    $pdf->Cell($ancho, 4, utf8_decode($regv->tipo_documento . ": " . $regv->num_documento), 0, "L");
    $pdf->Ln(5);
    $pdf->SetX($xposision);
    $pdf->Multicell($ancho, 4, utf8_decode("Tels: " . $regv->telefono), 0, "L");
    $pdf->Ln(5);
    $pdf->SetX($xposision);
    $pdf->Cell($ancho, 5, "", "B", 0, "C");
    $pdf->Ln(5);

    // Encabezado tabla
    $pdf->SetX($xposision);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFillColor(0, 0, 0);
    $pdf->Cell($ancho, 3.5, ".:: DETALLE DE PRODUCTOS ::.", 1, 0, 'C', 1);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Ln(8);
    $pdf->SetX($xposision);
    $pdf->cell(19, 10, "CANT");
    $pdf->cell(19, 10, "P.U.");
    $pdf->cell(19, 10, "SUB");
    $pdf->Ln(8);
    $pdf->SetX($xposision);

    // =============================
    // AGRUPACIÓN DE PRODUCTOS / EXTRAS / TOPPINGS
    // =============================
    $rsptad = $cotizaciones->cotizaciondetalle($_GET["id"]);
    $items = [];
    while ($row = $rsptad->fetch_object()) {
      $items[] = $row;
    }

    $parents = [];
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

    // Mostrar productos agrupados
    foreach ($parents as $regd) {
      $descripcion = utf8_decode("{$regd->articulo} {$regd->presen} {$regd->descripcion_detalle}");
      $pdf->MultiCell($ancho, 4, $descripcion, 0, 'L');
      $pdf->SetX(1);
      $pdf->Cell(15, 4, number_format($regd->cantidad, 2, '.', ','), 0, 0, 'L');
      $pdf->Cell(21, 4, number_format($regd->q_ref, 2, '.', ','), 0, 0, 'C');
      $pdf->Cell(21, 4, number_format($regd->subtotal, 2, '.', ','), 0, 0, 'R');
      $pdf->Ln(5);
      $pdf->SetX(1);

      // Subitems (Toppings primero, luego Extras)
      $idPadre = (int)$regd->idarticulo;
      if (!empty($children[$idPadre])) {
        // Ordenar: Topping primero, luego Extra
        $hijosOrdenados = $children[$idPadre];
        usort($hijosOrdenados, function ($a, $b) {
          if ($a->tipo === $b->tipo) return 0;
          return ($a->tipo === 'Topping') ? -1 : 1;
        });

        foreach ($hijosOrdenados as $sub) {
          $descSub = "  --> " . utf8_decode("{$sub->articulo} {$sub->presen} {$sub->descripcion_detalle}");
          if ($sub->tipo === 'Topping') {
            // Solo descripción
            $pdf->SetFont('Arial', 'I', 8);
            $pdf->MultiCell($ancho - 4, 4, $descSub, 0, 'L');
            $pdf->SetFont('Arial', '', 10);
          } else {
            // Mostrar con cantidad, precio y subtotal
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell($ancho - 4, 4, $descSub, 0, 'L');
            $pdf->SetX(4);
            $pdf->Cell(15, 4, number_format($sub->cantidad, 2, '.', ','), 0, 0, 'L');
            $pdf->Cell(21, 4, number_format($sub->precio_venta, 2, '.', ','), 0, 0, 'C');
            $pdf->Cell(21, 4, number_format($sub->subtotal, 2, '.', ','), 0, 0, 'R');
            $pdf->Ln(5);
          }
          $pdf->SetX(1);
        }
      }
    }

    // =============================

    // Totales
    $pdf->Ln(3);
    $pdf->SetX($xposision);
    $pdf->Cell($ancho, 5, "", "B", 0, "C");
    require_once "num2letras.php";
    $conletras = $regv->total_venta;
    $conletrasresultado = num2letras($conletras);
    $pdf->Ln(5);
    $pdf->SetX($xposision);
    $pdf->Cell($ancho, 4, utf8_decode("SUBTOTAL: " . $regv->total_general), 0, 0, "R");
    $pdf->Ln(5);
    $pdf->SetX($xposision);
    $pdf->Cell($ancho, 4, utf8_decode("DESCUENTO: " . $regv->total_ventades), 0, 0, "R");
    $pdf->Ln(5);
    $pdf->SetX($xposision);
    $pdf->Cell($ancho, 4, utf8_decode("TOTAL: " . $regv->total_venta), 0, 0, "R");
    $pdf->Ln(6);
    $pdf->SetX($xposision);
    $pdf->Multicell($ancho, 4, utf8_decode("Total en Letras: " . $conletrasresultado . " Quetzales"), 0, "C");

    // Pie
    $pdf->Ln(5);
    $pdf->SetX($xposision);
    $pdf->Cell($ancho, 5, "", "B", 0, "C");
    $pdf->Ln(5);
    $pdf->SetX($xposision);
    $pdf->Cell($ancho, 4, utf8_decode("Le atendió: " . $regv->usuario), 0, 0, "L");
    $pdf->Ln(5);
    $pdf->SetX($xposision);
    $pdf->Cell($ancho, 4, utf8_decode("Vendedor: " . $regv->nombre_vendedor), 0, 0, "L");
    $pdf->Ln(5);
    $pdf->SetX($xposision);
    $pdf->Cell($ancho, 4, utf8_decode("# Cotización: " . $regv->num_comprobante), 0, 0, "L");
    $pdf->Ln(5);
    $pdf->SetX($xposision);
    $pdf->Cell($ancho, 4, utf8_decode("# Interno: " . $regv->idcotizacion), 0, 0, "L");

    $pdf->Ln(5);
    $pdf->SetX($xposision);
    $pdf->Cell($ancho, 5, "", "B", 0, "C");
    $pdf->Ln(5);
    $pdf->SetX($xposision);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFillColor(0, 0, 0);
    $pdf->Cell($ancho, 5, ".:: ULTIMA LINEA ::.", 1, 0, 'C', 1);
    $pdf->SetTextColor(0, 0, 0);

    $pdf->Output('Cotizacion No ' . $regv->num_comprobante . ".pdf", 'I');
  } else {
    echo 'No tiene permiso para visualizar el reporte';
  }
}
ob_end_flush();
?>
