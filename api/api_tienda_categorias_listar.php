<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once "../config/Conexion.php";
require_once "../modelos/Categoria.php";

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
$categoria = new Categoria();
$resultado = $categoria->selectCategoriaslistar();

// Construir respuesta JSON
$lista = [];

while ($fila = $resultado->fetch_object()) {
    $lista[] = [
        "idcategoria" => $fila->idcategoria,
        "nombre"      => $fila->nombre,
        "descripcion" => $fila->descripcion,
        "condicion"   => $fila->condicion
    ];
}

echo json_encode([
    "success" => true,
    "data" => $lista
]);
