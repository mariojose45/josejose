<?php
// Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1)
    session_start();

if (!isset($_SESSION["nombre"])) {
    echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {

    if ($_SESSION['nomina_empleados'] == 1) {

        // ======================================================
        //  ARCHIVOS REQUERIDOS
        // ======================================================
        require('FormatoMuchoshojasBlanco.php');
        include 'empresa.php';
        require_once "../modelos/Nomina_pagos.php";

        $resobject = new NominaPagos();

        // CABECERA GENERAL DE NÓMINA
        $rsptav = $resobject->cabeceranominapagos($_GET["id"]);
        $regv = $rsptav->fetch_object();

        // ======================================================
        //   INICIAR PDF LEGAL HORIZONTAL (TU REPORTE GENERAL)
        // ======================================================
        $pdf = new PDF_Invoice('L', 'mm', 'Legal');
        $pdf->SetFont("Arial", "", 9);




        // =================================================================
        //   FUNCIÓN PARA GENERAR COMPROBANTE INDIVIDUAL (MEDIA HOJA)
        // =================================================================
        function comprobanteNominaMediaHoja($pdf, $reg, $regv, $posY)
        {
            $url = '../files/articulos/';
            $logo = $url . $regv->sucursal_imagen;

            // LOGO pequeño y centrado
            if (file_exists($logo)) {
                //$pdf->Image($logo, 95, $posY - 15, 25);
            }

            // === NÚMERO DE NÓMINA ARRIBA A LA DERECHA ===
            $pdf->SetXY(125, $posY - 5);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(75, 5, utf8_decode("# Recibo: " . $regv->idnomina_pagos . " - " . $reg->iddetalle_nomina_pagos), 0, 1, 'R');


            // ===== TÍTULOS =====
            $pdf->SetXY(10, $posY + 10);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 6, utf8_decode("COMPROBANTE DE PAGO"), 0, 1, 'C');


            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(0, 4, utf8_decode($regv->sucursal_nombre . " - NIT " . $regv->sucursal_nit), 0, 1, 'C');
            $pdf->Cell(0, 4, utf8_decode("Planilla del " . date("d-m-Y", strtotime($regv->fechainicio)) . " al " . date("d-m-Y", strtotime($regv->fechafin))), 0, 1, 'C');

            // línea
            $pdf->SetDrawColor(160, 160, 160);
            $pdf->Ln(1);
            $pdf->Line(10, $pdf->GetY(), 205, $pdf->GetY());
            $pdf->Ln(2);

            // ===== INFORMACIÓN DEL EMPLEADO =====
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(40, 5, utf8_decode("Empleado:"), 0, 0);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(80, 5, utf8_decode($reg->nombre_empleado), 0, 1);

            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(40, 5, utf8_decode("DPI:"), 0, 0);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(80, 5, $reg->cui, 0, 1);

            $pdf->Ln(2);

            // ===== ENCABEZADOS =====
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(90, 6, utf8_decode("Salario Mensual"), 0, 0, 'L');
            $pdf->Cell(90, 6, "Descuentos", 0, 1, 'L');

            // ===== TABLAS =====
            $pdf->SetFont('Arial', '', 8);

            $pdf->Cell(50, 5, utf8_decode("Días Trabajados"), 0, 0);
            $pdf->Cell(40, 5, number_format($reg->dias_trabajados, 2), 0, 0, 'R');
            $pdf->Cell(50, 5, utf8_decode("IGSS"), 0, 0);
            $pdf->Cell(40, 5, number_format($reg->descuento_igss, 2), 0, 1, 'R');

            // Suponiendo que $regv->fechafin viene en formato YYYY-mm-dd
            $diaFin = (int) date('d', strtotime($regv->fechafin));
            $diaInicio = (int) date('d', strtotime($regv->fechainicio));


            // ✅ SEGUNDA QUINCENA: del 16 al 30/31
            $salariobase = $reg->total_salario;
            $salarioextra = $reg->salario_extra;
            $boniley = $reg->bonificacion_ley + $reg->bonificacion_productividad;
            $txt_salariobruto = "Salario Bruto:";
            $totaldevengado = number_format($reg->total_devengado, 2);
            $totaldeducciones = $reg->total_deducciones;


            $pdf->Cell(50, 5, utf8_decode("Salario Ordinario"), 0, 0);
            $pdf->Cell(40, 5, number_format($salariobase, 2), 0, 0, 'R');
            $pdf->Cell(50, 5, utf8_decode("ISR"), 0, 0);
            $pdf->Cell(40, 5, number_format($reg->descuento_isr, 2), 0, 1, 'R');

            $pdf->Cell(50, 5, utf8_decode("Salario Extraordinario"), 0, 0);
            $pdf->Cell(40, 5, number_format($salarioextra, 2), 0, 0, 'R');
            $pdf->Cell(50, 5, utf8_decode("Seguro Médico"), 0, 0);
            $pdf->Cell(40, 5, "0.00", 0, 1, 'R');

            $pdf->Cell(50, 5, utf8_decode("Bonificación Ley"), 0, 0);
            $pdf->Cell(40, 5, number_format($boniley, 2), 0, 0, 'R');
            $pdf->Cell(50, 5, utf8_decode("Anticipo Salarial"), 0, 0);
            $pdf->Cell(40, 5, number_format($reg->abono_adelantoSalarial, 2), 0, 1, 'R');


            $pdf->SetX(100);
            $pdf->Cell(50, 5, utf8_decode("Otras Deducciones"), 0, 0);

            $pdf->Cell(40, 5, number_format($reg->abono_otrosDescuentos, 2), 0, 1, 'R');



            // ===== TOTALES =====
            $pdf->Ln(2);
            $pdf->Line(10, $pdf->GetY(), 205, $pdf->GetY());
            $pdf->Ln(2);

            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(90, 6, utf8_decode($txt_salariobruto), 0, 0, 'L');
            $pdf->Cell(90, 6, $totaldevengado, 0, 1, 'R');

            $pdf->Cell(90, 6, utf8_decode("Total de Deducciones:"), 0, 0, 'L');
            $pdf->Cell(90, 6, number_format($totaldeducciones, 2), 0, 1, 'R');

            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetTextColor(0, 120, 0);
            $pdf->Cell(90, 7, utf8_decode("Líquido a Recibir:"), 0, 0, 'L');
            $pdf->Cell(90, 7, number_format($reg->liquido_recibir, 2), 0, 1, 'R');
            $pdf->SetTextColor(0, 0, 0);

            $pdf->Ln(1);

            // Firma y texto legal
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(40, 5, "(F)", 0, 1);

            $pdf->SetLineWidth(0.4);
            $pdf->SetDrawColor(0, 0, 0);

            $pdf->Ln(1);
            $pdf->SetX(10);

            $pdf->Cell(190, 6, '', 'T', 0, 'C');

            $pdf->SetLineWidth(0.2); // devolver grosor normal            
            $pdf->Ln(1);
            $pdf->SetX(10);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(0, 4, utf8_decode(
                "Declaro que los valores aquí descritos son correctos y que el saldo líquido me ha sido entregado a entera satisfacción por " . $regv->sucursal_nombre . "."
            ), 0, 'C');
        }




        // ============================================================
        //     GENERAR COMPROBANTES INDIVIDUALES (MEDIA HOJA)
        // ============================================================
        $rsptads = $resobject->detallecabeceranominapagos($_GET["id"]);

        // PRIMERA PÁGINA VERTICAL DONDE VAN LOS RECIBOS
        $pdf->AddPage('P', 'Letter');

        $posY = 15;   // posición del primer comprobante
        $contador = 0;

        while ($reg = $rsptads->fetch_object()) {

            // Agregar nueva página cada 2 comprobantes
            if ($contador == 2) {
                $pdf->AddPage('P', 'Letter');
                $contador = 0;
                $posY = 15;
            }

            comprobanteNominaMediaHoja($pdf, $reg, $regv, $posY);

            // aumentar para siguiente comprobante
            $posY += 120;
            $contador++;
        }

        // SALIDA
        $pdf->Output('Nomina Empleado No' . $regv->idnomina_pagos . ".pdf", 'I');

    } else {
        echo 'No tiene permiso para visualizar el reporte';
    }
}
?>