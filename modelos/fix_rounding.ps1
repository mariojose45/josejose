$content = Get-Content -Path "c:\xampp\htdocs\josejose\modelos\Articulo.php" -Raw
$content = $content -replace 'ROUND\((asu\.[a-zA-Z0-9_]+),\s*2\)', 'ROUND($1, 6)'
Set-Content -Path "c:\xampp\htdocs\josejose\modelos\Articulo.php" -Value $content -NoNewline
