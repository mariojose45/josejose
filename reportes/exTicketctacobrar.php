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
 
$rsptav = $cotizaciones->ventacabecera2($_GET["id"]); 
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
    $documento = "NIT: -5";
    $direccion = "";
    $telefono = " +502-";
    $email = "";


?>
<div class="zona_impresion">
<!-- codigo imprimir -->
<br> 
<img src="<?php echo $logo; ?>">
<table border="0" align="center" width="300px">
    <tr>
        <td align="center"><strong>INTEGRACION DE CTAS X COBRAR </strong></td>
    </tr>
    <tr>
        <td align="center">
        <!-- Mostramos los datos de la empresa en el documento HTML -->
        .::<strong> <?php echo $reg->sucursal_nombre; ?></strong>::.<br>
        <?php echo $reg->sucursal_nit; ?><br>
        <?php echo $reg->sucursal_direccion; ?><br>
        <?php echo $reg->sucursal_telefono; ?><br>
        <?php echo $reg->sucursal_email ?><br>
        </td>
    </tr>
    <tr>
        <td align="center">Fecha Operacion: <?php echo $reg->fecha; ?></td>
    </tr>
    <tr>
      <td align="center"></td>
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
        <td><?php echo $reg->tipo_documento.": ".$reg->num_documento; ?></td>
    </tr>
    <tr>
        <td>IdVenta: #<?php echo $reg->idventa ?></td>
    </tr>    
</table>
<br>
<!-- Mostramos los detalles de la venta en el documento HTML -->
<table border="0" align="center" width="300px">
    <tr>
        <td>ABONO.</td>
        <td>FORMA</td>
        <td>FEC, PAG</td>
        <td align="right">SALDO CUENTA</td>
    </tr>
    <tr>
      <td colspan="4">=============================================</td>
    </tr>
    <?php
    $rsptad = $cotizaciones->ventadetalle3($_GET["id"]);
    $totalabonos=0;
    while ($regd = $rsptad->fetch_object()) {
        echo "<tr>";
        echo "<td>".$regd->total_abono."</td>";
        echo "<td>".$regd->tipo_pago;
        echo "<td>".$regd->fechapago;
        echo "<td align='right'>Q/ ".$regd->saldo_venta."</td>";
        echo "</tr>";
         $totalabonos+=$regd->total_abono;
      
    }

    $totalsalgoxpagar=$reg->total_venta-$totalabonos;
    ?> 
    <tr>
      <td colspan="4">=============================================</td>
    </tr>    
    <!-- Mostramos los totales de la venta en el documento HTML -->
    <tr>
    <td  colspan="2"><b>TOTAL ABONOS:</b></td>
    <td>&nbsp;</td>
    <td align="right"><b>Q/  <?php echo $totalabonos;  ?></b></td>
    </tr>
    <tr>
    <td  colspan="2"><b>TOTAL VENTA:</b></td>
    <td>&nbsp;</td>
    <td align="right"><b>Q/  <?php echo $reg->total_venta;  ?></b></td>
    </tr>
    <tr>
    <td  colspan="2"><b>SALDO PENDIENTE X PAGAR:</b></td>
    <td>&nbsp;</td>
    <td align="right"><b>Q/  <?php echo $totalsalgoxpagar;  ?></b></td>
    </tr>    

    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>      
    <tr>
      <td colspan="4" align="center">¡Gracias por su pago!</td>
    </tr>
    <tr>
      <td colspan="4" align="center"><?php echo $reg->sucursal_nombre; ?></td>
    </tr>
    <tr>
      <td colspan="4" align="center">Guatemala, Guatemala</td>
    </tr> 
    <tr>
      <td colspan="4" align="center">Le Atendio: "<?php echo $reg->usuario;  ?>"</td>
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