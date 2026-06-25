<?php

namespace App\Controllers;

use App\Models\User;

class AuthController {
    public function login() {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $login = $input['login'] ?? '';
        $password = $input['clave'] ?? '';

        if (empty($login) || empty($password)) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Login and password are required"
            ]);
            return;
        }

        $userModel = new User();
        $user = $userModel->verify($login, $password);

        if ($user) {
            // In a production environment, you should use a real JWT library
            // For now, we'll return the user info and a mock token
            echo json_encode([
                "status" => "success",
                "data" => [
                    "user" => $user,
                    "token" => base64_encode(json_encode(["id" => $user['idusuario'], "exp" => time() + 3600]))
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                "status" => "error",
                "message" => "Invalid credentials"
            ]);
        }
    }
}
