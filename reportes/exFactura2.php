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
<link  rel="stylesheet" type="text/css">
</head>
<body onload="window.print();">
<?php
 
    require('Factura.php');
    
    require_once"num2letras.php";

    
    //Obtenemos los datos de la cabecera de la venta actual
    require_once "../modelos/Venta.php";
    $venta= new Venta();
    $rsptav = $venta->ventacabecera($_GET["id"]); 
    //Recorremos todos los valores obtenidos
    $regv = $rsptav->fetch_object();

    $fecha_dia=date( "d", strtotime( $regv->fecha ));
    $fecha_mes=date( "m", strtotime( $regv->fecha ));
    $fecha_year=date( "Y", strtotime( $regv->fecha ));    
 
?>

<div>
<!-- codigo imprimir -->

    <table border="0" align="center" width="100%" style="position:absolute;top:77px;left:45px;" >
        <tr>
            <td style="width:170px"></td>
            <td style="width:10px"><?php echo $fecha_dia ?></td>
            <td style="width:10px"><?php echo $fecha_mes ?></td>
            <td style="width:10px"><?php echo $fecha_year ?></td>
        </tr>
    </table>
    <table border="0" align="center" width="100%" style="position:absolute;top:110px;left:45px;" >
        <tr>
            <td style="width:85px"></td>
            <td style="width:430px"><?php echo $regv->cliente ?></td>
            <td style="width:100px"></td>
            <td><?php echo $regv->num_documento ?></td>
        </tr>
    </table>
    <table border="0" align="center" width="100%" style="position:absolute;top:140px;left:45px;" >
        <tr>
            <td style="width:85px"></td>
            <td style="width:430px"><?php echo $regv->direccion ?></td>
            <td style="width:100px"></td>
            <td><?php echo $regv->telefono ?></td>
        </tr>
    </table>
<div >
    <table border="0" align="center" width="100%" style="position:absolute;top:190px;left:15px;" >

    <?php
    $rsptad = $venta->ventadetalle($_GET["id"]);
    $cantidad=0;
    while ($regd = $rsptad->fetch_object()) {
        echo "<tr>";
        echo "<td style='width:5px'></td>";
        echo "<td style='width:20px'>".$regd->cantidad."</td>";
        echo "<td style='width:70px'>".$regd->codigo."</td>";
        echo "<td style='width:175px'>".$regd->articulo."</td>";
        
        echo "<td style='width:50px'>Des % ".$regd->descuento."</td>";
        echo "<td style='width:50px'>".$regd->precio_venta."</td>";
        echo "<td style='width:30px'>".$regd->subtotal."</td>";
        echo "<td style='width:15px'></td>";        
        echo "</tr>";
        $cantidad+=$regd->cantidad;
    }
    require_once"num2letras.php";
    $conletras=$regv->total_venta;     
    $conletrasresultado=num2letras($conletras);    
    ?>
    <!-- Mostramos los totales de la venta en el documento HTML -->

</table>
    <table border="0" align="center" width="100%" style="position:absolute;top:400px;left:45px;" >
        <tr>
            <td style="width:150px"></td>
            <td style="width:175px">Total Descuentos</td>
            <td style="width:150px"></td>
            <td><?php echo $regv->total_ventades ?></td>
        </tr>
    </table>     



</table>
    <table border="0" align="center" width="100%" style="position:absolute;top:435px;left:45px;" >
        <tr>
            <td style="width:85px"></td>
            <td style="width:400px"><?php echo $conletrasresultado ?></td>
            <td style="width:150px"></td>
            <td><?php echo $regv->total_venta ?></td>
        </tr>
    </table>
<br>
</div>
<!-- Mostramos los detalles de la venta en el documento HTML -->

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