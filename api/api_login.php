<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// Incluir archivos necesarios
require_once "../config/Conexion.php";
require_once "../modelos/Usuario.php";

// Verificar que sea método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
    exit;
}

// Obtener y decodificar JSON del cuerpo
$data = json_decode(file_get_contents("php://input"), true);
$login = isset($data['login']) ? limpiarCadena($data['login']) : '';
$clave = isset($data['clave']) ? hash("SHA256", $data['clave']) : '';

if (empty($login) || empty($clave)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Parámetros incompletos."]);
    exit;
}

// Verificar usuario en BD
$usuario = new Usuario();
$resultado = $usuario->verificar($login, $clave);

if ($fila = $resultado->fetch_object()) {
    echo json_encode([
        "success" => true,
        "usuario" => [
            "idusuario"       => $fila->idusuario,
            "nombre"          => $fila->nombre,
            "tipo_documento"  => $fila->tipo_documento,
            "num_documento"   => $fila->num_documento,
            "telefono"        => $fila->telefono,
            "email"           => $fila->email,
            "cargo"           => $fila->cargo,
            "imagen"          => $fila->imagen,
            "login"           => $fila->login,
            "idsucursal"      => $fila->idsucursal
        ]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Credenciales incorrectas."]);
}
