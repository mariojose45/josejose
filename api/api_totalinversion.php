<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once "../config/Conexion.php";
require_once "../modelos/Consultas.php";

// Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
    exit;
}

// Recibir datos de Flutter
$data = json_decode(file_get_contents("php://input"), true);
$idusuario = isset($data['idusuario']) ? intval($data['idusuario']) : 0;

if ($idusuario <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Parámetro inválido"]);
    exit;
}

// Instanciar modelo
$consulta = new Consultas();
$rspta = $consulta->totalinversionprestamosxusuarioApi($idusuario);

// Obtener fila
$fila = $rspta->fetch_assoc();

if ($fila) {
    echo json_encode([
        "success" => true,
        "data" => [
            "totalprestamo" => $fila["totalprestamo"],
            "totalinteres"  => $fila["totalinteres"],
            "totalgeneral"  => $fila["totalprestamo"] + $fila["totalinteres"]
        ]
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "No hay datos para este usuario"
    ]);
}
