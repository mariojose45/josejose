<?php
// Activamos el almacenamiento en el buffer
ob_start();
define('BYPASS_SESSION', true);

// Para la app móvil no validamos sesión de navegador para permitir el WebView
// El acceso está restringido por el ID de venta generado internamente
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link href="../../public/css/ticket.css" rel="stylesheet" type="text/css">
<style>
    /* 🔹 Alineación y formato de tabla de productos */
    table.detalle {
        width: 300px;
        font-size: 12px;
        border-collapse: collapse;
    }
    table.detalle td {
        vertical-align: top;
    }
    td.desc { width: 150px; }
    td.cant { width: 40px; text-align: center; }
    td.pu   { width: 45px; text-align: right; }
    td.sub  { width: 55px; text-align: right; }
    /* Estilo para artículos anidados (Topping/Extra) */
    .topping {
        padding-left: 15px; /* Indentación para anidamiento */
        font-size: 11px;
        font-style: italic;
    }
</style>
</head>
<body style="background: white;">
<?php
// Incluímos la clase Venta
require_once "../../modelos/Cotizaciones.php"; 
$cotizaciones = new Cotizaciones();

// **Líneas corregidas para eliminar el carácter invisible que causaba el Parse Error**
$rsptav = $cotizaciones->ventacabecera2($_GET["id"]); 
$reg = $rsptav->fetch_object();
// Fin de la corrección

// Datos de empresa
require_once "../../reportes/num2letras.php";
$conletras = $reg->total_venta;
$conletrasresultado = num2letras($conletras);
?>
<div class="zona_impresion"> 
<br> 
<table border="0" align="center" width="300px">
    <tr>
        <td align="center">
            .::<strong><?php echo $reg->nombre_comercial; ?></strong>::.<br>
            .::<strong><?php echo $reg->direccion_fiscal; ?></strong>::.<br>
            .::<strong>EMAIL: <?php echo $reg->sucursal_email; ?></strong>::.<br>
            .::<strong>TELS: <?php echo $reg->sucursal_telefono; ?></strong>::.<br>    
        </td>
    </tr>
    <tr><td align="center" colspan="4">==========================================</td></tr>
    <tr><td align="center"><strong>ENVIO</strong></td></tr>
    <tr><td align="center" colspan="4">==========================================</td></tr>
    <tr><td align="center">Fecha Operacion: <?php echo $reg->fecha; ?></td></tr>
    <tr><td align="center"><h1># Venta: <?php echo $reg->num_comprobante; ?></h1></td></tr>
    <tr><td align="center" colspan="4">==========================================</td></tr>
    <tr><td align="center"><strong>DATOS CLIENTE</strong></td></tr>
    <tr><td>Cliente: <?php echo $reg->cliente; ?></td></tr>
    <tr><td>Direccion: <?php echo $reg->direccion; ?></td></tr>
    <tr><td>Telefono: <?php echo $reg->telefono; ?></td></tr>
    <tr><td align="center" colspan="3">==========================================</td></tr>
</table>

<br>

<table class="detalle" align="center">
    <tr>
        <td class="desc"><b>DESCRIPCIÓN</b></td>
        <td class="cant"><b>CANT.</b></td>
        <td class="pu"><b>P.U.</b></td>
        <td class="sub"><b>SUB</b></td>
    </tr>
    <tr><td colspan="4">==========================================</td></tr>

    <?php
    $rsptad = $cotizaciones->ventadetalle2($_GET["id"]);
    $articulos_map = [];
    $cantidad_total = 0;

    while ($regd = $rsptad->fetch_object()) {
        $id_padre = $regd->idarticulopadre ?: 0; 
        
        if (!isset($articulos_map[$id_padre])) {
            $articulos_map[$id_padre] = [];
        }
        $articulos_map[$id_padre][] = $regd;
    }

    $productos_principales = $articulos_map[0] ?? []; 
    
    foreach ($productos_principales as $p) {
        echo "<tr>";
        echo "<td class='desc'>".$p->articulo." / ".$p->presen." / ".$p->descripcion_detalle."</td>";
        echo "<td class='cant'>".$p->cantidad."</td>";
        echo "<td class='pu'>".number_format($p->q_ref, 2)."</td>";
        echo "<td class='sub'>Q ".number_format($p->subtotal, 2)."</td>";
        echo "</tr>";

        $sub_articulos = $articulos_map[$p->idarticulo] ?? [];

        foreach ($sub_articulos as $sub) {
            echo "<tr class='topping'>";
            echo "<td class='desc'>↳ ".$sub->articulo."</td>"; 
            echo "<td class='cant'>".$sub->cantidad."</td>";
            echo "<td class='pu'>".number_format($sub->q_ref, 2)."</td>";
            echo "<td class='sub'>Q ".number_format($sub->subtotal, 2)."</td>";
            echo "</tr>";
        }

        $cantidad_total += $p->cantidad;
    }
    ?>

    <tr><td colspan="4">==========================================</td></tr>
    <tr><td colspan="2"></td><td align="right"><b>SUBTOTAL:</b></td><td class="sub"><b>Q <?php echo number_format($reg->totalgeneral,2); ?></b></td></tr>
    <tr><td colspan="2"></td><td align="right"><b>DESCUENTO:</b></td><td class="sub"><b>Q <?php echo number_format($reg->total_ventades,2); ?></b></td></tr>
    <tr><td colspan="2"></td><td align="right"><b>TOTAL:</b></td><td class="sub"><b>Q <?php echo number_format($reg->total_venta,2); ?></b></td></tr>
    <tr><td colspan="4"><?php echo ucfirst($conletrasresultado); ?></td></tr>
    <tr><td colspan="4">==========================================</td></tr>
    <tr><td colspan="4">Nº de artículos: <?php echo $cantidad_total; ?></td></tr>
    <tr><td colspan="4">&nbsp;</td></tr>
    <tr><td colspan="4" align="center">¡Gracias por su compra!</td></tr>
    <tr><td colspan="4" align="center"><?php echo $reg->sucursal_nombre; ?></td></tr>
    <tr><td colspan="4" align="center">Le Atendió: "<?php echo $reg->usuario; ?>"</td></tr>
    <tr><td colspan="4" align="center">Vendedor: "<?php echo $reg->nombre_vendedor; ?>"</td></tr>
    <tr><td colspan="4">_____________________________________________</td></tr>
    <tr><td colspan="4" align="center"><?php echo $reg->empresadesarrollo; ?></td></tr>
    <tr><td colspan="4" align="center">Nº venta control interno: #<?php echo $reg->idventa; ?></td></tr>
    <tr><td colspan="4" align="center"><h1>Efectivo: "<?php echo $reg->cefectivo; ?>"</h1></td></tr>
    <tr><td colspan="4" align="center"><h1>Cambio: "<?php echo $reg->rescambio; ?>"</td></tr>

    <?php 
    if (!empty($reg->id_add_orden) && $reg->id_add_orden != null) {
    ?>
    <tr><td colspan="4" align="center"><h1>Orden #: "<?php echo $reg->id_add_orden; ?>"</h1></td></tr>
    <tr><td colspan="4" align="center"><h1>Mesa Orden #: "<?php echo $reg->mesa_orden; ?>"</h1></td></tr>
    <tr><td colspan="4" align="center"><h1>Mesero Orden: "<?php echo $reg->usuario_orden; ?>"</h1></td></tr>
    <?php } ?>
</table>
<br>
</div>
<p>&nbsp;</p>
</body>
</html>
<?php 
ob_end_flush();
?>