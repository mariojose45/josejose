<?php
/***************************************************
 * EXPORTAR PLANTILLA ARTICULOS (CSV)
 * Compatible con PHP viejo / XAMPP
 * Excel-safe
 ***************************************************/

// LIMPIAR CUALQUIER BUFFER
while (ob_get_level()) {
    ob_end_clean();
}

// APAGAR ERRORES (evita que se metan en el CSV)
ini_set('display_errors', 0);
error_reporting(0);



// MODELOS
require_once __DIR__ . '/../modelos/Articulo.php';

// ============================================
// OBTENER PRESENTACIONES DESDE BD
// ============================================
$articulo = new Articulo();
$rsPresen = $articulo->presentacionprecioventa();
$p = $rsPresen->fetch_object();

$presentaciones = [];

if ($p) {
    for ($i = 1; $i <= 20; $i++) {
        $campo = "nombrepresentacion" . $i;
        if (
            isset($p->$campo) &&
            trim($p->$campo) !== '' &&
            $p->$campo !== 'NA' &&
            $p->$campo !== '0'
        ) {
            $presentaciones[] = trim($p->$campo);
        }
    }
}

// SI NO HAY PRESENTACIONES, PONEMOS UNAS POR DEFECTO
if (empty($presentaciones)) {
    $presentaciones = ['UNIDAD'];
}

// ============================================
// CABECERAS HTTP PARA CSV
// ============================================
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="Plantilla_Articulos.csv"');
header('Pragma: no-cache');
header('Expires: 0');

// BOM UTF-8 PARA EXCEL (acentos correctos)
echo "\xEF\xBB\xBF";

// ABRIMOS SALIDA
$output = fopen('php://output', 'w');

// ============================================
// ENCABEZADOS BASE
// ============================================
$headers = [
    'Nombre',
    'Categoria',
    'Subcategoria',
    'Descripcion',
    'Ubicacion',
    'Codigo',
    'Precio Compra',
    'Precio Venta',
    'Stock'
];

// ============================================
// ENCABEZADOS DINÁMICOS (PRESENTACIONES)
// ============================================
foreach ($presentaciones as $pres) {
    $headers[] = 'Stock ' . $pres;
    $headers[] = 'Precio ' . $pres;
}

// ESCRIBIR ENCABEZADOS
fputcsv($output, $headers);

// (NO escribimos filas, es solo plantilla)

// CERRAR
fclose($output);
exit;
