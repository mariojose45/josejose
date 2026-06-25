<?php

namespace App\Models;

use App\Core\Config;
use PDO;

class User {
    private $db;

    public function __construct() {
        $this->db = Config::getConnection();
    }

    public function verify($login, $password) {
        $sql = "SELECT 
                    u.idusuario,
                    u.nombre,
                    u.tipo_documento,
                    u.num_documento,
                    u.telefono,
                    u.email,
                    u.cargo,
                    u.imagen,
                    u.login,
                    u.idsucursal,
                    s.clave_ordenes,
                    s.clave_ingresos,
                    s.clave_ventas
                FROM usuario u
                INNER JOIN usuario_sucursal us ON us.idusuario = u.idusuario
                INNER JOIN sucursal s ON s.idsucursal = us.idsucursal
                WHERE u.login = :login 
                AND u.clave = :clave 
                AND u.condicion = '1' 
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':login' => $login,
            ':clave' => hash("sha256", $password)
        ]);

        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function getById($id) {
        $sql = "SELECT * FROM usuario WHERE idusuario = :id AND condicion = '1' LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function getAgencies($id) {
        $sql = "SELECT 
                    us.idsucursal,
                    s.nombre AS nombre_sucursal,
                    s.direccion AS direccion_sucursal
                FROM usuario_sucursal us 
                INNER JOIN sucursal s ON s.idsucursal = us.idsucursal 
                WHERE us.idusuario = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll();
    }
}
