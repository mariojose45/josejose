<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Articulo.php";
 
$articulo=new Articulo();  
      
$idarticulo=isset($_POST["idarticulo"])? limpiarCadena($_POST["idarticulo"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$idcategoria=isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$stock=isset($_POST["stock"])? limpiarCadena($_POST["stock"]):"";
$stockminimo=isset($_POST["stockminimo"])? limpiarCadena($_POST["stockminimo"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";
$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
$precio_venta=isset($_POST["precio_venta"])? limpiarCadena($_POST["precio_venta"]):"";
$descuento_porcentaje=isset($_POST["descuento_porcentaje"])? limpiarCadena($_POST["descuento_porcentaje"]):"";
$precio_descuento=isset($_POST["precio_descuento"])? limpiarCadena($_POST["precio_descuento"]):""; 
$tipo_producto=isset($_POST["tipo_producto"])? limpiarCadena($_POST["tipo_producto"]):""; 
$idusuario=$_SESSION["idusuario"];
$sucursaldestino=isset($_POST["idsucursal"])? limpiarCadena($_POST["idsucursal"]):"";
$cantidadt=isset($_POST["cantidadt"])? limpiarCadena($_POST["cantidadt"]):"";
 
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
        if (empty($idarticulo)){
            $rspta=$articulo->insertar($nombre,$idcategoria,$descripcion,$stock,$stockminimo,$imagen,$codigo,$precio_venta,$descuento_porcentaje,$precio_descuento,$tipo_producto,$idusuario,$_SESSION['idsucursal']);
            echo $rspta ? "Artículo registrado" : "Artículo no se pudo registrar";
        }
        else {
            $rspta=$articulo->editar($idarticulo,$nombre,$idcategoria,$descripcion,$stock,$stockminimo,$imagen,$codigo,$precio_venta,$descuento_porcentaje,$precio_descuento,$tipo_producto,$idusuario,$_SESSION['idsucursal']);
            echo $rspta ? "Artículo actualizado" : "Artículo no se pudo actualizar";
        }
    break;
 
    case 'desactivar':
        $rspta=$articulo->desactivar($idarticulo);
        echo $rspta ? "Artículo Desactivado" : "Artículo no se puede desactivar";
    break;
 
    case 'activar':
        $rspta=$articulo->activar($idarticulo);
        echo $rspta ? "Artículo activado" : "Artículo no se puede activar";
    break;
 
    case 'mostrar':
        $rspta=$articulo->mostrar($idarticulo);
        //Codificar el resultado utilizando json
        echo json_encode($rspta); 
    break;
  
    case 'listar':
        $rspta=$articulo->listar(); 
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<center><input type="checkbox" class="articulos" value="'.$reg->idarticulo.'@'.$reg->nombre.'" name="articulosSelecionados" /></center> ',
                "1"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-truck"></i> Trasladar</button>',
                "2"=>$reg->nombre,
                "3"=>$reg->categoria,
                "4"=>$reg->tipo_producto,
                "5"=>$reg->codigo,
                "6"=>$reg->stock, 
                "7"=>$reg->stockminimo,                
                "8"=>($reg->stockminimo <=$reg->stock )?'<span class="label bg-green">Stock Normal</span>':
                '<span class="label bg-red">Stock Bajo</span>',                                
                "9"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >",
                "10"=>$reg->precio_venta,                 
                "11"=>$reg->descuento_porcentaje,
                "12"=>$reg->precio_descuento,
                "13"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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
 
    case "selectCategoria":
        require_once "../modelos/Categoria.php";
        $categoria = new Categoria();
 
        $rspta = $categoria->select();
 
        while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idcategoria . '>' . $reg->nombre . '</option>';
                }
    break;
 

     case 'selectMarca':
        $rspta=$articulo->selectMarca(); 
        $dbdata = array();
        //Fetch into associative array
        while ( $row = $rspta->fetch_assoc())  {
            $dbdata[]=$row;
        }
        echo json_encode($dbdata);
    break; 

    case 'selectlinea':
        $rspta=$articulo->selectlinea($_POST["idmarca"]); 
        $dbdata = array();
        //Fetch into associative array
        while ( $row = $rspta->fetch_assoc())  {
            $dbdata[]=$row;
        } 
        echo json_encode($dbdata);
    break;   
    
    
    case 'trasladar':
        $rspta=$articulo->trasladar($sucursaldestino,$idarticulo,$cantidadt);
            echo $rspta ? "Artículo Trasladado" : "Artículo no se pudo Trasladar";
    break;

}
?> 