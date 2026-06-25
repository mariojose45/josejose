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
    $documento = "NIT 1111111-1";
    $direccion = "10MA AVE. 07-03 ZONA 01, GUATEMALA, GUATEMALA";
    $telefono = " 2238-3747 / 34775001";
    $email = "mdeleon@tecnoserviciosjireh.com";

    //Incluímos el archivo Factura.php
    require('Factura.php');
    
    require_once"num2letras.php";

    
    //Obtenemos los datos de la cabecera de la venta actual
    require_once "../modelos/Pagos_empleados.php";
    $pagoempleado = new Pagoempleados();
    //En el objeto $rspta Obtenemos los valores devueltos del método ventacabecera del modelo
    $rsptav = $pagoempleado->pagocabecera($_GET["id"]);  
    //Recorremos todos los valores obtenidos
    $reg = $rsptav->fetch_object();

    
    $pdf = new FPDF('P','mm','A4');
    $pdf->AddPage();
    $pdf->SetFont('helvetica','',10); 
    // Salto de línea
    $fecha_dia=date( "d", strtotime( $reg->fecha_creacion));
    $fecha_mes=date( "m", strtotime( $reg->fecha_creacion));

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
    $fecha_year=date( "Y", strtotime( $reg->fecha_creacion));


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
    $pdf->Cell(10,10,$reg->sueldo_liquido_recibir,0);  


    //arear de cliente
    $pdf->SetXY(25,33);
    $pdf->Cell(10,10,utf8_decode($reg->empleado),0);   
    //Convertimos el total en letras
    $conletras=$reg->sueldo_liquido_recibir;     
    $conletrasresultado=num2letras($conletras);
    $pdf->SetXY(25,40);
    $pdf->Cell(10,10,$conletrasresultado);      

    //datos de integracion de baucher
    $pdf->SetXY(10,85);    
    $pdf->Cell(10,10,'*************************************************************************************************************************************'); 

    $pdf->Image($logo,140,90,75,25);

    $pdf->SetXY(10,90);    
    $pdf->Cell(10,10,'Voucher de pago: ');   
    $pdf->SetXY(10,95);
    $pdf->Cell(10,10,$empresa);  
    $pdf->SetXY(10,100);
    $pdf->Cell(10,10,$documento);   
    $pdf->SetXY(10,105);
    $pdf->Cell(10,10,$direccion);  
    $pdf->SetXY(10,110);
    $pdf->Cell(10,10,$telefono); 


    //datos de integracion de baucher
    $pdf->SetXY(10,115);    
    $pdf->Cell(10,10,'*************************************************************************************************************************************'); 

    $pdf->SetXY(10,120);    
    $pdf->Cell(10,10,'fecha Creacion Cheque: '); 
    $pdf->SetXY(50,120);
    $pdf->Cell(10,10,$reg->fecha_creacion,0);  


    $pdf->SetXY(75,120);    
    $pdf->Cell(10,10,'Valor  Cheque: Q'); 
    $pdf->SetXY(105,120);
    $pdf->Cell(10,10,$reg->sueldo_liquido_recibir,0); 

    $pdf->SetXY(10,125);    
    $pdf->Cell(10,10,'Colaborador:'); 
    $pdf->SetXY(50,125);
    $pdf->Cell(10,10,utf8_decode($reg->empleado),0);  

    $pdf->SetXY(10,130);    
    $pdf->Cell(10,10,'Cta Bancaria:'); 
    $pdf->SetXY(50,130);
    $pdf->Cell(10,10,utf8_decode($reg->num_cta),0);  

    $pdf->SetXY(75,130);    
    $pdf->Cell(10,10,'Tipo Pago:'); 
    $pdf->SetXY(95,130);
    $pdf->Cell(10,10,utf8_decode($reg->forma_pago),0);    

    $pdf->SetXY(120,130);    
    $pdf->Cell(10,10,'Cheque No./ Autorizacion No:'); 
    $pdf->SetXY(170,130);
    $pdf->Cell(10,10,utf8_decode($reg->cheque_auto_no),0);          

    $pdf->SetXY(10,140);    
    $pdf->Cell(10,10,'*************************************************************************************************************************************'); 

    $pdf->SetXY(10,145);    
    $pdf->Cell(10,10,'Pago Correspodiente del siguiente rango de fechas:'); 
 
    $pdf->SetXY(10,150);    
    $pdf->Cell(10,10,'Fecha Inicio:'); 
    $pdf->SetXY(50,150);
    $pdf->Cell(10,10,$reg->fecha_hora_ini,0);  

    $pdf->SetXY(10,155);    
    $pdf->Cell(10,10,'Fecha Fin:'); 
    $pdf->SetXY(50,155);
    $pdf->Cell(10,10,$reg->fecha_hora_fin,0);    

    $pdf->SetXY(10,160);    
    $pdf->Cell(10,10,'DPI Empleado:'); 
    $pdf->SetXY(50,160);
    $pdf->Cell(10,10,$reg->dpi_no,0);   

    $pdf->SetXY(10,165);    
    $pdf->Cell(10,10,'Telefono Empleado:'); 
    $pdf->SetXY(50,165);
    $pdf->Cell(10,10,$reg->telefono,0);  

    $pdf->SetXY(10,170);    
    $pdf->Cell(10,10,'Codigo Empleado:'); 
    $pdf->SetXY(50,170);
    $pdf->Cell(10,10,$reg->cod_empleado,0);                                          

//////////////////////datos de horas 
    $pdf->SetXY(100,150);    
    $pdf->Cell(10,10,'Horas Acumuladas:'); 
    $pdf->SetXY(140,150);
    $pdf->Cell(10,10,$reg->horas_acumuladas,0);  

    $pdf->SetXY(100,155);    
    $pdf->Cell(10,10,'Horas Tarde:'); 
    $pdf->SetXY(140,155);
    $pdf->Cell(10,10,$reg->horas_tarde,0);    

    $pdf->SetXY(100,160);    
    $pdf->Cell(10,10,'Horas Extra:'); 
    $pdf->SetXY(140,160);
    $pdf->Cell(10,10,$reg->horas_extra,0);   

    $pdf->SetXY(100,165);    
    $pdf->Cell(10,10,'Valor Hora Q:'); 
    $pdf->SetXY(140,165);
    $pdf->Cell(10,10,$reg->valor_hora,0);  

    $pdf->SetXY(100,170);    
    $pdf->Cell(10,10,'Bonificacion de Ley Q:'); 
    $pdf->SetXY(140,170);
    $pdf->Cell(10,10,$reg->bonificacion,0);   

    $pdf->SetXY(10,175);    
    $pdf->Cell(10,10,'Observaciones:'); 
    $pdf->SetXY(36,179);
    $pdf->Multicell(155,4,$reg->descripcion,0);             

    $pdf->SetXY(10,190);     
    $pdf->Cell(10,10,'*************************************************************************************************************************************');                  


  

    $pdf->Output();
  }
}

?>