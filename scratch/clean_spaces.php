<?php
$file1 = 'c:\\xampp\\htdocs\\josejose\\reportes\\exTicket58mm.php';
$file2 = 'c:\\xampp\\htdocs\\josejose\\reportes\\exTicket.php';

foreach ([$file1, $file2] as $file) {
    $content = file_get_contents($file);
    
    // Remove excessive spaces/newlines (more than 2 consecutive newlines)
    $content = preg_replace("/(\r?\n){3,}/", "\r\n\r\n", $content);
    
    // Also remove spaces around lines like `$pdf->Cell(\n    10,\n    ...`
    // but maybe just removing the extra blank lines is enough for readability!
    
    file_put_contents($file, $content);
}

echo "Cleaned spaces in both files!";
?>
