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
    $id = $_GET['id'];         // Captura el parámetro 'id'
    $idventa = $_GET['idventa']; // Captura el parámetro 'idventa'
        // Establecemos los datos de la empresa
        include 'empresa.php';  

        //Incluímos la clase Venta
        require_once "../modelos/Cotizaciones.php"; 
        $cotizaciones= new Cotizaciones();

        $rsptav = $cotizaciones->ventacabecera2($idventa);  
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
            $pdf->fact_dev( utf8_decode("ENVIO NC NO: ".$regv->num_comprobante),"" );
            $pdf->temporaire( "" ); 
            $pdf->addDate3( ""," "," ","# Interno: ".$regv->idventa,$regv->fecha);
            $pdf->SetXY(110,1); 
            $pdf->Multicell(100,4,utf8_decode("ENVIO NOTA CREDITO"),0,"C"); 
        
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
            $pdf->Image($imagePath,10 ,3, 40 , 25 );
            $pdf->SetFont('Arial','',10);
            $pdf->SetXY(50,5); 
            $pdf->Multicell(70,4,$regv->sucursal_nombre,0,"C"); 
            $pdf->SetXY(50,10); 
            $pdf->Multicell(70,4,utf8_decode($regv->nombre_fel),0,"C");        
            $pdf->SetXY(50,22); 
            $pdf->Multicell(70,4,"Direc: ".$regv->sucursal_direccion,0,"C");  
            $pdf->SetXY(50,30); 
            $pdf->Multicell(70,4,"Tels: ".$regv->sucursal_telefono."  Email: ".$regv->sucursal_email,0,"C");  
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
            $pdf->Cell(190,4,utf8_decode("Cliente: ".$regv->cliente),0,"L"); 
            $pdf->SetXY(10,45);
            $pdf->Cell(95,4,utf8_decode("Direccion: ".$regv->direccion),0,"L");
            $pdf->SetXY(10,50); 
            $pdf->Cell(95,4,utf8_decode("Tels: ".$regv->telefono),0,"L");     $pdf->Cell(95,4,utf8_decode("Email: ".$regv->email),0,"L");     
    
    
            $pdf->SetXY(10,57); 
            $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
            $pdf->SetFillColor($color_r,$color_g,$color_b);
            $pdf->Cell(190,3.5,".::DATOS DE PRODUCTOS::.",1,0,'C',1); 
            $pdf->SetTextColor(0,0,0); 
            $pdf->SetXY(10,63);
            
            $pdf->SetFillColor($color_r,$color_g,$color_b);
            $pdf->Cell(190,5.3," ",1,0,'C',1); 
            $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
            $pdf->SetFont('Arial','',8);
            //Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
            $cols=array( "FECHA.PAGO"=>15,
                         "TV"=>105,
                         "ABONO"=>30,
                         "SALDO.V"=>20,
                         "TIPO.PAGO"=>20,
                        "TIPO.PAGO"=>20,
                        "BANCO"=>20,
                        "NO.BOLETA"=>20,
                        "NO.RECIBO"=>20,
                        "DDESCRIP"=>20,);
            $pdf->addCols( $cols);
            $cols=array( "FECHA.PAGO"=>"C",
                         "TV"=>"L",
                         "ABONO"=>"C",
                         "SALDO.V"=>"R",
                         "TIPO.PAGO"=>"R",
                        "BANCO"=>"R",
                        "NO.BOLETA"=>"R",
                        "NO.RECIBO"=>"R",
                        "DDESCRIP"=>"R");
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

        $pdf->fact_dev( utf8_decode("# COMPROBANTE VENTA: ".$regv->num_comprobante),"" );
        $pdf->temporaire( "" ); 
         $pdf->addDate3( ""," "," ","# Interno ".$regv->idventa,$regv->fecha);
        $pdf->SetXY(110,1); 
        $pdf->Multicell(100,4,utf8_decode("DETALLE DE ABONOS"),0,"C"); 
    
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
        $pdf->Image($url.$regv->sucursal_imagen,10 ,3, 40 , 25 );
        $pdf->SetFont('Arial','',10);
        $pdf->SetXY(50,5); 
        $pdf->Multicell(70,4,$regv->sucursal_nombre,0,"C"); 
        $pdf->SetXY(50,10); 
        $pdf->Multicell(70,4,utf8_decode($regv->nombre_fel),0,"C");       
        $pdf->SetXY(50,22); 
        $pdf->Multicell(70,4,"Direc: ".$regv->sucursal_direccion,0,"C");  
        $pdf->SetXY(50,30); 
        $pdf->Multicell(70,4,"Tels: ".$regv->sucursal_telefono."  Email: ".$regv->sucursal_email,0,"C");  
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
            $pdf->Cell(190,4,utf8_decode("Cliente: ".$regv->cliente),0,"L"); 
            $pdf->SetXY(10,45);
            $pdf->Cell(95,4,utf8_decode("Direccion: ".$regv->direccion),0,"L"); 
            $pdf->SetXY(10,50); 
            $pdf->Cell(95,4,utf8_decode("Tels: ".$regv->telefono),0,"L");     $pdf->Cell(95,4,utf8_decode("Email: ".$regv->email),0,"L");      

  
        $pdf->SetXY(10,57); 
        $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
        $pdf->SetFillColor($color_r,$color_g,$color_b);
        $pdf->Cell(190,3.5,".::DATOS DE PRODUCTOS::.",1,0,'C',1); 
        $pdf->SetTextColor(0,0,0); 
        $pdf->SetXY(10,63);
        
        $pdf->SetFillColor($color_r,$color_g,$color_b); 
        $pdf->Cell(190,5.3," ",1,0,'C',1); 
        $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
        $pdf->SetFont('Arial','',8);
        //Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
            $cols=array( "FECHA.PAGO"=>20,
                         "TV"=>20,
                         "ABONO"=>20,
                         "SALDO.V"=>20,
                         "TIPO.PAGO"=>20,
                        "TIPO.PAGO"=>20,
                        "BANCO"=>20,
                        "NO.BOLETA"=>20,
                        "NO.RECIBO"=>20,
                        "DDESCRIP"=>30,);
            $pdf->addCols( $cols);
            $cols=array( "FECHA.PAGO"=>"C",
                         "TV"=>"R",
                         "ABONO"=>"R",
                         "SALDO.V"=>"R",
                         "TIPO.PAGO"=>"C",
                        "BANCO"=>"C",
                        "NO.BOLETA"=>"R",
                        "NO.RECIBO"=>"R",
                        "DDESCRIP"=>"C");
        $pdf->addLineFormat( $cols);
        $pdf->addLineFormat($cols);
        // Espacio disponible en la página
        $espacioDisponible = 295; 
        // Altura del footer
        $alturaFooter = 60; 

        $y= 71;


    $pdf->SetTextColor(0,0,0); 
     $cantidad=0;
        // Obtenemos todos los detalles de la venta actual
        $rsptad = $cotizaciones->detalle_abonosCtasxcobrar($id);
       
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
          $line = array("FECHA.PAGO"=> "$regd->fechapago",
                        "TV"=> "$regd->total_venta",
                        "ABONO"=> "$regd->total_abono",
                        "SALDO.V"=> "$regd->saldo_venta",
                        "TIPO.PAGO"=> "$regd->tipo_pago",
                        "BANCO"=> "$regd->tipo_banco",
                        "NO.BOLETA"=> "$regd->numero_boleta",
                        "NO.RECIBO"=> "$regd->recibo_caja_numero",
                        "DDESCRIP"=> utf8_decode("$regd->descripcion"));
                    $size = $pdf->addLine( $y, $line );
                    $y   += $size + 2;

                    
        }


        require_once"num2letras.php";
        //$V=new EnLetras();  
        //$con_letra=strtoupper($V->ValorEnLetras($regv->total_venta,"QUETZALEZ"));
        $conletras=$regv->total_venta; 
        $conletrasresultado=num2letras($conletras);
        $pdf->addCadreTVAs("---".$conletrasresultado,"QUETZALEZ"); 
        $pdf->addTVAs( "", $regv->total_venta,"Q ");
        $pdf->addCadreEurosFrancs(""." "); 
        

        $pdf->SetXY(10,230);  
        $pdf->Cell(10,10,"T/".$cantidad,0);         

        $pdf->SetXY(10,255); 
 
        $pdf->SetFont('Arial','',6);


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
        $pdf->Output('ABONO No'.$regv->idventa.".pdf", 'I');
    } else {
        echo 'No tiene permiso para visualizar el reporte';
    }
}
?>
