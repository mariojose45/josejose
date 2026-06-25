
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

    $rsptav = $cotizaciones->ventacabecera2($_GET["id"]);  
//Recorremos todos los valores obtenidos
    $regv = $rsptav->fetch_object();
//Establecemos la configuración de la factura
    $pdf = new PDF_Invoice( 'P', 'mm', array(58,2500) );
    $pdf->AddPage();


//$pdf->addSociete(utf8_decode($logo,$ext_logo));
//$pdf->addSociete($logo,$ext_logo);
 
    $pdf->fact_dev( utf8_decode("FACTURA CAMBIARIA"),"" );
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
    $pdf->Multicell($ancchodefial,4,utf8_decode($regv->nombre_comercial),0,"C");  

    $pdf->Ln(1);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,utf8_decode($regv->nombre_fel),0,"C");   

    $pdf->Ln(1);
    $pdf->SetX($xposision); 
    $pdf->Cell($ancchodefial,4,utf8_decode("Nit: ".$regv->sucursal_nit),0,0,"C");           

    $pdf->Ln(5);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,"Direc: ".$regv->direccion_fiscal,0,"C");  
    $pdf->Ln(1);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,"Tels: ".$regv->sucursal_telefono,0,"C");  

    $pdf->Ln(1);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,"  Email: ".$regv->sucursal_email,0,"C");      

    $pdf->Ln(1);
    $pdf->SetX($xposision);               
    $pdf->Cell($ancchodefial, 5, "", "B", 0, "C");  
    $pdf->Ln(1);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,utf8_decode("Fecha Emision: ".date("d/m/Y", strtotime($regv->fecha))),0,"C"); 

    $pdf->Ln(1);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,utf8_decode("DOCUMENTO TRIBUTARIO ELECTRONICO"),0,"C");  
    
    $pdf->Ln(1);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,utf8_decode("NUMERO DE AUTORIZACION: ".$regv->autorizacionEcoFactura),0,"C");        

    $pdf->Ln(1);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,utf8_decode("SERIE: ".$regv->serie_ecoFactura),0,"C");  

    $pdf->Ln(1);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,utf8_decode("NUMERO: ".$regv->numero_ecoFactura),0,"C");      

                            

    $pdf->Ln(1);
    $pdf->SetX($xposision);               
    $pdf->Cell($ancchodefial, 5, "", "B", 0, "C");   
    $pdf->Ln(6);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,utf8_decode("Por medio de esta única factura cambiaria se servirá usted pagar a orden o endoso de ".$regv->nombre_fel.", la suma de acuerdo a las condiciones que se establecen en el presente título, en concepto de servicios y mercadería que acepta haber recibido a entera satisfacción conforme al detalle siguiente."),0,"L");     
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
    $resultadoDetalle = $cotizaciones->ventadetalle2($_GET["id"]); 

    while ($row_detalle = $resultadoDetalle->fetch_assoc()) 
    {

            $yInicio = $pdf->GetY();
            $pdf->MultiCell($ancchodefial, 4, utf8_decode($row_detalle['articulo']."  ".$row_detalle['descripcion_detalle']), 0, 'L');
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
    $pdf->Multicell($ancchodefial,4,utf8_decode($regv->dato_sat),0,"C");     

    $pdf->Ln(5);
    $pdf->SetX($xposision);               
    $pdf->Cell($ancchodefial, 5, "", "B", 0, "C");  

    $pdf->Ln(6);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,utf8_decode("a) AL CANCELAR ESTA FACTURA SIRVASE EXIGIR SU RECIBO COMO UNICO COMPROBANTE DE PAGO.
    \nb) POR CADA CHEQUE RECHAZADO SE COBRARA Q. 150.00.
    \nc) ESTA FACTURA ES EXIGIBLE A SU VENCIMIENTO Y CAUSA INTERRES DE MORA AL 3% MENSUAL.
    \nd) EL COMPRADOR DA COMO CORRECTO EL VALOR TOTAL DE ESTA FACTURA CAMBIARIA LIBRE DE PROTESTO Y SE COMPROMETE A CANCELAR AL VENCIMIENTO AL
    VENDEDOR O ATRAVES DE UNA ENTIDAD BANCARIA QUE ESTE NOMBRE, EN CASO DE INCUMPLIMIENTO EL COMPRADOR RENUNCIA AL FUERO DE SU DOMICILIO Y SE
    SOMETE A LOS TRIBUNALES DEL DEPARTAMENTO DE GUATEMALA, SEÑALANDO PARA RECIBIR Y NOTIFICACIONES, CITACIONES O EMPLAZAMIENTO LA DIRECCION ACTUAL
    DE SU NEGOCIO, SALVO AVISO DE CAMBIO QUE DIERE POR ESCRITO."),0,"L");       
    $pdf->Ln(5);
    $pdf->SetX($xposision);               
    $pdf->Cell($ancchodefial, 5, "", "B", 0, "C");  

    $pdf->Ln(6);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,utf8_decode("A:  $regv->fechahoravencimientofactura
    (EN LAS CONDICIONES ESTIPULADAS EN ESTE
    TITULO) SE SERVIRAN USTEDES PAGAR POR ESTA
    UNICA FACTURA CAMBIARIA GIRADA LIBRE DE
    PROTESTO A LA ORDEN O ENDOSO DE $regv->nombre_fel EN EL VALOR
    TOTAL POR EL QUE FUE EXTENDIDA O POR EL
    ULTIMO SALDO INSOLUTO QUE APAREZCA, VALOR
    RECIBIDO QUE ASENTARAN USTEDES SEGUN
    NUESTRO AVISO."),0,"L");       
    $pdf->Ln(5);
    $pdf->SetX($xposision);               
    $pdf->Cell($ancchodefial, 5, "", "B", 0, "C");  

    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Multicell($ancchodefial,4,utf8_decode("# Abonos: ".$regv->numero_pagos),0,"C");   
    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Multicell($ancchodefial,4,utf8_decode("Fecha Vencimiento: ".$regv->fechahoravencimientofactura),0,"C");   
    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Multicell($ancchodefial,4,utf8_decode("Monto: ".$regv->monto_abono),0,"C");           


    $pdf->Ln(5);
    $pdf->SetX($xposision);               
    $pdf->Cell($ancchodefial, 5, "", "B", 0, "C");      

    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Cell($ancchodefial,4,utf8_decode("Le atendio: ".$regv->usuario),0,0,"L");   

    $pdf->Ln(5); 
    $pdf->SetX($xposision);     
    $pdf->Cell($ancchodefial,4,utf8_decode("# Correlativo V: ".$regv->num_comprobante),0,0,"L");  

    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Cell($ancchodefial,4,utf8_decode("Nº de venta control interno: # ".$regv->idventa),0,0,"L");                     

                      

    $pdf->Ln(5);
    $pdf->SetX($xposision);               
    $pdf->Cell($ancchodefial, 5, "", "B", 0, "C");  

    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Multicell($ancchodefial,4,utf8_decode($regv->nombrecertificador),0,"C");                    

    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Multicell($ancchodefial,4,utf8_decode($regv->empresadesarrollo),0,"C");              

    $pdf->Ln(5);
    $pdf->SetX($xposision);               
    $pdf->SetTextColor(255,255,255); 
    $pdf->SetFillColor($color_r,$color_g,$color_b);
    $pdf->Cell($ancchodefial,5,".::ULTIMA LINEA::.",1,0,'C',1); 
    $pdf->SetTextColor(0,0,0); 




    $pdf->Output('Venta Factura Dte No '.$regv->numero_ecoFactura.".pdf",'I');


 


  }
  else
  {
    echo 'No tiene permiso para visualizar el reporte';
  }

}
ob_end_flush();
?>



