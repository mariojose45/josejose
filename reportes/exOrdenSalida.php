<?php
require('FormatoMuchoshojasBlanco.php');   

        // Establecemos los datos de la empresa
        include 'empresa.php';  

// Crear una clase que herede de FPDF para personalizar la cabecera y el pie de página
class PDF extends FPDF
{
    // Método para la cabecera
    function Header()
    {
        // COMERCIAL MAURICIO
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'COMERCIAL MAURICIO', 0, 1, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, 'Tel. 79252167', 0, 1, 'L');

        // ORDEN DE ENTREGA
        $this->SetFont('Arial', 'B', 15);
        $this->SetXY(10, 10); // Posiciona el texto en una coordenada específica
        $this->Cell(0, 20, 'ORDEN DE ENTREGA', 0, 1, 'C');

        // Fecha y número
        $this->SetFont('Arial', '', 10);
        $this->SetXY(140, 10);
        $this->Cell(0, 5, 'viernes, 29 de agosto de 2025', 0, 1, 'R');
        $this->SetXY(140, 15);
        $this->Cell(0, 5, '69254', 0, 1, 'R');
    }

    // Método para el pie de página
    function Footer()
    {
        // Pie de página, en este caso está vacío en el ejemplo.
    }
}

// Crear un nuevo objeto PDF con la clase personalizada
$pdf = new PDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Información del cliente
    require_once "../modelos/Cotizaciones.php"; 
    $cotizaciones= new Cotizaciones();
    $rsptav = $cotizaciones->contrato_cliente($_GET["id2"]);
    //Recorremos todos los valores obtenidos
    $regv = $rsptav->fetch_object();

    $pdf->SetXY(10, 30);
    $pdf->Cell(20, 5, 'CLIENTE', 0, 0, 'L');
    $pdf->Cell(20, 5, $regv->codigo_cliente, 0, 0, 'L');
    $pdf->Cell(0, 5, utf8_decode($regv->nombre_cliente), 0, 1, 'L');
    $pdf->SetX(10);
    $pdf->Cell(0, 5, utf8_decode($regv->direccion), 0, 1, 'L');
    $pdf->SetXY(140, 30);
    $pdf->Cell(0, 5, 'TEL: ' .$regv->telefono, 0, 1, 'R');
// Información del cliente

// Líneas de la tabla de productos
$pdf->SetY(60);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(30, 10, 'PRODUCTO', 0, 0);
$pdf->Cell(75, 10, 'NOMBRE', 0, 0);
$pdf->Cell(20, 10, 'modelo', 0, 0);
$pdf->Cell(20, 10, 'marca', 0, 0);
$pdf->Cell(30, 10, 'SALIDA UBICACI', 0, 0);
$pdf->Cell(20, 10, 'SERIE', 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());

// Datos del producto
$rsptad = $cotizaciones->ventadetalle2($_GET["id"]);

// El bucle while recorre cada fila de resultados obtenida de la base de datos
while($regdv = $rsptad->fetch_object()){

    // Asume que los datos están en las siguientes propiedades
    $producto_codigo = $regdv->codigo; 
    $nombre_articulo = $regdv->articulo;
    $modelo_producto = "N/A";
    $marca_producto  = "N/A";
    $ubicacion_salida = "N/A";
    $serie_producto  = "N/A";

    $pdf->Cell(30, 10, $producto_codigo, 0, 0);
    $pdf->Cell(75, 10, utf8_decode($nombre_articulo), 0, 0);
    $pdf->Cell(20, 10, utf8_decode($modelo_producto), 0, 0);
    $pdf->Cell(20, 10, utf8_decode($marca_producto), 0, 0);
    $pdf->Cell(30, 10, $ubicacion_salida, 0, 0);
    $pdf->Cell(20, 10, $serie_producto, 0, 1); // El 1 al final hace un salto de línea
    $pdf->Ln(2); // Salto de línea adicional para separar cada fila
}

// Texto de verificación y firmas
$pdf->SetY(120);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(60, 10, 'BODEGA', 'B', 0, 'C');
$pdf->Cell(20, 10, '', 0, 0);
$pdf->Cell(60, 10, 'ENTREGA', 'B', 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(140, 5, utf8_decode('DOY FE QUE LOS ARTICULOS ADQUIRIDOS FUERON ENTREGADOS EN OPTIMAS CONDICIONES Y FUNCIONALES LUEGO DE UNA REVISION MINUCIOSA'), 0, 'L');

// Líneas de firma
$pdf->SetY(160);
$pdf->Cell(60, 5, '', 'B', 0, 'L'); // Fecha
$pdf->Cell(20, 5, '', 0, 0);
$pdf->Cell(60, 5, '', 'B', 1, 'L'); // Hora

// Texto final
$pdf->SetY(165);
$pdf->Cell(20, 5, 'Fecha:', 0, 0, 'L');
$pdf->Cell(60, 5, 'Hora:', 0, 0, 'L');
$pdf->Cell(30, 5, 'Operado por:', 0, 0, 'L');
$pdf->Cell(20, 5, '14 ANA LUCIA', 0, 1, 'L');
$pdf->SetY(160);
$pdf->SetX(145);
$pdf->Cell(50, 5, 'Firma Cliente', 'B', 1, 'C');

// Salida del PDF al navegador
$pdf->Output('I');
?>