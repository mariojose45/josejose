<?php
// Activamos el almacenamiento en el buffer
ob_start();
require('FormatoMuchoshojasBlanco.php');   

        // Establecemos los datos de la empresa
        include 'empresa.php';  


// Custom class to define the PDF structure
class PDF extends FPDF {
    // Page header
    function Header() {
        // Company Title
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 10, utf8_decode('COMERCIAL MAURICIO'), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 5, utf8_decode('Donde le damos el verdadero valor a su dinero'), 0, 1, 'C');
        $this->Cell(0, 5, utf8_decode('2a. Ave. 1-54 zona 4, Sanarate; El Progreso'), 0, 1, 'C');
        $this->Cell(0, 5, utf8_decode('TEL: 7925-2167'), 0, 1, 'C');
        $this->Ln(10);
    }
}

// Create new PDF document
$pdf = new PDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Customer and general information section
//DATOS DEL CLIENTE DEL CREDITO
    require_once "../modelos/Cotizaciones.php"; 
    $cotizaciones= new Cotizaciones();
    $rsptav = $cotizaciones->contrato_cliente($_GET["id2"]);
    //Recorremos todos los valores obtenidos
    $regv = $rsptav->fetch_object();
    $pdf->SetX(10);
    $pdf->Cell(20, 5, 'Cuenta:', 0, 0, 'L');
    $pdf->Cell(40, 5, $regv->codigo_cliente, 0, 0, 'L');
    $pdf->Cell(75, 5, '', 0, 0, 'L');
    $pdf->Cell(20, 5, 'Fecha:', 0, 0, 'L');
    $pdf->Cell(20, 5, '29/08/2025', 0, 1, 'L');

    $pdf->SetX(10);
    $pdf->Cell(20, 5, 'Cliente:', 0, 0, 'L');
    $pdf->Cell(120, 5, utf8_decode($regv->nombre_cliente), 0, 1, 'L');

    $pdf->SetX(10);
    $pdf->Cell(20, 5, 'Sector:', 0, 0, 'L');
    $pdf->Cell(40, 5, $regv->nombre_sector, 0, 0, 'L');
    $pdf->Cell(30, 5, '', 0, 0, 'L');
    $pdf->Cell(60, 5, utf8_decode(''), 0, 1, 'L');

    $pdf->SetX(10);
    $pdf->Cell(20, 5, utf8_decode('Dirección:'), 0, 0, 'L');
    $pdf->MultiCell(0, 5, utf8_decode($regv->direccion), 0, 'L');
    $pdf->Ln(5);

    $pdf->SetX(10);
    $pdf->Cell(20, 5, 'Trabajo', 0, 0, 'L');
    $pdf->Cell(100, 5, utf8_decode($regv->trabajo), 0, 0, 'L');
    $pdf->Cell(20, 5, utf8_decode('Teléfono:'), 0, 0, 'L');
    $pdf->Cell(30, 5, $regv->telefono, 0, 1, 'L');

    $pdf->SetX(10);
    $pdf->Cell(20, 5, $regv->tipo_documento, 0, 0, 'L');
    $pdf->Cell(100, 5, $regv->num_documento, 0, 0, 'L');
    $pdf->Cell(20, 5, utf8_decode('Teléfono:'), 0, 0, 'L');
    $pdf->Cell(30, 5, '', 0, 1, 'L');
    $pdf->Ln(5);
//DATOS DEL CLIENTE DEL CREDITO

//DATOS DEL FIADOR
    $pdf->SetX(10);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->Cell(190, 30, '', 1, 1, 'L');
    $pdf->SetY($pdf->GetY() - 30);
    $pdf->SetX(15);
    $pdf->Cell(20, 5, 'Fiador: ' .$regv->nombre_fiador, 0, 1, 'L');
    $pdf->SetX(15);
    $pdf->Cell(20, 5, utf8_decode('Dirección: '.$regv->direccion_fiador), 0, 1, 'L');
    $pdf->SetX(15);
    $pdf->Cell(20, 5, 'Trabajo: '.$regv->trabajo_fiador, 0, 0, 'L');
    $pdf->Cell(100, 5, '', 0, 0, 'L');
    $pdf->Cell(20, 5, utf8_decode('Teléfono: '.$regv->telefono_fiador), 0, 1, 'L');
    $pdf->SetX(15);
    $pdf->Cell(20, 5, $regv->tipo_documento_fiador. ': '.$regv->num_documento_fiador, 0, 0, 'L');
    $pdf->Cell(100, 5, '', 0, 0, 'L');
    $pdf->Cell(20, 5, utf8_decode('Teléfono:'), 0, 1, 'L');
    $pdf->Ln(5);
