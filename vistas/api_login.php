<?php
header("Access-Control-Allow-Origin: *"); // Permite acceso desde cualquier origen (puedes limitarlo)
header("Content-Type: application/json; charset=UTF-8");

// Incluir archivo de conexión y modelo
require_once "../config/conexion.php";
require_once "../modelos/Usuario.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos JSON del cuerpo
    $data = json_decode(file_get_contents("php://input"), true);

    if (!empty($data['login']) && !empty($data['clave'])) {
        $login = $data['login'];
        $clave = hash("SHA256", $data['clave']); // hasheamos aquí también, por seguridad

        $usuario = new Usuario();
        $resultado = $usuario->verificar($login, $clave);

        if ($fila = $resultado->fetch_object()) {
            // Usuario encontrado
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
                    "idsucursal"      => $fila->idsucursal,
                    "clave_ordenes"   => $fila->clave_ordenes,
                    "clave_ingresos"  => $fila->clave_ingresos,
                    "clave_ventas"    => $fila->clave_ventas
                ]
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Usuario o clave incorrectos."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Parámetros incompletos."
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido."
    ]);
}
?>
