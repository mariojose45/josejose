-- --------------------------------------------------------
-- Host:                         159.65.36.174
-- Versión del servidor:         10.1.48-MariaDB-0ubuntu0.18.04.1 - Ubuntu 18.04
-- SO del servidor:              debian-linux-gnu
-- HeidiSQL Versión:             12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para dbsuper365
CREATE DATABASE IF NOT EXISTS `dbsuper365` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `dbsuper365`;

-- Volcando estructura para tabla dbsuper365.abono_otrosdecuentos_empleado
CREATE TABLE IF NOT EXISTS `abono_otrosdecuentos_empleado` (
  `idabono_otrosdecuentos_empleado` int(11) NOT NULL AUTO_INCREMENT,
  `idotrosdecuentosempleado` int(11) NOT NULL DEFAULT '0',
  `idempleado` int(11) NOT NULL DEFAULT '0',
  `monto_prestamo` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `abono_prestamo` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `saldo_prestamo` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `fecha_hora` datetime NOT NULL,
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `fecha_creacion` datetime NOT NULL,
  `idusuario_delete` int(11) NOT NULL DEFAULT '0',
  `fecha_delete` datetime NOT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT '1',
  `idcuenta` int(11) NOT NULL DEFAULT '1',
  `descripcion` varchar(250) NOT NULL DEFAULT 'N/A',
  `tipo_operacion` varchar(250) NOT NULL DEFAULT 'PRESTAMO',
  PRIMARY KEY (`idabono_otrosdecuentos_empleado`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.abono_otrosdecuentos_empleado: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.accesorios
CREATE TABLE IF NOT EXISTS `accesorios` (
  `idaccesorios` int(11) NOT NULL AUTO_INCREMENT,
  `idmarca` int(11) NOT NULL,
  `idlinea` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL,
  PRIMARY KEY (`idaccesorios`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.accesorios: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.add_correlativo
CREATE TABLE IF NOT EXISTS `add_correlativo` (
  `idcorrelativo` int(11) NOT NULL AUTO_INCREMENT,
  `num_cotizacion` varchar(20) NOT NULL DEFAULT '0',
  `num_envio` varchar(20) NOT NULL DEFAULT '0',
  `num_factura` varchar(20) NOT NULL DEFAULT '0',
  `num_nc` varchar(20) NOT NULL DEFAULT '0',
  `num_ordenesMesa` varchar(20) NOT NULL DEFAULT '0',
  `codigo_cliente` varchar(20) NOT NULL DEFAULT '0',
  `idsucursal` int(11) NOT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT '1',
  `fecha_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idcorrelativo`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.add_correlativo: ~1 rows (aproximadamente)
INSERT INTO `add_correlativo` (`idcorrelativo`, `num_cotizacion`, `num_envio`, `num_factura`, `num_nc`, `num_ordenesMesa`, `codigo_cliente`, `idsucursal`, `condicion`, `fecha_hora`) VALUES
	(1, '0', '0', '0', '0', '0', '1', 4, 1, '2026-07-15 15:34:01');

-- Volcando estructura para tabla dbsuper365.add_orden
CREATE TABLE IF NOT EXISTS `add_orden` (
  `id_add_orden` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) NOT NULL,
  `idmesa` int(11) NOT NULL,
  `motivo` varchar(450) NOT NULL DEFAULT '0',
  `idusuario` int(11) NOT NULL,
  `idusuario_delete` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `idnota_credito` int(11) NOT NULL DEFAULT '0',
  `tipo_comprobante` varchar(20) NOT NULL,
  `serie_comprobante` varchar(7) DEFAULT NULL,
  `num_comprobante` varchar(10) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total_venta` decimal(11,2) NOT NULL,
  `total_ventades` decimal(11,2) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  `tipo_pago` varchar(20) NOT NULL,
  `numero_boleta` varchar(150) NOT NULL,
  `usuariopago` int(11) NOT NULL,
  `fechapago` datetime NOT NULL,
  `estadopago` varchar(20) NOT NULL,
  `forma_pago` varchar(30) NOT NULL,
  `dias_credito` varchar(5) NOT NULL,
  `fecha_hora_cobro` datetime NOT NULL,
  `tipo_banco` varchar(30) NOT NULL,
  `recibo_caja_numero` varchar(60) NOT NULL,
  `idcuenta` int(11) NOT NULL,
  `fecha_hora_siguiente_pago` datetime NOT NULL,
  `observacion_credito` varchar(250) NOT NULL,
  `total_abono` decimal(11,2) NOT NULL,
  `saldo_venta` decimal(11,2) NOT NULL,
  `cefectivo` decimal(11,2) NOT NULL,
  `ctarjeta` decimal(11,2) NOT NULL,
  `ctransferencia` decimal(11,2) NOT NULL,
  `ccredito` decimal(11,2) NOT NULL,
  `rescambio` decimal(11,2) NOT NULL,
  `propina` decimal(11,2) NOT NULL,
  `autorizacionEcoFactura` varchar(200) NOT NULL,
  `serie_ecoFactura` varchar(200) NOT NULL,
  `numero_ecoFactura` varchar(200) NOT NULL,
  `fechaCertificacion_ecoFactura` datetime NOT NULL,
  `nombre_vendedor` varchar(250) NOT NULL,
  `numero_pagos` varchar(100) NOT NULL,
  `fecha_hora_pago` varchar(100) NOT NULL,
  `fecha_hora_vencimiento_factura` varchar(100) NOT NULL,
  `monto_abono` varchar(100) NOT NULL,
  `tipo_operacion` varchar(100) NOT NULL DEFAULT 'APERTURA',
  `idcuadre_caja` int(11) DEFAULT '0',
  `tipo_pagoBacVisaNet` varchar(50) DEFAULT '0',
  `opcionesAdicionales` varchar(50) DEFAULT '0',
  `valor_tarjeta` decimal(20,6) DEFAULT '0.000000',
  `add_fecha_hora` datetime DEFAULT NULL,
  `delete_fecha_hora` datetime DEFAULT NULL,
  `cobradosino` varchar(50) DEFAULT 'NO',
  `idventa` int(11) DEFAULT '0',
  PRIMARY KEY (`id_add_orden`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.add_orden: ~0 rows (aproximadamente)

-- Volcando estructura para procedimiento dbsuper365.aplicar_nota_debito
DELIMITER //
CREATE PROCEDURE `aplicar_nota_debito`(IN p_idnota_debito INT)
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE v_idarticulo INT;
    DECLARE v_totalcantidad DECIMAL(20,6);
    DECLARE v_idingreso INT;
    DECLARE v_saldo DECIMAL(20,6);
    DECLARE v_estado VARCHAR(50);

    -- Cursor para recorrer los artículos y cantidades de la nota de débito
    DECLARE cur CURSOR FOR
        SELECT d.idarticulo, d.totalcantidadpresentacion, n.idingreso
        FROM detalle_nota_debito d
        INNER JOIN nota_debito n ON n.idnota_debito = d.idnota_debito
        WHERE n.idnota_debito = p_idnota_debito;

    -- Manejador para finalizar el bucle cuando no haya más filas
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO v_idarticulo, v_totalcantidad, v_idingreso;
        IF done THEN
            LEAVE read_loop;
        END IF;

        -- Obtener el saldo y estado actual del artículo en operaciones_compras_ventas
        SELECT saldo, estado
        INTO v_saldo, v_estado
        FROM operaciones_compras_ventas
        WHERE idarticulo = v_idarticulo
          AND idingreso = v_idingreso
        LIMIT 1;

        -- Si el saldo es > 0 y el estado es Aceptado, actualizamos
        IF v_saldo > 0 AND v_estado = 'Aceptado' THEN

            -- Restar del saldo la cantidad aplicada
            UPDATE operaciones_compras_ventas
            SET saldo = saldo - v_totalcantidad
            WHERE idarticulo = v_idarticulo
              AND idingreso = v_idingreso;

            -- Consultar nuevamente el saldo actualizado
            SELECT saldo INTO v_saldo
            FROM operaciones_compras_ventas
            WHERE idarticulo = v_idarticulo
              AND idingreso = v_idingreso;

            -- Si el saldo después de restar queda menor o igual a 0, cambiar estado a TERMINADO
            IF v_saldo <= 0 THEN
                UPDATE operaciones_compras_ventas
                SET estado = 'TERMINADO'
                WHERE idarticulo = v_idarticulo
                  AND idingreso = v_idingreso;
            END IF;
        END IF;

        -- Si saldo <= 0 o estado = 'TERMINADO', no se hace nada

    END LOOP;

    CLOSE cur;
END//
DELIMITER ;

-- Volcando estructura para tabla dbsuper365.articulo
CREATE TABLE IF NOT EXISTS `articulo` (
  `idarticulo` int(11) NOT NULL AUTO_INCREMENT,
  `idcategoria` int(11) NOT NULL,
  `idsubcategoria` int(11) NOT NULL,
  `idmarca` int(11) NOT NULL,
  `idlinea` int(11) NOT NULL,
  `idempresa` int(11) NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ano_de` varchar(50) NOT NULL,
  `ano_a` varchar(50) NOT NULL,
  `stock` int(11) NOT NULL,
  `stockminimo` int(11) NOT NULL,
  `descripcion` text,
  `imagen` varchar(50) DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT '1',
  `precio_venta` decimal(11,2) NOT NULL,
  `precio_venta_oferta` decimal(11,2) NOT NULL,
  `descuento_porcentaje` varchar(10) NOT NULL,
  `precio_descuento` decimal(11,0) NOT NULL,
  `pocerntaje_precio_mayorista` varchar(10) NOT NULL,
  `precio_mayorista` decimal(11,0) NOT NULL,
  `pocerntaje_precio_minorista` varchar(10) NOT NULL,
  `precio_minorista` decimal(11,0) NOT NULL,
  `pocerntaje_precio_menudeo` varchar(10) NOT NULL,
  `precio_menudeo` decimal(11,0) NOT NULL,
  `peso_producto` varchar(10) NOT NULL,
  `tipo_producto` varchar(50) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `precio_compra` decimal(11,2) NOT NULL,
  `aplica_comision` varchar(30) NOT NULL,
  `fecha_creacion` varchar(30) NOT NULL,
  `fecha_modificacion` varchar(30) NOT NULL,
  `idusuario_update` int(11) NOT NULL DEFAULT '0',
  `facturar_cero` varchar(50) NOT NULL DEFAULT '0',
  `descripcion_2` varchar(500) CHARACTER SET utf8mb4 NOT NULL DEFAULT '0',
  `tipo_descuento` varchar(50) NOT NULL DEFAULT '0',
  `crearArticuloSucursal` varchar(10) NOT NULL DEFAULT 'Una',
  `meta_titulo` varchar(256) NOT NULL DEFAULT '0',
  `meta_descripcion` varchar(256) NOT NULL DEFAULT '0',
  `meta_keywords` varchar(256) NOT NULL DEFAULT '0',
  `descripcion_articulo` varchar(500) NOT NULL DEFAULT '0',
  `tipo_promocion` varchar(10) NOT NULL DEFAULT 'NO',
  PRIMARY KEY (`idarticulo`),
  KEY `fk_articulo_categoria_idx` (`idcategoria`),
  KEY `idx_articulo_idcategoria` (`idcategoria`),
  KEY `idx_articulo_idusuario` (`idusuario`),
  KEY `idx_articulo_categoria_usuario` (`idcategoria`,`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.articulo: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.articulos_procesos
CREATE TABLE IF NOT EXISTS `articulos_procesos` (
  `idarticulos_proceso` int(11) NOT NULL AUTO_INCREMENT,
  `idarticulo` int(11) NOT NULL,
  `proceso` varchar(250) NOT NULL,
  `descripcion` varchar(256) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  PRIMARY KEY (`idarticulos_proceso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.articulos_procesos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.articuloxsucursal
CREATE TABLE IF NOT EXISTS `articuloxsucursal` (
  `idarticuloxsucursal` int(11) NOT NULL AUTO_INCREMENT,
  `idarticulo` int(11) NOT NULL DEFAULT '0',
  `idsucursal` int(11) NOT NULL DEFAULT '0',
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idusuario_update` int(11) NOT NULL DEFAULT '0',
  `stocksucursal` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `stockminimo` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `stockmaximo` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_compra` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_venta` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_venta_oferta` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_ventaNocturno` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `descuento_porcentaje` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_descuento` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_rango1` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_rango1_Dos` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_rango2` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_rango2_Dos` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_rango3` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_rango3_Dos` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_01` varchar(50) NOT NULL DEFAULT 'UNIDAD',
  `stock_unidad` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_unidad` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_02` varchar(50) NOT NULL DEFAULT '0',
  `stock_blister` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_blister` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_03` varchar(50) NOT NULL DEFAULT '0',
  `stock_caja` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_caja` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_04` varchar(50) NOT NULL DEFAULT '0',
  `stock_fardo` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_fardo` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_05` varchar(50) NOT NULL DEFAULT '0',
  `stock_sacos` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_sacos` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_06` varchar(50) NOT NULL DEFAULT '0',
  `stock_paquete` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_paquete` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_07` varchar(50) NOT NULL DEFAULT '0',
  `stock_07` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_07` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_08` varchar(50) NOT NULL DEFAULT '0',
  `stock_08` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_08` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_09` varchar(50) NOT NULL DEFAULT '0',
  `stock_09` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_09` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_10` varchar(50) NOT NULL DEFAULT '0',
  `stock_10` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_10` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_11` varchar(50) NOT NULL DEFAULT '0',
  `stock_11` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_11` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_12` varchar(50) NOT NULL DEFAULT '0',
  `stock_12` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_12` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_13` varchar(50) NOT NULL DEFAULT '0',
  `stock_13` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_13` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_14` varchar(50) NOT NULL DEFAULT '0',
  `stock_14` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_14` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_15` varchar(50) NOT NULL DEFAULT '0',
  `stock_15` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_15` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_16` varchar(50) NOT NULL DEFAULT '0',
  `stock_16` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_16` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_17` varchar(50) NOT NULL DEFAULT '0',
  `stock_17` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_17` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_18` varchar(50) NOT NULL DEFAULT '0',
  `stock_18` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_18` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_19` varchar(50) NOT NULL DEFAULT '0',
  `stock_19` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_19` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `nombre_20` varchar(50) NOT NULL DEFAULT '0',
  `stock_20` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_20` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `ganacia_articulo` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `condicion` tinyint(4) NOT NULL,
  `fecha_update` varchar(50) NOT NULL,
  `fecha_creacion` varchar(50) NOT NULL,
  `tipo_ganacia` varchar(50) NOT NULL DEFAULT 'QUETZALES',
  `precio_activado` varchar(50) NOT NULL DEFAULT 'SI',
  `producto_iva_exento` varchar(50) NOT NULL DEFAULT 'NO',
  `producto_consignacion` varchar(50) NOT NULL DEFAULT 'NO',
  `aplica_impuestos` varchar(50) NOT NULL DEFAULT 'SI',
  `precio_rango1_Mecanico` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_rango2_MecanicoDos` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_rango3_MecanicoTres` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_rango1_Distribuidor` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_rango2_DistribuidorDos` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_rango3_DistribuidorTres` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_rango1_Mayorista` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_rango2_MayoristaDos` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_rango3_MayoristaTres` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `codigo_sku` varchar(50) NOT NULL DEFAULT '0',
  `precio_activo_si_no` varchar(50) NOT NULL DEFAULT '0',
  `descripcion_2` varchar(500) NOT NULL DEFAULT '0',
  `pocentaje_ganacia` decimal(20,6) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`idarticuloxsucursal`),
  KEY `idx_articuloxsucursal_idarticulo` (`idarticulo`),
  KEY `idx_articuloxsucursal_idsucursal` (`idsucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.articuloxsucursal: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.articuloxsucursal_bitacora
CREATE TABLE IF NOT EXISTS `articuloxsucursal_bitacora` (
  `idarticuloxsucursal_bitacora` int(11) NOT NULL AUTO_INCREMENT,
  `idarticulo` int(11) NOT NULL,
  `idarticuloxsucursal` int(11) NOT NULL,
  `stocksucursal_anterior` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `stocksucursal_nuevo` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `idusuario` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  PRIMARY KEY (`idarticuloxsucursal_bitacora`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.articuloxsucursal_bitacora: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.asociar_subcategoria
CREATE TABLE IF NOT EXISTS `asociar_subcategoria` (
  `idasociar_subcategoria` int(11) NOT NULL AUTO_INCREMENT,
  `idcategoria` int(11) NOT NULL DEFAULT '0',
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idsucursal` int(11) NOT NULL DEFAULT '0',
  `fecha_creacion` datetime NOT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT '0',
  `idusuario_update` int(11) NOT NULL DEFAULT '0',
  `fecha_modificacion` datetime NOT NULL,
  PRIMARY KEY (`idasociar_subcategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.asociar_subcategoria: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.asociar_subcategoriadetalle
CREATE TABLE IF NOT EXISTS `asociar_subcategoriadetalle` (
  `idasociar_subcategoriaDetalle` int(11) NOT NULL AUTO_INCREMENT,
  `idasociar_subcategoria` int(11) NOT NULL DEFAULT '0',
  `idcategoria` int(11) DEFAULT NULL,
  `idsubcategoria` int(11) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `idsucursal` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `idusuario_update` int(11) DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `condicion` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`idasociar_subcategoriaDetalle`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.asociar_subcategoriadetalle: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.auditoria_orden_compra
CREATE TABLE IF NOT EXISTS `auditoria_orden_compra` (
  `idauditoria` int(11) NOT NULL AUTO_INCREMENT,
  `idorden_compra` int(11) NOT NULL,
  `accion` varchar(50) NOT NULL,
  `descripcion` text,
  `detalle_articulos` text,
  `idusuario` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  PRIMARY KEY (`idauditoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.auditoria_orden_compra: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.auditoria_traslado_sucursal
CREATE TABLE IF NOT EXISTS `auditoria_traslado_sucursal` (
  `idauditoria` int(11) NOT NULL AUTO_INCREMENT,
  `idtraladosucursal` int(11) NOT NULL,
  `accion` varchar(50) NOT NULL,
  `descripcion` text,
  `detalle_articulos` text,
  `idusuario` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  PRIMARY KEY (`idauditoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.auditoria_traslado_sucursal: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.auditoria_traslado_sucursal_entrada
CREATE TABLE IF NOT EXISTS `auditoria_traslado_sucursal_entrada` (
  `idauditoria` int(11) NOT NULL AUTO_INCREMENT,
  `idtraladosucursal_entrada` int(11) NOT NULL,
  `accion` varchar(50) NOT NULL,
  `descripcion` text,
  `detalle_articulos` text,
  `idusuario` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  PRIMARY KEY (`idauditoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.auditoria_traslado_sucursal_entrada: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.avance_produccion
CREATE TABLE IF NOT EXISTS `avance_produccion` (
  `idavance_produccion` int(11) NOT NULL AUTO_INCREMENT,
  `idficha_empleado` int(11) NOT NULL,
  `idcategoria` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `idarticulos_proceso` int(11) NOT NULL,
  `idhora_produccion` int(11) NOT NULL,
  `cantidad` varchar(20) NOT NULL,
  `descripcion` varchar(256) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  PRIMARY KEY (`idavance_produccion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.avance_produccion: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.caja_chica
CREATE TABLE IF NOT EXISTS `caja_chica` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `valor` varchar(255) NOT NULL,
  `estado` varchar(255) NOT NULL,
  `condicion` varchar(255) NOT NULL,
  `id_usuario` varchar(255) NOT NULL,
  `diferencia` varchar(255) NOT NULL,
  `total_items` varchar(255) NOT NULL,
  `idsucursal` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.caja_chica: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.caja_chica_detalle
CREATE TABLE IF NOT EXISTS `caja_chica_detalle` (
  `Id_CajaChica` varchar(255) NOT NULL,
  `IdUsuario` varchar(255) NOT NULL,
  `fecha` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `valor` varchar(255) NOT NULL,
  `IdCajaDetalle` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_comprobante` varchar(20) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  PRIMARY KEY (`IdCajaDetalle`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.caja_chica_detalle: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.categoria
CREATE TABLE IF NOT EXISTS `categoria` (
  `idcategoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(256) DEFAULT NULL,
  `tipo_descuento` varchar(100) NOT NULL,
  `valor_descuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `condicion` tinyint(1) NOT NULL DEFAULT '1',
  `mostrar_en_venta` varchar(5) NOT NULL DEFAULT 'SI',
  PRIMARY KEY (`idcategoria`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`),
  KEY `idx_categoria_idcategoria` (`idcategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.categoria: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.categoria_sucursal
CREATE TABLE IF NOT EXISTS `categoria_sucursal` (
  `idcategoria_sucursal` int(11) NOT NULL AUTO_INCREMENT,
  `idcategoria` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `mostrar` varchar(50) NOT NULL DEFAULT 'Si',
  PRIMARY KEY (`idcategoria_sucursal`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.categoria_sucursal: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.certificador
CREATE TABLE IF NOT EXISTS `certificador` (
  `idcertificador` int(11) NOT NULL AUTO_INCREMENT,
  `idsucursal` int(11) DEFAULT NULL,
  `prueba_produccion` varchar(50) DEFAULT NULL,
  `_CLIENTE_` varchar(50) DEFAULT NULL,
  `_USUARIO_` varchar(50) DEFAULT NULL,
  `_PASS_` varchar(50) DEFAULT NULL,
  `_NIT_` varchar(50) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT NULL,
  `condicion` int(11) DEFAULT '1',
  `dato_sat` varchar(250) DEFAULT NULL,
  `nombrecertificador` varchar(250) DEFAULT NULL,
  `empresadesarrollo` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`idcertificador`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.certificador: ~2 rows (aproximadamente)
INSERT INTO `certificador` (`idcertificador`, `idsucursal`, `prueba_produccion`, `_CLIENTE_`, `_USUARIO_`, `_PASS_`, `_NIT_`, `fecha_creacion`, `condicion`, `dato_sat`, `nombrecertificador`, `empresadesarrollo`) VALUES
	(1, 4, 'PRUEBAS', '0', '0', '0', '0', '2024-12-23 22:36:30', 1, 'Sujeto a retención definitiva ISR', 'CERTIFICADOR AINNOVA, SOCIEDAD ANONIMA NIT: 5640773-4', 'Developed  / +502 2293-4153 / WhatsApp: +502 5622-2080'),
	(2, 4, 'PRODUCCION', '0', '0', '0', '0', '2024-12-23 22:36:30', 0, 'Sujeto a retención definitiva ISR', 'CERTIFICADOR AINNOVA, SOCIEDAD ANONIMA NIT: 5640773-4', 'Developed  / +502 2293-4153 / WhatsApp: +502 5622-2080');

-- Volcando estructura para tabla dbsuper365.cheque
CREATE TABLE IF NOT EXISTS `cheque` (
  `idcheque` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) NOT NULL,
  `idcuenta` int(11) NOT NULL,
  `fac_serie` varchar(20) NOT NULL,
  `fac_documento` varchar(20) NOT NULL,
  `fecha_hora_factura` datetime NOT NULL,
  `fecha_hora_operacion` datetime NOT NULL,
  `valor_cheque` decimal(11,2) NOT NULL,
  `descripcion` text NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idpagoempleado` int(11) NOT NULL,
  `idventa` int(11) NOT NULL,
  PRIMARY KEY (`idcheque`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.cheque: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.cobradores
CREATE TABLE IF NOT EXISTS `cobradores` (
  `idcobradores` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `telefono` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  PRIMARY KEY (`idcobradores`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.cobradores: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.cobros_tickets
CREATE TABLE IF NOT EXISTS `cobros_tickets` (
  `idcobros_tickets` int(11) NOT NULL AUTO_INCREMENT,
  `idlectura` int(11) DEFAULT NULL,
  `fecha_ingreso_cobro` datetime DEFAULT NULL,
  `tiempo_gracia_ticket_cobro` decimal(20,6) DEFAULT NULL,
  `p_fraccion` decimal(20,6) DEFAULT NULL,
  `p_hora` decimal(20,6) DEFAULT NULL,
  `tarifa_dia` decimal(20,6) DEFAULT NULL,
  `tarifa_noche` decimal(20,6) DEFAULT NULL,
  `tarifa_evento` decimal(20,6) DEFAULT NULL,
  `tipo_vehiculo_cobro` varchar(50) DEFAULT NULL,
  `numero_ticket` int(11) DEFAULT NULL,
  `tip_evento_cobro` varchar(50) DEFAULT NULL,
  `numeroplacaEvento` varchar(50) DEFAULT NULL,
  `tipo_vehiculoEventos` varchar(50) DEFAULT NULL,
  `fecha_cobro` datetime DEFAULT NULL,
  `tiempo_transcurrido_horas` int(11) DEFAULT NULL,
  `tiempo_transcurrido_minutos` int(11) DEFAULT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `tipo_documento_cliente` varchar(50) DEFAULT NULL,
  `total_venta` decimal(20,6) DEFAULT NULL,
  `cefectivo` decimal(20,6) DEFAULT NULL,
  `ctarjeta` decimal(20,6) DEFAULT NULL,
  `ctransferencia` decimal(20,6) DEFAULT NULL,
  `ccredito` decimal(20,6) DEFAULT NULL,
  `observacion_credito` varchar(250) DEFAULT NULL,
  `rescambio` decimal(20,6) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `idusuario_anulacion` int(11) DEFAULT NULL,
  `idsucursal` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_anulacion` datetime DEFAULT NULL,
  `condicion` int(11) DEFAULT NULL,
  `estado` varchar(50) DEFAULT 'Aceptado',
  `tipo_operacion` varchar(50) DEFAULT 'APERTURA',
  `idcuadre_caja` int(11) DEFAULT '0',
  `autorizacionEcoFactura` varchar(250) DEFAULT '0',
  `serie_ecoFactura` varchar(250) DEFAULT '0',
  `numero_ecoFactura` varchar(250) DEFAULT '0',
  `fechaCertificacion_ecoFactura` datetime DEFAULT NULL,
  PRIMARY KEY (`idcobros_tickets`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.cobros_tickets: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.color
CREATE TABLE IF NOT EXISTS `color` (
  `idcolor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` varchar(256) DEFAULT NULL,
  `condicion` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`idcolor`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.color: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.compras
CREATE TABLE IF NOT EXISTS `compras` (
  `idcompra` int(11) NOT NULL AUTO_INCREMENT,
  `idusuario` int(11) NOT NULL,
  `idusuario_update` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `serie_no` varchar(30) NOT NULL,
  `factura_no` varchar(30) NOT NULL,
  `mes_a_contabilizar` varchar(30) NOT NULL,
  `ano_contabilizar` varchar(30) NOT NULL,
  `tipo_factura` varchar(30) NOT NULL,
  `nit_no` varchar(30) NOT NULL,
  `proveedor` varchar(256) NOT NULL,
  `idpersona` int(11) NOT NULL DEFAULT '0',
  `fecha_hora` datetime NOT NULL,
  `valor_q` decimal(11,2) NOT NULL,
  `tipo_compra` varchar(30) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  `tipo_comprobante` varchar(50) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `fecha_update` varchar(50) NOT NULL,
  `tipo_operacion` varchar(50) NOT NULL DEFAULT 'APERTURA',
  `idcuadre_caja` varchar(50) NOT NULL DEFAULT '0',
  `tipo_operacion_banco` varchar(50) NOT NULL DEFAULT '0',
  `num_operacion_bac` varchar(50) NOT NULL DEFAULT '0',
  `tipo_combustible` varchar(50) NOT NULL DEFAULT '0',
  `num_galonaje` varchar(50) NOT NULL DEFAULT '0',
  `concepto_fac` varchar(250) NOT NULL DEFAULT '0',
  `TotalEfectivoDisponible_GastoaVenta` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `totalAcumuladoGasots_GastoaVenta` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `disponibleparaGastos_GastoaVenta` decimal(20,6) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`idcompra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.compras: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.compra_articulos
CREATE TABLE IF NOT EXISTS `compra_articulos` (
  `idingreso` int(11) NOT NULL AUTO_INCREMENT,
  `idproveedor` int(11) NOT NULL,
  `tipo_ingreso_producion` varchar(250) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `tipo_articulo` varchar(50) NOT NULL,
  `marca` varchar(100) NOT NULL,
  `linea` varchar(100) NOT NULL,
  `ano` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `precio_compra` decimal(11,2) NOT NULL,
  `imagen_dpi_1` varchar(250) NOT NULL,
  `imagen_dpi_2` varchar(250) NOT NULL,
  `imagen_titulo_1` varchar(250) NOT NULL,
  `imagen_titulo_2` varchar(250) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  PRIMARY KEY (`idingreso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.compra_articulos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.contacto_secundario
CREATE TABLE IF NOT EXISTS `contacto_secundario` (
  `idcontacto` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `idsucursal` int(11) DEFAULT NULL,
  PRIMARY KEY (`idcontacto`),
  KEY `idcliente` (`idcliente`),
  CONSTRAINT `contacto_secundario_ibfk_1` FOREIGN KEY (`idcliente`) REFERENCES `persona` (`idpersona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.contacto_secundario: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.cotejamientoventas_guias
CREATE TABLE IF NOT EXISTS `cotejamientoventas_guias` (
  `idcotejamientoventas_guias` int(11) NOT NULL AUTO_INCREMENT,
  `idguias_excel` int(11) DEFAULT NULL,
  `idguias_excel2` int(11) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT NULL,
  `total_liquidacion` decimal(10,2) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `condicion` tinyint(4) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `idsucursal` int(11) DEFAULT NULL,
  PRIMARY KEY (`idcotejamientoventas_guias`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.cotejamientoventas_guias: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.cotizacion
CREATE TABLE IF NOT EXISTS `cotizacion` (
  `idcotizacion` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `idusuario_update` int(11) DEFAULT NULL,
  `nombre_empresa` varchar(250) DEFAULT NULL,
  `telefono_empresa` varchar(25) DEFAULT NULL,
  `fecha_hora` datetime NOT NULL,
  `impuesto` decimal(4,2) DEFAULT NULL,
  `total_venta` decimal(11,2) NOT NULL,
  `total_ventades` decimal(11,2) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `tipo_comprobante` varchar(50) NOT NULL,
  `num_comprobante` varchar(15) NOT NULL,
  `fechaCreacion` varchar(50) NOT NULL,
  `fechaModificacion` varchar(50) DEFAULT NULL,
  `cobradosino` varchar(20) DEFAULT 'NO',
  `forma_pago` varchar(20) DEFAULT 'Efectivo',
  `idventa` varchar(20) DEFAULT '0',
  `valor_tarjeta` decimal(20,2) DEFAULT NULL,
  `tipo_pagoBacVisaNet` varchar(20) DEFAULT '0',
  `opcionesAdicionales` varchar(20) DEFAULT '0',
  `idvendedor` int(11) DEFAULT NULL,
  `comentario_cotizacion` text,
  `forma_productos` varchar(20) DEFAULT 'Agrupado',
  `destino` varchar(20) DEFAULT 'VENTA',
  `tipo_envioPedidos` varchar(20) DEFAULT 'Pendiente',
  PRIMARY KEY (`idcotizacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.cotizacion: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.cta_cobrar
CREATE TABLE IF NOT EXISTS `cta_cobrar` (
  `idcta_cobrar` int(11) NOT NULL AUTO_INCREMENT,
  `idventa` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `total_venta` decimal(11,2) NOT NULL,
  `total_abono` decimal(11,2) NOT NULL,
  `saldo_venta` decimal(11,2) NOT NULL,
  `tipo_pago` varchar(50) NOT NULL,
  `fechapago` datetime NOT NULL,
  `tipo_banco` varchar(50) NOT NULL,
  `numero_boleta` varchar(100) NOT NULL,
  `recibo_caja_numero` varchar(100) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `tipo_operacion` varchar(50) NOT NULL DEFAULT 'APERTURA',
  `idcuadre_caja` varchar(50) NOT NULL DEFAULT '0',
  `idusuario_anulacion` varchar(50) NOT NULL DEFAULT '0',
  `fecha_hora_anulacion` varchar(50) NOT NULL DEFAULT '0',
  `ubicacion_pago` varchar(250) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idcta_cobrar`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.cta_cobrar: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.cta_pagar
CREATE TABLE IF NOT EXISTS `cta_pagar` (
  `idcta_pagar` int(11) NOT NULL AUTO_INCREMENT,
  `idingreso` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `idcuenta` int(11) NOT NULL,
  `total_compra` decimal(11,0) NOT NULL,
  `valor_pagar` decimal(11,0) NOT NULL,
  `saldo_ingreso` decimal(11,0) NOT NULL,
  `tipo_pago` varchar(50) NOT NULL,
  `tipo_banco` varchar(60) NOT NULL,
  `numero_boleta` varchar(100) NOT NULL,
  `recibo_caja_numero` varchar(100) NOT NULL,
  `no_cheque` varchar(100) NOT NULL,
  `fecha_hora_generacion_pago` datetime NOT NULL,
  `desp_cheque` varchar(250) NOT NULL,
  `estado` varchar(100) NOT NULL,
  PRIMARY KEY (`idcta_pagar`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.cta_pagar: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.cuadre_cajas
CREATE TABLE IF NOT EXISTS `cuadre_cajas` (
  `idcuadre_caja` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_hora_inicio` datetime NOT NULL,
  `centavos_1` varchar(200) NOT NULL,
  `centavos_5` varchar(200) NOT NULL,
  `centavos_10` varchar(20) NOT NULL,
  `centavos_25` varchar(20) NOT NULL,
  `centavos_50` varchar(20) NOT NULL,
  `quetzal_1` varchar(20) NOT NULL,
  `quetzal_5` varchar(20) NOT NULL,
  `quetzal_10` varchar(20) NOT NULL,
  `quetzal_20` varchar(20) NOT NULL,
  `quetzal_50` varchar(20) NOT NULL,
  `quetzal_100` varchar(20) NOT NULL,
  `quetzal_200` varchar(20) NOT NULL,
  `total_efectivo` varchar(20) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `hora_inicio` varchar(20) NOT NULL,
  `idusuario_update` int(11) NOT NULL,
  `hora_inicio_update` datetime NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  `tipo_operacion` varchar(50) NOT NULL,
  `fecha_hora_inicio_update` varchar(50) NOT NULL,
  `total_ventas_diarias` varchar(100) NOT NULL,
  `total_efectivo_cierre_operaciones` varchar(100) NOT NULL,
  `operacion_efectvio` varchar(100) NOT NULL,
  `descripcion_numero_boleta` text NOT NULL,
  `valor_operacion_efectivo` varchar(100) NOT NULL,
  `saldo_final_cierre_caja` varchar(100) NOT NULL,
  `total_efectivo_inicio` varchar(100) NOT NULL,
  `total_ventas_diarias_efectivo` varchar(100) NOT NULL,
  `total_ventas_diarias_tarjeta` varchar(100) NOT NULL,
  `fecha_hora_apertura` date NOT NULL,
  `total_ventas_diarias_credito` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_ventas_diarias_transferencia` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fecha_hora_cierre` date NOT NULL,
  `total_efectivocierre` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tc` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_efectivocierreDolar` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_ventas_diariasQuetzales` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_ventas_diariasDolar` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_ventas_gastosEfectivo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_ventas_AbonosVentas` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_ventas_NCVentas` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fecha_anulacion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `idcuadre_caja_cierre` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idcuadre_caja`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.cuadre_cajas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.cuenta
CREATE TABLE IF NOT EXISTS `cuenta` (
  `idcuenta` int(11) NOT NULL AUTO_INCREMENT,
  `cta_cod` varchar(20) NOT NULL,
  `cta_nombre` varchar(256) NOT NULL,
  `num_cta` varchar(30) NOT NULL,
  `descripcion` varchar(256) NOT NULL,
  `saldo_inicial` decimal(11,2) NOT NULL,
  `saldo_cuenta` decimal(10,0) NOT NULL,
  `tipo_banco` varchar(20) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  PRIMARY KEY (`idcuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.cuenta: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.deposito
CREATE TABLE IF NOT EXISTS `deposito` (
  `iddeposito` int(11) NOT NULL AUTO_INCREMENT,
  `idusuario` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `idcuenta` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `tipo_banco` varchar(20) NOT NULL,
  `nombre_agencia` varchar(250) NOT NULL,
  `deposito_no` varchar(200) NOT NULL,
  `valor_deposito` decimal(11,2) NOT NULL,
  `descripcion` varchar(256) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  PRIMARY KEY (`iddeposito`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.deposito: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalleOtrosdecuentos_empleado
CREATE TABLE IF NOT EXISTS `detalleOtrosdecuentos_empleado` (
  `iddetalleOtrosdecuentos_empleado` int(11) NOT NULL AUTO_INCREMENT,
  `idotrosdecuentosempleado` int(11) NOT NULL DEFAULT '0',
  `no_cuota` int(11) NOT NULL DEFAULT '0',
  `fecha_abono` datetime NOT NULL,
  `monto_abono` decimal(20,6) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`iddetalleOtrosdecuentos_empleado`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.detalleOtrosdecuentos_empleado: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_accesorios
CREATE TABLE IF NOT EXISTS `detalle_accesorios` (
  `iddetalle_accesorios` int(11) NOT NULL AUTO_INCREMENT,
  `idaccesorios` int(11) NOT NULL,
  `codaccesorio` varchar(250) NOT NULL,
  `descripcion_accesorio` varchar(250) NOT NULL,
  PRIMARY KEY (`iddetalle_accesorios`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.detalle_accesorios: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_add_orden
CREATE TABLE IF NOT EXISTS `detalle_add_orden` (
  `iddetalle_add_orden` int(11) NOT NULL AUTO_INCREMENT,
  `id_add_orden` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_venta` varchar(50) NOT NULL DEFAULT '0',
  `descuento` decimal(11,6) NOT NULL,
  `descripcion_detalle` varchar(250) NOT NULL,
  `stockinven` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `subtotaldes1` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_ventaSistema` varchar(50) NOT NULL DEFAULT '0.000000',
  `precio_ventaSistema2` varchar(50) NOT NULL DEFAULT '0.000000',
  `subtotal1` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `cantidadpresentacion` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `totalcantidadpresentacion` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `presen` varchar(50) NOT NULL DEFAULT '0',
  `precio_recargo` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `q_ref` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_recargoPV` varchar(50) NOT NULL DEFAULT '0.000000',
  `precio_recargoQRef` varchar(50) NOT NULL DEFAULT '0.000000',
  `comentarios` varchar(500) NOT NULL DEFAULT 'Sin comentario',
  PRIMARY KEY (`iddetalle_add_orden`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.detalle_add_orden: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_add_ordenanulacion
CREATE TABLE IF NOT EXISTS `detalle_add_ordenanulacion` (
  `id_detalle_add_ordenanulacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_add_orden` int(11) NOT NULL DEFAULT '0',
  `idarticulo` int(11) NOT NULL DEFAULT '0',
  `cantidad` varchar(50) NOT NULL DEFAULT '0',
  `descripcion_detalle` varchar(50) NOT NULL DEFAULT '0',
  `iddetalle_add_orden` int(11) NOT NULL DEFAULT '0',
  `valormotivo` varchar(250) NOT NULL DEFAULT '0',
  `estado` varchar(50) NOT NULL DEFAULT '0',
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idsucursal` int(11) NOT NULL DEFAULT '0',
  `idmesa` int(11) NOT NULL DEFAULT '0',
  `no_personas` varchar(50) NOT NULL DEFAULT '0',
  `idcliente` int(11) NOT NULL DEFAULT '0',
  `mesero` varchar(50) NOT NULL DEFAULT '0',
  `fecha_hora_orden` varchar(50) NOT NULL DEFAULT '0',
  `total_venta` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `descuento_orden` decimal(10,2) NOT NULL DEFAULT '0.00',
  `propina_sugerida` decimal(10,2) NOT NULL DEFAULT '0.00',
  `idusuario_orden` int(11) NOT NULL DEFAULT '0',
  `estado_orden` varchar(50) NOT NULL DEFAULT '0',
  `add_fecha_hora_orden` varchar(50) NOT NULL DEFAULT '0',
  `fecha_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_fecha_hora` datetime NOT NULL,
  PRIMARY KEY (`id_detalle_add_ordenanulacion`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.detalle_add_ordenanulacion: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_cotejamientoventas_guias
CREATE TABLE IF NOT EXISTS `detalle_cotejamientoventas_guias` (
  `iddetalle_cotejamientoventas_guias` int(11) NOT NULL AUTO_INCREMENT,
  `idcotejamientoventas_guias` int(11) DEFAULT NULL,
  `idventa` int(11) DEFAULT NULL,
  `fechaventa` datetime DEFAULT NULL,
  `total_venta` decimal(10,2) DEFAULT NULL,
  `guia_transporte` varchar(50) DEFAULT NULL,
  `iddetalle_guias_excel` int(11) DEFAULT NULL,
  `idguia` int(11) DEFAULT NULL,
  `mventa` decimal(10,2) DEFAULT NULL,
  `comision` decimal(10,2) DEFAULT NULL,
  `vcomision` decimal(10,2) DEFAULT NULL,
  `mliquido` decimal(10,2) DEFAULT NULL,
  `autorizacion` varchar(50) DEFAULT NULL,
  `ctabanco` varchar(50) DEFAULT NULL,
  `vflete` decimal(10,2) DEFAULT NULL,
  `idtransporte` int(11) DEFAULT '0',
  `subtotal` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`iddetalle_cotejamientoventas_guias`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.detalle_cotejamientoventas_guias: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_cotizacion
CREATE TABLE IF NOT EXISTS `detalle_cotizacion` (
  `iddetalle_cotizacion` int(11) NOT NULL AUTO_INCREMENT,
  `idcotizacion` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_venta` varchar(50) NOT NULL DEFAULT '0',
  `descuento` decimal(11,2) NOT NULL,
  `descripcion_detalle` varchar(250) NOT NULL,
  `stockinven` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `cantidadpresentacion` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `totalcantidadpresentacion` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `presen` varchar(50) NOT NULL DEFAULT '0',
  `precio_ventaSistema` varchar(50) NOT NULL DEFAULT '0',
  `precio_ventaSistema2` varchar(50) NOT NULL DEFAULT '0',
  `q_ref` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_recargoPV` varchar(50) NOT NULL DEFAULT '0',
  `precio_recargoQRef` varchar(50) NOT NULL DEFAULT '0',
  `subtotal1` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `subtotaldes1` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `checks` varchar(2) NOT NULL,
  `tipo` varchar(50) NOT NULL DEFAULT '0',
  `idarticulopadre` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`iddetalle_cotizacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.detalle_cotizacion: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_cotizacionExtra
CREATE TABLE IF NOT EXISTS `detalle_cotizacionExtra` (
  `iddetalle_cotizacionExtra` int(11) NOT NULL AUTO_INCREMENT,
  `idcotizacion` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `idproducto` int(11) NOT NULL,
  `cantidad` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_venta` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `check` int(11) NOT NULL,
  PRIMARY KEY (`iddetalle_cotizacionExtra`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.detalle_cotizacionExtra: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_credito_venta
CREATE TABLE IF NOT EXISTS `detalle_credito_venta` (
  `iddetalle_credito_venta` int(11) NOT NULL AUTO_INCREMENT,
  `idventa` int(11) NOT NULL,
  `cuota_no` int(11) NOT NULL DEFAULT '0',
  `fecha_pago` date NOT NULL,
  `valor_cuota` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `estado_pago` varchar(50) NOT NULL DEFAULT 'PENDIENTE',
  PRIMARY KEY (`iddetalle_credito_venta`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.detalle_credito_venta: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_guias_excel
CREATE TABLE IF NOT EXISTS `detalle_guias_excel` (
  `iddetalle_guias_excel` int(11) NOT NULL AUTO_INCREMENT,
  `idguias_excel` int(11) NOT NULL DEFAULT '0',
  `idguia` varchar(100) DEFAULT NULL,
  `fechaliqui` datetime DEFAULT NULL,
  `mventa` decimal(10,2) DEFAULT NULL,
  `comision` decimal(10,2) DEFAULT NULL,
  `vcomision` decimal(10,2) DEFAULT NULL,
  `mliquido` decimal(10,2) DEFAULT NULL,
  `autorizacion` varchar(50) DEFAULT NULL,
  `ctabanco` varchar(50) DEFAULT NULL,
  `vflete` decimal(10,2) DEFAULT NULL,
  `idtransporte` int(11) DEFAULT '0',
  PRIMARY KEY (`iddetalle_guias_excel`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.detalle_guias_excel: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_ingreso
CREATE TABLE IF NOT EXISTS `detalle_ingreso` (
  `iddetalle_ingreso` int(11) NOT NULL AUTO_INCREMENT,
  `idingreso` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_compra` decimal(11,2) NOT NULL,
  `precio_venta` decimal(11,2) NOT NULL,
  `descuento_porcentaje` decimal(11,2) NOT NULL,
  `stock_inventario` decimal(11,2) NOT NULL,
  `precio_ventaNocturno` decimal(11,2) NOT NULL,
  `precio_rango1` decimal(11,2) NOT NULL,
  `precio_rango1_Distribuidor` decimal(11,2) NOT NULL,
  `precio_rango1_Mayorista` decimal(11,2) NOT NULL,
  `precio_rango2` decimal(11,2) NOT NULL,
  `precio_rango2_DistribuidorDos` decimal(11,2) NOT NULL,
  `precio_rango2_MayoristaDos` decimal(11,2) NOT NULL,
  `precio_rango3` decimal(11,2) NOT NULL,
  `precio_rango3_DistribuidorTres` decimal(11,2) NOT NULL,
  `precio_rango3_MayoristaTres` decimal(11,2) NOT NULL,
  `precio_unidad` decimal(11,2) NOT NULL,
  `precio_blister` decimal(11,2) NOT NULL,
  `precio_caja` decimal(11,2) NOT NULL,
  `precio_fardo` decimal(11,2) NOT NULL,
  `precio_sacos` decimal(11,2) NOT NULL,
  `precio_paquete` decimal(11,2) NOT NULL,
  `precio_07` decimal(11,2) NOT NULL,
  `precio_08` decimal(11,2) NOT NULL,
  `precio_09` decimal(11,2) NOT NULL,
  `precio_10` decimal(11,2) NOT NULL,
  `precio_11` decimal(11,2) NOT NULL,
  `precio_12` decimal(11,2) NOT NULL,
  `precio_13` decimal(11,2) NOT NULL,
  `precio_14` decimal(11,2) NOT NULL,
  `precio_15` decimal(11,2) NOT NULL,
  `precio_16` decimal(11,2) NOT NULL,
  `precio_17` decimal(11,2) NOT NULL,
  `precio_18` decimal(11,2) NOT NULL,
  `precio_19` decimal(11,2) NOT NULL,
  `precio_20` decimal(11,2) NOT NULL,
  `cantidadpresentacion` decimal(11,2) NOT NULL,
  `totalcantidadpresentacion` decimal(11,2) NOT NULL,
  `saldo_totalcantidadpresentacion` decimal(11,2) NOT NULL,
  `presentacion` varchar(50) NOT NULL DEFAULT '',
  `fechavencimiento` datetime NOT NULL,
  `pc_anterior_anterior` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `descripcion_detalle` varchar(50) NOT NULL DEFAULT '',
  `status_stock` varchar(50) NOT NULL DEFAULT 'EXISTENCIA',
  `idsucursalDestino` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`iddetalle_ingreso`),
  KEY `fk_detalle_ingreso_ingreso_idx` (`idingreso`),
  KEY `fk_detalle_ingreso_articulo_idx` (`idarticulo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.detalle_ingreso: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_ingreso_vehiculo
CREATE TABLE IF NOT EXISTS `detalle_ingreso_vehiculo` (
  `iddetalle_ingreso_vehiculo` int(11) NOT NULL AUTO_INCREMENT,
  `idingreso_vehiculo` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` decimal(20,6) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`iddetalle_ingreso_vehiculo`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.detalle_ingreso_vehiculo: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_nomina_pagos
CREATE TABLE IF NOT EXISTS `detalle_nomina_pagos` (
  `iddetalle_nomina_pagos` int(11) NOT NULL AUTO_INCREMENT,
  `idnomina_pagos` int(11) DEFAULT NULL,
  `idempleado` int(11) DEFAULT NULL,
  `dias_trabajados` int(11) DEFAULT NULL,
  `diario` int(11) DEFAULT NULL,
  `salario_base` decimal(20,6) DEFAULT NULL,
  `horas_trabajados` decimal(20,6) DEFAULT NULL,
  `salario_extra` decimal(20,6) DEFAULT NULL,
  `total_devengado` decimal(20,6) DEFAULT NULL,
  `bonificacion_ley` decimal(20,6) DEFAULT NULL,
  `bonificacion_productividad` decimal(20,6) DEFAULT NULL,
  `descuento_igss` decimal(20,6) DEFAULT NULL,
  `descuento_isr` decimal(20,6) DEFAULT NULL,
  `abono_prestamo` decimal(20,6) DEFAULT NULL,
  `abono_otrosDescuentos` decimal(20,6) DEFAULT NULL,
  `abono_adelantoQuincenal` decimal(20,6) DEFAULT NULL,
  `abono_adelantoSalarial` decimal(20,6) DEFAULT NULL,
  `total_deducciones` decimal(20,6) DEFAULT NULL,
  `liquido_recibir` decimal(20,6) DEFAULT NULL,
  `total_salario` decimal(20,6) DEFAULT NULL,
  PRIMARY KEY (`iddetalle_nomina_pagos`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.detalle_nomina_pagos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_nomina_pagos_14_aguinaldo
CREATE TABLE IF NOT EXISTS `detalle_nomina_pagos_14_aguinaldo` (
  `iddetalle_nomina_pagos_14_aguinaldo` int(11) NOT NULL AUTO_INCREMENT,
  `idnomina_pagos_14_aguinaldo` int(11) DEFAULT NULL,
  `idempleado` int(11) DEFAULT NULL,
  `dias_trabajados` int(11) DEFAULT NULL,
  `diario` int(11) DEFAULT NULL,
  `salario_base` decimal(20,6) DEFAULT NULL,
  `fecha_del` date DEFAULT NULL,
  `fecha_al` date DEFAULT NULL,
  `total_devengado` decimal(20,6) DEFAULT NULL,
  `anticipos_bono_14` decimal(20,6) DEFAULT NULL,
  `liquido_recibir` decimal(20,6) DEFAULT NULL,
  PRIMARY KEY (`iddetalle_nomina_pagos_14_aguinaldo`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.detalle_nomina_pagos_14_aguinaldo: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_nomina_pagos_quincenales
CREATE TABLE IF NOT EXISTS `detalle_nomina_pagos_quincenales` (
  `iddetalle_nomina_pagos_quincenales` int(11) NOT NULL AUTO_INCREMENT,
  `idnomina_pagos_quincenales` int(11) DEFAULT '0',
  `idempleado` int(11) DEFAULT '0',
  `dias_trabajados` int(11) DEFAULT '0',
  `diario` int(11) DEFAULT '0',
  `salario_base` decimal(20,6) DEFAULT '0.000000',
  `salario_extra` decimal(20,6) DEFAULT '0.000000',
  `total_devengado` decimal(20,6) DEFAULT '0.000000',
  `bonificacion_ley` decimal(20,6) DEFAULT '0.000000',
  `bonificacion_productividad` decimal(20,6) DEFAULT '0.000000',
  `descuento_igss` decimal(20,6) DEFAULT '0.000000',
  `descuento_isr` decimal(20,6) DEFAULT '0.000000',
  `abono_prestamo` decimal(20,6) DEFAULT '0.000000',
  `abono_otrosDescuentos` decimal(20,6) DEFAULT '0.000000',
  `abono_adelantoQuincenal` decimal(20,6) DEFAULT '0.000000',
  `abono_adelantoSalarial` decimal(20,6) DEFAULT '0.000000',
  `total_deducciones` decimal(20,6) DEFAULT '0.000000',
  `liquido_recibir` decimal(20,6) DEFAULT '0.000000',
  PRIMARY KEY (`iddetalle_nomina_pagos_quincenales`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.detalle_nomina_pagos_quincenales: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_nota_credito
CREATE TABLE IF NOT EXISTS `detalle_nota_credito` (
  `iddetalle_nota_credito` int(11) NOT NULL AUTO_INCREMENT,
  `idnota_credito` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_venta` varchar(50) NOT NULL DEFAULT '0',
  `descuento` decimal(11,6) NOT NULL,
  `descripcion_detalle` varchar(250) NOT NULL,
  `stockinven` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `subtotaldes1` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_ventaSistema` varchar(50) NOT NULL DEFAULT '0.000000',
  `precio_ventaSistema2` varchar(50) NOT NULL DEFAULT '0.000000',
  `subtotal1` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `cantidadpresentacion` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `totalcantidadpresentacion` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `presen` varchar(50) NOT NULL DEFAULT '0',
  `precio_recargo` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `q_ref` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_recargoPV` varchar(50) NOT NULL DEFAULT '0.000000',
  `precio_recargoQRef` varchar(50) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`iddetalle_nota_credito`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.detalle_nota_credito: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_nota_debito
CREATE TABLE IF NOT EXISTS `detalle_nota_debito` (
  `iddetalle_nota_debito` int(11) NOT NULL AUTO_INCREMENT,
  `idnota_debito` int(11) NOT NULL,
  `idingreso` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_compra` decimal(11,2) NOT NULL,
  `precio_venta` decimal(11,2) NOT NULL,
  `descuento_porcentaje` decimal(11,2) NOT NULL,
  `stock_inventario` decimal(11,2) NOT NULL,
  `precio_ventaNocturno` decimal(11,2) NOT NULL,
  `precio_rango1` decimal(11,2) NOT NULL,
  `precio_rango2` decimal(11,2) NOT NULL,
  `precio_rango3` decimal(11,2) NOT NULL,
  `precio_unidad` decimal(11,2) NOT NULL,
  `precio_blister` decimal(11,2) NOT NULL,
  `precio_caja` decimal(11,2) NOT NULL,
  `precio_fardo` decimal(11,2) NOT NULL,
  `precio_sacos` decimal(11,2) NOT NULL,
  `precio_paquete` decimal(11,2) NOT NULL,
  `cantidadpresentacion` decimal(11,2) NOT NULL,
  `totalcantidadpresentacion` decimal(11,2) NOT NULL,
  `presentacion` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`iddetalle_nota_debito`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.detalle_nota_debito: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_orden_compra
CREATE TABLE IF NOT EXISTS `detalle_orden_compra` (
  `iddetalle_orden_compra` int(11) NOT NULL AUTO_INCREMENT,
  `idorden_compra` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_compra` decimal(11,2) NOT NULL,
  `precio_venta` decimal(11,2) NOT NULL,
  `descuento_porcentaje` decimal(11,2) NOT NULL,
  `stock_inventario` decimal(11,2) NOT NULL,
  `precio_ventaNocturno` decimal(11,2) NOT NULL,
  `precio_rango1` decimal(11,2) NOT NULL,
  `precio_rango1_Distribuidor` decimal(11,2) NOT NULL,
  `precio_rango1_Mayorista` decimal(11,2) NOT NULL,
  `precio_rango2` decimal(11,2) NOT NULL,
  `precio_rango2_DistribuidorDos` decimal(11,2) NOT NULL,
  `precio_rango2_MayoristaDos` decimal(11,2) NOT NULL,
  `precio_rango3` decimal(11,2) NOT NULL,
  `precio_rango3_DistribuidorTres` decimal(11,2) NOT NULL,
  `precio_rango3_MayoristaTres` decimal(11,2) NOT NULL,
  `precio_unidad` decimal(11,2) NOT NULL,
  `precio_blister` decimal(11,2) NOT NULL,
  `precio_caja` decimal(11,2) NOT NULL,
  `precio_fardo` decimal(11,2) NOT NULL,
  `precio_sacos` decimal(11,2) NOT NULL,
  `precio_paquete` decimal(11,2) NOT NULL,
  `precio_07` decimal(11,2) NOT NULL,
  `precio_08` decimal(11,2) NOT NULL,
  `precio_09` decimal(11,2) NOT NULL,
  `precio_10` decimal(11,2) NOT NULL,
  `precio_11` decimal(11,2) NOT NULL,
  `precio_12` decimal(11,2) NOT NULL,
  `precio_13` decimal(11,2) NOT NULL,
  `precio_14` decimal(11,2) NOT NULL,
  `precio_15` decimal(11,2) NOT NULL,
  `precio_16` decimal(11,2) NOT NULL,
  `precio_17` decimal(11,2) NOT NULL,
  `precio_18` decimal(11,2) NOT NULL,
  `precio_19` decimal(11,2) NOT NULL,
  `precio_20` decimal(11,2) NOT NULL,
  `cantidadpresentacion` decimal(11,2) NOT NULL,
  `totalcantidadpresentacion` decimal(11,2) NOT NULL,
  `presentacion` varchar(50) NOT NULL DEFAULT '',
  `fechavencimiento` datetime NOT NULL,
  `pc_anterior_anterior` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `descripcion_detalle` varchar(100) NOT NULL DEFAULT '',
  `idsucursalDestino` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`iddetalle_orden_compra`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.detalle_orden_compra: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_produccion
CREATE TABLE IF NOT EXISTS `detalle_produccion` (
  `iddetalle_produccion` int(11) NOT NULL AUTO_INCREMENT,
  `idproduccion` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` decimal(20,2) NOT NULL DEFAULT '0.00',
  `precio_compra` decimal(20,2) NOT NULL DEFAULT '0.00',
  `precio_venta` decimal(20,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(20,2) NOT NULL DEFAULT '0.00',
  `tipo_item` varchar(50) NOT NULL DEFAULT 'Producto',
  PRIMARY KEY (`iddetalle_produccion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.detalle_produccion: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_revision_vehiculo
CREATE TABLE IF NOT EXISTS `detalle_revision_vehiculo` (
  `iddetalle_revision_vehiculo` int(11) NOT NULL AUTO_INCREMENT,
  `idingreso_vehiculo` int(11) NOT NULL,
  `nombre_revision` varchar(255) NOT NULL,
  `estado_100` tinyint(1) NOT NULL DEFAULT '0',
  `estado_75` tinyint(1) NOT NULL DEFAULT '0',
  `estado_50` tinyint(1) NOT NULL DEFAULT '0',
  `cambio_sugerido` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`iddetalle_revision_vehiculo`),
  KEY `fk_revision_ingreso` (`idingreso_vehiculo`),
  CONSTRAINT `fk_revision_ingreso` FOREIGN KEY (`idingreso_vehiculo`) REFERENCES `ingreso_vehiculo` (`idingreso_vehiculo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.detalle_revision_vehiculo: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_solicitud_productos
CREATE TABLE IF NOT EXISTS `detalle_solicitud_productos` (
  `iddetalle_solicitud_productos` int(11) NOT NULL AUTO_INCREMENT,
  `idsolicitud_productos` int(11) NOT NULL DEFAULT '0',
  `idarticulo` int(11) NOT NULL DEFAULT '0',
  `precio_compra` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `stockinven` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `cantidadpresentacion` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `cantidad` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `presentacion` varchar(50) NOT NULL DEFAULT '0',
  `totalcantidadpresentacion` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idsucursal` int(11) NOT NULL DEFAULT '0',
  `estado` varchar(50) NOT NULL DEFAULT '0',
  `condicion` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`iddetalle_solicitud_productos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.detalle_solicitud_productos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_traslado_sucursal
CREATE TABLE IF NOT EXISTS `detalle_traslado_sucursal` (
  `id_detalle_traslado_sucursal` int(11) NOT NULL AUTO_INCREMENT,
  `idtraladosucursal` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `descripcion_detalle` varchar(350) NOT NULL DEFAULT '0',
  `idsucursalorigen` int(11) NOT NULL,
  `idsucursaldestino` int(11) NOT NULL,
  `precio_venta` varchar(50) NOT NULL,
  `cantidadpresentacion` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `totalcantidadpresentacion` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `presentacion` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_detalle_traslado_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.detalle_traslado_sucursal: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_traslado_sucursal_entrada
CREATE TABLE IF NOT EXISTS `detalle_traslado_sucursal_entrada` (
  `iddetalle_traslado_sucursal_entrada` int(11) NOT NULL AUTO_INCREMENT,
  `idtraladosucursal_entrada` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `descripcion_detalle` varchar(250) NOT NULL,
  `idsucursalorigen` int(11) NOT NULL,
  `idsucursaldestino` int(11) NOT NULL,
  `precio_venta` varchar(50) NOT NULL,
  `cantidadpresentacion` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `totalcantidadpresentacion` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `presentacion` varchar(50) NOT NULL DEFAULT '0',
  `fecha_vencimiento` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`iddetalle_traslado_sucursal_entrada`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.detalle_traslado_sucursal_entrada: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_traslado_sucursal_entrada_fechasvencimiento
CREATE TABLE IF NOT EXISTS `detalle_traslado_sucursal_entrada_fechasvencimiento` (
  `iddetalle_traslado_sucursal_entrada_fechasvencimiento` int(11) NOT NULL AUTO_INCREMENT,
  `idtraladosucursal_entrada` int(11) NOT NULL,
  `idoperaciones_compras_ventas` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `cantidad2` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `descripcion_detalle` varchar(250) DEFAULT NULL,
  `idsucursalorigen` int(11) NOT NULL,
  `idsucursaldestino` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `precio_venta` varchar(50) DEFAULT NULL,
  `cantidadpresentacion` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `totalcantidadpresentacion` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `presentacion` varchar(50) NOT NULL DEFAULT '0',
  `fecha_vencimiento` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`iddetalle_traslado_sucursal_entrada_fechasvencimiento`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.detalle_traslado_sucursal_entrada_fechasvencimiento: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_venta
CREATE TABLE IF NOT EXISTS `detalle_venta` (
  `iddetalle_venta` int(11) NOT NULL AUTO_INCREMENT,
  `idventa` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_venta` varchar(50) NOT NULL DEFAULT '0',
  `precio_compra` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `descuento` decimal(11,6) NOT NULL,
  `descripcion_detalle` varchar(250) NOT NULL,
  `stockinven` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `subtotaldes1` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_ventaSistema` varchar(50) NOT NULL DEFAULT '0.000000',
  `precio_ventaSistema2` varchar(50) NOT NULL DEFAULT '0.000000',
  `subtotal1` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `cantidadpresentacion` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `totalcantidadpresentacion` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `presen` varchar(50) NOT NULL DEFAULT '0',
  `precio_recargo` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `q_ref` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_recargoPV` varchar(50) NOT NULL DEFAULT '0.000000',
  `precio_recargoQRef` varchar(50) NOT NULL DEFAULT '0.000000',
  `valor_descuentoGeneralLista` varchar(50) NOT NULL DEFAULT '0.000000',
  `tipo` varchar(50) NOT NULL DEFAULT '0',
  `idarticulopadre` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`iddetalle_venta`),
  KEY `fk_detalle_venta_venta_idx` (`idventa`),
  KEY `fk_detalle_venta_articulo_idx` (`idarticulo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.detalle_venta: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_ventaExtra
CREATE TABLE IF NOT EXISTS `detalle_ventaExtra` (
  `iddetalle_ventaExtra` int(11) NOT NULL AUTO_INCREMENT,
  `idventa` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `idproducto` int(11) NOT NULL,
  `cantidad` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_venta` decimal(20,6) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`iddetalle_ventaExtra`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.detalle_ventaExtra: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_venta_fechasvencimiento
CREATE TABLE IF NOT EXISTS `detalle_venta_fechasvencimiento` (
  `iddetalle_venta_fechasvencimiento` int(11) NOT NULL AUTO_INCREMENT,
  `idventa` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `idingreso` int(11) NOT NULL,
  `idtraladosucursal_entrada` int(11) NOT NULL,
  `fecha_vencimiento` varchar(50) NOT NULL DEFAULT '',
  `cantidad` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `cantidad2` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `idsucursal` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idoperaciones_compras_ventas` int(11) NOT NULL,
  PRIMARY KEY (`iddetalle_venta_fechasvencimiento`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.detalle_venta_fechasvencimiento: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.detalle_venta_salida
CREATE TABLE IF NOT EXISTS `detalle_venta_salida` (
  `iddetalle_venta_salida` int(11) NOT NULL AUTO_INCREMENT,
  `idventa_salida` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_venta` varchar(50) NOT NULL DEFAULT '0',
  `precio_compra` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `descuento` decimal(11,6) NOT NULL,
  `descripcion_detalle` varchar(250) NOT NULL,
  `stockinven` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `subtotaldes1` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_ventaSistema` varchar(50) NOT NULL DEFAULT '0.000000',
  `precio_ventaSistema2` varchar(50) NOT NULL DEFAULT '0.000000',
  `subtotal1` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `cantidadpresentacion` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `totalcantidadpresentacion` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `presen` varchar(50) NOT NULL DEFAULT '0',
  `precio_recargo` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `q_ref` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `precio_recargoPV` varchar(50) NOT NULL DEFAULT '0.000000',
  `precio_recargoQRef` varchar(50) NOT NULL DEFAULT '0.000000',
  `valor_descuentoGeneralLista` varchar(50) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`iddetalle_venta_salida`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.detalle_venta_salida: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.empleados
CREATE TABLE IF NOT EXISTS `empleados` (
  `idempleado` int(11) NOT NULL AUTO_INCREMENT,
  `nombres` varchar(100) NOT NULL,
  `cui` varchar(13) NOT NULL,
  `nit` varchar(15) DEFAULT NULL,
  `direccion` varchar(256) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `hijos` int(2) NOT NULL DEFAULT '0',
  `sexo` varchar(50) NOT NULL DEFAULT 'M',
  `estado_civil` varchar(20) DEFAULT NULL,
  `nacionalidad` varchar(50) DEFAULT 'Guatemalteca',
  `nivel_educativo` varchar(50) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `edad` varchar(15) DEFAULT NULL,
  `puesto` varchar(100) NOT NULL,
  `fecha_inicio_laboral` date NOT NULL,
  `fecha_fin_laboral` date DEFAULT NULL COMMENT 'Solo si el empleado está inactivo',
  `salario_base` decimal(10,2) NOT NULL COMMENT 'Salario sin bonificaciones',
  `bonificacion` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Bonificación de Ley/Decreto',
  `bono_productividad` decimal(10,2) NOT NULL DEFAULT '0.00',
  `salario_extra` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Cualquier monto extra no recurrente',
  `descuento_igss` decimal(10,2) NOT NULL DEFAULT '0.00',
  `descuento_isr` decimal(10,2) NOT NULL DEFAULT '0.00',
  `descuento_prestamo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `otros_descuentos` decimal(10,2) NOT NULL DEFAULT '0.00',
  `anticipo_salarial` decimal(10,2) NOT NULL DEFAULT '0.00',
  `condicion` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Activo, 0=Inactivo/Despedido',
  `codigo` varchar(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idempleado`) USING BTREE,
  UNIQUE KEY `cui` (`cui`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.empleados: ~1 rows (aproximadamente)
INSERT INTO `empleados` (`idempleado`, `nombres`, `cui`, `nit`, `direccion`, `fecha_nacimiento`, `hijos`, `sexo`, `estado_civil`, `nacionalidad`, `nivel_educativo`, `telefono`, `edad`, `puesto`, `fecha_inicio_laboral`, `fecha_fin_laboral`, `salario_base`, `bonificacion`, `bono_productividad`, `salario_extra`, `descuento_igss`, `descuento_isr`, `descuento_prestamo`, `otros_descuentos`, `anticipo_salarial`, `condicion`, `codigo`) VALUES
	(1, 'NOMBRE COMPLETOS', '1313131', '3131313', 'DIRECCION DEL EMPLEADO', '2003-01-01', 0, 'Masculino', 'Soltero(a)', 'Guatemalteco', 'Licenciatura/Universidad', '+502 5821-6179', '20', 'PUESTO', '2026-01-01', '0000-00-00', 3000.00, 250.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 'EMP-01');

-- Volcando estructura para tabla dbsuper365.empresa
CREATE TABLE IF NOT EXISTS `empresa` (
  `idempresa` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(256) DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idempresa`) USING BTREE,
  KEY `idx_categoria_idcategoria` (`idempresa`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.empresa: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.evento
CREATE TABLE IF NOT EXISTS `evento` (
  `idevento` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `descripcion` text,
  `fecha_evento` datetime DEFAULT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `idsucursal` int(11) DEFAULT NULL,
  PRIMARY KEY (`idevento`),
  KEY `idcliente` (`idcliente`),
  CONSTRAINT `evento_ibfk_1` FOREIGN KEY (`idcliente`) REFERENCES `persona` (`idpersona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.evento: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.ficha_empleado
CREATE TABLE IF NOT EXISTS `ficha_empleado` (
  `idficha_empleado` int(11) NOT NULL AUTO_INCREMENT,
  `cod_empleado` varchar(20) NOT NULL,
  `nombre` varchar(256) NOT NULL,
  `direccion` varchar(256) NOT NULL,
  `dpi_no` varchar(30) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `celular` varchar(15) NOT NULL,
  `tipo_sexo` varchar(30) NOT NULL,
  `estado_civil` varchar(30) NOT NULL,
  `forma_pago` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `fecha_nacimiento` datetime NOT NULL,
  `cta_no` varchar(60) NOT NULL,
  `tipo_banco` varchar(20) NOT NULL,
  `sueldo_base` decimal(11,2) NOT NULL,
  `bonificacion` decimal(11,2) NOT NULL,
  `horaextra` decimal(11,2) NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  `jefe_immediato_1` varchar(200) NOT NULL,
  `tiempo_trabajo_1` varchar(30) NOT NULL,
  `fecha_finalizacion_labora_1` datetime NOT NULL,
  `telefono_1` varchar(15) NOT NULL,
  `jefe_immediato_2` varchar(200) NOT NULL,
  `tiempo_trabajo_2` varchar(30) NOT NULL,
  `fecha_finalizacion_labora_2` datetime NOT NULL,
  `telefono_2` varchar(15) NOT NULL,
  `jefe_immediato_3` varchar(200) NOT NULL,
  `tiempo_trabajo_3` varchar(30) NOT NULL,
  `fecha_finalizacion_labora_3` datetime NOT NULL,
  `telefono_3` varchar(15) NOT NULL,
  `nombre_refe_laboral_1` varchar(256) NOT NULL,
  `telefono_refe_laboral_1` varchar(15) NOT NULL,
  `parentesco_refe_laboral_1` varchar(200) NOT NULL,
  `nombre_refe_laboral_2` varchar(200) NOT NULL,
  `telefono_refe_laboral_2` varchar(15) NOT NULL,
  `parentesco_refe_laboral_2` varchar(200) NOT NULL,
  `nombre_refe_laboral_3` varchar(200) NOT NULL,
  `telefono_refe_laboral_3` varchar(15) NOT NULL,
  `parentesco_refe_laboral_3` varchar(200) NOT NULL,
  `dpi_Lado1_imagen` varchar(256) NOT NULL,
  `dpi_lado2_imagen` varchar(256) NOT NULL,
  `carta_recomendacion1_imagen` varchar(256) NOT NULL,
  `carta_recomendacion2_imagen` varchar(256) NOT NULL,
  `carta_recomendacion3_imagen` varchar(50) NOT NULL,
  `carta_trabajo1_imagen` varchar(256) NOT NULL,
  `carta_trabajo2_imagen` varchar(256) NOT NULL,
  `carta_trabajo3_imagen` varchar(256) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  `hora_entrada` varchar(20) NOT NULL,
  `hora_refaccion` varchar(20) NOT NULL,
  `entrada_refaccion2` varchar(20) NOT NULL,
  `hora_almuerzo` varchar(20) NOT NULL,
  `hora_almuerzo2` varchar(20) NOT NULL,
  `hora_salida` varchar(20) NOT NULL,
  PRIMARY KEY (`idficha_empleado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.ficha_empleado: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.guias_excel
CREATE TABLE IF NOT EXISTS `guias_excel` (
  `idguias_excel` int(11) NOT NULL AUTO_INCREMENT,
  `idtransporte` int(11) DEFAULT NULL,
  `obervacioncargaexcel` varchar(500) DEFAULT NULL,
  `fecha_cargaExcel` date DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `idsucursal` int(11) DEFAULT NULL,
  `condicion` int(11) DEFAULT '1',
  PRIMARY KEY (`idguias_excel`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.guias_excel: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.horarios_produccion
CREATE TABLE IF NOT EXISTS `horarios_produccion` (
  `idhora_produccion` int(11) NOT NULL AUTO_INCREMENT,
  `hora` time NOT NULL,
  `hora2` time NOT NULL,
  `descripcion` varchar(256) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  PRIMARY KEY (`idhora_produccion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.horarios_produccion: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.imagenes_producto
CREATE TABLE IF NOT EXISTS `imagenes_producto` (
  `idimagenes_producto` int(11) NOT NULL AUTO_INCREMENT,
  `idarticulo` int(11) DEFAULT NULL,
  `ruta_imagen` varchar(255) NOT NULL,
  `orden` int(11) DEFAULT '1',
  PRIMARY KEY (`idimagenes_producto`) USING BTREE,
  KEY `idarticulo` (`idarticulo`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.imagenes_producto: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.informacion_ticket
CREATE TABLE IF NOT EXISTS `informacion_ticket` (
  `id_informacionticket` int(11) NOT NULL AUTO_INCREMENT,
  `instruccionesdeticket` text NOT NULL,
  `mensajefinal` varchar(500) NOT NULL DEFAULT '0',
  `correlativo_ticket_factura` int(11) NOT NULL,
  `horario_atencion` varchar(100) NOT NULL DEFAULT '0',
  `idusuario` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `condicion` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_informacionticket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.informacion_ticket: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.ingreso
CREATE TABLE IF NOT EXISTS `ingreso` (
  `idingreso` int(11) NOT NULL AUTO_INCREMENT,
  `idproveedor` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `idnota_debito` int(11) NOT NULL DEFAULT '0',
  `idusuario_update` int(11) NOT NULL,
  `tipo_comprobante` varchar(50) NOT NULL,
  `serie_comprobante` varchar(50) DEFAULT NULL,
  `num_comprobante` varchar(10) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total_compra` decimal(11,2) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `forma_pago` varchar(50) NOT NULL,
  `dias_credito` varchar(10) NOT NULL,
  `fecha_hora_pago_credito` varchar(50) NOT NULL DEFAULT '',
  `valor_pagar` decimal(11,2) NOT NULL,
  `saldo_ingreso` decimal(11,2) NOT NULL,
  `tipo_pago` varchar(20) NOT NULL,
  `no_cheque` varchar(30) NOT NULL,
  `fecha_hora_generacion_pago` datetime NOT NULL,
  `tipo_banco` varchar(50) NOT NULL,
  `numero_boleta` varchar(50) NOT NULL,
  `recibo_caja_numero` varchar(50) NOT NULL,
  `direccion_entrega_orden_compra` varchar(256) NOT NULL,
  `fecha_entrega_orden_compra` varchar(50) NOT NULL DEFAULT '',
  `observacion_orden_compra` varchar(256) NOT NULL,
  `usuarui_modificacion` varchar(50) NOT NULL,
  `fecha_modificacion` datetime NOT NULL,
  `motivo_modificacion` text NOT NULL,
  `total_comprades` decimal(11,2) NOT NULL,
  `fechacreacion` datetime NOT NULL,
  `fechaUpdate` datetime NOT NULL,
  `tipo_ingreso_producion` varchar(50) NOT NULL DEFAULT '0',
  `notadebito` varchar(50) NOT NULL DEFAULT 'NO',
  `idorden_compra` varchar(50) NOT NULL DEFAULT 'NO',
  PRIMARY KEY (`idingreso`),
  KEY `fk_ingreso_persona_idx` (`idproveedor`),
  KEY `fk_ingreso_usuario_idx` (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.ingreso: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.ingreso_vehiculo
CREATE TABLE IF NOT EXISTS `ingreso_vehiculo` (
  `idingreso_vehiculo` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `fechaCreacion` varchar(50) NOT NULL,
  `no_placa` varchar(100) NOT NULL,
  `no_chasis` varchar(100) NOT NULL,
  `serie` varchar(100) NOT NULL,
  `no_motor` varchar(100) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `km` varchar(100) NOT NULL,
  `checkLado1` varchar(100) NOT NULL,
  `descripcionLado1` varchar(100) NOT NULL,
  `checkLado2` varchar(100) NOT NULL,
  `descripcionLado2` varchar(100) NOT NULL,
  `checkLado3` varchar(100) NOT NULL,
  `descripcionLado3` varchar(100) NOT NULL,
  `checkLado4` varchar(100) NOT NULL,
  `descripcionLado4` varchar(100) NOT NULL,
  `checkLado5` varchar(100) NOT NULL,
  `descripcionLado5` varchar(100) NOT NULL,
  `check_bateria` varchar(100) NOT NULL,
  `check_tcs` varchar(100) NOT NULL,
  `check_motor` varchar(100) NOT NULL,
  `check_aceite` varchar(100) NOT NULL,
  `check_airbag` varchar(100) NOT NULL,
  `check_tpms` varchar(100) NOT NULL,
  `check_abs` varchar(100) NOT NULL,
  `imagen1` varchar(100) NOT NULL,
  `imagen2` varchar(100) NOT NULL,
  `imagen3` varchar(100) NOT NULL,
  `imagen4` varchar(100) NOT NULL,
  `imagen5` varchar(100) NOT NULL,
  `imagen6` varchar(100) NOT NULL,
  `imagen7` varchar(100) NOT NULL,
  `imagen8` varchar(100) NOT NULL,
  `descripcion1` varchar(100) NOT NULL,
  `descripcion2` varchar(100) NOT NULL,
  `descripcion3` varchar(100) NOT NULL,
  `descripcion4` varchar(100) NOT NULL,
  `descripcion5` varchar(100) NOT NULL,
  `descripcion6` varchar(100) NOT NULL,
  `descripcion7` varchar(100) NOT NULL,
  `descripcion8` varchar(100) NOT NULL,
  `trabajos_detalle` text NOT NULL,
  `observaciones_adicionales` text NOT NULL,
  `fecha_anulacion` varchar(100) NOT NULL,
  `idusuario_anulacion` varchar(100) NOT NULL,
  `idvendedor` int(11) DEFAULT NULL,
  `facturado` tinyint(4) DEFAULT '0',
  `horaInicio` varchar(100) NOT NULL,
  `horaFinalizada` varchar(100) NOT NULL,
  `tecnico` varchar(100) NOT NULL,
  `gradoAceite` varchar(100) NOT NULL,
  `filtroAceite` varchar(100) NOT NULL,
  `filtroAire` varchar(100) NOT NULL,
  `filtroCombustible` varchar(100) NOT NULL,
  `observaciones` text NOT NULL,
  `idusuario_modificacion` varchar(100) NOT NULL,
  `fecha_modificacion` varchar(100) NOT NULL,
  `idventa` varchar(100) NOT NULL,
  PRIMARY KEY (`idingreso_vehiculo`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.ingreso_vehiculo: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.lectura
CREATE TABLE IF NOT EXISTS `lectura` (
  `idlectura` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_vehiculo` varchar(50) NOT NULL,
  `placa` varchar(50) NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  `idcuadre_caja` int(11) DEFAULT NULL,
  `idusuario_delete` int(11) DEFAULT NULL,
  `fecha_delete` datetime DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'LECTURA',
  PRIMARY KEY (`idlectura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.lectura: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.linea
CREATE TABLE IF NOT EXISTS `linea` (
  `idlinea` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  PRIMARY KEY (`idlinea`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.linea: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.logs
CREATE TABLE IF NOT EXISTS `logs` (
  `idlogs` int(11) NOT NULL AUTO_INCREMENT,
  `idventa` int(11) NOT NULL DEFAULT '0',
  `idnota_credito` int(11) NOT NULL DEFAULT '0',
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idsucursal` int(11) NOT NULL DEFAULT '0',
  `fecha_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `JsonIntegracionEcoFactura` text NOT NULL,
  `resultado` text NOT NULL,
  `ArrayResultado` text NOT NULL,
  `codigo_articulo` text NOT NULL,
  `descripcion_error` text NOT NULL,
  `fecha_error` datetime NOT NULL,
  PRIMARY KEY (`idlogs`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.logs: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.marca
CREATE TABLE IF NOT EXISTS `marca` (
  `idmarca` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `condicion` tinyint(4) NOT NULL,
  PRIMARY KEY (`idmarca`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.marca: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.mensajero
CREATE TABLE IF NOT EXISTS `mensajero` (
  `idmensajero` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `telefono` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `idusuario` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idmensajero`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.mensajero: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.mensajero_comentarios
CREATE TABLE IF NOT EXISTS `mensajero_comentarios` (
  `idmensajero_comentarios` int(11) NOT NULL AUTO_INCREMENT,
  `idventa` int(11) NOT NULL DEFAULT '0',
  `comentario_mensajero` text NOT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT '0',
  `fecha_crecion` tinyint(4) NOT NULL DEFAULT '0',
  `idusuario` tinyint(4) NOT NULL DEFAULT '0',
  `idusuario_updadte` tinyint(4) NOT NULL DEFAULT '0',
  `idsucursal` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idmensajero_comentarios`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.mensajero_comentarios: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.mesa
CREATE TABLE IF NOT EXISTS `mesa` (
  `idmesa` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  PRIMARY KEY (`idmesa`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.mesa: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.modelo
CREATE TABLE IF NOT EXISTS `modelo` (
  `idmodelo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` varchar(256) DEFAULT NULL,
  `condicion` tinyint(4) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`idmodelo`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.modelo: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.nomina_pagos
CREATE TABLE IF NOT EXISTS `nomina_pagos` (
  `idnomina_pagos` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `descripcion` varchar(250) NOT NULL DEFAULT 'N/A',
  `condicion` tinyint(4) NOT NULL DEFAULT '1',
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `fecha_creacion` datetime NOT NULL,
  `idusuario_modificacion` int(11) NOT NULL DEFAULT '0',
  `fecha_modificacion` datetime NOT NULL,
  `idusuario_delete` int(11) NOT NULL DEFAULT '0',
  `fecha_delete` datetime NOT NULL,
  PRIMARY KEY (`idnomina_pagos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.nomina_pagos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.nomina_pagos_14_aguinaldo
CREATE TABLE IF NOT EXISTS `nomina_pagos_14_aguinaldo` (
  `idnomina_pagos_14_aguinaldo` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `descripcion` varchar(250) NOT NULL DEFAULT 'N/A',
  `condicion` tinyint(4) NOT NULL DEFAULT '1',
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `fecha_creacion` datetime NOT NULL,
  `idusuario_modificacion` int(11) NOT NULL DEFAULT '0',
  `fecha_modificacion` datetime NOT NULL,
  `idusuario_delete` int(11) NOT NULL DEFAULT '0',
  `fecha_delete` datetime NOT NULL,
  `tipo_operacion` varchar(50) NOT NULL,
  PRIMARY KEY (`idnomina_pagos_14_aguinaldo`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.nomina_pagos_14_aguinaldo: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.nomina_pagos_quincenales
CREATE TABLE IF NOT EXISTS `nomina_pagos_quincenales` (
  `idnomina_pagos_quincenales` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `descripcion` varchar(250) NOT NULL DEFAULT 'N/A',
  `condicion` tinyint(4) NOT NULL DEFAULT '1',
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `fecha_creacion` datetime NOT NULL,
  `idusuario_modificacion` int(11) NOT NULL DEFAULT '0',
  `fecha_modificacion` datetime NOT NULL,
  `idusuario_delete` int(11) NOT NULL DEFAULT '0',
  `fecha_delete` datetime NOT NULL,
  PRIMARY KEY (`idnomina_pagos_quincenales`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.nomina_pagos_quincenales: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.nota_credito
CREATE TABLE IF NOT EXISTS `nota_credito` (
  `idnota_credito` int(11) NOT NULL AUTO_INCREMENT,
  `idventa` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `tipo_comprobante` varchar(20) NOT NULL,
  `serie_comprobante` varchar(7) DEFAULT NULL,
  `num_comprobante` varchar(10) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total_venta` decimal(11,2) NOT NULL,
  `total_ventades` decimal(11,2) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  `tipo_pago` varchar(20) NOT NULL,
  `numero_boleta` varchar(150) NOT NULL,
  `usuariopago` int(11) NOT NULL,
  `fechapago` datetime NOT NULL,
  `estadopago` varchar(20) NOT NULL,
  `forma_pago` varchar(30) NOT NULL,
  `dias_credito` varchar(5) NOT NULL,
  `fecha_hora_cobro` datetime NOT NULL,
  `tipo_banco` varchar(30) NOT NULL,
  `recibo_caja_numero` varchar(60) NOT NULL,
  `idcuenta` int(11) NOT NULL,
  `fecha_hora_siguiente_pago` datetime NOT NULL,
  `observacion_credito` varchar(250) NOT NULL,
  `total_abono` decimal(11,2) NOT NULL,
  `saldo_venta` decimal(11,2) NOT NULL,
  `cefectivo` decimal(11,2) NOT NULL,
  `ctarjeta` decimal(11,2) NOT NULL,
  `ctransferencia` decimal(11,2) NOT NULL,
  `ccredito` decimal(11,2) NOT NULL,
  `rescambio` decimal(11,2) NOT NULL,
  `autorizacionEcoFactura` varchar(200) NOT NULL,
  `serie_ecoFactura` varchar(200) NOT NULL,
  `numero_ecoFactura` varchar(200) NOT NULL,
  `fechaCertificacion_ecoFactura` datetime NOT NULL,
  `nombre_vendedor` varchar(250) NOT NULL,
  `numero_pagos` varchar(100) NOT NULL,
  `fecha_hora_pago` varchar(100) NOT NULL,
  `fecha_hora_vencimiento_factura` varchar(100) NOT NULL,
  `monto_abono` varchar(100) NOT NULL,
  `tipo_operacion` varchar(100) NOT NULL DEFAULT 'APERTURA',
  `idcuadre_caja` int(11) DEFAULT '0',
  `tipo_pagoBacVisaNet` varchar(50) DEFAULT '0',
  `opcionesAdicionales` varchar(50) DEFAULT '0',
  `valor_tarjeta` decimal(20,6) DEFAULT '0.000000',
  `propina` decimal(20,6) DEFAULT '0.000000',
  `id_add_orden` int(11) DEFAULT '0',
  `fecha_creacion` datetime DEFAULT NULL,
  `autorizacionEcoFactura_venta` varchar(250) DEFAULT '0',
  `serie_comprobante_venta` varchar(50) DEFAULT '0',
  `numero_ecoFactura_venta` varchar(50) DEFAULT '0',
  `fecha_hora_nc` datetime DEFAULT NULL,
  `motivo_nc` varchar(250) DEFAULT '0',
  PRIMARY KEY (`idnota_credito`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.nota_credito: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.nota_debito
CREATE TABLE IF NOT EXISTS `nota_debito` (
  `idnota_debito` int(11) NOT NULL AUTO_INCREMENT,
  `idingreso` int(11) NOT NULL,
  `idproveedor` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `idusuario_update` int(11) NOT NULL,
  `tipo_comprobante` varchar(50) NOT NULL,
  `serie_comprobante` varchar(50) DEFAULT NULL,
  `num_comprobante` varchar(10) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `fecha_hora_ND` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total_compra` decimal(11,2) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `motivo_ND` varchar(500) NOT NULL,
  `forma_pago` varchar(50) NOT NULL,
  `dias_credito` varchar(10) NOT NULL,
  `fecha_hora_pago_credito` varchar(50) NOT NULL DEFAULT '',
  `valor_pagar` decimal(11,2) NOT NULL,
  `saldo_ingreso` decimal(11,2) NOT NULL,
  `tipo_pago` varchar(20) NOT NULL,
  `no_cheque` varchar(30) NOT NULL,
  `fecha_hora_generacion_pago` datetime NOT NULL,
  `tipo_banco` varchar(50) NOT NULL,
  `numero_boleta` varchar(50) NOT NULL,
  `recibo_caja_numero` varchar(50) NOT NULL,
  `direccion_entrega_orden_compra` varchar(256) NOT NULL,
  `fecha_entrega_orden_compra` varchar(50) NOT NULL DEFAULT '',
  `observacion_orden_compra` varchar(256) NOT NULL,
  `usuarui_modificacion` varchar(50) NOT NULL,
  `fecha_modificacion` datetime NOT NULL,
  `motivo_modificacion` text NOT NULL,
  `total_comprades` decimal(11,2) NOT NULL,
  `fechacreacion` datetime NOT NULL,
  `fechaUpdate` datetime NOT NULL,
  `tipo_ingreso_producion` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idnota_debito`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.nota_debito: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.operaciones_compras_ventas
CREATE TABLE IF NOT EXISTS `operaciones_compras_ventas` (
  `idoperaciones_compras_ventas` int(11) NOT NULL AUTO_INCREMENT,
  `idingreso` int(11) NOT NULL DEFAULT '0',
  `idventa` int(11) NOT NULL DEFAULT '0',
  `idventa_salida` int(11) NOT NULL DEFAULT '0',
  `idtraladosucursal` int(11) NOT NULL DEFAULT '0',
  `idtraladosucursal_entrada` int(11) NOT NULL DEFAULT '0',
  `iddevolucion` int(11) NOT NULL DEFAULT '0',
  `idnota_debito` int(11) NOT NULL DEFAULT '0',
  `cantidad_compras` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cantidad_ventas` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cantidad_ventas_salidas` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cantidad_entrada` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cantidad_devolucion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cantidad_notaDebito` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cantidad_salida` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock_inventario` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fecha_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fecha_horaCreacion` varchar(50) NOT NULL,
  `idarticulo` int(11) NOT NULL DEFAULT '0',
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idsucursal` int(11) NOT NULL DEFAULT '0',
  `estado` varchar(50) NOT NULL DEFAULT 'Aceptado',
  `fecha_vencimiento` varchar(50) NOT NULL DEFAULT '0',
  `saldo` decimal(20,6) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`idoperaciones_compras_ventas`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.operaciones_compras_ventas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.operaciones_entre_bancos
CREATE TABLE IF NOT EXISTS `operaciones_entre_bancos` (
  `idoperaciones_entre_bancos` int(11) NOT NULL AUTO_INCREMENT,
  `idcheque` int(11) NOT NULL,
  `fecha_hora_operacion_cheque` datetime NOT NULL,
  `valor_cheque` decimal(11,2) NOT NULL,
  `iddeposito` int(11) NOT NULL,
  `fecha_hora_deposito` datetime NOT NULL,
  `valor_deposito` decimal(11,2) NOT NULL,
  `idcuenta` int(11) NOT NULL,
  `saldo_cuenta` decimal(11,2) NOT NULL,
  `saldo_final_cuenta` decimal(11,2) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `condicion` int(11) NOT NULL,
  PRIMARY KEY (`idoperaciones_entre_bancos`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.operaciones_entre_bancos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.orden
CREATE TABLE IF NOT EXISTS `orden` (
  `idorden` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `serie` varchar(100) NOT NULL,
  `descripcion_equipo` varchar(250) NOT NULL,
  `reparacion_equipo` varchar(250) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  `idusuario_modificacion` int(11) NOT NULL,
  `fecha_hora_modificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `diagnostico_reparacion` text NOT NULL,
  `idusuario_diagnostico_reparacion` int(11) NOT NULL,
  `fecha_hora_diagnostico_reparacion` datetime NOT NULL,
  `detalle_tecnico` text NOT NULL,
  `tipo_status` varchar(100) NOT NULL,
  `tecnico` varchar(100) NOT NULL,
  `fecha_hora_detalle` datetime NOT NULL,
  PRIMARY KEY (`idorden`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.orden: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.ordenes
CREATE TABLE IF NOT EXISTS `ordenes` (
  `idnueva_orden` int(11) NOT NULL AUTO_INCREMENT,
  `idtecnico` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `imei_cel` varchar(50) NOT NULL,
  `idmarca` int(11) NOT NULL,
  `idmodelo` int(11) NOT NULL,
  `idtipo_equipo` int(11) NOT NULL,
  `idcolor` int(11) NOT NULL,
  `enciende` varchar(5) NOT NULL,
  `golpes` varchar(5) NOT NULL,
  `puerto_carga` varchar(5) NOT NULL,
  `password_orden` varchar(50) NOT NULL,
  `falla_equipo` text NOT NULL,
  `diagnostico_equipo` text NOT NULL,
  `presupuesto` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `repuestos` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `anticipo` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_orden` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `codigo_ordennueva` varchar(50) NOT NULL DEFAULT '0',
  `idusuario` int(11) NOT NULL,
  `idusuario_update` int(11) DEFAULT NULL,
  `fecha_update` varchar(50) DEFAULT NULL,
  `fecha_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `condicion` tinyint(4) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `num_nueva_orden` int(11) NOT NULL DEFAULT '0',
  `fecha_entrega` datetime NOT NULL,
  `fecha_creacion_ingreso` datetime NOT NULL,
  PRIMARY KEY (`idnueva_orden`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.ordenes: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.orden_compra
CREATE TABLE IF NOT EXISTS `orden_compra` (
  `idorden_compra` int(11) NOT NULL AUTO_INCREMENT,
  `idproveedor` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `idnota_debito` int(11) NOT NULL DEFAULT '0',
  `idusuario_update` int(11) NOT NULL,
  `tipo_comprobante` varchar(50) NOT NULL,
  `serie_comprobante` varchar(50) DEFAULT NULL,
  `num_comprobante` varchar(10) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total_compra` decimal(11,2) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `forma_pago` varchar(50) NOT NULL,
  `dias_credito` varchar(10) NOT NULL,
  `fecha_hora_pago_credito` varchar(50) NOT NULL DEFAULT '',
  `valor_pagar` decimal(11,2) NOT NULL,
  `saldo_ingreso` decimal(11,2) NOT NULL,
  `tipo_pago` varchar(20) NOT NULL,
  `no_cheque` varchar(30) NOT NULL,
  `fecha_hora_generacion_pago` datetime NOT NULL,
  `tipo_banco` varchar(50) NOT NULL,
  `numero_boleta` varchar(50) NOT NULL,
  `recibo_caja_numero` varchar(50) NOT NULL,
  `direccion_entrega_orden_compra` varchar(256) NOT NULL,
  `fecha_entrega_orden_compra` varchar(50) NOT NULL DEFAULT '',
  `observacion_orden_compra` varchar(256) NOT NULL,
  `usuarui_modificacion` varchar(50) NOT NULL,
  `fecha_modificacion` datetime NOT NULL,
  `motivo_modificacion` text NOT NULL,
  `total_comprades` decimal(11,2) NOT NULL,
  `fechacreacion` datetime NOT NULL,
  `fechaUpdate` datetime NOT NULL,
  `tipo_ingreso_producion` varchar(50) NOT NULL DEFAULT '0',
  `notadebito` varchar(50) NOT NULL DEFAULT 'NO',
  `estado_compra` varchar(50) NOT NULL DEFAULT 'Orden de compra',
  `fecha_autorizacion` varchar(50) NOT NULL DEFAULT 'Orden de compra',
  `idusuario_autorizacion` varchar(50) NOT NULL DEFAULT 'Orden de compra',
  PRIMARY KEY (`idorden_compra`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.orden_compra: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.otrosdecuentos_empleado
CREATE TABLE IF NOT EXISTS `otrosdecuentos_empleado` (
  `idotrosdecuentosempleado` int(11) NOT NULL AUTO_INCREMENT,
  `idempleado` int(11) NOT NULL DEFAULT '0',
  `monto_prestamo` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `saldo_prestamo` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `no_cuotas` int(11) NOT NULL DEFAULT '0',
  `fecha_prestamo` datetime NOT NULL,
  `fecha_ultimo_abono` datetime NOT NULL,
  `concepto_prestamo` varchar(50) NOT NULL DEFAULT '0',
  `fecha_creacion` datetime NOT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT '0',
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `fecha_modificacion` datetime NOT NULL,
  `idusuario_modificacion` int(11) NOT NULL DEFAULT '0',
  `idusuario_delete` int(11) NOT NULL DEFAULT '0',
  `fecha_delete` datetime NOT NULL,
  `tipo_operacion` varchar(50) NOT NULL DEFAULT 'PRESTAMO',
  PRIMARY KEY (`idotrosdecuentosempleado`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.otrosdecuentos_empleado: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.pagos_empleados
CREATE TABLE IF NOT EXISTS `pagos_empleados` (
  `idpagoempleado` int(11) NOT NULL AUTO_INCREMENT,
  `idficha_empleado` int(11) NOT NULL,
  `fecha_hora_ini` datetime NOT NULL,
  `fecha_hora_fin` datetime NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `horas_acumuladas` varchar(20) NOT NULL,
  `horas_tarde` varchar(20) NOT NULL,
  `horas_extra` varchar(20) NOT NULL,
  `horas_feriado` varchar(20) NOT NULL,
  `total_horas_pagar` varchar(20) NOT NULL,
  `valor_hora` decimal(11,2) NOT NULL,
  `sueldo_pagar` decimal(11,2) NOT NULL,
  `bonificacion` decimal(11,2) NOT NULL,
  `bonificacion_extra` decimal(11,2) NOT NULL,
  `descuento` decimal(11,2) NOT NULL,
  `sueldo_liquido_recibir` decimal(11,2) NOT NULL,
  `idcuenta` int(11) NOT NULL,
  `forma_pago` varchar(20) NOT NULL,
  `cheque_auto_no` varchar(30) NOT NULL,
  `estado` varchar(30) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `idusuario` int(11) NOT NULL,
  `bono14` varchar(20) NOT NULL,
  `aguinaldo` varchar(20) NOT NULL,
  `vacaciones` decimal(11,2) NOT NULL,
  `fecha_hora_de` datetime NOT NULL,
  `fecha_hora_asta` datetime NOT NULL,
  `prestacion_a_sumar` decimal(11,2) NOT NULL,
  `prestacion_a_pagar` varchar(30) NOT NULL,
  `dtrabajados` varchar(30) NOT NULL,
  PRIMARY KEY (`idpagoempleado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.pagos_empleados: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.permiso
CREATE TABLE IF NOT EXISTS `permiso` (
  `idpermiso` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idpermiso`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.permiso: ~97 rows (aproximadamente)
INSERT INTO `permiso` (`idpermiso`, `nombre`, `condicion`) VALUES
	(1, 'Escritorio x Usuario', 1),
	(2, 'Almacen', 1),
	(3, 'Compras', 1),
	(4, 'Ventas', 1),
	(5, 'Acceso', 1),
	(6, 'Consulta Compras', 1),
	(7, 'Consulta Ventas', 1),
	(8, 'Restaurante Creacion Mesas', 0),
	(9, 'CuentasXcobrar', 1),
	(10, 'Cotizaciones', 1),
	(11, 'Caja Chica', 1),
	(12, 'Reportes', 1),
	(13, 'Inventario x Sucursal', 1),
	(14, 'Cuentasxpagar', 1),
	(15, 'Salida Producto', 1),
	(16, 'Entrada Producto', 1),
	(17, 'Restaurante Ordenes', 0),
	(18, 'Nota Credito', 1),
	(19, 'Nota Debito', 1),
	(20, 'Restaurante Cobros Ordenes', 0),
	(21, 'Despacho Ventas', 1),
	(22, 'Inventario x General', 1),
	(24, 'Guias Transporte', 1),
	(25, 'Almacen Crear Articulos', 1),
	(26, 'Almacen Crear Categorias', 1),
	(27, 'Almacen Crear Sub Categorias', 1),
	(28, 'Almacen Crear Combos', 1),
	(29, 'Compras Ordenes de Compra', 1),
	(30, 'Compras Revision Orden Compra', 1),
	(31, 'Compras Ingresos', 1),
	(32, 'Compras Proveedores', 1),
	(33, 'Compras Gastos', 1),
	(34, 'Compras Reporte Ingresos', 1),
	(35, 'Ventas Facturacion', 1),
	(36, 'Ventas por Categoria', 1),
	(37, 'Clientes', 1),
	(38, 'Ventas Mensajero/Transporte', 1),
	(39, 'Acceso Usuarios', 1),
	(40, 'Acceso Sucursales', 1),
	(41, 'Acceso Mensajeros', 1),
	(42, 'Acceso Transportes', 1),
	(43, 'Acceso Vendedores', 1),
	(44, 'Consulta Compras Compras', 1),
	(45, 'Consulta Compras Detallado', 1),
	(46, 'Ctas x Cobrar Ctas', 1),
	(47, 'Ctas x Cobrar Reporte', 1),
	(48, 'Ctas x Cobrar Pagadas x Provee', 1),
	(49, 'Ctas x Pagar Ver Ctas', 1),
	(50, 'Ctas x Pagar Reporte Pagadas', 1),
	(51, 'Ctas x Pagar Reporte Pagadas x', 1),
	(52, 'Almacen Asociar Sub Categoria', 1),
	(53, 'Consulta Ventas Ventas', 1),
	(54, 'Consulta Ventas Detalle', 1),
	(55, 'Consulta Ventas Anuladas', 1),
	(56, 'Escritorio x Sucursal', 1),
	(57, 'Restaurante Toma Ordenes', 0),
	(59, 'Clientes Crear', 1),
	(60, 'Clientes Seguimiento', 1),
	(61, 'Salidas/Rebajas Inventario', 1),
	(62, 'Almacen Crear Presentacion', 1),
	(63, 'Almacen Crear Sector', 1),
	(64, 'Almacen Crear Empresa Interna', 0),
	(65, 'Taller', 0),
	(66, 'Taller - Ingreso Vehiculo', 0),
	(67, 'Taller - Mecanico', 0),
	(68, 'Ordenes de Trabajo Crear Marca', 0),
	(69, 'Ordenes de Trabajo', 0),
	(70, 'Ordenes de Trabajo Tecnicos', 0),
	(71, 'Ordenes de Trabajo Modelos', 0),
	(72, 'Ordenes de Trabajo Tipo de Equ', 0),
	(73, 'Ordenes de Trabajo Colores', 0),
	(74, 'Ordenes de Trabajo Crear ', 0),
	(75, 'Tienda Web', 0),
	(76, 'Tienda Web Articulos', 0),
	(77, 'Tienda Web Inicio', 0),
	(78, 'Tienda Web Nosotros', 0),
	(79, 'Tienda Web Servicios', 0),
	(80, 'Tienda Web Contactanos', 0),
	(81, 'Tecnico', 0),
	(82, 'Tecnico Instalaciones x User', 0),
	(83, 'Tecnico Instalaciones General', 0),
	(84, 'Tecnico Reparaciones x User', 0),
	(85, 'Tecnico Reparaciones General', 0),
	(86, 'Ventas Servicios', 0),
	(87, 'Acceso - Tecnicos', 1),
	(88, 'Acceso - Cobradores', 1),
	(89, 'Nomina', 1),
	(90, 'Nomina - Empleados', 1),
	(91, 'Nomina - Pagos', 1),
	(92, 'Nomina - Prestaciones', 1),
	(93, 'Nomina - Vacaciones', 1),
	(94, 'Parqueo', 0),
	(95, 'Parqueo Tarifas', 0),
	(96, 'Parqueo Info Ticke-Fac', 0),
	(97, 'Parqueo Operaciones', 0),
	(98, 'Parqueo Rpt Tickets', 0),
	(99, 'Parqueo Graficas', 0);

-- Volcando estructura para tabla dbsuper365.persona
CREATE TABLE IF NOT EXISTS `persona` (
  `idpersona` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_persona` varchar(20) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `nombre_comercial` varchar(250) NOT NULL,
  `tipo_documento` varchar(20) DEFAULT NULL,
  `num_documento` varchar(20) DEFAULT NULL,
  `direccion` varchar(250) DEFAULT NULL,
  `direccion_comercial` varchar(250) DEFAULT NULL,
  `telefono` varchar(250) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `tipo_cliente` varchar(35) NOT NULL,
  `codigo_cliente` varchar(35) NOT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT '1',
  `fechaCreacion` datetime NOT NULL,
  `fechaUpdate` datetime NOT NULL,
  `noCasa` varchar(50) NOT NULL DEFAULT '0',
  `zona` varchar(50) NOT NULL DEFAULT '0',
  `coloniaCaserio` varchar(50) NOT NULL DEFAULT '0',
  `ubicacion_maps` text NOT NULL,
  `firma` text NOT NULL,
  `trabajo` varchar(250) NOT NULL,
  `idfiador` varchar(50) NOT NULL,
  `idsector` int(11) NOT NULL DEFAULT '0',
  `idruta` int(11) NOT NULL DEFAULT '0',
  `descuento_cliente` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idusuario_update` int(11) NOT NULL DEFAULT '0',
  `idsucursal` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idpersona`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.persona: ~1 rows (aproximadamente)
INSERT INTO `persona` (`idpersona`, `tipo_persona`, `nombre`, `nombre_comercial`, `tipo_documento`, `num_documento`, `direccion`, `direccion_comercial`, `telefono`, `email`, `tipo_cliente`, `codigo_cliente`, `condicion`, `fechaCreacion`, `fechaUpdate`, `noCasa`, `zona`, `coloniaCaserio`, `ubicacion_maps`, `firma`, `trabajo`, `idfiador`, `idsector`, `idruta`, `descuento_cliente`, `idusuario`, `idusuario_update`, `idsucursal`) VALUES
	(1, 'Cliente', 'CONSUMIDOR FINAL', '', 'NIT', 'CF', 'CIUDAD', NULL, '0', 'soporte@gmail.com', 'PUBLICO', 'COD11', 1, '2024-12-17 13:17:57', '0000-00-00 00:00:00', '0', '0', '0', '14.6349,-90.5069', 'firma_1_1748885354.png', '', '', 0, 0, 0.000000, 0, 0, 0);

-- Volcando estructura para tabla dbsuper365.presentacion_precios
CREATE TABLE IF NOT EXISTS `presentacion_precios` (
  `idpresentacion` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_presentacion1` varchar(50) NOT NULL DEFAULT 'NA',
  `nombre_presentacion2` varchar(50) NOT NULL DEFAULT 'NA',
  `nombre_presentacion3` varchar(50) NOT NULL DEFAULT 'NA',
  `nombre_presentacion4` varchar(50) NOT NULL DEFAULT 'NA',
  `nombre_presentacion5` varchar(50) NOT NULL DEFAULT 'NA',
  `nombre_presentacion6` varchar(50) NOT NULL DEFAULT 'NA',
  `nombre_presentacion7` varchar(50) NOT NULL DEFAULT 'NA',
  `nombre_presentacion8` varchar(50) NOT NULL DEFAULT 'NA',
  `nombre_presentacion9` varchar(50) NOT NULL DEFAULT 'NA',
  `nombre_presentacion10` varchar(50) NOT NULL DEFAULT 'NA',
  `nombre_presentacion11` varchar(50) NOT NULL DEFAULT 'NA',
  `nombre_presentacion12` varchar(50) NOT NULL DEFAULT 'NA',
  `nombre_presentacion13` varchar(50) NOT NULL DEFAULT 'NA',
  `nombre_presentacion14` varchar(50) NOT NULL DEFAULT 'NA',
  `nombre_presentacion15` varchar(50) NOT NULL DEFAULT 'NA',
  `nombre_presentacion16` varchar(50) NOT NULL DEFAULT 'NA',
  `nombre_presentacion17` varchar(50) NOT NULL DEFAULT 'NA',
  `nombre_presentacion18` varchar(50) NOT NULL DEFAULT 'NA',
  `nombre_presentacion19` varchar(50) NOT NULL DEFAULT 'NA',
  `nombre_presentacion20` varchar(50) NOT NULL DEFAULT 'NA',
  `fecha_creacion` datetime NOT NULL,
  `fecha_actualizacion` datetime NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idusuario_update` int(11) NOT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idpresentacion`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.presentacion_precios: ~1 rows (aproximadamente)
INSERT INTO `presentacion_precios` (`idpresentacion`, `nombre_presentacion1`, `nombre_presentacion2`, `nombre_presentacion3`, `nombre_presentacion4`, `nombre_presentacion5`, `nombre_presentacion6`, `nombre_presentacion7`, `nombre_presentacion8`, `nombre_presentacion9`, `nombre_presentacion10`, `nombre_presentacion11`, `nombre_presentacion12`, `nombre_presentacion13`, `nombre_presentacion14`, `nombre_presentacion15`, `nombre_presentacion16`, `nombre_presentacion17`, `nombre_presentacion18`, `nombre_presentacion19`, `nombre_presentacion20`, `fecha_creacion`, `fecha_actualizacion`, `idusuario`, `idusuario_update`, `condicion`) VALUES
	(1, 'UNIDAD', '2PACK', 'caja', 'TONEL', 'BLOQUE', 'PAQUETES', 'Botella', 'MANGAS', 'CIENTO', 'DOCENA', 'Blister', 'KILO', 'MEDIA DOCENA', 'LIBRA', 'MEDIO KITAL', 'ROLLO', 'CARTON', 'paquete', 'ARROBA', 'CUBETAZO', '2025-08-18 17:35:00', '2026-05-14 16:37:29', 34, 34, 1);

-- Volcando estructura para procedimiento dbsuper365.procesar_traslado_articuloEntrada
DELIMITER //
CREATE PROCEDURE `procesar_traslado_articuloEntrada`(
    IN p_idtraladosucursal_entradanew INT,
    IN p_idarticulo INT,
    IN p_cantidad_total DECIMAL(20,6),
    IN p_idsucursal INT,
    IN p_idusuario INT,
    IN p_fechavencimiento DATE,
    IN p_idsucursalorigen INT,
    IN p_idsucursaldestino INT
)
BEGIN
    DECLARE v_cantidad_restante DECIMAL(20,6);
    DECLARE v_idop INT;
    DECLARE v_saldo_actual DECIMAL(20,6);
    DECLARE v_fecha_vencimiento VARCHAR(50);
    DECLARE v_idingreso INT;
    DECLARE v_idtrasladoentrada INT;
    DECLARE done INT DEFAULT FALSE;

    DECLARE cur CURSOR FOR
        SELECT idoperaciones_compras_ventas, saldo, fecha_vencimiento, idingreso, idtraladosucursal_entrada
        FROM operaciones_compras_ventas
        WHERE idarticulo = p_idarticulo
          AND idsucursal = p_idsucursal
          AND estado = 'Aceptado'
          AND saldo > 0
          AND (idingreso > 0 OR idtraladosucursal_entrada > 0)
        ORDER BY STR_TO_DATE(fecha_vencimiento, '%Y-%m-%d') ASC;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    SET v_cantidad_restante = p_cantidad_total;

    OPEN cur;
    read_loop: LOOP
        FETCH cur INTO v_idop, v_saldo_actual, v_fecha_vencimiento, v_idingreso, v_idtrasladoentrada;
        IF done THEN
            LEAVE read_loop;
        END IF;

        IF v_cantidad_restante <= 0 THEN
            LEAVE read_loop;
        END IF;

        IF v_cantidad_restante >= v_saldo_actual THEN
            -- Consumir todo el saldo
            UPDATE operaciones_compras_ventas
            SET saldo = 0, estado = 'TERMINADO'
            WHERE idoperaciones_compras_ventas = v_idop;

            INSERT INTO detalle_traslado_sucursal_entrada_fechasvencimiento(
                idtraladosucursal_entrada, idarticulo,
                fecha_vencimiento, cantidad, cantidad2, idsucursal, idusuario, 
					 idoperaciones_compras_ventas,idsucursalorigen,idsucursaldestino)
            VALUES (
                p_idtraladosucursal_entradanew, p_idarticulo,
                p_fechavencimiento, v_saldo_actual, v_saldo_actual,
                p_idsucursal, p_idusuario, v_idop, p_idsucursalorigen, p_idsucursaldestino);

            SET v_cantidad_restante = v_cantidad_restante - v_saldo_actual;
        ELSE
            -- Consumir parcialmente
            UPDATE operaciones_compras_ventas
            SET saldo = saldo - v_cantidad_restante
            WHERE idoperaciones_compras_ventas = v_idop;

            INSERT INTO detalle_traslado_sucursal_entrada_fechasvencimiento(
                idtraladosucursal_entrada, idarticulo,
                fecha_vencimiento, cantidad, cantidad2, idsucursal, idusuario, 
					 idoperaciones_compras_ventas, idsucursalorigen,idsucursaldestino)
            VALUES (
                p_idtraladosucursal_entradanew, p_idarticulo,
                p_fechavencimiento, v_cantidad_restante, v_cantidad_restante,
                p_idsucursal, p_idusuario, v_idop, p_idsucursalorigen, p_idsucursaldestino);

            SET v_cantidad_restante = 0;
        END IF;
    END LOOP;
    CLOSE cur;
END//
DELIMITER ;

-- Volcando estructura para procedimiento dbsuper365.procesar_venta_articulo
DELIMITER //
CREATE PROCEDURE `procesar_venta_articulo`(
    IN p_idventa INT,
    IN p_idarticulo INT,
    IN p_cantidad_total DECIMAL(20,6),
    IN p_idsucursal INT,
    IN p_idusuario INT
)
BEGIN
    DECLARE v_cantidad_restante DECIMAL(20,6);
    DECLARE v_idop INT;
    DECLARE v_saldo_actual DECIMAL(20,6);
    DECLARE v_fecha_vencimiento VARCHAR(50);
    DECLARE v_idingreso INT;
    DECLARE v_idtrasladoentrada INT;
    DECLARE done INT DEFAULT FALSE;

    DECLARE cur CURSOR FOR
        SELECT idoperaciones_compras_ventas, saldo, fecha_vencimiento, idingreso, idtraladosucursal_entrada
        FROM operaciones_compras_ventas
        WHERE idarticulo = p_idarticulo
          AND idsucursal = p_idsucursal
          AND estado = 'Aceptado'
          AND saldo > 0
          AND (idingreso > 0 OR idtraladosucursal_entrada > 0)
        ORDER BY STR_TO_DATE(fecha_vencimiento, '%Y-%m-%d') ASC;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    SET v_cantidad_restante = p_cantidad_total;

    OPEN cur;
    read_loop: LOOP
        FETCH cur INTO v_idop, v_saldo_actual, v_fecha_vencimiento, v_idingreso, v_idtrasladoentrada;
        IF done THEN
            LEAVE read_loop;
        END IF;

        IF v_cantidad_restante <= 0 THEN
            LEAVE read_loop;
        END IF;

        IF v_cantidad_restante >= v_saldo_actual THEN
            -- Consumir todo el saldo
            UPDATE operaciones_compras_ventas
            SET saldo = 0, estado = 'TERMINADO'
            WHERE idoperaciones_compras_ventas = v_idop;

            INSERT INTO detalle_venta_fechasvencimiento(
                idventa, idarticulo, idingreso, idtraladosucursal_entrada,
                fecha_vencimiento, cantidad, cantidad2, idsucursal, idusuario, idoperaciones_compras_ventas)
            VALUES (
                p_idventa, p_idarticulo, v_idingreso, v_idtrasladoentrada,
                v_fecha_vencimiento, v_saldo_actual, v_saldo_actual,
                p_idsucursal, p_idusuario, v_idop);

            SET v_cantidad_restante = v_cantidad_restante - v_saldo_actual;
        ELSE
            -- Consumir parcialmente
            UPDATE operaciones_compras_ventas
            SET saldo = saldo - v_cantidad_restante
            WHERE idoperaciones_compras_ventas = v_idop;

            INSERT INTO detalle_venta_fechasvencimiento(
                idventa, idarticulo, idingreso, idtraladosucursal_entrada,
                fecha_vencimiento, cantidad, cantidad2, idsucursal, idusuario, idoperaciones_compras_ventas)
            VALUES (
                p_idventa, p_idarticulo, v_idingreso, v_idtrasladoentrada,
                v_fecha_vencimiento, v_cantidad_restante, v_cantidad_restante,
                p_idsucursal, p_idusuario, v_idop);

            SET v_cantidad_restante = 0;
        END IF;
    END LOOP;
    CLOSE cur;
END//
DELIMITER ;

-- Volcando estructura para tabla dbsuper365.produccion
CREATE TABLE IF NOT EXISTS `produccion` (
  `idproduccion` int(11) NOT NULL AUTO_INCREMENT,
  `idproducto` int(11) NOT NULL,
  `cantidad_materia` varchar(20) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `precio_compra` decimal(11,2) NOT NULL,
  `ganacia_producto` decimal(11,2) NOT NULL,
  `precio_venta` decimal(11,2) NOT NULL,
  `sub_total` decimal(11,2) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `tipo_ingreso_producion` int(11) NOT NULL,
  `fecha_creacion` varchar(50) NOT NULL DEFAULT '0',
  `idusuario_update` int(11) NOT NULL DEFAULT '0',
  `fecha_modificacion` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idproduccion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.produccion: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.registro
CREATE TABLE IF NOT EXISTS `registro` (
  `id` int(11) NOT NULL,
  `foto` varchar(400) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `codigo` varchar(100) DEFAULT NULL,
  `accion` int(11) DEFAULT NULL,
  `codsubida` varchar(100) DEFAULT NULL,
  `tipoingreso` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codsubida` (`codsubida`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.registro: 0 rows
/*!40000 ALTER TABLE `registro` DISABLE KEYS */;
/*!40000 ALTER TABLE `registro` ENABLE KEYS */;

-- Volcando estructura para tabla dbsuper365.registro_app
CREATE TABLE IF NOT EXISTS `registro_app` (
  `idregistro_app` int(11) NOT NULL AUTO_INCREMENT,
  `foto` varchar(50) NOT NULL DEFAULT '0',
  `fecha` datetime NOT NULL,
  `codigo` varchar(10) NOT NULL DEFAULT '0',
  `condicion` tinyint(4) NOT NULL DEFAULT '1',
  `fecha_creacion` datetime NOT NULL,
  `tipo_salida_entrada` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idregistro_app`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.registro_app: ~0 rows (aproximadamente)

-- Volcando estructura para procedimiento dbsuper365.restaurar_saldos_por_venta
DELIMITER //
CREATE PROCEDURE `restaurar_saldos_por_venta`(IN p_idventa INT)
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE v_idop INT;
    DECLARE v_cantidad DECIMAL(20,6);
    DECLARE v_saldo_actual DECIMAL(20,6);
    DECLARE v_estado_actual VARCHAR(50);

    DECLARE cur CURSOR FOR
        SELECT idoperaciones_compras_ventas, cantidad
        FROM detalle_venta_fechasvencimiento
        WHERE idventa = p_idventa;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO v_idop, v_cantidad;
        IF done THEN
            LEAVE read_loop;
        END IF;

        -- Obtener saldo y estado actual
        SELECT saldo, estado INTO v_saldo_actual, v_estado_actual
        FROM operaciones_compras_ventas
        WHERE idoperaciones_compras_ventas = v_idop;

        -- Lógica principal
        IF v_saldo_actual <= 0 AND v_estado_actual = 'TERMINADO' THEN
            -- Reemplazar saldo por cantidad y cambiar estado a Aceptado
            UPDATE operaciones_compras_ventas
            SET saldo = v_cantidad,
                estado = 'Aceptado'
            WHERE idoperaciones_compras_ventas = v_idop;

        ELSEIF v_saldo_actual > 0 AND v_estado_actual = 'Aceptado' THEN
            -- Sumar cantidad al saldo y dejar estado como está
            UPDATE operaciones_compras_ventas
            SET saldo = saldo + v_cantidad
            WHERE idoperaciones_compras_ventas = v_idop;
        END IF;

    END LOOP;

    CLOSE cur;
END//
DELIMITER ;

-- Volcando estructura para tabla dbsuper365.ruta_visita
CREATE TABLE IF NOT EXISTS `ruta_visita` (
  `idruta` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(256) DEFAULT NULL,
  `condicion` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`idruta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.ruta_visita: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.sector
CREATE TABLE IF NOT EXISTS `sector` (
  `idsector` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(256) DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idsector`) USING BTREE,
  KEY `idx_categoria_idcategoria` (`idsector`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.sector: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.seguimiento
CREATE TABLE IF NOT EXISTS `seguimiento` (
  `idseguimiento` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) NOT NULL DEFAULT '0',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tipo` varchar(50) NOT NULL DEFAULT 'LLAMADA',
  `notas` text NOT NULL,
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idsucursal` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idseguimiento`),
  KEY `fk_seguimiento_cliente` (`idcliente`),
  CONSTRAINT `fk_seguimiento_cliente` FOREIGN KEY (`idcliente`) REFERENCES `persona` (`idpersona`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.seguimiento: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.session_usuarios
CREATE TABLE IF NOT EXISTS `session_usuarios` (
  `idsession_usuarios` int(11) NOT NULL AUTO_INCREMENT,
  `idsucursal` int(11) NOT NULL DEFAULT '0',
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `fechaHora` datetime NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idsession_usuarios`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.session_usuarios: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.solicitud_productos
CREATE TABLE IF NOT EXISTS `solicitud_productos` (
  `idsolicitud_productos` int(11) NOT NULL AUTO_INCREMENT,
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idsucursal` int(11) NOT NULL DEFAULT '0',
  `condicion` tinyint(4) NOT NULL DEFAULT '0',
  `fecha_creacion` datetime NOT NULL,
  `fecha_modificacion` datetime NOT NULL,
  `estado` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idsolicitud_productos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.solicitud_productos: ~0 rows (aproximadamente)

-- Volcando estructura para procedimiento dbsuper365.sp_actualizar_stock_y_registrar_operacion_ingreso
DELIMITER //
CREATE PROCEDURE `sp_actualizar_stock_y_registrar_operacion_ingreso`(
    IN p_idarticulo INT,
    IN p_totalcantidadpresentacion DECIMAL(10,2),
    IN p_precio_compra DECIMAL(10,2),
    IN p_precio_venta DECIMAL(10,2),
    IN p_precio_ventaNocturno DECIMAL(10,2),
    IN p_precio_rango1_Mecanico DECIMAL(10,2),
    IN p_precio_rango1_Distribuidor DECIMAL(10,2),
    IN p_precio_rango1_Mayorista DECIMAL(10,2),
    IN p_precio_rango2_MecanicoDos DECIMAL(10,2),
    IN p_precio_rango2_DistribuidorDos DECIMAL(10,2),
    IN p_precio_rango2_MayoristaDos DECIMAL(10,2),
    IN p_precio_rango3_MecanicoTres DECIMAL(10,2),
    IN p_precio_rango3_DistribuidorTres DECIMAL(10,2),
    IN p_precio_rango3_MayoristaTres DECIMAL(10,2),
    IN p_precio_unidad DECIMAL(10,2),
    IN p_precio_blister DECIMAL(10,2),
    IN p_precio_caja DECIMAL(10,2),
    IN p_precio_fardo DECIMAL(10,2),
    IN p_precio_sacos DECIMAL(10,2),
    IN p_precio_paquete DECIMAL(10,2),
    IN p_precio_07 DECIMAL(10,2),
    IN p_precio_08 DECIMAL(10,2),
    IN p_precio_09 DECIMAL(10,2),
    IN p_precio_10 DECIMAL(10,2),
    IN p_precio_11 DECIMAL(10,2),
    IN p_precio_12 DECIMAL(10,2),
    IN p_precio_13 DECIMAL(10,2),
    IN p_precio_14 DECIMAL(10,2),
    IN p_precio_15 DECIMAL(10,2),
    IN p_precio_16 DECIMAL(10,2),
    IN p_precio_17 DECIMAL(10,2),
    IN p_precio_18 DECIMAL(10,2),
    IN p_precio_19 DECIMAL(10,2),
    IN p_precio_20 DECIMAL(10,2),
    IN p_stockinven DECIMAL(10,2),
    IN p_idingreso INT,
    IN p_fechaHora DATETIME,
    IN p_fechavencimiento DATE,
    IN p_idusuario INT,
    IN p_idsucursal INT
)
BEGIN
    DECLARE v_tipo_producto VARCHAR(50);
    DECLARE v_stocksucursal_anterior DECIMAL(10,2);
    DECLARE v_pc_anterior DECIMAL(10,2);
    DECLARE v_pc_nuevo DECIMAL(10,2);

    -- Verificar tipo de producto
    SELECT tipo_producto INTO v_tipo_producto
    FROM articulo
    WHERE idarticulo = p_idarticulo;

    IF v_tipo_producto != 'Servicios' AND v_tipo_producto != 'Combos' THEN

        -- Obtener stock anterior y precio anterior
        SELECT stocksucursal, precio_compra INTO v_stocksucursal_anterior, v_pc_anterior
        FROM articuloxsucursal
        WHERE idarticulo = p_idarticulo AND idsucursal = p_idsucursal;

        -- Calcular nuevo precio ponderado
        IF (p_totalcantidadpresentacion + p_stockinven) != 0 THEN
            SET v_pc_nuevo = (
                (v_stocksucursal_anterior * v_pc_anterior) + 
                (p_totalcantidadpresentacion * p_precio_compra)
            ) / (p_totalcantidadpresentacion + p_stockinven);
        ELSE
            SET v_pc_nuevo = p_precio_compra;
        END IF;

        -- Actualizar articuloxsucursal
        UPDATE articuloxsucursal SET 
            stocksucursal = stocksucursal + p_totalcantidadpresentacion,
            precio_compra = v_pc_nuevo,
            precio_venta = p_precio_venta,
            precio_rango1_Mecanico = p_precio_rango1_Mecanico,
            precio_rango1_Distribuidor = p_precio_rango1_Distribuidor,
            precio_rango1_Mayorista = p_precio_rango1_Mayorista,
            precio_rango2_MecanicoDos = p_precio_rango2_MecanicoDos,
            precio_rango2_DistribuidorDos = p_precio_rango2_DistribuidorDos,
            precio_rango2_MayoristaDos = p_precio_rango2_MayoristaDos,
            precio_rango3_MecanicoTres = p_precio_rango3_MecanicoTres,
            precio_rango3_DistribuidorTres = p_precio_rango3_DistribuidorTres,
            precio_rango3_MayoristaTres = p_precio_rango3_MayoristaTres,
            precio_unidad = p_precio_unidad,
            precio_blister = p_precio_blister,
            precio_caja = p_precio_caja,
            precio_fardo = p_precio_fardo,
            precio_sacos = p_precio_sacos,
            precio_paquete = p_precio_paquete,
            precio_07 = p_precio_07,
            precio_08 = p_precio_08,
            precio_09 = p_precio_09,
            precio_10 = p_precio_10,
            precio_11 = p_precio_11,
            precio_12 = p_precio_12,
            precio_13 = p_precio_13,
            precio_14 = p_precio_14,
            precio_15 = p_precio_15,
            precio_16 = p_precio_16,
            precio_17 = p_precio_17,
            precio_18 = p_precio_18,
            precio_19 = p_precio_19,
            precio_20 = p_precio_20
        WHERE idarticulo = p_idarticulo AND idsucursal = p_idsucursal;

    END IF;

    -- Registrar en operaciones_compras_ventas
    INSERT INTO operaciones_compras_ventas (
        idingreso, idventa, idtraladosucursal, idtraladosucursal_entrada,
        iddevolucion, cantidad_compras, cantidad_ventas, cantidad_entrada,
        cantidad_devolucion, cantidad_salida, stock_inventario, fecha_horaCreacion,
        idarticulo, idusuario, idsucursal, estado, fecha_vencimiento, saldo
    ) VALUES (
        p_idingreso, 0, 0, 0,
        0, p_totalcantidadpresentacion, 0, 0,
        0, 0, p_stockinven, p_fechaHora,
        p_idarticulo, p_idusuario, p_idsucursal, 'Aceptado', p_fechavencimiento, p_totalcantidadpresentacion
    );
END//
DELIMITER ;

-- Volcando estructura para procedimiento dbsuper365.sp_guardar_cliente
DELIMITER //
CREATE PROCEDURE `sp_guardar_cliente`(
    IN p_idcliente INT,
    IN p_nombre_cliente VARCHAR(100),
    IN p_tipo_documento_cliente VARCHAR(20),
    IN p_nit VARCHAR(20),
    IN p_direccion_cliente VARCHAR(255),
    IN p_telefono_cliente VARCHAR(20),
    IN p_correo_cliente VARCHAR(100),
    IN p_fecha_creacion DATETIME,
    IN p_idsucursal INT,
    OUT p_residcliente INT
)
BEGIN
    DECLARE v_codigo_cliente VARCHAR(20);
    DECLARE v_correlativo INT;
    DECLARE v_existente_codigo_cliente VARCHAR(20);

    IF p_idcliente = 0 THEN
        -- Generar nuevo correlativo
        UPDATE add_correlativo SET codigo_cliente = codigo_cliente + 1 WHERE idsucursal = p_idsucursal;

        SELECT codigo_cliente INTO v_correlativo 
        FROM add_correlativo 
        WHERE idsucursal = p_idsucursal;

        SET v_codigo_cliente = CONCAT('COD', v_correlativo);

        -- Insertar nuevo cliente
        INSERT INTO persona (
            tipo_persona, nombre, tipo_documento, num_documento,
            direccion, telefono, email, tipo_cliente, codigo_cliente, fechaCreacion
        ) VALUES (
            'Proveedor', p_nombre_cliente, p_tipo_documento_cliente, p_nit,
            p_direccion_cliente, p_telefono_cliente, p_correo_cliente,
            'PUBLICO', v_codigo_cliente, p_fecha_creacion
        );

        SET p_residcliente = LAST_INSERT_ID();

    ELSE
        -- Revisar si ya tiene código
        SELECT codigo_cliente INTO v_existente_codigo_cliente
        FROM persona
        WHERE idpersona = p_idcliente;

        IF v_existente_codigo_cliente IS NULL OR v_existente_codigo_cliente = '' OR v_existente_codigo_cliente = '0' THEN
            -- Generar nuevo código si no tiene
            UPDATE add_correlativo SET codigo_cliente = codigo_cliente + 1 WHERE idsucursal = p_idsucursal;

            SELECT codigo_cliente INTO v_correlativo 
            FROM add_correlativo 
            WHERE idsucursal = p_idsucursal;

            SET v_codigo_cliente = CONCAT('COD', v_correlativo);

            UPDATE persona 
            SET codigo_cliente = v_codigo_cliente 
            WHERE idpersona = p_idcliente;
        END IF;

        -- Actualizar datos
        UPDATE persona SET
            direccion = p_direccion_cliente,
            telefono = p_telefono_cliente,
            email = p_correo_cliente,
            tipo_documento = p_tipo_documento_cliente,
            nombre = p_nombre_cliente
        WHERE idpersona = p_idcliente;

        SET p_residcliente = p_idcliente;
    END IF;
END//
DELIMITER ;

-- Volcando estructura para tabla dbsuper365.subcategoria
CREATE TABLE IF NOT EXISTS `subcategoria` (
  `idsubcategoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(256) DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idsubcategoria`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.subcategoria: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.sucursal
CREATE TABLE IF NOT EXISTS `sucursal` (
  `idsucursal` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  `nombre_fel` varchar(250) NOT NULL,
  `nombre_comercial` varchar(250) NOT NULL,
  `direccion` varchar(250) NOT NULL,
  `direccion_fiscal` varchar(250) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `nit` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `imagen` varchar(250) NOT NULL,
  `condicion` tinyint(1) NOT NULL,
  `num_establecimiento` int(11) DEFAULT '0',
  `certificador` varchar(50) DEFAULT NULL,
  `clave_ordenes` varchar(50) DEFAULT NULL,
  `clave_ingresos` varchar(50) DEFAULT NULL,
  `clave_ventas` varchar(50) DEFAULT NULL,
  `fecha_creacion` varchar(50) DEFAULT NULL,
  `calculo_descuento` varchar(50) DEFAULT 'QUETZALES',
  PRIMARY KEY (`idsucursal`),
  KEY `idx_sucursal_idsucursal` (`idsucursal`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.sucursal: ~1 rows (aproximadamente)
INSERT INTO `sucursal` (`idsucursal`, `nombre`, `nombre_fel`, `nombre_comercial`, `direccion`, `direccion_fiscal`, `telefono`, `nit`, `email`, `imagen`, `condicion`, `num_establecimiento`, `certificador`, `clave_ordenes`, `clave_ingresos`, `clave_ventas`, `fecha_creacion`, `calculo_descuento`) VALUES
	(4, 'Super365', 'Super365', 'Super365', '7ma. Av. 10-93 zona 5 Huehuetenango', '7ma. Av. 10-93 zona 5 Huehuetenango', '56327773', '0', 'mateo_tzun_15@hotmail.com', '1590204245.jpg', 1, 0, 'GUATEFACTURAS', '12345', '12345', '12345', '2024-12-23 16:36:30', 'QUETZALES');

-- Volcando estructura para tabla dbsuper365.sucursal_usuario
CREATE TABLE IF NOT EXISTS `sucursal_usuario` (
  `idsucursal_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idsucursal` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idsucursal_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.sucursal_usuario: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.tarea
CREATE TABLE IF NOT EXISTS `tarea` (
  `idtarea` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text,
  `fecha_limite` date NOT NULL,
  `completado` tinyint(1) DEFAULT '0',
  `prioridad` enum('Alta','Media','Baja') DEFAULT 'Media',
  `idusuario` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idtarea`),
  KEY `idcliente` (`idcliente`),
  CONSTRAINT `tarea_ibfk_1` FOREIGN KEY (`idcliente`) REFERENCES `persona` (`idpersona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.tarea: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.tariasprecios
CREATE TABLE IF NOT EXISTS `tariasprecios` (
  `id_tariasprecios` int(11) NOT NULL AUTO_INCREMENT,
  `precio_fraccion_carro` decimal(20,6) NOT NULL,
  `precio_hora_carro` decimal(20,6) NOT NULL,
  `tarifa_dia_carro` decimal(20,6) NOT NULL,
  `tarifa_noche_carro` decimal(20,6) NOT NULL,
  `tarifa_evento_carro` decimal(20,6) NOT NULL,
  `precio_fraccion_moto` decimal(20,6) NOT NULL,
  `precio_hora_moto` decimal(20,6) NOT NULL,
  `tarifa_dia_moto` decimal(20,6) NOT NULL,
  `tarifa_noche_moto` decimal(20,6) NOT NULL,
  `tarifa_evento_moto` decimal(20,6) NOT NULL,
  `precio_fraccion_camion` decimal(20,6) NOT NULL,
  `precio_hora_camion` decimal(20,6) NOT NULL,
  `tarifa_dia_camion` decimal(20,6) NOT NULL,
  `tarifa_noche_camion` decimal(20,6) NOT NULL,
  `tarifa_evento_camion` decimal(20,6) NOT NULL,
  `no_correlativo_ticket` decimal(20,6) NOT NULL,
  `valor_ticket_extraviado` decimal(20,6) NOT NULL,
  `tiempo_gracia_ticket` decimal(20,6) NOT NULL,
  `cantidad_parqueos` decimal(20,6) NOT NULL,
  `idsucursal` int(11) NOT NULL DEFAULT '0',
  `idsucursal_update` int(11) NOT NULL DEFAULT '0',
  `fecha_update` datetime NOT NULL,
  `condiocion` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_tariasprecios`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.tariasprecios: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.tarifas_precios
CREATE TABLE IF NOT EXISTS `tarifas_precios` (
  `id_tariasprecios` int(11) NOT NULL AUTO_INCREMENT,
  `precio_fraccion_carro` decimal(10,0) NOT NULL DEFAULT '0',
  `precio_hora_carro` decimal(10,0) NOT NULL DEFAULT '0',
  `tarifa_dia_carro` decimal(10,0) NOT NULL DEFAULT '0',
  `tarifa_noche_carro` decimal(10,0) NOT NULL DEFAULT '0',
  `tarifa_evento_carro` decimal(10,0) NOT NULL DEFAULT '0',
  `precio_fraccion_moto` decimal(10,0) NOT NULL DEFAULT '0',
  `precio_hora_moto` decimal(10,0) NOT NULL DEFAULT '0',
  `tarifa_dia_moto` decimal(10,0) NOT NULL DEFAULT '0',
  `tarifa_noche_moto` decimal(10,0) NOT NULL DEFAULT '0',
  `tarifa_evento_moto` decimal(10,0) NOT NULL DEFAULT '0',
  `precio_fraccion_camion` decimal(10,0) NOT NULL DEFAULT '0',
  `precio_hora_camion` decimal(10,0) NOT NULL DEFAULT '0',
  `tarifa_dia_camion` decimal(10,0) NOT NULL DEFAULT '0',
  `tarifa_noche_camion` decimal(10,0) NOT NULL DEFAULT '0',
  `tarifa_evento_camion` decimal(10,0) NOT NULL DEFAULT '0',
  `no_correlativo_ticket` decimal(10,0) NOT NULL DEFAULT '0',
  `valor_ticket_extraviado` decimal(10,0) NOT NULL DEFAULT '0',
  `tiempo_gracia_ticket` int(11) NOT NULL DEFAULT '0',
  `cantidad_parqueos` int(11) NOT NULL DEFAULT '0',
  `condicion` tinyint(4) NOT NULL DEFAULT '0',
  `idusuario_update` int(11) NOT NULL DEFAULT '0',
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `fecha_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `idsucursal` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_tariasprecios`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.tarifas_precios: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.tecnico
CREATE TABLE IF NOT EXISTS `tecnico` (
  `idtecnico` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `telefono` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  `comision` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `fecha_creacion` datetime NOT NULL,
  PRIMARY KEY (`idtecnico`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.tecnico: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.tienda_web_inicio
CREATE TABLE IF NOT EXISTS `tienda_web_inicio` (
  `idinicio` int(11) NOT NULL AUTO_INCREMENT,
  `titulo_1` varchar(50) NOT NULL DEFAULT '0',
  `sub_titulo_1` varchar(50) NOT NULL DEFAULT '0',
  `descripcion_titulo_1` varchar(400) NOT NULL DEFAULT '0',
  `imagen_1` varchar(50) NOT NULL DEFAULT '0',
  `titulo_2` varchar(50) NOT NULL DEFAULT '0',
  `sub_titulo_2` varchar(50) NOT NULL DEFAULT '0',
  `descripcion_titulo_2` varchar(400) NOT NULL DEFAULT '0',
  `imagen_2` varchar(50) NOT NULL DEFAULT '0',
  `titulo_3` varchar(50) NOT NULL DEFAULT '0',
  `sub_titulo_3` varchar(50) NOT NULL DEFAULT '0',
  `descripcion_titulo_3` varchar(400) NOT NULL DEFAULT '0',
  `imagen_3` varchar(50) NOT NULL DEFAULT '0',
  `condicion` tinyint(4) NOT NULL DEFAULT '1',
  `idusuario_update` int(11) NOT NULL DEFAULT '34',
  `idsucursal_update` int(11) NOT NULL DEFAULT '4',
  `fecha_update` datetime NOT NULL,
  PRIMARY KEY (`idinicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.tienda_web_inicio: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.tienda_web_nosotros
CREATE TABLE IF NOT EXISTS `tienda_web_nosotros` (
  `idnosotros` int(11) NOT NULL AUTO_INCREMENT,
  `historia_empresa` text NOT NULL,
  `imagen_nosotros` varchar(50) NOT NULL DEFAULT '',
  `mision_nosotros` text NOT NULL,
  `vision_nosotros` text NOT NULL,
  `diferencia_nosotros` text NOT NULL,
  `idusuario_update` int(11) NOT NULL DEFAULT '0',
  `fecha_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `idsucursal_update` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idnosotros`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.tienda_web_nosotros: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.tienda_web_servicios
CREATE TABLE IF NOT EXISTS `tienda_web_servicios` (
  `idservicios` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL DEFAULT '0',
  `Columna 3` varchar(250) NOT NULL DEFAULT '0',
  `tipo` varchar(20) NOT NULL DEFAULT '0',
  `Columna 5` varchar(20) NOT NULL DEFAULT '0',
  `descripcion_servicio` text NOT NULL,
  `imagen_servicio` varchar(50) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `idusuario_create` int(11) NOT NULL,
  `idsucursal_create` int(11) NOT NULL,
  `idusuario_update` int(11) NOT NULL,
  `fecha_update` datetime NOT NULL,
  `idsucursal_update` datetime NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  PRIMARY KEY (`idservicios`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.tienda_web_servicios: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.tipo_equipo
CREATE TABLE IF NOT EXISTS `tipo_equipo` (
  `idtipo_equipo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(256) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  PRIMARY KEY (`idtipo_equipo`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.tipo_equipo: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.transporte
CREATE TABLE IF NOT EXISTS `transporte` (
  `idtransporte` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `telefono` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `placa` varchar(200) NOT NULL,
  `modelo` varchar(200) NOT NULL,
  `marca` varchar(200) NOT NULL,
  `color` varchar(200) NOT NULL,
  `tipo_transporte` varchar(200) NOT NULL,
  `capacidad` varchar(200) NOT NULL,
  PRIMARY KEY (`idtransporte`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.transporte: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.traslado_sucursal
CREATE TABLE IF NOT EXISTS `traslado_sucursal` (
  `idtraladosucursal` int(11) NOT NULL AUTO_INCREMENT,
  `idsucursaldestino` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idsucursalorigen` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `estado` varchar(100) NOT NULL,
  `descripcion_salida_producto` varchar(500) NOT NULL,
  `total_venta` decimal(11,2) NOT NULL,
  PRIMARY KEY (`idtraladosucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.traslado_sucursal: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.traslado_sucursal_entrada
CREATE TABLE IF NOT EXISTS `traslado_sucursal_entrada` (
  `idtraslado_sucursal_entrada` int(11) NOT NULL AUTO_INCREMENT,
  `idtraladosucursal` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `descripcion_entrada_producto` text NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idsucursalingreso` int(11) NOT NULL,
  `idsucursalorigen` int(11) NOT NULL,
  `idsucursaldestino` varchar(200) NOT NULL,
  `estado` varchar(200) NOT NULL,
  `nombresucursaldestino` varchar(50) NOT NULL,
  PRIMARY KEY (`idtraslado_sucursal_entrada`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.traslado_sucursal_entrada: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `idusuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `tipo_documento` varchar(20) NOT NULL,
  `num_documento` varchar(20) NOT NULL,
  `direccion` varchar(70) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `cargo` varchar(20) DEFAULT NULL,
  `login` varchar(20) NOT NULL,
  `clave` varchar(64) NOT NULL,
  `imagen` varchar(50) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT '1',
  `comision` decimal(11,2) NOT NULL DEFAULT '0.00',
  `meta` decimal(11,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `login_UNIQUE` (`login`),
  KEY `idx_usuario_idusuario` (`idusuario`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.usuario: ~1 rows (aproximadamente)
INSERT INTO `usuario` (`idusuario`, `nombre`, `tipo_documento`, `num_documento`, `direccion`, `telefono`, `email`, `cargo`, `login`, `clave`, `imagen`, `idsucursal`, `condicion`, `comision`, `meta`) VALUES
	(34, 'admin', 'DNI', '2342348987987', 'CUIDAD', '44958964', 'admin@gmail.com', 'ADMINISTRADOR', 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', '1580489865.png', 0, 1, 10.00, 50000.00);

-- Volcando estructura para tabla dbsuper365.usuario_permiso
CREATE TABLE IF NOT EXISTS `usuario_permiso` (
  `idusuario_permiso` int(11) NOT NULL AUTO_INCREMENT,
  `idusuario` int(11) NOT NULL,
  `idpermiso` int(11) NOT NULL,
  PRIMARY KEY (`idusuario_permiso`),
  KEY `fk_usuario_permiso_permiso_idx` (`idpermiso`),
  KEY `fk_usuario_permiso_usuario_idx` (`idusuario`),
  CONSTRAINT `fk_usuario_permiso_permiso` FOREIGN KEY (`idpermiso`) REFERENCES `permiso` (`idpermiso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_permiso_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11955 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.usuario_permiso: ~57 rows (aproximadamente)
INSERT INTO `usuario_permiso` (`idusuario_permiso`, `idusuario`, `idpermiso`) VALUES
	(11898, 34, 5),
	(11899, 34, 41),
	(11900, 34, 40),
	(11901, 34, 42),
	(11902, 34, 39),
	(11903, 34, 43),
	(11904, 34, 2),
	(11905, 34, 52),
	(11906, 34, 25),
	(11907, 34, 26),
	(11908, 34, 28),
	(11909, 34, 62),
	(11910, 34, 63),
	(11911, 34, 27),
	(11912, 34, 11),
	(11913, 34, 37),
	(11914, 34, 59),
	(11915, 34, 60),
	(11916, 34, 3),
	(11917, 34, 33),
	(11918, 34, 31),
	(11919, 34, 29),
	(11920, 34, 32),
	(11921, 34, 34),
	(11922, 34, 30),
	(11923, 34, 6),
	(11924, 34, 44),
	(11925, 34, 45),
	(11926, 34, 7),
	(11927, 34, 55),
	(11928, 34, 54),
	(11929, 34, 53),
	(11930, 34, 10),
	(11931, 34, 46),
	(11932, 34, 48),
	(11933, 34, 47),
	(11934, 34, 50),
	(11935, 34, 51),
	(11936, 34, 49),
	(11937, 34, 9),
	(11938, 34, 14),
	(11939, 34, 21),
	(11940, 34, 16),
	(11941, 34, 56),
	(11942, 34, 1),
	(11943, 34, 24),
	(11944, 34, 22),
	(11945, 34, 13),
	(11946, 34, 18),
	(11947, 34, 19),
	(11948, 34, 12),
	(11949, 34, 15),
	(11950, 34, 61),
	(11951, 34, 4),
	(11952, 34, 35),
	(11953, 34, 38),
	(11954, 34, 36);

-- Volcando estructura para tabla dbsuper365.usuario_sucursal
CREATE TABLE IF NOT EXISTS `usuario_sucursal` (
  `idusuario_sucursal` int(11) NOT NULL AUTO_INCREMENT,
  `idusuario` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  PRIMARY KEY (`idusuario_sucursal`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=690 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.usuario_sucursal: ~1 rows (aproximadamente)
INSERT INTO `usuario_sucursal` (`idusuario_sucursal`, `idusuario`, `idsucursal`) VALUES
	(689, 34, 4);

-- Volcando estructura para tabla dbsuper365.vacaciones_empleado
CREATE TABLE IF NOT EXISTS `vacaciones_empleado` (
  `idvacacion` int(11) NOT NULL AUTO_INCREMENT,
  `idempleado` int(11) NOT NULL,
  `fecha_solicitud` date NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `dias_solicitados` int(11) NOT NULL,
  `motivo` text,
  `estado` varchar(20) NOT NULL DEFAULT 'PENDIENTE',
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `condicion` tinyint(4) NOT NULL DEFAULT '1',
  `idusuario` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `idusuario_update` int(11) NOT NULL,
  `fecha_update` datetime NOT NULL,
  `idusuario_delete` int(11) NOT NULL DEFAULT '0',
  `fecha_delete` datetime NOT NULL,
  PRIMARY KEY (`idvacacion`) USING BTREE,
  KEY `fk_vacaciones_empleado` (`idempleado`) USING BTREE,
  CONSTRAINT `fk_vacaciones_empleado` FOREIGN KEY (`idempleado`) REFERENCES `empleados` (`idempleado`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.vacaciones_empleado: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.vendedor
CREATE TABLE IF NOT EXISTS `vendedor` (
  `idvendedor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `telefono` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  PRIMARY KEY (`idvendedor`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla dbsuper365.vendedor: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.venta
CREATE TABLE IF NOT EXISTS `venta` (
  `idventa` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `idnota_credito` int(11) NOT NULL DEFAULT '0',
  `idcobradores` int(11) NOT NULL DEFAULT '0',
  `idtecnico` int(11) NOT NULL DEFAULT '0',
  `tipo_comprobante` varchar(20) NOT NULL,
  `serie_comprobante` varchar(7) DEFAULT NULL,
  `num_comprobante` varchar(10) NOT NULL,
  `tipo_venta_operacion` varchar(50) NOT NULL DEFAULT 'VENTA NORMAL',
  `estado_venta_servicio` varchar(50) NOT NULL DEFAULT 'PENDIENTE',
  `fecha_hora` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total_venta` decimal(11,2) NOT NULL,
  `total_ventades` decimal(11,2) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `condicion` tinyint(4) NOT NULL,
  `tipo_pago` varchar(20) NOT NULL,
  `numero_boleta` varchar(150) NOT NULL,
  `usuariopago` int(11) NOT NULL,
  `fechapago` datetime NOT NULL,
  `estadopago` varchar(20) NOT NULL,
  `forma_pago` varchar(30) NOT NULL,
  `dias_credito` varchar(5) NOT NULL,
  `fecha_hora_cobro` datetime NOT NULL,
  `tipo_banco` varchar(30) NOT NULL,
  `recibo_caja_numero` varchar(60) NOT NULL,
  `idcuenta` int(11) NOT NULL,
  `fecha_hora_siguiente_pago` datetime NOT NULL,
  `observacion_credito` varchar(250) NOT NULL,
  `total_abono` decimal(11,2) NOT NULL,
  `saldo_venta` decimal(11,2) NOT NULL,
  `cefectivo` decimal(11,2) NOT NULL,
  `ctarjeta` decimal(11,2) NOT NULL,
  `ctransferencia` decimal(11,2) NOT NULL,
  `ccredito` decimal(11,2) NOT NULL,
  `rescambio` decimal(11,2) NOT NULL,
  `autorizacionEcoFactura` varchar(200) NOT NULL,
  `serie_ecoFactura` varchar(200) NOT NULL,
  `numero_ecoFactura` varchar(200) NOT NULL,
  `fechaCertificacion_ecoFactura` datetime NOT NULL,
  `nombre_vendedor` varchar(250) NOT NULL,
  `numero_pagos` varchar(100) NOT NULL,
  `fecha_hora_pago` varchar(100) NOT NULL,
  `fecha_hora_vencimiento_factura` varchar(100) NOT NULL,
  `monto_abono` varchar(100) NOT NULL,
  `tipo_operacion` varchar(100) NOT NULL DEFAULT 'APERTURA',
  `idcuadre_caja` int(11) DEFAULT '0',
  `tipo_pagoBacVisaNet` varchar(50) DEFAULT '0',
  `opcionesAdicionales` varchar(50) DEFAULT '0',
  `valor_tarjeta` decimal(20,6) DEFAULT '0.000000',
  `propina` decimal(20,6) DEFAULT '0.000000',
  `id_add_orden` int(11) DEFAULT '0',
  `fecha_creacion` datetime DEFAULT NULL,
  `notacredito` varchar(50) DEFAULT 'NO',
  `tipo_entrega` varchar(50) DEFAULT 'Tienda',
  `estado_venta` varchar(50) DEFAULT 'COMPLETO',
  `despachosino` varchar(50) DEFAULT 'NO',
  `idtransporte` int(11) DEFAULT NULL,
  `idmensajero` int(11) DEFAULT NULL,
  `idvendedor` int(11) DEFAULT NULL,
  `cta_cobrar_descripcion` varchar(450) DEFAULT '0',
  `guia_transporte` varchar(100) DEFAULT '0',
  `fecha_hora_guia_transporte` date DEFAULT NULL,
  `estadoguia` varchar(50) DEFAULT 'PENDIENTEGUIA',
  `descuento_general` varchar(50) DEFAULT 'NO APLICA',
  `valor_descuentoGeneral` varchar(50) DEFAULT '0',
  `estadoventamensajero` varchar(50) DEFAULT '0',
  `comentarioVentaEntregaMensajero` varchar(500) DEFAULT '0',
  `destino` varchar(30) DEFAULT '0',
  `forma_productos` varchar(30) DEFAULT '0',
  `comentario_venta` varchar(500) DEFAULT '0',
  `tipo_envioPedidos` varchar(50) DEFAULT 'PENDIENTE',
  PRIMARY KEY (`idventa`),
  KEY `fk_venta_persona_idx` (`idcliente`),
  KEY `fk_venta_usuario_idx` (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.venta: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.ventas_servicios
CREATE TABLE IF NOT EXISTS `ventas_servicios` (
  `idventas_servicios` int(11) NOT NULL AUTO_INCREMENT,
  `idventa` int(11) NOT NULL DEFAULT '0',
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idsucursal` int(11) NOT NULL DEFAULT '0',
  `descripcion_comentario` text,
  `fecha_hora` varchar(50) DEFAULT NULL,
  `ubicacion` varchar(50) DEFAULT NULL,
  `ip_instalacion` varchar(50) DEFAULT NULL,
  `estado_servicio_venta` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idventas_servicios`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.ventas_servicios: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dbsuper365.venta_salida
CREATE TABLE IF NOT EXISTS `venta_salida` (
  `idventa_salida` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idusuario_creacion` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `observacion_credito` varchar(250) NOT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT '1',
  `estado` varchar(50) NOT NULL DEFAULT 'ACEPTADO',
  PRIMARY KEY (`idventa_salida`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla dbsuper365.venta_salida: ~0 rows (aproximadamente)

-- Volcando estructura para vista dbsuper365.vista_articulos_disponibles
-- Creando tabla temporal para superar errores de dependencia de VIEW
CREATE TABLE `vista_articulos_disponibles` (
	`idarticulo` INT(11) NOT NULL,
	`nombre` MEDIUMTEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`idcategoria` INT(11) NOT NULL,
	`categoria` VARCHAR(1) NOT NULL COLLATE 'latin1_swedish_ci',
	`idsubcategoria` INT(11) NOT NULL,
	`descripcion` TEXT NULL COLLATE 'latin1_swedish_ci',
	`imagen` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`codigo` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`tipo_producto` VARCHAR(1) NOT NULL COLLATE 'latin1_swedish_ci',
	`idusuario` INT(11) NOT NULL,
	`aplica_comision` VARCHAR(1) NOT NULL COLLATE 'latin1_swedish_ci',
	`idsucursal` INT(11) NOT NULL,
	`stock` DECIMAL(20,6) NOT NULL,
	`stockminimo` DECIMAL(20,6) NOT NULL,
	`precio_compra` DECIMAL(20,6) NOT NULL,
	`precio_venta` DECIMAL(20,6) NOT NULL,
	`precio_ventaNocturno` DECIMAL(20,6) NOT NULL,
	`descuento_porcentaje` DECIMAL(20,6) NOT NULL,
	`precio_descuento` DECIMAL(20,6) NOT NULL,
	`precio_rango1` DECIMAL(20,6) NOT NULL,
	`precio_rango1_Dos` DECIMAL(20,6) NOT NULL,
	`precio_rango1_Mecanico` DECIMAL(20,6) NOT NULL,
	`precio_rango1_Distribuidor` DECIMAL(20,6) NOT NULL,
	`precio_rango1_Mayorista` DECIMAL(20,6) NOT NULL,
	`precio_rango2` DECIMAL(20,6) NOT NULL,
	`precio_rango2_Dos` DECIMAL(20,6) NOT NULL,
	`precio_rango2_MecanicoDos` DECIMAL(20,6) NOT NULL,
	`precio_rango2_DistribuidorDos` DECIMAL(20,6) NOT NULL,
	`precio_rango2_MayoristaDos` DECIMAL(20,6) NOT NULL,
	`precio_rango3` DECIMAL(20,6) NOT NULL,
	`precio_rango3_Dos` DECIMAL(20,6) NOT NULL,
	`precio_rango3_MecanicoTres` DECIMAL(20,6) NOT NULL,
	`precio_rango3_DistribuidorTres` DECIMAL(20,6) NOT NULL,
	`precio_rango3_MayoristaTres` DECIMAL(20,6) NOT NULL,
	`facturar_cero` VARCHAR(1) NOT NULL COLLATE 'latin1_swedish_ci',
	`nombre_01` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_unidad` DECIMAL(20,6) NOT NULL,
	`precio_unidad` DECIMAL(20,6) NOT NULL,
	`nombre_02` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_blister` DECIMAL(20,6) NOT NULL,
	`precio_blister` DECIMAL(20,6) NOT NULL,
	`nombre_03` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_caja` DECIMAL(20,6) NOT NULL,
	`precio_caja` DECIMAL(20,6) NOT NULL,
	`nombre_04` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_fardo` DECIMAL(20,6) NOT NULL,
	`precio_fardo` DECIMAL(20,6) NOT NULL,
	`nombre_05` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_sacos` DECIMAL(20,6) NOT NULL,
	`precio_sacos` DECIMAL(20,6) NOT NULL,
	`nombre_06` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_paquete` DECIMAL(20,6) NOT NULL,
	`precio_paquete` DECIMAL(20,6) NOT NULL,
	`nombre_07` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_07` DECIMAL(20,6) NOT NULL,
	`precio_07` DECIMAL(20,6) NOT NULL,
	`nombre_08` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_08` DECIMAL(20,6) NOT NULL,
	`precio_08` DECIMAL(20,6) NOT NULL,
	`nombre_09` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_09` DECIMAL(20,6) NOT NULL,
	`precio_09` DECIMAL(20,6) NOT NULL,
	`nombre_10` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_10` DECIMAL(20,6) NOT NULL,
	`precio_10` DECIMAL(20,6) NOT NULL,
	`nombre_11` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_11` DECIMAL(20,6) NOT NULL,
	`precio_11` DECIMAL(20,6) NOT NULL,
	`nombre_12` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_12` DECIMAL(20,6) NOT NULL,
	`precio_12` DECIMAL(20,6) NOT NULL,
	`nombre_13` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_13` DECIMAL(20,6) NOT NULL,
	`precio_13` DECIMAL(20,6) NOT NULL,
	`nombre_14` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_14` DECIMAL(20,6) NOT NULL,
	`precio_14` DECIMAL(20,6) NOT NULL,
	`nombre_15` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_15` DECIMAL(20,6) NOT NULL,
	`precio_15` DECIMAL(20,6) NOT NULL,
	`nombre_16` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_16` DECIMAL(20,6) NOT NULL,
	`precio_16` DECIMAL(20,6) NOT NULL,
	`nombre_17` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_17` DECIMAL(20,6) NOT NULL,
	`precio_17` DECIMAL(20,6) NOT NULL,
	`nombre_18` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_18` DECIMAL(20,6) NOT NULL,
	`precio_18` DECIMAL(20,6) NOT NULL,
	`nombre_19` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_19` DECIMAL(20,6) NOT NULL,
	`precio_19` DECIMAL(20,6) NOT NULL,
	`nombre_20` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_20` DECIMAL(20,6) NOT NULL,
	`precio_20` DECIMAL(20,6) NOT NULL,
	`condicion` TINYINT(4) NOT NULL,
	`nom_sucursal` VARCHAR(1) NOT NULL COLLATE 'latin1_swedish_ci'
);

-- Volcando estructura para vista dbsuper365.vista_articulo_por_codigo
-- Creando tabla temporal para superar errores de dependencia de VIEW
CREATE TABLE `vista_articulo_por_codigo` (
	`idarticulo` INT(11) NOT NULL,
	`nombre` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`idcategoria` INT(11) NOT NULL,
	`idsubcategoria` INT(11) NOT NULL,
	`descripcion` TEXT NULL COLLATE 'latin1_swedish_ci',
	`imagen` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`codigo` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`tipo_producto` VARCHAR(1) NOT NULL COLLATE 'latin1_swedish_ci',
	`idusuario` INT(11) NOT NULL,
	`aplica_comision` VARCHAR(1) NOT NULL COLLATE 'latin1_swedish_ci',
	`idsucursal` INT(11) NOT NULL,
	`stock` DECIMAL(20,6) NOT NULL,
	`stockminimo` DECIMAL(20,6) NOT NULL,
	`precio_compra` DECIMAL(20,6) NOT NULL,
	`precio_ventaNocturno` DECIMAL(20,6) NOT NULL,
	`precio_rango1` DECIMAL(20,6) NOT NULL,
	`precio_rango1_Dos` DECIMAL(20,6) NOT NULL,
	`precio_rango2` DECIMAL(20,6) NOT NULL,
	`precio_rango2_Dos` DECIMAL(20,6) NOT NULL,
	`precio_rango3` DECIMAL(20,6) NOT NULL,
	`precio_rango3_Dos` DECIMAL(20,6) NOT NULL,
	`precio_rango1_Mecanico` DECIMAL(20,6) NOT NULL,
	`precio_rango1_Distribuidor` DECIMAL(20,6) NOT NULL,
	`precio_rango1_Mayorista` DECIMAL(20,6) NOT NULL,
	`precio_rango2_MecanicoDos` DECIMAL(20,6) NOT NULL,
	`precio_rango2_DistribuidorDos` DECIMAL(20,6) NOT NULL,
	`precio_rango2_MayoristaDos` DECIMAL(20,6) NOT NULL,
	`precio_rango3_MecanicoTres` DECIMAL(20,6) NOT NULL,
	`precio_rango3_DistribuidorTres` DECIMAL(20,6) NOT NULL,
	`precio_rango3_MayoristaTres` DECIMAL(20,6) NOT NULL,
	`descuento_porcentaje` DECIMAL(20,6) NOT NULL,
	`nombre_01` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_unidad` DECIMAL(20,6) NOT NULL,
	`precio_unidad` DECIMAL(20,6) NOT NULL,
	`nombre_02` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_blister` DECIMAL(20,6) NOT NULL,
	`precio_blister` DECIMAL(20,6) NOT NULL,
	`nombre_03` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_caja` DECIMAL(20,6) NOT NULL,
	`precio_caja` DECIMAL(20,6) NOT NULL,
	`nombre_04` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_fardo` DECIMAL(20,6) NOT NULL,
	`precio_fardo` DECIMAL(20,6) NOT NULL,
	`nombre_05` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_sacos` DECIMAL(20,6) NOT NULL,
	`precio_sacos` DECIMAL(20,6) NOT NULL,
	`nombre_06` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_paquete` DECIMAL(20,6) NOT NULL,
	`precio_paquete` DECIMAL(20,6) NOT NULL,
	`nombre_07` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_07` DECIMAL(20,6) NOT NULL,
	`precio_07` DECIMAL(20,6) NOT NULL,
	`nombre_08` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_08` DECIMAL(20,6) NOT NULL,
	`precio_08` DECIMAL(20,6) NOT NULL,
	`nombre_09` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_09` DECIMAL(20,6) NOT NULL,
	`precio_09` DECIMAL(20,6) NOT NULL,
	`nombre_10` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_10` DECIMAL(20,6) NOT NULL,
	`precio_10` DECIMAL(20,6) NOT NULL,
	`nombre_11` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_11` DECIMAL(20,6) NOT NULL,
	`precio_11` DECIMAL(20,6) NOT NULL,
	`nombre_12` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_12` DECIMAL(20,6) NOT NULL,
	`precio_12` DECIMAL(20,6) NOT NULL,
	`nombre_13` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_13` DECIMAL(20,6) NOT NULL,
	`precio_13` DECIMAL(20,6) NOT NULL,
	`nombre_14` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_14` DECIMAL(20,6) NOT NULL,
	`precio_14` DECIMAL(20,6) NOT NULL,
	`nombre_15` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_15` DECIMAL(20,6) NOT NULL,
	`precio_15` DECIMAL(20,6) NOT NULL,
	`nombre_16` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_16` DECIMAL(20,6) NOT NULL,
	`precio_16` DECIMAL(20,6) NOT NULL,
	`nombre_17` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_17` DECIMAL(20,6) NOT NULL,
	`precio_17` DECIMAL(20,6) NOT NULL,
	`nombre_18` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_18` DECIMAL(20,6) NOT NULL,
	`precio_18` DECIMAL(20,6) NOT NULL,
	`nombre_19` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_19` DECIMAL(20,6) NOT NULL,
	`precio_19` DECIMAL(20,6) NOT NULL,
	`nombre_20` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stock_20` DECIMAL(20,6) NOT NULL,
	`precio_20` DECIMAL(20,6) NOT NULL,
	`precio_activado` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`facturar_cero` VARCHAR(1) NOT NULL COLLATE 'latin1_swedish_ci',
	`categoria` VARCHAR(1) NOT NULL COLLATE 'latin1_swedish_ci',
	`precio_venta` DECIMAL(35,12) NULL,
	`nombre_sucursal` VARCHAR(1) NOT NULL COLLATE 'latin1_swedish_ci'
);

-- Volcando estructura para disparador dbsuper365.tr_updStockIngreso
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `tr_updStockIngreso` AFTER INSERT ON `detalle_ingreso` FOR EACH ROW BEGIN
 UPDATE articulo SET stock = stock + NEW.cantidad, precio_venta=New.precio_venta
 WHERE articulo.idarticulo = NEW.idarticulo;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Volcando estructura para disparador dbsuper365.tr_updStockProduccion
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `tr_updStockProduccion` AFTER INSERT ON `detalle_produccion` FOR EACH ROW BEGIN
 UPDATE articulo SET stock = stock - NEW.cantidad 
 WHERE articulo.idarticulo = NEW.idarticulo;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Eliminando tabla temporal y crear estructura final de VIEW
DROP TABLE IF EXISTS `vista_articulos_disponibles`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_articulos_disponibles` AS select `asu`.`idarticulo` AS `idarticulo`,concat(`a`.`nombre`,' ',`a`.`descripcion`) AS `nombre`,`a`.`idcategoria` AS `idcategoria`,`c`.`nombre` AS `categoria`,`a`.`idsubcategoria` AS `idsubcategoria`,`a`.`descripcion` AS `descripcion`,`a`.`imagen` AS `imagen`,`a`.`codigo` AS `codigo`,`a`.`tipo_producto` AS `tipo_producto`,`a`.`idusuario` AS `idusuario`,`a`.`aplica_comision` AS `aplica_comision`,`asu`.`idsucursal` AS `idsucursal`,`asu`.`stocksucursal` AS `stock`,`asu`.`stockminimo` AS `stockminimo`,`asu`.`precio_compra` AS `precio_compra`,`asu`.`precio_venta` AS `precio_venta`,`asu`.`precio_ventaNocturno` AS `precio_ventaNocturno`,`asu`.`descuento_porcentaje` AS `descuento_porcentaje`,`asu`.`precio_descuento` AS `precio_descuento`,`asu`.`precio_rango1` AS `precio_rango1`,`asu`.`precio_rango1_Dos` AS `precio_rango1_Dos`,`asu`.`precio_rango1_Mecanico` AS `precio_rango1_Mecanico`,`asu`.`precio_rango1_Distribuidor` AS `precio_rango1_Distribuidor`,`asu`.`precio_rango1_Mayorista` AS `precio_rango1_Mayorista`,`asu`.`precio_rango2` AS `precio_rango2`,`asu`.`precio_rango2_Dos` AS `precio_rango2_Dos`,`asu`.`precio_rango2_MecanicoDos` AS `precio_rango2_MecanicoDos`,`asu`.`precio_rango2_DistribuidorDos` AS `precio_rango2_DistribuidorDos`,`asu`.`precio_rango2_MayoristaDos` AS `precio_rango2_MayoristaDos`,`asu`.`precio_rango3` AS `precio_rango3`,`asu`.`precio_rango3_Dos` AS `precio_rango3_Dos`,`asu`.`precio_rango3_MecanicoTres` AS `precio_rango3_MecanicoTres`,`asu`.`precio_rango3_DistribuidorTres` AS `precio_rango3_DistribuidorTres`,`asu`.`precio_rango3_MayoristaTres` AS `precio_rango3_MayoristaTres`,`a`.`facturar_cero` AS `facturar_cero`,`asu`.`nombre_01` AS `nombre_01`,`asu`.`stock_unidad` AS `stock_unidad`,`asu`.`precio_unidad` AS `precio_unidad`,`asu`.`nombre_02` AS `nombre_02`,`asu`.`stock_blister` AS `stock_blister`,`asu`.`precio_blister` AS `precio_blister`,`asu`.`nombre_03` AS `nombre_03`,`asu`.`stock_caja` AS `stock_caja`,`asu`.`precio_caja` AS `precio_caja`,`asu`.`nombre_04` AS `nombre_04`,`asu`.`stock_fardo` AS `stock_fardo`,`asu`.`precio_fardo` AS `precio_fardo`,`asu`.`nombre_05` AS `nombre_05`,`asu`.`stock_sacos` AS `stock_sacos`,`asu`.`precio_sacos` AS `precio_sacos`,`asu`.`nombre_06` AS `nombre_06`,`asu`.`stock_paquete` AS `stock_paquete`,`asu`.`precio_paquete` AS `precio_paquete`,`asu`.`nombre_07` AS `nombre_07`,`asu`.`stock_07` AS `stock_07`,`asu`.`precio_07` AS `precio_07`,`asu`.`nombre_08` AS `nombre_08`,`asu`.`stock_08` AS `stock_08`,`asu`.`precio_08` AS `precio_08`,`asu`.`nombre_09` AS `nombre_09`,`asu`.`stock_09` AS `stock_09`,`asu`.`precio_09` AS `precio_09`,`asu`.`nombre_10` AS `nombre_10`,`asu`.`stock_10` AS `stock_10`,`asu`.`precio_10` AS `precio_10`,`asu`.`nombre_11` AS `nombre_11`,`asu`.`stock_11` AS `stock_11`,`asu`.`precio_11` AS `precio_11`,`asu`.`nombre_12` AS `nombre_12`,`asu`.`stock_12` AS `stock_12`,`asu`.`precio_12` AS `precio_12`,`asu`.`nombre_13` AS `nombre_13`,`asu`.`stock_13` AS `stock_13`,`asu`.`precio_13` AS `precio_13`,`asu`.`nombre_14` AS `nombre_14`,`asu`.`stock_14` AS `stock_14`,`asu`.`precio_14` AS `precio_14`,`asu`.`nombre_15` AS `nombre_15`,`asu`.`stock_15` AS `stock_15`,`asu`.`precio_15` AS `precio_15`,`asu`.`nombre_16` AS `nombre_16`,`asu`.`stock_16` AS `stock_16`,`asu`.`precio_16` AS `precio_16`,`asu`.`nombre_17` AS `nombre_17`,`asu`.`stock_17` AS `stock_17`,`asu`.`precio_17` AS `precio_17`,`asu`.`nombre_18` AS `nombre_18`,`asu`.`stock_18` AS `stock_18`,`asu`.`precio_18` AS `precio_18`,`asu`.`nombre_19` AS `nombre_19`,`asu`.`stock_19` AS `stock_19`,`asu`.`precio_19` AS `precio_19`,`asu`.`nombre_20` AS `nombre_20`,`asu`.`stock_20` AS `stock_20`,`asu`.`precio_20` AS `precio_20`,`asu`.`condicion` AS `condicion`,`s`.`nombre` AS `nom_sucursal` from (((`articulo` `a` join `articuloxsucursal` `asu` on((`a`.`idarticulo` = `asu`.`idarticulo`))) join `categoria` `c` on((`c`.`idcategoria` = `a`.`idcategoria`))) join `sucursal` `s` on((`s`.`idsucursal` = `asu`.`idsucursal`))) where ((`asu`.`condicion` = '1') and (`a`.`tipo_producto` not in ('Combos','Servicios')))
;

-- Eliminando tabla temporal y crear estructura final de VIEW
DROP TABLE IF EXISTS `vista_articulo_por_codigo`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_articulo_por_codigo` AS select `asu`.`idarticulo` AS `idarticulo`,`a`.`nombre` AS `nombre`,`a`.`idcategoria` AS `idcategoria`,`a`.`idsubcategoria` AS `idsubcategoria`,`a`.`descripcion` AS `descripcion`,`a`.`imagen` AS `imagen`,`a`.`codigo` AS `codigo`,`a`.`tipo_producto` AS `tipo_producto`,`a`.`idusuario` AS `idusuario`,`a`.`aplica_comision` AS `aplica_comision`,`asu`.`idsucursal` AS `idsucursal`,`asu`.`stocksucursal` AS `stock`,`asu`.`stockminimo` AS `stockminimo`,`asu`.`precio_compra` AS `precio_compra`,`asu`.`precio_ventaNocturno` AS `precio_ventaNocturno`,`asu`.`precio_rango1` AS `precio_rango1`,`asu`.`precio_rango1_Dos` AS `precio_rango1_Dos`,`asu`.`precio_rango2` AS `precio_rango2`,`asu`.`precio_rango2_Dos` AS `precio_rango2_Dos`,`asu`.`precio_rango3` AS `precio_rango3`,`asu`.`precio_rango3_Dos` AS `precio_rango3_Dos`,`asu`.`precio_rango1_Mecanico` AS `precio_rango1_Mecanico`,`asu`.`precio_rango1_Distribuidor` AS `precio_rango1_Distribuidor`,`asu`.`precio_rango1_Mayorista` AS `precio_rango1_Mayorista`,`asu`.`precio_rango2_MecanicoDos` AS `precio_rango2_MecanicoDos`,`asu`.`precio_rango2_DistribuidorDos` AS `precio_rango2_DistribuidorDos`,`asu`.`precio_rango2_MayoristaDos` AS `precio_rango2_MayoristaDos`,`asu`.`precio_rango3_MecanicoTres` AS `precio_rango3_MecanicoTres`,`asu`.`precio_rango3_DistribuidorTres` AS `precio_rango3_DistribuidorTres`,`asu`.`precio_rango3_MayoristaTres` AS `precio_rango3_MayoristaTres`,`asu`.`descuento_porcentaje` AS `descuento_porcentaje`,`asu`.`nombre_01` AS `nombre_01`,`asu`.`stock_unidad` AS `stock_unidad`,`asu`.`precio_unidad` AS `precio_unidad`,`asu`.`nombre_02` AS `nombre_02`,`asu`.`stock_blister` AS `stock_blister`,`asu`.`precio_blister` AS `precio_blister`,`asu`.`nombre_03` AS `nombre_03`,`asu`.`stock_caja` AS `stock_caja`,`asu`.`precio_caja` AS `precio_caja`,`asu`.`nombre_04` AS `nombre_04`,`asu`.`stock_fardo` AS `stock_fardo`,`asu`.`precio_fardo` AS `precio_fardo`,`asu`.`nombre_05` AS `nombre_05`,`asu`.`stock_sacos` AS `stock_sacos`,`asu`.`precio_sacos` AS `precio_sacos`,`asu`.`nombre_06` AS `nombre_06`,`asu`.`stock_paquete` AS `stock_paquete`,`asu`.`precio_paquete` AS `precio_paquete`,`asu`.`nombre_07` AS `nombre_07`,`asu`.`stock_07` AS `stock_07`,`asu`.`precio_07` AS `precio_07`,`asu`.`nombre_08` AS `nombre_08`,`asu`.`stock_08` AS `stock_08`,`asu`.`precio_08` AS `precio_08`,`asu`.`nombre_09` AS `nombre_09`,`asu`.`stock_09` AS `stock_09`,`asu`.`precio_09` AS `precio_09`,`asu`.`nombre_10` AS `nombre_10`,`asu`.`stock_10` AS `stock_10`,`asu`.`precio_10` AS `precio_10`,`asu`.`nombre_11` AS `nombre_11`,`asu`.`stock_11` AS `stock_11`,`asu`.`precio_11` AS `precio_11`,`asu`.`nombre_12` AS `nombre_12`,`asu`.`stock_12` AS `stock_12`,`asu`.`precio_12` AS `precio_12`,`asu`.`nombre_13` AS `nombre_13`,`asu`.`stock_13` AS `stock_13`,`asu`.`precio_13` AS `precio_13`,`asu`.`nombre_14` AS `nombre_14`,`asu`.`stock_14` AS `stock_14`,`asu`.`precio_14` AS `precio_14`,`asu`.`nombre_15` AS `nombre_15`,`asu`.`stock_15` AS `stock_15`,`asu`.`precio_15` AS `precio_15`,`asu`.`nombre_16` AS `nombre_16`,`asu`.`stock_16` AS `stock_16`,`asu`.`precio_16` AS `precio_16`,`asu`.`nombre_17` AS `nombre_17`,`asu`.`stock_17` AS `stock_17`,`asu`.`precio_17` AS `precio_17`,`asu`.`nombre_18` AS `nombre_18`,`asu`.`stock_18` AS `stock_18`,`asu`.`precio_18` AS `precio_18`,`asu`.`nombre_19` AS `nombre_19`,`asu`.`stock_19` AS `stock_19`,`asu`.`precio_19` AS `precio_19`,`asu`.`nombre_20` AS `nombre_20`,`asu`.`stock_20` AS `stock_20`,`asu`.`precio_20` AS `precio_20`,`asu`.`precio_activado` AS `precio_activado`,`a`.`facturar_cero` AS `facturar_cero`,`c`.`nombre` AS `categoria`,(case when (`c`.`tipo_descuento` = 'Porcentaje') then (`asu`.`precio_venta` * (1 - (`c`.`valor_descuento` / 100))) when (`c`.`tipo_descuento` = 'Quetzales') then (`asu`.`precio_venta` - `c`.`valor_descuento`) else `asu`.`precio_venta` end) AS `precio_venta`,`s`.`nombre` AS `nombre_sucursal` from (((`articulo` `a` join `articuloxsucursal` `asu` on((`a`.`idarticulo` = `asu`.`idarticulo`))) join `sucursal` `s` on((`s`.`idsucursal` = `asu`.`idsucursal`))) join `categoria` `c` on((`a`.`idcategoria` = `c`.`idcategoria`))) where (`asu`.`condicion` = '1')
;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
