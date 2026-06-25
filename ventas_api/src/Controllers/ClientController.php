<?php

namespace App\Controllers;

use App\Models\Client;

class ClientController {
    public function search() {
        header('Content-Type: application/json');
        
        $query = $_GET['q'] ?? '';

        try {
            $model = new Client();
            $list = $model->search($query);

            echo json_encode([
                "status" => "success",
                "data" => $list
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
}
