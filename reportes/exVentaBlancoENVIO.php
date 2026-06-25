<?php
// Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';

if (!isset($_SESSION["nombre"])) {
    echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else { 
    
    if ($_SESSION['ventas'] == 1) {
        // Incluimos el archivo Factura.php
        require('FormatoMuchoshojasBlanco.php');   

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

        $pdf->SetDrawColor(0, 0, 0);

       

        $pdf->fact_dev( utf8_decode("# Venta: ".$regv->num_comprobante),"" );
        $pdf->temporaire( "" ); 
         $pdf->addDate3( ""," "," ","# Interno ".$regv->idventa,$regv->fecha);
        $pdf->SetXY(110,1); 
        $pdf->Multicell(100,4,utf8_decode("ENVIO DE PRODUCTOS"),0,"C"); 
    
    //$pdf->Rect(10,43, 100, 4, 'F'); //Rectángulo relleno y con liena
        $url='../files/articulos/';
        $color_r_texto=255;
        $color_g_texto=255;
        $color_b_texto=255;
    
        $color_r=0;
        $color_g=0;
        $color_b=0;
        if ($regv->estado=='Anulado') {
            # code...
            $pdf->Image($anulado,50 ,100, 100 , 25 );
        }
        $pdf->SetXY(30,209);
        $pdf->Image($url.$regv->sucursal_imagen,10 ,3, 30 , 15 );
        $pdf->SetFont('Arial','',8);
        $pdf->SetXY(10,20); 
        $pdf->Multicell(78,4,utf8_decode($regv->nombre_comercial),0,"L");  

        $pdf->SetXY(10,27); 
        $pdf->Multicell(100,4,"Direc: ".$regv->sucursal_direccion,0,"L");  
        $pdf->SetXY(10,30); 
        $pdf->Multicell(190,4,"Tels: ".$regv->sucursal_telefono."  Email: ".$regv->sucursal_email,0,"L");  
            $pdf->Ln(5);
            $pdf->SetX(10);               
            $pdf->Cell(190, 5, "", "B", 0, "C");   
            $pdf->Ln(5);
            $pdf->SetX(10);               
            $pdf->Cell(190, 5, "", "B", 0, "C");  
                 
            $pdf->SetDrawColor(255, 255, 255);
            $pdf->addRectangulo1();  
            $pdf->SetXY(140,34);
            $pdf->Cell(95,4,utf8_decode("Usuario Creacion: ".$regv->usuario),0,"L"); 
            $pdf->SetXY(10,39);
            $pdf->Cell(190,4,utf8_decode("Cliente: ".$regv->cliente),0,"L"); 
            $pdf->SetXY(10,45);
            $pdf->Cell(95,4,utf8_decode("Direccion: ".$regv->direccion),0,"L"); 
            $pdf->SetXY(10,50); 
            $pdf->Cell(63,4,utf8_decode("Tels: ".$regv->telefono),0,"L");     
            $pdf->Cell(63,4,utf8_decode("Email: ".$regv->email),0,"L");     
            $pdf->Cell(63,4,utf8_decode("Vendedor: ".$regv->nombre_vendedor),0,"L");        

  
        $pdf->SetXY(10,57); 
        $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
        $pdf->Cell(190,3.5,".::DATOS DE PRODUCTOS::.",0,0,'C',1); 
        $pdf->SetTextColor(0,0,0); 
        $pdf->SetXY(10,63);
        
        $pdf->SetFont('Arial','',8);
        //Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
        $pdf->SetDrawColor(255, 255, 255);
        $cols=array( "CANT"=>15,
                         "DESCRIPCION"=>105,
                         "CODIGO"=>30,
                         "P.U."=>20,
                         "SUBTOTAL"=>20);
            $pdf->addCols( $cols);
            $cols=array( "CANT"=>"C",
                         "DESCRIPCION"=>"L",
                         "CODIGO"=>"C",
                         "P.U."=>"R",
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
        // Ejecutamos las consultas para obtener los detalles de la venta
        $rsptad = $cotizaciones->ventadetalle2($_GET["id"]);
        $rsptadExtra = $cotizaciones->ventadetalleExtra($_GET["id"]);

        // Convertimos los resultados a arrays (o puedes hacer un fetch directo en el ciclo)
        $detalleVenta = [];
        while ($regd = $rsptad->fetch_object()) {
            $detalleVenta[] = $regd;
        }

        $detalleExtra = [];
        while ($regdExtra = $rsptadExtra->fetch_object()) {
            $detalleExtra[] = $regdExtra;
        }

        // Ahora procesamos los resultados y generamos el PDF
        setlocale(LC_MONETARY, "en_US");

        // Combinamos los resultados de detalle_venta y detalle_ventaExtra
        foreach ($detalleVenta as $regd) {
            setlocale(LC_MONETARY, "en_US");

            if ($y + $alturaFooter > $espacioDisponible) {
                // Agrega una nueva página y vuelve a imprimir la cabecera
                $pdf->AddPage();
                $pageCount++;
                imprimirCabecera($pdf, $regv); 
                $y = 75; // Reinicia la posición y
            }

            // Descripción sin sangría para productos de detalle_venta
            $descripcion = utf8_decode("$regd->articulo $regd->presen $regd->descripcion_detalle");

            // Si es un Extra, agregar la sangría en la descripción
            $line = array(
                "CANT" => "$regd->cantidad",
                "DESCRIPCION" => $descripcion,
                "CODIGO" => "$regd->codigo",
                "P.U." => number_format($regd->precio_venta, 2, '.', ','),  // Precio del artículo
                "SUBTOTAL" => "Q " . number_format($regd->subtotal, 2, '.', ',')
            );

            // Agregar la línea al PDF
            $size = $pdf->addLine($y, $line);
            $y += $size + 2;

            // Acumulación de cantidad
            $cantidad += $regd->cantidad;
        }

        // Procesamos los detalles de detalle_ventaExtra (los Extras)
        foreach ($detalleExtra as $regdExtra) {
            // Agregar sangría en la descripción de Extras
            $descripcionExtra = "    " . utf8_decode("Extra - $regdExtra->articulo");  // Sangría para la descripción de Extra
            
            $lineExtra = array(
                "CANT" => number_format($regdExtra->cantidad_extra, 2, '.',','),  // Formateamos la cantidad
                "DESCRIPCION" => $descripcionExtra, // Descripción con sangría
                "CODIGO" => "$regdExtra->codigo", // Aquí accedemos al código del artículo
                "P.U." => number_format($regdExtra->precio_extra, 2, '.',','),  // Precio del extra
                "SUBTOTAL" => "Q " . number_format(($regdExtra->precio_extra * $regdExtra->cantidad_extra), 2, '.', ',')  // Calculamos el subtotal
            );

            // Agregar la línea extra al PDF
            $sizeExtra = $pdf->addLine($y, $lineExtra);
            $y += $sizeExtra + 2;

            // Acumulación de cantidad de extras
            $cantidad += $regdExtra->cantidad_extra;
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


        $pdf->SetFont('Arial','',8);
 

        $pdf->SetXY(10,255);  
        $pdf->SetX(15);     
        $pdf->Cell(95,10,"Firmas Salida: ___________________________",0); 
        $pdf->Cell(95,10,"Firma Recibido: ___________________________",0);     

        $pdf->Ln(8);
        $pdf->SetX(10);     
        $pdf->Cell(190,4,utf8_decode($regv->empresadesarrollo),0,0,"C");              

        $pdf->Ln(5);
        $pdf->SetX(10);               
        $pdf->SetTextColor(255,255,255); 
        $pdf->SetFillColor(0,0,0);
        $pdf->Cell(190,5,".::ULTIMA LINEA::.",1,0,'C',1); 
        $pdf->SetTextColor(0,0,0); 
        $pdf->Output('Envio Productos No'.$regv->idventa.".pdf", 'I');
    } else {
        echo 'No tiene permiso para visualizar el reporte';
    }
}
?>
