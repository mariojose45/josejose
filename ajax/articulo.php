<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Articulo.php";

$articulo = new Articulo();

$idarticulo = isset($_POST["idarticulo"]) ? limpiarCadena($_POST["idarticulo"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$crearArticuloSucursal = isset($_POST["crearArticuloSucursal"]) ? limpiarCadena($_POST["crearArticuloSucursal"]) : "";

$facturar_cero = isset($_POST["facturar_cero"]) ? limpiarCadena($_POST["facturar_cero"]) : "";

$idcategoria = isset($_POST["idcategoria"]) ? limpiarCadena($_POST["idcategoria"]) : "";
$idsubcategoria = isset($_POST["idsubcategoria"]) ? limpiarCadena($_POST["idsubcategoria"]) : "";
$idempresa = isset($_POST["idempresa"]) ? limpiarCadena($_POST["idempresa"]) : "";

$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
$descripcion_2 = isset($_POST["descripcion_2"]) ? limpiarCadena($_POST["descripcion_2"]) : "";
$aplica_comision = isset($_POST["aplica_comision"]) ? limpiarCadena($_POST["aplica_comision"]) : "";

$stock = isset($_POST["stock"]) ? limpiarCadena($_POST["stock"]) : "";
$stockminimo = isset($_POST["stockminimo"]) ? limpiarCadena($_POST["stockminimo"]) : "";
$stockmaximo = isset($_POST["stockmaximo"]) ? limpiarCadena($_POST["stockmaximo"]) : "";

$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";
$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
$codigo_sku = isset($_POST["codigo_sku"]) ? limpiarCadena($_POST["codigo_sku"]) : "";
$precio_compra = isset($_POST["precio_compra"]) ? limpiarCadena($_POST["precio_compra"]) : "";

$ganacia_articulo = isset($_POST["ganacia_articulo"]) ? limpiarCadena($_POST["ganacia_articulo"]) : "";
$tipo_ganacia = "0";
$pocentaje_ganacia = isset($_POST["pocentaje_ganacia"]) ? limpiarCadena($_POST["pocentaje_ganacia"]) : "";

$precio_venta = isset($_POST["precio_venta"]) ? limpiarCadena($_POST["precio_venta"]) : "";
$precio_ventaNocturno = isset($_POST["precio_ventaNocturno"]) ? limpiarCadena($_POST["precio_ventaNocturno"]) : "";

$descuento_porcentaje = isset($_POST["descuento_porcentaje"]) ? limpiarCadena($_POST["descuento_porcentaje"]) : "";
$tipo_descuento = isset($_POST["tipo_descuento"]) ? limpiarCadena($_POST["tipo_descuento"]) : "";

$precio_descuento = isset($_POST["precio_descuento"]) ? limpiarCadena($_POST["precio_descuento"]) : "";
$precio_rango1 = isset($_POST["precio_rango1"]) ? limpiarCadena($_POST["precio_rango1"]) : "";
$precio_rango1_Dos = isset($_POST["precio_rango1_Dos"]) ? limpiarCadena($_POST["precio_rango1_Dos"]) : "";
$precio_rango2 = isset($_POST["precio_rango2"]) ? limpiarCadena($_POST["precio_rango2"]) : "";
$precio_rango2_Dos = isset($_POST["precio_rango2_Dos"]) ? limpiarCadena($_POST["precio_rango2_Dos"]) : "";
$precio_rango3 = isset($_POST["precio_rango3"]) ? limpiarCadena($_POST["precio_rango3"]) : "";
$precio_rango3_Dos = isset($_POST["precio_rango3_Dos"]) ? limpiarCadena($_POST["precio_rango3_Dos"]) : "";

$precio_rango1_Mecanico = isset($_POST["precio_rango1_Mecanico"]) ? limpiarCadena($_POST["precio_rango1_Mecanico"]) : "";
$precio_rango2_MecanicoDos = isset($_POST["precio_rango2_MecanicoDos"]) ? limpiarCadena($_POST["precio_rango2_MecanicoDos"]) : "";
$precio_rango3_MecanicoTres = isset($_POST["precio_rango3_MecanicoTres"]) ? limpiarCadena($_POST["precio_rango3_MecanicoTres"]) : "";

$precio_rango1_Distribuidor = isset($_POST["precio_rango1_Distribuidor"]) ? limpiarCadena($_POST["precio_rango1_Distribuidor"]) : "";
$precio_rango2_DistribuidorDos = isset($_POST["precio_rango2_DistribuidorDos"]) ? limpiarCadena($_POST["precio_rango2_DistribuidorDos"]) : "";
$precio_rango3_DistribuidorTres = isset($_POST["precio_rango3_DistribuidorTres"]) ? limpiarCadena($_POST["precio_rango3_DistribuidorTres"]) : "";

$precio_rango1_Mayorista = isset($_POST["precio_rango1_Mayorista"]) ? limpiarCadena($_POST["precio_rango1_Mayorista"]) : "";
$precio_rango2_MayoristaDos = isset($_POST["precio_rango2_MayoristaDos"]) ? limpiarCadena($_POST["precio_rango2_MayoristaDos"]) : "";
$precio_rango3_MayoristaTres = isset($_POST["precio_rango3_MayoristaTres"]) ? limpiarCadena($_POST["precio_rango3_MayoristaTres"]) : "";


$tipo_producto = isset($_POST["tipo_producto"]) ? limpiarCadena($_POST["tipo_producto"]) : "";

$nombre_01 = isset($_POST["nombre_01"]) ? limpiarCadena($_POST["nombre_01"]) : "";
$stock_unidad = isset($_POST["stock_unidad"]) ? limpiarCadena($_POST["stock_unidad"]) : "";
$precio_unidad = isset($_POST["precio_unidad"]) ? limpiarCadena($_POST["precio_unidad"]) : "";

$nombre_02 = isset($_POST["nombre_02"]) ? limpiarCadena($_POST["nombre_02"]) : "";
$stock_blister = isset($_POST["stock_blister"]) ? limpiarCadena($_POST["stock_blister"]) : "";
$precio_blister = isset($_POST["precio_blister"]) ? limpiarCadena($_POST["precio_blister"]) : "";

$nombre_03 = isset($_POST["nombre_03"]) ? limpiarCadena($_POST["nombre_03"]) : "";
$stock_caja = isset($_POST["stock_caja"]) ? limpiarCadena($_POST["stock_caja"]) : "";
$precio_caja = isset($_POST["precio_caja"]) ? limpiarCadena($_POST["precio_caja"]) : "";

$nombre_04 = isset($_POST["nombre_04"]) ? limpiarCadena($_POST["nombre_04"]) : "";
$stock_fardo = isset($_POST["stock_fardo"]) ? limpiarCadena($_POST["stock_fardo"]) : "";
$precio_fardo = isset($_POST["precio_fardo"]) ? limpiarCadena($_POST["precio_fardo"]) : "";

$nombre_05 = isset($_POST["nombre_05"]) ? limpiarCadena($_POST["nombre_05"]) : "";
$stock_sacos = isset($_POST["stock_sacos"]) ? limpiarCadena($_POST["stock_sacos"]) : "";
$precio_sacos = isset($_POST["precio_sacos"]) ? limpiarCadena($_POST["precio_sacos"]) : "";

$nombre_06 = isset($_POST["nombre_06"]) ? limpiarCadena($_POST["nombre_06"]) : "";
$stock_paquete = isset($_POST["stock_paquete"]) ? limpiarCadena($_POST["stock_paquete"]) : "";
$precio_paquete = isset($_POST["precio_paquete"]) ? limpiarCadena($_POST["precio_paquete"]) : "";


$nombre_07 = isset($_POST["nombre_07"]) ? limpiarCadena($_POST["nombre_07"]) : "";
$stock_07 = isset($_POST["stock_07"]) ? limpiarCadena($_POST["stock_07"]) : "";
$precio_07 = isset($_POST["precio_07"]) ? limpiarCadena($_POST["precio_07"]) : "";

$nombre_08 = isset($_POST["nombre_08"]) ? limpiarCadena($_POST["nombre_08"]) : "";
$stock_08 = isset($_POST["stock_08"]) ? limpiarCadena($_POST["stock_08"]) : "";
$precio_08 = isset($_POST["precio_08"]) ? limpiarCadena($_POST["precio_08"]) : "";

$nombre_09 = isset($_POST["nombre_09"]) ? limpiarCadena($_POST["nombre_09"]) : "";
$stock_09 = isset($_POST["stock_09"]) ? limpiarCadena($_POST["stock_09"]) : "";
$precio_09 = isset($_POST["precio_09"]) ? limpiarCadena($_POST["precio_09"]) : "";

$nombre_10 = isset($_POST["nombre_10"]) ? limpiarCadena($_POST["nombre_10"]) : "";
$stock_10 = isset($_POST["stock_10"]) ? limpiarCadena($_POST["stock_10"]) : "";
$precio_10 = isset($_POST["precio_10"]) ? limpiarCadena($_POST["precio_10"]) : "";

$nombre_11 = isset($_POST["nombre_11"]) ? limpiarCadena($_POST["nombre_11"]) : "";
$stock_11 = isset($_POST["stock_11"]) ? limpiarCadena($_POST["stock_11"]) : "";
$precio_11 = isset($_POST["precio_11"]) ? limpiarCadena($_POST["precio_11"]) : "";

$nombre_12 = isset($_POST["nombre_12"]) ? limpiarCadena($_POST["nombre_12"]) : "";
$stock_12 = isset($_POST["stock_12"]) ? limpiarCadena($_POST["stock_12"]) : "";
$precio_12 = isset($_POST["precio_12"]) ? limpiarCadena($_POST["precio_12"]) : "";

$nombre_13 = isset($_POST["nombre_13"]) ? limpiarCadena($_POST["nombre_13"]) : "";
$stock_13 = isset($_POST["stock_13"]) ? limpiarCadena($_POST["stock_13"]) : "";
$precio_13 = isset($_POST["precio_13"]) ? limpiarCadena($_POST["precio_13"]) : "";

$nombre_14 = isset($_POST["nombre_14"]) ? limpiarCadena($_POST["nombre_14"]) : "";
$stock_14 = isset($_POST["stock_14"]) ? limpiarCadena($_POST["stock_14"]) : "";
$precio_14 = isset($_POST["precio_14"]) ? limpiarCadena($_POST["precio_14"]) : "";

$nombre_15 = isset($_POST["nombre_15"]) ? limpiarCadena($_POST["nombre_15"]) : "";
$stock_15 = isset($_POST["stock_15"]) ? limpiarCadena($_POST["stock_15"]) : "";
$precio_15 = isset($_POST["precio_15"]) ? limpiarCadena($_POST["precio_15"]) : "";

$nombre_16 = isset($_POST["nombre_16"]) ? limpiarCadena($_POST["nombre_16"]) : "";
$stock_16 = isset($_POST["stock_16"]) ? limpiarCadena($_POST["stock_16"]) : "";
$precio_16 = isset($_POST["precio_16"]) ? limpiarCadena($_POST["precio_16"]) : "";

$nombre_17 = isset($_POST["nombre_17"]) ? limpiarCadena($_POST["nombre_17"]) : "";
$stock_17 = isset($_POST["stock_17"]) ? limpiarCadena($_POST["stock_17"]) : "";
$precio_17 = isset($_POST["precio_17"]) ? limpiarCadena($_POST["precio_17"]) : "";

$nombre_18 = isset($_POST["nombre_18"]) ? limpiarCadena($_POST["nombre_18"]) : "";
$stock_18 = isset($_POST["stock_18"]) ? limpiarCadena($_POST["stock_18"]) : "";
$precio_18 = isset($_POST["precio_18"]) ? limpiarCadena($_POST["precio_18"]) : "";

$nombre_19 = isset($_POST["nombre_19"]) ? limpiarCadena($_POST["nombre_19"]) : "";
$stock_19 = isset($_POST["stock_19"]) ? limpiarCadena($_POST["stock_19"]) : "";
$precio_19 = isset($_POST["precio_19"]) ? limpiarCadena($_POST["precio_19"]) : "";

$nombre_20 = isset($_POST["nombre_20"]) ? limpiarCadena($_POST["nombre_20"]) : "";
$stock_20 = isset($_POST["stock_20"]) ? limpiarCadena($_POST["stock_20"]) : "";
$precio_20 = isset($_POST["precio_20"]) ? limpiarCadena($_POST["precio_20"]) : "";

$producto_consignacion = isset($_POST["producto_consignacion"]) ? limpiarCadena($_POST["producto_consignacion"]) : "";
$aplica_impuestos = isset($_POST["aplica_impuestos"]) ? limpiarCadena($_POST["aplica_impuestos"]) : "";
$precio_activo_si_no = isset($_POST["precio_activo_si_no"]) ? limpiarCadena($_POST["precio_activo_si_no"]) : "";


switch ($_GET["op"]) {
    case 'guardaryeditar':

        if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
            $imagen = $_POST["imagenactual"];
        } else {
            $ext = explode(".", $_FILES["imagen"]["name"]);
            if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png") {
                $imagen = round(microtime(true)) . '.' . end($ext);
                move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/articulos/" . $imagen);
            }
        }
        if (empty($idarticulo)) {
            $rspta = $articulo->insertar(
                $nombre,
                $crearArticuloSucursal,
                $facturar_cero,
                $idcategoria,
                $idsubcategoria,
                $descripcion,
                $descripcion_2,
                $aplica_comision,
                $stock,
                $stockminimo,
                $imagen,
                $codigo,
                $precio_compra,
                $ganacia_articulo,
                $tipo_ganacia,
                $precio_venta,
                $precio_ventaNocturno,
                $descuento_porcentaje,
                $tipo_descuento,
                $precio_descuento,
                $precio_rango1,
                $precio_rango1_Dos,
                $precio_rango2,
                $precio_rango2_Dos,
                $precio_rango3,
                $precio_rango3_Dos,
                $precio_rango1_Mecanico,
                $precio_rango2_MecanicoDos,
                $precio_rango3_MecanicoTres,
                $precio_rango1_Distribuidor,
                $precio_rango2_DistribuidorDos,
                $precio_rango3_DistribuidorTres,
                $precio_rango1_Mayorista,
                $precio_rango2_MayoristaDos,
                $precio_rango3_MayoristaTres,
                $tipo_producto,
                $nombre_01,
                $stock_unidad,
                $precio_unidad,
                $nombre_02,
                $stock_blister,
                $precio_blister,
                $nombre_03,
                $stock_caja,
                $precio_caja,
                $nombre_04,
                $stock_fardo,
                $precio_fardo,
                $nombre_05,
                $stock_sacos,
                $precio_sacos,
                $nombre_06,
                $stock_paquete,
                $precio_paquete,
                $nombre_07,
                $stock_07,
                $precio_07,
                $nombre_08,
                $stock_08,
                $precio_08,
                $nombre_09,
                $stock_09,
                $precio_09,
                $nombre_10,
                $stock_10,
                $precio_10,
                $nombre_11,
                $stock_11,
                $precio_11,
                $nombre_12,
                $stock_12,
                $precio_12,
                $nombre_13,
                $stock_13,
                $precio_13,
                $nombre_14,
                $stock_14,
                $precio_14,
                $nombre_15,
                $stock_15,
                $precio_15,
                $nombre_16,
                $stock_16,
                $precio_16,
                $nombre_17,
                $stock_17,
                $precio_17,
                $nombre_18,
                $stock_18,
                $precio_18,
                $nombre_19,
                $stock_19,
                $precio_19,
                $nombre_20,
                $stock_20,
                $precio_20,
                $producto_consignacion,
                $aplica_impuestos,
                $codigo_sku,
                $stockmaximo,
                $precio_activo_si_no,
                $idempresa,
                $pocentaje_ganacia
            );
            echo $rspta;
        } else {
            $rspta = $articulo->editar(
                $idarticulo,
                $nombre,
                $crearArticuloSucursal,
                $facturar_cero,
                $idcategoria,
                $idsubcategoria,
                $descripcion,
                $descripcion_2,
                $aplica_comision,
                $stock,
                $stockminimo,
                $imagen,
                $codigo,
                $precio_compra,
                $ganacia_articulo,
                $tipo_ganacia,
                $precio_venta,
                $precio_ventaNocturno,
                $descuento_porcentaje,
                $tipo_descuento,
                $precio_descuento,
                $precio_rango1,
                $precio_rango1_Dos,
                $precio_rango2,
                $precio_rango2_Dos,
                $precio_rango3,
                $precio_rango3_Dos,
                $precio_rango1_Mecanico,
                $precio_rango2_MecanicoDos,
                $precio_rango3_MecanicoTres,
                $precio_rango1_Distribuidor,
                $precio_rango2_DistribuidorDos,
                $precio_rango3_DistribuidorTres,
                $precio_rango1_Mayorista,
                $precio_rango2_MayoristaDos,
                $precio_rango3_MayoristaTres,
                $tipo_producto,
                $nombre_01,
                $stock_unidad,
                $precio_unidad,
                $nombre_02,
                $stock_blister,
                $precio_blister,
                $nombre_03,
                $stock_caja,
                $precio_caja,
                $nombre_04,
                $stock_fardo,
                $precio_fardo,
                $nombre_05,
                $stock_sacos,
                $precio_sacos,
                $nombre_06,
                $stock_paquete,
                $precio_paquete,
                $nombre_07,
                $stock_07,
                $precio_07,
                $nombre_08,
                $stock_08,
                $precio_08,
                $nombre_09,
                $stock_09,
                $precio_09,
                $nombre_10,
                $stock_10,
                $precio_10,
                $nombre_11,
                $stock_11,
                $precio_11,
                $nombre_12,
                $stock_12,
                $precio_12,
                $nombre_13,
                $stock_13,
                $precio_13,
                $nombre_14,
                $stock_14,
                $precio_14,
                $nombre_15,
                $stock_15,
                $precio_15,
                $nombre_16,
                $stock_16,
                $precio_16,
                $nombre_17,
                $stock_17,
                $precio_17,
                $nombre_18,
                $stock_18,
                $precio_18,
                $nombre_19,
                $stock_19,
                $precio_19,
                $nombre_20,
                $stock_20,
                $precio_20,
                $producto_consignacion,
                $aplica_impuestos,
                $codigo_sku,
                $stockmaximo,
                $precio_activo_si_no,
                $idempresa,
                $pocentaje_ganacia
            );
            echo $rspta ? "Artículo actualizado" : "Artículo no se pudo actualizar";
        }
        break;

    case 'actualizarStock':
        $idarticuloxsucursal = isset($_POST["idarticuloxsucursal"]) ? limpiarCadena($_POST["idarticuloxsucursal"]) : "";
        $idarticulo = isset($_POST["idarticulo"]) ? limpiarCadena($_POST["idarticulo"]) : "";
        $stock = isset($_POST["stock"]) ? limpiarCadena($_POST["stock"]) : "";

        $rspta = $articulo->actualizarStock($idarticuloxsucursal, $idarticulo, $stock);
        echo $rspta ? "Stock actualizado correctamente" : "Error al actualizar el stock";
        break;

    case 'desactivar':
        $rspta = $articulo->desactivar($idarticulo);
        echo $rspta ? "Artículo Desactivado" : "Artículo no se puede desactivar";
        break;

    case 'activar':
        $rspta = $articulo->activar($idarticulo);
        echo $rspta ? "Artículo activado" : "Artículo no se puede activar";
        break;

    case 'mostrar':
        $rspta = $articulo->mostrar($idarticulo);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;

    case 'listar':

        $estadofiltro = isset($_REQUEST["estadofiltro"]) ? limpiarCadena($_REQUEST["estadofiltro"]) : "1";

        // 🔥 PAGINACIÓN (NUEVO)
        $start = isset($_GET["start"]) ? intval($_GET["start"]) : 0;
        $length = isset($_GET["length"]) ? intval($_GET["length"]) : 10;

        // 🔥 LLAMAMOS MODELO CON PAGINACIÓN
        $rspta = $articulo->listar($estadofiltro, $start, $length);

        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => ($reg->condicion) ? '<button class="btn btn-warning" onclick="mostrar(' . $reg->idarticulo . ')"><i class="fa fa-pencil"></i></button>' .
                    ' <button class="btn btn-danger" onclick="desactivar(' . $reg->idarticulo . ')"><i class="fa fa-close"></i></button>' :
                    '<button class="btn btn-warning" onclick="mostrar(' . $reg->idarticulo . ')"><i class="fa fa-pencil"></i></button>' .
                    ' <button class="btn btn-primary" onclick="activar(' . $reg->idarticulo . ')"><i class="fa fa-check"></i></button>',

                "1" => '<a href="javascript:void(0)" onclick="verDetallesArticulo(' . $reg->idarticulo . ',
                \'' . $reg->tipo_producto . '\',
                \'' . $reg->aplica_comision . '\',
                \'' . $reg->precio_ventaNocturno . '\',
                \'' . $reg->descuento_porcentaje . '\',
                \'' . $reg->precio_descuento . '\',
                \'' . $reg->precio_rango1 . '\',
                \'' . $reg->precio_rango1_Dos . '\',
                \'' . $reg->precio_rango2 . '\',
                \'' . $reg->precio_rango2_Dos . '\',
                \'' . $reg->precio_rango3 . '\',
                \'' . $reg->precio_rango3_Dos . '\',
                \'' . $reg->precio_rango1_Mecanico . '\',
                \'' . $reg->precio_rango2_MecanicoDos . '\',
                \'' . $reg->precio_rango3_MecanicoTres . '\',
                \'' . $reg->precio_rango1_Distribuidor . '\',
                \'' . $reg->precio_rango2_DistribuidorDos . '\',
                \'' . $reg->precio_rango3_DistribuidorTres . '\',
                \'' . $reg->precio_rango1_Mayorista . '\',
                \'' . $reg->precio_rango2_MayoristaDos . '\',
                \'' . $reg->precio_rango3_MayoristaTres . '\',
                \'' . $reg->stock_unidad . '\',
                \'' . $reg->precio_unidad . '\',
                \'' . $reg->stock_blister . '\',
                \'' . $reg->precio_blister . '\',
                \'' . $reg->stock_caja . '\',
                \'' . $reg->precio_caja . '\',
                \'' . $reg->stock_fardo . '\',
                \'' . $reg->precio_fardo . '\',
                \'' . $reg->stock_sacos . '\',
                \'' . $reg->precio_sacos . '\',
                \'' . $reg->stock_paquete . '\',
                \'' . $reg->precio_paquete . '\',
                \'' . $reg->fecha_creacion . '\',
                \'' . $reg->user_creacion . '\',
                \'' . $reg->fecha_modificacion . '\',
                \'' . $reg->user_mod . '\',
                \'' . $reg->nombreSucursal . '\',
                \'' . $reg->facturar_cero . '\',
                \'' . $reg->tipo_descuento . '\',
                \'' . $reg->producto_consignacion . '\',
                \'' . $reg->aplica_impuestos . '\')">' . $reg->nombre . ' (mas click..)</a>',

                "2" => (
                    $reg->dias_vencimiento === null || $reg->dias_vencimiento === '' ?
                    '<span class="label" style="background-color: #00c0ef; font-size: 16px;">Sin días</span>' :
                    ($reg->dias_vencimiento <= -90 ?
                        '<span class="label bg-green" style="font-size: 16px;">' . $reg->dias_vencimiento . '</span>' :
                        ($reg->dias_vencimiento <= -60 ?
                            '<span class="label bg-yellow" style="font-size: 16px;">' . $reg->dias_vencimiento . '</span>' :
                            ($reg->dias_vencimiento <= -30 ?
                                '<span class="label bg-red" style="font-size: 16px;">' . $reg->dias_vencimiento . '</span>' :
                                '<span class="label bg-gray" style="font-size: 16px;">' . $reg->dias_vencimiento . '</span>')))
                ),

                "3" => $reg->categoria,
                "4" => $reg->subcategoria,
                "5" => $reg->descripcion,
                "6" => $reg->descripcion_2,

                "7" => (
                    $reg->tipo_producto === 'Productos'
                    ? '<input type="number" min="0" step="1" class="form-control input-lg" style="width:120px; height:40px;" value="' . $reg->stock . '" onblur="actualizarStock(\'' . $reg->idarticuloxsucursal . '\', \'' . $reg->idarticulo . '\', this.value)">'
                    : '<input type="number" class="form-control input-lg" style="width:120px; height:40px;" value="' . $reg->stock . '" disabled>'
                ),

                "8" => $reg->stock,
                "9" => ($reg->stockminimo <= $reg->stock) ? '<span class="label bg-green">' . $reg->stockminimo . '</span>' :
                    '<span class="label bg-red">' . $reg->stockminimo . ' MINIMO</span>',

                "10" => ((!empty($reg->imagen) && file_exists("../files/articulos/" . $reg->imagen)) ?
                    "<img src='../files/articulos/" . $reg->imagen . "' height='50px' width='50px'>" :
                    "<img src='../files/articulos/nofoto.jpg' height='50px' width='50px'>"),

                "11" => $reg->codigo,
                "12" => $reg->precio_compra,
                "13" => $reg->precio_venta,
                "14" => ($reg->condicion) ? '<span class="label bg-green">Activado</span>' :
                    '<span class="label bg-red">Desactivado</span>'
            );
        }

        // 🔥 TOTAL REAL (NUEVO)
        $total = $articulo->totalRegistros();

        // 🔥 RESPUESTA CORRECTA DATATABLE
        $results = array(
            "draw" => intval($_GET['draw']),
            "recordsTotal" => $total['total'],
            "recordsFiltered" => $total['total'],
            "data" => $data
        );

        echo json_encode($results);

        break;



    case 'listarxsucursal':
        $rspta = $articulo->listarxsucursal();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->dias_vencimiento,
                "1" => $reg->nombre,
                "2" => $reg->descripcion,
                "3" => $reg->descripcion_2,
                "4" => $reg->categoria,
                "5" => $reg->tipo_producto,
                "6" => $reg->codigo,
                "7" => $reg->stock,
                "8" => $reg->stockminimo,
                "9" => ($reg->stockminimo <= $reg->stock) ? '<span class="label bg-green">' . $reg->stockminimo . '</span>' :
                    '<span class="label bg-red">' . $reg->stockminimo . '</span>',
                "10" => "<img src='../files/articulos/" . $reg->imagen . "' height='50px' width='50px' >",
                "11" => $reg->precio_venta,
                "12" => $reg->precio_unidad,
                "13" => $reg->precio_blister,
                "14" => $reg->precio_caja,
                "15" => $reg->precio_fardo,
                "16" => $reg->precio_sacos,
                "17" => $reg->precio_paquete,
                "18" => $reg->age_sucursal,
                "19" => ($reg->condicion) ? '<span class="label bg-green">Activado</span>' :
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


    case 'listarxGeneral':
        $rspta = $articulo->listarxGeneral();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->dias_vencimiento,
                "1" => $reg->nombre,
                "2" => $reg->descripcion,
                "3" => $reg->descripcion_2,
                "4" => $reg->categoria,
                "5" => $reg->tipo_producto,
                "6" => $reg->codigo,
                "7" => $reg->stock,
                "8" => $reg->stockminimo,
                "9" => ($reg->stockminimo <= $reg->stock) ? '<span class="label bg-green">Stock Normal</span>' :
                    '<span class="label bg-red">Stock Bajo</span>',
                "10" => $reg->precio_venta,
                "11" => $reg->age_sucursal,
                "12" => ($reg->condicion) ? '<span class="label bg-green">Activado</span>' :
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

    case 'listarinvenariosucursal':
        $rspta = $articulo->listarinvenariosucursal();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->nombre,
                "1" => $reg->categoria,
                "2" => $reg->codigo,
                "3" => $reg->stock,
                "4" => $reg->stockminimo,
                "5" => ($reg->stockminimo <= $reg->stock) ? '<span class="label bg-green">Stock Normal</span>' :
                    '<span class="label bg-red">Stock Bajo</span>',
                "6" => "<img src='../files/articulos/" . $reg->imagen . "' height='50px' width='50px' >",
                "7" => $reg->precio_venta,
                "8" => $reg->nombre_sucursal,
                "9" => ($reg->condicion) ? '<span class="label bg-green">Activado</span>' :
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

    case "selectCategoria":
        require_once "../modelos/Categoria.php";
        $categoria = new Categoria();

        $rspta = $categoria->select();

        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idcategoria . '>' . $reg->nombre . '</option>';
        }
        break;

    case "selectCategoriaSubCategoria":

        $idcategoria_actual = isset($_POST["idcategoria_actual"]) ? $_POST["idcategoria_actual"] : 0;
        require_once "../modelos/Categoria.php";
        $categoria = new Categoria();

        $rspta = $categoria->selectCategoriaSubCategoria($idcategoria_actual);

        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idcategoria . '>' . $reg->nombre . '</option>';
        }
        break;


    case "selectSubcategoria": // Cambiado a minúscula para coincidir con el JS
        $idcategoria = $_POST['idcategoria'];
        require_once "../modelos/Sub_Categoria.php";
        $subcategoria = new SubCategoria();

        $rspta = $subcategoria->select($idcategoria);

        // Agregar una opción por defecto
        echo '<option value="">Seleccione una subcategoría</option>';

        while ($reg = $rspta->fetch_object()) {
            // Agregar comillas a los valores para evitar problemas con nombres que contengan espacios
            echo '<option value="' . $reg->idsubcategoria . '">' . $reg->nombre . '</option>';
        }
        break;

    case "selectEmpresa":
        $rspta = $articulo->selectEmpresa();
        echo '<option value="">Seleccione una Empresa</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idempresa . '>' . $reg->nombre . '</option>';
        }
        break;


    case 'selectMarca':
        $rspta = $articulo->selectMarca();
        $dbdata = array();
        //Fetch into associative array
        while ($row = $rspta->fetch_assoc()) {
            $dbdata[] = $row;
        }
        echo json_encode($dbdata);
        break;

    case 'selectlinea':
        $rspta = $articulo->selectlinea($_POST["idmarca"]);
        $dbdata = array();
        //Fetch into associative array
        while ($row = $rspta->fetch_assoc()) {
            $dbdata[] = $row;
        }
        echo json_encode($dbdata);
        break;

}
?>