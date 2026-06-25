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
    $empresa = "Expressfast Guatemala";
    $documento = "9373125-8";
    $direccion = "Guatemala, Guatemala";
    $telefono = " 502-2235-0770";
    $email = "";

    //Incluímos el archivo Factura.php
    require('Factura.php');
    
    require_once"num2letras.php";

    
    //Obtenemos los datos de la cabecera de la venta actual
    require_once "../modelos/Cheque.php";
    $cheque = new Cheque();
    $rsptav = $cheque->pagocheque($_GET["id"]);  
    //Recorremos todos los valores obtenidos
    $reg = $rsptav->fetch_object();

    
    $pdf = new FPDF('P','mm','A4');
    $pdf->AddPage();
    $pdf->SetFont('helvetica','',10);
    // Salto de línea
    $fecha_dia=date( "d", strtotime( $reg->fechaoperacion));
    $fecha_mes=date( "m", strtotime( $reg->fechaoperacion));

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
    
    $fecha_year=date( "Y", strtotime( $reg->fechaoperacion));
    $condicionn=$reg->condicion;
    switch ($condicionn) {
        case '1':
            # code...
            $rescondicion='Procesado';
            break;
         case '0':
            # code...
            $rescondicion='Anulado';
            break;
    }
    //area de cheque
    $fecha_y=27;
    $pdf->SetXY(25,$fecha_y);
    $pdf->Cell(10,10,'Guatemala');       
    $pdf->SetXY(45,$fecha_y);
    $pdf->Cell(10,10,$fecha_dia,0);
    $pdf->SetXY(50,$fecha_y);
    $pdf->Cell(10,10,'de');      
    $pdf->SetXY(55,$fecha_y);
    $pdf->Cell(10,10,$mes,0);
    $pdf->SetXY(75,$fecha_y);
    $pdf->Cell(10,10,$fecha_year,0);  
    $pdf->SetXY(135,$fecha_y);
    $pdf->Cell(10,10,$reg->valor_cheque,0);  


    //arear de cliente
    $pdf->SetXY(25,33);

 
    //Convertimos el total en letras
    $conletras=$reg->valor_cheque;     
    $conletrasresultado=num2letras($conletras);
    $pdf->SetXY(25,40);
    $pdf->Cell(10,10,$conletrasresultado);      

    //datos de integracion de baucher
    $pdf->SetXY(10,85);    
    $pdf->Cell(10,10,'*************************************************************************************************************************************'); 
    $url='../files/articulos/';

    $pdf->Image($url.$reg->sucursal_imagen,140,90,50,25);

    $pdf->SetXY(10,90);    
    $pdf->Cell(10,10,'Integracion de Cheque: ');   
    $pdf->SetXY(10,95);
    $pdf->Cell(10,10,utf8_decode($reg->sucursal_nombre),0);  
    $pdf->SetXY(10,100);
    $pdf->Cell(10,10,$reg->sucursal_nit);   
    $pdf->SetXY(10,105);
    $pdf->Cell(10,10,utf8_decode($reg->sucursal_direccion));  
    $pdf->SetXY(10,110);
    $pdf->Cell(10,10,$reg->sucursal_telefono); 


    //datos de integracion de baucher
    $pdf->SetXY(10,115);    
    $pdf->Cell(10,10,'*************************************************************************************************************************************'); 

    $pdf->SetXY(10,120);    
    $pdf->Cell(10,10,'fecha Creacion Cheque: '); 
    $pdf->SetXY(50,120);
    $pdf->Cell(10,10,$reg->fechaoperacion,0);  


    $pdf->SetXY(75,120);    
    $pdf->Cell(10,10,'Valor  Cheque: Q'); 
    $pdf->SetXY(105,120);
    $pdf->Cell(10,10,$reg->valor_cheque,0); 

    $pdf->SetXY(10,125);    
    $pdf->Cell(10,10,'Cliente:'); 
    $pdf->SetXY(50,125);
    $pdf->Cell(10,10,$reg->cliente,0);  

    $pdf->SetXY(10,130);    
    $pdf->Cell(10,10,'Descripcion:'); 
    $pdf->SetXY(50,133);
    $pdf->Multicell(150,5,$reg->descripcion,0); 


    //$pdf->SetXY(10,135);    
    //$pdf->Cell(10,10,'Cheque No:'); 
    //$pdf->SetXY(50,135);
    //$pdf->Cell(10,10,$reg->no_cheque,0);                      


    $pdf->SetXY(75,145);    
    $pdf->Cell(10,10,'Nombre Cta Bac:'); 
    $pdf->SetXY(105,145);
    $pdf->Cell(10,10,$reg->cta_nombre,0);  

    $pdf->SetXY(10,155);    
    $pdf->Cell(10,10,'*************************************************************************************************************************************'); 

    $pdf->SetXY(10,160);    
    $pdf->Cell(10,10,'Serie Facturacion:'); 
    $pdf->SetXY(50,160);
    $pdf->Cell(10,10,$reg->fac_serie,0);                      


    $pdf->SetXY(75,160);    
    $pdf->Cell(10,10,'Numero Fac:'); 
    $pdf->SetXY(105,160);
    $pdf->Cell(10,10,$reg->fac_documento,0);  

    $pdf->SetXY(10,165);    
    $pdf->Cell(10,10,'Fecha Facturacion:'); 
    $pdf->SetXY(50,165);
    $pdf->Cell(10,10,$reg->fechafactura,0);                       


    $pdf->SetXY(75,165);    
    $pdf->Cell(10,10,'Numero Fac:'); 
    $pdf->SetXY(105,165); 
    $pdf->Cell(10,10,$rescondicion,0);  

    $pdf->SetXY(75,170);    
    $pdf->Cell(10,10,'Usuario Creacion:'); 
    $pdf->SetXY(105,170);
    $pdf->Cell(10,10,$reg->usuario,0);          

    $pdf->SetXY(10,190);    
    $pdf->Cell(10,10,'*************************************************************************************************************************************');                  


  

    $pdf->Output();
  }
}

?>