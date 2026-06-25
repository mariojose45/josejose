<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once "../config/Conexion.php";
require_once "../modelos/Abono.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
    exit;
}

// Recibir datos de Flutter
$data = json_decode(file_get_contents("php://input"), true);

$fecha = isset($data['fecha']) ? $data['fecha'] : null;
$idusuario = isset($data['idusuario']) ? intval($data['idusuario']) : 0;

if (empty($fecha) || $idusuario <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Parámetros inválidos"]);
    exit;
}

// Usar el modelo
$abono = new Abono();
$resultado = $abono->listarApiadelantados($fecha, $idusuario);

// Construir JSON limpio
$abonos = [];
while ($fila = $resultado->fetch_object()) {
    $abonos[] = [
        "iddetalle_credito_add" => $fila->iddetalle_credito_add,
        "fechacuota"            => $fila->fechacuota,
        "cliente"               => $fila->cliente,
        "direccion"             => $fila->direccion,
        "telefono"              => $fila->telefono,
        "fechacredito"          => $fila->fechacredito,
        "dias_credito"          => $fila->dias_credito,
        "total"                 => $fila->total,
        "valor_cuotadiaria"     => $fila->valor_cuotadiaria,
        "tipo_prestamo"         => $fila->tipo_prestamo,
        "total_cuotaspagadas"   => $fila->total_cuotaspagadas,
        "total_cuotaspendiente" => $fila->total_cuotaspendiente,
        "estado"                => $fila->estado,
        "ultima_ubicacion_pago" => $fila->ultima_ubicacion_pago,
        "ubicacion_casa"        => $fila->ubicacion_casa
    ];
}

echo json_encode([
    "success" => true,
    "data" => $abonos
]);
