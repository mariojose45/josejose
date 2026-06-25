<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Tienda_web_servicios.php";
 
$tiendawebServicios=new TiendawebServicios();
 


// Usar limpiarCadenaConEmojis para preservar emojis y caracteres especiales
$idservicios=isset($_POST["idservicios"])? limpiarCadena($_POST["idservicios"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadenaConEmojis($_POST["nombre"]):"";
$tipo=isset($_POST["tipo"])? limpiarCadena($_POST["tipo"]):"";
$descripcion_servicio=isset($_POST["descripcion_servicio"])? limpiarCadenaConEmojis($_POST["descripcion_servicio"]):"";
$imagen_servicio=isset($_POST["imagen_servicio"])? limpiarCadena($_POST["imagen_servicio"]):"";

 
switch ($_GET["op"]){
    case 'guardaryeditar':
            if (!file_exists($_FILES['imagen_servicio']['tmp_name']) || !is_uploaded_file($_FILES['imagen_servicio']['tmp_name']))
                {
                    $imagen_servicio=$_POST["imagenactual_servicio"];
                } 
            else
            {
                $ext = explode(".", $_FILES["imagen_servicio"]["name"]);
                if ($_FILES['imagen_servicio']['type'] == "image/jpg" || $_FILES['imagen_servicio']['type'] == "image/jpeg" || $_FILES['imagen_servicio']['type'] == "image/png")
                {
                    $imagen_servicio = round(microtime(true)) . '.' . end($ext);
                    move_uploaded_file($_FILES["imagen_servicio"]["tmp_name"], "../files/articulos/" . $imagen_servicio);
                }
            }

            if (empty($idservicios)){
                $rspta=$tiendawebServicios->insertar($nombre,$tipo,$descripcion_servicio,$imagen_servicio);
                echo $rspta ? "Servicios registrada" : "Servicios no se pudo registrar";
            }
            else { 

            $rspta=$tiendawebServicios->editar($idservicios,$nombre,$tipo,$descripcion_servicio,$imagen_servicio);
            echo $rspta ? "Servicios actualizado" : "Servicios no se pudo actualizar";
            }
 
    break;


 

 
    case 'mostrar':
        $rspta=$tiendawebServicios->mostrar($idservicios);
        // Decodificar HTML en los títulos y descripciones (por compatibilidad con datos antiguos)
        if ($rspta) {
            $rspta['nombre'] = htmlspecialchars_decode($rspta['nombre'], ENT_QUOTES);
            $rspta['tipo'] = htmlspecialchars_decode($rspta['tipo'], ENT_QUOTES);
            $rspta['descripcion_servicio'] = htmlspecialchars_decode($rspta['descripcion_servicio'], ENT_QUOTES);
            $rspta['imagen_servicio'] = htmlspecialchars_decode($rspta['imagen_servicio'], ENT_QUOTES);
        }
        //Codificar el resultado utilizando json con UTF-8
        ob_clean(); // Limpiar cualquier salida previa
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($rspta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit(); // Asegurar que no se envíe nada más
    break;

 
    case 'listar':
        $rspta=$tiendawebServicios->listar();
        //Vamos a declarar un array
        $data= Array();

        if($rspta){
            while ($reg=$rspta->fetch_object()){
                // Los títulos ya no necesitan htmlspecialchars_decode si se guardaron sin htmlspecialchars
                // Pero lo mantenemos por compatibilidad con datos antiguos
                $data[]=array(
                    "0"=>'<button class="btn btn-warning" title="Editar" onclick="mostrar('.$reg->idservicios.')"><i class="fa fa-pencil"></i></button>',
                    "1"=>htmlspecialchars_decode($reg->nombre, ENT_QUOTES),
                    "2"=>"<img src='../files/articulos/".$reg->imagen_servicio."' height='50px' width='50px'>",
                    "3"=>htmlspecialchars_decode($reg->descripcion_servicio, ENT_QUOTES),
                    "4"=>htmlspecialchars_decode($reg->tipo, ENT_QUOTES),
                    );
            }
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