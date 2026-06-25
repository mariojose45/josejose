<?php

namespace App\Core;

class Router {
    private $routes = [];

    public function add($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function run() {
        header('Content-Type: application/json');
        
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $path = $_SERVER['REQUEST_URI'];

            // Extract the part of the path relative to the api root
            $apiPath = parse_url($path, PHP_URL_PATH);
            
            // Flexible base path detection: look for ventas_api/v1/ and take everything after it
            $marker = 'ventas_api/v1';
            $markerPos = strpos($apiPath, $marker);
            
            if ($markerPos !== false) {
                $apiPath = substr($apiPath, $markerPos + strlen($marker));
            }

            // Also strip /index.php if it's present at the beginning of the remaining path
            if (strpos($apiPath, '/index.php') === 0) {
                $apiPath = substr($apiPath, 10); // strlen('/index.php')
            }

            if (empty($apiPath) || $apiPath === '/') $apiPath = '/';
            // Remove trailing slash if present (except for root)
            if ($apiPath !== '/' && substr($apiPath, -1) === '/') {
                $apiPath = substr($apiPath, 0, -1);
            }

            // Fallback: if apiPath is empty or root, check if there's a 'route' parameter
            if (($apiPath === '/' || empty($apiPath)) && isset($_GET['route'])) {
                $apiPath = $_GET['route'];
                if (strpos($apiPath, '/') !== 0) {
                    $apiPath = '/' . $apiPath;
                }
            }

            foreach ($this->routes as $route) {
                if ($route['method'] === $method && $route['path'] === $apiPath) {
                    $handler = $route['handler'];
                    $handler();
                    return;
                }
            }

            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Route not found: " . $apiPath]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Global API Error: " . $e->getMessage(),
                "trace" => $e->getTraceAsString()
            ]);
        }
    }
}
