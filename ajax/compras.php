<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Compras.php";

$compras = new Compras();


$idcompra = isset($_POST["idcompra"]) ? limpiarCadena($_POST["idcompra"]) : "";
$idcliente_GastoaVenta = isset($_POST["idcliente_GastoaVenta"]) ? limpiarCadena($_POST["idcliente_GastoaVenta"]) : "";
$idusuario = $_SESSION["idusuario"];
$serie_no = isset($_POST["serie_no"]) ? limpiarCadena($_POST["serie_no"]) : "";
$factura_no = isset($_POST["factura_no"]) ? limpiarCadena($_POST["factura_no"]) : "";
$mes_a_contabilizar = isset($_POST["mes_a_contabilizar"]) ? limpiarCadena($_POST["mes_a_contabilizar"]) : "";
$ano_contabilizar = isset($_POST["ano_contabilizar"]) ? limpiarCadena($_POST["ano_contabilizar"]) : "";
$tipo_factura = isset($_POST["tipo_factura"]) ? limpiarCadena($_POST["tipo_factura"]) : "";
$tipo_documento_cliente_GastoaVenta = isset($_POST["tipo_documento_cliente_GastoaVenta"]) ? limpiarCadena($_POST["tipo_documento_cliente_GastoaVenta"]) : "";
$nit_no = isset($_POST["nit_no"]) ? limpiarCadena($_POST["nit_no"]) : "";
$proveedor = isset($_POST["proveedor"]) ? limpiarCadena($_POST["proveedor"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
$fecha_hora = isset($_POST["fecha_hora"]) ? limpiarCadena($_POST["fecha_hora"]) : "";
$valor_q = isset($_POST["valor_q"]) ? limpiarCadena($_POST["valor_q"]) : "";
$tipo_compra = isset($_POST["tipo_compra"]) ? limpiarCadena($_POST["tipo_compra"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        // Insertar o actualizar
        if (empty($idcompra)) {
            $rspta = $compras->insertar(
                $idcliente_GastoaVenta,
                $idusuario,
                $serie_no,
                $factura_no,
                $mes_a_contabilizar,
                $ano_contabilizar,
                $tipo_factura,
                $tipo_documento_cliente_GastoaVenta,
                $nit_no,
                $proveedor,
                $direccion,
                $fecha_hora,
                $valor_q,
                $tipo_compra
            );
            echo $rspta ? "Compra registrada" : "Compra no se pudo registrar";
        } else {
            $rspta = $compras->editar(
                $idcompra,
                $idcliente_GastoaVenta,
                $idusuario,
                $serie_no,
                $factura_no,
                $mes_a_contabilizar,
                $ano_contabilizar,
                $tipo_factura,
                $tipo_documento_cliente_GastoaVenta,
                $nit_no,
                $proveedor,
                $direccion,
                $fecha_hora,
                $valor_q,
                $tipo_compra
            );
            echo $rspta ? "Compra actualizada" : "Compra no se pudo actualizar";
        }
        break;

    case 'desactivar':
        $rspta = $compras->desactivar($idcompra);
        echo $rspta ? "Compra Desactivada" : "Compra no se puede desactivar";

        break;



    case 'mostrar':
        $rspta = $compras->mostrar($idcompra);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $compras->listar();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $botones = "";
            if ($reg->condicion) { // Si la condición es 1 (Activado)
                $botones =
                    '<button class="btn btn-danger" onclick="desactivar(' . $reg->idcompra . ')">
                            <i class="fa fa-close"></i>
                         </button>';
            } else { // Si la condición no es 1 (Desactivado)
                $botones =
                    '<span class="label bg-red">Compra Anulada</span>';
            }
            $data[] = array(
                "0" => $botones,
                "1" => $reg->fecha_hora,
                "2" => $reg->serie_no,
                "3" => $reg->factura_no,
                "4" => $reg->mes_a_contabilizar,
                "5" => $reg->ano_contabilizar,
                "6" => $reg->tipo_factura,
                "7" => $reg->nit_no,
                "8" => $reg->nombre_persona,
                "9" => $reg->valor_q,
                "10" => $reg->tipo_compra,
                "11" => $reg->nombre_usuario,
                "12" => $reg->concepto_fac,
                "13" => ($reg->condicion) ? '<span class="label bg-green">Activado</span>' :
                    '<span class="label bg-red">Desactivado</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;
}
?>