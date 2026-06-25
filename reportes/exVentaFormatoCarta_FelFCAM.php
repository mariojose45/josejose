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

    // Función para imprimir la cabecera
        function imprimirCabecera($pdf, $regv) {

/////PARTE DE ABAJO            
            $pdf->fact_dev( utf8_decode("FACTURA CAMBIARIA "),"" );
            $pdf->temporaire( "" ); 
            $pdf->addDate($regv->serie_ecoFactura,$regv->numero_ecoFactura,$regv->fechaCertificacion_ecoFactura,$regv->autorizacionEcoFactura,$regv->fecha);
            $pdf->SetXY(110,1); 
            $pdf->Multicell(100,4,utf8_decode("DOCUMENTO TRIBURARIO ELECTRONICO"),0,"C"); 

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

            $pdf->SetXY(30,209);
            $pdf->Image($url.$regv->sucursal_imagen,10 ,3, 30 , 15 );
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(10,18); 
            $pdf->Multicell(190,4,utf8_decode($regv->nombre_comercial),0,"L"); 
            $pdf->SetXY(10,22); 
            $pdf->Multicell(70,4,utf8_decode($regv->nombre_fel),0,"L");     
            $pdf->SetXY(50,22); 
            $pdf->Cell(190,4,"Nit: ".$regv->sucursal_nit,0,0,"L");     
            $pdf->SetXY(10,26); 
            $pdf->Multicell(120,4,"Direc: ".$regv->sucursal_direccion,0,"L");  
            $pdf->SetXY(10,30); 
            $pdf->Multicell(110,4,"Tels: ".$regv->sucursal_telefono."  Email: ".$regv->sucursal_email,0,"L");  
            $pdf->Ln(1);
            $pdf->SetX(10);               
            $pdf->Cell(190, 5, "", "B", 0, "C");   
            $pdf->Ln(5);
            $pdf->SetX(10);               
            $pdf->Cell(190, 5, "", "B", 0, "C");                   

            $pdf->addRectangulo1();  
            $pdf->SetXY(10,39);
            $pdf->Cell(190,4,utf8_decode("Cliente: ".$regv->cliente),0,"L"); 
            $pdf->SetXY(10,45);
            $pdf->Cell(190,4,utf8_decode("Direccion: ".$regv->direccion),0,"L");
            $pdf->SetXY(10,50); 
            $pdf->Cell(47.50,4,utf8_decode("Nit: ".$regv->num_documento),0,"L");  
            $pdf->Cell(47.50,4,utf8_decode("Tels: ".$regv->telefono),0,"L");     
            $pdf->Cell(47.50,4,utf8_decode("F/Pago: ".$regv->forma_pago),0,"L");     
            $pdf->Cell(47.50,4,utf8_decode("Email: ".$regv->email),0,"L");      

            $pdf->SetXY(10,57);
            $pdf->SetFont('Arial', '', 6);
            $textoNuevo = "Por medio de esta única factura cambiaria se servirá usted pagar a orden o endoso de $regv->nombre_fel, la suma de acuerdo a las condiciones que se establecen en el presente título, en concepto de servicios y mercadería que acepta haber recibido a entera satisfacción conforme al detalle siguiente.";
            $pdf->MultiCell(190, 3, utf8_decode($textoNuevo), 0, 'L');

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetXY(10,64); 
            $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
            $pdf->SetFillColor($color_r,$color_g,$color_b);
            $pdf->Cell(190,3.5,".::DATOS DE PRODUCTOS::.",1,0,'C',1); 
            $pdf->SetTextColor(0,0,0); 
            $pdf->SetXY(10,70);

            $pdf->SetFillColor($color_r,$color_g,$color_b);
            $pdf->Cell(190,5.3," ",1,0,'C',1); 
            $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
            $pdf->SetFont('Arial','',8);
        //Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta

            $cols=array( "CANT"=>15,
               "DESCRIPCION"=>105,
               "CODIGO"=>30,
               "P.U."=>20,
               "SUBTOTAL"=>20);
            $pdf->addCols_faccambiaria( $cols);
            $cols=array( "CANT"=>"C",
               "DESCRIPCION"=>"L",
               "CODIGO"=>"C",
               "P.U."=>"R",
               "SUBTOTAL"=>"R");
            $pdf->addLineFormat( $cols);
            $pdf->addLineFormat($cols);

            // ** CABECERA DE LA FACTURA ** //

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
        $pdf->fact_dev( utf8_decode("FACTURA CAMBIARIA "),"" );
        $pdf->temporaire( "" ); 
        $pdf->addDate($regv->serie_ecoFactura,$regv->numero_ecoFactura,$regv->fechaCertificacion_ecoFactura,$regv->autorizacionEcoFactura,$regv->fecha);
        $pdf->SetXY(110,1); 
        $pdf->Multicell(100,4,utf8_decode("DOCUMENTO TRIBURARIO ELECTRONICO"),0,"C"); 

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
        $pdf->SetXY(30,209);
        $pdf->Image($url.$regv->sucursal_imagen,10 ,3, 30 , 15 );
        $pdf->SetFont('Arial','',8);
        $pdf->SetXY(10,18); 
        $pdf->Multicell(190,4,utf8_decode($regv->nombre_comercial),0,"L"); 
        $pdf->SetXY(10,22); 
        $pdf->Multicell(70,4,utf8_decode($regv->nombre_fel),0,"L");     
        $pdf->SetXY(50,22); 
        $pdf->Cell(190,4,"Nit: ".$regv->sucursal_nit,0,0,"L");     
        $pdf->SetXY(10,26); 
        $pdf->Multicell(120,4,"Direc: ".$regv->sucursal_direccion,0,"L");  
        $pdf->SetXY(10,30); 
        $pdf->Multicell(110,4,"Tels: ".$regv->sucursal_telefono."  Email: ".$regv->sucursal_email,0,"L");  
        $pdf->Ln(1);
        $pdf->SetX(10);               
        $pdf->Cell(190, 5, "", "B", 0, "C");   
        $pdf->Ln(5);
        $pdf->SetX(10);               
        $pdf->Cell(190, 5, "", "B", 0, "C");                    

        $pdf->addRectangulo1();  
        $pdf->SetXY(10,39);
        $pdf->Cell(190,4,utf8_decode("Cliente: ".$regv->cliente),0,"L"); 
        $pdf->SetXY(10,45);
        $pdf->Cell(190,4,utf8_decode("Direccion: ".$regv->direccion),0,"L"); 
        $pdf->SetXY(10,50); 
        $pdf->Cell(47.50,4,utf8_decode("Nit: ".$regv->num_documento),0,"L");  
        $pdf->Cell(47.50,4,utf8_decode("Tels: ".$regv->telefono),0,"L");     
        $pdf->Cell(47.50,4,utf8_decode("F/Pago: ".$regv->forma_pago),0,"L");     
        $pdf->Cell(47.50,4,utf8_decode("Email: ".$regv->email),0,"L");      

        /*--------------- NUEVAS LEYENDAS PARA FACTURA CAMBIARA  ---------------*/
        $pdf->SetXY(10,55);
        $pdf->SetFont('Arial', '', 6);
        $textoNuevo = "Por medio de esta única factura cambiaria se servirá usted pagar a orden o endoso de $regv->nombre_fel, la suma de acuerdo a las condiciones que se establecen en el presente título, en concepto de servicios y mercadería que acepta haber recibido a entera satisfacción conforme al detalle siguiente.";
        $pdf->MultiCell(190, 3, utf8_decode($textoNuevo), 0, 'L');

        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(10,63); 
        $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
        $pdf->SetFillColor($color_r,$color_g,$color_b);
        $pdf->Cell(190,3.5,".::DATOS DE PRODUCTOS::.",1,0,'C',1); 
        $pdf->SetTextColor(0,0,0); 
        $pdf->SetXY(10,70);

        $pdf->SetFillColor($color_r,$color_g,$color_b); 
        $pdf->Cell(190,5.3," ",1,0,'C',1); 
        $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
        $pdf->SetFont('Arial','',8);
    //Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta

        $cols=array( "CANT"=>15,
           "DESCRIPCION"=>105,
           "CODIGO"=>30,
           "P.U."=>20,
           "SUBTOTAL"=>20);
        $pdf->addCols_faccambiaria( $cols);
        $cols=array( "CANT"=>"C",
           "DESCRIPCION"=>"L",
           "CODIGO"=>"C",
           "P.U."=>"R",
           "SUBTOTAL"=>"R");
        $pdf->addLineFormat( $cols);
        $pdf->addLineFormat($cols);

        /**------------------------ NUEVAS LEYENDAS PARA FACTURA CAMBIARIA  ------------------------*/
            // PRIMERA FILA


        $pdf->SetXY(10, 185);
        $pdf->Ln();

            // SEGUNDA FILA
        $pdf->SetXY(10, $pdf->GetY());
        $pdf->SetXY(10, $pdf->GetY());
        $pdf->SetFont('Arial', '', 6);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY()); // Ajusta las coordenadas según sea necesario
        $pdf->Ln(2);           
        $pdf->SetX(10);
        $pdf->MultiCell(190, 2.5, utf8_decode("a) AL CANCELAR ESTA FACTURA SIRVASE EXIGIR SU RECIBO COMO UNICO COMPROBANTE DE PAGO.\n b) POR CADA CHEQUE RECHAZADO SE COBRARA Q. 150.00.\n c) ESTA FACTURA ES EXIGIBLE A SU VENCIMIENTO Y CAUSA INTERRES DE MORA AL 3% MENSUAL.\n d) EL COMPRADOR DA COMO CORRECTO EL VALOR TOTAL DE ESTA FACTURA CAMBIARIA LIBRE DE PROTESTO Y SE COMPROMETE A CANCELAR AL VENCIMIENTO AL
    VENDEDOR O ATRAVES DE UNA ENTIDAD BANCARIA QUE ESTE NOMBRE, EN CASO DE INCUMPLIMIENTO EL COMPRADOR RENUNCIA AL FUERO DE SU DOMICILIO Y SE
    SOMETE A LOS TRIBUNALES DEL DEPARTAMENTO DE GUATEMALA, SEÑALANDO PARA RECIBIR Y NOTIFICACIONES, CITACIONES O EMPLAZAMIENTO LA DIRECCION ACTUAL
    DE SU NEGOCIO, SALVO AVISO DE CAMBIO QUE DIERE POR ESCRITO."), 0, 'L');
    $pdf->Ln(2);           
    $pdf->SetX(10); 
    // Dibujar una línea horizontal
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY()); // Ajusta las coordenadas según sea necesario
        $pdf->Ln(2);           
        $pdf->SetX(10); 
        $pdf->SetFont('Arial', '', 5.6);
        $pdf->MultiCell(190, 1.9, utf8_decode("A:  $regv->fechahoravencimientofactura (EN LAS CONDICIONES ESTIPULADAS EN ESTETITULO) SE SERVIRAN USTEDES PAGAR POR ESTA UNICA FACTURA CAMBIARIA GIRADA LIBRE DE PROTESTO A LA ORDEN O ENDOSO DE $regv->nombre_fel EN EL VALOR
        TOTAL POR EL QUE FUE EXTENDIDA O POR ELULTIMO SALDO INSOLUTO QUE APAREZCA, VALOR RECIBIDO QUE ASENTARAN USTEDES SEGUN NUESTRO AVISO."), 0, 'L');
        $pdf->Ln(2);           
        $pdf->SetX(10); 

            // TERCERA FILA
        $contenidoColumna_fila_3 =  "FIRMA Y SELLO DE ACEPTANTE Y/O REPRESENTANTE APARENTE RECIBI CONFORME LA MERCADERIA";
        $pdf->SetXY(10, $pdf->GetY()); 
        $pdf->Cell(63.33, 15, "LIBRADOR", 1); 
        $pdf->Cell(63.33, 15, "LUGAR Y FECHA ACEPTACION", 1);
        $pdf->SetFont('Arial', '', 5);
        $pdf->MultiCell(63.33, 7.5, utf8_decode($contenidoColumna_fila_3), 1, 'L');
        $pdf->Ln(); 
 $pdf->SetFont('Arial','B',9);
    $pdf->SetXY(10,180);      
        $pdf->Cell(190,4,utf8_decode($regv->dato_sat),0,0,"C");         

    $pdf->SetFont('Arial','',10);
    // Espacio disponible en la página
        $espacioDisponible = 235; 
    // Altura del footer
        $alturaFooter = 60; 

        $y= 80;


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
            $y = 80; // Reinicia la posición y
        }
        $line = array("CANT"=> "$regd->cantidad",
            "DESCRIPCION"=> utf8_decode("$regd->articulo $regd->descripcion_detalle "),
            "CODIGO"=> "$regd->codigo",
            "P.U."=>  "$regd->q_ref",
            "SUBTOTAL"=> "$regd->subtotal");
        $size = $pdf->addLine( $y, $line );
        $y   += $size + 2;

        $cantidad+=$regd->cantidad;

    }


    require_once"num2letras.php";
    //$V=new EnLetras();  
    //$con_letra=strtoupper($V->ValorEnLetras($regv->total_venta,"QUETZALEZ"));
    $conletras=$regv->total_venta; 
    $conletrasresultado=num2letras($conletras);
    $pdf->addCadreTVAs("---".$conletrasresultado,"QUETZALEZ"); 
    $pdf->addTVAs($regv->total_ventades, $regv->total_venta,"Q ");
    $pdf->addCadreEurosFrancs(""." "); 
    

    $pdf->SetXY(10,175);  
    $pdf->Cell(10,10,"T/".$cantidad,0);         

    /**------------------------ NUEVAS LEYENDAS PARA FACTURA CAMBIARIA  ------------------------*/
        // PRIMERA FILA


    $pdf->SetXY(10, 200);
    $pdf->Ln();

        // SEGUNDA FILA
    $pdf->SetXY(10, $pdf->GetY());
    $pdf->SetXY(10, $pdf->GetY());
    $pdf->SetFont('Arial', '', 6);

    $pdf->Ln(2);           
    $pdf->SetX(10);
    $pdf->MultiCell(190, 2.5, utf8_decode(""), 0, 'L');
$pdf->Ln(2);           
$pdf->SetX(10); 
// Dibujar una línea horizontal
    $pdf->Ln(2);           
    $pdf->SetX(10); 
    $pdf->SetFont('Arial', '', 5.6);
    $pdf->MultiCell(190, 1.9, utf8_decode(""), 0, 'L');
    $pdf->Ln(2);           
    $pdf->SetX(10); 


    $pdf->Ln();
 $pdf->SetFont('Arial','B',9);
    $pdf->SetXY(10,180);      
        $pdf->Cell(190,4,utf8_decode($regv->dato_sat),0,0,"C"); 

    $pdf->SetFont('Arial','B',9);

    $pdf->SetXY(10,247);  
    $pdf->Cell(30,10,"Efectivo:".$regv->cefectivo,0); 
    $pdf->Cell(30,10,"Tarjeta:".$regv->ctarjeta,0); 
    $pdf->Cell(30,10,"Credito:".$regv->ccredito,0); 
    $pdf->Cell(30,10,"Cambio:".$regv->rescambio,0); 

    $pdf->SetXY(10,251);  
    $pdf->Cell(30,10,"# Abono:".$regv->numero_pagos,0); 
    $pdf->Cell(60,10,"Fecha Vencimiento:".$regv->fechahoravencimientofactura,0); 
    $pdf->Cell(30,10,"Monto:".$regv->monto_abono,0);     


    if ($regv->forma_pago=='Tarjeta') {
            # code...
        $pdf->SetXY(10,252);  
        $pdf->Cell(40,10,"T/Tarjeta:".$regv->tipo_pagoBacVisaNet,0); 
        $pdf->Cell(40,10,"Pago:".$regv->opcionesAdicionales,0); 
    }


    $pdf->SetFont('Arial','',8);
    $pdf->SetXY(10,260);  
    $pdf->SetX(10);     
    $pdf->Cell(190,4,utf8_decode($regv->nombrecertificador),0,0,"C");   
    $pdf->Ln(5);           
    $pdf->SetX(10);     
    $pdf->Cell(190,4,utf8_decode($regv->empresadesarrollo),0,0,"C");                      

    $pdf->Ln(5);
    $pdf->SetX(10);               
    $pdf->SetTextColor(0,0,0); 
    $pdf->SetFillColor($color_r,$color_g,$color_b);
    $pdf->Cell(190,5,".::ULTIMA LINEA::.",1,0,'C',1); 
    $pdf->SetTextColor(0,0,0); 
    $pdf->Output('Fac Cam DTE'.$regv->numero_ecoFactura.".pdf", 'I');
} else {
    echo 'No tiene permiso para visualizar el reporte';
}
}
?>
