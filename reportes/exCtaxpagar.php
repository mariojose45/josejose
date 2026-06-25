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

    //Incluímos el archivo Factura.php
    require('Factura.php');
    
    require_once"num2letras.php";

    
    //Obtenemos los datos de la cabecera de la venta actual
    require_once "../modelos/Cheque.php";
    $cheque = new Cheque();
    $rsptav = $cheque->pagochequenomina2($_GET["id"]);  
    //Recorremos todos los valores obtenidos
    $reg = $rsptav->fetch_object();

    
    $pdf = new FPDF('P','mm','A4');
    $pdf->AddPage();
    $pdf->SetFont('helvetica','',10); 
    // Salto de línea

    $pdf->SetXY(10,10);    
    $pdf->Cell(10,10,'.::CUENTA POR PAGAR::. '); 
    $pdf->SetXY(10,20);
    $pdf->Cell(10,10,$empresa);  
    $pdf->SetXY(10,25);
    $pdf->Cell(10,10,$documento);   
    $pdf->SetXY(10,30);
    $pdf->Cell(10,10,$direccion);  
    $pdf->SetXY(10,35);
    $pdf->Cell(10,10,$telefono);      
    //datos de integracion de baucher
    $pdf->SetXY(10,45);    
    $pdf->Cell(10,10,'*************************************************************************************************************************************'); 

    $pdf->Image($logo,120,5,75,25);

    $pdf->SetXY(10,55);    
    $pdf->Cell(10,10,'Forma Pago: '); 
    $pdf->SetXY(50,55);
    $pdf->Cell(10,10,$reg->tipo_pago); 

    $pdf->SetXY(10,60);    
    $pdf->Cell(10,10,'Fecha Creacion Pago: '); 
    $pdf->SetXY(50,60);
    $pdf->Cell(10,10,$reg->fecha_hora_generacion_pago);    

    $pdf->SetXY(10,65);    
    $pdf->Cell(10,10,'Valor Pago : '); 
    $pdf->SetXY(50,65);
    $pdf->Cell(10,10,$reg->valor_pagar); 

    $pdf->SetXY(10,70);    
    $pdf->Cell(10,10,'Cliente: '); 
    $pdf->SetXY(50,70);
    $pdf->Cell(10,10,$reg->cliente);  

    $pdf->SetXY(90,70);    
    $pdf->Cell(10,10,'Saldo por pagar: '); 
    $pdf->SetXY(120,70);
    $pdf->Cell(10,10,$reg->saldo_ingreso);      

           

    //datos de integracion de baucher
    $pdf->SetXY(10,75);    
    $pdf->Cell(10,10,'*************************************************************************************************************************************'); 

    $pdf->SetXY(10,80);    
    $pdf->Cell(10,10,'Serie Facturacion: '); 
    $pdf->SetXY(50,80);
    $pdf->Cell(10,10,$reg->serie_comprobante); 

    $pdf->SetXY(10,85);    
    $pdf->Cell(10,10,'Numero Facturacion: '); 
    $pdf->SetXY(50,85);
    $pdf->Cell(10,10,$reg->num_comprobante);    

    $pdf->SetXY(10,90);    
    $pdf->Cell(10,10,'Fecha Facturacion: '); 
    $pdf->SetXY(50,90);
    $pdf->Cell(10,10,$reg->fechafac); 

    $pdf->SetXY(10,95);    
    $pdf->Cell(10,10,'Usuario Creacion: '); 
    $pdf->SetXY(50,95);
    $pdf->Cell(10,10,$reg->usuario);              

    $pdf->SetXY(10,135);    
    $pdf->Cell(10,10,'*************************************************************************************************************************************'); 

         
                


  

    $pdf->Output();
  }
}

?>