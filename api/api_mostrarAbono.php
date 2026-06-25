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

$data = json_decode(file_get_contents("php://input"), true);
$iddetalle_credito_add = isset($data['iddetalle_credito_add']) ? intval($data['iddetalle_credito_add']) : 0;

if ($iddetalle_credito_add <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Parámetro inválido"]);
    exit;
}

// Instancia de modelo
$abono = new Abono();
$res = $abono->mostrar($iddetalle_credito_add);

if (!$res) {
    echo json_encode(["success" => false, "message" => "No se encontró el abono"]);
    exit;
}

// 🔹 Convertir a array
$data = $res;

// ==========================
// 📌 Calcular campos extra (como lo hacía JS)
// ==========================
$saldoAfavor       = floatval($data['saldoAfavor'] ?? 0);
$moraCredito       = floatval($data['mora_credito'] ?? 0);
$diasVencidos      = floatval($data['total_dias_vencidos'] ?? 0);
$valorCuota        = floatval($data['valor_cuotadiaria'] ?? 0);
$cuotasVencidas    = floatval($data['total_cuotas_vencidas'] ?? 0);
$totalAbonoRecibido= floatval($data['total_abonoRecibido'] ?? 0);
$saldoParcial      = floatval($data['saldopendientexpagar'] ?? 0);

// 📌 Función JS 1: calcular mora + cuotas vencidas
$moraAcumulada = $moraCredito * $diasVencidos;
$totalCuotas   = $valorCuota * $cuotasVencidas;

// 📌 Saldo pendiente (lógica del JS mostrar)
if ($cuotasVencidas > 0) {
    $saldoFinal = ($moraAcumulada + $totalCuotas - $totalAbonoRecibido) - $saldoAfavor;
} else {
    $saldoFinal = 0 - $saldoAfavor;
}

// 📌 Resultado final
$data['mora_calculada'] = $moraAcumulada;
$data['cuotas_calculadas'] = $totalCuotas;
$data['saldo_calculado'] = round($saldoFinal, 2);

// ==========================
// 📌 Enviar respuesta JSON
// ==========================
echo json_encode([
    "success" => true,
    "data" => $data
]);
