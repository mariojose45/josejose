<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Sucursal.php";
 
$sucursal=new Sucursal();
 
$idsucursal=isset($_POST["idsucursal"])? limpiarCadena($_POST["idsucursal"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$nit=isset($_POST["nit"])? limpiarCadena($_POST["nit"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";

$clave_ordenes=isset($_POST["clave_ordenes"])? limpiarCadena($_POST["clave_ordenes"]):"";
$clave_ingresos=isset($_POST["clave_ingresos"])? limpiarCadena($_POST["clave_ingresos"]):"";
$clave_ventas=isset($_POST["clave_ventas"])? limpiarCadena($_POST["clave_ventas"]):"";

$calculo_descuento=isset($_POST["calculo_descuento"])? limpiarCadena($_POST["calculo_descuento"]):"";
  
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
            {
                $imagen=$_POST["imagenactual"];
            }
        else
        {
            $ext = explode(".", $_FILES["imagen"]["name"]);
            if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
            {
                $imagen = round(microtime(true)) . '.' . end($ext);
                move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/articulos/" . $imagen);
            }
        }    
        if (empty($idsucursal)){
            $rspta=$sucursal->insertar($nombre,$direccion,$telefono,$nit,$email,$imagen,$clave_ordenes,$clave_ingresos,$clave_ventas,$calculo_descuento);
            echo $rspta ? "Sucursal registrada" : "Sucursal no se pudo registrar";
        }
        else {
            $rspta=$sucursal->editar($idsucursal,$nombre,$direccion,$telefono,$nit,$email,$imagen,$clave_ordenes,$clave_ingresos,$clave_ventas,$calculo_descuento);
            echo $rspta ? "Sucursal actualizada" : "Sucursal no se pudo actualizar";
        }
    break;
 
    case 'desactivar':
        $rspta=$sucursal->desactivar($idsucursal);
        echo $rspta ? "Sucursal Desactivada" : "Sucursal no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$sucursal->activar($idsucursal);
        echo $rspta ? "Sucursal activada" : "Sucursal no se puede activar";
    break;  
 
    case 'mostrar':
        $rspta=$sucursal->mostrar($idsucursal);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;
 
    case 'listar':
        $rspta=$sucursal->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idsucursal.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idsucursal.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idsucursal.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idsucursal.')"><i class="fa fa-check"></i></button>',
                "1"=>$reg->nombre,
                "2"=>$reg->direccion,
                "3"=>$reg->telefono,
                "4"=>$reg->nit,
                "5"=>$reg->email,
                "6"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >",
                "7"=>$reg->calculo_descuento,
                "8"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
                '<span class="label bg-red">Desactivado</span>'
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
        $rspta=$sucursal->listar(); 
        $dbdata = array();
        //Fetch into associative array
        while ( $row = $rspta->fetch_assoc())  {
            $dbdata[]=$row;
        }
        echo json_encode($dbdata);
    break; 

    case 'obtenerClaveOrdenes':
        $idsucursal = $_SESSION["idsucursal"];
        $rspta = $sucursal->obtenerClaveOrdenes($idsucursal);
        echo json_encode($rspta);
    break;

    case 'obtenerClaveIngresos':
        $idsucursal = $_SESSION["idsucursal"];
        $rspta = $sucursal->obtenerClaveIngresos($idsucursal);
        echo json_encode($rspta);
    break;
}
?>