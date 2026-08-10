<?php
require_once('../fpdf181/fpdf.php');

class FacturaEnvio extends FPDF
{
    var $colonnes;
    var $format;
    var $angle = 0;

    // Encabezado principal de la empresa
    function addSociete($nom, $adresse)
    {
        $this->SetXY(12, 12);
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(15, 23, 42); // Azul oscuro / Slate
        $this->Cell(0, 6, mb_convert_encoding($nom, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        
        $this->SetFont('Arial', '', 8.5);
        $this->SetTextColor(100, 116, 139); // Gris slate
        $this->SetXY(12, 19);
        $this->MultiCell(100, 4, mb_convert_encoding($adresse, 'ISO-8859-1', 'UTF-8'));
    }

    // Badge del comprobante (Factura / Envío / Ticket)
    function fact_dev($libelle, $num)
    {
        $r1 = 132;
        $y1 = 12;
        $w  = 66;
        $h  = 22;

        // Fondo del badge con bordes redondeados simétricos
        $this->SetFillColor(30, 41, 59); // Dark Slate
        $this->Rect($r1, $y1, $w, $h, 'F');

        // Tipo de Comprobante
        $this->SetXY($r1, $y1 + 4);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(203, 213, 225); // Gris claro
        $this->Cell($w, 4, strtoupper(mb_convert_encoding($libelle, 'ISO-8859-1', 'UTF-8')), 0, 1, 'C');

        // Número de Comprobante
        $this->SetXY($r1, $y1 + 10);
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(255, 255, 255); // Blanco
        $this->Cell($w, 6, mb_convert_encoding($num, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
    }

    // Cuadro de datos del cliente
    function addClientAdresse($cliente, $nit, $direccion, $telefono, $fecha, $lote)
    {
        $r1 = 12;
        $y1 = 38;
        $w  = 186;
        $h  = 24;

        // Fondo gris suave para la sección del cliente
        $this->SetFillColor(248, 250, 252);
        $this->SetDrawColor(226, 232, 240);
        $this->SetLineWidth(0.3);
        $this->Rect($r1, $y1, $w, $h, 'DF');

        // Línea 1: Cliente y Fecha
        $this->SetXY($r1 + 4, $y1 + 3);
        $this->SetFont('Arial', 'B', 8.5);
        $this->SetTextColor(71, 85, 105);
        $this->Cell(18, 4, "CLIENTE:", 0, 0);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(15, 23, 42);
        $this->Cell(100, 4, mb_convert_encoding($cliente, 'ISO-8859-1', 'UTF-8'), 0, 0);

        $this->SetFont('Arial', 'B', 8.5);
        $this->SetTextColor(71, 85, 105);
        $this->Cell(18, 4, "FECHA:", 0, 0);
        $this->SetFont('Arial', '', 8.5);
        $this->SetTextColor(15, 23, 42);
        $this->Cell(40, 4, $fecha, 0, 1);

        // Línea 2: NIT/Doc y Lote Ref
        $this->SetXY($r1 + 4, $y1 + 9);
        $this->SetFont('Arial', 'B', 8.5);
        $this->SetTextColor(71, 85, 105);
        $this->Cell(18, 4, "NIT/DOC:", 0, 0);
        $this->SetFont('Arial', '', 8.5);
        $this->SetTextColor(15, 23, 42);
        $this->Cell(100, 4, mb_convert_encoding($nit, 'ISO-8859-1', 'UTF-8'), 0, 0);

        $this->SetFont('Arial', 'B', 8.5);
        $this->SetTextColor(71, 85, 105);
        $this->Cell(18, 4, "LOTE REF:", 0, 0);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(30, 58, 138); // Azul institucional
        $this->Cell(40, 4, $lote, 0, 1);

        // Línea 3: Dirección y Teléfono
        $this->SetXY($r1 + 4, $y1 + 15);
        $this->SetFont('Arial', 'B', 8.5);
        $this->SetTextColor(71, 85, 105);
        $this->Cell(18, 4, mb_convert_encoding("DIRECCIÓN:", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $this->SetFont('Arial', '', 8.5);
        $this->SetTextColor(15, 23, 42);
        $this->Cell(100, 4, mb_convert_encoding($direccion, 'ISO-8859-1', 'UTF-8'), 0, 0);

        $this->SetFont('Arial', 'B', 8.5);
        $this->SetTextColor(71, 85, 105);
        $this->Cell(18, 4, mb_convert_encoding("TELÉFONO:", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $this->SetFont('Arial', '', 8.5);
        $this->SetTextColor(15, 23, 42);
        $this->Cell(40, 4, $telefono, 0, 1);
    }

    // Cabecera de la tabla de productos
    function addCols($tab)
    {
        $r1 = 12;
        $y1 = 66;
        $this->colonnes = $tab;

        // Fondo del encabezado de tabla
        $this->SetFillColor(30, 41, 59);
        $this->Rect($r1, $y1, 186, 7, 'F');

        $this->SetXY($r1, $y1 + 1.5);
        $this->SetFont('Arial', 'B', 8.5);
        $this->SetTextColor(255, 255, 255);

        $colX = $r1;
        foreach ($tab as $lib => $pos) {
            $align = 'C';
            if ($lib == "Descripción") $align = 'L';
            if ($lib == "P. Unitario" || $lib == "Subtotal") $align = 'R';

            $this->SetX($colX + ($align == 'L' ? 3 : 0));
            $this->Cell($pos - ($align == 'L' ? 3 : 0), 4, mb_convert_encoding(strtoupper($lib), 'ISO-8859-1', 'UTF-8'), 0, 0, $align);
            $colX += $pos;
        }

        // Borde exterior contenedor del detalle
        $this->SetDrawColor(226, 232, 240);
        $this->SetLineWidth(0.3);
        $this->Rect($r1, $y1, 186, 162);
    }

    // Fila individual de la tabla (Soporta Zebra Striping y texto limpio)
    function addLine($ligne, $tab, $indexFila = 0)
    {
        $r1 = 12;

        // Color intercalado suave (Zebra Striping)
        if ($indexFila % 2 == 0) {
            $this->SetFillColor(248, 250, 252);
            $this->Rect($r1, $ligne - 1, 186, 6, 'F');
        }

        $this->SetFont('Arial', '', 8.5);
        $this->SetTextColor(30, 41, 59);

        $colX = $r1;
        foreach ($tab as $key => $val) {
            $align = 'L';
            if ($key == 'cant') $align = 'C';
            if ($key == 'pu' || $key == 'subtotal') $align = 'R';

            $ancho = $this->colonnes[$val['col']];

            $this->SetXY($colX + ($align == 'L' ? 3 : 0), $ligne);
            $this->Cell($ancho - ($align == 'L' ? 3 : ($align == 'R' ? 3 : 0)), 4, mb_convert_encoding($val['text'], 'ISO-8859-1', 'UTF-8'), 0, 0, $align);
            $colX += $ancho;
        }
    }

    // Dibujar líneas divisoras verticales suaves
    function lineVert()
    {
        $r1 = 12;
        $y1 = 66;
        $y2 = 228;
        $this->SetDrawColor(226, 232, 240);
        $this->SetLineWidth(0.3);

        $colX = $r1;
        if (is_array($this->colonnes)) {
            foreach ($this->colonnes as $lib => $pos) {
                $colX += $pos;
                if ($colX < 198) {
                    $this->Line($colX, $y1 + 7, $colX, $y2);
                }
            }
        }
    }

    // Cuadro de Total destacado
    function addCadreTotales($total)
    {
        $r1 = 128;
        $y1 = 232;
        $w  = 70;
        $h  = 12;

        // Fondo oscuro del total
        $this->SetFillColor(15, 23, 42);
        $this->Rect($r1, $y1, $w, $h, 'F');

        $this->SetXY($r1 + 4, $y1 + 3.5);
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(226, 232, 240);
        $this->Cell(30, 5, "TOTAL VENTA:", 0, 0, 'L');

        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(32, 5, "Q. " . number_format($total, 2), 0, 1, 'R');
    }
}
?>