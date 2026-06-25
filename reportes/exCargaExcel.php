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
if ($_SESSION['guiastransporte']==1)
{
//Incluímos el archivo Factura.php
require('FacturaGuias.php');   
 
//Establecemos los datos de la empresa
include'empresa.php';
 
  
//Obtenemos los datos de la cabecera de la venta actual
require_once "../modelos/Cargadeguias.php"; 
$cotizaciones= new Categoria();

$rsptav = $cotizaciones->cabecerareporteecel($_GET["id"]); 
//Recorremos todos los valores obtenidos
$regv = $rsptav->fetch_object();
 
//Establecemos la configuración de la factura
$pdf = new PDF_Invoice( 'L', 'mm', 'A4' );
$pdf->AddPage();
//$pdf->Image($logo,50,50,50);
//$pdf->Ln(50); 
//$pdf->Ln();
//$pdf->Ln(); 
//Enviamos los datos de la empresa al método addSociete de la clase Factura



//$pdf->addSociete(utf8_decode($logo,$ext_logo));
//$pdf->addSociete($logo,$ext_logo);
$pdf->fact_dev( "Carga Excel No. 0000 ","$regv->idguias_excel" );
$pdf->temporaire( "" );
$pdf->addDate( $regv->fecha);




//$pdf->addClienteprueba(utf8_decode($regv->cliente));
 
//Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
$cols=array( "GUIA"=>27,
            "FECHA"=>25,
            "M_VENTA"=>25,
            "COMISION"=>30,
            "VCOMISION"=>25,
            "MLIQUIDACION"=>30,
            "AUTORIZACION"=>30,
            "CTABANCO"=>30,
         	  "VFLETE"=>30,
            "TRANSPORTE"=>25);
$pdf->addCols( $cols);
$cols=array( "GUIA"=>"R",
              "FECHA"=>"R",
              "M_VENTA"=>"R",
       		     "COMISION"=>"R",
         			"VCOMISION"=>"R",
        			"MLIQUIDACION"=>"R",
        			"AUTORIZACION"=>"R",
        			"CTABANCO"=>"R",
        			"VFLETE"=>"R",
              "TRANSPORTE"=>"R");
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols);
//Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
$y= 50;
 
//Obtenemos todos los detalles de la venta actual
$rsptad = $cotizaciones->reporteeceldetalle($_GET["id"]);
 
while ($regd = $rsptad->fetch_object()) {
  $line = array( "GUIA"=> "$regd->idguia",
                "FECHA"=> utf8_decode("$regd->fecha"),
                "M_VENTA"=> utf8_decode("$regd->mventa"),
                "COMISION"=> utf8_decode("$regd->comision"),
                "VCOMISION"=> utf8_decode("$regd->vcomision"),
                "MLIQUIDACION"=> utf8_decode("$regd->mliquido"),
                "AUTORIZACION"=> utf8_decode("$regd->autorizacion"),
                "CTABANCO"=> utf8_decode("$regd->ctabanco"),
                "VFLETE"=> utf8_decode("$regd->vflete"),
                "TRANSPORTE"=> utf8_decode("$regd->transportes"));
            $size = $pdf->addLine( $y, $line );
            $y   += $size+0.5; 
} 
 
$url='../files/articulos/';



$pdf->SetXY(245,25);
$pdf->Cell(10,10,$regv->fecha,0); 

$pdf->SetFont('Arial','B',20);
$pdf->SetXY(10,10);
$pdf->Cell(10,10,"GUIA LIQUIDACION",0); 

$pdf->SetFont('Arial','B',10);
$pdf->SetXY(10,16);
$pdf->Cell(10,10,$regv->sucursal_nombre,0); 

$pdf->SetXY(10,23);
$pdf->Multicell(150,4,utf8_decode("Direccion:".$regv->sucursal_direccion),0); 
$pdf->SetXY(10,24);
$pdf->Cell(10,10,"Nit:  ".$regv->sucursal_nit,0); 
$pdf->SetXY(10,28);
$pdf->Cell(10,10,"Telefono: ".$regv->sucursal_telefono,0); 
$pdf->SetXY(10,32);
$pdf->Cell(10,10,"Email: ".$regv->sucursal_email,0);  



$pdf->Output('Carga Excel','I');



 
 
}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}
 
}
ob_end_flush();
?>