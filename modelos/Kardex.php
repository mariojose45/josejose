<?php
require "../config/Conexion.php";

class Kardex
{
    public function __construct() {}

    public function listarDetallado($fecha_inicio, $fecha_fin, $idsucursal, $codigo_pro)
    {
        // Obtener el idarticulo a partir del codigo_pro
        $idarticulo = 0;
        if (!empty($codigo_pro)) {
            $sqlArt = "SELECT idarticulo FROM articulo WHERE codigo = '$codigo_pro' LIMIT 1";
            $resArt = ejecutarConsultaSimpleFila($sqlArt);
            if ($resArt) {
                $idarticulo = $resArt['idarticulo'];
            }
        }

        // Armar el WHERE
        $where = " 1=1 ";
        if (!empty($idsucursal) && $idsucursal !== 'TODO') {
            $where .= " AND km.idsucursal = '$idsucursal' ";
        }
        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $where .= " AND DATE(km.fecha_hora) >= '$fecha_inicio' AND DATE(km.fecha_hora) <= '$fecha_fin' ";
        }
        if ($idarticulo > 0) {
            $where .= " AND km.idarticulo = '$idarticulo' ";
        }

        $sql = "SELECT 
                    km.idkardex,
                    DATE(km.fecha_hora) AS fecha,
                    TIME(km.fecha_hora) AS hora,
                    a.codigo,
                    a.nombre AS nombre_articulo,
                    s.nombre AS sucursal,
                    km.concepto,
                    km.num_documento,
                    km.cantidad_existente,
                    km.cantidad_modificacion,
                    km.tipo_modificacion,
                    km.cantidad_final,
                    km.precio,
                    (km.cantidad_modificacion * km.precio) AS total,
                    km.responsable
                FROM kardex_movimientos km
                INNER JOIN articulo a ON a.idarticulo = km.idarticulo
                INNER JOIN sucursal s ON s.idsucursal = km.idsucursal
                WHERE $where
                ORDER BY km.fecha_hora ASC";

        return ejecutarConsulta($sql);
    }



    public function listarDetalladoTodo($fecha_inicio, $fecha_fin, $idsucursal)
    {
        // Armar el WHERE general por sucursal
        $where = " 1=1 ";
        if (!empty($idsucursal) && $idsucursal !== 'TODO') {
            $where .= " AND km.idsucursal = '$idsucursal' ";
        }

        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $where .= " AND DATE(km.fecha_hora) >= '$fecha_inicio' AND DATE(km.fecha_hora) <= '$fecha_fin' ";
        }

        $sql = "SELECT 
                    km.idkardex,
                    DATE(km.fecha_hora) AS fecha,
                    TIME(km.fecha_hora) AS hora,
                    a.codigo,
                    a.nombre AS nombre_articulo,
                    s.nombre AS sucursal,
                    km.concepto,
                    km.num_documento,
                    km.cantidad_existente,
                    km.cantidad_modificacion,
                    km.tipo_modificacion,
                    km.cantidad_final,
                    km.precio,
                    (km.cantidad_modificacion * km.precio) AS total,
                    km.responsable
                FROM kardex_movimientos km
                INNER JOIN articulo a ON a.idarticulo = km.idarticulo
                INNER JOIN sucursal s ON s.idsucursal = km.idsucursal
                WHERE $where
                ORDER BY km.fecha_hora ASC";

        return ejecutarConsulta($sql);
    }
}
