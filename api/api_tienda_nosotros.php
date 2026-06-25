<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once "../config/Conexion.php";
require_once "../modelos/Tienda_web_nosotros.php";

// Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido"
    ]);
    exit;
}

$nosotros = new Tiendawebnosotros();
$resultado = $nosotros->listar();

$data = [];

while ($fila = $resultado->fetch_object()) {
    $data[] = [
        "idnosotros" => $fila->idnosotros,
        "historia_empresa" => $fila->historia_empresa,
        "imagen_nosotros" => $fila->imagen_nosotros,
        "mision_nosotros" => $fila->mision_nosotros,
        "vision_nosotros" => $fila->vision_nosotros,
        "diferencia_nosotros" => $fila->diferencia_nosotros,
        "usuario_update" => $fila->usuario_update,
        "idsucursal_update" => $fila->idsucursal_update,
        "fecha_update" => $fila->fecha_update
    ];
}

echo json_encode([
    "success" => true,
    "data" => $data
]);
