<?php
// ===========================================
// ✅ API: Listar imágenes de un producto por ID
// ===========================================

// --- CORS FIX COMPLETO ---
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// --- Manejar preflight (OPTIONS) ---
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once "../config/Conexion.php";
require_once "../modelos/Articulo.php";

try {

    // --- Validar método ---
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode([
            "success" => false,
            "message" => "Método no permitido, usa POST"
        ]);
        exit();
    }

    // --- Recibir JSON ---
    $input = json_decode(file_get_contents("php://input"), true);
    $idarticulo = $input["idarticulo"] ?? 0;

    if ($idarticulo <= 0) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Falta el parámetro idarticulo"
        ]);
        exit();
    }

    // --- Instancia modelo ---
    $articulo = new Articulo();

    // --- Obtener imágenes ---
    $rspta = $articulo->mostrar_ApiTiendaWeb_imagenesproducto($idarticulo);

    $imagenes = [];

    while ($reg = $rspta->fetch_object()) {
        $imagenes[] = [
            "id" => $reg->idimagenes_producto,
            "ruta" => $reg->ruta_imagen,
            "orden" => $reg->orden
        ];
    }

    echo json_encode([
        "success" => true,
        "idarticulo" => $idarticulo,
        "imagenes" => $imagenes
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error interno: " . $e->getMessage()
    ]);
}
?>
