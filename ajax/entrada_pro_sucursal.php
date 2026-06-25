<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"]))
{
  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
}
else    
{    
//Validamos el acceso solo al usuario logueado y autorizado. 
if ($_SESSION['entradaproducto']==1)
{
require_once "../modelos/Entrada_pro_sucursal.php"; 
 
$entradaprosucursal=new Entradaprosucursal();       

$idtraladosucursal_entrada=isset($_POST["idtraladosucursal_entrada"])? limpiarCadena($_POST["idtraladosucursal_entrada"]):"";
$idtraladosucursal=isset($_POST["idtraladosucursal"])? limpiarCadena($_POST["idtraladosucursal"]):"";
$idsucursal=isset($_POST["idsucursal"])? limpiarCadena($_POST["idsucursal"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$idsucursalorigen=isset($_POST["idsucursalorigen"])? limpiarCadena($_POST["idsucursalorigen"]):"";
$idsucursaldestino=isset($_POST["idsucursaldestino"])? limpiarCadena($_POST["idsucursaldestino"]):"";
$descripcion_salida_producto=isset($_POST["descripcion_salida_producto"])? limpiarCadena($_POST["descripcion_salida_producto"]):"";
$idusuario=$_SESSION["idusuario"];
$idsucursalingreso=$_SESSION["idsucursal"];
$nombresucursaldestino=isset($_POST["nombresucursaldestino"])? limpiarCadena($_POST["nombresucursaldestino"]):""; 



switch ($_GET["op"]){
    case 'guardaryeditar':
        if(isset($_POST['datosArticulosE'])){
            $datosArticulos = json_decode($_POST['datosArticulosE'], true);
        }
        if (empty($idtraladosucursal_entrada)){
            $rspta=$entradaprosucursal->insertar($idtraladosucursal,$idsucursal,$fecha_hora,$descripcion_salida_producto,
            $idusuario,$idsucursalingreso,$idsucursalorigen,$idsucursaldestino,$nombresucursaldestino,$datosArticulos);
            echo $rspta ? "Salida Producto Sucursal" : "No se pudieron registrar todos los datos de la venta";
            //echo $rspta;
        }
        else {
        }
    break; 

    case 'anular':
        $rspta=$entradaprosucursal->anular($idtraladosucursal_entrada);
        echo $rspta ? "Salida Producto anulada" : "Salida Producto no se puede anular";
    break;

    case 'paratrasladodetalle':
        $cotiz=$entradaprosucursal->detalletraaladoparasucursal($idtraladosucursal);
        echo json_encode($cotiz);
    break;  

    case 'mostrar': 
        $rspta=$entradaprosucursal->mostrar($idtraladosucursal); 
        //Codificar el resultado utilizando json
        echo json_encode($rspta);  
    break;      
 


    case 'listar':
        $rspta=$entradaprosucursal->listar();
        //Vamos a declarar un array
        $data= Array(); 

        while ($reg=$rspta->fetch_object()){
                $url='../reportes/exEntradaProducto.php?id=';
                $url2='../reportes/exEntradaProducto_formatotermico_79mm.php?id=';
                $url3='../reportes/exEntradaProducto_formatotermico_58mm.php?id=';
 

            $data[]=array(
                "0"=>'<a target="_blank" href="'.$url.$reg->idtraslado_sucursal_entrada.'"> <button class="btn btn-info"><i class="fa fa-print"></i></button></a>'.
                '<a target="_blank" title="Imprimir a 79mm" href="'.$url2.$reg->idtraslado_sucursal_entrada.'"> <button class="btn btn-info"><i class="fa fa-print"></i></button></a>'.
                '<a target="_blank" title="Imprimir a 58mm" href="'.$url3.$reg->idtraslado_sucursal_entrada.'"> <button class="btn btn-info"><i class="fa fa-print"></i></button></a>',
                "1"=>$reg->idtraslado_sucursal_entrada,
                "2"=>$reg->fecha,
                "3"=>$reg->usuario,
                "4"=>$reg->nombresucursaldestino,
                "5"=>$reg->nombresucursalorigen,
                "6"=>$reg->descripcion_entrada_producto,
                "7"=>($reg->estado=='INGRESO PRODUCTO')?'<span class="label bg-green">INGRESO PRODUCTO</span>':
                '<span class="label bg-red">Anulado</span>'
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);

    break;

    case 'selectSucursal':

        $rspta = $entradaprosucursal->selectSucursal();

        while ($reg = $rspta->fetch_object())
                {
                echo '<option value=' . $reg->idsucursal . '>' . $reg->nombre . '</option>';
                }
    break;

    case 'listarArticulosVenta':
        require_once "../modelos/Articulo.php";
        $articulo=new Articulo();

        $rspta=$articulo->listarActivosVentatraslado();
        //Vamos a declarar un array
        $data= Array();

        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\')"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->nombre,
                "2"=>$reg->categoria,
                "3"=>$reg->codigo,
                "4"=>$reg->stock,
                "5"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >"
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
    break;


    case 'listarxfechasucursal':

        $fecha_inicio=$_REQUEST["fecha_inicio"];
        $fecha_fin=$_REQUEST["fecha_fin"];
        $idsucursal=$_REQUEST["idsucursal"];  

        $rspta=$entradaprosucursal->listarxfechasucursal($fecha_inicio,$fecha_fin,$idsucursal);
        //Vamos a declarar un array
        $data= Array();

        while ($reg=$rspta->fetch_object()){
                $url='../reportes/exSalidaProducto.php?id=';
            $data[]=array(
                "0"=>'<a target="_blank" href="'.$url.$reg->idtraslado_sucursal_entrada.'"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>',
                "1"=>$reg->idtraslado_sucursal_entrada,
                "2"=>$reg->fecha,
                "3"=>$reg->usuario,
                "4"=>$reg->nombresucursaldestino,
                "5"=>$reg->nombresucursalorigen,
                "6"=>$reg->descripcion_entrada_producto,
                "7"=>$reg->estado
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
    break;


    case 'listarxfechasucursalDetalle':

        $fecha_inicio=$_REQUEST["fecha_inicio"];
        $fecha_fin=$_REQUEST["fecha_fin"];
        $idsucursal=$_REQUEST["idsucursal"];   

        $rspta=$entradaprosucursal->listarxfechasucursalDetalle($fecha_inicio,$fecha_fin,$idsucursal);
        //Vamos a declarar un array
        $data= Array();

        while ($reg=$rspta->fetch_object()){
            $url='../reportes/exEntradaProducto.php?id=';
            $data[]=array(
                "0"=>$reg->fecha,
                "1"=>$reg->idtraladosucursal,
                "2"=>$reg->fechavencimiento,
                "3"=>$reg->codigo,
                "4"=>$reg->articulo,
                "5"=>$reg->cantidad,
                "6"=>$reg->precio_venta,
                "7"=>$reg->estado,
                "8"=>'<a target="_blank" href="'.$url.$reg->idtraslado_sucursal_entrada.'"> <button class="btn btn-info"><i class="fa fa-print"></i></button></a>'
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
    break;    

}
//Fin de las validaciones de acceso
}
else
{
  require 'noacceso.php';
}
}
ob_end_flush();
?>