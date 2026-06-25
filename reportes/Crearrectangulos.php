<?php 

// Incluir FPDF si no está incluido aún
if (!class_exists('FPDF')) {
    require_once('fpdf.php');
}

// Verifica si la clase PDF_Code128 ya está definida
if (!class_exists('PDF_Code128')) {
    class PDF_Code128 extends FPDF {
        // Función para dibujar rectángulos con bordes redondeados
        function RoundRect($x, $y, $w, $h, $r) {
            $k = $this->k;
            $ar = $r * $k;
            $this->_out(sprintf('%.2f %.2f m', ($x + $r) * $k, $y * $k));
            $this->_out(sprintf('%.2f %.2f l', ($x + $w - $r) * $k, $y * $k));
            $this->_out(sprintf('%.2f %.2f l', ($x + $w) * $k, ($y + $r) * $k));
            $this->_out(sprintf('%.2f %.2f l', ($x + $w) * $k, ($y + $h - $r) * $k));
            $this->_out(sprintf('%.2f %.2f l', ($x + $w - $r) * $k, ($y + $h) * $k));
            $this->_out(sprintf('%.2f %.2f l', ($x + $r) * $k, ($y + $h) * $k));
            $this->_out(sprintf('%.2f %.2f l', $x * $k, ($y + $h - $r) * $k));
            $this->_out(sprintf('%.2f %.2f l', $x * $k, ($y + $r) * $k));
            $this->_out('S');
        }
    }
}

 ?>

