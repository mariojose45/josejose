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
        .::<strong> <?php echo $reg->nombre_fel; ?></strong>::.<br>
        .::<strong>Nit:  <?php echo $reg->sucursal_nit; ?></strong>::.<br>
        .::<strong> <?php echo $reg->direccion_fiscal; ?></strong>::.<br>
        .::<strong> Email: <?php echo $reg->sucursal_email; ?></strong>::.<br>
        .::<strong> Tels: <?php echo $reg->sucursal_telefono; ?></strong>::.<br>    
        </td>
    </tr>

    <tr>
      <td align="center" colspan="4">==========================================</td>
    </tr>         
    <tr>
        <td align="center"><strong> FACTURA CAMBIARIA </strong></td>
    </tr>
    <tr>
        <td align="center"><strong> DOCUMENTO TRIBUTARIO ELECTRONICO </strong></td>
    </tr> 
    <tr>
        <td align="center">NUMERO DE AUTORIZACION:</td>
    </tr>    
    <tr>
        <td align="center"> <?php echo $reg->autorizacionEcoFactura; ?></td>
    </tr> 
    <tr>
        <td >SERIE: <?php echo $reg->serie_ecoFactura; ?></td>

    </tr>  
    <tr>
        <td >NUMERO: <?php echo $reg->numero_ecoFactura; ?></td>

    </tr>    
    <tr>
        <td align="center">Fecha Emision: <?php echo date("d/m/Y", strtotime($reg->fecha)); ?></td>
    </tr>

    <tr>
      <td align="center" colspan="4">==========================================</td>
    </tr>             

    <tr>
        <td align="center"># Venta: <?php echo $reg->num_comprobante; ?></td>
    </tr>    
    <tr>
      <td align="center" colspan="4">==========================================</td>
    </tr> 
    <tr>
        <td align="center"><strong> Por medio de esta única factura cambiaria se servirá usted pagar a orden o endoso de <?php echo $reg->nombre_fel; ?>, la suma de acuerdo a las condiciones que se establecen en el presente título, en concepto de servicios y mercadería que acepta haber recibido a entera satisfacción conforme al detalle siguiente. </strong></td>
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
        <td>Nit: <?php echo $reg->num_documento; ?></td>
    </tr>      
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td>Telefono: <?php echo $reg->telefono; ?></td>
    </tr>        
    <tr>
      <td align="center" colspan="4">==========================================</td>
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
    $rsptad = $cotizaciones->ventadetalle2($_GET["id"]);
    $cantidad=0;
    while ($regd = $rsptad->fetch_object()) {
        echo "<tr>";
        echo "<td>".$regd->articulo."/".$regd->presen."/".$regd->descripcion_detalle."</td>";
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
      <td colspan="4"> a) AL CANCELAR ESTA FACTURA SIRVASE EXIGIR SU RECIBO COMO UNICO COMPROBANTE DE PAGO.<br>
    b) POR CADA CHEQUE RECHAZADO SE COBRARA Q. 150.00.<br>
    c) ESTA FACTURA ES EXIGIBLE A SU VENCIMIENTO Y CAUSA INTERRES DE MORA AL 3% MENSUAL.<br>
    d) EL COMPRADOR DA COMO CORRECTO EL VALOR TOTAL DE ESTA FACTURA CAMBIARIA LIBRE DE PROTESTO Y SE COMPROMETE A CANCELAR AL VENCIMIENTO AL
    VENDEDOR O ATRAVES DE UNA ENTIDAD BANCARIA QUE ESTE NOMBRE, EN CASO DE INCUMPLIMIENTO EL COMPRADOR RENUNCIA AL FUERO DE SU DOMICILIO Y SE
    SOMETE A LOS TRIBUNALES DEL DEPARTAMENTO DE GUATEMALA, SEÑALANDO PARA RECIBIR Y NOTIFICACIONES, CITACIONES O EMPLAZAMIENTO LA DIRECCION ACTUAL
    DE SU NEGOCIO, SALVO AVISO DE CAMBIO QUE DIERE POR ESCRITO.</td>
    </tr>  
    <tr>
      <td colspan="4">_____________________________________________</td>
    </tr> 
    <tr>
      <td colspan="4"> A:  <?php echo $reg->fechahoravencimientofactura; ?>
    (EN LAS CONDICIONES ESTIPULADAS EN ESTE
    TITULO) SE SERVIRAN USTEDES PAGAR POR ESTA
    UNICA FACTURA CAMBIARIA GIRADA LIBRE DE
    PROTESTO A LA ORDEN O ENDOSO DE $regv->nombre_fel EN EL VALOR
    TOTAL POR EL QUE FUE EXTENDIDA O POR EL
    ULTIMO SALDO INSOLUTO QUE APAREZCA, VALOR
    RECIBIDO QUE ASENTARAN USTEDES SEGUN
    NUESTRO AVISO.</td>
    </tr>  


    <tr>
      <td colspan="4">==========================================</td>
    </tr>     
    <tr>
      <td colspan="4"># Abonos <?php echo $reg->numero_pagos; ?></td>
    </tr>  
    <tr>
      <td colspan="4">Fecha Vencimiento <?php echo $reg->fechahoravencimientofactura; ?></td>
    </tr>  
    <tr>
      <td colspan="4">Monto <?php echo $reg->monto_abono; ?></td>
    </tr>          
    <tr>
      <td align="center" colspan="4">==========================================</td>
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
      <td colspan="4" align="center">Le Atendio: "<?php echo $reg->usuario;  ?>"</td>
    </tr> 

    <tr>
      <td colspan="4" align="center">Vendedor: "<?php echo $reg->nombre_vendedor;  ?>"</td>
    </tr> 

    <tr>
      <td colspan="4" align="center"><?php echo $reg->dato_sat;  ?></td>
    </tr>      
    <tr>
      <td colspan="4">_____________________________________________</td>
    </tr> 
    <tr>
      <td colspan="4" align="center"> <?php echo $reg->nombrecertificador;  ?></td>
    </tr>
    <tr>
      <td colspan="4">_____________________________________________</td> 
    </tr> 
    <tr>
      <td colspan="4" align="center"> <?php echo $reg->empresadesarrollo;  ?></td>
    </tr>                
    <tr>
        <td colspan="4" align="center">Nº de venta control interno: #<?php  echo $reg->idventa; ?></td>
    </tr> 
    <tr>
      <td colspan="4" align="center"><h1>Efectivo: "<?php echo $reg->cefectivo;  ?>"</h1> </td>
    </tr>  
    <tr>
      <td colspan="4" align="center"><h1>Cambio: "<?php echo $reg->rescambio;  ?>"</td>
    </tr>   

    <tr>
      <td colspan="4" align="center">LIBRADOR</td>
    </tr>  
    <tr>
      <td colspan="4">_____________________________________________</td>
    </tr> 
    <tr>
      <td colspan="4" align="center">LUGAR Y FECHA ACEPTACION</td>
    </tr>  
    <tr>
      <td colspan="4">_____________________________________________</td>
    </tr> 
    <tr>
      <td colspan="4" align="center">FIRMA Y SELLO DE ACEPTANTE Y/O REPRESENTANTE APARENTE RECIBI CONFORME LA MERCADERIA</td>
    </tr>  
    <tr>
      <td colspan="4">_____________________________________________</td>
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