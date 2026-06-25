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
?>
<html>
<head>


<meta http-equiv="content-type" content="text/html; charset=utf-8" />

</head>
<body onload="window.print();">
<?php
 

require_once"num2letras.php";
//Incluímos la clase Venta

require_once "../modelos/Pagos_empleados.php";
//Instanaciamos a la clase con el objeto venta
$pagoempleado = new Pagoempleados();
//En el objeto $rspta Obtenemos los valores devueltos del método ventacabecera del modelo
$rspta = $pagoempleado->pagocabecera($_GET["id"]);
//Recorremos todos los valores obtenidos
$reg = $rspta->fetch_object();
 
//Establecemos los datos de la empresa
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

$conletras=$reg->sueldo_liquido_recibir;     
$conletrasresultado=num2letras($conletras); 
 
?>
<div >
<!-- codigo imprimir -->
<br>
<table border="0"  width="100%" style="font-size:12px;">
   
    <tr>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center"></td>
    </tr>
    <br>


    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->

        <td width="25%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Guatemala <?php echo $reg->fecha_creacion; ?></td>
                <td width="25%"> <?php echo $reg->sueldo_liquido_recibir; ?></td>
    </tr>

    
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $reg->empleado; ?></td>

    </tr>  
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $conletrasresultado; ?></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr> 
    <tr>
      <td align="center">&nbsp;</td>
    </tr> 

 
    <tr>
      <td align="center">&nbsp;</td>
    </tr> 
    <tr>
      <td align="center">&nbsp;</td>
    </tr>     
    <tr>
      <td align="center">&nbsp;</td>
    </tr>    
    <tr>
      <td align="center"><?php echo $empresa; ?></td>
    </tr>                
    <tr>
      <td align="center"><?php echo $direccion; ?></td>
    </tr> 
    <tr>
      <td align="center"><?php echo $telefono; ?></td>
    </tr>   
    <tr>
      <td align="center">Pago Correspodiente del siguiente rango de fechas Del:<strong><?php echo $reg->fecha_hora_ini; ?></strong> &nbsp;Al&nbsp;<strong><?php echo $reg->fecha_hora_fin; ?></strong> </td>
    </tr>
    <tr>
      <td align="center"><strong>Fecha Operacion: </strong><?php echo $reg->fecha_creacion; ?> </td>
    </tr>       
    <tr>
      <td>&nbsp; </td>
    </tr>   
    <tr>
      <td align="center"><strong>.::DATOS EMPLEADO::.</strong> </td>
    </tr>   
    <tr>
      <td><strong>Nombre Empleado: </strong><?php echo $reg->empleado; ?> </td>
    </tr> 
    <tr>
      <td><strong>DPI Empleado: </strong><?php echo $reg->dpi_no; ?> </td>
    </tr>  
    <tr>
      <td><strong>Codigo Empleado: </strong><?php echo $reg->cod_empleado; ?> </td>
    </tr>
    <tr>
      <td>&nbsp; </td>
    </tr>

    <tr>
      <td align="center"><strong>.::INTEGRACION DE SUELDO::.</strong> </td>
    </tr> 
    <tr>
      <td><strong>->Control Horas</strong> </td>
    </tr>                                                       
    <tr>
      <td><strong>Horas Acumuladas: </strong><?php echo $reg->horas_acumuladas; ?> </td>
    </tr>  
    <tr>
      <td><strong>Horas Tarde: </strong><?php echo $reg->horas_tarde; ?> </td>
    </tr> 
    <tr>
      <td><strong>Horas Extra: </strong><?php echo $reg->horas_extra; ?> </td>
    </tr>    
    <tr>
      <td><strong>Total Horas a Pagar: </strong><?php echo $reg->total_horas_pagar; ?> </td>
    </tr>
    <tr>
      <td>&nbsp; </td>
    </tr>    
    <tr>
      <td><strong>->Desgloce de Sueldo</strong> </td>
    </tr>  
    <tr>
      <td><strong>Valor Hora: </strong><?php echo $reg->valor_hora; ?> </td>
    </tr> 
    <tr>
      <td><strong>Sueldo a Pagar: </strong><?php echo $reg->sueldo_pagar; ?> </td>
    </tr> 
    <tr>
      <td><strong>Bonificacion Extra: </strong><?php echo $reg->bonificacion_extra; ?> </td>
    </tr> 
    <tr>
      <td><strong>Descuentos: </strong><?php echo $reg->descuento; ?> </td>
    </tr>     
    <tr>
      <td><strong>Sueldo Liquido a recibir: </strong><?php echo $reg->sueldo_liquido_recibir; ?> </td>
    </tr>
    <tr>
      <td><strong>Forma pago: </strong><?php echo $reg->forma_pago; ?> </td>
    </tr>  
    <tr>
      <td><strong>Autorizacion No: </strong><?php echo $reg->cheque_auto_no; ?> </td>
    </tr> 
    <tr>
    <tr>
      <td align="center"><strong>.::******************************************************::.</strong> </td>
    </tr>  
    <tr>
      <td align="center"><strong>Prestaciones laborales: </strong></td>
    <tr>
      <td><strong>Rango de fechas  De: </strong><?php echo $reg->fechahorade; ?> Asta: <?php echo $reg->fechahoraasta; ?> </td>
    </tr> 
    <tr>
      <td><strong>Dias Trabajados </strong><?php echo $reg->dtrabajados; ?></td>
    </tr>
    <tr>
      <td><strong>Tipo de pago </strong><?php echo $reg->prestacion_a_pagar; ?></td>
    </tr> 
    <tr>
      <td><strong>Pago prestaciones </strong><?php echo $reg->prestacion_a_sumar; ?></td>
    </tr>             
    <tr>
      <td align="center"><strong>Recibi Conforme: </strong></td>
    </tr>              
    <tr>
      <td align="center"><strong>.::******************************************************::.</strong> </td>
    </tr>                                               
    
</table>
<br>
<br>



<br>
</div>
<p>&nbsp;</p>
 
</body>
</html>
<?php 
}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}
 
}
ob_end_flush();
?>