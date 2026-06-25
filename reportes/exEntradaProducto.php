<?php
// Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
    echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else { 
    
    if ($_SESSION['entradaproducto'] == 1) {
        // Incluimos el archivo Factura.php
        require('FormatoMuchoshojas.php');   

        // Establecemos los datos de la empresa
        include 'empresa.php'; 

        // Incluimos la clase Venta
        require_once "../modelos/Entrada_pro_sucursal.php"; 
        $entradaprosucursal= new Entradaprosucursal();

        $rsptav = $entradaprosucursal->entradaprosucursalcabecera($_GET["id"]); 
        //Recorremos todos los valores obtenidos
        $regv = $rsptav->fetch_object();
        // Establecemos la configuración de la factura
        $pdf = new PDF_Invoice('P', 'mm', 'A4');
        $pageCount = 0;
        $pdf->AddPage();
        $pageCount++;

        // Función para imprimir la cabecera
        function imprimirCabecera($pdf, $regv) {
            $pdf->fact_dev( utf8_decode("ENTRADA NO: ".$regv->idtraladosucursal),"" );
            $pdf->temporaire( "" ); 
            $pdf->addDate2( ""," "," "," ",$regv->fecha);
            $pdf->SetXY(110,1); 
            $pdf->Multicell(100,4,utf8_decode("ENTRADA DE PRODUCTO SUCURSAL"),0,"C"); 
        
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
            $pdf->Multicell(70,4,utf8_decode($regv->sucursal_nombre),0,"C"); 
            $pdf->SetXY(50,15); 
            $pdf->Multicell(70,4,utf8_decode($regv->nombre_fel),0,"C");     
            $pdf->SetXY(50,25); 
            $pdf->Cell(70,4,"Nit: ".$regv->sucursal_nit,0,0,"C");     
            $pdf->SetXY(10,30); 
            $pdf->Multicell(190,4,"Direc: ".$regv->sucursal_direccion,0,"C");  
            $pdf->SetXY(10,35); 
            $pdf->Multicell(190,4,"Tels: ".$regv->sucursal_telefono."  Email: ".utf8_decode($regv->sucursal_email),0,"L");  
                $pdf->Ln(1);
                $pdf->SetX(10);               
                $pdf->Cell(190, 5, "", "B", 0, "C");   
                $pdf->Ln(5);
                $pdf->SetX(10);               
                $pdf->Cell(190, 5, "", "B", 0, "C");                   
            
                $pdf->addRectangulo1();  
                $pdf->SetFont('Arial','',8);
                $pdf->SetXY(10,39);
                $pdf->Cell(190,4,utf8_decode("Sucdddursal origen: ".$regv->nombresucursalorigen),0,"L"); 
                $pdf->SetXY(10,45);
                $pdf->Cell(130,4,utf8_decode("Sucursal destino: ".$regv->nombresucursaldestino),0,"L"); $pdf->Cell(40,4,utf8_decode("Usuario Creacion: ".$regv->usuario),0,"L"); 
                $pdf->SetXY(10,50); 
                $pdf->Cell(190,4,utf8_decode("Desc Traslado: ".$regv->descripcion_entrada_producto),0,"L");     
                $pdf->SetFont('Arial','',10);
    
    
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
                         "P.U."=>20);
            $pdf->addCols( $cols);
            $cols=array( "CANT"=>"C",
                         "DESCRIPCION"=>"L",
                         "CODIGO"=>"C",
                         "P.U."=>"R");
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
            $pdf->Cell(190,4,utf8_decode("Le atendio: ".$regv->usuario),0,0,"C");  

            $pdf->SetFont('Arial','B',10);
            $pdf->Ln(5);
            $pdf->SetX(10);     
            $pdf->Cell(190,4,utf8_decode("COD:  ".$regv->num_comprobante),0,0,"C");  

            $pdf->SetFont('Arial','',8);
            $pdf->Ln(2);
            $pdf->SetX(10);     
            $pdf->Cell(95,10,"Firma ENTRADA: ___________________________",0); 
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

        $pdf->fact_dev( utf8_decode("ENTRADA NO: ".$regv->idtraladosucursal),"" );
        $pdf->temporaire( "" ); 
         $pdf->addDate2( ""," "," "," ",$regv->fecha);
        $pdf->SetXY(110,1); 
        $pdf->Multicell(100,4,utf8_decode("ENTRADA DE PRODUCTO SUCURSAL"),0,"C"); 
    
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
        $pdf->SetXY(50,15); 
        $pdf->Multicell(70,4,utf8_decode($regv->nombre_fel),0,"C");     
        $pdf->SetXY(50,25); 
        $pdf->Cell(70,4,"Nit: ".$regv->sucursal_nit,0,0,"C");     
        $pdf->SetXY(10,30); 
        $pdf->Multicell(190,4,"Direc: ".$regv->sucursal_direccion,0,"C");  
        $pdf->SetXY(10,35); 
        $pdf->Multicell(190,4,"Tels: ".$regv->sucursal_telefono."  Email: ".utf8_decode($regv->sucursal_email),0,"L");  
            $pdf->Ln(1);
            $pdf->SetX(10);               
            $pdf->Cell(190, 5, "", "B", 0, "C");   
            $pdf->Ln(5);
            $pdf->SetX(10);               
            $pdf->Cell(190, 5, "", "B", 0, "C");                   
        
            $pdf->addRectangulo1();  
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(10,39);
            $pdf->Cell(190,4,utf8_decode("Sucdddursal origen: ".$regv->nombresucursalorigen),0,"L"); 
            $pdf->SetXY(10,45);
            $pdf->Cell(130,4,utf8_decode("Sucursal destino: ".$regv->nombresucursaldestino),0,"L"); $pdf->Cell(40,4,utf8_decode("Usuario Creacion: ".$regv->usuario),0,"L"); 
            $pdf->SetXY(10,50); 
            $pdf->Cell(190,4,utf8_decode("Desc Traslado: ".$regv->descripcion_entrada_producto),0,"L");     
            $pdf->SetFont('Arial','',10);

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
                         "DESCRIPCION"=>105,
                         "CODIGO"=>25,
                         "PRESEN"=>25,
                         "P.U."=>20);
            $pdf->addCols( $cols);
            $cols=array( "CANT"=>"C",
                         "DESCRIPCION"=>"L",
                         "CODIGO"=>"C",
                         "PRESEN"=>"C",
                         "P.U."=>"R");
        $pdf->addLineFormat( $cols);
        $pdf->addLineFormat($cols);
        // Espacio disponible en la página
        $espacioDisponible = 295; 
        // Altura del footer
        $alturaFooter = 60; 

        $y= 71;


    $pdf->SetTextColor(0,0,0); 
        // Obtenemos todos los detalles de la venta actual
        $rsptad = $entradaprosucursal->entradaprosucursaltadetalle($_GET["id"]);
        $cantidad=0;
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
                        "DESCRIPCION"=> utf8_decode("$regd->articulo"."  "."$regd->descripcion_detalle"),
                         "CODIGO"=> "$regd->codigo",
                         "PRESEN"=> "$regd->presentacion",
                        "P.U."=>  "$regd->precio_venta");
                    $size = $pdf->addLine( $y, $line );
                    $y   += $size + 2;

                    
        }


      
        $pdf->SetXY(10,250);     
        $pdf->Cell(190,4,utf8_decode("Le atendio: ".$regv->usuario),0,0,"C");  

        $pdf->SetFont('Arial','B',10);
        $pdf->Ln(5);
        $pdf->SetX(10);     
        $pdf->Cell(190,4,utf8_decode("C-No:  ".$regv->idtraladosucursal),0,0,"C");  

        $pdf->SetFont('Arial','',8);
 

        $pdf->Ln(2);
        $pdf->SetX(10);     
        $pdf->Cell(95,10,"Firma ENTRADA: ___________________________",0); 
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
        $pdf->Output('ENTRADA Productos No'.$regv->idtraladosucursal.".pdf", 'I');
    } else {
        echo 'No tiene permiso para visualizar el reporte';
    }
}
?>
