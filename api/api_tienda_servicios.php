<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once "../config/Conexion.php";
require_once "../modelos/Tienda_web_servicios.php";

// Validar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido"
    ]);
    exit;
}

$servicios = new TiendawebServicios();
$resultado = $servicios->listar();

$data = [];

while ($fila = $resultado->fetch_object()) {
    $data[] = [
        "idservicios"        => $fila->idservicios,
        "nombre"    => $fila->nombre,
        "descripcion_servicio"        => $fila->descripcion_servicio,
        "imagen_servicio"    => $fila->imagen_servicio,
        "tipo"             => $fila->tipo,
        "fecha_creacion"     => $fila->fecha_creacion,
    ];
}

echo json_encode([
    "success" => true,
    "data" => $data
]);
