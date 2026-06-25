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
if ($_SESSION['compras']==1)
{
//Incluímos el archivo Factura.php
    require('Factura3.php');
    require_once"num2letras.php";    
 
//Establecemos los datos de la empresa
    $logo = "logouno.jpg";
    $ext_logo = "jpg";
    $logopie = "piepagina.jpg";
    $ext_logopie = "jpg";
    $logovisa="visacuota.jpg";
    $logovisaext = "jpg";
    $empresa = "TECNOSERVICIOSJIREH";
    $documento = "NIT: 5869008-5";
    $direccion = "10MA AVENIDA 07-03 Z. 01, GUATEMALA";
    $telefono = " +502-2238-3747";
    $email = "mdeleon@tecnoserviciosjireh.com";

  
//Obtenemos los datos de la cabecera de la venta actual
require_once "../modelos/Ingreso.php"; 
$ingreso= new Ingreso();
 
$rsptav = $ingreso->ingresocabecera($_GET["id"]);
//Recorremos todos los valores obtenidos
$regv = $rsptav->fetch_object();
 
//Establecemos la configuración de la factura
$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );  
$pdf->AddPage();
 
$pdf->Image($logo,20,10,80,25);


$pdf->SetFont('Arial','B',8);
$pdf->SetXY(10,35);
$pdf->cell(10,10,"DATOS PROVEEDOR"); 
$pdf->SetXY(10,38);
$pdf->cell(10,10,"Nombre:");
$pdf->SetXY(25,38);
$pdf->cell(10,10,utf8_decode($regv->proveedor)); 
$pdf->SetXY(10,42);
$pdf->cell(10,10,"Direccion:");
$pdf->SetXY(25,42);
$pdf->cell(10,10,utf8_decode($regv->direccion)); 
$pdf->SetXY(10,45);
$pdf->cell(10,10,"Telefono:");
$pdf->SetXY(25,45); 
$pdf->cell(10,10,utf8_decode($regv->telefono)); 
$pdf->SetXY(10,48);
$pdf->cell(10,10,"Nit:");
$pdf->SetXY(25,48);
$pdf->cell(10,10,utf8_decode($regv->num_documento)); 
$pdf->SetXY(10,51);
$pdf->cell(10,10,"Correo:");
$pdf->SetXY(25,51);
$pdf->cell(10,10,utf8_decode($regv->email)); 


$pdf->SetFont('Arial','B',8);
$pdf->SetXY(120,10);
$pdf->cell(10,10,"SERIE:"); 
$pdf->SetXY(140,10);
$pdf->cell(10,10,utf8_decode($regv->serie_comprobante)); 
$pdf->SetXY(120,15);
$pdf->cell(10,10,"NO:"); 
$pdf->SetXY(140,15);
$pdf->cell(10,10,utf8_decode($regv->num_comprobante)); 
$pdf->SetXY(120,35);
$pdf->cell(10,10,"DATOS FACTURACION"); 
$pdf->SetXY(120,38);
$pdf->cell(10,10,"Nombre:");
$pdf->SetXY(135,38);
$pdf->cell(10,10,utf8_decode($empresa)); 
$pdf->SetXY(120,42);
$pdf->cell(10,10,"Direccion:");
$pdf->SetXY(135,45);
$pdf->MultiCell(70,5,utf8_decode($direccion)); 
$pdf->SetXY(120,51);
$pdf->cell(10,10,"Telefono:");
$pdf->SetXY(135,51); 
$pdf->cell(10,10,utf8_decode($telefono)); 
$pdf->SetXY(120,55);
$pdf->cell(10,10,"Nit:");
$pdf->SetXY(135,55);
$pdf->cell(10,10,utf8_decode($documento)); 
$pdf->SetXY(120,59);
$pdf->cell(120,10,"Correo:");
$pdf->SetXY(135,59);
$pdf->cell(10,10,utf8_decode($email)); 

//$pdf->SetFont('Arial','B',10);
//Enviamos los datos del cliente al método addClientAdresse de la clase Factura
//$pdf->addClientAdresse(utf8_decode($regv->proveedor),$regv->direccion,"TELEFONO: ".$regv->telefono);

//$pdf->addClienteprueba(utf8_decode($regv->cliente));
$pdf->SetXY(10,62);
$pdf->cell(120,10,"Direccion de Entrega:");
$pdf->SetXY(45,62);
$pdf->cell(10,10,utf8_decode($regv->direccion_entrega_orden_compra)); 
$pdf->SetXY(10,65);
$pdf->cell(120,10,"Fecha de Entrega:"); 
$pdf->SetXY(45,65);
$pdf->cell(10,10,utf8_decode($regv->fechaentregaordencompra)); 
$pdf->SetXY(10,68);
$pdf->cell(120,10,"Observaciones:"); 
$pdf->SetXY(45,72);
$pdf->Multicell(155,4,$regv->observacion_orden_compra,0);  

//Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta

     $pdf->SetXY(10,80);    
     $pdf->Cell(10,10,'*********************************************************************************************************************************************************************************'); 
      $pdf->SetXY(10,85);
      $pdf->cell(120,10,"CANTIDAD"); 

      $pdf->SetXY(30,85);
      $pdf->cell(120,10,"CODIGO");  

      $pdf->SetXY(75,85);
      $pdf->cell(120,10,"DESCRIPCION");              

      $pdf->SetXY(155,85);
      $pdf->cell(120,10,"PRECIO COMPRA");                    

      $pdf->SetXY(185,85);
      $pdf->cell(120,10,"SUB TOTAL");                          
         
     $pdf->SetXY(10,90);    
     $pdf->Cell(10,10,'*********************************************************************************************************************************************************************************');      
      $pdf->Ln(2);
      $pdf->Ln(2);
    $rsptad = $ingreso->ingresodetalle($_GET["id"]);
    while($regdv = $rsptad->fetch_object()){

      $pdf->Cell(5,10,$regdv->cantidad,0);
      $pdf->Cell(15,10,'',0);
      $pdf->Cell(45,10,utf8_decode($regdv->codigo),0);        
      $pdf->Cell(80,10,utf8_decode($regdv->articulo),0);          
      $pdf->Cell(30,10,"Q".$regdv->precio_compra,0);           
      $pdf->Cell(30,10,$regdv->subtotal,0);
      $pdf->Ln(2);

    
      $pdf->Ln(2);
    }

$nombreusuario=$regv->usuario;
$fechacreacion=$regv->fecha;
$pdf->SetXY(10,250);
$pdf->cell(10,10,"USUARIO CREACION:  ".$nombreusuario);
$pdf->SetXY(10,255);
$pdf->cell(10,10,"FECHA:  ".$fechacreacion);

$conletras=$regv->total_compra;     
$conletrasresultado=num2letras($conletras);
$pdf->SetXY(10,240);
$pdf->Cell(10,10,$conletrasresultado);
$pdf->SetXY(175,240);
$pdf->Cell(40,10,"Q".$regv->total_compra,0); 


$pdf->Output('Cotizacion','I');



 
 
}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}
 
}
ob_end_flush();
?>