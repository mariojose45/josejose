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

require_once "../modelos/Cheque.php";
//Instanaciamos a la clase con el objeto venta
$cheque = new Cheque();
//En el objeto $rspta Obtenemos los valores devueltos del método ventacabecera del modelo
$rspta = $cheque->pagochequenomina($_GET["id"]);    
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

$conletras=$reg->valor_cheque;     
$conletrasresultado=num2letras($conletras);

    $fecha_dia=date( "d", strtotime( $reg->fechaoperacion ));
    $fecha_mes=date( "m", strtotime( $reg->fechaoperacion ));

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
    $fecha_year=date( "Y", strtotime( $reg->fechaoperacion ));
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
        <br>


    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->

        <td width="25%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Guatemala <?php echo $fecha_dia; ?>&nbsp;de&nbsp;<?php echo $mes; ?>&nbsp;<?php echo $fecha_year; ?></td>
        <td></td>
        <td width="25%"> <?php echo $reg->valor_cheque; ?></td>
    </tr>

    
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $reg->cliente; ?></td>

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
      <td ><?php echo $empresa; ?></td>
    </tr>                
    <tr>
      <td ><?php echo $direccion; ?></td>
    </tr> 
    <tr>
      <td ><?php echo $telefono; ?></td>
    </tr>   

  
    <tr>
      <td>&nbsp; </td>
    </tr> 
    <tr>
      <td align="center"><strong>Integracion de Cheque: </strong></td>
    </tr>              
    <tr>
      <td align="center"><strong>.::******************************************************::.</strong> </td>
    </tr> 
    <tr>
      <td >fecha Creacion Cheque<strong><?php echo $reg->fechaoperacion; ?></strong></td>
    </tr> 
    <tr>
      <td >Valor Cheque<strong><?php echo $reg->valor_cheque; ?></strong></td>
    </tr> 
    <tr>
      <td >Beneficiario<strong><?php echo $reg->cliente; ?></strong></td>
    </tr>
    <tr>
      <td >Descripcion&nbsp;&nbsp;<strong><?php echo $reg->descripcion; ?></strong></td>
    </tr> 
    <tr>
      <td >Nombre Cta Bancaria&nbsp;&nbsp;<strong><?php echo $reg->cta_nombre; ?></strong></td>
    </tr> 
    <tr>
      <td >Cheque No&nbsp;&nbsp;<strong><?php echo "303"; ?></strong></td>
    </tr>                     
    <tr>
      <td align="center"><strong>Datos de Facturacion: </strong></td>
    </tr>              
    <tr>
      <td align="center"><strong>.::******************************************************::.</strong> </td>
    </tr>
    <tr>
      <td >Serie Facturacion<strong>&nbsp;&nbsp;<?php echo $reg->fac_serie; ?></strong></td>
    </tr> 
    <tr>
      <td >Numero Facturacion<strong>&nbsp;&nbsp;<?php echo $reg->fac_documento; ?></strong></td>
    </tr> 
    <tr>
      <td >Fecha Facturacion<strong>&nbsp;&nbsp;<?php echo $reg->fechafactura; ?></strong></td>
    </tr>              
    <tr>
      <td align="center"><strong>&nbsp;&nbsp;<?php echo $rescondicion; ?></strong></td>
    </tr>   
      
    <tr>
      <td align="center"><strong>Recibi Conforme:  </strong></td>
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