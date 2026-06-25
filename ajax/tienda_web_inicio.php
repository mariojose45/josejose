<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/tienda_web_inicio.php";
 
$tiendawebInicio=new Tiendawebinicio();
 
$idinicio=isset($_POST["idinicio"])? limpiarCadena($_POST["idinicio"]):"";

// Usar limpiarCadenaConEmojis para preservar emojis y caracteres especiales
$titulo_1=isset($_POST["titulo_1"])? limpiarCadenaConEmojis($_POST["titulo_1"]):"";
$sub_titulo_1=isset($_POST["sub_titulo_1"])? limpiarCadenaConEmojis($_POST["sub_titulo_1"]):"";
$descripcion_titulo_1=isset($_POST["descripcion_titulo_1"])? limpiarCadenaConEmojis($_POST["descripcion_titulo_1"]):"";
$imagen_1=isset($_POST["imagen_1"])? limpiarCadena($_POST["imagen_1"]):"";

$titulo_2=isset($_POST["titulo_2"])? limpiarCadenaConEmojis($_POST["titulo_2"]):"";
$sub_titulo_2=isset($_POST["sub_titulo_2"])? limpiarCadenaConEmojis($_POST["sub_titulo_2"]):"";
$descripcion_titulo_2=isset($_POST["descripcion_titulo_2"])? limpiarCadenaConEmojis($_POST["descripcion_titulo_2"]):"";
$imagen_2=isset($_POST["imagen_2"])? limpiarCadena($_POST["imagen_2"]):"";

