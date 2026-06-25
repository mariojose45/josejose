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
        require_once "../modelos/Nomina_pagos_14_aguinaldo.php"; 
        $resobject= new NominaPagos14Aguinaldo();

        $rsptav = $resobject->cabeceranominapagos($_GET["id"]);  
        //Recorremos todos los valores obtenidos
        $regv = $rsptav->fetch_object();
         

        // Establecemos la configuración de la factura
        $pdf = new PDF_Invoice('L', 'mm', 'Legal');
        $pdf->SetFont( "Arial", "", 9);
        $pageCount = 0;
        $pdf->AddPage();
        $pageCount++;

        // Función para imprimir la cabecera

        $pdf->SetDrawColor(0, 0, 0);

       

        $pdf->fact_dev( utf8_decode("# : ".$regv->idnomina_pagos_14_aguinaldo),"" );
        $pdf->temporaire( "" ); 
        $pdf->SetFont( "Arial", "", 9);
        $pdf->SetXY(290,1); 
        $pdf->Multicell(100,4,utf8_decode("Nomina de Pagos ".$regv->tipo_operacion),0,"L"); 
    
    //$pdf->Rect(10,43, 100, 4, 'F'); //Rectángulo relleno y con liena
        $url='../files/articulos/';
        $color_r_texto=255;
        $color_g_texto=255;
        $color_b_texto=255;
    
        $color_r=0;
        $color_g=0;
        $color_b=0;

        $pdf->SetXY(30,209);
        //$pdf->Image($url.$regv->sucursal_imagen,10 ,3, 40 , 25 );
        $pdf->SetFont( "Arial", "B", 12);
        $pdf->SetXY(100,5); 
        $pdf->Multicell(70,4,$regv->sucursal_nombre,0,"C");      
        $pdf->SetFont( "Arial", "", 9);
        $pdf->SetXY(110,10);
        $pdf->Cell(95,4,utf8_decode("Usuario Creacion: ".$regv->nombre_usuario),0,"L"); 
                 
            $pdf->SetDrawColor(255, 255, 255);
            $pdf->addRectangulo1();  
            
            $pdf->SetXY(10,25);
            $pdf->Cell(275,4,utf8_decode("Descripcion: ".$regv->descripcion),0,"L"); 

            // ====== TÍTULO DE LA SECCIÓN ======
            $pdf->SetXY(10, 35);
            $pdf->SetTextColor($color_r_texto, $color_g_texto, $color_b_texto);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(335, 7, utf8_decode(':: Planilla de '.$regv->tipo_operacion.' Comprobante del '.date("d-m-Y", strtotime($regv->fechainicio)).' al '.date("d-m-Y", strtotime($regv->fechafin)).' ::'), 1, 1, 'C', true);
            $pdf->SetTextColor(0, 0, 0);

            // ====== ENCABEZADO DE LA TABLA ======
            // 🔵 ENCABEZADO PRINCIPAL
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->SetFillColor(230, 230, 230);

            $pdf->Cell(100, 7, utf8_decode('NOMBRE DEL TRABAJADOR'), 1, 0, 'C', true);
            $pdf->Cell(55, 7, 'PUESTO', 1, 0, 'C', true);
            $pdf->Cell(30, 7, utf8_decode('SUELDO ASIGNADO'), 1, 0, 'C', true);

            $pdf->Cell(45, 7, utf8_decode('FECHA CÁLCULO'), 1, 0, 'C', true);  // Contiene DEL y AL
            $pdf->Cell(25, 7, utf8_decode('DÍAS'), 1, 0, 'C', true);

            $pdf->Cell(25, 7, 'Total '.$regv->tipo_operacion, 1, 0, 'C', true); // Bono 14
            $pdf->Cell(25, 7, 'Anticipos', 1, 0, 'C', true);

            $pdf->Cell(30, 7, utf8_decode('LÍQUIDO RECIBIR'), 1, 1, 'C', true);


            // 🔵 SUBENCABEZADOS (SEGUNDA FILA)
            $pdf->SetFont('Arial', '', 7);

            // ORDEN – NOMBRE – PUESTO – SUELDO (sin subdivisiones)
            $pdf->Cell(100, 7, '', 1, 0, 'C');
            $pdf->Cell(55, 7, '', 1, 0, 'C');
            $pdf->Cell(30, 7, '', 1, 0, 'C');

            // FECHA DEL / AL
            $pdf->Cell(22.5, 7, 'DEL', 1, 0, 'C');
            $pdf->Cell(22.5, 7, 'AL', 1, 0, 'C');

            // DIAS A CALCULAR
            $pdf->Cell(25, 7, 'CALCULADOS', 1, 0, 'C');

            // Total Bono 14 (sin subdivisión)
            $pdf->Cell(20, 7, '', 1, 0, 'C');

            // ANTICIPOS en 1 sola celda
            $pdf->Cell(20, 7, '', 1, 0, 'C');

            // LIQUIDO RECIBIR
            $pdf->Cell(20, 7, '', 1, 1, 'C');


          // ====== DETALLES DE CUOTAS ======
            $pdf->SetDrawColor(200, 200, 200);
            $pdf->Ln(1);
            $total_empleados = 0;
            $sum_salario_base = 0;
            $sum_total_devengado = 0;
            $sum_anticipos_bono_14 = 0;            
            $sum_liquido_recibir = 0;            

          
            $rsptads = $resobject->detallecabeceranominapagos($_GET["id"]);
            while($regds = $rsptads->fetch_object()){ 
                
                $pdf->Cell(100, 7, utf8_decode($regds->nombre_empleado), 1, 0, 'C');
                $pdf->Cell(55, 7, $regds->puesto, 1, 0, 'C');
                $pdf->Cell(30, 7, number_format($regds->salario_base, 2, '.', ','), 1, 0, 'R');
                $pdf->Cell(22.5, 7, date("d-m-Y", strtotime($regds->fecha_del)), 1, 0, 'C');
                $pdf->Cell(22.5, 7, date("d-m-Y", strtotime($regds->fecha_al)), 1, 0, 'C');
                $pdf->Cell(25, 7, number_format($regds->dias_trabajados, 2, '.', ','), 1, 0, 'R');
                $pdf->Cell(25, 7, number_format($regds->total_devengado, 2, '.', ','), 1, 0, 'R');
                $pdf->Cell(25, 7, number_format($regds->anticipos_bono_14, 2, '.', ','), 1, 0, 'R');
                $pdf->Cell(30, 7, number_format($regds->liquido_recibir, 2, '.', ','), 1, 1, 'R');
                $total_empleados++;
                $sum_salario_base += $regds->salario_base;
                $sum_total_devengado += $regds->total_devengado;
                $sum_anticipos_bono_14 += $regds->anticipos_bono_14;
                $sum_liquido_recibir += $regds->liquido_recibir;

 
            }
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Ln(2);
            
            // Totales debajo de cada columna
            $pdf->Cell(155, 7, utf8_decode("TOTAL EMPLEADOS: ").$total_empleados, 1, 0, 'L');
        
            $pdf->Cell(30, 7, number_format($sum_salario_base, 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(70, 7, "", 1, 0, 'C'); // Días → sin total general
            $pdf->Cell(25.5, 7, number_format($sum_total_devengado, 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(25, 7, number_format($sum_anticipos_bono_14, 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(30, 7, number_format($sum_liquido_recibir, 2, '.', ','), 1, 1, 'R');

             
        
        $pdf->Ln(8);
        $pdf->SetX(10);               
        $pdf->SetTextColor(255,255,255); 
        $pdf->SetFillColor(0,0,0);
        $pdf->Cell(335,1,"",1,0,'C',1); 
        $pdf->SetTextColor(0,0,0); 
        $pdf->Ln(5);


        $pdf->Output('Nomina Empleado No'.$regv->idnomina_pagos_14_aguinaldo.".pdf", 'I');
    } else {
        echo 'No tiene permiso para visualizar el reporte';
    }
}
?>
