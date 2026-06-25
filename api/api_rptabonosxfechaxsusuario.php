<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once "../config/Conexion.php";
require_once "../modelos/Abono.php";  // 👈 tu modelo Abono

// Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
    exit;
}

// Recibir datos desde Flutter
$data = json_decode(file_get_contents("php://input"), true);

$fecha_inicio = isset($data['fecha_inicio']) ? $data['fecha_inicio'] : '';
$fecha_fin    = isset($data['fecha_fin']) ? $data['fecha_fin'] : '';
$idusuario    = isset($data['idusuario']) ? intval($data['idusuario']) : 0;

if (empty($fecha_inicio) || empty($fecha_fin) || $idusuario <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Parámetros inválidos"]);
    exit;
}

// Instanciar modelo
$abono = new Abono();
$rspta = $abono->listarcobradosApi($fecha_inicio, $fecha_fin, $idusuario);

// Preparar datos
$dataArray = [];
while ($reg = $rspta->fetch_object()) {
    $dataArray[] = [
        "idabono"                  => $reg->idabono,
        "nombreCliente"            => $reg->nombreCliente,
        "fecha_abono"              => $reg->fecha_abono,
        "idcredito"                => $reg->idcredito,
        "saldopendientexpagar"     => $reg->saldopendientexpagar,
        "saldoAfavor"              => $reg->saldoAfavor,
        "morapendientemonto"       => $reg->morapendientemonto,
        "valor_cuotadiaria"        => $reg->valor_cuotadiaria,
        "abonoRecibido"            => $reg->abonoRecibido,
        "saldopendientexpagarAbono"=> $reg->saldopendientexpagarAbono,
        "forma_pago"               => $reg->forma_pago,
        "c_efectivo"               => $reg->c_efectivo,
        "c_transferencia"          => $reg->c_transferencia,
        "nota_pago"                => $reg->nota_pago,
        "estado"                   => $reg->estado,
        "cobrador"                 => $reg->cobrador
    ];
}

if (!empty($dataArray)) {
    echo json_encode([
        "success" => true,
        "data"    => $dataArray
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "No se encontraron abonos en el rango de fechas"
    ]);
}
