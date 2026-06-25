<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

require_once "../config/Conexion.php";
require_once "../modelos/Persona.php";

try {
    $persona = new Persona();
    $rspta = $persona->listarp();

    $proveedores = [];
    while ($reg = $rspta->fetch_object()) {
        $proveedores[] = [
            "idpersona" => $reg->idpersona,
            "nombre" => $reg->nombre,
            "direccion" => $reg->direccion,
            "num_documento" => $reg->num_documento,
            "telefono" => $reg->telefono,
            "email" => $reg->email,
            "tipo_cliente" => $reg->tipo_cliente,
            "codigo_cliente" => $reg->codigo_cliente
        ];
    }

    echo json_encode([
        "success" => true,
        "total" => count($proveedores),
        "data" => $proveedores
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error al listar proveedores: " . $e->getMessage()
    ]);
}
