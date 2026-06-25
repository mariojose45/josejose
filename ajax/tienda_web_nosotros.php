<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Tienda_web_nosotros.php";
 
$tiendawebNosotros=new Tiendawebnosotros();
 


// Usar limpiarCadenaConEmojis para preservar emojis y caracteres especiales
$idnosotros=isset($_POST["idnosotros"])? limpiarCadena($_POST["idnosotros"]):"";
$historia_empresa=isset($_POST["historia_empresa"])? limpiarCadenaConEmojis($_POST["historia_empresa"]):"";
$imagen_nosotros=isset($_POST["imagen_nosotros"])? limpiarCadena($_POST["imagen_nosotros"]):"";
$mision_nosotros=isset($_POST["mision_nosotros"])? limpiarCadenaConEmojis($_POST["mision_nosotros"]):"";
$vision_nosotros=isset($_POST["vision_nosotros"])? limpiarCadenaConEmojis($_POST["vision_nosotros"]):"";
$diferencia_nosotros=isset($_POST["diferencia_nosotros"])? limpiarCadenaConEmojis($_POST["diferencia_nosotros"]):"";


 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (!file_exists($_FILES['imagen_nosotros']['tmp_name']) || !is_uploaded_file($_FILES['imagen_nosotros']['tmp_name']))
            {
                $imagen_nosotros=$_POST["imagenactual_nosotros"];
            } 
        else
        {
            $ext = explode(".", $_FILES["imagen_nosotros"]["name"]);
            if ($_FILES['imagen_nosotros']['type'] == "image/jpg" || $_FILES['imagen_nosotros']['type'] == "image/jpeg" || $_FILES['imagen_nosotros']['type'] == "image/png")
            {
                $imagen_nosotros = round(microtime(true)) . '.' . end($ext);
                move_uploaded_file($_FILES["imagen_nosotros"]["tmp_name"], "../files/articulos/" . $imagen_nosotros);
            }
        }
            $rspta=$tiendawebNosotros->editar($idnosotros,$historia_empresa,$imagen_nosotros,
            $mision_nosotros,$vision_nosotros,$diferencia_nosotros);
            echo $rspta ? "Nosotros actualizado" : "Nosotros no se pudo actualizar";
            break;
 
    break;
 

 
    case 'mostrar':
        $rspta=$tiendawebNosotros->mostrar($idnosotros);
        // Decodificar HTML en los títulos y descripciones (por compatibilidad con datos antiguos)
        if ($rspta) {
            $rspta['historia_empresa'] = htmlspecialchars_decode($rspta['historia_empresa'], ENT_QUOTES);
            $rspta['imagen_nosotros'] = htmlspecialchars_decode($rspta['imagen_nosotros'], ENT_QUOTES);
            $rspta['mision_nosotros'] = htmlspecialchars_decode($rspta['mision_nosotros'], ENT_QUOTES);
            $rspta['vision_nosotros'] = htmlspecialchars_decode($rspta['vision_nosotros'], ENT_QUOTES);
            $rspta['diferencia_nosotros'] = htmlspecialchars_decode($rspta['diferencia_nosotros'], ENT_QUOTES);
        }
        //Codificar el resultado utilizando json con UTF-8
        ob_clean(); // Limpiar cualquier salida previa
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($rspta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit(); // Asegurar que no se envíe nada más
    break;

 
    case 'listar':
        $rspta=$tiendawebNosotros->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            // Los títulos ya no necesitan htmlspecialchars_decode si se guardaron sin htmlspecialchars
            // Pero lo mantenemos por compatibilidad con datos antiguos
            $data[]=array(
                "0"=>'<button class="btn btn-warning" title="Editar" onclick="mostrar('.$reg->idnosotros.')"><i class="fa fa-pencil"></i></button>',
                "1"=>htmlspecialchars_decode($reg->historia_empresa, ENT_QUOTES),
                "2"=>"<img src='../files/articulos/".$reg->imagen_nosotros."' height='50px' width='50px'>",
                "3"=>htmlspecialchars_decode($reg->mision_nosotros, ENT_QUOTES),
                "4"=>htmlspecialchars_decode($reg->vision_nosotros, ENT_QUOTES),
                "5"=>htmlspecialchars_decode($reg->diferencia_nosotros, ENT_QUOTES),
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