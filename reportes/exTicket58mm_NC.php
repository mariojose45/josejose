
<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"]))
{
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
}
else
{
  if ($_SESSION['ventas']==1)
  {
//Incluímos el archivo Factura.php
    require('FormatoCartaConFacturaTickekt.php');   

//Establecemos los datos de la empresa
    include'empresa.php';
  

    //Incluímos la clase Venta
    require_once "../modelos/Cotizaciones.php";   
    $cotizaciones= new Cotizaciones();

    $rsptav = $cotizaciones->ventacabeceraNC2($_GET["id"]);  
//Recorremos todos los valores obtenidos
    $regv = $rsptav->fetch_object();
//Establecemos la configuración de la factura
    $pdf = new PDF_Invoice( 'P', 'mm', array(58,2500) ); 
    $pdf->AddPage();


//$pdf->addSociete(utf8_decode($logo,$ext_logo));
//$pdf->addSociete($logo,$ext_logo);
 
    $pdf->fact_dev( utf8_decode("NOTA CREDITO ENVIO"),"" );
    $pdf->temporaire( "" ); 

    $pdf->SetXY(110,1); 
    $pdf->Multicell(100,4,utf8_decode(""),0,"C"); 

//$pdf->Rect(10,43, 100, 4, 'F'); //Rectángulo relleno y con liena
    $url='../files/articulos/';
    $color_r_texto=255;
    $color_g_texto=255;
    $color_b_texto=255;

    $color_r=0;
    $color_g=0;
    $color_b=0; 

    $xposision=1;

    $ancchodefial=58;


    if ($regv->estado=='Anulado') {
        # code...
        $pdf->Image($anulado,10 ,150, 40 , 25 );
    }

    $pdf->SetXY($xposision,1);
    $pdf->Image($url.$regv->sucursal_imagen,10 ,3, 40 , 25 );
    $pdf->SetFont('Arial','',11);
    $pdf->SetXY($xposision,42); 
    $pdf->Multicell($ancchodefial,4,$regv->nombre_comercial,0,"C");  

        

    $pdf->SetXY($xposision,48); 
    $pdf->Multicell($ancchodefial,4,"Direc: ".$regv->direccion_fiscal,0,"C");  
    $pdf->SetXY($xposision,70); 
    $pdf->Multicell($ancchodefial,4,"Tels: ".$regv->sucursal_telefono,0,"C");  

    $pdf->SetXY($xposision,75); 
    $pdf->Multicell($ancchodefial,4,"  Email: ".$regv->sucursal_email,0,"C");      

    $pdf->Ln(1);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,utf8_decode("Fecha NC: ".$regv->fecha_hora_nc),0,"C"); 
                          

    $pdf->Ln(1);
    $pdf->SetX($xposision);               
    $pdf->Cell($ancchodefial, 5, "", "B", 0, "C");         

    $pdf->Ln(6);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,utf8_decode("Clie: ".$regv->cliente),0,"L"); 
    $pdf->Ln(5);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,utf8_decode("Direc: ".$regv->direccion),0,"L");     
    $pdf->Ln(5);
    $pdf->SetX($xposision); 
    $pdf->Cell($ancchodefial,4,utf8_decode($regv->tipo_documento.": ".$regv->num_documento),0,"L");  
    $pdf->Ln(5);
    $pdf->SetX($xposision);          
    $pdf->Multicell($ancchodefial,4,utf8_decode("Tels: ".$regv->telefono),0,"L");       
 

    $pdf->Ln(5);
    $pdf->SetX($xposision);               
    $pdf->Cell($ancchodefial, 5, "", "B", 0, "C");  
    
    $pdf->Ln(5);
    $pdf->SetX($xposision); 
    $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
    $pdf->SetFillColor($color_r,$color_g,$color_b);
    $pdf->Cell($ancchodefial,3.5,".::DATOS DE PRODUCTOS::.",1,0,'C',1); 
    $pdf->SetTextColor(0,0,0); 

    $pdf->Ln(5);
    $pdf->SetX($xposision); 
    $pdf->SetFillColor($color_r,$color_g,$color_b);
    $pdf->Cell($ancchodefial,10," ",1,0,'C',1); 
    $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
    $pdf->Ln(1);
    $pdf->SetX($xposision); 
    $pdf->cell($ancchodefial,4,"ARTICULO",0,0,'C'); 
    $pdf->Ln(1);
    $pdf->SetX($xposision);     
    $pdf->cell(19,10,"CAN"); 
    $pdf->cell(19,10,"P.U.");
    $pdf->cell(19,10,"SUB"); 
    $pdf->SetTextColor(0,0,0);         
    $pdf->Ln(10);
    $pdf->SetX($xposision); 
    $resultadoDetalle = $cotizaciones->ventadetalleNC2($_GET["id"]); 

    while ($row_detalle = $resultadoDetalle->fetch_assoc()) 
    {

            $yInicio = $pdf->GetY();
            $pdf->MultiCell($ancchodefial, 4, utf8_decode($row_detalle['articulo']." ".$row_detalle['presen']), 0, 'L');
            $yFin = $pdf->GetY();
            // Agregar espacio adicional
            $espacio = 2; // espacio adicional de 5 unidades
            $yFin += $espacio;

            $pdf->SetY($yFin); // Mover hacia abajo para el espacio
            $pdf->SetX(1); // Restablecer la posición X

            $pdf->Cell(15, 4, $row_detalle['cantidad'], 0, 0, 'L');
            $pdf->Cell(21, 4, number_format($row_detalle['q_ref'], 2, '.', ','), 0, 0, 'C');
            $pdf->Cell(21, 4, number_format($row_detalle['subtotal'], 2, '.', ','), 0, 0, 'R');
            $pdf->Ln(5); // Salto de línea después de cada fila de detalle
            // Restablecer posición X después de cada detalle
            $pdf->SetX(1);    
    }

    $pdf->Ln(1); 
    $pdf->SetX($xposision);               
    $pdf->Cell($ancchodefial, 5, "", "B", 0, "C"); 
    require_once"num2letras.php";
    //$V=new EnLetras();  
    //$con_letra=strtoupper($V->ValorEnLetras($regv->total_venta,"QUETZALEZ"));
    $conletras=$regv->total_venta;
    $conletrasresultado=num2letras($conletras);    
    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Cell($ancchodefial,4,utf8_decode("SUBTOTAL: ".$regv->totalgeneral),0,0,"R"); 
    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Cell($ancchodefial,4,utf8_decode("DESCUENTO: ".$regv->total_ventades),0,0,"R"); 
    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Cell($ancchodefial,4,utf8_decode("TOTAL: ".$regv->total_venta),0,0,"R");         


    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Multicell($ancchodefial,4,utf8_decode("Total en Letras: ".$conletrasresultado." Quetzales"),0,"C"); 




    $pdf->Ln(5);
    $pdf->SetX($xposision);               
    $pdf->Cell($ancchodefial, 5, "", "B", 0, "C");  

    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Cell($ancchodefial,4,utf8_decode("Le atendio: ".$regv->usuarioNc),0,0,"L");   
 
    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Cell($ancchodefial,4,utf8_decode("# Correlativo NC: ".$regv->num_comprobante),0,0,"L");     

    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Cell($ancchodefial,4,utf8_decode("# Idventa: ".$regv->idventa),0,0,"L");    
                     

                      

    $pdf->Ln(5);
    $pdf->SetX($xposision);               
    $pdf->Cell($ancchodefial, 5, "", "B", 0, "C");  
               

    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Multicell($ancchodefial,4,utf8_decode("Desarrollado por www.compusisgt.comm / Email: info@compusisgt.com / +502 2293-4153 / WhatsApp: +502 5622-2080 "),0,"C");              

    $pdf->Ln(5);
    $pdf->SetX($xposision);               
    $pdf->SetTextColor(255,255,255); 
    $pdf->SetFillColor($color_r,$color_g,$color_b);
    $pdf->Cell($ancchodefial,5,".::ULTIMA LINEA::.",1,0,'C',1); 
    $pdf->SetTextColor(0,0,0); 




    $pdf->Output('NC No '.$regv->num_comprobante.".pdf",'I');


 


  }
  else
  {
    echo 'No tiene permiso para visualizar el reporte';
  }

}
ob_end_flush();
?>



