<?php
// Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1)
    session_start();

if (!isset($_SESSION["nombre"])) {
    echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {

    if ($_SESSION['nomina_empleados'] == 1) {
        // Incluimos el archivo Factura.php
        require('FormatoMuchoshojasBlanco.php');

        // Establecemos los datos de la empresa
        include 'empresa.php';

        //Incluímos la clase Venta
        require_once "../modelos/Nomina_pagos.php";
        $resobject = new NominaPagos();

        $rsptav = $resobject->cabeceranominapagos($_GET["id"]);
        //Recorremos todos los valores obtenidos
        $regv = $rsptav->fetch_object();


        // Establecemos la configuración de la factura
        $pdf = new PDF_Invoice('L', 'mm', 'Legal');
        $pdf->SetFont("Arial", "", 9);
        $pageCount = 0;
        $pdf->AddPage();
        $pageCount++;

        // Función para imprimir la cabecera

        $pdf->SetDrawColor(0, 0, 0);



        $pdf->fact_dev(utf8_decode("# Nomina: " . $regv->idnomina_pagos), "");
        $pdf->temporaire("");
        //$pdf->addDateNominaPägos($regv->fechainicio,$regv->fechafin);
        $pdf->SetFont("Arial", "", 9);
        $pdf->SetXY(310, 1);
        $pdf->Multicell(100, 4, utf8_decode("NOMINA DE PAGOS"), 0, "L");

        //$pdf->Rect(10,43, 100, 4, 'F'); //Rectángulo relleno y con liena
        $url = '../files/articulos/';
        $color_r_texto = 255;
        $color_g_texto = 255;
        $color_b_texto = 255;

        $color_r = 0;
        $color_g = 0;
        $color_b = 0;

        $pdf->SetXY(30, 209);
        //$pdf->Image($url . $regv->sucursal_imagen, 10, 3, 40, 25);
        $pdf->SetXY(100, 5);
        $pdf->Multicell(70, 4, $regv->sucursal_nombre, 0, "C");
        $pdf->SetXY(100, 10);
        $pdf->Cell(70, 4, "Nit: " . $regv->sucursal_nit, 0, 0, "C");
        $pdf->SetXY(100, 15);
        $pdf->Multicell(70, 4, "Direc: " . $regv->sucursal_direccion, 0, "C");



        $pdf->SetDrawColor(255, 255, 255);
        $pdf->addRectangulo1();

        $pdf->SetXY(10, 29);
        $pdf->Cell(275, 4, utf8_decode("Descripcion: " . $regv->descripcion), 0, "L");

        // ====== TÍTULO DE LA SECCIÓN ======
        $pdf->SetXY(10, 33);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 10);

        $pdf->Cell(335, 7, utf8_decode(':: PERIODO: ' . date("d-m-Y", strtotime($regv->fechainicio)) . ' al ' . date("d-m-Y", strtotime($regv->fechafin)) . ' ::'), 1, 1, 'C');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(10, 40);
        $pdf->SetTextColor($color_r_texto, $color_g_texto, $color_b_texto);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(335, 7, utf8_decode(':: PLANILLA DE SUELDOS Y SALARIOS ::'), 1, 1, 'C', true);
        $pdf->SetTextColor(0, 0, 0);

        // ====== ENCABEZADO DE LA TABLA ======
        $pdf->SetXY(10, 47);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(230, 230, 230);

        // Encabezados principales
        $pdf->Cell(87, 7, utf8_decode('NOMBRE DEL TRABAJADOR'), 1, 0, 'C', true);
        $pdf->Cell(15, 7, 'T/HORAS', 1, 0, 'C', true);
        $pdf->Cell(12, 7, 'V/HORA', 1, 0, 'C', true);
        $pdf->Cell(54, 7, utf8_decode('SALARIOS DEVENGADOS'), 1, 0, 'C', true);
        $pdf->Cell(35, 7, 'BONIFICACION.', 1, 0, 'C', true);
        $pdf->Cell(56, 7, utf8_decode('DEDUCCIONES LEGALES'), 1, 0, 'C', true);
        $pdf->Cell(36, 7, utf8_decode('ADELANTOS'), 1, 0, 'C', true);
        $pdf->Cell(20, 7, 'TOTAL DED.', 1, 0, 'C', true);
        $pdf->Cell(20, 7, utf8_decode('LIQUIDO'), 1, 1, 'C', true);

        // Subencabezados
        $pdf->SetFont('Arial', '', 7);

        $pdf->Cell(87, 7, '', 1, 0, 'C');
        $pdf->Cell(10, 7, '', 1, 0, 'C');
        $pdf->Cell(18, 7, '', 1, 0, 'C');

        $pdf->Cell(18, 7, 'ORDINARIO', 1, 0, 'C');
        $pdf->Cell(18, 7, 'EXTRA', 1, 0, 'C');
        $pdf->Cell(18, 7, 'DEVENGADO', 1, 0, 'C');

        $pdf->Cell(35, 7, 'DTO. 37-2001', 1, 0, 'C');

        $pdf->Cell(14, 7, 'IGSS', 1, 0, 'C');
        $pdf->Cell(14, 7, 'ISR', 1, 0, 'C');
        $pdf->Cell(14, 7, 'PREST.', 1, 0, 'C');
        $pdf->Cell(14, 7, 'OTROS', 1, 0, 'C');

        $pdf->Cell(18, 7, 'QUINCENAL', 1, 0, 'C');
        $pdf->Cell(18, 7, 'SALARIAL', 1, 0, 'C');

        $pdf->Cell(20, 7, '', 1, 0, 'C');
        $pdf->Cell(20, 7, 'RECIBIR', 1, 1, 'C');

        // ====== DETALLES DE CUOTAS ======
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetDrawColor(200, 200, 200);
        $pdf->Ln(1);
        $total_empleados = 0;

        $sum_salario_base = 0;
        $sum_salario_extra = 0;
        $sum_total_devengado = 0;
        $sum_bonificacion_ley = 0;
        $sum_bonificacion_productividad = 0;
        $sum_descuento_igss = 0;
        $sum_descuento_isr = 0;
        $sum_abono_prestamo = 0;
        $sum_abono_otrosDescuentos = 0;
        $sum_abono_adelantoQuincenal = 0;
        $sum_abono_adelantoSalarial = 0;
        $sum_total_deducciones = 0;
        $sum_liquido_recibir = 0;
        $rsptads = $resobject->detallecabeceranominapagos($_GET["id"]);
        while ($regds = $rsptads->fetch_object()) {
            $totalbonificacion = $regds->bonificacion_ley + $regds->bonificacion_productividad;

            $pdf->Cell(87, 7, utf8_decode($regds->nombre_empleado), 1, 0, 'C');
            $pdf->Cell(15, 7, ROUND($regds->horas_trabajados, 2), 1, 0, 'C');
            $pdf->Cell(12, 7, ROUND($regds->diario, 2), 1, 0, 'C');
            $pdf->Cell(18, 7, number_format($regds->total_salario, 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(18, 7, number_format($regds->salario_extra, 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(18, 7, number_format($regds->total_devengado, 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(35, 7, number_format($totalbonificacion, 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(14, 7, number_format($regds->descuento_igss, 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(14, 7, number_format($regds->descuento_isr, 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(14, 7, number_format($regds->abono_prestamo, 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(14, 7, number_format($regds->abono_otrosDescuentos, 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(18, 7, number_format($regds->abono_adelantoQuincenal, 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(18, 7, number_format($regds->abono_adelantoSalarial, 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(20, 7, number_format($regds->total_deducciones, 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(20, 7, number_format($regds->liquido_recibir, 2, '.', ','), 1, 1, 'R');

            $total_empleados++;
            $sum_salario_base += $regds->total_salario;
            $sum_salario_extra += $regds->salario_extra;
            $sum_total_devengado += $regds->total_devengado;
            $sum_bonificacion_ley += $totalbonificacion;
            $sum_descuento_igss += $regds->descuento_igss;
            $sum_descuento_isr += $regds->descuento_isr;
            $sum_abono_prestamo += $regds->abono_prestamo;
            $sum_abono_otrosDescuentos += $regds->abono_otrosDescuentos;
            $sum_abono_adelantoQuincenal += $regds->abono_adelantoQuincenal;
            $sum_abono_adelantoSalarial += $regds->abono_adelantoSalarial;
            $sum_total_deducciones += $regds->total_deducciones;
            $sum_liquido_recibir += $regds->liquido_recibir;
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Ln(2);

        // Totales debajo de cada columna
        $pdf->Cell(87, 7, utf8_decode("TOTAL EMPLEADOS: ") . $total_empleados, 1, 0, 'L');

        $pdf->Cell(15, 7, "", 1, 0, 'C'); // Días → sin total general
        $pdf->Cell(12, 7, "", 1, 0, 'C'); // Diario → sin total general

        $pdf->Cell(18, 7, number_format($sum_salario_base, 2), 1, 0, 'R'); // Ordinario
        $pdf->Cell(18, 7, number_format($sum_salario_extra, 2), 1, 0, 'R'); // Extra
        $pdf->Cell(18, 7, number_format($sum_total_devengado, 2), 1, 0, 'R'); // Devengado

        $pdf->Cell(35, 7, number_format($sum_bonificacion_ley, 2), 1, 0, 'R');

        $pdf->Cell(14, 7, number_format($sum_descuento_igss, 2), 1, 0, 'R');
        $pdf->Cell(14, 7, number_format($sum_descuento_isr, 2), 1, 0, 'R');
        $pdf->Cell(14, 7, number_format($sum_abono_prestamo, 2), 1, 0, 'R');
        $pdf->Cell(14, 7, number_format($sum_abono_otrosDescuentos, 2), 1, 0, 'R');

        $pdf->Cell(18, 7, number_format($sum_abono_adelantoQuincenal, 2), 1, 0, 'R');
        $pdf->Cell(18, 7, number_format($sum_abono_adelantoSalarial, 2), 1, 0, 'R');

        $pdf->Cell(20, 7, number_format($sum_total_deducciones, 2), 1, 0, 'R');
        $pdf->Cell(20, 7, number_format($sum_liquido_recibir, 2), 1, 1, 'R');

        $pdf->Ln(1);
        $pdf->SetX(10);
        $pdf->Cell(95, 4, utf8_decode("Usuario Creacion: " . $regv->nombre_usuario), 0, "L");
        // Línea para firma
        $pdf->SetLineWidth(0.4);
        $pdf->SetDrawColor(0, 0, 0);

        $pdf->Ln(10);
        $pdf->SetX(10);

        $pdf->Cell(95, 1, '', 'T', 0, 'C');
        $pdf->Cell(140, 1, "", 0, 'C'); // Días → sin total general
        $pdf->Cell(95, 1, '', 'T', 1, 'C');

        $pdf->SetLineWidth(0.2); // devolver grosor normal

        $pdf->Ln(1);
        $pdf->SetX(10);
        // Primera caja (centro dentro de 95mm)
        $pdf->Cell(95, 5, utf8_decode("Departamento de Contabilidad"), 0, 0, 'C');
        $pdf->Cell(140, 5, "", 0, 'C'); // Días → sin total general
        // Segunda caja
        $pdf->Cell(95, 5, utf8_decode("Gerencia General"), 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetX(10);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->Cell(335, 1, "", 1, 0, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln(5);


        $pdf->Output('Nomina Empleado No' . $regv->idnomina_pagos . ".pdf", 'I');
    } else {
        echo 'No tiene permiso para visualizar el reporte';
    }
}
?>