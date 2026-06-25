<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once "../config/Conexion.php";
require_once "../modelos/Ordenes_compra.php"; // 👈 asegúrate de que el nombre coincida con tu archivo real

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    if (!empty($data)) {

        $orden = new Ordenes_compra();

        // --- 📦 Preparar estructura de artículos ---
        // Tu método espera un arreglo: $datosArticulos['articulos'][campo][i]
        $articulos = $data['datosArticulos'] ?? [];

        $datosArticulos = [
            "articulos" => [
                "idarticulo" => [],
                "stockinven" => [],
                "fechavencimiento" => [],
                "cantidadpresentacion" => [],
                "cantidad" => [],
                "totalcantidadpresentacion" => [],
                "presentacion" => [],
                "precio_compra" => [],
                "descuento_porcentaje" => [],
                "precio_venta" => [],
                "precio_ventaNocturno" => [],
                "precio_rango1_Mecanico" => [],
                "precio_rango1_Distribuidor" => [],
                "precio_rango1_Mayorista" => [],
                "precio_rango2_MecanicoDos" => [],
                "precio_rango2_DistribuidorDos" => [],
                "precio_rango2_MayoristaDos" => [],
                "precio_rango3_MecanicoTres" => [],
                "precio_rango3_DistribuidorTres" => [],
                "precio_rango3_MayoristaTres" => [],
                "precio_unidad" => [],
                "precio_blister" => [],
                "precio_caja" => [],
                "precio_fardo" => [],
                "precio_sacos" => [],
                "precio_paquete" => [],
                "precio_07" => [],
                "precio_08" => [],
                "precio_09" => [],
                "precio_10" => [],
                "precio_11" => [],
                "precio_12" => [],
                "precio_13" => [],
                "precio_14" => [],
                "precio_15" => [],
                "precio_16" => [],
                "precio_17" => [],
                "precio_18" => [],
                "precio_19" => [],
                "precio_20" => []
            ]
        ];

        // --- 🧠 Llenar los arrays dinámicamente ---
        foreach ($articulos as $item) {
            foreach ($datosArticulos["articulos"] as $campo => $_) {
                $datosArticulos["articulos"][$campo][] = $item[$campo] ?? 0;
            }
        }

        // --- 🧾 Llamar al método insertar ---
        $resultado = $orden->insertarApi(
            $data['idcliente'] ?? 0,
            $data['codigo_cliente'] ?? '',
            $data['nit'] ?? '',
            $data['nombre_cliente'] ?? '',
            $data['telefono_cliente'] ?? '',
            $data['direccion_cliente'] ?? '',
            $data['correo_cliente'] ?? '',
            $data['tipo_documento_cliente'] ?? '',
            $data['tipo_comprobante'] ?? '',
            $data['serie_comprobante'] ?? '',
            $data['num_comprobante'] ?? '',
            $data['fecha_hora'] ?? date('Y-m-d H:i:s'),
            $data['impuesto'] ?? 0,
            $data['total_compra'] ?? 0,
            $data['total_comprades'] ?? 0,
            $data['forma_pago'] ?? '',
            $data['dias_credito'] ?? 0,
            $data['fecha_hora_pago_credito'] ?? date('Y-m-d'),
            $data['direccion_entrega_orden_compra'] ?? '',
            $data['fecha_entrega_orden_compra'] ?? date('Y-m-d'),
            $data['observacion_orden_compra'] ?? '',
            $data['tipo_ingreso_producion'] ?? '',
            $datosArticulos, // 👈 aquí va como arreglo completo, no JSON
            $data['total_compra_r'] ?? 0,
            $data['total_comprades_r'] ?? 0,
            $data['idusuario'] ?? 0,   
            $data['idsucursal'] ?? 0         
        );

        // --- 🟢 Respuesta final ---
        if ($resultado) {
            echo json_encode([
                "success" => true,
                "message" => "Ingreso registrado correctamente",
                "idorden_compra" => $resultado
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error al registrar el ingreso"
            ]);
        }

    } else {
        echo json_encode([
            "success" => false,
            "message" => "No se recibieron datos válidos"
        ]);
    }

} else {
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido"
    ]);
}
?>
