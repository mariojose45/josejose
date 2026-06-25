<?php
// Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';

if (!isset($_SESSION["nombre"])) {
    echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else { 
    if ($_SESSION['ventas'] == 1) {
        // Librerías necesarias
        require('FormatoMuchoshojasBlanco.php'); 
        require_once "../modelos/Cotizaciones.php"; 
        require_once "num2letras.php";
        include 'empresa.php';  

        $cotizaciones = new Cotizaciones();
        $idventa = $_GET["id"];

        // 1. Obtener Cabecera (Datos del Cliente y Venta)
        $rsptav = $cotizaciones->ventacabecera2($idventa);
        $regv = $rsptav->fetch_object();

        // Configuración Inicial PDF
        $pdf = new PDF_Invoice('P', 'mm', 'A4');
        $pdf->AddPage();

        // --- ESTILO Y COLORES ---
        $colorPrincipal = array(52, 73, 94); // Gris Oscuro Profesional
        $colorAcento = array(22, 160, 133);  // Verde Esmeralda para estados
        $pdf->SetDrawColor(180, 180, 180);

        // --- ENCABEZADO: LOGO Y DATOS EMPRESA ---
        $url = '../files/articulos/';
        if (!empty($regv->sucursal_imagen)) {
            $pdf->Image($url . $regv->sucursal_imagen, 10, 8, 40);
        }
        
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->SetXY(100, 10);
        $pdf->Cell(100, 7, utf8_decode("HOJA DE SERVICIO TÉCNICO"), 0, 1, 'R');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetX(100);
        $pdf->Cell(100, 5, utf8_decode("REFERENCIA: " . $regv->num_comprobante), 0, 1, 'R');
        $pdf->SetX(100);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(100, 5, utf8_decode("ID Interno: " . $regv->idventa), 0, 1, 'R');

        // Datos de la sucursal debajo del logo
        $pdf->SetXY(10, 25);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(100, 5, utf8_decode($regv->nombre_comercial), 0, 1, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(100, 4, utf8_decode($regv->sucursal_direccion), 0, 1, 'L');
        $pdf->Cell(100, 4, utf8_decode("Tel: " . $regv->sucursal_telefono), 0, 1, 'L');

        // --- BLOQUE: INFORMACIÓN DEL CLIENTE ---
        $pdf->Ln(10);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 7, utf8_decode(" DATOS DEL CLIENTE"), 0, 1, 'L', 1);
        
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(30, 7, utf8_decode("Nombre:"), "B", 0, 'L');
        $pdf->Cell(100, 7, utf8_decode($regv->cliente), "B", 0, 'L');
        $pdf->Cell(20, 7, utf8_decode("Fecha:"), "B", 0, 'L');
        $pdf->Cell(40, 7, $regv->fecha, "B", 1, 'L');

        $pdf->Cell(30, 7, utf8_decode("Dirección:"), "B", 0, 'L');
        $pdf->Cell(100, 7, utf8_decode($regv->direccion), "B", 0, 'L');
        $pdf->Cell(20, 7, utf8_decode("Teléfono:"), "B", 0, 'L');
        $pdf->Cell(40, 7, $regv->telefono, "B", 1, 'L');

        // --- BLOQUE CENTRAL: DETALLE DEL SERVICIO ---
        $pdf->Ln(8);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor($colorPrincipal[0], $colorPrincipal[1], $colorPrincipal[2]);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(190, 8, utf8_decode(" SEGUIMIENTO Y ESTADO DE SERVICIOS"), 0, 1, 'L', 1);
        
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln(3);

        $rspta_serv = $cotizaciones->venta_servicio_rpt($idventa);
        
        if ($rspta_serv->num_rows > 0) {
            while ($regs = $rspta_serv->fetch_object()) {
                // Etiqueta de Estado con color
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetTextColor($colorAcento[0], $colorAcento[1], $colorAcento[2]);
                $pdf->Cell(190, 6, utf8_decode("ESTADO: " . $regs->estado_servicio_venta), 0, 1, 'L');
                $pdf->SetTextColor(0, 0, 0);

                // Cuadro de datos técnicos
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(40, 6, utf8_decode("ID INSTALACIÓN/IP:"), 0, 0);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(55, 6, utf8_decode($regs->ip_instalacion), 0, 0);
                
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(40, 6, utf8_decode("FECHA OPERACIÓN:"), 0, 0);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(55, 6, $regs->fecha_hora, 0, 1);

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(40, 6, utf8_decode("COMENTARIOS:"), 0, 1);
                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCell(190, 5, utf8_decode($regs->descripcion_comentario), 1, 'L');
                
                $pdf->SetFont('Arial', 'I', 7);
                $pdf->Cell(190, 5, utf8_decode("Responsable: " . $regs->usuario_operacion . " | Sucursal: " . $regs->sucursal_operacion), 0, 1, 'R');
                $pdf->Ln(5);
                $pdf->Cell(190, 0, "", "T", 1); // Línea divisoria entre servicios
                $pdf->Ln(3);
            }
        } else {
            $pdf->SetFont('Arial', 'I', 10);
            $pdf->Cell(190, 15, utf8_decode("No existen servicios registrados para esta orden."), 0, 1, 'C');
        }

        // --- SECCIÓN DE FIRMAS ---
        $pdf->SetY(-40);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(63, 10, "___________________________", 0, 0, 'C');
        $pdf->Cell(63, 10, "___________________________", 0, 0, 'C');
        $pdf->Cell(63, 10, "___________________________", 0, 1, 'C');
        
        $pdf->Cell(63, 4, utf8_decode("Firma Técnico"), 0, 0, 'C');
        $pdf->Cell(63, 4, utf8_decode("Firma Cliente"), 0, 0, 'C');
        $pdf->Cell(63, 4, utf8_decode("Sello de Entrega"), 0, 1, 'C');

        $pdf->Output('Servicio_'.$regv->num_comprobante.'.pdf', 'I');

    } else {
        echo 'No tiene permiso para visualizar el reporte';
    }
}
ob_end_flush();
?>