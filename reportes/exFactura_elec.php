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
    $empresa = "";
    $documento = "NIT: ";
    $direccion = " ";
    $telefono = " ";
    $email = ".com";
  
   
//Obtenemos los datos de la cabecera de la venta actual
require_once "../modelos/Cotizaciones.php"; 
$cotizaciones= new Cotizaciones();

$rsptav = $cotizaciones->ventacabecera2($_GET["id"]);  
//Recorremos todos los valores obtenidos
$regv = $rsptav->fetch_object();
 
//Establecemos la configuración de la factura
$pdf = new PDF_Invoice( 'P', 'mm', 'A4' ); 
$pdf->AddPage();



    $pdf->fact_dev( "Ticket # ", "$regv->num_comprobante" );
    $pdf->temporaire( "" );
    $pdf->addDate( $regv->fecha);



    //Enviamos los datos del cliente al método addClientAdresse de la clase Factura
    $pdf->addClientAdresse(utf8_decode($regv->cliente),"Domicilio: ".utf8_decode($regv->direccion),$regv->tipo_documento.": ".$regv->num_documento,"Email: ".$regv->email,"Telefono: ".$regv->telefono);
    $pdf->SetXY(145,43);
$pdf->Cell(10,10,"TELEFONO: ".$regv->telefono,0); 
     
    //Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
    $cols=array( "CANTIDAD"=>23,
                 "DESCRIPCION"=>100,
                 "P.U."=>25,
                 "DSCTO"=>20,                 
                 "SUBTOTAL"=>22);
    $pdf->addCols( $cols);
    $cols=array( "CODIGO"=>"L",
                 "DESCRIPCION"=>"L",
                 "CANTIDAD"=>"C",
                 "P.U."=>"R",
                    "DSCTO" =>"R",                 
                 "SUBTOTAL"=>"C");
    $pdf->addLineFormat( $cols);
    $pdf->addLineFormat($cols);
    //Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
    $y= 57;


    //Obtenemos todos los detalles de la venta actual
    $rsptad = $cotizaciones->ventadetalle2($_GET["id"]);
     
    while ($regd = $rsptad->fetch_object())
    {
        setlocale(LC_MONETARY,"en_US");

      $line = array( "CANTIDAD"=> "$regd->cantidad",
                    "DESCRIPCION"=> utf8_decode("$regd->articulo"."  "."$regd->descripcion_detalle"),
                    "P.U."=>  "$regd->precio_venta",
                    "DSCTO" => "$regd->descuento",
                    "SUBTOTAL"=> "$regd->subtotal");
                $size = $pdf->addLine( $y, $line );
                $y   += $size + 2;
    }
$url='../files/articulos/';






    //Convertimos el total en letras
    //require_once "Letras.php"; 
    require_once"num2letras.php";
    //$V=new EnLetras(); 
    //$con_letra=strtoupper($V->ValorEnLetras($regv->total_venta,"QUETZALEZ"));
    $conletras=$regv->total_venta;
     $conletrasresultado=num2letras($conletras);
    $pdf->addCadreTVAs("---".$conletrasresultado,"QUETZALEZ");

 
 
$pdf->SetFont('Arial','B',14);
$pdf->SetXY(10,5);
$pdf->Cell(10,10,$regv->sucursal_nombre,0); 
$pdf->SetFont('Arial','B',10 );
$pdf->SetXY(10,10);
$pdf->Cell(10,10,"Correo: ".$regv->sucursal_email,0);  

$pdf->SetXY(10,14);
$pdf->Cell(10,10,"Usuario Creacion: ".$regv->usuario,0);  

$pdf->SetXY(10,18);
$pdf->Cell(10,10,"Forma Pago: ".$regv->forma_pago,0);  






$pdf->SetXY(10,33);
$pdf->Cell(10,10,"________________________________________________________________________________________________ ",0); 
$pdf->SetXY(10,38);
$pdf->Cell(10,10,"________________________________________________________________________________________________ ",0); 




    //Mostramos el impuesto
    $pdf->addTVAs( $regv->impuesto, $regv->total_venta,"Q ");
    $pdf->addCadreEurosFrancs(""." "); 





    $pdf->addRecibo($regv->idventa); 

    $pdf->Output('FACTURA ELECTRONICA','I');

 
}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}
 
}
ob_end_flush();
?>