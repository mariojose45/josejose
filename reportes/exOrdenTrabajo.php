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
require_once "../modelos/Orden_trabajo.php"; 
$cotizaciones= new OrdenTRbajo();

$rsptav = $cotizaciones->cabecera($_GET["id"]);  
//Recorremos todos los valores obtenidos
$reg = $rsptav->fetch_object();
 
//Establecemos los datos de la empresa
    $logo = "logo1.jpg";
    $ext_logo = "jpg";
    $logopie = "piepagina.jpg";
    $ext_logopie = "jpg";
    $logovisa="visacuota.jpg";
    $logovisaext = "jpg";
    $empresa = "Expressfast Guatemala";
    $documento = "NIT: 9373581-2";
    $direccion = "Guatemala, Guatemala";
    $telefono = "+502-2235-0770";
    $email = "";


?>
<div class="zona_impresion">
<!-- codigo imprimir -->
<br> 
<img src="<?php echo $logo; ?>">
<table border="0" align="center" width="300px">
    <tr>
        <td align="center">
        <!-- Mostramos los datos de la empresa en el documento HTML -->
        .::<strong> <?php echo $empresa; ?></strong>::.<br>
        <?php echo $documento; ?><br>
        <?php echo $direccion ?><br>
        <?php echo $email ?><br>
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
        <td>Cliente: <?php echo $reg->cliente_nombre; ?></td>
    </tr>
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td>Telefono: <?php echo $reg->cliente_telefono; ?></td>
    </tr>  
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td>Modelo: <?php echo $reg->modelo; ?></td>
    </tr> 
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td>Serie: <?php echo $reg->serie; ?></td>
    </tr>                

    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td>Descripcion: <?php echo $reg->descripcion_equipo; ?></td>
    </tr>   
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td>Reparacion: <?php echo $reg->reparacion_equipo; ?></td>
    </tr>  

    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td>Reparacion: <?php echo $reg->reparacion_equipo; ?></td>
    </tr>                        

    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>      
    <tr>
      <td colspan="3" align="center">¡Gracias por preferirnos!</td>
    </tr>
    <tr>
      <td colspan="3" align="center">COMPUTECNOLOGIA</td>
    </tr>
    <tr>
      <td colspan="3" align="center">Guatemala, Guatemala</td>
    </tr> 
    <tr>
      <td colspan="3" align="center">Le Atendio: "<?php echo $reg->usuario;  ?>"</td>
    </tr>       
        
</table> 
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