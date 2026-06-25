<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';

if (!isset($_SESSION["nombre"]))
    exit('Debe ingresar al sistema correctamente para visualizar el reporte');
if ($_SESSION['ventas'] != 1)
    exit('No tiene permiso para visualizar el reporte');

// =============================================
// CONFIGURACIÓN TICKET 78 mm
// =============================================
$ANCHO = 78;                // ancho real del ticket
$MARGEN = 2;
$UTIL = $ANCHO - ($MARGEN * 2);

// =============================================
// OBTENER DATOS
// =============================================
$id = $_GET['id'];
$idventa = $_GET['idventa'];

require_once "../modelos/Cotizaciones.php";
$cot = new Cotizaciones();

$rsptaCab = $cot->ventacabecera2($idventa);
$reg = $rsptaCab->fetch_object();

// LOGO (reemplazar si quieres otro)
$logo = "logo1.jpg";

?>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="../public/css/ticket.css" rel="stylesheet" type="text/css">

    <style>
        body {
            font-family: monospace;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .ticket {
            width:
                <?php echo $ANCHO; ?>
                mm;
            max-width:
                <?php echo $ANCHO; ?>
                mm;
            margin: 0 auto;
            padding: 0;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Tabla de abonos más compacta */
        .abonos td {
            font-size: 10px;
            padding: 1px 0;
        }

        /* Marca de agua */
        .watermark-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: none;
            z-index: 1000;
            pointer-events: none;
        }
    </style>
</head>

<body onload="window.print();">

    <div class="ticket">

        <!-- LOGO -->
        <div class="center">
            <img src="<?php echo $logo; ?>" width="120">
        </div>

        <!-- TÍTULO PRINCIPAL -->
        <div class="center bold">INTEGRACIÓN DE CTAS X COBRAR</div>

        <div class="center">
            <div><strong><?php echo $reg->sucursal_nombre; ?></strong></div>
            <div><?php echo $reg->sucursal_nit; ?></div>
            <div><?php echo $reg->sucursal_direccion; ?></div>
            <div><?php echo $reg->sucursal_telefono; ?></div>
            <div><?php echo $reg->sucursal_email; ?></div>
        </div>

        <div class="center">Fecha: <?php echo $reg->fecha; ?></div>

        <div class="line"></div>

        <!-- DATOS DEL CLIENTE -->
        <div><strong>Cliente:</strong> <?php echo $reg->cliente; ?></div>
        <div><strong>Dirección:</strong> <?php echo $reg->direccion; ?></div>
        <div><strong><?php echo $reg->tipo_documento; ?>:</strong> <?php echo $reg->num_documento; ?></div>
        <div><strong>ID Venta:</strong> #<?php echo $reg->idventa; ?></div>

        <div class="line"></div>

        <!-- TABLA DE ABONOS -->
        <div class="bold center">ABONOS</div>
        <div class="line"></div>

        <table class="abonos">
            <tr>
                <td class="bold">ABONO</td>
                <td class="bold">FORMA</td>
                <td class="bold">FECHA</td>
                <td class="bold right">SALDO</td>
            </tr>
            <tr>
                <td colspan="4">
                    <div class="line"></div>
                </td>
            </tr>

            <?php
            $rsptad = $cot->detalle_abonosCtasxcobrar($id);
            $totalabonos = 0;

            while ($d = $rsptad->fetch_object()) {
                echo "<tr>";
                echo "<td>{$d->total_abono}</td>";
                echo "<td>{$d->tipo_pago}</td>";
                echo "<td>{$d->fechapago}</td>";
                echo "<td class='right'>Q " . number_format($d->saldo_venta, 2) . "</td>";
                echo "</tr>";
                $totalabonos += $d->total_abono;
            }

            $saldoPendiente = $reg->total_venta - $totalabonos;
            ?>
        </table>

        <div class="line"></div>

        <!-- TOTALES -->
        <table>
            <tr>
                <td class="bold">Total Abonos:</td>
                <td class="right">Q <?php echo number_format($totalabonos, 2); ?></td>
            </tr>
            <tr>
                <td class="bold">Total Venta:</td>
                <td class="right">Q <?php echo number_format($reg->total_venta, 2); ?></td>
            </tr>
            <tr>
                <td class="bold">Saldo Pendiente:</td>
                <td class="right">Q <?php echo number_format($saldoPendiente, 2); ?></td>
            </tr>
        </table>

        <div class="line"></div>

        <!-- PIE -->
        <div class="center">¡Gracias por su pago!</div>
        <div class="center"><?php echo $reg->sucursal_nombre; ?></div>
        <div class="center">Guatemala, Guatemala</div>
        <div class="center">Le atendió: <?php echo $reg->usuario; ?></div>

    </div>

    <!-- MARCA DE AGUA SI ESTÁ ANULADO -->
    <div class="watermark-container">
    </div>

    <?php if ($reg->condicion == 0): ?>
        <script>
            document.querySelector('.watermark-container').style.display = 'block';
        </script>
    <?php endif; ?>

</body>

</html>

<?php
ob_end_flush();
?>