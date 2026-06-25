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
    require 'Classes/PHPExcel.php';
    require_once "../modelos/Venta.php"; 
    
    $venta = new Venta();
   
   
    $rspta = $venta->listar(); 
    $fila=2;
  //Objeto de PHPExcel
    $objPHPExcel  = new PHPExcel();
  
  //Propiedades de Documento
  $objPHPExcel->getProperties()->setCreator("TecnoserviciosJireh")->setDescription("Reporte de Ventas x Fecha");    

//Establecemos la pestaña activa y nombre a la pestaña
  $objPHPExcel->setActiveSheetIndex(0);
  $objPHPExcel->getActiveSheet()->setTitle("VentasxFecha");  

  $objPHPExcel->getActiveSheet()->setCellValue('A1','FECHA');
  $objPHPExcel->getActiveSheet()->setCellValue('B1','USUARIO');


//Recorremos los resultados de la consulta y los imprimimos
  while($rows = $rspta->fetch_assoc()){
    
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $rows['fecha']);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $rows['usuario']);

    //$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, '=C'.$fila.'*D'.$fila);
    
    $fila++; //Sumamos 1 para pasar a la siguiente fila
  }
  
  $fila = $fila-1;
  
  header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
  header('Content-Disposition: attachment;filename="ventasxfecha.xlsx"'); 
  header('Cache-Control: max-age=0');
  $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
  
  $writer->save('php://output');  

  }
}

?>