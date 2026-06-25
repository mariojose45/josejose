<?php
//Activamos el almacenamiento en el buffer
ob_start();


//Incluímos el archivo Factura.php
require('FacturaEnvio2.php'); 

//Establecemos los datos de la empresa
$logo = "LOGOAGUA.png";
$ext_logo = "png";
$logopie = "piepagina.jpg";
$ext_logopie = "jpg";
$logovisa="visacuota.jpg";
$logovisaext = "jpg";
$feel="fel.jpg";
$ext_feel = "jpg";     



//Obtenemos los datos de la cabecera de la venta actual
require_once "../modelos/Admin_ordenes.php";  
$adminordenes=new AdminOrdenes(); 

$rsptav = $adminordenes->cabeceranuevaorden($_GET["id"]);  
//Recorremos todos los valores obtenidos
$regv = $rsptav->fetch_object();

//Establecemos la configuración de la factura
$pdf = new PDF_Invoice( 'L', 'mm', array(50,100) ); 
$pdf->AddPage();
$url='../files/articulos/'; 

$pdf->SetFont('Arial', 'b', 10);
 
$pdf->Image($url.$regv->sucursal_imagen,0 ,1, 15 , 10 );
$pdf->SetXY(20,1);
$pdf->Cell(10,10,"# Orden Trabajo: ".$regv->num_nueva_orden,0); 
$pdf->SetXY(20,5);
$pdf->Cell(10,10,$regv->nombre_sucursal,0); 


$pdf->SetFont('Arial', '', 10); 
$pdf->SetXY(1,10);
$pdf->Cell(10,10,"Nombre Cli: ".$regv->nombre_cliente,0,1);  
$pdf->SetXY(1,15);
$pdf->Cell(10,10,"Tels Cli: ".$regv->nombre_telefono,0,0);  

$pdf->Output('Orden Trabajo No: '.$regv->num_nueva_orden.".pdf",'I');





ob_end_flush();
?>