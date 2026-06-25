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
            require_once "../modelos/Cuadres_caja_cierre.php";  
            $cuadreinicio= new CuadreInicio();

            $rsptav = $cuadreinicio->cuadrecajacabeceracierre($_GET["id"]);  
            //Recorremos todos los valores obtenidos
            $regv = $rsptav->fetch_object();
         

        // Establecemos la configuración de la factura
        $pdf = new PDF_Invoice('P', 'mm', 'A4');
        $pageCount = 0;
        $pdf->AddPage();
        $pageCount++;

        // Función para imprimir la cabecera
        function imprimirCabecera($pdf, $regv) {

/////PARTE DE ABAJO            
            $pdf->fact_dev( utf8_decode("CIERRE CAJA NO: ".$regv->idcuadre_caja),"" );
            $pdf->temporaire( "" ); 
            $pdf->addDate3( ""," "," ","# Interno: ".$regv->idcuadre_caja,$regv->fecha_hora_inicio);
            $pdf->SetXY(110,1); 
            $pdf->Multicell(100,4,utf8_decode("CIERRE DE CAJA"),0,"C"); 
        
        //$pdf->Rect(10,43, 100, 4, 'F'); //Rectángulo relleno y con liena
            $url='../files/articulos/';
            $color_r_texto=0;
            $color_g_texto=0;
            $color_b_texto=0;
        
            $color_r=171;
            $color_g=195;
            $color_b=220;

            // Asumiendo que $regv->sucursal_imagen es la imagen obtenida
            $imagePath = $url . $regv->sucursal_imagen;

            // Validar si la imagen está vacía o es igual a "0"
            if (empty($regv->sucursal_imagen) || $regv->sucursal_imagen == '0') {
                // Usar una ruta alternativa
                $imagePath = $url . '1590204245.jpg';
            }

            $pdf->SetXY(30,209);
            $pdf->Image($imagePath,10 ,3, 40 , 25 );
            $pdf->SetFont('Arial','',10);
            $pdf->SetXY(50,5); 
            $pdf->Multicell(70,4,utf8_decode($regv->sucursal_nombre),0,"C"); 
            $pdf->SetXY(50,15); 
            $pdf->Multicell(70,4,utf8_decode($regv->nombre_fel),0,"C");       
            $pdf->SetXY(50,22); 
            $pdf->Multicell(70,4,"Direc: ".$regv->sucursal_direccion,0,"C");  
            $pdf->SetXY(10,34); 
            $pdf->Multicell(100,4,"Tels: ".$regv->sucursal_telefono."  Email: ".$regv->sucursal_email,0,"C"); 
            $pdf->Ln(1);
            $pdf->SetX(10);               
            $pdf->Cell(190, 5, "", "B", 0, "C");   
            $pdf->Ln(5);
            $pdf->SetX(10);               
            $pdf->Cell(190, 5, "", "B", 0, "C");                   
         
            $pdf->addRectangulo1();   
            $pdf->SetXY(125,34);
            $pdf->Cell(95,4,utf8_decode("Usuario Creacion: ".$regv->usuario),0,"L"); 
            $pdf->SetXY(10,39);
            $pdf->Cell(95,4,utf8_decode("EFEC. INGRESADO: ".number_format($regv->total_efectivo, 2, '.', ',')),0,"C"); 
            $pdf->Cell(95,4,utf8_decode("EFEC. APERTURA:: ".number_format($regv->total_efectivo_inicio, 2, '.', ',')),0,"C"); 
            $pdf->SetXY(10,44);
            $pdf->Cell(47,4,utf8_decode("EFECTIVO: ".number_format($regv->total_ventas_diarias_efectivo, 2, '.', ',')),0,"L"); 
            $pdf->Cell(47,4,utf8_decode("CREDITO: ".number_format($regv->total_ventas_diarias_credito, 2, '.', ',')),0,"L"); 
            $pdf->Cell(47,4,utf8_decode("TARJETA: ".number_format($regv->total_ventas_diarias_tarjeta, 2, '.', ',')),0,"L"); 
            $pdf->Cell(47,4,utf8_decode("TRANSFERENCIA: ".number_format($regv->total_ventas_diarias_transferencia, 2, '.', ',')),0,"L"); 
            $pdf->SetXY(10,49);
            $pdf->Cell(47,4,utf8_decode("ABONOS CTA: ".number_format($regv->total_ventas_AbonosVentas, 2, '.', ',')),0,"L"); 
            $pdf->Cell(47,4,utf8_decode("GASTOS: ".number_format($regv->total_ventas_gastosEfectivo, 2, '.', ',')),0,"L"); 
            $pdf->Cell(47,4,utf8_decode("NC: ".number_format($regv->total_ventas_NCVentas, 2, '.', ',')),0,"L"); 
            $pdf->Cell(47,4,utf8_decode("S/F: ".number_format($regv->total_efectivo_cierre_operaciones, 2, '.', ',')),0,"L"); 
    
    
            $pdf->SetXY(10,57); 
            $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
            $pdf->SetFillColor($color_r,$color_g,$color_b);
            $pdf->Cell(190,3.5,".::INTEGRACION DE VENTAS X TIPO DOCUMENTO::.",1,0,'C',1); 
            $pdf->SetTextColor(0,0,0); 
            $pdf->SetXY(10,63);
            
            $pdf->SetFillColor($color_r,$color_g,$color_b);
            $pdf->Cell(190,5.3," ",1,0,'C',1); 
            $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
            $pdf->SetFont('Arial','',7.5);
            //Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
        $cols=array("NO."=>10, 
                    "FECHA"=>18,
                    "CLIE"=>53,
                    "T.V."=>20,
                    "ESTADO"=>18,
                    "F.PAGO"=>20,
                    "T/COMPRO"=>15,
                    "SERIE"=>18,
                    "DTE"=>18);
            $pdf->addCols( $cols);
            $cols=array("NO."=>"R", 
                        "FECHA"=>"C",
                        "CLIE"=>"L",
                        "T.V."=>"R",
                        "ESTADO"=>"C",
                        "F.PAGO"=>"C",
                        "T/COMPRO"=>"C",
                        "SERIE"=>"R",
                        "DTE"=>"R");
        $pdf->addLineFormat( $cols);
            $pdf->addLineFormat($cols);
        }

        // Función para imprimir el footer
        function imprimirFooter($pdf, $regv) {
            require_once"num2letras.php";
            //$V=new EnLetras();  
            //$con_letra=strtoupper($V->ValorEnLetras($regv->total_venta,"QUETZALEZ"));
            $conletras=$regv->total_venta; 
            $conletrasresultado=num2letras($conletras);
            $pdf->addCadreTVAs("---".$conletrasresultado,"QUETZALEZ"); 
            $pdf->addTVAs( $regv->impuesto, $regv->total_venta,"Q ");
            $pdf->addCadreEurosFrancs(""." "); 
            

            $pdf->SetXY(10,250);     
            $pdf->Cell(190,4,utf8_decode("Le atendio: ".$regv->usuarioNc),0,0,"C");  


            $pdf->SetFont('Arial','',8);
            $pdf->Ln(2);
            $pdf->SetX(10);     
            $pdf->Cell(95,10,"Firma Salida: ___________________________",0); 
            $pdf->Cell(95,10,"Firma Recibido: ___________________________",0);     

            $pdf->Ln(8);
            $pdf->SetX(10);     
            $pdf->Cell(190,4,utf8_decode("Desarrollado por www.compusisgt.comm / Email: info@compusisgt.com / +502 2293-4153 / WhatsApp: +502 5622-2080 "),0,0,"C");       
            $pdf->Ln(5);
            $pdf->SetX(10);               
            $pdf->SetTextColor(0,0,0); 
            $pdf->SetFillColor($color_r,$color_g,$color_b);
            $pdf->Cell(190,5,".::ULTIMA LINEA::.",1,0,'C',1); 
            $pdf->SetTextColor(0,0,0); 
        }

