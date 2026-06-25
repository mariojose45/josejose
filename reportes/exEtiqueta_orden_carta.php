<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1) 
  session_start();

if (!isset($_SESSION["nombre"]))
{
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
}
else
{

//Incluímos el archivo Factura.php
    require('FacturaCotizacionNuevo.php');   

//Establecemos los datos de la empresa



    require_once "../modelos/Admin_ordenes.php";  
    $adminordenes=new AdminOrdenes(); 

    $rsptav = $adminordenes->cabeceranuevaorden($_GET["id"]);  
    //Recorremos todos los valores obtenidos
    $regv = $rsptav->fetch_object();

//Establecemos la configuración de la factura
    $pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
    $pdf->AddPage();


//$pdf->addSociete(utf8_decode($logo,$ext_logo));
//$pdf->addSociete($logo,$ext_logo);
    $pdf->fact_dev( "# Orden Trabajo: ","$regv->num_nueva_orden" );
    $pdf->temporaire( "" );
    $pdf->addDate( $regv->fecha_creacion);


//$pdf->Rect(10,43, 100, 4, 'F'); //Rectángulo relleno y con liena
    $url='../files/articulos/';

    $pdf->SetXY(30,209);
    $pdf->Image($url.$regv->sucursal_imagen,10 ,3, 50 , 35 );
    $pdf->SetFont('Arial','B',25);
    $pdf->SetXY(58,1);
    $pdf->Cell(70,10,"ORDEN TRABAJO",0,0,"C"); 

    $pdf->SetFont('Arial','',10);


    $pdf->SetXY(58,10);
    $pdf->Multicell(75,4,utf8_decode("Direccion:".$regv->direccion_sucursal),0,"C"); 
    $pdf->SetXY(58,20);
    $pdf->Cell(70,10,"Nit:  ".$regv->nit_sucursal,0,0,"C"); 
    $pdf->SetXY(58,25);
    $pdf->Cell(70,10,"Telefono: ".utf8_decode($regv->tels_sucursal),0,0,"C"); 
    $pdf->SetXY(58,30);
    $pdf->Cell(70,10,"Email: ".utf8_decode($regv->correo_sucursal),0,0,"C");   
    $pdf->SetXY(10,67);
    $pdf->Cell(10,10,"Telefono: ".utf8_decode($regv->tels_sucursal),0);  
    $pdf->SetXY(130,42);
    $pdf->Cell(10,10,"Tecnico: ".utf8_decode($regv->nombre_tecnico),0); 



    $pdf->SetTextColor(0,0,0); 
    $pdf->SetXY(10,50);
    $pdf->SetFillColor(128,193,35);
    $pdf->Cell(190,5,".::DATOS CLIENTE::.",1,0,'C',1); 
    $pdf->SetTextColor(0,0,0);  


//Enviamos los datos del cliente al método addClientAdresse de la clase Factura
    $pdf->addClientAdresse(utf8_decode($regv->nombre_cliente),"Domicilio: ".utf8_decode($regv->nombre_direccion),$regv->tipo_documento.": ".$regv->num_documento,"Email: ".$regv->email,"Telefono: ".$regv->nombre_telefono);

//$pdf->addClienteprueba(utf8_decode($regv->cliente));

    $pdf->SetXY(10,80); 
    $pdf->SetTextColor($regv->color_r_texto,$regv->color_g_texto,$regv->color_b_texto); 
    $pdf->SetFillColor($regv->color_r,$regv->color_g,$regv->color_b);
    $pdf->Cell(190,3.5,".::DATOS DE PRODUCTOS::.",1,0,'C',1); 
    $pdf->SetTextColor(0,0,0); 

    $pdf->SetTextColor(0,0,0); 
    $pdf->SetXY(10,85);
    $pdf->Cell(100,3.5,"IMEI: ".$regv->imei_cel,0,0);
    $pdf->SetXY(10,90);
    $pdf->Cell(100,3.5,"MARCA: ".$regv->nombre_marca,0,0);
    $pdf->SetXY(10,95);
    $pdf->Cell(100,3.5,"MODELO: ".$regv->nombre_modelo,0,0);
    $pdf->SetXY(10,100);
    $pdf->Cell(100,3.5,"T/EQUIPO: ".$regv->nombre_tipoequipo,0,0);    
    $pdf->SetXY(100,85);
    $pdf->Cell(100,3.5,"COLOR: ".$regv->nombre_color,0,0);  
    $pdf->SetXY(100,90);
    $pdf->Cell(100,3.5,"ENCIENDE: ".$regv->enciende,0,0); 
    $pdf->SetXY(100,95);
    $pdf->Cell(100,3.5,"GOLPES: ".$regv->golpes,0,0);
    $pdf->SetXY(100,100);
    $pdf->Cell(100,3.5,"PUERTO CARGA: ".$regv->puerto_carga,0,0);   
    $pdf->SetXY(10,105);
    $pdf->Cell(190,5,"PASSWORD: ".$regv->password_orden,1,0);    
    $pdf->SetXY(10,110);
    $pdf->Multicell(190,5,"FALLATA EQUIPO: ".$regv->falla_equipo,0,0);    
    $pdf->Ln(5);
    $pdf->Multicell(190,5,"DIAGNOSTICO: ".$regv->diagnostico_equipo,0,0); 
    $pdf->Ln(5);  
    $pdf->Cell(190,5,"CODIGO INTERNO: ".$regv->codigo_ordennueva,1,1);   
    $pdf->Ln(5);  
    $pdf->Cell(47.5,5,"PRESUPUESTO Q: ".$regv->presupuesto,1,0);                 
    $pdf->Cell(47.5,5,"REPUESTOS Q: ".$regv->repuestos,1,0);
    $pdf->Cell(47.5,5,"ANTICIPO Q: ".$regv->anticipo,1,0);
    $pdf->Cell(47.5,5,"TOTAL Q: ".$regv->total_orden,1,1);
    $pdf->Ln(5);  

    $pdf->SetTextColor($regv->color_r_texto,$regv->color_g_texto,$regv->color_b_texto); 
    $pdf->SetFont('Arial','',8);


    $pdf->Ln(8);
    $pdf->SetTextColor(0,0,0); 
    $pdf->SetFillColor(128,193,35);
    $pdf->Cell(190,5,".::ULTIMA LINEA::.",1,0,'C',1); 
    $pdf->SetTextColor(0,0,0); 




    $pdf->Output('Orden Trabajo No #'.$regv->num_nueva_orden.".pdf",'I');



}
ob_end_flush();
?>