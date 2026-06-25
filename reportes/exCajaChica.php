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
    require('Factura.php');
    
    require_once"num2letras.php";

    
    //Obtenemos los datos de la cabecera de la venta actual
    require_once "../modelos/caja_chica.php";
    $venta= new CajaChica();
    $rsptav = $venta->ventacabecera($_GET["id"]);
    //Recorremos todos los valores obtenidos
    $regv = $rsptav->fetch_object();
    $url='../files/articulos/';
    //Establecemos la configuración de la factura
    
    $pdf = new FPDF('P','mm','A4');
    $pdf->AddPage();
    $pdf->SetFont('helvetica','',8);
    //Establecemos los datos de la empresa
    $logo = "logouno.jpg";
    $ext_logo = "jpg";
    $logopie = "piepagina.jpg";
    $ext_logopie = "jpg";
    $logovisa="visacuota.jpg";
    $logovisaext = "jpg";
    $empresa = "Expressfast Guatemala";
    $documento = "NIT: 9373581-2";
    $direccion = "Guatemala, Guatemala";
    $telefono = " +502-2235-0770";
    $email = "";
    // Salto de línea
   // $fecha_dia=date( "d", strtotime( $regv->fecha ));
    //$fecha_mes=date( "m", strtotime( $regv->fecha ));
   // $fecha_year=date( "Y", strtotime( $regv->fecha ));

   // $pdf->Ln(4);
   // $pdf->Ln(4); 
   // $pdf->Ln(4);
   // $pdf->Ln(4); 
    $pdf->Ln(4);
    $pdf->Ln(4);
    $pdf->Ln(2);
    $pdf->Ln(4);
    $pdf->Ln(4);
    $pdf->Ln(4);
    $pdf->Image($url.$regv->sucursal_imagen , 5 ,3, 50 , 35);

    $pdf->Cell(45,10,'',0);    
    $pdf->Cell(50,10,'Fecha Inicial Caja',0);
    $pdf->Cell(10,10,$regv->fecha,0);


 

   $pdf->Ln(4);
    $pdf->Cell(15,10,'Usuario:',0);    
    $pdf->Cell(21,10,utf8_decode($regv->usuario),0);
    $pdf->Ln(2);
    $pdf->Ln(4);
    $pdf->Cell(20,10,'Valor Caja Q',0);    
    $pdf->Cell(100,10,utf8_decode($regv->valorcaja),0);

    $pdf->Ln(4);
    $pdf->Ln(5); 
   
  

    $pdf->Rect(10,60, 190, 2, 'F'); //Rectángulo relleno de negro
    $pdf->Ln(4);
    $pdf->Ln(5); 
    //Obtenemos todos los detalles de la venta actual
    $rsptad = $venta->ventadetalle($_GET["id"]);
    while($regdv = $rsptad->fetch_object()){
      $pdf->Cell(10,10,$regdv->IdCajaDetalle,0); 
      $pdf->Cell(10,10,'',0);         
      $pdf->Cell(10,10,$regdv->fechaDetalle,0);
      $pdf->Cell(10,10,'',0);
      $pdf->Cell(80,10,utf8_decode($regdv->descripcion),0);    
      $pdf->Cell(33,10,$regdv->tipo_comprobante,0);      
      $pdf->Cell(20,10,$regdv->valorDetalle,0);
      $pdf->Ln(2);

    
      $pdf->Ln(2);
    }

    $pdf->SetXY(135, 35);
    $pdf->Cell(15,10,"Caja #");    
    $pdf->Cell(10,10,$regv->Id,0);
    $pdf->SetXY(110, 122);
  //  $pdf->Cell(38,10,"Saldo Caja");
  //  $pdf->Cell(10,10,$regv->valorcaja,0);

          $pdf->Ln(4);
//Convertimos el total en letras
//    $conletras=$regv->valorcaja;     
//    $conletrasresultado=num2letras($conletras);
//    $pdf->SetXY(25, 122);
//    $pdf->Cell(10,10,$conletrasresultado);

    $pdf->SetXY(60, 10);
    $pdf->Cell(30,10,$regv->sucursal_nombre,0); 

    $pdf->SetXY(60, 15);
    $pdf->Cell(30,10,$regv->sucursal_nit,0); 

    $pdf->SetXY(60, 20);
    $pdf->Cell(30,10,utf8_decode($regv->sucursal_direccion),0); 

    $pdf->SetXY(10, 50);
    $pdf->Cell(30,10,"Id",0);  

    $pdf->SetXY(35, 50);
    $pdf->Cell(30,10,"Fecha",0); 

    $pdf->SetXY(50, 50);
    $pdf->Cell(30,10,"Descripcion",0);   

    $pdf->SetXY(130, 50);
    $pdf->Cell(30,10,"Tipo Doc",0); 

    $pdf->SetXY(163, 50);
    $pdf->Cell(30,10,"Valor Doc",0);               

    $pdf->SetXY(60, 25);
    $pdf->Cell(30,10,"Cuadro de Caja Chica",0); 
      // 

    $pdf->Output();
  }
}

?>