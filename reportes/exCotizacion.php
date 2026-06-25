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
if ($_SESSION['cotizaciones']==1)
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

$rsptav = $cotizaciones->cotizacioncabecera($_GET["id"]); 
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


?>
<div class="zona_impresion"> 
<!-- codigo imprimir -->
<br> 
<table border="0" align="center" width="300px">
    <tr>
        <td align="center">
        <!-- Mostramos los datos de la empresa en el documento HTML -->
        .::<strong> <?php echo $reg->sucursal_nombre; ?></strong>::.<br>
        Correo: <?php echo $reg->sucursal_email ?><br>
        Usuario Creacion: <?php echo $reg->usuario ?><br>
        Forma pago:<?php echo $reg->forma_pago; ?><br>        
        </td>
    </tr>
    <tr>
        <td align="center">Fecha Operacion: <?php echo $reg->fecha; ?></td>
    </tr>
    <tr>
        <td align="center"><h1>Cotizacion #: <?php echo $reg->num_comprobante; ?></h1> </td>
    </tr>    
    <tr>
      <td align="center" colspan="4">==========================================</td>
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
    $rsptad = $cotizaciones->cotizaciondetalle($_GET["id"]);
    $productos = [];
    $toppings = [];
    $cantidad = 0;
    $articulos_principales = [];
    $articulos_hijos = [];

    while ($regd = $rsptad->fetch_object()) {
        if ($regd->idarticulopadre == 0) {
            $articulos_principales[] = $regd;
        } else {
            $articulos_hijos[] = $regd;
        }
    }


    /*
    while ($regd = $rsptad->fetch_object()) {
      if ($regd->tipo == 'Topping') {
          $toppings[] = $regd;
      } else {
          $productos[] = $regd;
      }
    }
    */

    /*
    while ($regd = $rsptad->fetch_object()) {
        echo "<tr>"; 
        echo "<td>".$regd->articulo." ".$regd->descripcion_detalle."</td>";
        echo "<td>".$regd->cantidad."</td>";
        echo "<td>".$regd->q_ref."</td>";
        echo "<td align='right'>Q/ ".$regd->subtotal."</td>";
        echo "</tr>";
        $cantidad+=$regd->cantidad;
    }
    */
      foreach ($articulos_principales as $p) {
        // Imprimir el artículo principal
        echo "<tr>";
        echo "<td class='desc'>".$p->articulo." / ".$p->presen." / ".$p->descripcion_detalle."</td>";
        echo "<td class='cant'>".$p->cantidad."</td>";
        echo "<td class='pu'>".number_format($p->q_ref, 2)."</td>";
        echo "<td class='sub'>Q ".number_format($p->subtotal, 2)."</td>";
        echo "</tr>";
        
        foreach ($articulos_hijos as $top) {
            if ($top->idarticulopadre == $p->idarticulo) {
              print_r($top->idarticulopadre);
              print_r($p->idarticulo);
                echo "<tr>";
                echo "<td class='desc topping' colspan='4'>↳ ".$top->articulo."</td>";
                echo "</tr>";
            }
        }

        $cantidad += $p->cantidad;
      }
    ?>
    <tr>
      <td colspan="4">==========================================</td>
    </tr>     
    <!-- Mostramos los totales de la venta en el documento HTML -->
    <tr>
    <td align="right"><b>SUB TOTAL:</b></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><b>Q/  <?php echo $reg->total_general;  ?></b></td>
    </tr>
    <tr>
    <td align="right"><b>TOTAL DES:</b></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><b>Q/  <?php echo $reg->total_ventades;  ?></b></td>
    </tr>
    <tr>
    <td align="right"><b>TOTAL:</b></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><b>Q/  <?php echo $reg->total_venta;  ?></b></td>
    </tr>        
    <tr>
      <td colspan="4">Nº de artículos: <?php echo $cantidad; ?></td>
    </tr>
    <tr>
      <td colspan="4">Forma Pago: <?php echo $reg->forma_pago; ?></td>
    </tr>  
    <tr>
      <td colspan="4">Cobrado: <?php echo $reg->cobradosino; ?></td>
    </tr>        
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>      
    <tr>
      <td colspan="4" align="center">¡Gracias por su Cotizacion!</td>
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
    <tr>
      <td colspan="4" align="center">Vendedor: "<?php echo $reg->nombre_vendedor;  ?>"</td>
    </tr> 

    <tr>
        <td align="center" colspan="4"><h1># Interno: <?php echo $reg->idcotizacion; ?></h1> </td>
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