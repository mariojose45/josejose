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
require('FormatoMuchoshojas.php');   
  
//Establecemos los datos de la empresa
include'empresa.php';
 
   
//Obtenemos los datos de la cabecera de la venta actual
require_once "../modelos/Venta.php"; 
$cotizaciones= new Venta();

$rsptav = $cotizaciones->pedidoscabecera($_GET["id"]); 
//Recorremos todos los valores obtenidos
$regv = $rsptav->fetch_object();
 
//Establecemos la configuración de la factura
$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
$pdf->AddPage();
    $pdf->SetFont('Arial','B',9);
    $url='../files/articulos/';
    $color_r_texto=0;
    $color_g_texto=0;
    $color_b_texto=0;

    $color_r=171;
    $color_g=195;
    $color_b=220;    

    $imagePath = $url . $regv->sucursal_imagen;

    // Validar si la imagen está vacía o es igual a "0"
    if (empty($regv->sucursal_imagen) || $regv->sucursal_imagen == '0') {
        // Usar una ruta alternativa
        $imagePath = $url . '1590204245.jpg';
    }

    $pdf->SetXY(30,209);
    $pdf->Image($url.$regv->sucursal_imagen,10 ,3, 39 , 20 );
    $pdf->SetFont('Arial','',11);
    $pdf->SetXY(48,5); 
    $pdf->Multicell(70,5,utf8_decode($regv->sucursal_nombre),0,"C");   
    $pdf->SetXY(48,20); 
    $pdf->Multicell(70,5,utf8_decode("User Solicitud: ".$regv->usuario),0,"C");       
    $pdf->SetFont('Arial','',8);
    $pdf->Ln(7);
    $pdf->SetX(10);               
    $pdf->Cell(190, 5, "", "B", 0, "C");   

    $pdf->fact_dev( utf8_decode("PEDIDO DE PRODUCTOS "),"" );
    $pdf->temporaire( "" ); 
    $pdf->addDate4($regv->idsolicitud_productos,$regv->fecha_creacion,$regv->estado);
    $pdf->SetXY(110,1); 
    $pdf->Multicell(100,4,utf8_decode("DOCUMENTO TRIBURARIO ELECTRONICO"),0,"C");    
    

    $pdf->SetXY(10,40); 
    $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
    $pdf->SetFillColor($color_r,$color_g,$color_b);
    $pdf->Cell(190,3.5,".::DATOS DE PRODUCTOS::.",1,0,'C',1); 
    $pdf->SetTextColor(0,0,0); 
    $pdf->SetXY(10,45);   
    
    $pdf->SetFillColor($color_r,$color_g,$color_b); 
    $pdf->Cell(190,5.3," ",1,0,'C',1); 
    $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto);   
    $pdf->SetFont('Arial','',7);    
    $pdf->SetXY(10,46);
    $pdf->Cell(25,5,utf8_decode("CODIGO"),0);        
    $pdf->Cell(110,5,utf8_decode("NOMBRE"),0);        
    $pdf->Cell(20,5,utf8_decode("CAT"),0);        
    $pdf->Cell(20,5,utf8_decode("PRESEN"),0);          
    $pdf->Cell(20,5,utf8_decode("T/UNI"),0);        
    
    $pdf->SetXY(10,52);
    $pdf->SetFont('Arial','',7);
    $rsptad = $cotizaciones->pedidoscabeceraDetalle($_GET["id"]);
    while($regdv = $rsptad->fetch_object()){
      $pdf->Cell(25,4,$regdv->codigo,0);  
      $pdf->Cell(100,4,utf8_decode($regdv->nombre),0);     
      $pdf->Cell(20,4,$regdv->cantidad,0,0,'R');               
      $pdf->Cell(20,4,$regdv->presentacion,0,0,'R');
      $pdf->Cell(20,4,$regdv->totalcantidadpresentacion,0,0,'R');
      
      $pdf->Ln(2);
      $pdf->Ln(2);
    }

    $pdf->Output('Reporte de Venta','I');

 
}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}
 
}
ob_end_flush();
?>