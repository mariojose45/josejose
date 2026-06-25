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

                $rsptav = $resobject->reciboempleado($_GET["id"]);
                //Recorremos todos los valores obtenidos
                $regv = $rsptav->fetch_object();


                // ===============================
//     FORMATO SOLICITUD VACACIONES
// ===============================

                $pdf = new PDF_Invoice('P', 'mm', 'A4');
                $pdf->AddPage();
                $pdf->SetFont('Arial', '', 10);

                // ===============================
// ENCABEZADO (LOGO + EMPRESA)
// ===============================

                $url = '../files/articulos/';

                //$pdf->Image($url . $regv->sucursal_imagen, 10, 5, 35);
                $pdf->SetXY(50, 5);
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(120, 6, utf8_decode($regv->sucursal_nombre), 0, 1, 'C');

                $pdf->SetXY(50, 12);
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(120, 5, utf8_decode("Nit: " . $regv->sucursal_nit), 0, 1, 'C');



                $pdf->SetXY(10, 35);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(100, 5, utf8_decode("Empleado: " . $regv->empleado), 0, 1);

                $pdf->SetXY(10, 40);
                $pdf->Cell(100, 5, utf8_decode("Fecha de Inicio Labores: " . date("d-m-Y", strtotime($regv->fechainiciolaboral))), 0, 1);


                // ===============================
//  TÍTULO PRINCIPAL
// ===============================

                $pdf->SetXY(10, 45);
                $pdf->SetFillColor(0, 0, 0);
                $pdf->SetTextColor(255, 0, 0);
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(190, 10, utf8_decode('SOLICITUD DE VACACIONES'), 1, 1, 'C', true);

                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont('Arial', '', 10);

                // ===============================
//  CAMPOS SUPERIORES
// ===============================

                $pdf->Ln(4);
                $pdf->SetX(10);
                $pdf->Cell(30, 6, 'Fecha:', 0, 0);
                $pdf->Cell(60, 6, date("d-m-Y", strtotime($regv->fechasolicitud)), 0, 1);

                $pdf->SetX(10);
                $pdf->Cell(40, 6, utf8_decode('Nombre de Empleado:'), 0, 0);
                $pdf->Cell(120, 6, utf8_decode($regv->empleado), 0, 1);

                $pdf->SetX(10);
                $pdf->Cell(40, 6, utf8_decode('Dpi:'), 0, 0);
                $pdf->Cell(120, 6, utf8_decode($regv->cui), 0, 1);

                $pdf->SetX(10);
                $pdf->Cell(25, 6, 'Puesto:', 0, 0);
                $pdf->Cell(120, 6, utf8_decode($regv->puesto), 0, 1);

                // ===============================
// TEXTO PRINCIPAL
// ===============================

                $pdf->Ln(3);
                $pdf->SetX(10);
                $pdf->MultiCell(190, 6, utf8_decode(
                        "Por medio de la presente, solicito me sea autorizado " .
                        $regv->dias_solicitados . " día(s) a cuenta de mis vacaciones."
                ), 0, 'L');

                $pdf->Ln(1);
                $pdf->SetX(10);
                $pdf->Cell(40, 6, utf8_decode('Para ser gozadas del'), 0, 0);
                $pdf->Cell(40, 6, date("d-m-Y", strtotime($regv->fechainicio)), 0, 0, 'L');

                $pdf->Cell(10, 6, utf8_decode('al'), 0, 0, 'C');
                $pdf->Cell(40, 6, date("d-m-Y", strtotime($regv->fechafin)), 0, 1, 'L');

                // ===============================
// FIRMAS EMPLEADO / JEFE
// ===============================

                $pdf->Ln(12);
                $pdf->SetX(10);

                // Línea de firma del empleado
                $pdf->Cell(90, 6, '', 'B', 0);
                $pdf->Cell(10, 6, '', 0, 0);
                $pdf->Cell(90, 6, '', 'B', 1);

                // Texto debajo
                $pdf->SetX(10);
                $pdf->Cell(90, 6, 'Solicitud', 0, 0, 'C');
                $pdf->Cell(10, 6, '', 0, 0);
                $pdf->Cell(90, 6, utf8_decode('Autorización'), 0, 1, 'C');

                $pdf->SetX(10);
                $pdf->Cell(90, 6, 'Firma Empleado', 0, 0, 'C');
                $pdf->Cell(10, 6, '', 0, 0);
                $pdf->Cell(90, 6, utf8_decode('Firma del Jefe Inmediato'), 0, 1, 'C');

                // ===============================
// PARA USO EXCLUSIVO DE LA EMPRESA
// ===============================

                $pdf->Ln(10);
                $pdf->SetX(10);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(190, 8, utf8_decode('Para uso Exclusivo de la Empresa:'), 1, 1, 'L');

                $pdf->SetFont('Arial', '', 10);
                $pdf->SetX(10);
                $pdf->Cell(30, 6, 'Fecha:', 0, 0);
                $pdf->Cell(60, 6, date('d/m/Y'), 0, 1);

                $pdf->SetX(10);
                $pdf->Cell(80, 6, utf8_decode('Vacaciones correspondientes al Periodo de:'), 0, 1);

                $pdf->SetX(10);
                $pdf->Cell(80, 6, utf8_decode('Tiene días anticipados a cuenta de vacación:'), 0, 0);
                $pdf->Cell(10, 6, 'Si [ ]', 0, 0);
                $pdf->Cell(10, 6, 'No [ ]', 0, 0);
                $pdf->Cell(20, 6, 'Cuantos: ____', 0, 1);

                $pdf->SetX(10);
                $pdf->Cell(80, 6, utf8_decode('Vacaciones pendientes por gozar:'), 0, 1);

                $pdf->SetX(10);
                $pdf->MultiCell(190, 15, utf8_decode('Observaciones:'), 0, 'L');


                $pdf->Ln(12);
                $pdf->SetX(10);
                // Firma RRHH
                $pdf->Cell(190, 6, '', 'B', 1);
                $pdf->SetX(10);
                $pdf->Cell(190, 6, utf8_decode('Recibido - Recursos Humanos'), 0, 1, 'C');

                $pdf->Ln(12);
                $pdf->SetX(10);

                $pdf->Cell(120, 5, utf8_decode("Usuario Creación: " . $regv->nombre_usuario), 0, 1, 'L');

                // ===============================
// SALIDA DEL PDF
// ===============================

                $pdf->Output('Detalle Vacaciones Empleado No' . $regv->idempleado . ".pdf", 'I');
        } else {
                echo 'No tiene permiso para visualizar el reporte';
        }
}
?>