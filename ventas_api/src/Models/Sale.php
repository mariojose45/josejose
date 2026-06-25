<?php

namespace App\Models;

use App\Core\Config;
use PDO;

class Sale {
    private $db;

    public function __construct() {
        $this->db = Config::getConnection();
    }

    public function list($startDate, $endDate, $agencyId) {
        $sql = "SELECT v.idventa, v.num_comprobante, v.fecha_hora as fecha, v.total_venta, v.estado,
                       p.nombre as cliente, u.nombre as usuario, v.tipo_comprobante, v.forma_pago
                FROM venta v
                INNER JOIN persona p ON v.idcliente = p.idpersona
                INNER JOIN usuario u ON v.idusuario = u.idusuario
                WHERE v.idsucursal = :agencyId 
                AND DATE(v.fecha_hora) BETWEEN :start AND :end
                ORDER BY v.idventa DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':agencyId' => $agencyId,
            ':start' => $startDate,
            ':end' => $endDate
        ]);

        return $stmt->fetchAll();
    }

    public function getDetails($id) {
        $sql = "SELECT v.*, p.nombre as cliente, p.num_documento, p.direccion, p.telefono, p.email,
                       s.nombre as sucursal_nombre, s.direccion as sucursal_direccion, s.telefono as sucursal_telefono, s.email as sucursal_email, s.nombre_comercial,
                       u.nombre as usuario_nombre
                FROM venta v
                INNER JOIN persona p ON v.idcliente = p.idpersona
                INNER JOIN sucursal s ON v.idsucursal = s.idsucursal
                INNER JOIN usuario u ON v.idusuario = u.idusuario
                WHERE v.idventa = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $header = $stmt->fetch();

        if (!$header) return null;

        $sql_items = "SELECT dv.*, a.nombre as producto
                      FROM detalle_venta dv
                      INNER JOIN articulo a ON dv.idarticulo = a.idarticulo
                      WHERE dv.idventa = :id";
        $stmt_items = $this->db->prepare($sql_items);
        $stmt_items->execute([':id' => $id]);
        $header['items'] = $stmt_items->fetchAll();

        return $header;
    }

    public function create($data) {
        $this->db->beginTransaction();

        try {
            // 1. Get Correlativo for the specific document type (Envio or Factura)
            // Note: In the legacy system, it depends on idsucursal and the column in add_correlativo
            $type = $data['tipo_comprobante'] ?? 'Envio';
            $col = ($type === 'Factura') ? 'num_factura' : 'num_envio';
            
            $sql_corr_up = "UPDATE add_correlativo SET $col = $col + 1 WHERE idsucursal = :agencyId";
            $stmt_corr_up = $this->db->prepare($sql_corr_up);
            $stmt_corr_up->execute([':agencyId' => $data['idsucursal']]);

            $sql_corr_get = "SELECT $col FROM add_correlativo WHERE idsucursal = :agencyId";
            $stmt_corr_get = $this->db->prepare($sql_corr_get);
            $stmt_corr_get->execute([':agencyId' => $data['idsucursal']]);
            $num_comprobante = $stmt_corr_get->fetchColumn();

            // 2. Insert Header
            $sql = "INSERT INTO venta (
                        idcliente, idusuario, idsucursal, tipo_comprobante, num_comprobante, 
                        fecha_hora, total_venta, total_ventades, estado, forma_pago,
                        cefectivo, rescambio, ccredito, ctarjeta, ctransferencia,
                        tipo_pagoBacVisaNet, opcionesAdicionales, observacion_credito,
                        tipo_entrega, idvendedor, comentario_venta, tipo_venta_operacion,
                        fecha_creacion, saldo_venta
                    ) VALUES (
                        :idcliente, :idusuario, :idsucursal, :tipo_comprobante, :num_comprobante,
                        :fecha_hora, :total_venta, :total_ventades, 'Aceptado', :forma_pago,
                        :cefectivo, :rescambio, :ccredito, :ctarjeta, :ctransferencia,
                        :tipo_pagoBacVisaNet, :opcionesAdicionales, :observacion_credito,
                        :tipo_entrega, :idvendedor, :comentario_venta, 'VENTA NORMAL',
                        NOW(), :saldo_venta
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':idcliente' => $data['idcliente'],
                ':idusuario' => $data['idusuario'],
                ':idsucursal' => $data['idsucursal'],
                ':tipo_comprobante' => $type,
                ':num_comprobante' => $num_comprobante,
                ':fecha_hora' => $data['fecha_hora'] ?? date('Y-m-d H:i:s'),
                ':total_venta' => $data['total_venta'],
                ':total_ventades' => $data['total_ventades'] ?? 0,
                ':forma_pago' => $data['forma_pago'],
                ':cefectivo' => $data['cefectivo'] ?? 0,
                ':rescambio' => $data['rescambio'] ?? 0,
                ':ccredito' => $data['ccredito'] ?? 0,
                ':ctarjeta' => $data['ctarjeta'] ?? 0,
                ':ctransferencia' => $data['ctransferencia'] ?? 0,
                ':tipo_pagoBacVisaNet' => $data['tipo_pagoBacVisaNet'] ?? 'Seleccione Uno',
                ':opcionesAdicionales' => $data['opcionesAdicionales'] ?? '',
                ':observacion_credito' => $data['observacion_credito'] ?? '',
                ':tipo_entrega' => $data['tipo_entrega'] ?? 'Tienda',
                ':idvendedor' => $data['idvendedor'] ?? $data['idusuario'],
                ':comentario_venta' => $data['comentario_venta'] ?? '',
                ':saldo_venta' => ($data['forma_pago'] === 'Credito') ? $data['ccredito'] : 0
            ]);

            $saleId = $this->db->lastInsertId();

            // 3. Insert Details and Update Stock
            foreach ($data['items'] as $item) {
                // Get current stock and cost info
                $sql_info = "SELECT asu.precio_compra, asu.stocksucursal, a.tipo_producto 
                             FROM articuloxsucursal asu 
                             INNER JOIN articulo a ON asu.idarticulo = a.idarticulo
                             WHERE asu.idarticulo = :id AND asu.idsucursal = :agencyId";
                $stmt_info = $this->db->prepare($sql_info);
                $stmt_info->execute([':id' => $item['idarticulo'], ':agencyId' => $data['idsucursal']]);
                $info = $stmt_info->fetch();

                $totalQty = $item['totalcantidadpresentacion'] ?? ($item['cantidad'] * ($item['cantidadpresentacion'] ?? 1));

                $sql_detalle = "INSERT INTO detalle_venta (
                                    idventa, idarticulo, cantidad, precio_venta, descuento, 
                                    stockinven, subtotaldes1, precio_ventaSistema, subtotal1,
                                    cantidadpresentacion, totalcantidadpresentacion, presen, precio_compra
                                ) VALUES (
                                    :idventa, :idarticulo, :cantidad, :precio_venta, :descuento,
                                    :stockinven, :subtotaldes1, :p_sistema, :subtotal1,
                                    :cant_pres, :total_qty, :presen, :p_compra
                                )";
                
                $stmt_det = $this->db->prepare($sql_detalle);
                $stmt_det->execute([
                    ':idventa' => $saleId,
                    ':idarticulo' => $item['idarticulo'],
                    ':cantidad' => $item['cantidad'],
                    ':precio_venta' => $item['precio_venta'],
                    ':descuento' => $item['descuento'] ?? 0,
                    ':stockinven' => $info['stocksucursal'] ?? 0,
                    ':subtotaldes1' => $item['subtotaldes1'] ?? 0,
                    ':p_sistema' => $item['precio_ventaSistema'] ?? $item['precio_venta'],
                    ':subtotal1' => $item['subtotal1'] ?? ($item['cantidad'] * $item['precio_venta']),
                    ':cant_pres' => $item['cantidadpresentacion'] ?? 1,
                    ':total_qty' => $totalQty,
                    ':presen' => $item['presen'] ?? 'General',
                    ':p_compra' => $info['precio_compra'] ?? 0
                ]);

                // Update Stock if it's a product
                if (($info['tipo_producto'] ?? '') === 'Productos') {
                    $sql_stock = "UPDATE articuloxsucursal SET stocksucursal = stocksucursal - :qty 
                                  WHERE idarticulo = :id AND idsucursal = :agencyId";
                    $stmt_stock = $this->db->prepare($sql_stock);
                    $stmt_stock->execute([
                        ':qty' => $totalQty,
                        ':id' => $item['idarticulo'],
                        ':agencyId' => $data['idsucursal']
                    ]);
                }
            }

            $this->db->commit();
            return (int)$saleId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
