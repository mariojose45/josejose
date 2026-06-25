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
<link href="../public/css/ticket.css" rel="stylesheet" type="text/css">
</head>
<body onload="window.print();">
<?php
   
//Incluímos la clase Venta
require_once "../modelos/Cotizaciones.php"; 
$cotizaciones= new Cotizaciones();

$rsptav = $cotizaciones->ventacabeceraNC2($_GET["id"]);  
//Recorremos todos los valores obtenidos
$reg = $rsptav->fetch_object();
 
//Establecemos los datos de la empresa
    $logo = "logo1.jpg";
    $ext_logo = "jpg";
    $logopie = "piepagina.jpg";
    $ext_logopie = "jpg";
    $logovisa="visacuota.jpg";
    $logovisaext = "jpg";
    $empresa = "";
    $documento = "NIT: ";
    $direccion = "";
    $telefono = " +502-";
    $email = "";
    $url='../files/articulos/';

    require_once"num2letras.php";
    //$V=new EnLetras(); 
    //$con_letra=strtoupper($V->ValorEnLetras($regv->total_venta,"QUETZALEZ"));
    $conletras=$reg->total_venta;
     $conletrasresultado=num2letras($conletras);    


?>
<div class="zona_impresion"> 
<!-- codigo imprimir -->
<br> 
<table border="0" align="center" width="300px">
    <tr>
        <td align="center">
        <!-- Mostramos los datos de la empresa en el documento HTML -->
        .::<strong> <?php echo $reg->nombre_comercial; ?></strong>::.<br>
        .::<strong> EMAIL: <?php echo $reg->sucursal_email; ?></strong>::.<br>
        .::<strong> TELS: <?php echo $reg->sucursal_telefono; ?></strong>::.<br>    
        </td>
    </tr>
    <tr>
      <td align="center" colspan="4">==========================================</td>
    </tr>     
    <tr>
        <td align="center"><strong> ENVIO NOTA CREDITO </strong></td>
    </tr>
    
    <tr>
      <td align="center" colspan="4">==========================================</td>
    </tr>             
    <tr>
        <td align="center">Fecha Operacion NC: <?php echo $reg->fecha_hora_nc; ?></td>
    </tr>
    <tr>
        <td align="center"><h1># NC: <?php echo $reg->num_comprobante; ?></h1> </td>
    </tr>    
    <tr>
      <td align="center" colspan="4">==========================================</td>
    </tr> 
    <tr>
        <td align="center"><strong> DATOS CLIENTE </strong></td>
    </tr>    
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td>Cliente: <?php echo $reg->cliente; ?></td>
    </tr>
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td>Direccion: <?php echo $reg->direccion; ?></td>
    </tr>      
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td>Telefono: <?php echo $reg->telefono; ?></td>
    </tr>        
    <tr>
      <td align="center" colspan="3">==========================================</td>
    </tr>
   
</table>
<br>
<!-- Mostramos los detalles de la venta en el documento HTML -->
<table border="0" align="center" width="300px">
    <tr>
        <td>DESCRIPCIÓN</td>
        <td>CANT.</td>
        <td>P.U.</td>
        <td align="right">SUB</td>
    </tr>
    <tr>
      <td colspan="4">==========================================</td>
    </tr>
    <?php
    $rsptad = $cotizaciones->ventadetalleNC2($_GET["id"]);
    $cantidad=0;
    while ($regd = $rsptad->fetch_object()) {
        echo "<tr>";
        echo "<td>".$regd->articulo."/".$regd->presen."</td>";
        echo "<td>".$regd->cantidad."</td>";
        echo "<td>".$regd->q_ref."</td>";
        echo "<td align='right'>Q/ ".$regd->subtotal."</td>";
        echo "</tr>";
        $cantidad+=$regd->cantidad;
    }
    ?> 
    <!-- Mostramos los totales de la venta en el documento HTML -->
    <tr>
    <td align="right"><b>SUBTOTAL:</b></td>        
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><b>Q <?php echo $reg->totalgeneral;  ?></b></td>
    </tr>
    <tr>
    <td align="right"><b>DESCUENTO:</b></td>        
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><b>Q <?php echo $reg->total_ventades;  ?></b></td>
    </tr>
    <tr>
    <td align="right"><b>TOTAL:</b></td>        
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><b>Q <?php echo $reg->total_venta;  ?></b></td>
    </tr>
    <tr>
      <td colspan="4"> <?php echo $conletrasresultado; ?></td>
    </tr>    
    <tr>
      <td colspan="4">==========================================</td>
    </tr> 

    <tr>
      <td colspan="4">Nº de artículos: <?php echo $cantidad; ?></td>
    </tr>

    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>      
    <tr>
      <td colspan="4" align="center">¡Gracias por su compra!</td>
    </tr>
    <tr>
      <td colspan="4" align="center"><?php echo $reg->sucursal_nombre; ?></td>
    </tr>
    <tr>
      <td colspan="4" align="center">Le Atendio: "<?php echo $reg->usuarioNc;  ?>"</td>
    </tr>     
    <tr>
      <td colspan="4">_____________________________________________</td>
    </tr> 
    <tr>
        <td colspan="4" align="center">DOCUMENTOS DE REFERENCIA VENTA</td>
    </tr>  
    <tr>
        <td colspan="4" align="center">Nº de idVenta control interno: #<?php  echo $reg->idventa; ?></td>
    </tr>   
    <tr>
        <td colspan="4" align="center">Fecha Venta: #<?php  echo $reg->fechaVenta; ?></td>
    </tr>  
    <tr>
        <td colspan="4" align="center">MOVITVO NC: #<?php  echo $reg->motivo_nc; ?></td>
    </tr>                 
    <tr>
      <td colspan="4">_____________________________________________</td>
    </tr> 
    <tr>
      <td colspan="4" align="center">Desarrollado por www.compusisgt.comm / Email: info@compusisgt.com / +502 2293-4153 / WhatsApp: +502 5622-2080</td>
    </tr>                
    <tr>
        <td colspan="4" align="center">Nº de NC control interno: #<?php  echo $reg->idnota_credito; ?></td>
    </tr> 
     
     
</table>
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