<?php
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
});
use App\Models\Product;
use App\Core\Config;

try {
    $p = new Product();
    $global = $p->getGlobalPresentations();
    echo "Global presentations:\n";
    print_r($global);
    
    // Also, query a product to see its values
    $db = Config::getConnection();
    // Let's get any product that has precio_09 > 0
    $stmt = $db->query("SELECT * FROM articuloxsucursal WHERE precio_09 > 0 LIMIT 1");
    $item = $stmt->fetch();
    echo "\nProduct with precio_09 > 0:\n";
    print_r($item);

} catch (Exception $e) {
    echo $e->getMessage();
}
