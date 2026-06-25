<?php
// Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) 
{
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
}
else
{
  if ($_SESSION['almacen'] == 1)
  {
    require_once "../modelos/Articulo.php"; 
    $arti = new Articulo();
    require('barcode.php');

    $pdf = new PDF_Code128('L', 'mm', array(58, 48));
    $pdf->SetFont('Arial', '', 8);

    //--COLUMNA 1--/
    $codigo = $_GET["id"];
    $numerocodigos = (int)$_GET["id2"]; 
    $precio_venta = $_GET["id3"];
    $articulo = $_GET["id4"];

    $rsptad = $arti->rptgenerarcodigobarras($_GET["id5"]);
    while ($regdv = $rsptad->fetch_object()) {
      for ($cantidad = 0; $cantidad < $numerocodigos; $cantidad++) {
        if ($cantidad > 0) {
            $pdf->AddPage(); 
        } else {
            $pdf->AddPage();
        }
        $pdf->SetXY(1, 1);
        
        // Recortar el nombre a un máximo de 15 caracteres (ajustar según sea necesario)
       // $nombreCorto = mb_strimwidth($regdv->nombre, 0, 80, '...');
        $nombreCorto = (strlen($regdv->nombre) > 20) ? substr($regdv->nombre, 0, 17) . '...' : $regdv->nombre;
        
        // Controlar el ancho del nombre (ajustar a un valor máximo)
        $nombreAnchoMaximo = 50; // El ancho máximo de la celda donde se imprimirá el nombre

        // Nombre (usando MultiCell para ajustar el texto al ancho)
        $pdf->SetXY(1, $pdf->GetY() + 2);  // Ajustar la posición vertical según lo necesites
        $pdf->MultiCell($nombreAnchoMaximo, 4, $nombreCorto, 0, 'C');  // El nombre se ajusta al ancho especificado

        // Precio
        $pdf->Ln(6);
        $pdf->Cell(35, -10, "Q" . $regdv->precio_venta, 0, 1, 'C');
        
        // Código
        $pdf->Ln(10);
        $pdf->Code128(5, $pdf->GetY(), $regdv->codigo, 47, 10);
    
        // Ajustar posición manualmente después del código de barras
        $pdf->SetXY(1, $pdf->GetY() + 12); // Ajusta la posición de los elementos restantes
      }
    
    }

    $pdf->Output();    
  }
}
?>
