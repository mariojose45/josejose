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
        require_once "../modelos/Nomina_otrosdecuentos_empleado.php"; 
        $resobject= new Nominaotrosdecuentosempleado();

        $rsptav = $resobject->estadoCtaprestamo($_GET["id"]);  
        //Recorremos todos los valores obtenidos
        $regv = $rsptav->fetch_object();
         

        // Establecemos la configuración de la factura
        $pdf = new PDF_Invoice('P', 'mm', 'A4');
        $pdf->SetFont( "Arial", "", 9);
        $pageCount = 0;
        $pdf->AddPage();
        $pageCount++;

        // Función para imprimir la cabecera

        $pdf->SetDrawColor(0, 0, 0);

       

        $pdf->fact_dev( utf8_decode("# Prestamo: ".$regv->idotrosdecuentosempleado),"" );
        $pdf->temporaire( "" ); 
        $pdf->addDatePrestamo($regv->monto_prestamo,$regv->tipo_operacion,$regv->no_cuotas,
        $regv->fecha_prestamo,$regv->fecha_ultimo_abono);
        $pdf->SetFont( "Arial", "", 9);
        $pdf->SetXY(140,1); 
        $pdf->Multicell(100,4,utf8_decode("ESTADO DE CUENTA DE PRESTAMO"),0,"L"); 
    
    //$pdf->Rect(10,43, 100, 4, 'F'); //Rectángulo relleno y con liena
        $url='../files/articulos/';
        $color_r_texto=255;
        $color_g_texto=255;
        $color_b_texto=255;
    
        $color_r=0;
        $color_g=0;
        $color_b=0;

        $pdf->SetXY(30,209);
        //pdf->Image($url.$regv->sucursal_imagen,10 ,3, 40 , 25 );
        $pdf->SetXY(50,5); 
        $pdf->Multicell(70,4,$regv->sucursal_nombre,0,"C");   
        $pdf->SetXY(50,10); 
        $pdf->Cell(70,4,"Nit: ".$regv->sucursal_nit,0,0,"C");     
        $pdf->SetXY(50,15); 
        $pdf->Multicell(70,4,"Direc: ".$regv->sucursal_direccion,0,"C");  
        $pdf->SetXY(35,35);
        $pdf->Cell(95,4,utf8_decode("Usuario Creacion: ".$regv->usuario),0,"L"); 
                 
            $pdf->SetDrawColor(255, 255, 255);
            $pdf->addRectangulo1();  
            
            $pdf->SetXY(10,39);
            $pdf->Cell(190,4,utf8_decode("Empleado: ".$regv->nombre_empleado),0,"L"); 
            $pdf->SetXY(10,45);
            $pdf->Cell(95,4,utf8_decode("Direccion: ".$regv->direccion_empleado),0,"L"); 
            $pdf->SetXY(10,50);  
            $pdf->Cell(63,4,utf8_decode("Dpi: ".$regv->cui_empleado),0,"L");            
            $pdf->SetXY(10,55); 
            $pdf->Cell(63,4,utf8_decode("Abono Prestamo: ".$regv->abono_prestamo),0,"L");     
            $pdf->Cell(63,4,utf8_decode("Saldo Prestamo: ".$regv->saldo_prestamos),0,"L");                        
            $pdf->Cell(63,4,utf8_decode("No. Cuotas Pagadas: ".$regv->no_cuotas_pagadas),0,"L");    

            // ====== TÍTULO DE LA SECCIÓN ======
            $pdf->SetXY(10, 60);
            $pdf->SetTextColor($color_r_texto, $color_g_texto, $color_b_texto);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(190, 7, utf8_decode(':: DETALLE DE CUOTAS ::'), 1, 1, 'C', true);
            $pdf->SetTextColor(0, 0, 0);

            // ====== ENCABEZADO DE LA TABLA ======
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetFillColor(230, 230, 230);
            $pdf->SetXY(10, 67);
            $pdf->Cell(63, 7, utf8_decode('No. Cuota'), 1, 0, 'C', true);
            $pdf->Cell(63, 7, utf8_decode('Fecha Abono'), 1, 0, 'C', true);
            $pdf->Cell(64, 7, utf8_decode('Monto Abono (Q)'), 1, 1, 'C', true);

            // ====== DETALLES DE CUOTAS ======
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetDrawColor(200, 200, 200);
            $pdf->Ln(5);
            $rsptads = $resobject->listarDetallecuotas($_GET["id"]);
            while($regds = $rsptads->fetch_object()){ 
                $fecha_formateada = date("d-m-Y", strtotime($regds->fecha_abono));
                $monto = number_format($regds->monto_abono, 2, '.', ',');
                $pdf->Cell(63, 7, utf8_decode($regds->no_cuota), 1, 0, 'C');
                $pdf->Cell(63, 7, utf8_decode($fecha_formateada), 1, 0, 'C'); // <-- ya formateada
                $pdf->Cell(64, 7, $monto, 1, 1, 'R');
              $pdf->Ln(2);
        
            }
        $pdf->Ln(5);
        $pdf->SetX(10);               
        $pdf->SetTextColor(255,255,255); 
        $pdf->SetFillColor(0,0,0);
        $pdf->Cell(190,5,".::ULTIMA LINEA::.",1,0,'C',1); 
        $pdf->SetTextColor(0,0,0); 
        $pdf->Ln(5);
        // ====== TÍTULO DETALLE DE ABONOS ======
        $pdf->Ln(8);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(190, 7, utf8_decode(':: DETALLE DE ABONOS ::'), 1, 1, 'C', true);

        // ====== ENCABEZADO DE LA TABLA ======
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(15, 7, utf8_decode('ID'), 1, 0, 'C', true);
        $pdf->Cell(33, 7, utf8_decode('PRÉSTAMO (Q)'), 1, 0, 'C', true);
        $pdf->Cell(33, 7, utf8_decode('ABONO (Q)'), 1, 0, 'C', true);
        $pdf->Cell(33, 7, utf8_decode('SALDO (Q)'), 1, 0, 'C', true);
        $pdf->Cell(36, 7, utf8_decode('FECHA'), 1, 0, 'C', true);
        $pdf->Cell(40, 7, utf8_decode('USUARIO'), 1, 1, 'C', true);

        // ====== DETALLES DE ABONOS ======
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetDrawColor(200, 200, 200);

        $rsptad = $resobject->DetalleestadoCtaprestamo($_GET["id"]);

        while ($regd = $rsptad->fetch_object()) {
            // Línea principal
            $fecha_formateada = date("d-m-Y", strtotime($regd->fecha_hora));
            $pdf->Cell(15, 7, utf8_decode($regd->idotrosdecuentosempleado), 1, 0, 'C');
            $pdf->Cell(33, 7, number_format($regd->monto_prestamo, 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(33, 7, number_format($regd->abono_prestamo, 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(33, 7, number_format($regd->saldo_prestamo, 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(36, 7, utf8_decode($fecha_formateada), 1, 0, 'C');
            $pdf->Cell(40, 7, utf8_decode($regd->usuario), 1, 1, 'C');

            // Subfila: cuenta y descripción
            $x = $pdf->GetX();
            $y = $pdf->GetY();

            $pdf->SetFont('Arial', 'I', 8);
            $pdf->SetFillColor(245, 245, 245);
            $pdf->SetTextColor(50, 50, 50);

            // Nombre de la cuenta
            $pdf->MultiCell(190, 6, utf8_decode("Cuenta: " . $regd->cta_nombre), 1, 'L', true);
            // Descripción con salto de línea automático
            $pdf->MultiCell(190, 6, utf8_decode("Descripción: " . $regd->descripcion), 1, 'L', false);

            $pdf->Ln(2); // espacio entre registros
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetTextColor(0, 0, 0);
        }
        $pdf->Ln(5);
        $pdf->SetX(10);               
        $pdf->SetTextColor(255,255,255); 
        $pdf->SetFillColor(0,0,0);
        $pdf->Cell(190,5,".::ULTIMA LINEA::.",1,0,'C',1); 
        $pdf->SetTextColor(0,0,0); 

        $pdf->Output('Prestamo Empleado No'.$regv->idotrosdecuentosempleado.".pdf", 'I');
    } else {
        echo 'No tiene permiso para visualizar el reporte';
    }
}
?>
