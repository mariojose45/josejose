<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once "../config/Conexion.php";
require_once "../modelos/Usuario.php";

// Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
    exit;
}

// Obtener datos JSON
$data = json_decode(file_get_contents("php://input"), true);
$idusuario = isset($data['idusuario']) ? intval($data['idusuario']) : 0;

if ($idusuario <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "ID de usuario no válido."]);
    exit;
}

// Consultar sucursales asignadas
$usuario = new Usuario();
$resultado = $usuario->mostrarSucursales($idusuario);

$sucursales = [];
while ($fila = $resultado->fetch_object()) {
    $sucursales[] = [
        "idsucursal"        => $fila->idsucursal,
        "nombre_sucursal"   => $fila->nombre_sucursal,
        "direccion_sucursal"=> $fila->direccion_sucursal,
    ];
}

echo json_encode([
    "success" => true,
    "sucursales" => $sucursales
]);
