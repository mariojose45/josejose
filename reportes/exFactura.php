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
require('Facturafactura.php');  
 
//Establecemos los datos de la empresa
    $logo = "logo.jpg";
    $ext_logo = "jpg";
    $logopie = "piepagina.jpg";
    $ext_logopie = "jpg";
    $logovisa="visacuota.jpg";
    $logovisaext = "jpg";
    $empresa = "TECNOSERVICIOSJIREH";
    $documento = "NIT: 5869008-5";
    $direccion = "10MA AVENIDA 07-03 Z. 01";
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



    $pdf->temporaire( "" );
    $pdf->addDate( $regv->fecha);



    //Enviamos los datos del cliente al método addClientAdresse de la clase Factura
    $pdf->addClientAdresse("Nombre:    ".utf8_decode($regv->cliente),"Direccion:  ".utf8_decode($regv->direccion),"Nit:             ".$regv->num_documento);
     
    //Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
    $cols=array( "CANTIDAD"=>23,
                 "DESCRIPCION"=>120,
                 "P.U."=>25,
                 "SUBTOTAL"=>22);
    $pdf->addCols( $cols);
    $cols=array( "CANTIDAD"=>"L",
                 "DESCRIPCION"=>"L",
                 "P.U."=>"R",
                 "SUBTOTAL"=>"C");
    $pdf->addLineFormat( $cols);
    $pdf->addLineFormat($cols);
    //Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
    $y= 59;

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


    //Convertimos el total en letras
    //require_once "Letras.php"; 
    require_once"num2letras.php";
    //$V=new EnLetras(); 
    //$con_letra=strtoupper($V->ValorEnLetras($regv->total_venta,"QUETZALEZ"));
    $conletras=$regv->total_venta;
     $conletrasresultado=num2letras($conletras);
    $pdf->addCadreTVAs("".$conletrasresultado,"QUETZALEZ");


     
    //Mostramos el impuesto
    $pdf->addTVAs( $regv->impuesto, $regv->total_venta,"Q/ ");
    $pdf->addCadreEurosFrancs("IVA"." $regv->impuesto %"); 
    $pdf->Output('Reporte de Venta','I');

 
}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}
 
}
ob_end_flush();
?>