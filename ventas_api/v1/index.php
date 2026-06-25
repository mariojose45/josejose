<?php
error_reporting(E_ALL);

// Basic Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\AgencyController;
use App\Controllers\QuotationController;
use App\Controllers\ClientController;
use App\Controllers\ProductController;
use App\Controllers\SaleController;

try {
    $router = new Router();

    // CORS Headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        exit;
    }

    // Welcome / Health check
    $router->add('GET', '/', function() { 
        echo json_encode(["status" => "success", "message" => "VentasSol API v1 is running"]); 
    });

    // Auth & Agencies
    $router->add('POST', '/login', function() { (new AuthController())->login(); });
    $router->add('GET', '/agencies', function() { (new AgencyController())->list(); });

    // Quotations
    $router->add('GET', '/quotations', function() { (new QuotationController())->list(); });
    $router->add('GET', '/quotations/detail', function() { (new QuotationController())->show(); });
    $router->add('POST', '/quotations', function() { (new QuotationController())->create(); });
    $router->add('POST', '/quotations/update', function() { (new QuotationController())->update(); });

    // Clients
    $router->add('GET', '/clients/search', function() { (new ClientController())->search(); });

    // Products & Categories
    $router->add('GET', '/products', function() { (new ProductController())->list(); });
    $router->add('GET', '/products/detail', function() { (new ProductController())->detail(); });
    $router->add('GET', '/categories', function() { (new ProductController())->categories(); });

    // Sales
    $router->add('GET', '/sales', function() { (new SaleController())->list(); });
    $router->add('GET', '/sales/detail', function() { (new SaleController())->show(); });
    $router->add('POST', '/sales', function() { (new SaleController())->create(); });

    // Run Router
    $router->run();
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "FATAL API ERROR",
        "details" => $e->getMessage(),
        "file" => $e->getFile(),
        "line" => $e->getLine()
    ]);
}
