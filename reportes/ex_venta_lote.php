<?php
require_once "../modelos/Venta.php";

if (file_exists("FacturaLote.php")) {
    require_once "FacturaLote.php";
} else {
    die("Error: No se encontró el archivo de la clase PDF (FacturaLote.php).");
}

if (isset($_GET["lote"])) {
    $venta_lote = $_GET["lote"];
    $venta = new Venta();
    
    // Obtenemos todas las ventas del lote
    $rsptaVentas = $venta->listarVentasPorLote($venta_lote);

    $pdf = new FacturaEnvio('P', 'mm', 'A4');
    $pdf->SetMargins(12, 12, 12);
    $pdf->SetAutoPageBreak(false);

    // Definición de anchos de columnas (Total suma 186 mm)
    $cols = array(
        "Cant."       => 20,
        "Descripción" => 106,
        "P. Unitario" => 30,
        "Subtotal"    => 30
    );

    while ($regVenta = $rsptaVentas->fetch_object()) {
        $pdf->AddPage();
        
        // Datos de la Empresa
        $pdf->addSociete("MI EMPRESA, S.A.", "Calle Principal 1-23 Zona 1, Guatemala, Guatemala\nTel: (502) 2222-2222 | NIT: 1234567-8");
        
        // Formateo elegante del Número de Comprobante
        $comprobante = !empty($regVenta->tipo_comprobante) ? $regVenta->tipo_comprobante : "Envío";
        
        $serie = trim($regVenta->serie_comprobante);
        $numero = trim($regVenta->num_comprobante);

        if (!empty($serie) && !empty($numero)) {
            $numeroComp = $serie . " - #" . str_pad($numero, 6, "0", STR_PAD_LEFT);
        } elseif (!empty($numero)) {
            $numeroComp = "#" . str_pad($numero, 6, "0", STR_PAD_LEFT);
        } else {
            $numeroComp = "#" . str_pad($regVenta->idventa, 6, "0", STR_PAD_LEFT);
        }

        $pdf->fact_dev($comprobante, $numeroComp);

        // Datos del Cliente y Lote
        $nitCliente  = !empty($regVenta->num_documento) ? $regVenta->num_documento : "C/F";
        $telCliente  = (!empty($regVenta->telefono) && $regVenta->telefono != "0") ? $regVenta->telefono : "N/A";
        $dirCliente  = (!empty($regVenta->direccion) && $regVenta->direccion != "0") ? $regVenta->direccion : "Ciudad";

        $pdf->addClientAdresse(
            $regVenta->nombre_cliente,
            $nitCliente,
            $dirCliente,
            $telCliente,
            $regVenta->fecha_hora,
            $regVenta->venta_lote
        );

        // Renderizar Encabezados de Tabla
        $pdf->addCols($cols);

        // Cargar Artículos de la Venta
        $rsptaDetalle = $venta->listarDetalleVenta($regVenta->idventa);
        $y = 75; // Posición Y inicial para productos
        $indexFila = 0;

        while ($regD = $rsptaDetalle->fetch_object()) {
            $nombreArticulo = !empty($regD->descripcion_detalle) ? $regD->articulo . " - " . $regD->descripcion_detalle : $regD->articulo;

            $linea = array(
                "cant"     => array("col" => "Cant.",       "text" => number_format($regD->cantidad, 2)),
                "desc"     => array("col" => "Descripción", "text" => $nombreArticulo),
                "pu"       => array("col" => "P. Unitario", "text" => number_format((float)$regD->precio_venta, 2)),
                "subtotal" => array("col" => "Subtotal",    "text" => number_format($regD->subtotal, 2))
            );

            $pdf->addLine($y, $linea, $indexFila);
            $y += 6;
            $indexFila++;

            // Control de salto de página si hay más productos de los que entran
            if ($y > 220) {
                $pdf->lineVert();
                $pdf->AddPage();
                $pdf->addCols($cols);
                $y = 75;
                $indexFila = 0;
            }
        }

        // Dibujar divisiones verticales suaves y el total destacado
        $pdf->lineVert();
        $pdf->addCadreTotales($regVenta->total_venta);
    }

    $pdf->Output("I", "Reporte_Lote_" . $venta_lote . ".pdf");
} else {
    echo "No se especificó un identificador de lote válido.";
}
?>