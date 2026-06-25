<?php
// Activamos el almacenamiento en el buffer
ob_start();
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
        require_once "../modelos/Nomina_pagos_vacaciones.php";
        $resobject = new nominapagosvacaciones();

        $rsptav = $resobject->cabecera($_GET["id"]);
        //Recorremos todos los valores obtenidos
        $regv = $rsptav->fetch_object();


        // Establecemos la configuración de la factura
        $pdf = new PDF_Invoice('P', 'mm', 'A4');
        $pdf->SetFont("Arial", "", 9);
        $pageCount = 0;
        $pdf->AddPage();
        $pageCount++;

        // Función para imprimir la cabecera

        $pdf->SetDrawColor(0, 0, 0);



        $pdf->fact_dev(utf8_decode("# Empleado: " . $regv->idempleado), "");
        $pdf->temporaire("");
        $pdf->SetFont("Arial", "", 9);
        $pdf->SetXY(310, 1);
        $pdf->Multicell(100, 4, utf8_decode("VACACIONES EMPLEADOS"), 0, "L");

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
        $pdf->SetXY(50, 5);
        $pdf->Multicell(70, 4, $regv->sucursal_nombre, 0, "C");
        $pdf->SetXY(50, 10);
        $pdf->Cell(70, 4, "Nit: " . $regv->sucursal_nit, 0, 0, "C");

        $pdf->SetXY(10, 25);
        $pdf->Cell(70, 4, "Empleado: " . $regv->empleado, 0, 0, "L");
        $pdf->SetXY(10, 30);
        $pdf->Cell(70, 4, "Fecha de Inicio Labores: " . date("d-m-Y", strtotime($regv->fechainiciolaboral)), 0, 0, "L");

        $pdf->SetDrawColor(255, 255, 255);
        $pdf->addRectangulo1();

        // ====== TÍTULO DE LA SECCIÓN ======
        $pdf->SetXY(10, 50);
        $pdf->SetTextColor($color_r_texto, $color_g_texto, $color_b_texto);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 7, utf8_decode(':: DETALLE DE VACACIONES ::'), 1, 1, 'C', true);
        $pdf->SetTextColor(0, 0, 0);

        // ====== ENCABEZADO DE LA TABLA ======
        $pdf->SetXY(10, 57);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(230, 230, 230);

        // Encabezados principales
        // Encabezados ajustados a 190 mm
        $pdf->Cell(60, 7, utf8_decode('PERIODO DE VACACIONES'), 1, 0, 'C', true);
        $pdf->Cell(35, 7, utf8_decode('FECHA SOLICITUD'), 1, 0, 'C', true);
        $pdf->Cell(15, 7, 'DIAS', 1, 0, 'C', true);
        $pdf->Cell(80, 7, 'MOTIVO', 1, 1, 'C', true);


        // Subencabezados
        $pdf->SetFont('Arial', '', 7);


        // ====== DETALLES DE CUOTAS ======
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetDrawColor(200, 200, 200);
        $pdf->Ln(5);

        $rsptads = $resobject->detalleVacaciones($_GET["id"]);
        while ($regds = $rsptads->fetch_object()) {

            // PERIODO (Inicio - Fin)
            $periodo = utf8_decode(date("d-m-Y", strtotime($regds->fechainicio)) . " al " . date("d-m-Y", strtotime($regds->fechafin)));
            $pdf->Cell(60, 7, $periodo, 1, 0, 'C');
            // FECHA SOLICITUD
            $pdf->Cell(35, 7, utf8_decode(date("d-m-Y", strtotime($regds->fechasolicitud))), 1, 0, 'C');
            // DIAS
            $pdf->Cell(15, 7, $regds->dias_solicitados, 1, 0, 'C');
            // MOTIVO
            $pdf->Cell(80, 7, utf8_decode($regds->motivo), 1, 1, 'C');
            $pdf->Ln(3);
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Ln(1);
        $pdf->SetX(10);
        $pdf->MultiCell(100, 4, utf8_decode("Usuario Creacion: " . $regv->nombre_usuario), 0, "L");
        $pdf->Ln(1);
        $pdf->SetX(10);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->Cell(190, 1, "", 1, 0, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln(5);


        $pdf->Output('Detalle Vacaciones Empleado No' . $regv->idempleado . ".pdf", 'I');
    } else {
        echo 'No tiene permiso para visualizar el reporte';
    }
}
?>