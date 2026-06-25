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
    $logo = "logo.jpg";
    $ext_logo = "jpg";
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

    $fecha_dia=date( "d", strtotime($regv->fecha ));
    $fecha_mes=date( "m", strtotime($regv->fecha ));

    switch ($fecha_mes) {
        case '1':
            # code...
            $mes='Enero';
            break;
         case '2':
            # code...
            $mes='Febrero';
            break;  
         case '3':
            # code...
            $mes='Marzo';
            break; 
         case '4':
            # code...
            $mes='Abril';
            break; 
         case '5':
            # code...
            $mes='Mayo';
            break;  
        case '6':
            # code...
            $mes='Junio';
            break;
         case '7':
            # code...
            $mes='Julio';
            break;  
         case '8':
            # code...
            $mes='Agosto';
            break; 
         case '9':
            # code...
            $mes='Septiembre';
            break; 
         case '10':
            # code...
            $mes='Octubre';
            break;  
         case '11':
            # code...
            $mes='Noviembre';
            break;  
         case '12':
            # code...
            $mes='Diciembre';
            break;                                                                              
        default:
            # code...
            break;
    }
    $fecha_year=date( "Y", strtotime($regv->fecha ));



 
    $pdf->SetFont('Arial','B',9);

    $pdf->SetXY(150,31);
    $pdf->Cell(10,10,$fecha_dia.'              '.$mes.'                  '.$fecha_year,0);
   /* $pdf->SetXY(150,50);
    $pdf->Cell(10,10,"Envio #  ".$regv->idventa,0);*/

    $pdf->SetXY(35,39);
    $pdf->Cell(10,10,utf8_decode($regv->cliente),0);  
    $pdf->SetXY(35,47);
    $pdf->Cell(10,10,$regv->direccion,0); 
    $pdf->SetXY(165,47);
    $pdf->Cell(10,10,$regv->num_documento,0);  
 

    $pdf->SetFont('Arial','B',6);
    $pdf->Ln(2);
    $pdf->Ln(2); 
    $pdf->Ln(2);            
    $pdf->Ln(6);

    $rsptad = $cotizaciones->ventadetalle2($_GET["id"]);
    while($regdv = $rsptad->fetch_object()){
      $pdf->Cell(8,10," ",0);  
      $pdf->Cell(8,10,$regdv->cantidad,0);  
      $pdf->Cell(140,10,utf8_decode($regdv->articulo." ".$regdv->descripcion_detalle),0);     
      $pdf->Cell(22,10,$regdv->precio_venta,0);               
      $pdf->Cell(20,10,$regdv->subtotal,0);
      $pdf->Ln(2);

    
      $pdf->Ln(2);
    }


    require_once"num2letras.php";
    $conletras=$regv->total_venta;
    $conletrasresultado=num2letras($conletras);
    $pdf->SetXY(35,112);
    $pdf->Cell(10,10,$conletrasresultado.'QUETZALEZ',0);  
    $pdf->SetXY(185,112);
    $pdf->Cell(10,10,$regv->total_venta,0);         





    $pdf->Output('Reporte de Venta','I');

 
}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}
 
}
ob_end_flush();
?>