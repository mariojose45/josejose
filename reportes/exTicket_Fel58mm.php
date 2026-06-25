<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

if (!isset($_SESSION["nombre"]))
{
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
}
else
{
  if ($_SESSION['ventas']==1)
  {
    //Incluímos el archivo Factura.php
    require('FormatoCartaConFacturaTickekt.php');   

    //Establecemos los datos de la empresa
    include 'empresa.php';

    //Incluímos la clase Venta
    require_once "../modelos/Cotizaciones.php";   
    $cotizaciones = new Cotizaciones();

    $rsptav = $cotizaciones->ventacabecera2($_GET["id"]);  
    //Recorremos todos los valores obtenidos
    $regv = $rsptav->fetch_object();

    //Establecemos la configuración de la factura
    $pdf = new PDF_Invoice('P', 'mm', array(58,2500));
    $pdf->AddPage();

    $pdf->fact_dev(utf8_decode("FACTURA"), "");
    $pdf->temporaire("");

    $pdf->SetXY(110,1); 
    $pdf->Multicell(100,4,utf8_decode(""),0,"C"); 

    $url='../files/articulos/';
    $color_r_texto=255;
    $color_g_texto=255;
    $color_b_texto=255;

    $color_r=0;
    $color_g=0;
    $color_b=0; 

    $xposision=1;
    $ancchodefial=58;

    if ($regv->estado=='Anulado') {
        $pdf->Image($anulado,10 ,150, 40 , 25 );
    }

    $pdf->SetXY($xposision,1);
    $pdf->Image($url.$regv->sucursal_imagen,10 ,3, 40 , 25 );
    $pdf->SetFont('Arial','',11);
    $pdf->SetXY($xposision,42); 
    $pdf->Multicell($ancchodefial,4,utf8_decode($regv->nombre_comercial),0,"C");  

    $pdf->Ln(1);
    $pdf->SetX($xposision);  
    $pdf->Multicell($ancchodefial,4,utf8_decode($regv->nombre_fel),0,"C");   

    $pdf->Ln(1);
    $pdf->SetX($xposision);  
    $pdf->Cell($ancchodefial,4,utf8_decode("Nit: ".$regv->sucursal_nit),0,0,"C");           

    $pdf->Ln(5);
    $pdf->SetX($xposision);  
    $pdf->Multicell($ancchodefial,4,"Direc: ".$regv->direccion_fiscal,0,"C");  
    $pdf->Ln(1);
    $pdf->SetX($xposision);  
    $pdf->Multicell($ancchodefial,4,"Tels: ".$regv->sucursal_telefono,0,"C");  

    $pdf->Ln(1);
    $pdf->SetX($xposision);  
    $pdf->Multicell($ancchodefial,4,$regv->sucursal_email,0,"C");      

    $pdf->Ln(1);
    $pdf->SetX($xposision);               
    $pdf->Cell($ancchodefial, 5, "", "B", 0, "C");  

    $pdf->Ln(5);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,utf8_decode("DOCUMENTO TRIBUTARIO ELECTRONICO"),0,"C");  
    
    $pdf->Ln(1);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,utf8_decode("NUMERO DE AUTORIZACION: ".$regv->autorizacionEcoFactura),0,"C");        

    $pdf->Ln(1);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,utf8_decode("SERIE: ".$regv->serie_ecoFactura),0,"C");  

    $pdf->Ln(1);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,utf8_decode("NUMERO: ".$regv->numero_ecoFactura),0,"C");      

    $pdf->Ln(1);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,utf8_decode("FECHA EMISION: ".date("d/m/Y", strtotime($regv->fecha))),0,"C");                                

    $pdf->Ln(1);
    $pdf->SetX($xposision);               
    $pdf->Cell($ancchodefial, 5, "", "B", 0, "C");         

    $pdf->Ln(6);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,utf8_decode("Clie: ".$regv->cliente),0,"L"); 
    $pdf->Ln(5);
    $pdf->SetX($xposision); 
    $pdf->Multicell($ancchodefial,4,utf8_decode("Direc: ".$regv->direccion),0,"L");     
    $pdf->Ln(5);
    $pdf->SetX($xposision); 
    $pdf->Cell($ancchodefial,4,utf8_decode($regv->tipo_documento.": ".$regv->num_documento),0,"L");  
    $pdf->Ln(5);
    $pdf->SetX($xposision);          
    $pdf->Multicell($ancchodefial,4,utf8_decode("Tels: ".$regv->telefono),0,"L");       

    $pdf->Ln(5);
    $pdf->SetX($xposision);               
    $pdf->Cell($ancchodefial, 5, "", "B", 0, "C");  
    
    $pdf->Ln(5);
    $pdf->SetX($xposision); 
    $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
    $pdf->SetFillColor($color_r,$color_g,$color_b);
    $pdf->Cell($ancchodefial,3.5,".::DATOS DE PRODUCTOS::.",1,0,'C',1); 
    $pdf->SetTextColor(0,0,0); 

    $pdf->Ln(5);
    $pdf->SetX($xposision); 
    $pdf->SetFillColor($color_r,$color_g,$color_b);
    $pdf->Cell($ancchodefial,10," ",1,0,'C',1); 
    $pdf->SetTextColor($color_r_texto,$color_g_texto,$color_b_texto); 
    $pdf->Ln(1);
    $pdf->SetX($xposision); 
    $pdf->cell($ancchodefial,4,"ARTICULO",0,0,'C'); 
    $pdf->Ln(1);
    $pdf->SetX($xposision);     
    $pdf->cell(19,10,"CAN"); 
    $pdf->cell(19,10,"P.U.");
    $pdf->cell(19,10,"SUB"); 
    $pdf->SetTextColor(0,0,0);         
    $pdf->Ln(10);
    $pdf->SetX($xposision); 

    // ============================================================
    // ==== INICIO CAMBIO (agrupación padre/hijos para FACTURA) ====
    // 1) Traer TODAS las líneas (padres + hijos) con UNA sola consulta
    $rsptad = $cotizaciones->ventadetalle2($_GET["id"]);

    $items = [];
    while ($row = $rsptad->fetch_object()) {
        $items[] = $row;
    }

    // 2) Separar padres e indexar hijos por idarticulopadre
    $parents  = [];
    $children = [];
    foreach ($items as $it) {
        $tipo = isset($it->tipo) ? trim((string)$it->tipo) : '';
        if ($tipo === 'Topping' || $tipo === 'Extra') {
            $padre = (int)$it->idarticulopadre;
            if (!isset($children[$padre])) $children[$padre] = [];
            $children[$padre][] = $it;
        } else {
            $parents[] = $it;
        }
    }

    setlocale(LC_MONETARY, "en_US");

    foreach ($parents as $regd) {
        // --------- LÍNEA PADRE ----------
        $descPadre = utf8_decode($regd->articulo . (isset($regd->presen) ? " ".$regd->presen : "") . " " . $regd->descripcion_detalle);

        $yInicio = $pdf->GetY();
        $pdf->MultiCell($ancchodefial, 4, $descPadre, 0, 'L');
        $yFin = $pdf->GetY();
        $yFin += 2; // espacio adicional
        $pdf->SetY($yFin);
        $pdf->SetX(1);

        // Cantidad, P.U., Subtotal del padre
        $pdf->Cell(15, 4, $regd->cantidad, 0, 0, 'L');
        $pdf->Cell(21, 4, number_format((float)$regd->q_ref, 2, '.', ','), 0, 0, 'C');     // P.U.
        $pdf->Cell(21, 4, number_format((float)$regd->totalsindescuento, 2, '.', ','), 0, 0, 'R');  // Sub
        $pdf->Ln(5);
        $pdf->SetX(1);

        // --------- HIJOS (TOPPINGS / EXTRAS) ----------
        $idPadre = (int)$regd->idarticulo;
        if (!empty($children[$idPadre])) {

            // Orden: primero Topping, luego Extra (y opcionalmente por nombre)
            $kids = $children[$idPadre];
            $order = ['Topping' => 0, 'Extra' => 1];
            usort($kids, function($a, $b) use ($order) {
                $ta = isset($a->tipo) ? $a->tipo : '';
                $tb = isset($b->tipo) ? $b->tipo : '';
                $pa = $order[$ta] ?? 99;
                $pb = $order[$tb] ?? 99;
                if ($pa !== $pb) return $pa <=> $pb;
                return strcasecmp((string)$a->articulo, (string)$b->articulo);
            });

            foreach ($kids as $hijo) {
                // Descripción con sangría; para guion usa "  -- "
                $descHijo = "  -- " . utf8_decode($hijo->articulo . (isset($hijo->presen) ? " ".$hijo->presen : "") . " " . $hijo->descripcion_detalle);

                if (isset($hijo->tipo) && $hijo->tipo === 'Topping') {
                    // TOPPING: SOLO DESCRIPCIÓN (sin CAN/P.U./SUB)
                    $pdf->MultiCell($ancchodefial, 4, $descHijo, 0, 'L');
                    $pdf->SetY($pdf->GetY() + 1);
                    $pdf->SetX(1);

                } else {
                    // EXTRA: CON cantidad, P.U. y Subtotal
                    $pdf->MultiCell($ancchodefial, 4, $descHijo, 0, 'L');
                    $pdf->SetY($pdf->GetY() + 1);
                    $pdf->SetX(1);

                    $cantidadHijo = isset($hijo->cantidad) ? (float)$hijo->cantidad : 0.0;
                    // P.U. del hijo: usa precio_venta si existe; si no, q_ref
                    $puHijo  = isset($hijo->precio_venta) ? (float)$hijo->precio_venta
                                                           : (isset($hijo->q_ref) ? (float)$hijo->q_ref : 0.0);
                    // Subtotal del hijo: usa totalsindescuento si viene; si no, calcula
                    $subHijo = isset($hijo->totalsindescuento) ? (float)$hijo->totalsindescuento
                                                                : ($puHijo * $cantidadHijo);

                    $pdf->Cell(15, 4, number_format($cantidadHijo, 2, '.', ','), 0, 0, 'L');
                    $pdf->Cell(21, 4, number_format($puHijo,       2, '.', ','), 0, 0, 'C');
                    $pdf->Cell(21, 4, number_format($subHijo,      2, '.', ','), 0, 0, 'R');
                    $pdf->Ln(5);
                    $pdf->SetX(1);
                }
            }
        }
    }
    // ==== FIN CAMBIO (agrupación padre/hijos para FACTURA) ====
    // ============================================================

    $pdf->Ln(1); 
    $pdf->SetX($xposision);               
    $pdf->Cell($ancchodefial, 5, "", "B", 0, "C"); 
    require_once "num2letras.php";
    $conletras = $regv->total_venta;
    $conletrasresultado = num2letras($conletras);    

    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Cell($ancchodefial,4,utf8_decode("SUBTOTAL Q: ".$regv->totalgeneral),0,0,"R"); 
    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Cell($ancchodefial,4,utf8_decode("DESCUENTO Q: ".$regv->total_ventades),0,0,"R"); 
    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Cell($ancchodefial,4,utf8_decode("TOTAL Q: ".$regv->total_venta),0,0,"R");         

    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Multicell($ancchodefial,4,utf8_decode("Total en Letras: ".$conletrasresultado." Quetzales"),0,"C"); 

    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Multicell($ancchodefial,4,utf8_decode($regv->dato_sat),0,"C");     

    $pdf->Ln(5);
    $pdf->SetX($xposision);               
    $pdf->Cell($ancchodefial, 5, "", "B", 0, "C");  

    $pdf->Ln(1);
    $pdf->SetX($xposision);     
    $pdf->Multicell($ancchodefial,4,utf8_decode("Le atendio: ".$regv->usuario),0,"C");   

    $pdf->Ln(1);
    $pdf->SetX($xposision);     
    $pdf->Multicell($ancchodefial,4,utf8_decode("# Venta: ".$regv->num_comprobante),0,"C");   

    $pdf->Ln(1);
    $pdf->SetX($xposision);     
    $pdf->Multicell($ancchodefial,4,utf8_decode("Nº de venta control interno: # ".$regv->idventa),0,"C");                     

    $pdf->Ln(1);
    $pdf->SetX($xposision);               
    $pdf->Cell($ancchodefial, 5, "", "B", 0, "C");  

    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Multicell($ancchodefial,4,utf8_decode($regv->nombrecertificador),0,"C");                    

    $pdf->Ln(5);
    $pdf->SetX($xposision);     
    $pdf->Multicell($ancchodefial,4,utf8_decode($regv->empresadesarrollo),0,"C");              

    $pdf->Ln(5);
    $pdf->SetX($xposision);               
    $pdf->SetTextColor(255,255,255); 
    $pdf->SetFillColor($color_r,$color_g,$color_b);
    $pdf->Cell($ancchodefial,5,".::ULTIMA LINEA::.",1,0,'C',1); 
    $pdf->SetTextColor(0,0,0); 

    $pdf->Output('Venta Factura Dte No '.$regv->numero_ecoFactura.".pdf",'I');

  }
  else
  {
    echo 'No tiene permiso para visualizar el reporte';
  }
}
ob_end_flush();
?>
