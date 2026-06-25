<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';
 
require_once "../modelos/Ingreso_produccion.php";
 
$ingresoproduccion=new Ingresoproduccion();
 
$idproduccion=isset($_POST["idproduccion"])? limpiarCadena($_POST["idproduccion"]):"";
$idusuario=$_SESSION["idusuario"];
$idproducto=isset($_POST["idproducto"])? limpiarCadena($_POST["idproducto"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";

$precio_compraProducto=isset($_POST["precio_compraProducto"])? limpiarCadena($_POST["precio_compraProducto"]):"";
$ganacia_producto=isset($_POST["ganacia_producto"])? limpiarCadena($_POST["ganacia_producto"]):"";
$precio_ventaProducto=isset($_POST["precio_ventaProducto"])? limpiarCadena($_POST["precio_ventaProducto"]):"";
$subtotalprecioCompra=isset($_POST["subtotalprecioCompra"])? limpiarCadena($_POST["subtotalprecioCompra"]):"";

 
$detalles_json = isset($_POST["detalles_json"]) ? json_decode($_POST["detalles_json"], true) : [];   
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idproduccion)){
            $rspta=$ingresoproduccion->insertar($idusuario,$idproducto,$fecha_hora,$precio_compraProducto,$ganacia_producto,
            $precio_ventaProducto,$subtotalprecioCompra,$detalles_json);
            echo $rspta;
        }
        else { 
            $rspta=$ingresoproduccion->editar($idproduccion,$idusuario,$idproducto,$fecha_hora,$precio_compraProducto,$ganacia_producto,
            $precio_ventaProducto,$subtotalprecioCompra,$detalles_json);
            echo $rspta;
        } 
    break; 
  
    case 'anular':
        $rspta=$ingresoproduccion->anular($idproduccion);
        echo $rspta ? "Ingreso produccion anulado" : "Ingreso produccion no se puede anular";
    break;
 
    case 'mostrar':
        $rspta=$ingresoproduccion->mostrar($idproduccion);
        //Codificar el resultado utilizando json 
        echo json_encode($rspta);
    break;

    case 'obtenerdetalle':
        $cotiz=$ingresoproduccion->obtenerdetalle($idproduccion);
        echo json_encode($cotiz); 
    break;       
 

 
    case 'listar':
        $rspta=$ingresoproduccion->listar(); 
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->estado=='Aceptado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idproduccion.')"><i class="fa fa-eye"></i></button>'.
                    ' <button class="btn btn-danger" onclick="anular('.$reg->idproduccion.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idproduccion.')"><i class="fa fa-eye"></i></button>',
                "1"=>$reg->idproduccion,
                "2"=>$reg->fecha,
                "3"=>$reg->usuario, 
                "4"=>$reg->nombrearticulo,
                "5"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
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
 
    case 'selectMateriaPrima':
        require_once "../modelos/Articulo.php";  
        $articulo = new Articulo(); 
  
        $rspta = $articulo->listarMateriaPrima();
 
        while ($reg = $rspta->fetch_object()) 
                {
                echo '<option data-precio_compra="'.$reg->precio_compra.'" data-precio_venta="'.$reg->precio_venta.'"   value=' . $reg->idarticulo . '>' . $reg->nombre . '-Stock-' . $reg->stock . '</option>';
                }
    break; 
 
    case 'listarArticulos':
        require_once "../modelos/Articulo.php";
        $articulo=new Articulo();
 
        $rspta=$articulo->listarActivosMateria();    
        //Vamos a declarar un array
        $data= Array(); 
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\',\''.$reg->precio_compra.'\',\''.$reg->precio_venta.'\')"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->nombre,
                "2"=>$reg->codigo,
                "3"=>$reg->stock,
                "4"=>$reg->precio_compra,
                "5"=>$reg->precio_venta
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
?>