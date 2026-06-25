<?php

namespace App\Models;

use App\Core\Config;
use PDO;

class Product {
    private $db;

    public function __construct() {
        $this->db = Config::getConnection();
    }

    public function listByAgency(int $agencyId, ?int $categoryId = null, ?string $search = null): array {
        $sql = "SELECT 
                    a.idarticulo,
                    a.nombre,
                    a.codigo,
                    a.descripcion,
                    a.imagen,
                    axs.precio_venta,
                    c.nombre as categoria,
                    axs.stocksucursal as stock,
                    axs.nombre_01, axs.precio_unidad, axs.stock_unidad,
                    axs.nombre_02, axs.precio_blister, axs.stock_blister,
                    axs.nombre_03, axs.precio_caja, axs.stock_caja,
                    axs.nombre_04, axs.precio_fardo, axs.stock_fardo,
                    axs.nombre_05, axs.precio_sacos, axs.stock_sacos,
                    axs.nombre_06, axs.precio_paquete, axs.stock_paquete,
                    axs.precio_rango1, axs.precio_rango2, axs.precio_rango3
                FROM articulo a
                INNER JOIN categoria c ON a.idcategoria = c.idcategoria
                INNER JOIN articuloxsucursal axs ON a.idarticulo = axs.idarticulo
                WHERE axs.idsucursal = :agencyId AND axs.condicion = '1'";
        
        $params = [':agencyId' => $agencyId];

        if ($categoryId && $categoryId !== 'null' && $categoryId !== '') {
            $sql .= " AND a.idcategoria = :categoryId";
            $params[':categoryId'] = $categoryId;
        }

        if ($search && $search !== '') {
            $sql .= " AND (a.nombre LIKE :search1 OR a.codigo LIKE :search2)";
            $params[':search1'] = "%$search%";
            $params[':search2'] = "%$search%";
        }

        $sql .= " ORDER BY a.nombre ASC LIMIT 50";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            // Re-throw with more context
            throw new \Exception("Database query error: " . $e->getMessage() . " | SQL: " . $sql);
        }
    }

    public function listCategories(): array {
        $sql = "SELECT idcategoria as id, nombre FROM categoria WHERE condicion = '1' ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id, int $agencyId): ?array {
        $sql = "SELECT 
                    a.idarticulo,
                    a.nombre,
                    a.codigo,
                    a.descripcion,
                    a.imagen,
                    axs.precio_venta,
                    axs.stocksucursal as stock,
                    axs.nombre_01, axs.precio_unidad, axs.stock_unidad,
                    axs.nombre_02, axs.precio_blister, axs.stock_blister,
                    axs.nombre_03, axs.precio_caja, axs.stock_caja,
                    axs.nombre_04, axs.precio_fardo, axs.stock_fardo,
                    axs.nombre_05, axs.precio_sacos, axs.stock_sacos,
                    axs.nombre_06, axs.precio_paquete, axs.stock_paquete,
                    axs.precio_rango1, axs.precio_rango2, axs.precio_rango3
                FROM articulo a
                INNER JOIN articuloxsucursal axs ON a.idarticulo = axs.idarticulo
                WHERE a.idarticulo = :id AND axs.idsucursal = :agencyId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id, ':agencyId' => $agencyId]);
        return $stmt->fetch() ?: null;
    }

    public function getGlobalPresentations(): ?array {
        $sql = "SELECT * FROM presentacion_precios LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch() ?: null;
    }
}