$titulo_3=isset($_POST["titulo_3"])? limpiarCadenaConEmojis($_POST["titulo_3"]):"";
$sub_titulo_3=isset($_POST["sub_titulo_3"])? limpiarCadenaConEmojis($_POST["sub_titulo_3"]):"";
$descripcion_titulo_3=isset($_POST["descripcion_titulo_3"])? limpiarCadenaConEmojis($_POST["descripcion_titulo_3"]):"";
$imagen_3=isset($_POST["imagen_3"])? limpiarCadena($_POST["imagen_3"]):"";

 
switch ($_GET["op"]){
    case 'guardaryeditar':
        // Procesar imagen_1
        if (isset($_FILES['imagen_1']) && file_exists($_FILES['imagen_1']['tmp_name']) && is_uploaded_file($_FILES['imagen_1']['tmp_name']))
        {
            $ext = explode(".", $_FILES["imagen_1"]["name"]);
            if ($_FILES['imagen_1']['type'] == "image/jpg" || $_FILES['imagen_1']['type'] == "image/jpeg" || $_FILES['imagen_1']['type'] == "image/png")
            {
                $imagen_1 = round(microtime(true)) . '_1.' . end($ext);
                move_uploaded_file($_FILES["imagen_1"]["tmp_name"], "../files/articulos/" . $imagen_1);
            } else {
                $imagen_1 = isset($_POST["imagenactual_1"]) ? $_POST["imagenactual_1"] : "";
            }
        } else {
            $imagen_1 = isset($_POST["imagenactual_1"]) ? $_POST["imagenactual_1"] : "";
        }

        // Procesar imagen_2
        if (isset($_FILES['imagen_2']) && file_exists($_FILES['imagen_2']['tmp_name']) && is_uploaded_file($_FILES['imagen_2']['tmp_name']))
        {
            $ext = explode(".", $_FILES["imagen_2"]["name"]);
            if ($_FILES['imagen_2']['type'] == "image/jpg" || $_FILES['imagen_2']['type'] == "image/jpeg" || $_FILES['imagen_2']['type'] == "image/png")
            {
                $imagen_2 = round(microtime(true)) . '_2.' . end($ext);
                move_uploaded_file($_FILES["imagen_2"]["tmp_name"], "../files/articulos/" . $imagen_2);
            } else {
                $imagen_2 = isset($_POST["imagenactual_2"]) ? $_POST["imagenactual_2"] : "";
            }
        } else {
            $imagen_2 = isset($_POST["imagenactual_2"]) ? $_POST["imagenactual_2"] : "";
        }

        // Procesar imagen_3
        if (isset($_FILES['imagen_3']) && file_exists($_FILES['imagen_3']['tmp_name']) && is_uploaded_file($_FILES['imagen_3']['tmp_name']))
        {
            $ext = explode(".", $_FILES["imagen_3"]["name"]);
            if ($_FILES['imagen_3']['type'] == "image/jpg" || $_FILES['imagen_3']['type'] == "image/jpeg" || $_FILES['imagen_3']['type'] == "image/png")
            {
                $imagen_3 = round(microtime(true)) . '_3.' . end($ext);
                move_uploaded_file($_FILES["imagen_3"]["tmp_name"], "../files/articulos/" . $imagen_3);
            } else {
                $imagen_3 = isset($_POST["imagenactual_3"]) ? $_POST["imagenactual_3"] : "";
            }
        } else {
            $imagen_3 = isset($_POST["imagenactual_3"]) ? $_POST["imagenactual_3"] : "";
        }

        $rspta=$tiendawebInicio->editar($idinicio,
            $titulo_1,$sub_titulo_1,$descripcion_titulo_1,$imagen_1,
            $titulo_2,$sub_titulo_2,$descripcion_titulo_2,$imagen_2,
            $titulo_3,$sub_titulo_3,$descripcion_titulo_3,$imagen_3);
 
    break;
 
    case 'desactivar':
        $rspta=$tiendawebInicio->desactivar($idinicio);
        echo $rspta ? "Inicio Desactivado" : "Inicio no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$tiendawebInicio->activar($idinicio);
        echo $rspta ? "Inicio activado" : "Inicio no se puede activar";
        break;
    break;
 
    case 'mostrar':
        $rspta=$tiendawebInicio->mostrar($idinicio);
        // Decodificar HTML en los títulos y descripciones (por compatibilidad con datos antiguos)
        if ($rspta) {
            $rspta['titulo_1'] = htmlspecialchars_decode($rspta['titulo_1'], ENT_QUOTES);
            $rspta['sub_titulo_1'] = htmlspecialchars_decode($rspta['sub_titulo_1'], ENT_QUOTES);
            $rspta['descripcion_titulo_1'] = htmlspecialchars_decode($rspta['descripcion_titulo_1'], ENT_QUOTES);
            $rspta['titulo_2'] = htmlspecialchars_decode($rspta['titulo_2'], ENT_QUOTES);
            $rspta['sub_titulo_2'] = htmlspecialchars_decode($rspta['sub_titulo_2'], ENT_QUOTES);
            $rspta['descripcion_titulo_2'] = htmlspecialchars_decode($rspta['descripcion_titulo_2'], ENT_QUOTES);
            $rspta['titulo_3'] = htmlspecialchars_decode($rspta['titulo_3'], ENT_QUOTES);
            $rspta['sub_titulo_3'] = htmlspecialchars_decode($rspta['sub_titulo_3'], ENT_QUOTES);
            $rspta['descripcion_titulo_3'] = htmlspecialchars_decode($rspta['descripcion_titulo_3'], ENT_QUOTES);
        }
        //Codificar el resultado utilizando json con UTF-8
        ob_clean(); // Limpiar cualquier salida previa
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($rspta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit(); // Asegurar que no se envíe nada más
    break;

 
    case 'listar':
        $rspta=$tiendawebInicio->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            // Los títulos ya no necesitan htmlspecialchars_decode si se guardaron sin htmlspecialchars
            // Pero lo mantenemos por compatibilidad con datos antiguos
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" title="Editar" onclick="mostrar('.$reg->idinicio.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" title="Desactivar" onclick="desactivar('.$reg->idinicio.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" title="Editar" onclick="mostrar('.$reg->idinicio.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" title="Activar" onclick="activar('.$reg->idinicio.')"><i class="fa fa-check"></i></button>',
                "1"=>htmlspecialchars_decode($reg->titulo_1, ENT_QUOTES),
                "2"=>htmlspecialchars_decode($reg->sub_titulo_1, ENT_QUOTES),
                "3"=>htmlspecialchars_decode($reg->descripcion_titulo_1, ENT_QUOTES),
                "4"=>"<img src='../files/articulos/".$reg->imagen_1."' height='50px' width='50px'>",
                "5"=>htmlspecialchars_decode($reg->titulo_2, ENT_QUOTES),
                "6"=>htmlspecialchars_decode($reg->sub_titulo_2, ENT_QUOTES),
                "7"=>htmlspecialchars_decode($reg->descripcion_titulo_2, ENT_QUOTES),
                "8"=>"<img src='../files/articulos/".$reg->imagen_2."' height='50px' width='50px'>",
                "9"=>htmlspecialchars_decode($reg->titulo_3, ENT_QUOTES),
                "10"=>htmlspecialchars_decode($reg->sub_titulo_3, ENT_QUOTES),
                "11"=>htmlspecialchars_decode($reg->descripcion_titulo_3, ENT_QUOTES),
                "12"=>"<img src='../files/articulos/".$reg->imagen_3."' height='50px' width='50px'>",
                "13"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
                '<span class="label bg-red">Desactivado</span>'
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        // Asegurar que el JSON se envíe con UTF-8
        ob_clean(); // Limpiar cualquier salida previa
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($results, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit(); // Asegurar que no se envíe nada más
 
    break;


}
?>