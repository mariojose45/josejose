<?php
// ===========================================
// ✅ API: Listar artículos disponibles (con filtro por sucursal)
// ===========================================

// --- CORS FIX COMPLETO ---
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// --- Manejar preflight (OPTIONS) ---
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); // IMPORTANTE
}

require_once "../config/Conexion.php";
require_once "../modelos/Articulo.php";

try {

    // --- Validar método POST ---
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode([
            "success" => false,
            "message" => "Método no permitido, usa POST"
        ]);
        exit();
    }

    // --- Leer parámetros desde POST JSON ---
    $input = json_decode(file_get_contents("php://input"), true);

    // Puedes dejarlo fijo o permitir que venga de frontend
    $idsucursal = 4;

    if ($idsucursal <= 0) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Falta el parámetro idsucursal"
        ]);
        exit();
    }

    // --- Instancia del modelo ---
    $articulo = new Articulo();

    // --- Ejecutar consulta ---
    $rspta = $articulo->listarActivosApiTiendaWebProductosNuevos($idsucursal);

    if (!$rspta) {
        echo json_encode([
            "success" => false,
            "message" => "No se pudo obtener la lista de artículos"
        ]);
        exit();
    }

    // --- Convertir resultados ---
    $data = [];
    while ($reg = $rspta->fetch_object()) {
        $data[] = [
            "idarticulo"   => $reg->idarticulo,
            "nombre"       => $reg->nombre,
            "descripcion"  => $reg->descripcion,
            "codigo"       => $reg->codigo,
            "categoria"    => $reg->categoria ?? "",
            "stock"        => $reg->stocksucursal,
            "imagen"       => $reg->imagen ?? "",
            "precio_venta" => $reg->precio_venta
        ];
    }

    // --- Respuesta final ---
    echo json_encode([
        "success" => true,
        "data" => $data
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error interno: " . $e->getMessage()
    ]);
}
?>