/////PARTE DE ARRIBA        

            $pdf->fact_dev( utf8_decode("CIERRE CAJA NO: ".$regv->idcuadre_caja),"" );
            $pdf->temporaire( "" ); 
            $pdf->addDate3( ""," "," ","# Interno: ".$regv->idcuadre_caja,$regv->fecha_hora_inicio);
            $pdf->SetXY(110,1); 
            $pdf->Multicell(100,4,utf8_decode("CIERRE DE CAJA"),0,"C"); 
    
    //$pdf->Rect(10,43, 100, 4, 'F'); //Rectángulo relleno y con liena
        $url='../files/articulos/';
        $color_r_texto=0;
        $color_g_texto=0;
        $color_b_texto=0;
    
        $color_r=171;
        $color_g=195;
        $color_b=220;

        $pdf->SetXY(30,209);
        $pdf->Image($url.$regv->sucursal_imagen,10 ,3, 40 , 25 );
        $pdf->SetFont('Arial','',10);
        $pdf->SetXY(50,5); 
        $pdf->Multicell(70,4,utf8_decode($regv->sucursal_nombre),0,"C"); 
        $pdf->SetXY(50,15); 
        $pdf->Multicell(70,4,utf8_decode($regv->nombre_fel),0,"C");       
        $pdf->SetXY(50,22); 
        $pdf->Multicell(70,4,"Direc: ".$regv->sucursal_direccion,0,"C");  
        $pdf->SetXY(10,30); 
        $pdf->Multicell(100,4,"Tels: ".$regv->sucursal_telefono."  Email: ".$regv->sucursal_email,0,"C");  
            $pdf->Ln(1);
            $pdf->SetX(10);               
            $pdf->Cell(190, 5, "", "B", 0, "C");   
            $pdf->Ln(5);
            $pdf->SetX(10);               
            $pdf->Cell(190, 5, "", "B", 0, "C");                   
        
            $pdf->addRectangulo1();  
            $pdf->SetXY(125,34);
            $pdf->Cell(95,4,utf8_decode("Usuario Creacion: ".$regv->usuario),0,"L"); 
            $pdf->SetXY(10,39);
            $pdf->Cell(95,4,utf8_decode("EFEC. INGRESADO: ".number_format($regv->total_efectivo, 2, '.', ',')),0,"C"); 
            $pdf->Cell(95,4,utf8_decode("EFEC. APERTURA: ".number_format($regv->total_efectivo_inicio, 2, '.', ',')),0,"C"); 
            $pdf->SetXY(10,44);
            $pdf->Cell(47,4,utf8_decode("EFECTIVO: ".number_format($regv->total_ventas_diarias_efectivo, 2, '.', ',')),0,"L"); 
            $pdf->Cell(47,4,utf8_decode("CREDITO: ".number_format($regv->total_ventas_diarias_credito, 2, '.', ',')),0,"L"); 
            $pdf->Cell(47,4,utf8_decode("TARJETA: ".number_format($regv->total_ventas_diarias_tarjeta, 2, '.', ',')),0,"L"); 
            $pdf->Cell(47,4,utf8_decode("TRANSFERENCIA: ".number_format($regv->total_ventas_diarias_transferencia, 2, '.', ',')),0,"L"); 
            $pdf->SetXY(10,49);
            $pdf->Cell(47,4,utf8_decode("ABONOS CTA: ".number_format($regv->total_ventas_AbonosVentas, 2, '.', ',')),0,"L"); 
            $pdf->Cell(47,4,utf8_decode("GASTOS: ".number_format($regv->total_ventas_gastosEfectivo, 2, '.', ',')),0,"L"); 
            $pdf->Cell(47,4,utf8_decode("NC: ".number_format($regv->total_ventas_NCVentas, 2, '.', ',')),0,"L"); 
            $pdf->Cell(47,4,utf8_decode("S/F: ".number_format($regv->total_efectivo_cierre_operaciones, 2, '.', ',')),0,"L"); 


  
        $pdf->SetXY(10,57); 
        $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
        $pdf->SetFillColor($color_r,$color_g,$color_b);
        $pdf->Cell(190,3.5,".::INTEGRACION DE VENTAS X TIPO DOCUMENTO::.",1,0,'C',1); 
        $pdf->SetTextColor(0,0,0); 
        $pdf->SetXY(10,63);
        
        $pdf->SetFillColor($color_r,$color_g,$color_b); 
        $pdf->Cell(190,5.3," ",1,0,'C',1); 
        $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
        $pdf->SetFont('Arial','',7.5);
        //Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
        $cols=array("NO."=>10, 
                    "FECHA"=>18,
                    "CLIE"=>53,
                    "T.V."=>20,
                    "ESTADO"=>18,
                    "F.PAGO"=>20,
                    "T/COMPRO"=>15,
                    "SERIE"=>18,
                    "DTE"=>18);
            $pdf->addCols( $cols);
            $cols=array("NO."=>"R", 
                        "FECHA"=>"C",
                        "CLIE"=>"L",
                        "T.V."=>"R",
                        "ESTADO"=>"C",
                        "F.PAGO"=>"C",
                        "T/COMPRO"=>"C",
                        "SERIE"=>"R",
                        "DTE"=>"R");
        $pdf->addLineFormat( $cols);
        $pdf->addLineFormat($cols);
        // Espacio disponible en la página
        $espacioDisponible = 295; 
        // Altura del footer
        $alturaFooter = 60; 

        $y= 71;


    $pdf->SetTextColor(0,0,0); 

        // Obtenemos todos los detalles de la venta actual
    require_once "../modelos/Venta.php";  
    $venta= new Venta();    
    $rsptad = $venta->listarVentasCierre($_GET["id"]);     
       
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
          $line = array("NO."=> "$regd->idventa",
                        "FECHA"=> "$regd->fecha",
                        "CLIE"=> utf8_decode("$regd->cliente"),
                         "T.V."=> "$regd->total_venta",
                        "ESTADO"=>  "$regd->estado",
                        "F.PAGO"=> "$regd->forma_pago",
                        "T/COMPRO"=> "$regd->tipo_comprobante",
                        "SERIE"=> "$regd->serie_ecoFactura",
                        "DTE"=> "$regd->numero_ecoFactura");
                    $size = $pdf->addLine( $y, $line );
                    $y   += $size + 2;

                    
        }




        $pdf->SetFont('Arial','',8);
        $pdf->SetXY(10,255);  
        $pdf->SetX(15);     
        $pdf->Cell(95,10,"Firma Salida: ___________________________",0); 
        $pdf->Cell(95,10,"Firma Recibido: ___________________________",0);     

        $pdf->Ln(8);
        $pdf->SetX(10);     
        $pdf->Cell(190,4,utf8_decode("Desarrollado por www.compusisgt.comm / Email: info@compusisgt.com / +502 2293-4153 / WhatsApp: +502 5622-2080 "),0,0,"C");              

        $pdf->Ln(5);
        $pdf->SetX(10);               
        $pdf->SetTextColor(0,0,0); 
        $pdf->SetFillColor($color_r,$color_g,$color_b);
        $pdf->Cell(190,5,".::ULTIMA LINEA::.",1,0,'C',1); 
        $pdf->SetTextColor(0,0,0); 
        $pdf->Output('Cierre No'.$regv->idcuadre_caja.".pdf", 'I');
    } else {
        echo 'No tiene permiso para visualizar el reporte';
    }
}
?>
