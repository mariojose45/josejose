<?php

namespace App\Models;

use App\Core\Config;
use PDO;

class Quotation
{
    private $db;

    public function __construct()
    {
        $this->db = Config::getConnection();
    }

    public function list($startDate, $endDate, $agencyId)
    {
        $sql = "SELECT 
                    c.idcotizacion,
                    c.idcliente,
                    p.nombre as cliente,
                    c.idusuario,
                    u.nombre as usuario,
                    DATE(c.fecha_hora) as fecha,
                    c.total_venta,
                    c.total_ventades,
                    c.estado,
                    c.tipo_comprobante,
                    c.num_comprobante
                FROM cotizacion c
                INNER JOIN persona p ON p.idpersona = c.idcliente
                INNER JOIN usuario u ON u.idusuario = c.idusuario
                WHERE c.idsucursal = :agencyId 
                AND DATE(c.fecha_hora) BETWEEN :startDate AND :endDate
                ORDER BY c.idcotizacion DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':agencyId' => $agencyId,
            ':startDate' => $startDate,
            ':endDate' => $endDate
        ]);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $this->db->beginTransaction();

        try {
            // Update correlative
            $sqlCorrelativo = "UPDATE add_correlativo SET num_cotizacion = num_cotizacion + 1 WHERE idsucursal = :idsucursal";
            $stmtCorr = $this->db->prepare($sqlCorrelativo);
            $stmtCorr->execute([':idsucursal' => $data['idsucursal']]);

            // Get new correlative
            $sqlGetCorr = "SELECT num_cotizacion FROM add_correlativo WHERE idsucursal = :idsucursal";
            $stmtGetCorr = $this->db->prepare($sqlGetCorr);
            $stmtGetCorr->execute([':idsucursal' => $data['idsucursal']]);
            $correlativo = $stmtGetCorr->fetchColumn();

            // Insert Quotation
            $sql = "INSERT INTO cotizacion (idcliente, 
                                            idusuario, 
                                            fecha_hora, 
                                            nombre_empresa, 
                                            telefono_empresa, 
                                            total_venta, 
                                            total_ventades, 
                                            estado, 
                                            tipo_comprobante, 
                                            num_comprobante, 
                                            idsucursal)
                                    VALUES (:idcliente, 
                                            :idusuario, 
                                            :fecha_hora, 
                                            :nombre_empresa, 
                                            :telefono_empresa, 
                                            :total_venta, 
                                            :total_ventades, 
                                            'Aceptado', 
                                            'Cotizacion', 
                                            :num_comprobante, 
                                            :idsucursal)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':idcliente' => $data['idcliente'],
                ':idusuario' => $data['idusuario'],
                ':fecha_hora' => $data['fecha_hora'],
                ':nombre_empresa' => $data['nombre_empresa'] ?? '',
                ':telefono_empresa' => $data['telefono_empresa'] ?? '',
                ':total_venta' => $data['total_venta'],
                ':total_ventades' => $data['total_ventades'],
                ':num_comprobante' => $correlativo,
                ':idsucursal' => $data['idsucursal']
            ]);

            $idCotizacion = $this->db->lastInsertId();

            // Insert Details
            foreach ($data['items'] as $item) {
                $sql_detalle = "INSERT INTO detalle_cotizacion (idcotizacion, idarticulo, cantidad, precio_venta, descuento, 
                descripcion_detalle, presen, cantidadpresentacion, totalcantidadpresentacion,
                stockinven, precio_ventaSistema, precio_ventaSistema2, q_ref,
                subtotal1 ) 
                                VALUES (:idcotizacion, :idarticulo, :cantidad, :precio_venta, :descuento, 
                                :descripcion_detalle, :presen, :cantidadpresentacion, :totalcantidadpresentacion, 
                                :stockinven, :precio_ventaSistema, :precio_ventaSistema2, :q_ref,
                                :subtotal1)";
                $stmt_det = $this->db->prepare($sql_detalle);

                $cantidadPresentacion = !empty($item['cantidadpresentacion']) ? $item['cantidadpresentacion'] : 1;
                $cantidad = !empty($item['cantidad']) ? $item['cantidad'] : 1;
                $precioVenta = $item['precio_venta'] ?? 0;
                $precioVentaSistemaCalculado = $precioVenta / $cantidadPresentacion;
                $subtotal1 = $precioVenta * $cantidad;

                $stmt_det->execute([
                    ':idcotizacion' => $idCotizacion,
                    ':idarticulo' => $item['idarticulo'],
                    ':cantidad' => $item['cantidad'],
                    ':precio_venta' => $precioVentaSistemaCalculado,
                    ':descuento' => $item['descuento'] ?? 0,
                    ':descripcion_detalle' => $item['descripcion_detalle'] ?? '',
                    ':presen' => $item['presen'] ?? 'General',
                    ':cantidadpresentacion' => $item['cantidadpresentacion'] ?? 1,
                    ':totalcantidadpresentacion' => $item['totalcantidadpresentacion'] ?? $item['cantidad'],
                    ':stockinven' => 0,
                    ':precio_ventaSistema' => $precioVentaSistemaCalculado,
                    ':precio_ventaSistema2' => $precioVenta,
                    ':q_ref' => $precioVenta,
                    ':subtotal1' => $subtotal1
                ]);
            }

            $this->db->commit();
            return $idCotizacion;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    public function getDetails($id)
    {
        // Header
        $sql = "SELECT c.*, p.nombre as cliente, p.num_documento, p.email, p.telefono, p.direccion
                FROM cotizacion c
                INNER JOIN persona p ON p.idpersona = c.idcliente
                WHERE c.idcotizacion = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $header = $stmt->fetch();

        if (!$header)
            return null;

        // Items
        $sql_items = "SELECT dc.*, a.nombre as producto, a.codigo
                      FROM detalle_cotizacion dc
                      INNER JOIN articulo a ON a.idarticulo = dc.idarticulo
                      WHERE dc.idcotizacion = :id";
        $stmt_items = $this->db->prepare($sql_items);
        $stmt_items->execute([':id' => $id]);
        $header['items'] = $stmt_items->fetchAll();

        return $header;
    }
    public function update($id, $data)
    {
        $this->db->beginTransaction();

        try {
            // Update Header
            $sql = "UPDATE cotizacion SET 
                        idcliente = :idcliente, 
                        total_venta = :total_venta, 
                        total_ventades = :total_ventades
                    WHERE idcotizacion = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':idcliente' => $data['idcliente'],
                ':total_venta' => $data['total_venta'],
                ':total_ventades' => $data['total_ventades'],
                ':id' => $id
            ]);

            // Clear old Details
            $sql_clear = "DELETE FROM detalle_cotizacion WHERE idcotizacion = :id";
            $stmt_clear = $this->db->prepare($sql_clear);
            $stmt_clear->execute([':id' => $id]);

            // Insert New Details
            foreach ($data['items'] as $item) {
                $sql_detalle = "INSERT INTO detalle_cotizacion (idcotizacion, idarticulo, cantidad, precio_venta, descuento, descripcion_detalle, presen, cantidadpresentacion, totalcantidadpresentacion) 
                                VALUES (:idcotizacion, :idarticulo, :cantidad, :precio_venta, :descuento, :descripcion_detalle, :presen, :cantidadpresentacion, :totalcantidadpresentacion)";
                $stmt_det = $this->db->prepare($sql_detalle);
                $stmt_det->execute([
                    ':idcotizacion' => $id,
                    ':idarticulo' => $item['idarticulo'],
                    ':cantidad' => $item['cantidad'],
                    ':precio_venta' => $item['precio_venta'],
                    ':descuento' => $item['descuento'] ?? 0,
                    ':descripcion_detalle' => $item['descripcion_detalle'] ?? '',
                    ':presen' => $item['presen'] ?? 'General',
                    ':cantidadpresentacion' => $item['cantidadpresentacion'] ?? 1,
                    ':totalcantidadpresentacion' => $item['totalcantidadpresentacion'] ?? $item['cantidad']
                ]);
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