//DATOS DEL FIADOR

// Merchandise section
$pdf->SetX(10);
$pdf->Cell(20, 5, 'Mercaderia:', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->MultiCell(0, 5, utf8_decode('2 CAMAS MATRIMONIAL, FLORIDA OPTIMA DOBLE EURO TOP 6  2320-'), 0, 'L');
$pdf->Ln(5);

// Price summary section with borders
$pdf->SetFont('Arial', '', 10);
$pdf->SetX(10);
$pdf->Cell(80, 5, '10 MESES PRECIO DE CONTADO', 0, 0, 'L');
$pdf->Cell(20, 5, '', 0, 0, 'L');
$pdf->Cell(80, 5, 'PRECIO CREDITO', 0, 1, 'L');
$pdf->SetDrawColor(0, 0, 0);
$pdf->Rect(10, $pdf->GetY(), 90, 25);
$pdf->Rect(110, $pdf->GetY(), 90, 25);

$y_start = $pdf->GetY();
$pdf->SetY($y_start);
$pdf->SetX(15);
$pdf->Cell(30, 5, 'Valor Contado:', 0, 0, 'L');
$pdf->Cell(30, 5, 'Q3,860.00', 0, 0, 'R');
$pdf->SetX(115);
$pdf->Cell(30, 5, 'Valor:', 0, 0, 'L');
$pdf->Cell(30, 5, 'Q4,640.00', 0, 1, 'R');

$pdf->SetY($y_start + 5);
$pdf->SetX(15);
$pdf->Cell(30, 5, 'Enganche:', 0, 0, 'L');
$pdf->Cell(30, 5, 'Q400.00', 0, 0, 'R');
$pdf->SetX(115);
$pdf->Cell(30, 5, 'Enganche:', 0, 0, 'L');
$pdf->Cell(30, 5, 'Q400.00', 0, 1, 'R');

$pdf->SetY($y_start + 10);
$pdf->SetX(15);
$pdf->Cell(30, 5, 'Saldo Contado:', 0, 0, 'L');
$pdf->Cell(30, 5, 'Q3,460.00', 0, 0, 'R');
$pdf->SetX(115);
$pdf->Cell(30, 5, 'Saldo:', 0, 0, 'L');
$pdf->Cell(30, 5, 'Q4,240.00', 0, 1, 'R');
$pdf->Ln(10);

// "PAGARE SIN PROTESTO" section
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('PAGARE SIN PROTESTO'), 0, 1, 'C');
$pdf->Ln(2);
$pdf->SetFont('Arial', '', 9);
$pagare_text = utf8_decode("Por este documento prometo pagar a la orden de Comercial Mauricio en sus oficinas situadas en 2a. Av. 1-54 Zona 4, Sanarate El Progreso, La cantidad de: Q4240\n\nDicha cantidad se amortiza sucesivamente de la siguiente manera: 17 cuotas de Q260 C/U el día estipulado para pago es el 29 de cada mes\n\nLa falta de pago de una sola de las amortizaciones dará por vencido el plazo de las demás y el poseedor del presente Titulo de Crédito, podrá exigir íntegramente el saldo de la obligación cambiaria. En este caso renuncio al fuero de mi domicilio y me someto expresamente a los tribunales que el acreedor considere pertinente.");
$pdf->MultiCell(0, 5, $pagare_text, 0, 'J');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(0, 5, 'Intereses moratorios sobre saldo 15%', 0, 1, 'L');
$pdf->Ln(15);

// Signature section
$pdf->SetFont('Arial', '', 10);
$pdf->SetX(20);
$pdf->Cell(70, 5, '__________________________', 0, 0, 'C');
$pdf->Cell(50, 5, '', 0, 0, 'L');
$pdf->Cell(70, 5, '__________________________', 0, 1, 'C');
$pdf->SetX(20);
$pdf->Cell(70, 5, utf8_decode('Firma del Cliente'), 0, 0, 'C');
$pdf->Cell(50, 5, '', 0, 0, 'L');
$pdf->Cell(70, 5, utf8_decode('Firma de Comercial Mauricio'), 0, 1, 'C');
$pdf->SetX(20);
$pdf->Cell(70, 5, 'DPI', 0, 0, 'C');
$pdf->Cell(50, 5, '', 0, 0, 'L');
$pdf->Cell(70, 5, 'DPI', 0, 1, 'C');

// Output the PDF to the browser
$pdf->Output('Reporte.pdf', 'I');

ob_end_flush();
?>