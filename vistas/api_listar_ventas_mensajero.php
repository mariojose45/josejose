<?php
header('Content-Type: application/json');
require_once "../config/conexion.php";
require_once "../modelos/consultasApp.php";

$venta = new Venta();

// Obtener datos del cuerpo de la solicitud (JSON)
$input = json_decode(file_get_contents("php://input"), true);

// Validar que venga el idusuario
if (!isset($input['idusuario'])) {
    echo json_encode([
        "success" => false,
        "message" => "Falta el parámetro idusuario"
    ]);
    exit;
}

$idusuario = $input['idusuario'];

// Ejecutar la función del modelo
$rspta = $venta->listarVentasMensajeroApp($idusuario);

// Preparar respuesta
$data = [];
while ($reg = $rspta->fetch_object()) {
    $data[] = [
        "idventa"          => $reg->idventa,
        "fecha"            => $reg->fecha,
        "cliente"          => $reg->cliente,
        "direccion"        => $reg->direccion,
        "telefono"         => $reg->telefono,
        "usuario"          => $reg->usuario,
        "num_comprobante"  => $reg->num_comprobante,
        "total_venta"      => $reg->total_venta,
        "total_ventades"   => $reg->total_ventades,
        "ctransferencia"   => $reg->ctransferencia,
        "estado"           => ($reg->estadoventamensajero == 0) ? "PENDIENTE DESPACHO" : $reg->estadoventamensajero
    ];
}

// Respuesta final
echo json_encode([
    "success" => true,
    "data" => $data
]);
