<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once "../config/Conexion.php";
require_once "../modelos/Ingreso_vehiculo.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $cotizacion = new Ingreso_vehiculo();

        // Directorio donde se guardarán las imágenes
        $ruta_archivos = "../files/articulos/";
        if (!file_exists($ruta_archivos)) {
            mkdir($ruta_archivos, 0777, true);
        }

        // Capturar variables (si vienen en JSON o en POST multipart)
        $idcliente              = $_POST["idcliente"] ?? "0";
        $codigo_cliente         = $_POST["codigo_cliente"] ?? "";
        $nit                    = $_POST["nit"] ?? "";
        $nombre_cliente         = $_POST["nombre_cliente"] ?? "";
        $telefono_cliente       = $_POST["telefono_cliente"] ?? "";
        $direccion_cliente      = $_POST["direccion_cliente"] ?? "";
        $correo_cliente         = $_POST["correo_cliente"] ?? "";
        $tipo_documento_cliente = $_POST["tipo_documento_cliente"] ?? "";
        $tipo_cliente           = $_POST["tipo_cliente"] ?? "";
        $idvendedor             = $_POST["idvendedor"] ?? "";
        $no_placa               = $_POST["no_placa"] ?? "";
        $no_chasis              = $_POST["no_chasis"] ?? "";
        $serie                  = $_POST["serie"] ?? "";
        $no_motor               = $_POST["no_motor"] ?? "";
        $modelo                 = $_POST["modelo"] ?? "";
        $km                     = $_POST["km"] ?? "";
        $trabajos_detalle       = $_POST["trabajos_detalle"] ?? "";
        $observaciones          = $_POST["observaciones_adicionales"] ?? "";

        // Procesar imágenes (hasta 8)
        $nombres_imagenes = [];
        for ($i = 1; $i <= 8; $i++) {
            $campo_imagen = "imagen" . $i;
            $nombre_final = "";

            if (isset($_FILES[$campo_imagen]) && $_FILES[$campo_imagen]['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES[$campo_imagen]["name"], PATHINFO_EXTENSION);
                $tipos_permitidos = ["jpg", "jpeg", "png"];

                if (in_array(strtolower($ext), $tipos_permitidos)) {
                    $nombre_final = round(microtime(true) * 1000) . "_$i.$ext";
                    move_uploaded_file($_FILES[$campo_imagen]["tmp_name"], $ruta_archivos . $nombre_final);
                }
            }
            $nombres_imagenes[] = $nombre_final;
        }

        // Descripciones de imágenes
        $descripcion1 = $_POST["descripcion1"] ?? "";
        $descripcion2 = $_POST["descripcion2"] ?? "";
        $descripcion3 = $_POST["descripcion3"] ?? "";
        $descripcion4 = $_POST["descripcion4"] ?? "";
        $descripcion5 = $_POST["descripcion5"] ?? "";
        $descripcion6 = $_POST["descripcion6"] ?? "";
        $descripcion7 = $_POST["descripcion7"] ?? "";
        $descripcion8 = $_POST["descripcion8"] ?? "";

        // Llamar al modelo
        $resultado = $cotizacion->insertar(
            $idcliente, $codigo_cliente, $nit, $nombre_cliente, $telefono_cliente,
            $direccion_cliente, $correo_cliente, $tipo_documento_cliente,
            $no_placa, $no_chasis, $serie, $no_motor, $modelo, $km,
            $trabajos_detalle, $observaciones, $idvendedor, $tipo_cliente,
            $nombres_imagenes[0], $nombres_imagenes[1], $nombres_imagenes[2], $nombres_imagenes[3],
            $nombres_imagenes[4], $nombres_imagenes[5], $nombres_imagenes[6], $nombres_imagenes[7],
            $descripcion1, $descripcion2, $descripcion3, $descripcion4,
            $descripcion5, $descripcion6, $descripcion7, $descripcion8
        );

        if ($resultado) {
            echo json_encode([
                "success" => true,
                "message" => "Ingreso de vehículo registrado correctamente",
                "id" => $resultado
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error al registrar ingreso de vehículo"
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "Excepción: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido"
    ]);
}
