<?php

namespace App\Core;

use PDO;
use PDOException;

class Config
{
    private const HOST = "159.65.36.174";
    private const DB_NAME = "dbjosejose";
    private const USER = "compusisgt";
    private const PASS = "compusisgt2020%";
    private const CHARSET = "utf8mb4";

    private static $connection = null;

    public static function getConnection()
    {
        if (self::$connection === null) {
            try {
                $dsn = "mysql:host=" . self::HOST . ";dbname=" . self::DB_NAME . ";charset=" . self::CHARSET;
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                self::$connection = new PDO($dsn, self::USER, self::PASS, $options);
            } catch (PDOException $e) {
                // Return generic error to user
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => "Database connection failed"]);
                exit;
            }
        }
        return self::$connection;
    }
}
