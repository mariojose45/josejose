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
        .::<strong>NIT:  <?php echo $reg->sucursal_nit; ?></strong>::.<br>
        .::<strong> <?php echo $reg->direccion_fiscal; ?></strong>::.<br>
        .::<strong> EMAIL: <?php echo $reg->sucursal_email; ?></strong>::.<br>
        .::<strong> TELS: <?php echo $reg->sucursal_telefono; ?></strong>::.<br>    
        </td>
    </tr>
    <tr>
      <td align="center" colspan="4">==========================================</td>
    </tr>     
    <tr>
        <td align="center"><strong> FACTURA </strong></td>
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
        <td >NUMERO: <?php echo $reg->numero_ecoFactura; ?></td>

    </tr>    

    <tr>
        <td >SERIE: <?php echo $reg->serie_ecoFactura; ?></td>

    </tr> 
   
    <tr>
        <td align="center">Fecha Emision:</td>
    </tr> 
    <tr>
        <td align="center"> <?php echo date("d/m/Y", strtotime($reg->fecha)); ?></td>
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
  $productos = [];
  $toppings = [];
  $cantidad = 0;

  // 1️⃣ Separar productos y toppings
  while ($regd = $rsptad->fetch_object()) {
      if ($regd->tipo == 'Topping') {
          $toppings[] = $regd;
      } else {
          $productos[] = $regd;
      }
  }

  // 2️⃣ Mostrar productos principales (tipo 0 y Extra)
  foreach ($productos as $p) {
      echo "<tr>";
      echo "<td class='desc'>".$p->articulo." / ".$p->presen." / ".$p->descripcion_detalle."</td>";
      echo "<td class='cant'>".$p->cantidad."</td>";
      echo "<td class='pu'>".number_format($p->q_ref, 2)."</td>";
      echo "<td class='sub'>Q ".number_format($p->subtotal, 2)."</td>";
      echo "</tr>";

      // 3️⃣ Buscar si tiene toppings relacionados
      foreach ($toppings as $top) {
          if ($top->idarticulopadre == $p->idarticulo) {
              echo "<tr>";
              echo "<td class='desc topping' colspan='4'>↳ ".$top->articulo."</td>";
              echo "</tr>";
          }
      }

      $cantidad += $p->cantidad;
  }
  ?>
    <!-- Mostramos los totales de la venta en el documento HTML -->
    <tr>
    <td align="right"><b>SUBTOTAL Q:</b></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><b><?php echo $reg->totalgeneral;  ?></b></td>
    </tr>
    <tr>
    <td align="right"><b>DESCUENTO Q:</b></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><b><?php echo $reg->total_ventades;  ?></b></td>
    </tr>
    <tr>
    <td align="right"><b>TOTAL Q:</b></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><b><?php echo $reg->total_venta;  ?></b></td>
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
      <td colspan="4" align="center"><?php echo $reg->nombrecertificador;  ?></td>
    </tr>
    <tr>
      <td colspan="4">_____________________________________________</td>
    </tr> 
    <tr>
      <td colspan="4" align="center"><?php echo $reg->empresadesarrollo;  ?></td>
    </tr>                
    <tr>
        <td colspan="4" align="center">Nº de venta: #<?php  echo $reg->num_comprobante; ?></td>
    </tr> 
    <tr>
      <td colspan="4" align="center"><h1>Efectivo: "<?php echo $reg->cefectivo;  ?>"</h1> </td>
    </tr>  
    <tr>
      <td colspan="4" align="center"><h1>Cambio: "<?php echo $reg->rescambio;  ?>"</td>
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