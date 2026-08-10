<?php
$path = 'c:/xampp/htdocs/josejose/modelos/Articulo.php';
$content = file_get_contents($path);
$content = preg_replace('/ROUND\(([^,]+),\s*2\)/', 'ROUND($1, 6)', $content);
file_put_contents($path, $content);
echo "Replaced properly";
?>
