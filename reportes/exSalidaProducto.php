<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';

if (!isset($_SESSION["nombre"])) exit("Error de sesión");
if ($_SESSION["salidaproducto"] != 1) exit("Sin permisos");

require_once "../modelos/Salida_pro_sucursal.php";
require("FormatoMuchoshojasBlanco.php"); // Aquí ya está PDF_Invoice y RoundedRect02()


// ===================================================
//     EXTENDEMOS PDF_Invoice PARA PERSONALIZAR PDF
// ===================================================
class PDF_SALIDA extends PDF_Invoice
{
    public $fecha_impresion;

    function Header()
    {
        global $regv;

        // Guardar fecha de impresión una sola vez
        if (!$this->fecha_impresion) {
            date_default_timezone_set("America/Guatemala");
            $this->fecha_impresion = date("Y-m-d H:i:s");
        }

        // LOGO
        $url = '../files/articulos/';
        $logo = ($regv->sucursal_imagen == "" || $regv->sucursal_imagen == "0")
            ? $url . '1590204245.jpg'
            : $url . $regv->sucursal_imagen;

        $this->Image($logo, 10, 10, 28);

        // RECUADRO SALIDA
        $this->SetXY(140, 10);
        $this->TitleBox("SALIDA NO: " . $regv->idtraladosucursal, [42, 66, 110], [255, 255, 255]);

        // RECUADRO FECHA
        $this->SetXY(140, $this->GetY());
        $this->TitleBox("Fecha Emisión: " . $regv->fecha, [255, 255, 255], [0, 0, 0]);

        // INFORMACIÓN SUCURSAL
        $this->SetFont('Arial', '', 10);
        $this->SetXY(40, 10);
        $this->MultiCell(
            0,
            5,
            utf8_decode(
                $regv->sucursal_nombre . "\n" .
                    "Nit: " . $regv->sucursal_nit . "\n" .
                    "Dirección: " . $regv->sucursal_direccion . "\n" .
                    "Tel: " . $regv->sucursal_telefono . " - Email: " . $regv->sucursal_email . "\n" .
                    "Usuario Creación: " . $regv->usuario
            )
        );

        // Línea separadora
        $this->Ln(4);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        // Datos de traslado
        $this->Ln(3);
        $this->MultiCell(
            0,
            5,
            utf8_decode(
                "Sucursal origen: " . $regv->nombresucursalorigen . "\n" .
                    "Sucursal destino: " . $regv->nombresucursaldestino . "\n" .
                    "Desc Traslado: " . $regv->descripcion_salida_producto
            )
        );

        // Línea separadora
        $this->Ln(2);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(4);

        // TABLA ENCABEZADO
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(230, 230, 230);
        $this->Cell(20, 8, "CANT", 1, 0, 'C', true);
        $this->Cell(90, 8, "DESCRIPCION", 1, 0, 'C', true);
        $this->Cell(30, 8, "CODIGO", 1, 0, 'C', true);
        $this->Cell(25, 8, "P.U.", 1, 0, 'C', true);
        $this->Cell(25, 8, "SUBTOTAL", 1, 1, 'C', true);
    }


    function Footer()
    {
        global $regv;

        $this->SetY(-25);
        $this->SetFont('Arial', '', 9);

        // Separador
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(3);

        // Fecha de impresión
        $this->Cell(0, 5, "Impreso: " . $this->fecha_impresion, 0, 1, 'C');

        // PAGINA X DE Y
        $this->Cell(0, 5, "Pagina " . $this->PageNo() . " de {nb}", 0, 1, 'C');

        // Última línea
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(0, 5, utf8_decode(".:: ULTIMA LINEA ::."), 0, 1, 'C');
    }
}


// ===================================================
//              CARGAR DATOS
// ===================================================
$sal = new Salidaprosucursal();
$id = $_GET["id"];
$rspta = $sal->salidaprosucursalcabecera($id);
$regv = $rspta->fetch_object();


// ===================================================
//                GENERAR PDF
// ===================================================
$pdf = new PDF_SALIDA('P', 'mm', 'A4');
$pdf->AliasNbPages();        // <<--- ACTIVAR PAGINACIÓN DINÁMICA
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);


// ===================================================
//               TABLA DETALLE
// ===================================================
$rsptad = $sal->salidaprosucursaltadetalle($id);
$totalCant = 0;

while ($d = $rsptad->fetch_object()) {

    $pdf->Cell(20, 8, $d->cantidad, 1);

    $x = $pdf->GetX();
    $y = $pdf->GetY();

    $pdf->MultiCell(90, 8, utf8_decode($d->articulo . ' ' . $d->presentacion . ' ' . $d->descripcion_detalle), 1);

    $altura = $pdf->GetY() - $y;
    $pdf->SetXY($x + 90, $y);

    $pdf->Cell(30, $altura, $d->codigo, 1, 0, 'C');
    $pdf->Cell(25, $altura, number_format($d->precio_venta, 2), 1, 0, 'R');
    $pdf->Cell(25, $altura, number_format($d->subtotal, 2), 1, 1, 'R');

    $totalCant += $d->cantidad;
}


// ===================================================
//               TOTAL ARTÍCULOS
// ===================================================
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, "Total Articulos: " . $totalCant, 0, 1, 'R');


// ===================================================
//                  EXPORTAR
// ===================================================
$pdf->Output("Salida_Producto_$id.pdf", "I");
