<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Articulo
{
    //Implementamos nuestro constructor
    public function __construct() {}

    public function listarActivosMateria()
    {
        $sql = "SELECT 
                asu.idarticulo,
                a.nombre,
                a.idcategoria,
                a.idsubcategoria, 
                a.descripcion,
                a.imagen,
                a.codigo, 
                a.tipo_producto,
                a.idusuario,
                a.aplica_comision,
                asu.idsucursal,
                ROUND(asu.stocksucursal, 2) AS stock,
                ROUND(asu.stockminimo, 2) AS stockminimo,
                ROUND(asu.precio_compra, 2) AS precio_compra,
                ROUND(asu.precio_venta, 2) AS precio_venta,
                ROUND(asu.precio_ventaNocturno, 2) AS precio_ventaNocturno,
                ROUND(asu.descuento_porcentaje, 2) AS descuento_porcentaje,
                ROUND(asu.precio_descuento, 2) AS precio_descuento,
                ROUND(asu.precio_rango1, 2) AS precio_rango1,
                ROUND(asu.precio_rango2, 2) AS precio_rango2,
                ROUND(asu.precio_rango3, 2) AS precio_rango3,
                ROUND(asu.stock_unidad, 2) AS stock_unidad,
                ROUND(asu.precio_unidad, 2) AS precio_unidad,
                ROUND(asu.stock_blister, 2) AS stock_blister,
                ROUND(asu.precio_blister, 2) AS precio_blister,
                ROUND(asu.stock_caja, 2) AS stock_caja,
                ROUND(asu.precio_caja, 2) AS precio_caja, 
                ROUND(asu.stock_fardo, 2) AS stock_fardo, 
                ROUND(asu.precio_fardo, 2) AS precio_fardo,
                ROUND(asu.stock_sacos, 2) AS stock_sacos,
                ROUND(asu.precio_sacos, 2) AS precio_sacos,
                ROUND(asu.stock_paquete, 2) AS stock_paquete,
                ROUND(asu.precio_paquete, 2) AS precio_paquete,
                asu.condicion,
                asu.descripcion_2
                FROM articulo a  
                INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
         WHERE a.tipo_producto<>'Combos' and  asu.idsucursal='" . $_SESSION["idsucursal"] . "'  ";
        return ejecutarConsulta($sql);
    }

    public function totalcostoinventario()
    {
        $sql = "SELECT 
                IFNULL(SUM(asu.precio_compra*asu.stocksucursal),0) as total_compra 
                FROM articulo a 
                INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
                where asu.condicion=1 and asu.idsucursal='" . $_SESSION["idsucursal"] . "' ";
        return ejecutarConsulta($sql);
    }

    public function totalventainventario()
    {
        $sql = "SELECT IFNULL(SUM(asu.precio_venta*asu.stocksucursal),0) as total_venta 
                FROM articulo a
                INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
                where asu.condicion=1 and asu.idsucursal='" . $_SESSION["idsucursal"] . "' ";
        return ejecutarConsulta($sql);
    }

    public function presentacionprecioventa()
    {
        $sql = "SELECT 
            p.idpresentacion,
            p.nombre_presentacion1,
            p.nombre_presentacion2,
            p.nombre_presentacion3,
            p.nombre_presentacion4,
            p.nombre_presentacion5,
            p.nombre_presentacion6,
            p.nombre_presentacion7,
            p.nombre_presentacion8,
            p.nombre_presentacion9,
            p.nombre_presentacion10,
            p.nombre_presentacion11,
            p.nombre_presentacion12,
            p.nombre_presentacion13,
            p.nombre_presentacion14,
            p.nombre_presentacion15,
            p.nombre_presentacion16,
            p.nombre_presentacion17,
            p.nombre_presentacion18,
            p.nombre_presentacion19,
            p.nombre_presentacion20,
            p.fecha_creacion,
            p.fecha_actualizacion,
            p.idusuario,
            p.idusuario_update,
            p.condicion
        FROM presentacion_precios p ";
        return ejecutarConsulta($sql);
    }

    // Implementamos un método para insertar registros
    public function insertar(
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
    ) {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        // Validar si el código ya existe
        $sqlCheckCodigo = "SELECT COUNT(*) as total FROM articulo WHERE codigo = '$codigo'";
        $resultCheck = ejecutarConsultaSimpleFila($sqlCheckCodigo);

        if ($resultCheck['total'] > 0) {
            $this->registrarLog($codigo, 'El código ya está registrado', $fechaHora);
            return "error: El código ya está registrado."; // Retorna mensaje de error si el código ya existe
        }

        // Inserta en la tabla 'articulo'
        $sql = "INSERT INTO articulo (nombre, idcategoria, idsubcategoria, descripcion, imagen, codigo, tipo_producto, 
                                      idusuario, aplica_comision, facturar_cero,crearArticuloSucursal,idempresa)
                VALUES ('$nombre', '$idcategoria', '$idsubcategoria', '$descripcion', '$imagen', '$codigo', '$tipo_producto', 
                        '" . $_SESSION["idusuario"] . "', '$aplica_comision', '$facturar_cero','$crearArticuloSucursal','$idempresa')";

        $idarticulonew = ejecutarConsulta_retornarID($sql);
        if (!$idarticulonew) {
            $this->registrarLog($codigo, 'Fallo al insertar en la tabla "articulo"', $fechaHora);
            return "error: Fallo al insertar en la tabla 'articulo'"; // Retorna error si no se insertó correctamente
        }

        if ($crearArticuloSucursal == "Todas") {
            // Insertar en la tabla 'articuloxsucursal' por cada sucursal
            $sqlsucursales = "SELECT * FROM sucursal s";
            $DetalleSucursal = ejecutarConsulta($sqlsucursales);

            while ($regS = $DetalleSucursal->fetch_object()) {
                $sqlArticuloxSucursal = "INSERT INTO articuloxsucursal (idarticulo, idsucursal, idusuario, stocksucursal, 
                                        stockminimo, precio_compra, precio_venta, precio_ventaNocturno, descuento_porcentaje, 
                                        precio_descuento, precio_rango1, precio_rango2, precio_rango3, 
                                        nombre_01,stock_unidad, precio_unidad, 
                                        nombre_02,stock_blister, precio_blister, 
                                        nombre_03,stock_caja, precio_caja, 
                                        nombre_04,stock_fardo, precio_fardo, 
                                        nombre_05,stock_sacos, precio_sacos, 
                                        nombre_06,stock_paquete, precio_paquete, 
                                        nombre_07,stock_07, precio_07, 
                                        nombre_08,stock_08, precio_08, 
                                        nombre_09,stock_09, precio_09, 
                                        nombre_10,stock_10, precio_10, 
                                        nombre_11,stock_11, precio_11, 
                                        nombre_12,stock_12, precio_12, 
                                        nombre_13,stock_13, precio_13, 
                                        nombre_14,stock_14, precio_14, 
                                        nombre_15,stock_15, precio_15, 
                                        nombre_16,stock_16, precio_16, 
                                        nombre_17,stock_17, precio_17, 
                                        nombre_18,stock_18, precio_18, 
                                        nombre_19,stock_19, precio_19, 
                                        nombre_20,stock_20, precio_20, 
                                        condicion, ganacia_articulo, 
                                        tipo_ganacia, fecha_creacion,producto_consignacion,aplica_impuestos,precio_rango1_Dos,
                                        precio_rango2_Dos,precio_rango3_Dos,precio_rango1_Mecanico,precio_rango2_MecanicoDos,
                                        precio_rango3_MecanicoTres,precio_rango1_Distribuidor,precio_rango2_DistribuidorDos,
                                        precio_rango3_DistribuidorTres,precio_rango1_Mayorista,precio_rango2_MayoristaDos,
                                        precio_rango3_MayoristaTres,codigo_sku,stockmaximo,precio_activado,descripcion_2,pocentaje_ganacia) 
                                        VALUES ('$idarticulonew', '" . $regS->idsucursal . "', '" . $_SESSION["idusuario"] . "', '0', 
                                                '$stockminimo', '$precio_compra', '$precio_venta', '$precio_ventaNocturno', 
                                                '$descuento_porcentaje', '$precio_descuento', '$precio_rango1', '$precio_rango2', 
                                                '$precio_rango3', 
                                                '$nombre_01', '$stock_unidad', '$precio_unidad', 
                                                '$nombre_02', '$stock_blister', '$precio_blister', 
                                                '$nombre_03', '$stock_caja', '$precio_caja', 
                                                '$nombre_04', '$stock_fardo', '$precio_fardo', 
                                                '$nombre_05', '$stock_sacos', '$precio_sacos', 
                                                '$nombre_06', '$stock_paquete', '$precio_paquete', 
                                                '$nombre_07', '$stock_07', '$precio_07', 
                                                '$nombre_08', '$stock_08', '$precio_08', 
                                                '$nombre_09', '$stock_09', '$precio_09', 
                                                '$nombre_10', '$stock_10', '$precio_10', 
                                                '$nombre_11', '$stock_11', '$precio_11', 
                                                '$nombre_12', '$stock_12', '$precio_12', 
                                                '$nombre_13', '$stock_13', '$precio_13', 
                                                '$nombre_14', '$stock_14', '$precio_14', 
                                                '$nombre_15', '$stock_15', '$precio_15', 
                                                '$nombre_16', '$stock_16', '$precio_16', 
                                                '$nombre_17', '$stock_17', '$precio_17', 
                                                '$nombre_18', '$stock_18', '$precio_18', 
                                                '$nombre_19', '$stock_19', '$precio_19', 
                                                '$nombre_20', '$stock_20', '$precio_20', 
                                                '1', '$ganacia_articulo', 
                                                '$tipo_ganacia', '$fechaHora','$producto_consignacion','$aplica_impuestos','$precio_rango1_Dos',
                                                '$precio_rango2_Dos','$precio_rango3_Dos','$precio_rango1_Mecanico',
                                                '$precio_rango2_MecanicoDos','$precio_rango3_MecanicoTres',
                                                '$precio_rango1_Distribuidor','$precio_rango2_DistribuidorDos',
                                                '$precio_rango3_DistribuidorTres','$precio_rango1_Mayorista',
                                                '$precio_rango2_MayoristaDos','$precio_rango3_MayoristaTres',
                                                '$codigo_sku','$stockmaximo','$precio_activo_si_no','$descripcion_2','$pocentaje_ganacia')";

                $insertSucursal = ejecutarConsulta($sqlArticuloxSucursal);
                if (!$insertSucursal) {
                    $this->registrarLog($codigo, 'Fallo al insertar en la tabla "articuloxsucursal" en sucursal ' . $regS->idsucursal, $fechaHora);
                    return "error: Fallo al insertar en la tabla 'articuloxsucursal' en sucursal " . $regS->idsucursal;
                }
            }

            // Actualizar la cantidad de stock en 'articuloxsucursal'
            $sqlart = "UPDATE articuloxsucursal SET 
                                stocksucursal = '$stock'
                                WHERE idarticulo = '$idarticulonew' 
                                AND idsucursal = '" . $_SESSION["idsucursal"] . "'";
            $updateStock = ejecutarConsulta($sqlart);
            if (!$updateStock) {
                $this->registrarLog($codigo, 'Fallo al actualizar el stock en "articuloxsucursal"', $fechaHora);
                return "error: Fallo al actualizar el stock en 'articuloxsucursal'";
            }
        } else if ($crearArticuloSucursal == "Una") {

            $sqlArticuloxSucursal = "INSERT INTO articuloxsucursal (idarticulo, idsucursal, idusuario, stocksucursal, 
                        stockminimo, precio_compra, precio_venta, precio_ventaNocturno, descuento_porcentaje, 
                        precio_descuento, precio_rango1, precio_rango2, precio_rango3, 
                        nombre_01,stock_unidad, precio_unidad, 
                        nombre_02,stock_blister, precio_blister, 
                        nombre_03,stock_caja, precio_caja, 
                        nombre_04,stock_fardo, precio_fardo, 
                        nombre_05,stock_sacos, precio_sacos, 
                        nombre_06,stock_paquete, precio_paquete, 
                        nombre_07,stock_07, precio_07, 
                        nombre_08,stock_08, precio_08, 
                        nombre_09,stock_09, precio_09, 
                        nombre_10,stock_10, precio_10, 
                        nombre_11,stock_11, precio_11, 
                        nombre_12,stock_12, precio_12, 
                        nombre_13,stock_13, precio_13, 
                        nombre_14,stock_14, precio_14, 
                        nombre_15,stock_15, precio_15, 
                        nombre_16,stock_16, precio_16, 
                        nombre_17,stock_17, precio_17, 
                        nombre_18,stock_18, precio_18, 
                        nombre_19,stock_19, precio_19, 
                        nombre_20,stock_20, precio_20, 
                        condicion, ganacia_articulo, 
                        tipo_ganacia, fecha_creacion,producto_consignacion,aplica_impuestos,precio_rango1_Dos,
                        precio_rango2_Dos,precio_rango3_Dos,precio_rango1_Mecanico,precio_rango2_MecanicoDos,
                        precio_rango3_MecanicoTres,precio_rango1_Distribuidor,precio_rango2_DistribuidorDos,
                        precio_rango3_DistribuidorTres,precio_rango1_Mayorista,precio_rango2_MayoristaDos,
                        precio_rango3_MayoristaTres,codigo_sku,stockmaximo,precio_activado,descripcion_2,pocentaje_ganacia) 
                        VALUES ('$idarticulonew', '" . $_SESSION["idsucursal"] . "', '" . $_SESSION["idusuario"] . "', '$stock', 
                                '$stockminimo', '$precio_compra', '$precio_venta', '$precio_ventaNocturno', 
                                '$descuento_porcentaje', '$precio_descuento', '$precio_rango1', '$precio_rango2', 
                                '$precio_rango3', 
                                '$nombre_01', '$stock_unidad', '$precio_unidad', 
                                '$nombre_02', '$stock_blister', '$precio_blister', 
                                '$nombre_03', '$stock_caja', '$precio_caja', 
                                '$nombre_04', '$stock_fardo', '$precio_fardo', 
                                '$nombre_05', '$stock_sacos', '$precio_sacos', 
                                '$nombre_06', '$stock_paquete', '$precio_paquete', 
                                '$nombre_07', '$stock_07', '$precio_07', 
                                '$nombre_08', '$stock_08', '$precio_08', 
                                '$nombre_09', '$stock_09', '$precio_09', 
                                '$nombre_10', '$stock_10', '$precio_10', 
                                '$nombre_11', '$stock_11', '$precio_11', 
                                '$nombre_12', '$stock_12', '$precio_12', 
                                '$nombre_13', '$stock_13', '$precio_13', 
                                '$nombre_14', '$stock_14', '$precio_14', 
                                '$nombre_15', '$stock_15', '$precio_15', 
                                '$nombre_16', '$stock_16', '$precio_16', 
                                '$nombre_17', '$stock_17', '$precio_17', 
                                '$nombre_18', '$stock_18', '$precio_18', 
                                '$nombre_19', '$stock_19', '$precio_19', 
                                '$nombre_20', '$stock_20', '$precio_20', 
                                '1', '$ganacia_articulo', 
                                '$tipo_ganacia', '$fechaHora','$producto_consignacion','$aplica_impuestos','$precio_rango1_Dos',
                                '$precio_rango2_Dos','$precio_rango3_Dos','$precio_rango1_Mecanico',
                                '$precio_rango2_MecanicoDos','$precio_rango3_MecanicoTres',
                                '$precio_rango1_Distribuidor','$precio_rango2_DistribuidorDos',
                                '$precio_rango3_DistribuidorTres','$precio_rango1_Mayorista',
                                '$precio_rango2_MayoristaDos','$precio_rango3_MayoristaTres',
                                '$codigo_sku','$stockmaximo','$precio_activo_si_no','$descripcion_2','$pocentaje_ganacia')";

            $insertSucursal = ejecutarConsulta($sqlArticuloxSucursal);
            if (!$insertSucursal) {
                $this->registrarLog($codigo, 'Fallo al insertar en la tabla "articuloxsucursal" en sucursal ' . $_SESSION["idsucursal"], $fechaHora);
                return "error: Fallo al insertar en la tabla 'articuloxsucursal' en sucursal " . $_SESSION["idsucursal"];
            }
        }

        // Si todo fue exitoso, retornar el ID del artículo creado
        return "articulo creado Codigo No: $codigo";
    }

    // Método para registrar errores en la tabla 'logs'
    private function registrarLog($codigo, $descripcion_error, $fecha_error)
    {
        $sqlLog = "INSERT INTO logs (codigo_articulo, descripcion_error, fecha_error, idusuario,idsucursal) 
                   VALUES ('$codigo', '$descripcion_error', '$fecha_error', '" . $_SESSION["idusuario"] . "','" . $_SESSION["idsucursal"] . "')";
        ejecutarConsulta($sqlLog);
    }



    //Implementamos un método para editar registros
    public function editar(
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
    ) {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        $sql = "UPDATE articulo
                    SET nombre = '$nombre',
                        idcategoria = '$idcategoria',
                        idsubcategoria = '$idsubcategoria',
                        descripcion = '$descripcion',
                        imagen = '$imagen',
                        codigo = '$codigo',
                        idempresa = '$idempresa',
                        tipo_producto = '$tipo_producto',
                        aplica_comision = '$aplica_comision',
                        facturar_cero='$facturar_cero',
                        crearArticuloSucursal='$crearArticuloSucursal'
                    WHERE  idarticulo='$idarticulo'";
        ejecutarConsulta($sql);
        //print_r($sql);

        if ($crearArticuloSucursal == "Todas") {
            // Insertar en la tabla 'articuloxsucursal' por cada sucursal
            $sqlsucursales = "SELECT * FROM sucursal s WHERE s.idsucursal<>'" . $_SESSION["idsucursal"] . "'";
            $DetalleSucursal = ejecutarConsulta($sqlsucursales);
            $faltantes = 0;

            while ($regS = $DetalleSucursal->fetch_object()) {
                // Verificar si ya existe ese artículo en esta sucursal
                $verificar = "SELECT COUNT(*) AS total FROM articuloxsucursal WHERE idarticulo = '$idarticulo' AND idsucursal = '" . $regS->idsucursal . "'";
                $existe = ejecutarConsultaSimpleFila($verificar);
                if ($existe && $existe["total"] == 0) {
                    // Insertar porque no existe
                    $faltantes++;

                    $sqlArticuloxSucursal = "INSERT INTO articuloxsucursal (idarticulo, idsucursal, idusuario, stocksucursal, 
                                        stockminimo, precio_compra, precio_venta, precio_ventaNocturno, descuento_porcentaje, 
                                        precio_descuento, precio_rango1, precio_rango2, precio_rango3, 
                                        nombre_01,stock_unidad, precio_unidad, 
                                        nombre_02,stock_blister, precio_blister, 
                                        nombre_03,stock_caja, precio_caja, 
                                        nombre_04,stock_fardo, precio_fardo, 
                                        nombre_05,stock_sacos, precio_sacos, 
                                        nombre_06,stock_paquete, precio_paquete, 
                                        nombre_07,stock_07, precio_07, 
                                        nombre_08,stock_08, precio_08, 
                                        nombre_09,stock_09, precio_09, 
                                        nombre_10,stock_10, precio_10, 
                                        nombre_11,stock_11, precio_11, 
                                        nombre_12,stock_12, precio_12, 
                                        nombre_13,stock_13, precio_13, 
                                        nombre_14,stock_14, precio_14, 
                                        nombre_15,stock_15, precio_15, 
                                        nombre_16,stock_16, precio_16, 
                                        nombre_17,stock_17, precio_17, 
                                        nombre_18,stock_18, precio_18, 
                                        nombre_19,stock_19, precio_19, 
                                        nombre_20,stock_20, precio_20, 
                                        condicion, ganacia_articulo, 
                                        tipo_ganacia, fecha_creacion,producto_consignacion,aplica_impuestos,precio_rango1_Dos,
                                        precio_rango2_Dos,precio_rango3_Dos,precio_rango1_Mecanico,precio_rango2_MecanicoDos,
                                        precio_rango3_MecanicoTres,precio_rango1_Distribuidor,precio_rango2_DistribuidorDos,
                                        precio_rango3_DistribuidorTres,precio_rango1_Mayorista,precio_rango2_MayoristaDos,
                                        precio_rango3_MayoristaTres,codigo_sku,stockmaximo,precio_activado,descripcion_2,pocentaje_ganacia) 
                                        VALUES ('$idarticulo', '" . $regS->idsucursal . "', '" . $_SESSION["idusuario"] . "', '0', 
                                                '$stockminimo', '$precio_compra', '$precio_venta', '$precio_ventaNocturno', 
                                                '$descuento_porcentaje', '$precio_descuento', '$precio_rango1', '$precio_rango2', 
                                                '$precio_rango3', 
                                                '$nombre_01', '$stock_unidad', '$precio_unidad', 
                                                '$nombre_02', '$stock_blister', '$precio_blister', 
                                                '$nombre_03', '$stock_caja', '$precio_caja', 
                                                '$nombre_04', '$stock_fardo', '$precio_fardo', 
                                                '$nombre_05', '$stock_sacos', '$precio_sacos', 
                                                '$nombre_06', '$stock_paquete', '$precio_paquete', 
                                                '$nombre_07', '$stock_07', '$precio_07', 
                                                '$nombre_08', '$stock_08', '$precio_08', 
                                                '$nombre_09', '$stock_09', '$precio_09', 
                                                '$nombre_10', '$stock_10', '$precio_10', 
                                                '$nombre_11', '$stock_11', '$precio_11', 
                                                '$nombre_12', '$stock_12', '$precio_12', 
                                                '$nombre_13', '$stock_13', '$precio_13', 
                                                '$nombre_14', '$stock_14', '$precio_14', 
                                                '$nombre_15', '$stock_15', '$precio_15', 
                                                '$nombre_16', '$stock_16', '$precio_16', 
                                                '$nombre_17', '$stock_17', '$precio_17', 
                                                '$nombre_18', '$stock_18', '$precio_18', 
                                                '$nombre_19', '$stock_19', '$precio_19', 
                                                '$nombre_20', '$stock_20', '$precio_20', 
                                                '1', '$ganacia_articulo', 
                                                '$tipo_ganacia', '$fechaHora','$producto_consignacion','$aplica_impuestos','$precio_rango1_Dos',
                                                '$precio_rango2_Dos','$precio_rango3_Dos','$precio_rango1_Mecanico',
                                                '$precio_rango2_MecanicoDos','$precio_rango3_MecanicoTres',
                                                '$precio_rango1_Distribuidor','$precio_rango2_DistribuidorDos',
                                                '$precio_rango3_DistribuidorTres','$precio_rango1_Mayorista',
                                                '$precio_rango2_MayoristaDos','$precio_rango3_MayoristaTres',
                                                '$codigo_sku','$stockmaximo','$precio_activo_si_no','$descripcion_2','$pocentaje_ganacia')";

                    $insertSucursal = ejecutarConsulta($sqlArticuloxSucursal);
                    if (!$insertSucursal) {
                        $this->registrarLog($codigo, 'Fallo al insertar en la tabla "articuloxsucursal" en sucursal ' . $regS->idsucursal, $fechaHora);
                        return "error: Fallo al insertar en la tabla 'articuloxsucursal' en sucursal " . $regS->idsucursal;
                    }
                }
            }
            if ($faltantes == 0) {
                $sqlArticuloxSucursal = "UPDATE articuloxsucursal
                                SET 
                                    stocksucursal = '$stock',
                                    stockminimo = '$stockminimo',
                                    precio_compra = '$precio_compra',
                                    ganacia_articulo = '$ganacia_articulo',
                                    tipo_ganacia='$tipo_ganacia',
                                    precio_venta = '$precio_venta',
                                    precio_ventaNocturno = '$precio_ventaNocturno',
                                    descuento_porcentaje = '$descuento_porcentaje',
                                    precio_descuento = '$precio_descuento',
                                    precio_rango1 = '$precio_rango1',
                                    precio_rango2 = '$precio_rango2',
                                    precio_rango3 = '$precio_rango3',
                                    nombre_01 = '$nombre_01',
                                    stock_unidad = '$stock_unidad',
                                    precio_unidad = '$precio_unidad',
                                    nombre_02 = '$nombre_02',
                                    stock_blister = '$stock_blister',
                                    precio_blister = '$precio_blister',
                                    nombre_03 = '$nombre_03',
                                    stock_caja = '$stock_caja',
                                    precio_caja = '$precio_caja',
                                    nombre_04 = '$nombre_04',
                                    stock_fardo = '$stock_fardo',
                                    precio_fardo = '$precio_fardo',
                                    nombre_05 = '$nombre_05',
                                    stock_sacos = '$stock_sacos',
                                    precio_sacos = '$precio_sacos',
                                    nombre_06 = '$nombre_06',
                                    stock_paquete = '$stock_paquete',
                                    precio_paquete = '$precio_paquete',
                                    nombre_07 = '$nombre_07',
                                    stock_07 = '$stock_07',
                                    precio_07 = '$precio_07',
                                    nombre_08 = '$nombre_08',
                                    stock_08 = '$stock_08',
                                    precio_08 = '$precio_08',
                                    nombre_09 = '$nombre_09',
                                    stock_09 = '$stock_09',
                                    precio_09 = '$precio_09',
                                    nombre_10 = '$nombre_10',
                                    stock_10 = '$stock_10',
                                    precio_10 = '$precio_10',
                                    nombre_11 = '$nombre_11',
                                    stock_11 = '$stock_11',
                                    precio_11 = '$precio_11',
                                    nombre_12 = '$nombre_12',
                                    stock_12 = '$stock_12',
                                    precio_12 = '$precio_12',
                                    nombre_13 = '$nombre_13',
                                    stock_13 = '$stock_13',
                                    precio_13 = '$precio_13',
                                    nombre_14 = '$nombre_14',
                                    stock_14 = '$stock_14',
                                    precio_14 = '$precio_14',
                                    nombre_15 = '$nombre_15',
                                    stock_15 = '$stock_15',
                                    precio_15 = '$precio_15',
                                    nombre_16 = '$nombre_16',
                                    stock_16 = '$stock_16',
                                    precio_16 = '$precio_16',
                                    nombre_17 = '$nombre_17',
                                    stock_17 = '$stock_17',
                                    precio_17 = '$precio_17',
                                    nombre_18 = '$nombre_18',
                                    stock_18 = '$stock_18',
                                    precio_18 = '$precio_18',
                                    nombre_19 = '$nombre_19',
                                    stock_19 = '$stock_19',
                                    precio_19 = '$precio_19',
                                    nombre_20 = '$nombre_20',
                                    stock_20 = '$stock_20',
                                    precio_20 = '$precio_20',
                                    idusuario_update='" . $_SESSION["idusuario"] . "',
                                    producto_consignacion='$producto_consignacion',
                                    aplica_impuestos='$aplica_impuestos',
                                    fecha_update='$fechaHora',
                                    precio_rango1_Dos='$precio_rango1_Dos',
                                    precio_rango2_Dos='$precio_rango2_Dos',
                                    precio_rango3_Dos='$precio_rango3_Dos',
                                    precio_rango1_Mecanico='$precio_rango1_Mecanico',
                                    precio_rango2_MecanicoDos='$precio_rango2_MecanicoDos',
                                    precio_rango3_MecanicoTres='$precio_rango3_MecanicoTres',
                                    precio_rango1_Distribuidor='$precio_rango1_Distribuidor',
                                    precio_rango2_DistribuidorDos='$precio_rango2_DistribuidorDos',
                                    precio_rango3_DistribuidorTres='$precio_rango3_DistribuidorTres',
                                    precio_rango1_Mayorista='$precio_rango1_Mayorista',
                                    precio_rango2_MayoristaDos='$precio_rango2_MayoristaDos',
                                    precio_rango3_MayoristaTres='$precio_rango3_MayoristaTres',
                                    codigo_sku='$codigo_sku',
                                    stockmaximo='$stockmaximo',
                                    precio_activado='$precio_activo_si_no',
                                    descripcion_2='$descripcion_2',
                                    pocentaje_ganacia='$pocentaje_ganacia'
                where idarticulo='" . $idarticulo . "'and  idsucursal='" . $_SESSION["idsucursal"] . "'  ";
                ejecutarConsulta($sqlArticuloxSucursal);
            }
        } elseif ($crearArticuloSucursal == "Una") {
            $sqlArticuloxSucursal = "UPDATE articuloxsucursal
                                SET 
                                    stocksucursal = '$stock',
                                    stockminimo = '$stockminimo',
                                    precio_compra = '$precio_compra',
                                    ganacia_articulo = '$ganacia_articulo',
                                    tipo_ganacia='$tipo_ganacia',
                                    precio_venta = '$precio_venta',
                                    precio_ventaNocturno = '$precio_ventaNocturno',
                                    descuento_porcentaje = '$descuento_porcentaje',
                                    precio_descuento = '$precio_descuento',
                                    precio_rango1 = '$precio_rango1',
                                    precio_rango2 = '$precio_rango2',
                                    precio_rango3 = '$precio_rango3',
                                    nombre_01 = '$nombre_01',
                                    stock_unidad = '$stock_unidad',
                                    precio_unidad = '$precio_unidad',
                                    nombre_02 = '$nombre_02',
                                    stock_blister = '$stock_blister',
                                    precio_blister = '$precio_blister',
                                    nombre_03 = '$nombre_03',
                                    stock_caja = '$stock_caja',
                                    precio_caja = '$precio_caja',
                                    nombre_04 = '$nombre_04',
                                    stock_fardo = '$stock_fardo',
                                    precio_fardo = '$precio_fardo',
                                    nombre_05 = '$nombre_05',
                                    stock_sacos = '$stock_sacos',
                                    precio_sacos = '$precio_sacos',
                                    nombre_06 = '$nombre_06',
                                    stock_paquete = '$stock_paquete',
                                    precio_paquete = '$precio_paquete',
                                    nombre_07 = '$nombre_07',
                                    stock_07 = '$stock_07',
                                    precio_07 = '$precio_07',
                                    nombre_08 = '$nombre_08',
                                    stock_08 = '$stock_08',
                                    precio_08 = '$precio_08',
                                    nombre_09 = '$nombre_09',
                                    stock_09 = '$stock_09',
                                    precio_09 = '$precio_09',
                                    nombre_10 = '$nombre_10',
                                    stock_10 = '$stock_10',
                                    precio_10 = '$precio_10',
                                    nombre_11 = '$nombre_11',
                                    stock_11 = '$stock_11',
                                    precio_11 = '$precio_11',
                                    nombre_12 = '$nombre_12',
                                    stock_12 = '$stock_12',
                                    precio_12 = '$precio_12',
                                    nombre_13 = '$nombre_13',
                                    stock_13 = '$stock_13',
                                    precio_13 = '$precio_13',
                                    nombre_14 = '$nombre_14',
                                    stock_14 = '$stock_14',
                                    precio_14 = '$precio_14',
                                    nombre_15 = '$nombre_15',
                                    stock_15 = '$stock_15',
                                    precio_15 = '$precio_15',
                                    nombre_16 = '$nombre_16',
                                    stock_16 = '$stock_16',
                                    precio_16 = '$precio_16',
                                    nombre_17 = '$nombre_17',
                                    stock_17 = '$stock_17',
                                    precio_17 = '$precio_17',
                                    nombre_18 = '$nombre_18',
                                    stock_18 = '$stock_18',
                                    precio_18 = '$precio_18',
                                    nombre_19 = '$nombre_19',
                                    stock_19 = '$stock_19',
                                    precio_19 = '$precio_19',
                                    nombre_20 = '$nombre_20',
                                    stock_20 = '$stock_20',
                                    precio_20 = '$precio_20',
                                    idusuario_update='" . $_SESSION["idusuario"] . "',
                                    producto_consignacion='$producto_consignacion',
                                    aplica_impuestos='$aplica_impuestos',
                                    fecha_update='$fechaHora',
                                    precio_rango1_Dos='$precio_rango1_Dos',
                                    precio_rango2_Dos='$precio_rango2_Dos',
                                    precio_rango3_Dos='$precio_rango3_Dos',
                                    precio_rango1_Mecanico='$precio_rango1_Mecanico',
                                    precio_rango2_MecanicoDos='$precio_rango2_MecanicoDos',
                                    precio_rango3_MecanicoTres='$precio_rango3_MecanicoTres',
                                    precio_rango1_Distribuidor='$precio_rango1_Distribuidor',
                                    precio_rango2_DistribuidorDos='$precio_rango2_DistribuidorDos',
                                    precio_rango3_DistribuidorTres='$precio_rango3_DistribuidorTres',
                                    precio_rango1_Mayorista='$precio_rango1_Mayorista',
                                    precio_rango2_MayoristaDos='$precio_rango2_MayoristaDos',
                                    precio_rango3_MayoristaTres='$precio_rango3_MayoristaTres',
                                    codigo_sku='$codigo_sku',
                                    stockmaximo='$stockmaximo',
                                    precio_activado='$precio_activo_si_no',
                                    descripcion_2='$descripcion_2',
                                    pocentaje_ganacia='$pocentaje_ganacia'
     where idarticulo='" . $idarticulo . "'and  idsucursal='" . $_SESSION["idsucursal"] . "'  ";
            ejecutarConsulta($sqlArticuloxSucursal);
        }
        return ($sql);
    }

    //Implementamos un método para desactivar registros
    public function desactivar($idarticulo)
    {

        $sql = "UPDATE articuloxsucursal asu SET asu.condicion='0' 
        WHERE asu.idarticulo='" . $idarticulo . "' and asu.idsucursal='" . $_SESSION["idsucursal"] . "' ";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar registros
    public function activar($idarticulo)
    {
        $sql = "UPDATE articuloxsucursal asu SET asu.condicion='1' 
        WHERE asu.idarticulo='" . $idarticulo . "' and asu.idsucursal='" . $_SESSION["idsucursal"] . "'  ";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idarticulo)
    {
        $sql = "SELECT 
                asu.idarticulo,
                a.nombre,
                a.facturar_cero, 
                a.idcategoria,
                a.idsubcategoria,
                a.descripcion,
                asu.descripcion_2,
                a.imagen,
                a.codigo,
                asu.codigo_sku,
                a.tipo_producto,
                a.idusuario,
                a.aplica_comision,
                asu.idsucursal,
                ROUND(asu.stocksucursal, 2) AS stock,
                ROUND(asu.stockminimo, 2) AS stockminimo,
                ROUND(asu.precio_compra, 2) AS precio_compra,
                ROUND(asu.ganacia_articulo, 2) AS ganacia_articulo,
                asu.tipo_ganacia,
                ROUND(asu.precio_venta, 2) AS precio_venta,
                ROUND(asu.precio_ventaNocturno, 2) AS precio_ventaNocturno,
                ROUND(asu.descuento_porcentaje, 2) AS descuento_porcentaje,
                ROUND(asu.precio_descuento, 2) AS precio_descuento,
                ROUND(asu.precio_rango1, 2) AS precio_rango1,
                ROUND(asu.precio_rango1_Dos, 2) AS precio_rango1_Dos,
                ROUND(asu.precio_rango2, 2) AS precio_rango2,
                ROUND(asu.precio_rango2_Dos, 2) AS precio_rango2_Dos,
                ROUND(asu.precio_rango3, 2) AS precio_rango3,
                ROUND(asu.precio_rango3_Dos, 2) AS precio_rango3_Dos,
                asu.nombre_01,
                ROUND(asu.stock_unidad, 2) AS stock_unidad,
                ROUND(asu.precio_unidad, 2) AS precio_unidad,
                asu.nombre_02,
                ROUND(asu.stock_blister, 2) AS stock_blister,
                ROUND(asu.precio_blister, 2) AS precio_blister,
                asu.nombre_03,
                ROUND(asu.stock_caja, 2) AS stock_caja,
                ROUND(asu.precio_caja, 2) AS precio_caja,
                asu.nombre_04,
                ROUND(asu.stock_fardo, 2) AS stock_fardo,
                ROUND(asu.precio_fardo, 2) AS precio_fardo,
                asu.nombre_05,
                ROUND(asu.stock_sacos, 2) AS stock_sacos,
                ROUND(asu.precio_sacos, 2) AS precio_sacos,
                asu.nombre_06,
                ROUND(asu.stock_paquete, 2) AS stock_paquete,
                ROUND(asu.precio_paquete, 2) AS precio_paquete,
                asu.nombre_07,
                ROUND(asu.stock_07, 2) AS stock_07,
                ROUND(asu.precio_07, 2) AS precio_07,
                asu.nombre_08,
                ROUND(asu.stock_08, 2) AS stock_08,
                ROUND(asu.precio_08, 2) AS precio_08,
                asu.nombre_09,
                ROUND(asu.stock_09, 2) AS stock_09,
                ROUND(asu.precio_09, 2) AS precio_09,
                asu.nombre_10,
                ROUND(asu.stock_10, 2) AS stock_10,
                ROUND(asu.precio_10, 2) AS precio_10,
                asu.nombre_11,
                ROUND(asu.stock_11, 2) AS stock_11,
                ROUND(asu.precio_11, 2) AS precio_11,
                asu.nombre_12,
                ROUND(asu.stock_12, 2) AS stock_12,
                ROUND(asu.precio_12, 2) AS precio_12,
                asu.nombre_13,
                ROUND(asu.stock_13, 2) AS stock_13,
                ROUND(asu.precio_13, 2) AS precio_13,
                asu.nombre_14,
                ROUND(asu.stock_14, 2) AS stock_14,
                ROUND(asu.precio_14, 2) AS precio_14,
                asu.nombre_15,
                ROUND(asu.stock_15, 2) AS stock_15,
                ROUND(asu.precio_15, 2) AS precio_15,
                asu.nombre_16,
                ROUND(asu.stock_16, 2) AS stock_16,
                ROUND(asu.precio_16, 2) AS precio_16,
                asu.nombre_17,
                ROUND(asu.stock_17, 2) AS stock_17,
                ROUND(asu.precio_17, 2) AS precio_17,
                asu.nombre_18,
                ROUND(asu.stock_18, 2) AS stock_18,
                ROUND(asu.precio_18, 2) AS precio_18,
                asu.nombre_19,
                ROUND(asu.stock_19, 2) AS stock_19,
                ROUND(asu.precio_19, 2) AS precio_19,
                asu.nombre_20,
                ROUND(asu.stock_20, 2) AS stock_20,
                ROUND(asu.precio_20, 2) AS precio_20,
                ROUND(asu.stockmaximo, 2) AS stockmaximo,
                ROUND(asu.precio_rango1_Mecanico, 2) AS precio_rango1_Mecanico,
                ROUND(asu.precio_rango2_MecanicoDos, 2) AS precio_rango2_MecanicoDos,
                ROUND(asu.precio_rango3_MecanicoTres, 2) AS precio_rango3_MecanicoTres,
                ROUND(asu.precio_rango1_Distribuidor, 2) AS precio_rango1_Distribuidor,
                ROUND(asu.precio_rango2_DistribuidorDos, 2) AS precio_rango2_DistribuidorDos,
                ROUND(asu.precio_rango3_DistribuidorTres, 2) AS precio_rango3_DistribuidorTres,
                ROUND(asu.precio_rango1_Mayorista, 2) AS precio_rango1_Mayorista,
                ROUND(asu.precio_rango2_MayoristaDos, 2) AS precio_rango2_MayoristaDos,
                ROUND(asu.precio_rango3_MayoristaTres, 2) AS precio_rango3_MayoristaTres,
                asu.producto_consignacion,
                asu.aplica_impuestos,
                asu.condicion,
                asu.precio_activado,
                em.nombre as nombre_empresa,
                em.idempresa,
                a.crearArticuloSucursal,
                asu.pocentaje_ganacia
                FROM articulo a  
                INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
                LEFT JOIN empresa em ON a.idempresa = em.idempresa
         WHERE a.idarticulo='$idarticulo' and  asu.idsucursal='" . $_SESSION["idsucursal"] . "'  ";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar($estadofiltro, $start, $length)
    {
        $sql = "SELECT SQL_CALC_FOUND_ROWS
                asu.idarticulo,
                a.nombre,
                c.nombre AS categoria,
                a.idsubcategoria,
                sc.nombre AS subcategoria,
                a.descripcion,
                a.imagen,
                a.codigo,
                a.tipo_producto,
                a.idusuario,
                a.aplica_comision,
                asu.idsucursal,
                ROUND(asu.stocksucursal, 2) AS stock,
                ROUND(asu.stockminimo, 2) AS stockminimo,
                ROUND(asu.precio_compra, 2) AS precio_compra,
                ROUND(asu.precio_venta, 2) AS precio_venta,
                ROUND(asu.precio_ventaNocturno, 2) AS precio_ventaNocturno,
                ROUND(asu.descuento_porcentaje, 2) AS descuento_porcentaje,
                ROUND(asu.precio_descuento, 2) AS precio_descuento,
                ROUND(asu.precio_rango1, 2) AS precio_rango1,
                ROUND(asu.precio_rango1_Dos, 2) AS precio_rango1_Dos,
                ROUND(asu.precio_rango2, 2) AS precio_rango2,
                ROUND(asu.precio_rango2_Dos, 2) AS precio_rango2_Dos,
                ROUND(asu.precio_rango3, 2) AS precio_rango3,
                ROUND(asu.precio_rango3_Dos, 2) AS precio_rango3_Dos,
                ROUND(asu.precio_rango1_Mecanico, 2) AS precio_rango1_Mecanico,
                ROUND(asu.precio_rango2_MecanicoDos, 2) AS precio_rango2_MecanicoDos,
                ROUND(asu.precio_rango3_MecanicoTres, 2) AS precio_rango3_MecanicoTres,
                ROUND(asu.precio_rango1_Distribuidor, 2) AS precio_rango1_Distribuidor,
                ROUND(asu.precio_rango2_DistribuidorDos, 2) AS precio_rango2_DistribuidorDos,
                ROUND(asu.precio_rango3_DistribuidorTres, 2) AS precio_rango3_DistribuidorTres,
                ROUND(asu.precio_rango1_Mayorista, 2) AS precio_rango1_Mayorista,
                ROUND(asu.precio_rango2_MayoristaDos, 2) AS precio_rango2_MayoristaDos,
                ROUND(asu.precio_rango3_MayoristaTres, 2) AS precio_rango3_MayoristaTres,
                asu.nombre_01,
                ROUND(asu.stock_unidad, 2) AS stock_unidad,
                ROUND(asu.precio_unidad, 2) AS precio_unidad,
                asu.nombre_02,
                ROUND(asu.stock_blister, 2) AS stock_blister,
                ROUND(asu.precio_blister, 2) AS precio_blister,
                asu.nombre_03,
                ROUND(asu.stock_caja, 2) AS stock_caja,
                ROUND(asu.precio_caja, 2) AS precio_caja,
                asu.nombre_04,
                ROUND(asu.stock_fardo, 2) AS stock_fardo,
                ROUND(asu.precio_fardo, 2) AS precio_fardo,
                asu.nombre_05,
                ROUND(asu.stock_sacos, 2) AS stock_sacos,
                ROUND(asu.precio_sacos, 2) AS precio_sacos,
                asu.nombre_06,
                ROUND(asu.stock_paquete, 2) AS stock_paquete,
                ROUND(asu.precio_paquete, 2) AS precio_paquete,
                asu.nombre_07,
                ROUND(asu.stock_07, 2) AS stock_07,
                ROUND(asu.precio_07, 2) AS precio_07,
                asu.nombre_08,
                ROUND(asu.stock_08, 2) AS stock_08,
                ROUND(asu.precio_08, 2) AS precio_08,
                asu.nombre_09,
                ROUND(asu.stock_09, 2) AS stock_09,
                ROUND(asu.precio_09, 2) AS precio_09,
                asu.nombre_10,
                ROUND(asu.stock_10, 2) AS stock_10,
                ROUND(asu.precio_10, 2) AS precio_10,
                asu.nombre_11,
                ROUND(asu.stock_11, 2) AS stock_11,
                ROUND(asu.precio_11, 2) AS precio_11,
                asu.nombre_12,
                ROUND(asu.stock_12, 2) AS stock_12,
                ROUND(asu.precio_12, 2) AS precio_12,
                asu.nombre_13,
                ROUND(asu.stock_13, 2) AS stock_13,
                ROUND(asu.precio_13, 2) AS precio_13,
                asu.nombre_14,
                ROUND(asu.stock_14, 2) AS stock_14,
                ROUND(asu.precio_14, 2) AS precio_14,
                asu.nombre_15,
                ROUND(asu.stock_15, 2) AS stock_15,
                ROUND(asu.precio_15, 2) AS precio_15,
                asu.nombre_16,
                ROUND(asu.stock_16, 2) AS stock_16,
                ROUND(asu.precio_16, 2) AS precio_16,
                asu.nombre_17,
                ROUND(asu.stock_17, 2) AS stock_17,
                ROUND(asu.precio_17, 2) AS precio_17,
                asu.nombre_18,
                ROUND(asu.stock_18, 2) AS stock_18,
                ROUND(asu.precio_18, 2) AS precio_18,
                asu.nombre_19,
                ROUND(asu.stock_19, 2) AS stock_19,
                ROUND(asu.precio_19, 2) AS precio_19,
                asu.nombre_20,
                ROUND(asu.stock_20, 2) AS stock_20,
                ROUND(asu.precio_20, 2) AS precio_20,
                asu.fecha_creacion,
                a.idusuario,
                (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=asu.idusuario LIMIT 1) AS user_creacion,
                asu.fecha_update as fecha_modificacion,
                a.idusuario_update,
                (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=asu.idusuario_update LIMIT 1) AS user_mod,
                asu.condicion,
                s.nombre as nombreSucursal,
                asu.descripcion_2,
                a.facturar_cero,
                a.tipo_descuento,
                asu.producto_consignacion,
                asu.aplica_impuestos,
                asu.idarticuloxsucursal,
                
                -- Aquí integras los días restantes por fecha de vencimiento más antigua
                v.fecha_vencimiento,
                DATEDIFF(CURDATE(), STR_TO_DATE(v.fecha_vencimiento, '%Y-%m-%d')) AS dias_vencimiento

            FROM articulo a  
            INNER JOIN articuloxsucursal asu ON a.idarticulo = asu.idarticulo
            INNER JOIN categoria c ON c.idcategoria = a.idcategoria
            INNER JOIN subcategoria sc ON sc.idsubcategoria = a.idsubcategoria
            INNER JOIN sucursal s ON s.idsucursal = asu.idsucursal

            -- LEFT JOIN con subconsulta de vencimiento más antiguo por artículo y sucursal
            LEFT JOIN (
                SELECT idarticulo, idsucursal, fecha_vencimiento
                FROM operaciones_compras_ventas
                WHERE (idingreso > 0 OR idtraladosucursal_entrada > 0)
                AND estado = 'Aceptado'
                AND saldo > 0
                GROUP BY idarticulo, idsucursal
            ) AS v ON v.idarticulo = asu.idarticulo AND v.idsucursal = asu.idsucursal
                where   asu.idsucursal='" . $_SESSION["idsucursal"] . "' and   asu.condicion='$estadofiltro'
                LIMIT $start, $length ";
        return ejecutarConsulta($sql);
    }

    public function totalRegistros()
    {
        $sql = "SELECT FOUND_ROWS() as total";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarxsucursal()
    {
        $sql = "SELECT 
                a.idarticulo,
                a.idcategoria, 
                c.nombre as categoria,
                a.idmarca,
                a.idlinea, 
                a.codigo,
                a.nombre,
                a.descripcion,
                asu.descripcion_2,
                a.ano_de,
                a.ano_a,
                asu.stocksucursal as stock, 
                asu.stockminimo,
                a.descripcion,
                a.imagen,
                a.condicion,
                asu.precio_venta,
                a.descuento_porcentaje,
                a.precio_descuento,
                a.pocerntaje_precio_mayorista,
                a.precio_mayorista,
                a.pocerntaje_precio_minorista,
                a.precio_minorista,
                a.pocerntaje_precio_menudeo,
                a.precio_menudeo,
                a.peso_producto, 
                a.tipo_producto,
                asu.precio_unidad,
                asu.precio_blister,
                asu.precio_caja,
                asu.precio_fardo,
                asu.precio_sacos,
                asu.precio_paquete,
                asu.precio_07,
                asu.precio_08,
                asu.precio_09,
                asu.precio_10,
                asu.precio_11,
                asu.precio_12,
                asu.precio_13,
                asu.precio_14,
                asu.precio_15,
                asu.precio_16,
                asu.precio_17,
                asu.precio_18,
                asu.precio_19,
                asu.precio_20,
                a.idusuario,
                u.nombre as usuario,
                a.precio_compra,
                s.nombre as age_sucursal,
                a.aplica_comision,
				    -- Aquí integras los días restantes por fecha de vencimiento más antigua
				    v.fecha_vencimiento,
				    DATEDIFF(CURDATE(), STR_TO_DATE(v.fecha_vencimiento, '%Y-%m-%d')) AS dias_vencimiento
                FROM articulo a 
                INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
                INNER join sucursal s on s.idsucursal=asu.idsucursal
                INNER JOIN categoria c ON c.idcategoria=a.idcategoria
                INNER JOIN usuario u ON u.idusuario=a.idusuario
                -- LEFT JOIN con subconsulta de vencimiento más antiguo por artículo y sucursal
					LEFT JOIN (
					    SELECT idarticulo, idsucursal, fecha_vencimiento
					    FROM operaciones_compras_ventas
					    WHERE (idingreso > 0 OR idtraladosucursal_entrada > 0)
					      AND estado = 'Aceptado'
					      AND saldo > 0
					    GROUP BY idarticulo, idsucursal
					) AS v ON v.idarticulo = asu.idarticulo AND v.idsucursal = asu.idsucursal
                where   a.tipo_producto='Productos' 
                and  asu.condicion=1 and asu.idsucursal='" . $_SESSION["idsucursal"] . "'";
        return ejecutarConsulta($sql);
    }

    public function listarxGeneral()
    {
        $sql = "SELECT 
                a.idarticulo,
                a.idcategoria, 
                c.nombre as categoria,
                a.idmarca,
                a.idlinea, 
                a.codigo,
                a.nombre,
                a.descripcion,
                asu.descripcion_2,                
                a.ano_de,
                a.ano_a,
                asu.stocksucursal as stock, 
                a.stockminimo,
                a.descripcion,
                a.imagen,
                a.condicion,
                asu.precio_venta,
                a.descuento_porcentaje,
                a.precio_descuento,
                a.pocerntaje_precio_mayorista,
                a.precio_mayorista,
                a.pocerntaje_precio_minorista,
                a.precio_minorista,
                a.pocerntaje_precio_menudeo,
                a.precio_menudeo,
                a.peso_producto, 
                a.tipo_producto,
                a.idusuario,
                u.nombre as usuario,
                a.precio_compra,
                CONCAT(s.nombre, ' ', s.direccion) AS age_sucursal,
                a.aplica_comision,
					-- Aquí integras los días restantes por fecha de vencimiento más antigua
					    v.fecha_vencimiento,
					    DATEDIFF(CURDATE(), STR_TO_DATE(v.fecha_vencimiento, '%Y-%m-%d')) AS dias_vencimiento                
                FROM articulo a 
                INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
                INNER join sucursal s on s.idsucursal=asu.idsucursal
                INNER JOIN categoria c ON c.idcategoria=a.idcategoria
                INNER JOIN usuario u ON u.idusuario=a.idusuario
					-- LEFT JOIN con subconsulta de vencimiento más antiguo por artículo y sucursal
					LEFT JOIN (
					    SELECT idarticulo, idsucursal, fecha_vencimiento
					    FROM operaciones_compras_ventas
					    WHERE (idingreso > 0 OR idtraladosucursal_entrada > 0)
					      AND estado = 'Aceptado'
					      AND saldo > 0
					    GROUP BY idarticulo, idsucursal
					) AS v ON v.idarticulo = asu.idarticulo AND v.idsucursal = asu.idsucursal                
                where   a.tipo_producto='Productos' and asu.condicion=1 ";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros
    public function listarinvenariosucursal()
    {
        $sql = "SELECT 
                a.idarticulo,
                a.idcategoria,
                c.nombre as categoria,
                a.codigo,
                a.nombre,
                asu.stocksucursal as stock, 
                a.stockminimo,
                a.descripcion,
                a.imagen,
                a.condicion,
                asu.precio_venta,
                a.descuento_porcentaje,
                a.precio_descuento,
                a.precio_compra,
                s.nombre as nombre_sucursal
                FROM articulo a 
                INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
                INNER JOIN categoria c ON c.idcategoria=a.idcategoria
                INNER JOIN usuario u ON u.idusuario=a.idusuario
                INNER JOIN sucursal s on s.idsucursal=asu.idsucursal";
        return ejecutarConsulta($sql);
    }
    public function listar2()
    {
        $sql = "SELECT 
                a.idarticulo,
                a.idcategoria,
                c.nombre as categoria,
                a.idmarca,
                a.idlinea,
                a.codigo,
                a.nombre,
                a.ano_de,
                a.ano_a,
                asu.stocksucursal as stock,
                a.stockminimo,
                a.descripcion,
                a.imagen,
                a.condicion,
                a.precio_venta,
                a.descuento_porcentaje,
                a.precio_descuento,
                a.pocerntaje_precio_mayorista,
                a.precio_mayorista,
                a.pocerntaje_precio_minorista,
                a.precio_minorista,
                a.pocerntaje_precio_menudeo,
                a.precio_menudeo,
                a.peso_producto,
                a.tipo_producto,
                a.idusuario,
                u.nombre as usuario,
                a.precio_compra
                FROM articulo a 
                INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
                INNER JOIN categoria c ON c.idcategoria=a.idcategoria
                INNER JOIN usuario u ON u.idusuario=a.idusuario
                WHERE asu.stocksucursal>='1'  and asu.idsucursal='" . $_SESSION["idsucursal"] . "'";
        return ejecutarConsulta($sql);
    }



    //Implementar un método para listar los registros activos
    /*public function listarActivos() 
    {
        $sql="SELECT 
                asu.idarticulo,
                 CONCAT(a.nombre, ' ', a.descripcion) AS nombre,
                a.idcategoria,
                c.nombre as categoria,
                a.idsubcategoria,
                a.descripcion,
                a.imagen,
                a.codigo,
                a.tipo_producto,
                a.idusuario,
                a.aplica_comision,
                asu.idsucursal,
                asu.stocksucursal as stock,
                asu.stockminimo,
                asu.precio_compra,
                asu.precio_venta,
                asu.precio_ventaNocturno,
                asu.descuento_porcentaje,
                asu.precio_descuento,
                asu.precio_rango1,
                asu.precio_rango2,
                asu.precio_rango3,
                asu.stock_unidad,
                asu.precio_unidad,
                asu.stock_blister,
                asu.precio_blister,
                asu.stock_caja,
                asu.precio_caja,
                asu.stock_fardo,
                asu.precio_fardo,
                asu.stock_sacos,
                asu.precio_sacos,
                asu.stock_paquete,
                asu.precio_paquete,
                asu.precio_rango1,
                asu.precio_rango2,
                asu.precio_rango3,
                asu.condicion
                FROM articulo a  
                INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
                inner join categoria c on c.idcategoria=a.idcategoria
          WHERE asu.condicion='1' AND  a.tipo_producto != 'Combos'  and asu.idsucursal='".$_SESSION["idsucursal"]."'";
        return ejecutarConsulta($sql);      
    }*/

    public function listarActivos()
    {
        $sql = "SELECT * FROM vista_articulos_disponibles WHERE idsucursal = '" . $_SESSION["idsucursal"] . "' ";
        return ejecutarConsulta($sql);
    }

    public function listarActivosSucursal($idsucursalOrigen)
    {
        $sql = "SELECT * FROM vista_articulos_disponibles WHERE idsucursal = '" . $idsucursalOrigen . "' ";
        return ejecutarConsulta($sql);
    }

    public function listarActivosApi($idsucursal)
    {
        $sql = "SELECT * FROM vista_articulos_disponibles WHERE idsucursal = '$idsucursal' ";
        return ejecutarConsulta($sql);
    }


    public function listarActivosApiTiendaWebProductosNuevos($idsucursal)
    {
        $sql = "SELECT 
                    a.idarticulo,
                    a.nombre,
                    a.descripcion_articulo as descripcion,
                    a.codigo,
                    c.nombre as categoria,
                    a.imagen,
                    asu.stocksucursal,
                    asu.precio_unidad as precio_venta,
                    asu.condicion
                FROM articulo a
                INNER JOIN articuloxsucursal asu ON asu.idarticulo = a.idarticulo
                INNER JOIN categoria c ON c.idcategoria = a.idcategoria
                WHERE asu.idsucursal = '$idsucursal' and a.idcategoria not in (12)
                ORDER BY a.idarticulo DESC
                LIMIT 4 ";
        return ejecutarConsulta($sql);
    }

    public function listarActivosApiTiendaWebProductosPromociones($idsucursal)
    {
        $sql = "SELECT 
                    a.idarticulo,
                    a.nombre,
                    a.descripcion_articulo as descripcion,
                    a.codigo,
                    c.nombre as categoria,
                    a.imagen,
                    asu.stocksucursal,
                    asu.precio_unidad as precio_venta,
                    asu.condicion
                FROM articulo a
                INNER JOIN articuloxsucursal asu ON asu.idarticulo = a.idarticulo
                INNER JOIN categoria c ON c.idcategoria = a.idcategoria
                WHERE asu.idsucursal = '$idsucursal' and a.idcategoria not in (12)
                and a.tipo_promocion = 'SI'
                ORDER BY a.idarticulo DESC
                LIMIT 4 ";
        return ejecutarConsulta($sql);
    }


    public function listarActivosApiTiendaWebProductosxId($idsucursal, $idarticulo)
    {
        $sql = "SELECT 
                    a.idarticulo,
                    a.nombre,
                    a.descripcion_articulo as descripcion,
                    a.codigo,
                    c.nombre as categoria,
                    a.imagen,
                    asu.stocksucursal,
                    asu.precio_unidad as precio_venta,
                    asu.condicion
                FROM articulo a
                INNER JOIN articuloxsucursal asu ON asu.idarticulo = a.idarticulo
                INNER JOIN categoria c ON c.idcategoria = a.idcategoria
                WHERE asu.idsucursal = '$idsucursal' and a.idarticulo = '$idarticulo'
                and a.idcategoria not in (12)
                ORDER BY a.idarticulo DESC
                LIMIT 4 ";
        return ejecutarConsulta($sql);
    }

    public function listarActivosApiTiendaWebProductosNuevosxCategoria($idsucursal, $idcategoria)
    {
        $sql = "SELECT 
                    a.idarticulo,
                    a.nombre,
                    a.descripcion,
                    a.codigo,
                    c.nombre as categoria,
                    a.imagen,
                    asu.stocksucursal,
                    asu.precio_unidad as precio_venta,
                    asu.condicion
                FROM articulo a
                INNER JOIN articuloxsucursal asu ON asu.idarticulo = a.idarticulo
                INNER JOIN categoria c ON c.idcategoria = a.idcategoria
                WHERE asu.idsucursal = '$idsucursal' and a.idcategoria = '$idcategoria'
                and a.idcategoria not in (12)
                ORDER BY a.idarticulo DESC ";
        return ejecutarConsulta($sql);
    }


    public function listarActivosApiTiendaWebProductosNuevosxSearch($idsucursal, $search)
    {
        $sql = "SELECT 
                    a.idarticulo,
                    a.nombre,
                    a.descripcion,
                    a.codigo,
                    c.nombre as categoria,
                    a.imagen,
                    asu.stocksucursal,
                    asu.precio_unidad as precio_venta,
                    asu.condicion
                FROM articulo a
                INNER JOIN articuloxsucursal asu ON asu.idarticulo = a.idarticulo
                INNER JOIN categoria c ON c.idcategoria = a.idcategoria
                WHERE asu.idsucursal = '$idsucursal' and a.nombre like '%$search%'
                and a.idcategoria not in (12)
                ORDER BY a.idarticulo DESC ";
        return ejecutarConsulta($sql);
    }



    public function listarActivosApiTiendaWebProductosNuevosLomasVendido($idsucursal)
    {
        $sql = "SELECT 
                    a.idarticulo,
                    a.nombre,
                    a.descripcion,
                    a.codigo,
                    c.nombre as categoria,
                    a.imagen,
                    asu.stocksucursal,
                    asu.precio_unidad as precio_venta,
                    asu.condicion,
                    sum(d.cantidad) AS cant
                FROM articulo a
                INNER JOIN articuloxsucursal asu ON asu.idarticulo = a.idarticulo
                INNER JOIN categoria c ON c.idcategoria = a.idcategoria
                inner join detalle_venta d on d.idarticulo = a.idarticulo
                WHERE asu.idsucursal = '4' and a.idcategoria not in (12)
                GROUP BY d.idarticulo
                ORDER BY d.idventa DESC 
                LIMIT 4 ";
        return ejecutarConsulta($sql);
    }

    public function mostrar_ApiTiendaWeb_imagenesproducto($idarticulo)
    {
        $sql = "SELECT idimagenes_producto, idarticulo, ruta_imagen, orden
                FROM imagenes_producto
                WHERE idarticulo = '$idarticulo'
                ORDER BY orden ASC";

        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros activos


    //Implementar un método para listar los registros activos, su último precio y el stock (vamos a unir con el último registro de la tabla detalle_ingreso)
    public function listarActivosCotizacion($idcliente)
    {



        $sql = "SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,
        a.nombre,asu.stocksucursal as stock,
        CASE (SELECT tipo_cliente FROM persona where idpersona=" . $idcliente . ")
        WHEN 'DISTRIBUIDOR' then a.precio_minorista
        WHEN 'MENUDEO' then a.precio_menudeo
        WHEN 'MAYORISTA' then a.precio_mayorista
        WHEN 'PUBLICO' then
            (SELECT precio_venta FROM detalle_ingreso WHERE idarticulo=a.idarticulo order by 
            iddetalle_ingreso desc limit 0,1) 
        ELSE
            0
        END as precio_cotizacion,
        a.descripcion,a.imagen,a.condicion FROM articulo a 
        INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
        INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.condicion='1' and asu.idsucursal='" . $_SESSION["idsucursal"] . "'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros activos, su último precio y el stock (vamos a unir con el último registro de la tabla detalle_ingreso)
    public function listarActivosVentadd()
    {
        $sql = "SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,
        asu.stocksucursal as stock,
        (SELECT precio_venta FROM detalle_ingreso WHERE idarticulo=a.idarticulo order by 
        iddetalle_ingreso desc limit 0,1) as precio_venta,a.descripcion,a.imagen,a.condicion 
        FROM articulo a 
        INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
        INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.condicion='1'   and asu.idsucursal='" . $_SESSION["idsucursal"] . "' ";
        return ejecutarConsulta($sql);
    }



    public function listarActivosVentatraslado()
    {
        $sql = "SELECT 
                                        a.idarticulo,
                                        a.idcategoria,
                                        c.nombre as categoria,
                                        a.codigo,
                                        a.nombre,
                                        asu.stocksucursal as stock,
                                        asu.precio_venta,
                                        a.descuento_porcentaje,
                                        a.precio_descuento,
                                        a.descripcion,
                                        a.imagen,
                                        a.condicion,
                                        a.stockminimo,
                                        a.facturar_cero,
                                        asu.idsucursal,
                                        asu.precio_compra,
                                        asu.precio_ventaNocturno,
                                        asu.nombre_01,
                                        asu.stock_unidad,
                                        asu.precio_unidad,
                                        asu.nombre_02,
                                        asu.stock_blister,
                                        asu.precio_blister,
                                        asu.nombre_03,
                                        asu.stock_caja,
                                        asu.precio_caja,
                                        asu.nombre_04,
                                        asu.stock_fardo,
                                        asu.precio_fardo,
                                        asu.nombre_05,
                                        asu.stock_sacos,
                                        asu.precio_sacos,
                                        asu.nombre_06,
                                        asu.stock_paquete,
                                        asu.precio_paquete,
                                        asu.nombre_07,
                                        asu.stock_07,
                                        asu.precio_07,
                                        asu.nombre_08,
                                        asu.stock_08,
                                        asu.precio_08,
                                        asu.nombre_09,
                                        asu.stock_09,
                                        asu.precio_09,
                                        asu.nombre_10,
                                        asu.stock_10,
                                        asu.precio_10,
                                        asu.nombre_11,
                                        asu.stock_11,
                                        asu.precio_11,
                                        asu.nombre_12,
                                        asu.stock_12,
                                        asu.precio_12,
                                        asu.nombre_13,
                                        asu.stock_13,
                                        asu.precio_13,
                                        asu.nombre_14,
                                        asu.stock_14,
                                        asu.precio_14,
                                        asu.nombre_15,
                                        asu.stock_15,
                                        asu.precio_15,
                                        asu.nombre_16,
                                        asu.stock_16,
                                        asu.precio_16,
                                        asu.nombre_17,
                                        asu.stock_17,
                                        asu.precio_17,
                                        asu.nombre_18,
                                        asu.stock_18,
                                        asu.precio_18,
                                        asu.nombre_19,
                                        asu.stock_19,
                                        asu.precio_19,
                                        asu.nombre_20,
                                        asu.stock_20,
                                        asu.precio_20,
                                        asu.descripcion_2
                                        FROM articulo a 
                                        INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
                                        INNER JOIN categoria c ON a.idcategoria=c.idcategoria 
                                        WHERE a.condicion='1' and a.tipo_producto='Productos'  
                                        and asu.idsucursal='" . $_SESSION["idsucursal"] . "' ";
        return ejecutarConsulta($sql);
    }


    public function listarActivosVentatrasladoxSucursal($idsucursalOr)
    {
        $sql = "SELECT 
                                        a.idarticulo,
                                        a.idcategoria,
                                        c.nombre as categoria,
                                        a.codigo,
                                        a.nombre,
                                        asu.stocksucursal as stock,
                                        asu.precio_venta,
                                        a.descuento_porcentaje,
                                        a.precio_descuento,
                                        a.descripcion,
                                        a.imagen,
                                        a.condicion,
                                        a.stockminimo,
                                        a.facturar_cero,
                                        asu.idsucursal,
                                        asu.precio_compra,
                                        asu.precio_ventaNocturno,
                                        asu.nombre_01,
                                        asu.stock_unidad,
                                        asu.precio_unidad,
                                        asu.nombre_02,
                                        asu.stock_blister,
                                        asu.precio_blister,
                                        asu.nombre_03,
                                        asu.stock_caja,
                                        asu.precio_caja,
                                        asu.nombre_04,
                                        asu.stock_fardo,
                                        asu.precio_fardo,
                                        asu.nombre_05,
                                        asu.stock_sacos,
                                        asu.precio_sacos,
                                        asu.nombre_06,
                                        asu.stock_paquete,
                                        asu.precio_paquete,
                                        asu.nombre_07,
                                        asu.stock_07,
                                        asu.precio_07,
                                        asu.nombre_08,
                                        asu.stock_08,
                                        asu.precio_08,
                                        asu.nombre_09,
                                        asu.stock_09,
                                        asu.precio_09,
                                        asu.nombre_10,
                                        asu.stock_10,
                                        asu.precio_10,
                                        asu.nombre_11,
                                        asu.stock_11,
                                        asu.precio_11,
                                        asu.nombre_12,
                                        asu.stock_12,
                                        asu.precio_12,
                                        asu.nombre_13,
                                        asu.stock_13,
                                        asu.precio_13,
                                        asu.nombre_14,
                                        asu.stock_14,
                                        asu.precio_14,
                                        asu.nombre_15,
                                        asu.stock_15,
                                        asu.precio_15,
                                        asu.nombre_16,
                                        asu.stock_16,
                                        asu.precio_16,
                                        asu.nombre_17,
                                        asu.stock_17,
                                        asu.precio_17,
                                        asu.nombre_18,
                                        asu.stock_18,
                                        asu.precio_18,
                                        asu.nombre_19,
                                        asu.stock_19,
                                        asu.precio_19,
                                        asu.nombre_20,
                                        asu.stock_20,
                                        asu.precio_20,
                                        asu.descripcion_2
                                        FROM articulo a 
                                        INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
                                        INNER JOIN categoria c ON a.idcategoria=c.idcategoria 
                                        WHERE a.condicion='1' and a.tipo_producto='Productos'  
                                        and asu.idsucursal='$idsucursalOr' ";
        return ejecutarConsulta($sql);
    }


    //Implementar un método para listar los registros activos, su último precio y el stock (vamos a unir con el último registro de la tabla detalle_ingreso)

    public function listarActivosVentacategoria($idcategoria)
    {

        $sql = "SELECT 
                asu.idarticulo,
                a.nombre,
                a.idcategoria,
                a.idsubcategoria,
                a.descripcion,
                asu.descripcion_2,
                a.imagen,
                a.codigo,
                a.tipo_producto,
                a.idusuario,
                a.aplica_comision,
                asu.idsucursal,
                asu.stocksucursal as stock,
                asu.stockminimo,
                asu.precio_compra,
                -- asu.precio_venta,
                asu.precio_ventaNocturno,
                asu.descuento_porcentaje,
                asu.stock_unidad,
                asu.precio_unidad,
                asu.stock_blister,
                asu.precio_blister,
                asu.stock_caja,
                asu.precio_caja,
                asu.stock_fardo,
                asu.precio_fardo,
                asu.stock_sacos, 
                asu.precio_sacos,
                asu.stock_paquete,
                asu.precio_paquete,
                asu.precio_rango1,
                asu.precio_rango2,
                asu.precio_rango3,
                asu.precio_rango1_Dos,
                asu.precio_rango2_Dos,
                asu.precio_rango3_Dos,
                asu.precio_rango1_Mecanico,
                asu.precio_rango2_MecanicoDos,
                asu.precio_rango3_MecanicoTres,
                asu.precio_rango1_Distribuidor,
                asu.precio_rango2_DistribuidorDos,
                asu.precio_rango3_DistribuidorTres,
                asu.precio_rango1_Mayorista,
                asu.precio_rango2_MayoristaDos,
                asu.precio_rango3_MayoristaTres,
                c.nombre as categoria,
                asu.codigo_sku,
                CASE 
                WHEN c.tipo_descuento = 'Porcentaje' THEN 
                    asu.precio_venta * (1 - c.valor_descuento / 100)
                WHEN c.tipo_descuento = 'Quetzales' THEN 
                    asu.precio_venta - c.valor_descuento
                ELSE 
                    asu.precio_venta
                END AS precio_venta,
                asu.precio_activado              
        FROM articulo a 
        INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
        INNER JOIN categoria c ON a.idcategoria=c.idcategoria
        WHERE   asu.condicion='1'  and asu.idsucursal='" . $_SESSION["idsucursal"] . "' and a.idcategoria='" . $idcategoria . "'";
        return ejecutarConsulta($sql);
    }


    public function listarActivosVenta()
    {
        $sql = "SELECT 
                asu.idarticulo,
                a.nombre,
                a.idcategoria,
                a.idsubcategoria,
                a.descripcion,
                asu.descripcion_2,
                a.imagen,
                a.codigo,
                a.tipo_producto,
                a.idusuario,
                a.aplica_comision,
                asu.idsucursal,
                asu.stocksucursal as stock,
                asu.stockminimo,
                asu.precio_compra,
                -- asu.precio_venta,
                asu.precio_ventaNocturno,
                asu.descuento_porcentaje,
                asu.nombre_01,asu.stock_unidad,asu.precio_unidad,
                asu.nombre_02,asu.stock_blister,asu.precio_blister,
                asu.nombre_03,asu.stock_caja,asu.precio_caja,
                asu.nombre_04,asu.stock_fardo,asu.precio_fardo,
                asu.nombre_05,asu.stock_sacos, asu.precio_sacos,
                asu.nombre_06,asu.stock_paquete,asu.precio_paquete,
                asu.nombre_07,asu.stock_07,asu.precio_07,
                asu.nombre_08,asu.stock_08,asu.precio_08,
                asu.nombre_09,asu.stock_09,asu.precio_09,
                asu.nombre_10,asu.stock_10,asu.precio_10,
                asu.nombre_11,asu.stock_11,asu.precio_11,
                asu.nombre_12,asu.stock_12,asu.precio_12,
                asu.nombre_13,asu.stock_13,asu.precio_13,
                asu.nombre_14,asu.stock_14,asu.precio_14,
                asu.nombre_15,asu.stock_15,asu.precio_15,
                asu.nombre_16,asu.stock_16,asu.precio_16,
                asu.nombre_17,asu.stock_17,asu.precio_17,
                asu.nombre_18,asu.stock_18,asu.precio_18,
                asu.nombre_19,asu.stock_19,asu.precio_19,
                asu.nombre_20,asu.stock_20,asu.precio_20,
                asu.precio_rango1,
                asu.precio_rango2,
                asu.precio_rango3,
                asu.precio_rango1_Dos,
                asu.precio_rango2_Dos,
                asu.precio_rango3_Dos,
                asu.precio_rango1_Mecanico,
                asu.precio_rango2_MecanicoDos,
                asu.precio_rango3_MecanicoTres,
                asu.precio_rango1_Distribuidor,
                asu.precio_rango2_DistribuidorDos,
                asu.precio_rango3_DistribuidorTres,
                asu.precio_rango1_Mayorista,
                asu.precio_rango2_MayoristaDos,
                asu.precio_rango3_MayoristaTres,
                a.facturar_cero,
                c.nombre as categoria,
                asu.codigo_sku,
                CASE 
                WHEN c.tipo_descuento = 'Porcentaje' THEN 
                    asu.precio_venta * (1 - c.valor_descuento / 100)
                WHEN c.tipo_descuento = 'Quetzales' THEN 
                    asu.precio_venta - c.valor_descuento
                ELSE 
                    asu.precio_venta
                END AS precio_venta,
                asu.precio_activado,
                -- Aquí integras los días restantes por fecha de vencimiento más antigua
                v.fecha_vencimiento,
				DATEDIFF(CURDATE(), STR_TO_DATE(v.fecha_vencimiento, '%Y-%m-%d')) AS dias_vencimiento                  
        FROM articulo a 
        INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
        INNER JOIN categoria c ON a.idcategoria=c.idcategoria
        -- LEFT JOIN con subconsulta de vencimiento más antiguo por artículo y sucursal
		LEFT JOIN (
					    SELECT idarticulo, idsucursal, fecha_vencimiento
					    FROM operaciones_compras_ventas
					    WHERE (idingreso > 0 OR idtraladosucursal_entrada > 0)
					      AND estado = 'Aceptado'
					      AND saldo > 0
					    GROUP BY idarticulo, idsucursal
					) AS v ON v.idarticulo = asu.idarticulo AND v.idsucursal = asu.idsucursal  
        WHERE   asu.condicion='1'  and asu.idsucursal='" . $_SESSION["idsucursal"] . "' ";
        return ejecutarConsulta($sql);
    }



    public function listarActivosVenta_descuento($idcliente)
    {
        $sql = "SELECT 
                asu.idarticulo,
                a.nombre,
                a.idcategoria,
                a.idsubcategoria,
                a.descripcion,
                asu.descripcion_2,
                a.imagen,
                a.codigo,
                a.tipo_producto,
                a.idusuario,
                a.aplica_comision,
                asu.idsucursal,
                asu.stocksucursal as stock,
                asu.stockminimo,
                asu.precio_compra,
                -- asu.precio_venta,
                asu.precio_ventaNocturno,
                asu.descuento_porcentaje,
                asu.nombre_01,asu.stock_unidad,
				asu.precio_unidad * (1 - (p.descuento_cliente/100)) AS precio_unidad,
                asu.nombre_02,asu.stock_blister,
				asu.precio_blister * (1 - (p.descuento_cliente/100)) AS precio_blister,
                asu.nombre_03,asu.stock_caja,
				asu.precio_caja * (1 - (p.descuento_cliente/100)) AS precio_caja,
                asu.nombre_04,asu.stock_fardo,
                asu.precio_fardo * (1 - (p.descuento_cliente/100)) AS precio_fardo,
                asu.nombre_05,asu.stock_sacos,
                asu.precio_sacos * (1 - (p.descuento_cliente/100)) AS precio_sacos,
                asu.nombre_06,asu.stock_paquete,
                asu.precio_paquete * (1 - (p.descuento_cliente/100)) AS precio_paquete,
                asu.nombre_07,asu.stock_07,
                asu.precio_07 * (1 - (p.descuento_cliente/100)) AS precio_07,
                asu.nombre_08,asu.stock_08,
                asu.precio_08 * (1 - (p.descuento_cliente/100)) AS precio_08,
                asu.nombre_09,asu.stock_09,
                asu.precio_09 * (1 - (p.descuento_cliente/100)) AS precio_09,
                asu.nombre_10,asu.stock_10,
                asu.precio_10 * (1 - (p.descuento_cliente/100)) AS precio_10,
                asu.nombre_11,asu.stock_11,
                asu.precio_11 * (1 - (p.descuento_cliente/100)) AS precio_11,
                asu.nombre_12,asu.stock_12,
                asu.precio_12 * (1 - (p.descuento_cliente/100)) AS precio_12,
                asu.nombre_13,asu.stock_13,
                asu.precio_13 * (1 - (p.descuento_cliente/100)) AS precio_13,
                asu.nombre_14,asu.stock_14,
                asu.precio_14 * (1 - (p.descuento_cliente/100)) AS precio_14,
                asu.nombre_15,asu.stock_15,
                asu.precio_15 * (1 - (p.descuento_cliente/100)) AS precio_15,
                asu.nombre_16,asu.stock_16,
                asu.precio_16 * (1 - (p.descuento_cliente/100)) AS precio_16,
                asu.nombre_17,asu.stock_17,
                asu.precio_17 * (1 - (p.descuento_cliente/100)) AS precio_17,
                asu.nombre_18,asu.stock_18,
                asu.precio_18 * (1 - (p.descuento_cliente/100)) AS precio_18,
                asu.nombre_19,asu.stock_19,
                asu.precio_19 * (1 - (p.descuento_cliente/100)) AS precio_19,
                asu.nombre_20,asu.stock_20,
                asu.precio_20 * (1 - (p.descuento_cliente/100)) AS precio_20,
                asu.precio_rango1,
                asu.precio_rango2,
                asu.precio_rango3,
                asu.precio_rango1_Dos,
                asu.precio_rango2_Dos,
                asu.precio_rango3_Dos,
                asu.precio_rango1_Mecanico,
                asu.precio_rango2_MecanicoDos,
                asu.precio_rango3_MecanicoTres,
                asu.precio_rango1_Distribuidor,
                asu.precio_rango2_DistribuidorDos,
                asu.precio_rango3_DistribuidorTres,
                asu.precio_rango1_Mayorista,
                asu.precio_rango2_MayoristaDos,
                asu.precio_rango3_MayoristaTres,
                a.facturar_cero,
                c.nombre as categoria,
                asu.codigo_sku,
					 asu.precio_venta * (1 - (p.descuento_cliente/100)) AS precio_venta,
                asu.precio_activado,
                -- Aquí integras los días restantes por fecha de vencimiento más antigua
                v.fecha_vencimiento,
				DATEDIFF(CURDATE(), STR_TO_DATE(v.fecha_vencimiento, '%Y-%m-%d')) AS dias_vencimiento                  
        FROM articulo a 
        INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
        INNER JOIN categoria c ON a.idcategoria=c.idcategoria
        INNER JOIN persona p ON p.idpersona ='$idcliente'
        -- LEFT JOIN con subconsulta de vencimiento más antiguo por artículo y sucursal
		LEFT JOIN (
					    SELECT idarticulo, idsucursal, fecha_vencimiento
					    FROM operaciones_compras_ventas
					    WHERE (idingreso > 0 OR idtraladosucursal_entrada > 0)
					      AND estado = 'Aceptado'
					      AND saldo > 0
					    GROUP BY idarticulo, idsucursal
					) AS v ON v.idarticulo = asu.idarticulo AND v.idsucursal = asu.idsucursal  
        WHERE   asu.condicion='1'  and asu.idsucursal='" . $_SESSION["idsucursal"] . "' ";
        return ejecutarConsulta($sql);
    }


    public function listarActivosVentacategoria22($idcategoria)
    {
        $sql = "SELECT 
                asu.idarticulo,
                a.nombre,
                a.idcategoria,
                a.idsubcategoria,
                a.descripcion,
                asu.descripcion_2,
                a.imagen,
                a.codigo,
                a.facturar_cero,
                a.tipo_producto,
                a.idusuario,
                a.aplica_comision,
                asu.idsucursal,
                asu.stocksucursal as stock,
                asu.stockminimo,
                asu.precio_compra,
                -- asu.precio_venta,
                asu.precio_ventaNocturno,
                asu.descuento_porcentaje,
                asu.nombre_01,asu.stock_unidad,asu.precio_unidad,
                asu.nombre_02,asu.stock_blister,asu.precio_blister,
                asu.nombre_03,asu.stock_caja,asu.precio_caja,
                asu.nombre_04,asu.stock_fardo,asu.precio_fardo,
                asu.nombre_05,asu.stock_sacos,asu.precio_sacos,
                asu.nombre_06,asu.stock_paquete,asu.precio_paquete,
                asu.nombre_07,asu.stock_07,asu.precio_07,
                asu.nombre_08,asu.stock_08,asu.precio_08,
                asu.nombre_09,asu.stock_09,asu.precio_09,
                asu.nombre_10,asu.stock_10,asu.precio_10,
                asu.nombre_11,asu.stock_11,asu.precio_11,
                asu.nombre_12,asu.stock_12,asu.precio_12,
                asu.nombre_13,asu.stock_13,asu.precio_13,
                asu.nombre_14,asu.stock_14,asu.precio_14,
                asu.nombre_15,asu.stock_15,asu.precio_15,
                asu.nombre_16,asu.stock_16,asu.precio_16,
                asu.nombre_17,asu.stock_17,asu.precio_17,
                asu.nombre_18,asu.stock_18,asu.precio_18,
                asu.nombre_19,asu.stock_19,asu.precio_19,
                asu.nombre_20,asu.stock_20,asu.precio_20,
                asu.precio_rango1,
                asu.precio_rango2,
                asu.precio_rango3,
                asu.precio_rango1_Dos,
                asu.precio_rango2_Dos,
                asu.precio_rango3_Dos,
                asu.precio_rango1_Mecanico,
                asu.precio_rango2_MecanicoDos,
                asu.precio_rango3_MecanicoTres,
                asu.precio_rango1_Distribuidor,
                asu.precio_rango2_DistribuidorDos,
                asu.precio_rango3_DistribuidorTres,
                asu.precio_rango1_Mayorista,
                asu.precio_rango2_MayoristaDos,
                asu.precio_rango3_MayoristaTres,
                c.nombre as categoria,
                asu.codigo_sku,
                CASE 
                WHEN c.tipo_descuento = 'Porcentaje' THEN 
                    asu.precio_venta * (1 - c.valor_descuento / 100)
                WHEN c.tipo_descuento = 'Quetzales' THEN 
                    asu.precio_venta - c.valor_descuento
                ELSE 
                    asu.precio_venta
                END AS precio_venta,
                asu.precio_activado,
                -- Aquí integras los días restantes por fecha de vencimiento más antigua
                v.fecha_vencimiento,
				DATEDIFF(CURDATE(), STR_TO_DATE(v.fecha_vencimiento, '%Y-%m-%d')) AS dias_vencimiento                  
        FROM articulo a 
        INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
        INNER JOIN categoria c ON a.idcategoria=c.idcategoria
        -- LEFT JOIN con subconsulta de vencimiento más antiguo por artículo y sucursal
		LEFT JOIN (
					    SELECT idarticulo, idsucursal, fecha_vencimiento
					    FROM operaciones_compras_ventas
					    WHERE (idingreso > 0 OR idtraladosucursal_entrada > 0)
					      AND estado = 'Aceptado'
					      AND saldo > 0
					    GROUP BY idarticulo, idsucursal
					) AS v ON v.idarticulo = asu.idarticulo AND v.idsucursal = asu.idsucursal  
        WHERE   asu.condicion='1'  and asu.idsucursal='" . $_SESSION["idsucursal"] . "' ";
        // Si idcategoria != 0, agregamos filtro extra
        if ($idcategoria != "0") {
            $sql .= " AND a.idcategoria='" . $idcategoria . "'";
        }
        return ejecutarConsulta($sql);
    }


    public function listarActivosVentacategoria22_descuento($idcategoria, $idcliente)
    {
        $sql = "SELECT 
                asu.idarticulo,
                a.nombre,
                a.idcategoria,
                a.idsubcategoria,
                a.descripcion,
                asu.descripcion_2,
                a.imagen,
                a.codigo,
                a.facturar_cero,
                a.tipo_producto,
                a.idusuario,
                a.aplica_comision,
                asu.idsucursal,
                asu.stocksucursal as stock,
                asu.stockminimo,
                asu.precio_compra,
                -- asu.precio_venta,
                asu.precio_ventaNocturno,
                asu.descuento_porcentaje,
                asu.nombre_01,asu.stock_unidad,
                asu.precio_unidad * (1 - (p.descuento_cliente/100)) AS precio_unidad,
                asu.nombre_02,asu.stock_blister,
                asu.precio_blister * (1 - (p.descuento_cliente/100)) AS precio_blister,
                asu.nombre_03,asu.stock_caja,
                asu.precio_caja * (1 - (p.descuento_cliente/100)) AS precio_caja,
                asu.nombre_04,asu.stock_fardo,
                asu.precio_fardo * (1 - (p.descuento_cliente/100)) AS precio_fardo,
                asu.nombre_05,asu.stock_sacos,
                asu.precio_sacos * (1 - (p.descuento_cliente/100)) AS precio_sacos,
                asu.nombre_06,asu.stock_paquete,
                asu.precio_paquete * (1 - (p.descuento_cliente/100)) AS precio_paquete,
                asu.nombre_07,asu.stock_07,
                asu.precio_07 * (1 - (p.descuento_cliente/100)) AS precio_07,
                asu.nombre_08,asu.stock_08,
                asu.precio_08 * (1 - (p.descuento_cliente/100)) AS precio_08,
                asu.nombre_09,asu.stock_09,
                asu.precio_09 * (1 - (p.descuento_cliente/100)) AS precio_09,
                asu.nombre_10,asu.stock_10,
                asu.precio_10 * (1 - (p.descuento_cliente/100)) AS precio_10,
                asu.nombre_11,asu.stock_11,
                asu.precio_11 * (1 - (p.descuento_cliente/100)) AS precio_11,
                asu.nombre_12,asu.stock_12,
                asu.precio_12 * (1 - (p.descuento_cliente/100)) AS precio_12,
                asu.nombre_13,asu.stock_13,
                asu.precio_13 * (1 - (p.descuento_cliente/100)) AS precio_13,
                asu.nombre_14,asu.stock_14,
                asu.precio_14 * (1 - (p.descuento_cliente/100)) AS precio_14,
                asu.nombre_15,asu.stock_15,
                asu.precio_15 * (1 - (p.descuento_cliente/100)) AS precio_15,
                asu.nombre_16,asu.stock_16,
                asu.precio_16 * (1 - (p.descuento_cliente/100)) AS precio_16,
                asu.nombre_17,asu.stock_17,
                asu.precio_17 * (1 - (p.descuento_cliente/100)) AS precio_17,
                asu.nombre_18,asu.stock_18,
                asu.precio_18 * (1 - (p.descuento_cliente/100)) AS precio_18,
                asu.nombre_19,asu.stock_19,
                asu.precio_19 * (1 - (p.descuento_cliente/100)) AS precio_19,
                asu.nombre_20,asu.stock_20,
                asu.precio_20 * (1 - (p.descuento_cliente/100)) AS precio_20,
                asu.precio_rango1,
                asu.precio_rango2,
                asu.precio_rango3,
                asu.precio_rango1_Dos,
                asu.precio_rango2_Dos,
                asu.precio_rango3_Dos,
                asu.precio_rango1_Mecanico,
                asu.precio_rango2_MecanicoDos,
                asu.precio_rango3_MecanicoTres,
                asu.precio_rango1_Distribuidor,
                asu.precio_rango2_DistribuidorDos,
                asu.precio_rango3_DistribuidorTres,
                asu.precio_rango1_Mayorista,
                asu.precio_rango2_MayoristaDos,
                asu.precio_rango3_MayoristaTres,
                c.nombre as categoria,
                asu.codigo_sku,
                asu.precio_venta * (1 - (p.descuento_cliente/100)) AS precio_venta,
                asu.precio_activado,
                -- Aquí integras los días restantes por fecha de vencimiento más antigua
                v.fecha_vencimiento,
				DATEDIFF(CURDATE(), STR_TO_DATE(v.fecha_vencimiento, '%Y-%m-%d')) AS dias_vencimiento                  
        FROM articulo a 
        INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
        INNER JOIN categoria c ON a.idcategoria=c.idcategoria
        INNER JOIN persona p ON p.idpersona ='$idcliente'
        -- LEFT JOIN con subconsulta de vencimiento más antiguo por artículo y sucursal
		LEFT JOIN (
					    SELECT idarticulo, idsucursal, fecha_vencimiento
					    FROM operaciones_compras_ventas
					    WHERE (idingreso > 0 OR idtraladosucursal_entrada > 0)
					      AND estado = 'Aceptado'
					      AND saldo > 0
					    GROUP BY idarticulo, idsucursal
					) AS v ON v.idarticulo = asu.idarticulo AND v.idsucursal = asu.idsucursal  
        WHERE   asu.condicion='1'  and asu.idsucursal='" . $_SESSION["idsucursal"] . "' ";
        // Si idcategoria != 0, agregamos filtro extra
        if ($idcategoria != "0") {
            $sql .= " AND a.idcategoria='" . $idcategoria . "'";
        }
        return ejecutarConsulta($sql);
    }

    public function listarActivosVentaxsucursalVenta2($idarticulo)
    {
        $sql = "SELECT 
                asu.idarticulo,
                a.nombre,
                a.idcategoria,
                a.idsubcategoria,
                a.descripcion,
                asu.descripcion_2,
                a.imagen,
                a.codigo,
                a.tipo_producto,
                a.idusuario,
                a.aplica_comision,
                asu.idsucursal,
                asu.stocksucursal as stock,
                asu.stockminimo,
                asu.precio_compra,
                -- asu.precio_venta,
                asu.precio_ventaNocturno,
                asu.descuento_porcentaje,
                asu.stock_unidad,
                asu.precio_unidad,
                asu.stock_blister,
                asu.precio_blister,
                asu.stock_caja,
                asu.precio_caja,
                asu.stock_fardo,
                asu.precio_fardo,
                asu.stock_sacos, 
                asu.precio_sacos,
                asu.stock_paquete,
                asu.precio_paquete,
                asu.precio_rango1,
                asu.precio_rango2,
                asu.precio_rango3,
                asu.precio_rango1_Dos,
                asu.precio_rango2_Dos,
                asu.precio_rango3_Dos,
                asu.precio_rango1_Mecanico,
                asu.precio_rango2_MecanicoDos,
                asu.precio_rango3_MecanicoTres,
                asu.precio_rango1_Distribuidor,
                asu.precio_rango2_DistribuidorDos,
                asu.precio_rango3_DistribuidorTres,
                asu.precio_rango1_Mayorista,
                asu.precio_rango2_MayoristaDos,
                asu.precio_rango3_MayoristaTres,
                c.nombre as categoria,
                asu.codigo_sku,
                CASE 
                WHEN c.tipo_descuento = 'Porcentaje' THEN 
                    asu.precio_venta * (1 - c.valor_descuento / 100)
                WHEN c.tipo_descuento = 'Quetzales' THEN 
                    asu.precio_venta - c.valor_descuento
                ELSE 
                    asu.precio_venta
                END AS precio_venta,
                asu.precio_activado,
                -- Aquí integras los días restantes por fecha de vencimiento más antigua
                v.fecha_vencimiento,
				DATEDIFF(CURDATE(), STR_TO_DATE(v.fecha_vencimiento, '%Y-%m-%d')) AS dias_vencimiento,
                s.nombre as nombre_sucursal,
                s.direccion as direccion_sucursal
        FROM articulo a 
        INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
        INNER JOIN categoria c ON a.idcategoria=c.idcategoria
        inner join sucursal s on s.idsucursal=asu.idsucursal
        -- LEFT JOIN con subconsulta de vencimiento más antiguo por artículo y sucursal
		LEFT JOIN (
					    SELECT idarticulo, idsucursal, fecha_vencimiento
					    FROM operaciones_compras_ventas
					    WHERE (idingreso > 0 OR idtraladosucursal_entrada > 0)
					      AND estado = 'Aceptado'
					      AND saldo > 0
					    GROUP BY idarticulo, idsucursal
					) AS v ON v.idarticulo = asu.idarticulo AND v.idsucursal = asu.idsucursal  
        WHERE   asu.condicion='1'   and asu.idarticulo='" . $idarticulo . "'";
        return ejecutarConsulta($sql);
    }

    public function ObtenerProductoBarCode2($codigo)
    {
        $sql = "SELECT * FROM vista_articulo_por_codigo 
                WHERE codigo = '" . $codigo . "' 
                  AND idsucursal = '" . $_SESSION["idsucursal"] . "'";

        return ejecutarConsulta($sql);
    }



    public function ObtenerProductoBarCode_Sucursal($codigo, $idsucursal)
    {
        $sql = "SELECT * FROM vista_articulo_por_codigo 
                WHERE codigo = '" . $codigo . "' 
                  AND idsucursal = '" . $idsucursal . "'";

        return ejecutarConsulta($sql);
    }


    public function ObtenerProductoBarCode2_descuento($codigo, $idcliente, $descuento_cliente)
    {
        $sql = "SELECT 
                asu.idarticulo,
                a.nombre,
                a.idcategoria,
                a.idsubcategoria,
                a.descripcion,
                asu.descripcion_2,
                a.imagen,
                a.codigo,
                a.tipo_producto,
                a.idusuario,
                a.aplica_comision,
                asu.idsucursal,
                asu.stocksucursal as stock,
                asu.stockminimo,
                asu.precio_compra,
                -- asu.precio_venta,
                asu.precio_ventaNocturno,
                asu.descuento_porcentaje,
                asu.nombre_01,asu.stock_unidad,
				asu.precio_unidad * (1 - (p.descuento_cliente/100)) AS precio_unidad,
                asu.nombre_02,asu.stock_blister,
				asu.precio_blister * (1 - (p.descuento_cliente/100)) AS precio_blister,
                asu.nombre_03,asu.stock_caja,
				asu.precio_caja * (1 - (p.descuento_cliente/100)) AS precio_caja,
                asu.nombre_04,asu.stock_fardo,
                asu.precio_fardo * (1 - (p.descuento_cliente/100)) AS precio_fardo,
                asu.nombre_05,asu.stock_sacos,
                asu.precio_sacos * (1 - (p.descuento_cliente/100)) AS precio_sacos,
                asu.nombre_06,asu.stock_paquete,
                asu.precio_paquete * (1 - (p.descuento_cliente/100)) AS precio_paquete,
                asu.nombre_07,asu.stock_07,
                asu.precio_07 * (1 - (p.descuento_cliente/100)) AS precio_07,
                asu.nombre_08,asu.stock_08,asu.precio_08,
                asu.precio_08 * (1 - (p.descuento_cliente/100)) AS precio_08,
                asu.nombre_09,asu.stock_09,
                asu.precio_09 * (1 - (p.descuento_cliente/100)) AS precio_09,
                asu.nombre_10,asu.stock_10,
                asu.precio_10 * (1 - (p.descuento_cliente/100)) AS precio_10,
                asu.nombre_11,asu.stock_11,
                asu.precio_11 * (1 - (p.descuento_cliente/100)) AS precio_11,
                asu.nombre_12,asu.stock_12,
                asu.precio_12 * (1 - (p.descuento_cliente/100)) AS precio_12,
                asu.nombre_13,asu.stock_13,
                asu.precio_13 * (1 - (p.descuento_cliente/100)) AS precio_13,
                asu.nombre_14,asu.stock_14,
                asu.precio_14 * (1 - (p.descuento_cliente/100)) AS precio_14,
                asu.nombre_15,asu.stock_15,
                asu.precio_15 * (1 - (p.descuento_cliente/100)) AS precio_15,
                asu.nombre_16,asu.stock_16,
                asu.precio_16 * (1 - (p.descuento_cliente/100)) AS precio_16,
                asu.nombre_17,asu.stock_17,
                asu.precio_17 * (1 - (p.descuento_cliente/100)) AS precio_17,
                asu.nombre_18,asu.stock_18,
                asu.precio_18 * (1 - (p.descuento_cliente/100)) AS precio_18,
                asu.nombre_19,asu.stock_19,
                asu.precio_19 * (1 - (p.descuento_cliente/100)) AS precio_19,
                asu.nombre_20,asu.stock_20,
                asu.precio_20 * (1 - (p.descuento_cliente/100)) AS precio_20,
                asu.precio_rango1,
                asu.precio_rango2,
                asu.precio_rango3,
                asu.precio_rango1_Dos,
                asu.precio_rango2_Dos,
                asu.precio_rango3_Dos,
                asu.precio_rango1_Mecanico,
                asu.precio_rango2_MecanicoDos,
                asu.precio_rango3_MecanicoTres,
                asu.precio_rango1_Distribuidor,
                asu.precio_rango2_DistribuidorDos,
                asu.precio_rango3_DistribuidorTres,
                asu.precio_rango1_Mayorista,
                asu.precio_rango2_MayoristaDos,
                asu.precio_rango3_MayoristaTres,
                a.facturar_cero,
                c.nombre as categoria,
                asu.codigo_sku,
					 asu.precio_venta * (1 - (p.descuento_cliente/100)) AS precio_venta,
                asu.precio_activado,
                -- Aquí integras los días restantes por fecha de vencimiento más antigua
                v.fecha_vencimiento,
				DATEDIFF(CURDATE(), STR_TO_DATE(v.fecha_vencimiento, '%Y-%m-%d')) AS dias_vencimiento                  
        FROM articulo a 
        INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
        INNER JOIN categoria c ON a.idcategoria=c.idcategoria
        INNER JOIN persona p ON p.idpersona ='$idcliente'
        -- LEFT JOIN con subconsulta de vencimiento más antiguo por artículo y sucursal
		LEFT JOIN (
					    SELECT idarticulo, idsucursal, fecha_vencimiento
					    FROM operaciones_compras_ventas
					    WHERE (idingreso > 0 OR idtraladosucursal_entrada > 0)
					      AND estado = 'Aceptado'
					      AND saldo > 0
					    GROUP BY idarticulo, idsucursal
					) AS v ON v.idarticulo = asu.idarticulo AND v.idsucursal = asu.idsucursal  
        WHERE   asu.condicion='1'  and asu.idsucursal='" . $_SESSION["idsucursal"] . "' and a.codigo='" . $codigo . "' ";

        return ejecutarConsulta($sql);
    }

    public function ObtenerProductoBarCode2Api($codigo, $idsucursal)
    {
        $sql = "SELECT * FROM vista_articulo_por_codigo 
                WHERE codigo = '" . $codigo . "' 
                  AND idsucursal = '$idsucursal'";

        return ejecutarConsulta($sql);
    }

    /////revisar en todos para cambiar por el de arriba que llama a una vista
    public function ObtenerProductoBarCode($codigo)
    {
        $sql = "SELECT 
                asu.idarticulo,
                a.nombre,
                a.idcategoria,
                a.idsubcategoria,
                a.descripcion,
                a.imagen,
                a.codigo,
                a.tipo_producto,
                a.idusuario,
                a.aplica_comision,
                asu.idsucursal,
                asu.stocksucursal as stock,
                asu.stockminimo,
                asu.precio_compra,
                -- asu.precio_venta,
                asu.precio_ventaNocturno,
                asu.descuento_porcentaje,
                asu.stock_unidad,
                asu.precio_unidad,
                asu.stock_blister,
                asu.precio_blister,
                asu.stock_caja,
                asu.precio_caja,
                asu.stock_fardo,
                asu.precio_fardo,
                asu.stock_sacos,
                asu.precio_sacos,
                asu.stock_paquete,
                asu.precio_paquete,
                asu.precio_rango1,
                asu.precio_rango1_Dos,  
                asu.precio_rango2,
                asu.precio_rango2_Dos,
                asu.precio_rango3,
                asu.precio_rango3_Dos,
                asu.precio_rango1_Mecanico,
                asu.precio_rango2_MecanicoDos,
                asu.precio_rango3_MecanicoTres,
                asu.precio_rango1_Distribuidor,
                asu.precio_rango2_DistribuidorDos,
                asu.precio_rango3_DistribuidorTres,
                asu.precio_rango1_Mayorista,
                asu.precio_rango2_MayoristaDos,
                asu.precio_rango3_MayoristaTres,
                c.nombre as categoria,
                CASE 
                WHEN c.tipo_descuento = 'Porcentaje' THEN 
                    asu.precio_venta * (1 - c.valor_descuento / 100)
                WHEN c.tipo_descuento = 'Quetzales' THEN 
                    asu.precio_venta - c.valor_descuento
                ELSE 
                    asu.precio_venta
                END AS precio_venta,
                asu.precio_activado
        FROM articulo a 
        INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
        INNER JOIN categoria c ON a.idcategoria=c.idcategoria 
        WHERE   asu.condicion='1'    and a.codigo='" . $codigo . "'  and asu.idsucursal='" . $_SESSION["idsucursal"] . "'";

        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros
    public function listarMateriaPrima()
    {
        $sql = "SELECT 
            a.idarticulo, 
            a.nombre,
            asu.stocksucursal as stock,
            ROUND(asu.precio_compra, 2) AS precio_compra,
            ROUND(asu.precio_venta, 2) AS precio_venta
         FROM articulo a
        INNER JOIN articuloxsucursal asu on a.idarticulo=asu.idarticulo
        WHERE a.tipo_producto='Combos'  and asu.idsucursal='" . $_SESSION["idsucursal"] . "'";
        return ejecutarConsulta($sql);
    }

    public function selectproduccion()
    {
        $sql = "SELECT * FROM articulo   and asu.idsucursal='" . $_SESSION["idsucursal"] . "'";
        return ejecutarConsulta($sql);
    }

    public function selectMarca()
    {
        $sql = "SELECT 
                m.idmarca,
                m.codigo,
                m.nombre,
                m.descripcion
                FROM marca m 
                WHERE m.condicion=1";
        return ejecutarConsulta($sql);
    }

    public function selectlinea($idmarca)
    {
        $sql = "SELECT 
                ac.idaccesorios,
                ac.idmarca,
                ac.idlinea,
                l.nombre
                FROM accesorios ac 
                INNER JOIN linea l ON l.idlinea=ac.idlinea
                WHERE ac.idmarca=" . $idmarca;
        return ejecutarConsulta($sql);
    }


    public function trasladar($idsucusaldestino, $idarticulo, $cantidadtraslado)
    {
        $sql = "SELECT COUNT(*) as existe FROM articuloxsucursal WHERE idarticulo='" . $idarticulo . "' and idsucursal='" . $idsucusaldestino . "' ";
        $existe = ejecutarConsultaSimpleFila($sql);
        if ($existe["existe"] > 0) {
            $sqlUpdateStock = "UPDATE articuloxsucursal SET stocksucursal=stocksucursal+" . $cantidadtraslado . " 
                            WHERE idarticulo='" . $idarticulo . "' and idsucursal='" . $idsucusaldestino . "' ";
            ejecutarConsulta($sqlUpdateStock);
        } else {
            $sqlInsertStock = "INSERT INTO articuloxsucursal(idarticulo,idsucursal,stocksucursal) values('" . $idarticulo . "','" . $idsucusaldestino . "','" . $cantidadtraslado . "')";
            ejecutarConsulta($sqlInsertStock);
        }

        $sqlUpdateStockOrigen = "UPDATE articuloxsucursal SET stocksucursal=stocksucursal-" . $cantidadtraslado . " 
                            WHERE idarticulo='" . $idarticulo . "' and idsucursal='" . $_SESSION["idsucursal"] . "' ";

        return ejecutarConsulta($sqlUpdateStockOrigen);
    }

    public function rptgenerarcodigobarras($idarticulo)
    {
        $sql = "SELECT 
                a.codigo,
                a.nombre,
                asu.precio_venta
                 FROM articulo a
                 INNER JOIN articuloxsucursal asu ON asu.idarticulo=a.idarticulo
                WHERE a.idarticulo='$idarticulo' and  asu.idsucursal='" . $_SESSION["idsucursal"] . "'";
        return ejecutarConsulta($sql);
    }

    public function actualizarStock($idarticuloxsucursal, $idarticulo, $stock)
    {

        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        $sqlCorre = "SELECT * FROM articuloxsucursal WHERE idarticuloxsucursal='$idarticuloxsucursal'";
        $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
        $stock_anterior = $correlativo["stocksucursal"];
        $idsucursal = $correlativo["idsucursal"];

        $sqlInsertStockBitacora = "INSERT INTO articuloxsucursal_bitacora(idarticulo,idarticuloxsucursal,
        stocksucursal_anterior,stocksucursal_nuevo,idusuario,idsucursal,fecha_creacion) 
        values('" . $idarticulo . "','" . $idarticuloxsucursal . "','" . $stock_anterior . "',
        '" . $stock . "','" . $_SESSION["idusuario"] . "','" . $idsucursal . "','" . $fechaHora . "')";
        ejecutarConsulta($sqlInsertStockBitacora);


        $sql = "UPDATE articuloxsucursal SET stocksucursal = '$stock' 
                WHERE idarticuloxsucursal = '$idarticuloxsucursal'  ";
        return ejecutarConsulta($sql);
    }

    public function selectEmpresa()
    {
        $sql = "SELECT * FROM empresa where condicion=1";
        return ejecutarConsulta($sql);
    }

    public function listarArticulosExtras($idarticulo)
    {
        $sql = "SELECT
                    d.iddetalle_produccion,
                    d.idproduccion,
                    d.idarticulo as idarticulo_extra,
                    a.nombre as articulo_extra,
                    d.cantidad as cantidad_extra,
                    d.precio_compra,
                    d.precio_venta,
                    d.subtotal,
                    d.tipo_item,
                    p.idproducto
                 FROM detalle_produccion d 
                 INNER JOIN articulo a ON a.idarticulo=d.idarticulo
                 INNER JOIN produccion p ON p.idproduccion=d.idproduccion
                  WHERE p.idproducto='" . $idarticulo . "'
                  AND d.tipo_item IN ('Extra', 'Topping')";
        //print_r($sql);
        return ejecutarConsulta($sql);
    }
}
