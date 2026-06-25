<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// Incluir conexión y modelo
require_once "../config/Conexion.php";
require_once "../modelos/Abono.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos JSON del cuerpo
    $data = json_decode(file_get_contents("php://input"), true);

    if (!empty($data)) {
        $abono = new Abono();

        $resultado = $abono->insertarApi(
            $data['idcliente'] ?? null,
            $data['idcredito'] ?? null,
            $data['iddetalle_credito_add'] ?? null,
            $data['total_pagar'] ?? 0,
            $data['saldopendientexpagar'] ?? 0,
            $data['saldoAfavor'] ?? 0,
            $data['morapendientemonto'] ?? 0,
            $data['total_cuotas_vencidas'] ?? 0,
            $data['valor_cuotadiaria'] ?? 0,
            $data['abonototal'] ?? 0,
            $data['saldocuotasAdelantadas'] ?? 0,
            $data['descuento_abono'] ?? 0,
            $data['abonoRecibido'] ?? 0,
            $data['saldopendientexpagarAbono'] ?? 0,
            $data['idusuario'] ?? null,
            $data['ubicacion_pago'] ?? null,
            $data['ubicacion_casa'] ?? null,
            $data['detalle_mora_credito'] ?? 0,
            $data['detalle_total_dias_vencidos'] ?? 0,
            $data['nota_pago'] ?? 0,
            $data['c_efectivo'] ?? 0,
            $data['c_transferencia'] ?? 0,
            $data['forma_pago'] ?? 0

        );

        if ($resultado) {
            echo json_encode([
                "success" => true,
                "message" => "Abono registrado correctamente",
                "idabono" => $resultado // 👈 devolvemos el id
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error al registrar abono"
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Datos incompletos"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido"
    ]);
}
