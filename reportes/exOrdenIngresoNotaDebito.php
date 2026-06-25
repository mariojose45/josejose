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

        // Incluimos la clase Venta
        require_once "../modelos/Cotizaciones.php"; 
        $cotizaciones= new Cotizaciones();

        $rsptav = $cotizaciones->cabeceraNotaDebito($_GET["id"]); 
        //Recorremos todos los valores obtenidos
        $regv = $rsptav->fetch_object();


        // Establecemos la configuración de la factura
        $pdf = new PDF_Invoice('P', 'mm', 'A4');
        $pageCount = 0;
        $pdf->AddPage();
        $pageCount++;  

        // Función para imprimir la cabecera
        function imprimirCabecera($pdf, $regv) {
            $pdf->fact_dev( utf8_decode("INGRESO NO: ".$regv->idnota_debito),"" );
            $pdf->temporaire( "" ); 
            $pdf->addDate2( ""," "," "," ",$regv->fechaIngreso);
            $pdf->SetXY(110,1); 
            $pdf->Multicell(100,4,utf8_decode("NC PRODUCTOS"),0,"C"); 

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
            $pdf->SetXY(50,21); 
            $pdf->Cell(70,4,"Nit: ".$regv->sucursal_nit,0,0,"C");     
            $pdf->SetXY(50,25); 
            $pdf->Multicell(70,4,"Direc: ".$regv->sucursal_direccion,0,"C");  
            $pdf->SetXY(10,34); 
            $pdf->Multicell(120,4,"Tels: ".$regv->sucursal_telefono."  Email: ".$regv->sucursal_email,0,"L");  
            $pdf->Ln(1);
            $pdf->SetX(10);               
            $pdf->Cell(190, 5, "", "B", 0, "C");   
            $pdf->Ln(5);
            $pdf->SetX(10);               
            $pdf->Cell(190, 5, "", "B", 0, "C");                   

            $pdf->addRectangulo1();  
            $pdf->SetXY(125,34);
            $pdf->Cell(95,4,utf8_decode("Usuario Creacion: ".$regv->usuarioND),0,"L"); 
            $pdf->SetXY(10,39); 
            $pdf->Cell(190,4,utf8_decode("Proveedor: ".$regv->proveedor." CodProveedor: ".$regv->codigo_cliente),0,"L"); 
            $pdf->SetXY(10,45);
            $pdf->Cell(190,4,utf8_decode("Direccion: ".$regv->direccion),0,"L"); 
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
            $cols=array( "CANT"=>15,
               "DESCRIPCION"=>110,
               "CODIGO"=>25,
               "P.C."=>20,
               "SUBTOTAL"=>20);
            $pdf->addCols( $cols);
            $cols=array( "CANT"=>"C",
               "DESCRIPCION"=>"L",
               "CODIGO"=>"C",
               "P.C."=>"R",
               "SUBTOTAL"=>"R");
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
            $pdf->Cell(190,4,utf8_decode("Le atendio: ".$regv->usuarioND),0,0,"C");  

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
            $pdf->Cell(190,4,utf8_decode("Desarrollado por www.compusisgt.comm / Email: info@compusisgt.com / +502 2293-4153 / WhatsApp: +502 5622-2080 "),0,0,"C");       
            $pdf->Ln(5);
            $pdf->SetX(10);               
            $pdf->SetTextColor(0,0,0); 
            $pdf->SetFillColor($color_r,$color_g,$color_b);
            $pdf->Cell(190,5,".::ULTIMA LINEA::.",1,0,'C',1); 
            $pdf->SetTextColor(0,0,0); 
        }

        $pdf->fact_dev( utf8_decode("NOTA DEBITO NO: ".$regv->idnota_debito),"" );
        $pdf->temporaire( "" ); 
        $pdf->addDate2( ""," "," "," ",$regv->fechaIngreso);
        $pdf->SetXY(110,1); 
        $pdf->Multicell(100,4,utf8_decode("NC PRODUCTOS"),0,"C"); 

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
        $pdf->Multicell(70,4,utf8_decode($regv->sucursal_nombre),0,"C"); 
        $pdf->SetXY(50,14); 
        $pdf->Multicell(70,4,utf8_decode($regv->nombre_fel),0,"C");     
        $pdf->SetXY(50,21); 
        $pdf->Cell(70,4,"Nit: ".$regv->sucursal_nit,0,0,"C");     
        $pdf->SetXY(50,25); 
        $pdf->Multicell(70,4,"Direc: ".$regv->sucursal_direccion,0,"C");  
        $pdf->SetXY(10,34); 
        $pdf->Multicell(120,4,"Tels: ".$regv->sucursal_telefono."  Email: ".$regv->sucursal_email,0,"L");  
        $pdf->Ln(1);
        $pdf->SetX(10);               
        $pdf->Cell(190, 5, "", "B", 0, "C");   
        $pdf->Ln(5);
        $pdf->SetX(10);               
        $pdf->Cell(190, 5, "", "B", 0, "C");                   
        
        $pdf->addRectangulo1();  
        $pdf->SetXY(125,34);
        $pdf->Cell(95,4,utf8_decode("Usuario Creacion: ".$regv->usuarioND),0,"L"); 
        $pdf->SetXY(10,39);
        $pdf->Cell(190,4,utf8_decode("Proveedor: ".$regv->proveedor." CodProveedor: ".$regv->codigo_cliente),0,"L"); 
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
        $cols=array( "CANT"=>15,
           "DESCRIPCION"=>110,
           "CODIGO"=>25,
           "P.C."=>20,
           "SUBTOTAL"=>20);
        $pdf->addCols( $cols);
        $cols=array( "CANT"=>"C",
           "DESCRIPCION"=>"L",
           "CODIGO"=>"C",
           "P.C."=>"R",
           "SUBTOTAL"=>"R");
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
        $rsptad = $cotizaciones->notadebitodetalle($_GET["id"]);

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
            $line = array("CANT"=> "$regd->cantidad",
                "DESCRIPCION"=> utf8_decode("$regd->articulo"),
                "CODIGO"=> "$regd->codigo",
                "P.C."=>  "$regd->precio_compra",
                "SUBTOTAL"=> "$regd->subtotal");
            $size = $pdf->addLine( $y, $line );
            $y   += $size + 2;

            $cantidad+=$regd->cantidad;

        }


        require_once"num2letras.php";
        //$V=new EnLetras();  
        //$con_letra=strtoupper($V->ValorEnLetras($regv->total_venta,"QUETZALEZ"));
        $conletras=$regv->total_compra; 
        $conletrasresultado=num2letras($conletras);
        $pdf->addCadreTVAs("---".$conletrasresultado,"QUETZALEZ"); 
        $pdf->addTVAs( "", $regv->total_compra,"Q ");
        $pdf->addCadreEurosFrancs(""." "); 
        

        $pdf->SetXY(10,230);  
        $pdf->Cell(10,10,"T/".$cantidad,0);         
        $pdf->SetXY(10,250);     
        $pdf->Cell(45,4,utf8_decode("Fecha Ingreso: ".$regv->fechaIngreso),0,0,"C");  
        $pdf->Cell(45,4,utf8_decode("Usuario ND: ".$regv->usuarioND),0,0,"C");  
        $pdf->Cell(45,4,utf8_decode("No Ingreso:  ".$regv->idingreso),0,0,"C");  
        $pdf->Ln(2);
        $pdf->SetX(10);     
        $pdf->Multicell(95,4,utf8_decode("MOTIVO:  ".$regv->motivo_ND),0,"L"); 

        $pdf->SetFont('Arial','',8);


        


        $pdf->Ln(2);
        $pdf->SetX(10);     
        $pdf->Cell(95,10,"Firma Salida: ___________________________",0); 
        $pdf->Cell(95,10,"Firma Recibido: ___________________________",0);     

        $pdf->Ln(6);
        $pdf->SetX(10);     
        $pdf->Cell(190,4,utf8_decode("Desarrollado por www.compusisgt.comm / Email: info@compusisgt.com / +502 2293-4153 / WhatsApp: +502 5622-2080 "),0,0,"C");              

        $pdf->Ln(5);
        $pdf->SetX(10);               
        $pdf->SetTextColor(0,0,0); 
        $pdf->SetFillColor($color_r,$color_g,$color_b);
        $pdf->Cell(190,5,".::ULTIMA LINEA::.",1,0,'C',1); 
        $pdf->SetTextColor(0,0,0); 
        $pdf->Output('Ingreso Productos No'.$regv->idingreso.".pdf", 'I');
    } else {
        echo 'No tiene permiso para visualizar el reporte';
    }
}
?>
