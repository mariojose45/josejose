<?php
// ===============================================================
// HEADERS
// ===============================================================
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// ===============================================================
// PREFLIGHT
// ===============================================================
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ===============================================================
// INCLUDES
// ===============================================================
require_once "../config/Conexion.php";
require_once "../modelos/Cotizaciones.php";

// ===============================================================
// VALIDAR MÉTODO
// ===============================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido"
    ]);
    exit;
}

// ===============================================================
// LEER JSON
// ===============================================================
$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !is_array($input)) {
    echo json_encode([
        "success" => false,
        "message" => "JSON inválido"
    ]);
    exit;
}

try {

    // ===============================================================
    // CAPTURAR VARIABLES (MISMO ORDEN DEL MODELO)
    // ===============================================================
    $codigo_cliente          = $input['codigo_cliente'] ?? null;
    $nit                     = $input['nit'] ?? "";
    $nombre_cliente          = $input['nombre_cliente'] ?? "";
    $telefono_cliente        = $input['telefono_cliente'] ?? "";
    $direccion_cliente       = $input['direccion_cliente'] ?? "";
    $correo_cliente          = $input['correo_cliente'] ?? "";
    $valor_tarjeta           = $input['valor_tarjeta'] ?? 0;
    $tipo_documento_cliente  = $input['tipo_documento_cliente'] ?? "";
    $fecha_hora              = $input['fecha_hora'] ?? date('Y-m-d H:i:s');
    $forma_pago              = $input['forma_pago'] ?? "";
    $tipo_comprobante        = $input['tipo_comprobante'] ?? "";
    $tipo_pagoBacVisaNet     = $input['tipo_pagoBacVisaNet'] ?? "";
    $opcionesAdicionales     = $input['opcionesAdicionales'] ?? "";
    $total_venta             = $input['total_venta'] ?? 0;
    $total_ventades          = $input['total_ventades'] ?? 0;
    $idvendedor              = $input['idvendedor'] ?? 0;
    $tipo_cliente            = $input['tipo_cliente'] ?? "";
    $forma_productos         = $input['forma_productos'] ?? "";
    $comentario_cotizacion   = $input['comentario_cotizacion'] ?? "";
    $destino                 = $input['destino'] ?? "";
    $datosArticulos          = $input['datosArticulos'] ?? [];

    // ===============================================================
    // VALIDACIONES BÁSICAS
    // ===============================================================
    if ($nombre_cliente == "" || empty($datosArticulos)) {
        echo json_encode([
            "success" => false,
            "message" => "Datos incompletos para generar la cotización"
        ]);
        exit;
    }

    // ===============================================================
    // EJECUTAR MODELO
    // ===============================================================
    $cotizacion = new Cotizaciones();

    $idcotizacion = $cotizacion->insertarApi(
        $codigo_cliente,
        $nit,
        $nombre_cliente,
        $telefono_cliente,
        $direccion_cliente,
        $correo_cliente,
        $valor_tarjeta,
        $tipo_documento_cliente,
        $fecha_hora,
        $forma_pago,
        $tipo_comprobante,
        $tipo_pagoBacVisaNet,
        $opcionesAdicionales,
        $total_venta,
        $total_ventades,
        $idvendedor,
        $tipo_cliente,
        $forma_productos,
        $comentario_cotizacion,
        $destino,
        $datosArticulos
    );

    // ===============================================================
    // RESPUESTA
    // ===============================================================
    echo json_encode([
        "success" => true,
        "message" => "Cotización registrada correctamente",
        "idcotizacion" => $idcotizacion
    ]);

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => "Error al generar cotización",
        "error" => $e->getMessage()
    ]);
}
