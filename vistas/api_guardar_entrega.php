<?php
require_once "../config/conexion.php";
require_once "../modelos/consultasApp.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (
        isset($data->idventa) &&
        isset($data->estadoventamensajero) &&
        isset($data->comentarioVentaEntregaMensajero) &&
        isset($data->ubicacionmaps) &&
        isset($data->firma)
    ) {
        $venta = new Venta();

        $idventa = $data->idventa;
        $estado = $data->estadoventamensajero;
        $comentario = $data->comentarioVentaEntregaMensajero;
        $ubicacion = $data->ubicacionmaps;
        $firmaBase64 = $data->firma;

        // Guardar imagen desde base64
        $nombreArchivo = "firma_" . $idventa . "_" . time() . ".png";
        $ruta = "../files/firmas/" . $nombreArchivo;

        if (!file_exists("../files/firmas/")) {
            mkdir("../files/firmas/", 0777, true);
        }

        // Decodificar y guardar el archivo de la firma
        $firmaBinaria = base64_decode($firmaBase64);
        file_put_contents($ruta, $firmaBinaria);

        // Guardar en la base de datos
        $respuesta = $venta->GuadarEntregaMensajeroApp(
            $estado,
            $idventa,
            $comentario,
            $ubicacion,
            $nombreArchivo // se guarda el nombre del archivo
        );

        echo json_encode(["status" => "success", "message" => "Entrega registrada correctamente."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Datos incompletos."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
?>
