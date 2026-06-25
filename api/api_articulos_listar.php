<?php
// ===========================================
// ✅ API: Listar artículos disponibles (con filtro por sucursal)
// ===========================================

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");

require_once "../config/Conexion.php";
require_once "../modelos/Articulo.php";

try {
    // --- Verificar método HTTP ---
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode([
            "success" => false,
            "message" => "Método no permitido, usa POST"
        ]);
        exit;
    }

    // --- Leer JSON desde Flutter ---
    $input = json_decode(file_get_contents("php://input"), true);
    $idsucursal = isset($input["idsucursal"]) ? intval($input["idsucursal"]) : 0;

    if ($idsucursal <= 0) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Falta el parámetro idsucursal"
        ]);
        exit;
    }

    // --- Crear instancia del modelo ---
    $articulo = new Articulo();

    // --- Ejecutar consulta (con o sin filtro de sucursal) ---
    // Si tu método listarActivos puede recibir sucursal, lo pasamos
    $rspta = $articulo->listarActivosApi($idsucursal);

    if (!$rspta) {
        echo json_encode([
            "success" => false,
            "message" => "No se pudo obtener la lista de artículos"
        ]);
        exit;
    }

    $data = [];
    while ($reg = $rspta->fetch_object()) {
        $data[] = [
            "idarticulo" => $reg->idarticulo,
            "nombre" => $reg->nombre,
            "descripcion" => $reg->descripcion,
            "codigo" => $reg->codigo,
            "categoria" => $reg->categoria ?? "",
            "stock" => $reg->stock,
            "imagen" => $reg->imagen ?? "",
            "precio_venta" => $reg->precio_venta,
            "precio_compra" => $reg->precio_compra,
            "precio_ventaNocturno" => $reg->precio_ventaNocturno,
            "precio_rango1_Mecanico" => $reg->precio_rango1_Mecanico,
            "precio_rango1_Distribuidor" => $reg->precio_rango1_Distribuidor,
            "precio_rango1_Mayorista" => $reg->precio_rango1_Mayorista,
            "precio_rango2_MecanicoDos" => $reg->precio_rango2_MecanicoDos,
            "precio_rango2_DistribuidorDos" => $reg->precio_rango2_DistribuidorDos,
            "precio_rango2_MayoristaDos" => $reg->precio_rango2_MayoristaDos,
            "precio_rango3_MecanicoTres" => $reg->precio_rango3_MecanicoTres,
            "precio_rango3_DistribuidorTres" => $reg->precio_rango3_DistribuidorTres,
            "precio_rango3_MayoristaTres" => $reg->precio_rango3_MayoristaTres,

            // --- Presentaciones ---
            "nombre_01" => $reg->nombre_01,
            "stock_unidad" => $reg->stock_unidad,
            "precio_unidad" => $reg->precio_unidad,
            "nombre_02" => $reg->nombre_02,
            "stock_blister" => $reg->stock_blister,
            "precio_blister" => $reg->precio_blister,
            "nombre_03" => $reg->nombre_03,
            "stock_caja" => $reg->stock_caja,
            "precio_caja" => $reg->precio_caja,
            "nombre_04" => $reg->nombre_04,
            "stock_fardo" => $reg->stock_fardo,
            "precio_fardo" => $reg->precio_fardo,
            "nombre_05" => $reg->nombre_05,
            "stock_sacos" => $reg->stock_sacos,
            "precio_sacos" => $reg->precio_sacos,
            "nombre_06" => $reg->nombre_06,
            "stock_paquete" => $reg->stock_paquete,
            "precio_paquete" => $reg->precio_paquete,
            "nombre_07" => $reg->nombre_07,
            "stock_07" => $reg->stock_07,
            "precio_07" => $reg->precio_07,
            "nombre_08" => $reg->nombre_08,
            "stock_08" => $reg->stock_08,
            "precio_08" => $reg->precio_08,
            "nombre_09" => $reg->nombre_09,
            "stock_09" => $reg->stock_09,
            "precio_09" => $reg->precio_09,
            "nombre_10" => $reg->nombre_10,
            "stock_10" => $reg->stock_10,
            "precio_10" => $reg->precio_10,
            "nombre_11" => $reg->nombre_11,
            "stock_11" => $reg->stock_11,
            "precio_11" => $reg->precio_11,
            "nombre_12" => $reg->nombre_12,
            "stock_12" => $reg->stock_12,
            "precio_12" => $reg->precio_12,
            "nombre_13" => $reg->nombre_13,
            "stock_13" => $reg->stock_13,
            "precio_13" => $reg->precio_13,
            "nombre_14" => $reg->nombre_14,
            "stock_14" => $reg->stock_14,
            "precio_14" => $reg->precio_14,
            "nombre_15" => $reg->nombre_15,
            "stock_15" => $reg->stock_15,
            "precio_15" => $reg->precio_15,
            "nombre_16" => $reg->nombre_16,
            "stock_16" => $reg->stock_16,
            "precio_16" => $reg->precio_16,
            "nombre_17" => $reg->nombre_17,
            "stock_17" => $reg->stock_17,
            "precio_17" => $reg->precio_17,
            "nombre_18" => $reg->nombre_18,
            "stock_18" => $reg->stock_18,
            "precio_18" => $reg->precio_18,
            "nombre_19" => $reg->nombre_19,
            "stock_19" => $reg->stock_19,
            "precio_19" => $reg->precio_19,
            "nombre_20" => $reg->nombre_20,
            "stock_20" => $reg->stock_20,
            "precio_20" => $reg->precio_20
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
        "message" => "Error interno: " . $e->getMessage()
    ]);
}
?>
