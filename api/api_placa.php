<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once "../config/Conexion.php";
require_once "../modelos/Consultas.php"; // aquí está validarxplacacarro()

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir datos desde JSON o POST normal
    $input = json_decode(file_get_contents("php://input"), true);
    $placa = $input['placa'] ?? ($_POST['placa'] ?? null);

    if (!$placa) {
        echo json_encode([
            "success" => false,
            "message" => "No se recibió la placa"
        ]);
        exit;
    }

    try {
        $consulta = new Consultas();
        $resultado = $consulta->validarxplacacarro($placa);

        if ($resultado && isset($resultado['idpersona'])) {
            echo json_encode([
                "success" => true,
                "data" => $resultado
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Placa no encontrada"
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "Error en la validación: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido"
    ]);
}
