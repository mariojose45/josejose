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
require('FacturaEnvio.php');   
   
//Establecemos los datos de la empresa
    $logo = "LOGOAGUA.png";
    $ext_logo = "png";
    $logopie = "piepagina.jpg";
    $ext_logopie = "jpg";
    $logovisa="visacuota.jpg";
    $logovisaext = "jpg";
    $feel="fel.jpg";
    $ext_feel = "jpg";    
    $empresa = "COMERCIALIZADORA JIREH M&A S.A.";
    $documento = "NIT: 9496528-5";
    $direccion = "Kilometro 19.5 Carretera al Pacifico  Zona 10 Comdominio Parque Naciones Unidas, Bodega Tipo C 19, Villa Nueva, Guatemala ";
    $telefono = " +502-2238-3747";
    $email = "mdeleon@tecnoserviciosjireh.com";
 
   
//Obtenemos los datos de la cabecera de la venta actual
require_once "../modelos/Cotizaciones.php"; 
$cotizaciones= new Cotizaciones();

$rsptav = $cotizaciones->ventacabecera2($_GET["id"]); 
//Recorremos todos los valores obtenidos
$regv = $rsptav->fetch_object();
 
//Establecemos la configuración de la factura
$pdf = new PDF_Invoice( 'P', 'mm', 'A4' ); 
$pdf->AddPage();


/*
    $pdf->fact_dev( "Comprobante Garantia # ", "$regv->idventa" );
    $pdf->temporaire( "" );
    $pdf->addDate( $regv->fecha);
*/


    //Enviamos los datos del cliente al método addClientAdresse de la clase Factura
    $pdf->addClientAdresse(utf8_decode($regv->cliente),"Domicilio: ".utf8_decode($regv->direccion),$regv->tipo_documento.": ".$regv->num_documento,"Email: ".$regv->email,"Telefono: ".$regv->telefono);
     
    //Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
    $cols=array( "CANTIDAD"=>23,
                 "DESCRIPCION"=>120,
                 "P.U."=>25,
                 "SUBTOTAL"=>22);
    $pdf->addCols( $cols);
    $cols=array( "CODIGO"=>"L",
                 "DESCRIPCION"=>"L",
                 "CANTIDAD"=>"C",
                 "P.U."=>"R",
                 "SUBTOTAL"=>"C");
    $pdf->addLineFormat( $cols);
    $pdf->addLineFormat($cols);
    //Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
    $y= 73;

    //Obtenemos todos los detalles de la venta actual
    $rsptad = $cotizaciones->ventadetalle2($_GET["id"]);
     
    while ($regd = $rsptad->fetch_object())
    {
      $line = array( "CANTIDAD"=> "$regd->cantidad",
                    "DESCRIPCION"=> utf8_decode("$regd->articulo"."  "."$regd->descripcion_detalle"),
                    "P.U."=> "$regd->precio_venta",
                    "SUBTOTAL"=> "$regd->subtotal");
                $size = $pdf->addLine( $y, $line );
                $y   += $size + 2;
    }
$url='../files/articulos/';

$pdf->SetXY(30,209);
$pdf->Image($url.$regv->sucursal_imagen,5 ,3, 50 , 35 );


$pdf->SetXY(30,209);
$pdf->Image($feel,163 ,3, 30 , 15 );

    //Convertimos el total en letras
    //require_once "Letras.php"; 
    require_once"num2letras.php";
    //$V=new EnLetras(); 
    //$con_letra=strtoupper($V->ValorEnLetras($regv->total_venta,"QUETZALEZ"));
    $conletras=$regv->total_venta;
     $conletrasresultado=num2letras($conletras);
    $pdf->addCadreTVAs("---".$conletrasresultado,"QUETZALEZ");

 
 
$pdf->SetFont('Arial','B',32);
$pdf->SetXY(60,10);
$pdf->Cell(10,10,$regv->sucursal_nombre,0); 
$pdf->SetFont('Arial','B',10 );
$pdf->SetXY(60,20);
//$pdf->Cell(10,10,$regv->sucursal_direccion,0); 
$pdf->Multicell(80,3,utf8_decode($regv->sucursal_direccion),0); 
$pdf->SetXY(60,21);
$pdf->Cell(10,10,"Telefono: ".$regv->sucursal_telefono,0); 
$pdf->SetXY(60,25);
$pdf->Cell(10,10,"Nit: ".$regv->sucursal_nit,0); 
$pdf->SetXY(60,29);
$pdf->Cell(10,10,"Correo: ".$regv->sucursal_email,0);  

$pdf->SetXY(60,33);
$pdf->Cell(10,10,"Vendedor: ".$regv->nombre_vendedor,0);  



  
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(143,15);
$pdf->Cell(10,10,"FACTURA CAMBIARIA");
$pdf->SetXY(143,19);
$pdf->Cell(10,10,"DOCUMENTO TRIBUTARIO ELECTRONICO ");
$pdf->SetXY(143,23);
$pdf->Cell(10,10,"NUMERO DE AUTORIZACION: ");
$pdf->SetXY(143,26);
$pdf->Cell(10,10,$regv->autorizacionEcoFactura,0);  
$pdf->SetXY(143,30);
$pdf->Cell(10,10,"SERIE: ".$regv->serie_ecoFactura,0);  
$pdf->SetXY(143,35);
$pdf->Cell(10,10,"NUMERO: ".$regv->numero_ecoFactura,0); 
$pdf->SetXY(143,40);
$pdf->Cell(10,10,"FECHA AUTORIZACION: ".$regv->fechaCertificacion_ecoFactura,0); 


$pdf->SetXY(10,48);
$pdf->Cell(10,10,"________________________________________________________________________________________________________________________ ",0); 
$pdf->SetXY(10,53);
$pdf->Cell(10,10,"________________________________________________________________________________________________________________________ ",0); 


$pdf->SetXY(30,209);
$pdf->Image($logo,40,60, 140 , 95 );

    //Mostramos el impuesto
    $pdf->addTVAs( $regv->impuesto, $regv->total_venta,"Q/ ");
    $pdf->addCadreEurosFrancs("IVA"." $regv->impuesto %"); 

$pdf->SetFont('Arial','B',11);
$pdf->SetXY(75,210);
$pdf->Cell(10,10,"PAGOS TRIMESTRALES ");

$pdf->SetFont('Arial','B',9);
$pdf->SetXY(10,235);
$pdf->Cell(10,10,"CERTIFICADOR ECO-FACTURAS NIT.: 64281167 www.ecofactura.com.gt ");
$pdf->SetXY(130,235);
$pdf->Cell(10,10,"CONTROL INTERNO:  ".$regv->tipo_comprobante."  ".$regv->idventa);


    $pdf->Output('Reporte de Venta','I');

 
}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}
 
}
ob_end_flush();
?>