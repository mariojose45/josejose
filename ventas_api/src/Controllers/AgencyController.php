<?php

namespace App\Controllers;

use App\Models\User;

class AgencyController {
    public function list() {
        header('Content-Type: application/json');
        
        $userId = $_GET['user_id'] ?? null;

        if (!$userId) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "User ID is required"]);
            return;
        }

        $userModel = new User();
        $agencies = $userModel->getAgencies($userId);

        echo json_encode([
            "status" => "success",
            "data" => $agencies
        ]);
    }
}
