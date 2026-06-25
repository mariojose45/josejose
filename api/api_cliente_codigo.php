<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once "../config/Conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar código desde JSON o form-data
    $input = json_decode(file_get_contents("php://input"), true);
    $codigo_cliente = $input['codigo_cliente'] ?? ($_POST['codigo_cliente'] ?? null);

    if (!$codigo_cliente) {
        echo json_encode([
            "success" => false,
            "message" => "No se recibió el código de cliente"
        ]);
        exit;
    }

    // Consulta
    $sql = "SELECT * FROM persona WHERE codigo_cliente LIKE '%$codigo_cliente%' LIMIT 1";
    $result = ejecutarConsultaSimpleFila($sql);

    if ($result && isset($result['idpersona'])) {
        echo json_encode([
            "success" => true,
            "data" => $result
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Cliente no encontrado"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido"
    ]);
}
