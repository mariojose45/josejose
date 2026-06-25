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

?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link href="../public/css/ticket.css" rel="stylesheet" type="text/css">
</head>
<body onload="window.print();">
<?php
   
//Incluímos la clase Venta
require_once "../modelos/Admin_ordenes.php";  
$adminordenes=new AdminOrdenes(); 

$rsptav = $adminordenes->cabeceranuevaorden($_GET["id"]);  
//Recorremos todos los valores obtenidos
$reg = $rsptav->fetch_object();
 
//Establecemos los datos de la empresa
    $logo = "logo1.jpg";
    $ext_logo = "jpg";
    $logopie = "piepagina.jpg";
    $ext_logopie = "jpg";
    $logovisa="visacuota.jpg";
    $logovisaext = "jpg";
    $url='../files/articulos/';


?>
<div class="zona_impresion">
<!-- codigo imprimir -->
<br> 
<img src="<?php echo $url.$reg->sucursal_imagen; ?>" width="350" height="100">
<table border="0" align="center" width="300px">
    <tr>
        <td align="center">
        <!-- Mostramos los datos de la empresa en el documento HTML -->
        .::<strong> <?php echo $reg->nombre_sucursal; ?></strong>::.<br>
        <?php echo $reg->nit_sucursal; ?><br>
        <?php echo $reg->direccion_sucursal; ?><br>
        <?php echo $reg->tels_sucursal; ?><br>
        <?php echo $reg->correo_sucursal ?><br>
        </td>
    </tr>
<hr>    
    <tr>
        <td align="center">Fecha Operacion: <?php echo $reg->fecha_creacion; ?></td>
    </tr>
    <tr>
      <td align="center"></td>
    </tr>
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td>Cliente: <?php echo $reg->nombre_cliente; ?></td>
    </tr>
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td>Direccion: <?php echo $reg->nombre_direccion; ?></td>
    </tr>  
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td>Telefono: <?php echo $reg->nombre_telefono; ?></td>
    </tr>        

    <tr>
        <td># Orden Trabajo <?php  echo $reg->num_nueva_orden; ?></td>
    </tr>    
</table> 
<br>
<hr>
<table border="0" align="center" width="300px">

    <tr>
        <td align="center">DATOS DE EQUIPO</td>
    </tr>
    <tr>
      <td align="center"></td>
    </tr>
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td >IMEI: <?php echo $reg->imei_cel; ?></td>
    </tr>
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td>MARCA: <?php echo $reg->nombre_marca; ?></td>
    </tr>  
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td>MODELO: <?php echo $reg->nombre_modelo; ?></td>
    </tr>        

    <tr>
        <td>TIPO EQUIPO<?php  echo $reg->nombre_tipoequipo; ?></td>
    </tr>  
    <tr>
        <td>COLOR <?php  echo $reg->nombre_color; ?></td>
    </tr> 
    <tr>
        <td>ENCIENTE <?php  echo $reg->enciende; ?></td>
    </tr> 
    <tr>
        <td>GOLPES <?php  echo $reg->golpes; ?></td>
    </tr>  
    <tr>
        <td>PUERTO CARGAR <?php  echo $reg->puerto_carga; ?></td>
    </tr> 
    <tr>
        <td>CODIGO ORDEN <?php  echo $reg->codigo_ordennueva; ?></td>
    </tr>  
    <tr>
        <td>PASSWORD <?php  echo $reg->password_orden; ?></td>
    </tr>          
    <tr>
        <td>FALLA EQUIPO <?php  echo $reg->falla_equipo; ?></td>
    </tr>   

    <tr>
        <td>DIAGNOSTICO <?php  echo $reg->diagnostico_equipo; ?></td>
    </tr> 
    <tr>
        <td>REPUESTOS Q:<?php  echo $reg->repuestos; ?></td>
    </tr>  
    <tr>
        <td>ANTICIPO Q:<?php  echo $reg->anticipo; ?></td>
    </tr> 
    <tr>
        <td>TOTAL Q:<?php  echo $reg->total_orden; ?></td>
    </tr>                                            
</table>
<hr>
<table border="1" align="center" width="300px">

         
    <tr>
        <td>FALLA EQUIPO: <?php  echo $reg->falla_equipo; ?></td>
    </tr>   

    <tr>
        <td>DIAGNOSTICO:  <?php  echo $reg->diagnostico_equipo; ?></td>
    </tr>                                            
</table>
<table border="0" align="center" width="300px">

         
    <tr>
        <td>* Políticas de garantía *:
* No nos hacemos responsables por memorias o tarjetas sim
dejadas en su equipo.
* La fecha de entrega es condicional. Puede variar según
disponibilidad de repuesto.
* Contará con un plazo de 30 días para recoger el equipo una
vez haya sido notificado de que el mismo está listo.
Transcurrido el lapso establecido el equipo pasará a ser
propiedad de la empresa
* La garantía de reparación únicamente será válida cuando
se presente la misma falla aquí descrita y no haya sido
abierto el equipo.
* Los equipos de software, mojados y/o húmedos no tienen
garantía alguna.
* Tiempo de garantía: puede variar de acuerdo a la pieza.
Válido únicamente presentando ticket.</td>
    </tr>   
                                          
</table>
<hr>


<br>
</div>
<p>&nbsp;</p>
 
</body>
</html>
<?php 

 
}
ob_end_flush();
?>