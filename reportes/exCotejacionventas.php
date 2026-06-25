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
require_once "../modelos/Cotejacionvetas_guias.php"; 
$cotijacion= new Cotijamiento();

$rsptav = $cotijacion->cabeceracotejacientoventas($_GET["id"]); 
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
$pdf->fact_dev( "Cotejacion No. 0000 ","$regv->idcotejamientoventas_guias" );
$pdf->temporaire( "" );
$pdf->addDate( $regv->fecha);


$url='../files/articulos/';
$pdf->SetXY(245,25);
$pdf->Cell(10,10,$regv->fecha,0); 


$pdf->SetFont('Arial','B',10);
$pdf->SetXY(10,10);
$pdf->Cell(10,10,"DESCARGUE DE GUIAS VRS VENTAS",0); 


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
$pdf->SetFont('Arial','',8);
//$pdf->addClienteprueba(utf8_decode($regv->cliente));
 
//Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
/*$cols=array( "GUIA"=>30,
            "FECHA"=>25,
            "M_VENTA"=>20,
            "COMISION"=>25,
            "VCOMISION"=>25,
            "MLIQUIDACION"=>30,
            "AUTORIZACION"=>25,
            "CTABANCO"=>25,
         	  "VFLETE"=>20,
            "RESTANTE"=>27,
            "TRANS"=>25);
$pdf->addCols( $cols);
$cols=array( "GUIA"=>"L",
            "FECHA"=>"L",
            "M_VENTA"=>"L",
     		     "COMISION"=>"L",
 			"VCOMISION"=>"L",
			"MLIQUIDACION"=>"L",
			"AUTORIZACION"=>"L",
			"CTABANCO"=>"L",
			"VFLETE"=>"L",
      "RESTANTE"=>"L",
      "TRANS"=>"L");
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols);
//Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
$y= 50;
 
//Obtenemos todos los detalles de la venta actual
$rsptad = $cotijacion->reporteeceldetallecotejacionventas($_GET["id"]);
 
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
              "VFLETE"=> utf8_decode("$regd->vflete"),
              "RESTANTE"=> utf8_decode("$regd->restan"),
              "TRANS"=> utf8_decode("$regd->transportes"));
            $size = $pdf->addLine( $y, $line );
            $y   += $size+0.5; 
} */
 
     $pdf->SetXY(10,40);    
    $pdf->Cell(190,10,'*****************************************************************************************************************************************************************************************************************************************************'); 
        $pdf->ln(2);
    $pdf->cell(30,10,"GUIA."); 
    $pdf->cell(25,10,"FECHA."); 
    $pdf->cell(20,10,"M_VENTA.");
    $pdf->cell(25,10,"COMISION.");
    $pdf->cell(25,10,"VCOMISION.");
    $pdf->cell(30,10,"MLIQUIDACION.");
    $pdf->cell(25,10,"AUTORIZACION.");
    $pdf->cell(25,10,"CTABANCO."); 
    $pdf->cell(20,10,"VFLETE."); 
    $pdf->cell(25,10,"RESTANTE."); 
    $pdf->cell(25,10,"TRANS."); 
    $pdf->ln(5);
    $pdf->Cell(190,10,'*****************************************************************************************************************************************************************************************************************************************************');    
    $pdf->Ln(2);
    $rsptad = $cotijacion->reporteeceldetallecotejacionventas($_GET["id"]);
    while($regdv = $rsptad->fetch_object()){

      $pdf->Cell(30,10,$regdv->idguia,0);
      $pdf->Cell(25,10,utf8_decode($regdv->fecha),0);        
      $pdf->Cell(20,10,utf8_decode($regdv->mventa),0); 
      $pdf->Cell(25,10,utf8_decode($regdv->comision),0); 
      $pdf->Cell(25,10,utf8_decode($regdv->vcomision),0); 
      $pdf->Cell(30,10,utf8_decode($regdv->mliquido),0); 
      $pdf->Cell(25,10,utf8_decode($regdv->autorizacion),0);   
      $pdf->Cell(25,10,utf8_decode($regdv->ctabanco),0); 
      $pdf->Cell(25,10,utf8_decode($regdv->vflete),0); 
      $pdf->Cell(20,10,utf8_decode($regdv->restan),0);  
      $pdf->Cell(20,10,utf8_decode($regdv->transportes),0);          
      $pdf->Ln(2);

    
      $pdf->Ln(2);
    }
    $pdf->Ln(2);
    $pdf->Cell(190,10,'*****************************************************************************************************************************************************************************************************************************************************');   




$pdf->Output('Carga Excel','I');



 
 
}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}
 
}
ob_end_flush();
?>