<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

// Incluir conexión y modelo
require_once "../config/Conexion.php";
require_once "../modelos/Consultas.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $vehiculo = new Consultas();
        $rspta = $vehiculo->listarmarca();

        $marcas = [];
        while ($reg = $rspta->fetch_assoc()) {
            $marcas[] = [
                "idmarca" => $reg["idmarca"],
                "nombre_marca" => $reg["nombre_marca"]
            ];
        }

        echo json_encode([
            "success" => true,
            "data" => $marcas
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "Error: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido"
    ]);
}
