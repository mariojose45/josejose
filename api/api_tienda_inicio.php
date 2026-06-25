<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once "../config/Conexion.php";
require_once "../modelos/tienda_web_inicio.php";

// Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido"
    ]);
    exit;
}

// Instancia del modelo
$tiendaInicio = new Tiendawebinicio();
$resultado = $tiendaInicio->listar();

$data = [];

while ($fila = $resultado->fetch_object()) {
    $data[] = [
        "idinicio" => $fila->idinicio,

        "bloque_1" => [
            "titulo"       => $fila->titulo_1,
            "sub_titulo"   => $fila->sub_titulo_1,
            "descripcion"  => $fila->descripcion_titulo_1,
            "imagen"       => $fila->imagen_1
        ],

        "bloque_2" => [
            "titulo"       => $fila->titulo_2,
            "sub_titulo"   => $fila->sub_titulo_2,
            "descripcion"  => $fila->descripcion_titulo_2,
            "imagen"       => $fila->imagen_2
        ],

        "bloque_3" => [
            "titulo"       => $fila->titulo_3,
            "sub_titulo"   => $fila->sub_titulo_3,
            "descripcion"  => $fila->descripcion_titulo_3,
            "imagen"       => $fila->imagen_3
        ],

        "condicion" => $fila->condicion,
        "idusuario_update" => $fila->idusuario_update,
        "idsucursal_update" => $fila->idsucursal_update,
        "fecha_update" => $fila->fecha_update
    ];
}

echo json_encode([
    "success" => true,
    "data" => $data
]);
