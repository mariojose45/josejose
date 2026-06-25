<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';

if (!isset($_SESSION['nombre'])) {
    echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
    if ($_SESSION['taller_ingreso_vehiculo'] == 1) {
        require('FacturaEnvio.php');
        require_once "../modelos/Ingreso_vehiculo.php";
        $ingreso_vehiculo = new Ingreso_vehiculo();

        $rsptav = $ingreso_vehiculo->mostrar($_GET['id']);
        $rsptav_revisiones = $ingreso_vehiculo->mostrarDetalles($_GET['id']);

        $regv = $rsptav;
        $regv_detalles = $rsptav_revisiones['datos'];

        // --- INICIO DE LA VALIDACIÓN ---
        $estado = $regv['estado']; // Obtener el estado del registro

        $anulado = ($estado == 'Anulado'); // Variable para saber si está anulado
        // --- FIN DE LA VALIDACIÓN ---

        $datos_empresa = [
            'nombre' => $regv['nombre_sucursal'],
            'direccion' => $regv['direccion_sucursal'],
            'telefono' => $regv['telefono_sucursal'],
            'nit' => $regv['nit_sucursal'],
            'imagen_sucursal' => $regv['imagen_sucursal'],
        ];

        $datos_cliente = (object) [
            'nombre' => $regv['nombre_cliente'],
            'apellido' => $regv['no_placa'],
            'telefono' => $regv['no_chasis']
        ];

        $datos_vehiculo = (object) [
            'piloto' => 'N/A',
            'serie' => $regv['serie'],
            'no_motor' => $regv['no_motor'],
            'telefono' => 'N/A',
            'modelo' => $regv['modelo'],
            'km' => $regv['km'],
        ];

        $datos_revisiones = $rsptav_revisiones['revisiones'];
        $datos_secciones = [
            'notas' => $regv['trabajos_detalle'],
            'observaciones' => $regv['observaciones_adicionales']
        ];

        $images_and_descriptions = [];
        for ($i = 1; $i <= 8; $i++) {
            if (!empty($regv['imagen' . $i])) {
                $images_and_descriptions[] = [
                    'file' => $regv['imagen' . $i],
                    'description' => $regv['descripcion' . $i]
                ];
            }
        }

        $pdf = new PDF_Invoice('P', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();

        $pdf->HeaderData(
            $datos_empresa['nombre'],
            $datos_empresa['direccion'],
            $datos_empresa['telefono'],
            $datos_empresa['nit'],
            $datos_empresa['imagen_sucursal'],
            $anulado // Pasamos la variable $anulado para que la clase sepa si debe marcar el documento
        );
        
        $pdf->InvoiceBody($datos_cliente, $datos_vehiculo);

        $pdf->Sections($datos_revisiones, $datos_secciones);

        if (!empty($images_and_descriptions)) {
            $pdf->SetY(230);
            $pdf->AddImagesWithDescriptionsv2($images_and_descriptions, false);
        }

        // --- VALIDACIÓN ADICIONAL PARA EL CONTENIDO ---
        if ($anulado) {
            $pdf->SetY(10); // Posición Y en la página para el texto de Anulado
            $pdf->SetFont('Arial', 'B', 50); // Tamaño y estilo de la fuente
            $pdf->SetTextColor(255, 0, 0); // Color rojo
            $pdf->RotatedText(35, 190, 'DOCUMENTO ANULADO', 45); // Dibuja el texto rotado y tachado
        }
        // --- FIN DE LA VALIDACIÓN ADICIONAL ---

        $pdf->Output('Reporte de Ingreso Vechiculo', 'I');
    } else {
        echo 'No tiene permiso para visualizar el reporte';
    }
}
ob_end_flush();
?>