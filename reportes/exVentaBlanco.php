<?php
// Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
    echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else { 
    
    if ($_SESSION['ventas'] == 1) {
        // Incluimos el archivo Factura.php
        require('FormatoMuchoshojas.php');   

        // Establecemos los datos de la empresa
        include 'empresa.php';

        //Incluímos la clase Venta
        require_once "../modelos/Cotizaciones.php"; 
        $cotizaciones= new Cotizaciones();

        $rsptav = $cotizaciones->ventacabecera2($_GET["id"]);  
        //Recorremos todos los valores obtenidos
        $regv = $rsptav->fetch_object();
         

        // Establecemos la configuración de la factura
        $pdf = new PDF_Invoice('P', 'mm', 'A4');
        $pageCount = 0;
        $pdf->AddPage();
        $pageCount++;
        $pdf->SetDrawColor(255, 255, 255);
        
        // Función para imprimir la cabecera
        function imprimirCabecera($pdf, $regv) {
            /////PARTE DE ABAJO           
            $pdf->SetDrawColor(255, 255, 255);
            $pdf->fact_dev( utf8_decode("FACTURA "),"" );
            $pdf->temporaire( "" ); 
            $pdf->addDate($regv->serie_ecoFactura,$regv->numero_ecoFactura,$regv->fechaCertificacion_ecoFactura,$regv->autorizacionEcoFactura,$regv->fecha);
            $pdf->SetXY(110,1); 
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->Multicell(100,4,utf8_decode("ENVIO DE PRODUCTOS"),0,"C"); 
        
            //$pdf->Rect(10,43, 100, 4, 'F'); //Rectángulo relleno y con liena
            $url='../files/articulos/';
            $color_r_texto=0;
            $color_g_texto=0;
            $color_b_texto=0;
        
            $color_r=171;
            $color_g=195;
            $color_b=220;
            if ($regv->estado=='Anulado') {
                # code...
                $pdf->Image($anulado,50 ,100, 100 , 25 );
            }

            // Asumiendo que $regv->sucursal_imagen es la imagen obtenida
            $imagePath = $url . $regv->sucursal_imagen;

            // Validar si la imagen está vacía o es igual a "0"
            if (empty($regv->sucursal_imagen) || $regv->sucursal_imagen == '0') {
                // Usar una ruta alternativa
                $imagePath = $url . '1590204245.jpg';
            }

            $pdf->SetDrawColor(255, 255, 255);
            $pdf->SetXY(30,209);
            $pdf->Image($imagePath,10 ,3, 40 , 25 );
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(50,5); 
            $pdf->Multicell(70,4,$regv->sucursal_nombre,0,"C"); 
            $pdf->SetXY(50,10); 
            $pdf->Multicell(70,4,utf8_decode($regv->nombre_fel),0,"C");     
            $pdf->SetXY(50,18); 
            $pdf->Cell(70,4,"Nit: ".$regv->sucursal_nit,0,0,"C");     
            $pdf->SetXY(50,22); 
            $pdf->Multicell(70,4,"Direc: ".$regv->sucursal_direccion,0,"C");  
            $pdf->SetXY(50,30); 
            $pdf->Multicell(70,4,"Tels: ".$regv->sucursal_telefono."  Email: ".$regv->sucursal_email,0,"C");  
            $pdf->Ln(5);
            $pdf->SetX(10);               
            $pdf->Cell(190, 5, "", "B", 0, "C");   
            $pdf->Ln(5);
            $pdf->SetX(10);               
            $pdf->Cell(190, 5, "", "B", 0, "C");                  
        
            
            $pdf->addRectangulo1();  
            $pdf->SetXY(10,30);
            $pdf->Cell(95,4,utf8_decode("User: ".$regv->usuario),0,"L"); 
            $pdf->SetXY(10,39);
            $pdf->Cell(190,4,utf8_decode("Cliente: ".$regv->cliente),0,"L"); 
            $pdf->SetXY(10,45);
            $pdf->Cell(190,4,utf8_decode("Direccion: ".$regv->direccion),0,"L");
            $pdf->SetXY(10,50); 
            $pdf->Cell(47.50,4,utf8_decode("Nit: ".$regv->num_documento),0,"L");  
            $pdf->Cell(47.50,4,utf8_decode("Tels: ".$regv->telefono),0,"L");     
            $pdf->Cell(47.50,4,utf8_decode("F/Pago: ".$regv->forma_pago),0,"L");     
            $pdf->Cell(47.50,4,utf8_decode("Email: ".$regv->email),0,"L");      
            $pdf->SetDrawColor(0, 0, 0);
    
    
            $pdf->SetDrawColor(255, 255, 255);
            $pdf->SetXY(10,57); 
            $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
            $pdf->SetFillColor($color_r,$color_g,$color_b);
            $pdf->SetDrawColor(255, 255, 255);
            $pdf->Cell(190,3.5,".::DATOS DE PRODUCTOS::.",1,0,'C',1); 
            $pdf->SetTextColor(0,0,0); 
            $pdf->SetXY(10,63);
            $pdf->SetDrawColor(0, 0, 0);
            
            $pdf->SetFillColor($color_r,$color_g,$color_b);
            $pdf->Cell(190,5.3," ",1,0,'C',1); 
            $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
            $pdf->SetFont('Arial','',8);
            //Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
            $pdf->SetDrawColor(255, 255, 255);
            $cols=array( "CANT"=>15,
                        "CODIGO"=>30,
                         "EQUIVALENCIA"=>50, 
                         "ART"=>35,
                         "P.U"=>30,
                            "SUBTOTAL"=>30);
            $pdf->addCols( $cols);
            $cols=array( "CANT"=>"C",
                         "CODIGO"=>"L",
                         "EQUIVALENCIA"=>"C",
                         "ART"=>"R",
                         "P.U"=>"R",
                         "SUBTOTAL"=>"R");
            $pdf->addLineFormat( $cols);
            $pdf->addLineFormat($cols);
            $pdf->SetDrawColor(0, 0, 0);
        }
        // Función para imprimir el footer
        function imprimirFooter($pdf, $regv) {
            require_once"num2letras.php";
            //$V=new EnLetras();  
            //$con_letra=strtoupper($V->ValorEnLetras($regv->total_venta,"QUETZALEZ"));
            $conletras=$regv->total_venta; 
            $conletrasresultado=num2letras($conletras);
            $pdf->addCadreTVAs("---".$conletrasresultado,"QUETZALEZ"); 
            $pdf->addTVAs( $regv->total_ventades, $regv->total_venta,"Q ");
            $pdf->addCadreEurosFrancs(""." "); 
            
 
            $pdf->SetXY(10,250);     
            $pdf->Cell(190,4,utf8_decode("Le atendio: ".$regv->usuario),0,0,"C");  

            $pdf->SetFont('Arial','B',10);
            $pdf->Ln(5);
            $pdf->SetX(10);     
            $pdf->Cell(190,4,utf8_decode("COD:  ".$regv->num_comprobante),0,0,"C");  

            $pdf->SetFont('Arial','',8);
            $pdf->Ln(2);
            $pdf->SetX(10);     
            $pdf->Cell(95,10,"Firma Salida: ___________________________",0); 
            $pdf->Cell(95,10,"Firma Recibido: ___________________________",0);     

            $pdf->Ln(8);
            $pdf->SetX(10);     
            $pdf->Cell(190,4,utf8_decode($regv->empresadesarrollo),0,0,"C");       
            $pdf->Ln(5);
            $pdf->SetX(10);               
            $pdf->SetTextColor(0,0,0); 
            $pdf->SetFillColor($color_r,$color_g,$color_b);
            $pdf->Cell(190,5,".::ULTIMA LINEA::.",1,0,'C',1); 
            $pdf->SetTextColor(0,0,0);  
        }
        /////PARTE DE ARRIBA        
        //function addDate( $serie,$numero,$fechafac,$numAutorizacion,$fecha )
        $pdf->fact_dev( utf8_decode("FACTURA "),"" );
        $pdf->temporaire( "" ); 
        $pdf->addDate($regv->serie_ecoFactura,$regv->numero_ecoFactura,$regv->fechaCertificacion_ecoFactura,$regv->autorizacionEcoFactura,$regv->fecha);
        $pdf->SetXY(110,1); 
        $pdf->Multicell(100,4,utf8_decode("ENVIO DE PRODUCTOS"),0,"C");
        $url='../files/articulos/';
        $color_r_texto=0;
        $color_g_texto=0;
        $color_b_texto=0;
    
        $color_r=171;
        $color_g=195;
        $color_b=220;
        if ($regv->estado=='Anulado') {
            # code...
            $pdf->Image($anulado,50 ,100, 100 , 25 );
        }
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetXY(30,209);
        $pdf->Image($url.$regv->sucursal_imagen,10 ,3, 40 , 25 );
        $pdf->SetFont('Arial','',8);
        $pdf->SetXY(50,5); 
        $pdf->Multicell(70,4,$regv->sucursal_nombre,0,"C"); 
        $pdf->SetXY(50,10); 
        $pdf->Multicell(70,4,utf8_decode($regv->nombre_fel),0,"C");     
        $pdf->SetXY(50,18); 
        $pdf->Cell(70,4,"Nit: ".$regv->sucursal_nit,0,0,"C");     
        $pdf->SetXY(50,22); 
        $pdf->Multicell(70,4,"Direc: ".$regv->sucursal_direccion,0,"C");  
        $pdf->SetXY(50,30); 
        $pdf->Multicell(70,4,"Tels: ".$regv->sucursal_telefono."  Email: ".$regv->sucursal_email,0,"C");  
            $pdf->Ln(5);
            $pdf->SetX(10);               
            $pdf->Cell(190, 5, "", "B", 0, "C");   
            $pdf->Ln(5);
            $pdf->SetX(10);               
            $pdf->Cell(190, 5, "", "B", 0, "C");                   
        
            $pdf->SetDrawColor(255, 255, 255);
            $pdf->addRectangulo1();  
            $pdf->SetXY(10,30);
            $pdf->Cell(95,4,utf8_decode("User: ".$regv->usuario),0,"L"); 
            $pdf->SetXY(10,39);
            $pdf->Cell(190,4,utf8_decode("Cliente: ".$regv->cliente),0,"L"); 
            $pdf->SetXY(10,45);
            $pdf->Cell(190,4,utf8_decode("Direccion: ".$regv->direccion),0,"L"); 
            $pdf->SetXY(10,50); 
            $pdf->Cell(47.50,4,utf8_decode("Nit: ".$regv->num_documento),0,"L");  
            $pdf->Cell(47.50,4,utf8_decode("Tels: ".$regv->telefono),0,"L");     
            $pdf->Cell(47.50,4,utf8_decode("F/Pago: ".$regv->forma_pago),0,"L");     
            $pdf->Cell(47.50,4,utf8_decode("Email: ".$regv->email),0,"L");    
            $pdf->SetDrawColor(0, 0, 0);  

  
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetXY(10,57); 
        $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
        $pdf->SetFillColor($color_r,$color_g,$color_b);
        $pdf->Cell(190,3.5,".::DATOS DE PRODUCTOS::.",1,0,'C',1); 
        $pdf->SetTextColor(0,0,0); 
        $pdf->SetXY(10,63);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor($color_r,$color_g,$color_b); 
        $pdf->Cell(190,5.3," ",1,0,'C',1); 
        $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
        $pdf->SetFont('Arial','',8);
        //Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
        $pdf->SetDrawColor(255, 255, 255);
        $cols=array( "CANT"=>15,
                        "CODIGO"=>30,
                         "EQUIVALENCIA"=>50, 
                         "ART"=>35,
                         "P.U"=>30,
                            "SUBTOTAL"=>30);
            $pdf->addCols( $cols);
            $cols=array( "CANT"=>"C",
                         "CODIGO"=>"L",
                         "EQUIVALENCIA"=>"C",
                         "ART"=>"R",
                         "P.U"=>"R",
                         "SUBTOTAL"=>"R");
        $pdf->addLineFormat( $cols);
        $pdf->addLineFormat($cols);
        $pdf->SetDrawColor(0, 0, 0);
        // Espacio disponible en la página
        $espacioDisponible = 295; 
        // Altura del footer
        $alturaFooter = 60; 

        $y= 71;


        $pdf->SetTextColor(0,0,0); 
        $cantidad=0;
        // Obtenemos todos los detalles de la venta actual
        $rsptad = $cotizaciones->ventadetalle2($_GET["id"]);
       
        while ($regd = $rsptad->fetch_object())
        {
            setlocale(LC_MONETARY,"en_US");
     
            if ($y + $alturaFooter > $espacioDisponible) {
                // Agrega una nueva página y vuelve a imprimir la cabecera
                $pdf->AddPage();
                $pageCount++;
                imprimirCabecera($pdf, $regv); 
                $y = 75; // Reinicia la posición y
            }
            $equivalencia="";
            if($regd->descripcion_2 == ""){
                $equivalencia=".";
            }else{
                $equivalencia = $regd->descripcion_2;
            }
          $line = array("CANT"=> "$regd->cantidad",
                        "CODIGO"=> utf8_decode("$regd->codigo"),
                         "EQUIVALENCIA"=> "$equivalencia",
                        "ART"=>  "$regd->articulo",
                        "P.U" => number_format($regd->precio_venta, 2, '.', ''),
                        "SUBTOTAL"=> "$regd->subtotal");
                    $size = $pdf->addLine( $y, $line );
                    $y   += $size + 2;
                    $cantidad+=$regd->cantidad;
        }


        require_once"num2letras.php";
        //$V=new EnLetras();  
        //$con_letra=strtoupper($V->ValorEnLetras($regv->total_venta,"QUETZALEZ"));
        $pdf->SetDrawColor(255, 255, 255);
        $conletras=$regv->total_venta; 
        $conletrasresultado=num2letras($conletras);
        $pdf->addCadreTVAs("---".$conletrasresultado,"QUETZALEZ"); 
        $pdf->addTVAs($regv->total_ventades, $regv->total_venta,"Q ");
        $pdf->addCadreEurosFrancs(""." "); 
        $pdf->SetDrawColor(0, 0, 0);
        

        $pdf->SetXY(10,230);  
        $pdf->Cell(10,10,"T/".$cantidad,0);         


        $pdf->SetFont('Arial','B',9);

        $pdf->SetXY(10,247);  
        $pdf->Cell(30,10,"Efectivo:".$regv->cefectivo,0); 
        $pdf->Cell(30,10,"Tarjeta:".$regv->ctarjeta,0); 
        $pdf->Cell(30,10,"Credito:".$regv->ccredito,0); 
        $pdf->Cell(30,10,"Cambio:".$regv->rescambio,0); 
        if ($regv->forma_pago=='Tarjeta') {
                # code...
                    $pdf->SetXY(10,252);  
            $pdf->Cell(40,10,"T/Tarjeta:".$regv->tipo_pagoBacVisaNet,0); 
            $pdf->Cell(40,10,"Pago:".$regv->opcionesAdicionales,0); 
        }


        $pdf->SetFont('Arial','',8);
        $pdf->SetXY(10,255);      
        $pdf->Cell(190,4,utf8_decode("Sujeto a pagos trimestrales ISR"),0,0,"C");   
        $pdf->SetXY(10,260);    
        $pdf->Cell(190,4,utf8_decode($regv->nombrecertificador),0,0,"C");   
        $pdf->Ln(5);           
        $pdf->SetX(10);     
        $pdf->Cell(190,4,utf8_decode($regv->empresadesarrollo),0,0,"C");                      

        $pdf->SetDrawColor(255, 255, 255);
        $pdf->Ln(5);
        $pdf->SetX(10);               
        $pdf->SetTextColor(0,0,0); 
        $pdf->SetFillColor($color_r,$color_g,$color_b);
        $pdf->Cell(190,5,".::ULTIMA LINEA::.",1,0,'C',1); 
        $pdf->SetTextColor(0,0,0); 
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Output('Envio Productos No'.$regv->idventa.".pdf", 'I');
    } else {
        echo 'No tiene permiso para visualizar el reporte';
    }
}
?>
