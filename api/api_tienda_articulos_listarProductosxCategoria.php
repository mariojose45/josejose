<?php
// ===========================================
// ✅ API: Listar artículos por sucursal y categoría
// ===========================================

// --- CORS ---
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// --- Preflight ---
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once "../config/Conexion.php";
require_once "../modelos/Articulo.php";

try {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode([
            "success" => false,
            "message" => "Método no permitido"
        ]);
        exit();
    }

    // ===============================
    // LEER JSON
    // ===============================
    $input = json_decode(file_get_contents("php://input"), true);

    // idsucursal (puede venir del frontend o fijo)
    $idsucursal = isset($input["idsucursal"]) ? intval($input["idsucursal"]) : 4;

    // idcategoria (OBLIGATORIO)
    $idcategoria = isset($input["idcategoria"]) ? intval($input["idcategoria"]) : 0;

    if ($idsucursal <= 0 || $idcategoria <= 0) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Faltan parámetros idsucursal o idcategoria"
        ]);
        exit();
    }

    // ===============================
    // CONSULTA
    // ===============================
    $articulo = new Articulo();
    $rspta = $articulo
        ->listarActivosApiTiendaWebProductosNuevosxCategoria(
            $idsucursal,
            $idcategoria
        );

    if (!$rspta) {
        echo json_encode([
            "success" => false,
            "message" => "No se pudo obtener la lista"
        ]);
        exit();
    }

    // ===============================
    // ARMAR RESPUESTA
    // ===============================
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

    echo json_encode([
        "success" => true,
        "data" => $data
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error interno: ".$e->getMessage()
    ]);
}
