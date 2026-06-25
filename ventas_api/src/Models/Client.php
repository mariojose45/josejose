<?php

namespace App\Models;

use App\Core\Config;
use PDO;

class Client {
    private $db;

    public function __construct() {
        $this->db = Config::getConnection();
    }

    public function search(string $query): array {
        $sql = "SELECT idpersona as id, nombre, num_documento, direccion, telefono, email, tipo_cliente 
                FROM persona 
                WHERE (nombre LIKE :query1 OR num_documento LIKE :query2) 
                AND tipo_persona = 'Cliente' 
                LIMIT 20";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':query1' => "%$query%",
                ':query2' => "%$query%"
            ]);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            throw new \Exception("Client search error: " . $e->getMessage() . " | SQL: " . $sql);
        }
    }

    public function getById(int $id): ?array {
        $sql = "SELECT idpersona as id, nombre, num_documento, direccion, telefono, email, tipo_cliente 
                FROM persona 
                WHERE idpersona = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $client = $stmt->fetch();
        return $client ?: null;
    }
}
