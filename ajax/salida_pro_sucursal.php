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
if ($_SESSION['salidaproducto']==1)
{
require_once "../modelos/Salida_pro_sucursal.php"; 
  
$salidaprosucursal=new Salidaprosucursal();    

$idtraladosucursal=isset($_POST["idtraladosucursal"])? limpiarCadena($_POST["idtraladosucursal"]):"";
$idsucursal=isset($_POST["idsucursal"])? limpiarCadena($_POST["idsucursal"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";
$descripcion_salida_producto=isset($_POST["descripcion_salida_producto"])? limpiarCadena($_POST["descripcion_salida_producto"]):"";
$total_venta_r=isset($_POST["total_venta_r"])? limpiarCadena($_POST["total_venta_r"]):"";
$idusuario=$_SESSION["idusuario"];
$idsucursalorigen=isset($_POST["idsucursalOrigen"])? limpiarCadena($_POST["idsucursalOrigen"]):"";


switch ($_GET["op"]){
    case 'guardaryeditar':
        if(isset($_POST['datosArticulosS'])){
            $datosArticulos = json_decode($_POST['datosArticulosS'], true);
        }
        if (empty($idtraladosucursal)){
            $rspta=$salidaprosucursal->insertar($idsucursal,$fecha_hora,$descripcion_salida_producto,$idusuario,$idsucursalorigen,
            $datosArticulos,$total_venta_r); 
            echo $rspta ? "Salida Producto Sucursal" : "No se pudieron registrar todos los datos de la venta";
            //echo "INSRTAR";

        }
        else {
            $rspta=$salidaprosucursal->editarSalida($idtraladosucursal,$idsucursal,$fecha_hora,$descripcion_salida_producto,$idusuario,$idsucursalorigen,
            $datosArticulos,$total_venta_r);
            echo $rspta ? "Salida actualizada" : "Salida no se pudo actualizar";
            //echo "ACTUALIZAR";
        }
    break; 

    case 'anular':
        $rspta=$salidaprosucursal->anular($idtraladosucursal);
        echo $rspta ? "Salida Producto anulada" : "Salida Producto no se puede anular";
    break;

    case 'mostrar':
        $rspta=$salidaprosucursal->mostrar($idtraladosucursal); 
        //Codificar el resultado utilizando json
        echo json_encode($rspta);  
    break;

    case 'detalle_traslado':
        $cotiz=$salidaprosucursal->detallecotizacionparaventa($idtraladosucursal);
        echo json_encode($cotiz); 
    break;



    case 'listar':
        $fecha_inicio=$_REQUEST["fecha_inicio"];
        $fecha_fin=$_REQUEST["fecha_fin"];
        $rspta=$salidaprosucursal->listar($fecha_inicio,$fecha_fin);
        //Vamos a declarar un array 
        $data= Array();

        while ($reg=$rspta->fetch_object()){
                $url='../reportes/exSalidaProducto.php?id=';
                $url2='../reportes/exSalidaProducto_formatotermico_79mm.php?id=';
                $url3='../reportes/exSalidaProducto_formatotermico_58mm.php?id=';


            $data[]=array(
                "0"=>(($reg->estado=='SALIDA PRODUCTO')?
                        ' <button class="btn btn-danger" onclick="anular('.$reg->idtraladosucursal.')"><i class="fa fa-close"></i></button>'.
                        ' <button class="btn btn-warning" onclick="mostrar('.$reg->idtraladosucursal.')"><i class="fa fa-pencil"></i></button>'.
                        '<a target="_blank" href="'.$url.$reg->idtraladosucursal.'"> <button class="btn btn-info"><i class="fa fa-print"></i></button></a>'.
                        '<a target="_blank" title="Imprimir a 79mm" href="'.$url2.$reg->idtraladosucursal.'"> <button class="btn btn-info"><i class="fa fa-print"></i></button></a>'.
                        '<a target="_blank" title="Imprimir a 58mm" href="'.$url3.$reg->idtraladosucursal.'"> <button class="btn btn-info"><i class="fa fa-print"></i></button></a>':
                        '<a target="_blank" href="'.$url.$reg->idtraladosucursal.'"> <button class="btn btn-info"><i class="fa fa-print"></i></button></a>'.
                    '<a target="_blank" title="Imprimir a 79mm" href="'.$url2.$reg->idtraladosucursal.'"> <button class="btn btn-info"><i class="fa fa-print"></i></button></a>'.
                    '<a target="_blank" title="Imprimir a 58mm" href="'.$url3.$reg->idtraladosucursal.'"> <button class="btn btn-info"><i class="fa fa-print"></i></button></a>'),
                "1"=>$reg->idtraladosucursal,
                "2"=>$reg->fecha,
                "3"=>$reg->usuario,
                "4"=>$reg->nombresucursaldestino.' '.$reg->direccionsucursadestino,
                "5"=>$reg->nombresucursalorigen.' '.$reg->direccionsucursalorigen,
                "6"=>$reg->descripcion_salida_producto,
                "7"=>$reg->total_venta,
                "8"=>$reg->estado
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

        $rspta = $salidaprosucursal->selectSucursal();

        while ($reg = $rspta->fetch_object())
                {
                echo '<option value=' . $reg->idsucursal . '>' . $reg->nombre . ' ' . $reg->direccion . '</option>';
                }
    break;

    case 'listarArticulosVenta':
        $idsucursalOr = isset($_REQUEST["idsucursalOrigen"]) ? limpiarCadena($_REQUEST["idsucursalOrigen"]) : "";
        require_once "../modelos/Articulo.php";
        $articulo=new Articulo(); 

        $rspta=$articulo->listarActivosVentatrasladoxSucursal($idsucursalOr);
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<div class="input-group" style="display: inline-flex; margin-right: 5px;">
                        <input type="number" step="any" class="form-control input-sm" id="cantidad_'.$reg->idarticulo.'" 
                        style="width: 70px;" min="1" value="1">
                        <button class="btn btn-warning" onclick="agregarDetalleCanta('.$reg->idarticulo.',
                                                                            \''.$reg->nombre.'\',
                                                                            \''.$reg->descripcion.'\',
                                                                            \''.$reg->precio_venta.'\',
                                                                            \''.$reg->stock.'\',
                                                                            \''.$reg->descuento_porcentaje.'\',
                                                                            \''.$reg->nombre_01.'\',
                                                                            \''.$reg->stock_unidad.'\',
                                                                            \''.$reg->precio_unidad.'\',
                                                                            \''.$reg->nombre_02.'\',
                                                                            \''.$reg->stock_blister.'\',
                                                                            \''.$reg->precio_blister.'\',
                                                                            \''.$reg->nombre_03.'\',
                                                                            \''.$reg->stock_caja.'\',
                                                                            \''.$reg->precio_caja.'\',
                                                                            \''.$reg->nombre_04.'\',
                                                                            \''.$reg->stock_fardo.'\',
                                                                            \''.$reg->precio_fardo.'\',
                                                                            \''.$reg->nombre_05.'\',
                                                                            \''.$reg->stock_sacos.'\',
                                                                            \''.$reg->precio_sacos.'\',
                                                                            \''.$reg->nombre_06.'\',
                                                                            \''.$reg->stock_paquete.'\',
                                                                            \''.$reg->precio_paquete.'\',
                                                                            \''.$reg->nombre_07.'\',
                                                                            \''.$reg->stock_07.'\',
                                                                            \''.$reg->precio_07.'\',
                                                                            \''.$reg->nombre_08.'\',
                                                                            \''.$reg->stock_08.'\',
                                                                            \''.$reg->precio_08.'\',
                                                                            \''.$reg->nombre_09.'\',
                                                                            \''.$reg->stock_09.'\',
                                                                            \''.$reg->precio_09.'\',
                                                                            \''.$reg->nombre_10.'\',
                                                                            \''.$reg->stock_10.'\',
                                                                            \''.$reg->precio_10.'\',
                                                                            \''.$reg->nombre_11.'\',
                                                                            \''.$reg->stock_11.'\',
                                                                            \''.$reg->precio_11.'\',
                                                                            \''.$reg->nombre_12.'\',
                                                                            \''.$reg->stock_12.'\',
                                                                            \''.$reg->precio_12.'\',
                                                                            \''.$reg->nombre_13.'\',
                                                                            \''.$reg->stock_13.'\',
                                                                            \''.$reg->precio_13.'\',
                                                                            \''.$reg->nombre_14.'\',
                                                                            \''.$reg->stock_14.'\',
                                                                            \''.$reg->precio_14.'\',
                                                                            \''.$reg->nombre_15.'\',
                                                                            \''.$reg->stock_15.'\',
                                                                            \''.$reg->precio_15.'\',
                                                                            \''.$reg->nombre_16.'\',
                                                                            \''.$reg->stock_16.'\',
                                                                            \''.$reg->precio_16.'\',
                                                                            \''.$reg->nombre_17.'\',
                                                                            \''.$reg->stock_17.'\',
                                                                            \''.$reg->precio_17.'\',
                                                                            \''.$reg->nombre_18.'\',
                                                                            \''.$reg->stock_18.'\',
                                                                            \''.$reg->precio_18.'\',
                                                                            \''.$reg->nombre_19.'\',
                                                                            \''.$reg->stock_19.'\',
                                                                            \''.$reg->precio_19.'\',
                                                                            \''.$reg->nombre_20.'\',
                                                                            \''.$reg->stock_20.'\',
                                                                            \''.$reg->precio_20.'\',
                                                                            \''.$reg->facturar_cero.'\',
                                                                            document.getElementById(\'cantidad_'.$reg->idarticulo.'\').value)">
                            <span class="fa fa-plus"></span>
                        </button>
                    </div>',
                "1"=>$reg->nombre,
                "2"=>$reg->descripcion,
                "3"=>$reg->descripcion_2,
                "4"=>$reg->categoria,
                "5"=>$reg->codigo,
                "6"=>$reg->stock,
                "7"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >",
                "8"=>$reg->precio_venta
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

        $rspta=$salidaprosucursal->listarxfechasucursal($fecha_inicio,$fecha_fin,$idsucursal);
        //Vamos a declarar un array
        $data= Array();

        while ($reg=$rspta->fetch_object()){
                $url='../reportes/exSalidaProducto.php?id=';
            $data[]=array(
                "0"=>'<a target="_blank" href="'.$url.$reg->idtraladosucursal.'"> <button class="btn btn-info"><i class="fa fa-print"></i></button></a>',
                "1"=>$reg->idtraladosucursal,
                "2"=>$reg->fecha,
                "3"=>$reg->usuario,
                "4"=>$reg->nombresucursaldestino,
                "5"=>$reg->nombresucursalorigen,
                "6"=>$reg->descripcion_salida_producto,
                "7"=>$reg->total_venta,
                "8"=>$reg->estado
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

        $rspta=$salidaprosucursal->listarxfechasucursalDetalle($fecha_inicio,$fecha_fin,$idsucursal);
        //Vamos a declarar un array
        $data= Array();

        while ($reg=$rspta->fetch_object()){
            $data[]=array(

                "0"=>$reg->fecha,
                "1"=>$reg->idtraladosucursal,
                "2"=>$reg->codigo,
                "3"=>$reg->articulo,
                "4"=>$reg->cantidad,
                "5"=>$reg->precio_venta,
                "6"=>$reg->estado
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