<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Tienda_web_articulos.php";
 
$tiendawebarticulos=new Tiendawebarticulos();
 
$idarticulo=isset($_POST["idarticulo"])? limpiarCadena($_POST["idarticulo"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
$idcategoria=isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
$idsubcategoria=isset($_POST["idsubcategoria"])? limpiarCadena($_POST["idsubcategoria"]):"";
$descripcion_articulo=isset($_POST["descripcion_articulo"])? limpiarCadena($_POST["descripcion_articulo"]):"";
$tipo_promocion=isset($_POST["tipo_promocion"])? limpiarCadena($_POST["tipo_promocion"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";

$stock=isset($_POST["stock"])? limpiarCadena($_POST["stock"]):"";
$precio_compra=isset($_POST["precio_compra"])? limpiarCadena($_POST["precio_compra"]):"";
$stock_pv=isset($_POST["stock_pv"])? limpiarCadena($_POST["stock_pv"]):"";
$stock_pv_oferta=isset($_POST["stock_pv_oferta"])? limpiarCadena($_POST["stock_pv_oferta"]):"";

$meta_titulo=isset($_POST["meta_titulo"])? limpiarCadena($_POST["meta_titulo"]):"";
$meta_descripcion=isset($_POST["meta_descripcion"])? limpiarCadena($_POST["meta_descripcion"]):"";
$meta_keywords=isset($_POST["meta_keywords"])? limpiarCadena($_POST["meta_keywords"]):"";





 
switch ($_GET["op"]){
    case 'guardaryeditar':

        // Validar si se subió una nueva imagen
        if (!isset($_FILES['imagen']) || !file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
        {
            // Si no se subió imagen nueva, usar la actual
            $imagen = isset($_POST["imagenactual"]) ? $_POST["imagenactual"] : "";
        } 
        else
        {
            // Si se subió una nueva imagen, procesarla
            $ext = explode(".", $_FILES["imagen"]["name"]);
            if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
            {
                $imagen = round(microtime(true)) . '.' . end($ext);
                move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/articulos/" . $imagen);
            }
            else
            {
                // Si el tipo no es válido, usar la imagen actual
                $imagen = isset($_POST["imagenactual"]) ? $_POST["imagenactual"] : "";
            }
        }
        if (empty($idarticulo)){
            $idarticulonew=$tiendawebarticulos->insertar($nombre,$codigo,$idcategoria,$idsubcategoria,
            $descripcion_articulo,$tipo_promocion,$imagen,$stock, $precio_compra,$stock_pv,$stock_pv_oferta,
            $meta_titulo,$meta_descripcion,$meta_keywords);
            
            if ($idarticulonew) {
                // Guardar imágenes adicionales si se subieron
                if (!empty($_FILES['imagenes']['name'][0])) {
                    $carpeta = "../files/imagenesarticulos/";
                    if (!file_exists($carpeta)) mkdir($carpeta, 0777, true);
                    
                    foreach ($_FILES['imagenes']['tmp_name'] as $i => $tmp) {
                        if (!is_uploaded_file($tmp)) continue;
                        
                        $nombreArchivo = time() . '_' . $i . '_' . $_FILES['imagenes']['name'][$i];
                        $destino = $carpeta . $nombreArchivo;
                        
                        if (move_uploaded_file($tmp, $destino)) {
                            $rutaRelativa = "files/imagenesarticulos/" . $nombreArchivo;
                            $sqlInsert = "INSERT INTO imagenes_producto (idarticulo, ruta_imagen, orden) 
                                        VALUES ('$idarticulonew', '$rutaRelativa', '$i')";
                            ejecutarConsulta($sqlInsert);
                        }
                    }
                }
                echo "Articulo registrado";
            } else {
                echo "Articulo no se pudo registrar";
            }
        }
        else {
            // Eliminar imágenes marcadas para eliminación
            if (!empty($_POST['imagenes_eliminar'])) {
                foreach ($_POST['imagenes_eliminar'] as $idImagen) {
                    // Obtener la ruta de la imagen para eliminarla del servidor
                    $sqlGetRuta = "SELECT ruta_imagen FROM imagenes_producto WHERE idimagenes_producto = '" . limpiarCadena($idImagen) . "'";
                    $rsptaRuta = ejecutarConsultaSimpleFila($sqlGetRuta);
                    if ($rsptaRuta && !empty($rsptaRuta['ruta_imagen'])) {
                        $rutaCompleta = "../" . $rsptaRuta['ruta_imagen'];
                        if (file_exists($rutaCompleta)) {
                            unlink($rutaCompleta);
                        }
                    }
                    // Eliminar de la base de datos
                    $sqlDelete = "DELETE FROM imagenes_producto WHERE idimagenes_producto = '" . limpiarCadena($idImagen) . "'";
                    ejecutarConsulta($sqlDelete);
                }
            }
            
            $rspta=$tiendawebarticulos->editar($idarticulo,$nombre,$codigo,$idcategoria,$idsubcategoria,
            $descripcion_articulo,$tipo_promocion,$imagen,$stock, $precio_compra,$stock_pv,$stock_pv_oferta,
            $meta_titulo,$meta_descripcion,$meta_keywords);
            echo $rspta ? "Articulo actualizado" : "Articulo no se pudo actualizar";
        }
    break;
 
    case 'desactivar':
        $rspta=$tiendawebarticulos->desactivar($idarticulo);
        echo $rspta ? "Articulo Desactivado" : "Articulo no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$tiendawebarticulos->activar($idarticulo);
        echo $rspta ? "Articulo activado" : "Articulo no se puede activar";
        break;
    break;
 
    case 'mostrar':
        $rspta=$tiendawebarticulos->mostrar($idarticulo);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;
 
    case 'listar':
        $rspta=$tiendawebarticulos->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idarticulo.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idarticulo.')"><i class="fa fa-check"></i></button>',
                "1"=>$reg->nombre,
                "2"=>$reg->descripcion_articulo,
                "3"=>$reg->codigo,
                "4"=>$reg->categoria,
                "5"=>((!empty($reg->imagen) && file_exists("../files/articulos/".$reg->imagen))  ? "<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px'>" 
                            : "<img src='../files/articulos/nofoto.jpg' height='50px' width='50px'>"),                             
                "6"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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

    case 'obtenerImagenes':
        $rspta=$tiendawebarticulos->obtenerImagenes($idarticulo);
        $imagenes = array();
        while ($reg=$rspta->fetch_object()){
            $imagenes[]=array(
                "id"=>$reg->idimagenes_producto,
                "ruta"=>$reg->ruta_imagen,
                "orden"=>$reg->orden
            );
        }
        echo json_encode($imagenes);
    break;



}
?>