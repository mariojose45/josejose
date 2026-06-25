<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

if (!isset($_SESSION["nombre"])) {
    echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
    if ($_SESSION['ventas'] == 1) {
        //Incluímos el archivo Factura.php
        require('FormatoCartaConFacturaTickekt2.php');

        //Establecemos los datos de la empresa
        include 'empresa.php';

        //Incluímos la clase Venta
        require_once "../modelos/Parqueo_Operaciones.php";
        $parqueooperaciones = new ParqueoOperaciones();
        $rsptav = $parqueooperaciones->cobrocabecera($_GET["id"]);
        //Recorremos todos los valores obtenidos
        $regv = $rsptav->fetch_object();

        //Establecemos la configuración de la factura
        $pdf = new PDF_Invoice('P', 'mm', array(79, 2500));
        $pdf->AddPage();

        $pdf->fact_dev(utf8_decode("FACTURA"), "");
        $pdf->temporaire("");

        $pdf->SetXY(110, 1);
        $pdf->Multicell(100, 4, utf8_decode(""), 0, "C");

        $url = '../files/articulos/';
        $color_r_texto = 255;
        $color_g_texto = 255;
        $color_b_texto = 255;

        $color_r = 0;
        $color_g = 0;
        $color_b = 0;

        $xposision = 1;
        $ancchodefial = 79;


        $x = (79 - 40) / 2;

        $pdf->Image($url . $regv->sucursal_imagen, $x, 3, 40, 15);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY($xposision, 35);
        $pdf->Multicell($ancchodefial, 4, utf8_decode($regv->nombre_comercial), 0, "C");
        $pdf->Ln(3);
        $pdf->SetX($xposision);
        $pdf->Multicell($ancchodefial, 4, utf8_decode($regv->nombre_fel), 0, "C");
        $pdf->Ln(3);
        $pdf->SetX($xposision);
        $pdf->Multicell($ancchodefial, 4, utf8_decode("NIT: " . $regv->sucursal_nit), 0, "C");
        $pdf->Ln(3);
        $pdf->SetX($xposision);
        $pdf->Multicell($ancchodefial, 4, utf8_decode($regv->direccion_fiscal), 0, "C");
        $pdf->Ln(3);
        $pdf->SetX($xposision);
        $pdf->Multicell($ancchodefial, 4, utf8_decode($regv->sucursal_telefono), 0, "C");
        $pdf->Ln(3);
        $pdf->SetX($xposision);
        $pdf->Multicell($ancchodefial, 4, utf8_decode($regv->sucursal_email), 0, "C");

        // --- INICIO: Dibujar Línea Horizontal ---
        $pdf->Ln(2); // Pequeño salto
        $y = $pdf->GetY();
        // $pdf->Line(X_Inicio, Y_Inicio, X_Fin, Y_Fin)
        $pdf->Line(2, $y, 77, $y);
        // --- FIN ---
        $pdf->Ln(3);
        $pdf->SetX($xposision);
        $pdf->Multicell($ancchodefial, 4, utf8_decode("CLIENTE: " . $regv->nombre_cliente), 0, "C");
        $pdf->Ln(3);
        $pdf->SetX($xposision);
        $pdf->Multicell($ancchodefial, 4, utf8_decode($regv->tipo_documentoCliente . ": " . $regv->num_docCliente), 0, "C");
        $pdf->Ln(3);
        $pdf->SetX($xposision);
        $pdf->Multicell($ancchodefial, 4, utf8_decode("DIRECCION: " . $regv->direccionCliente), 0, "C");
        $pdf->Ln(3);
        $pdf->SetX($xposision);
        $pdf->Multicell($ancchodefial, 4, utf8_decode("TELEFONO: " . $regv->telesCliente), 0, "C");
        // --- INICIO: Dibujar Línea Horizontal ---
        $pdf->Ln(2); // Pequeño salto
        $y = $pdf->GetY();
        // $pdf->Line(X_Inicio, Y_Inicio, X_Fin, Y_Fin)
        $pdf->Line(2, $y, 77, $y);
        // --- FIN ---        
        $pdf->Ln(3);
        $pdf->SetX($xposision);
        if ($regv->tip_evento_cobro == 'TICKET') {
            $pdf->Multicell($ancchodefial, 4, utf8_decode("TIPO: " . $regv->tipo_vehiculo_cobro), 0, "C");
        } else {
            $pdf->Multicell($ancchodefial, 4, utf8_decode("TIPO: " . $regv->tipo_vehiculoEventos), 0, "C");
        }
        $pdf->Ln(3);
        $pdf->SetX($xposision);
        if ($regv->tip_evento_cobro == 'TICKET') {
            $pdf->Multicell($ancchodefial, 4, utf8_decode("PLACA: " . $regv->placa), 0, "C");
        } else {
            $pdf->Multicell($ancchodefial, 4, utf8_decode("PLACA: " . $regv->numeroplacaEvento), 0, "C");
        }
        $pdf->Ln(3);
        $pdf->SetX($xposision);
        $pdf->Multicell($ancchodefial, 4, utf8_decode($regv->tip_evento_cobro), 0, "C");

        $pdf->Ln(3);
        $pdf->SetX($xposision);
        if ($regv->tip_evento_cobro == 'TICKET') {
            $pdf->Multicell($ancchodefial, 4, utf8_decode("FECHA INGRESO: " . $regv->fecha_ingreso_cobro), 0, "C");
        } else {
            $pdf->Multicell($ancchodefial, 4, utf8_decode("FECHA INGRESO: " . $regv->fecha_cobro), 0, "C");
        }

        $pdf->Ln(3);
        $pdf->SetX($xposision);
        $pdf->Multicell($ancchodefial, 4, utf8_decode("FECHA SALIDA: " . $regv->fecha_cobro), 0, "C");

        $pdf->Ln(3);
        $pdf->SetX($xposision);
        $pdf->Multicell($ancchodefial, 4, utf8_decode("H:M: " . $regv->tiempo_transcurrido_horas . ":" . $regv->tiempo_transcurrido_minutos), 0, "C");



        // --- INICIO: Dibujar Línea Horizontal ---
        $pdf->Ln(2); // Pequeño salto
        $y = $pdf->GetY();
        // $pdf->Line(X_Inicio, Y_Inicio, X_Fin, Y_Fin)
        $pdf->Line(2, $y, 77, $y);
        // --- FIN ---  
        $rsptInfo = $parqueooperaciones->informaciontickets();
        //Recorremos todos los valores obtenidos
        $regvinfo = $rsptInfo->fetch_object();

        $pdf->Ln(3);
        $pdf->SetX($xposision);
        $pdf->Multicell($ancchodefial, 4, utf8_decode($regvinfo->instruccionesdeticket), 0, "C");

        $pdf->Ln(3);
        $pdf->SetX($xposision);
        $pdf->Multicell($ancchodefial, 4, utf8_decode($regvinfo->mensajefinal), 0, "C");


        // --- IMPRESIÓN DE CÓDIGO DE BARRAS DE LA LECTURA ---
        $pdf->Ln(5);
        $y = $pdf->GetY();
        $code = trim($regv->idlectura); // Código a convertir en barras

        // Code128(X, Y, codigo, ancho, alto)
        // Posicionaremos a 20mm de márgen izq y con 40mm de ancho total, alt 10mm
        $pdf->Code128(20, $y, $code, 39, 10);

        // Imprimimos los números abajo del código de barras
        $pdf->SetXY(20, $y + 10);
        $pdf->Cell(39, 4, $code, 0, 0, 'C');
        // --- FIN CÓDIGO DE BARRAS ---

        $pdf->Ln(5);
        $pdf->SetX($xposision);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor($color_r, $color_g, $color_b);
        $pdf->Cell($ancchodefial, 5, ".::ULTIMA LINEA::.", 1, 0, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->Output('Lectura No ' . $regv->idlectura . ".pdf", 'I');

    } else {
        echo 'No tiene permiso para visualizar el reporte';
    }
}
ob_end_flush();
?>