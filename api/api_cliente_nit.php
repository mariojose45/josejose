<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once "../config/Conexion.php";
require_once "../modelos/Consultas.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);
    $nit = $input['nit'] ?? ($_POST['nit'] ?? null);

    if (!$nit) {
        echo json_encode([
            "success" => false,
            "message" => "No se recibió el NIT"
        ]);
        exit;
    }

    try {
        $consulta = new Consultas();
        $resultado = $consulta->validarnitapi($nit);

        // 👇 Aquí NO alteramos el JSON de FEL, lo devolvemos igual
        echo $resultado;

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
