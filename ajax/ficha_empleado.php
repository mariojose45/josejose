<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Ficha_empleado.php";
 
$fichaempleado=new Fichaempleado();
  
$idficha_empleado=isset($_POST["idficha_empleado"])? limpiarCadena($_POST["idficha_empleado"]):"";
$cod_empleado=isset($_POST["cod_empleado"])? limpiarCadena($_POST["cod_empleado"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$dpi_no=isset($_POST["dpi_no"])? limpiarCadena($_POST["dpi_no"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$celular=isset($_POST["celular"])? limpiarCadena($_POST["celular"]):"";
$tipo_sexo=isset($_POST["tipo_sexo"])? limpiarCadena($_POST["tipo_sexo"]):"";
$estado_civil=isset($_POST["estado_civil"])? limpiarCadena($_POST["estado_civil"]):"";
$forma_pago=isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$fecha_nacimiento=isset($_POST["fecha_nacimiento"])? limpiarCadena($_POST["fecha_nacimiento"]):"";
$cta_no=isset($_POST["cta_no"])? limpiarCadena($_POST["cta_no"]):"";
$tipo_banco=isset($_POST["tipo_banco"])? limpiarCadena($_POST["tipo_banco"]):""; 
$sueldo_base=isset($_POST["sueldo_base"])? limpiarCadena($_POST["sueldo_base"]):""; 
$bonificacion=isset($_POST["bonificacion"])? limpiarCadena($_POST["bonificacion"]):""; 
$horaextra=isset($_POST["horaext"])? limpiarCadena($_POST["horaext"]):""; 
$fecha_ingreso=isset($_POST["fecha_ingreso"])? limpiarCadena($_POST["fecha_ingreso"]):"";  


$jefe_immediato_1=isset($_POST["jefe_immediato_1"])? limpiarCadena($_POST["jefe_immediato_1"]):""; 
$tiempo_trabajo_1=isset($_POST["tiempo_trabajo_1"])? limpiarCadena($_POST["tiempo_trabajo_1"]):""; 
$fecha_finalizacion_labora_1=isset($_POST["fecha_finalizacion_labora_1"])? limpiarCadena($_POST["fecha_finalizacion_labora_1"]):""; 
$telefono_1=isset($_POST["telefono_1"])? limpiarCadena($_POST["telefono_1"]):"";  


$jefe_immediato_2=isset($_POST["jefe_immediato_2"])? limpiarCadena($_POST["jefe_immediato_2"]):""; 
$tiempo_trabajo_2=isset($_POST["tiempo_trabajo_2"])? limpiarCadena($_POST["tiempo_trabajo_2"]):""; 
$fecha_finalizacion_labora_2=isset($_POST["fecha_finalizacion_labora_2"])? limpiarCadena($_POST["fecha_finalizacion_labora_2"]):""; 
$telefono_2=isset($_POST["telefono_2"])? limpiarCadena($_POST["telefono_2"]):""; 

$jefe_immediato_3=isset($_POST["jefe_immediato_3"])? limpiarCadena($_POST["jefe_immediato_3"]):""; 
$tiempo_trabajo_3=isset($_POST["tiempo_trabajo_3"])? limpiarCadena($_POST["tiempo_trabajo_3"]):""; 
$fecha_finalizacion_labora_3=isset($_POST["fecha_finalizacion_labora_3"])? limpiarCadena($_POST["fecha_finalizacion_labora_3"]):""; 
$telefono_3=isset($_POST["telefono_3"])? limpiarCadena($_POST["telefono_3"]):""; 

$nombre_refe_laboral_1=isset($_POST["nombre_refe_laboral_1"])? limpiarCadena($_POST["nombre_refe_laboral_1"]):""; 
$telefono_refe_laboral_1=isset($_POST["telefono_refe_laboral_1"])? limpiarCadena($_POST["telefono_refe_laboral_1"]):""; 
$parentesco_refe_laboral_1=isset($_POST["parentesco_refe_laboral_1"])? limpiarCadena($_POST["parentesco_refe_laboral_1"]):""; 
$nombre_refe_laboral_2=isset($_POST["nombre_refe_laboral_2"])? limpiarCadena($_POST["nombre_refe_laboral_2"]):""; 
$telefono_refe_laboral_2=isset($_POST["telefono_refe_laboral_2"])? limpiarCadena($_POST["telefono_refe_laboral_2"]):""; 
$parentesco_refe_laboral_2=isset($_POST["parentesco_refe_laboral_2"])? limpiarCadena($_POST["parentesco_refe_laboral_2"]):""; 

$nombre_refe_laboral_3=isset($_POST["nombre_refe_laboral_3"])? limpiarCadena($_POST["nombre_refe_laboral_3"]):""; 
$telefono_refe_laboral_3=isset($_POST["telefono_refe_laboral_3"])? limpiarCadena($_POST["telefono_refe_laboral_3"]):""; 
$parentesco_refe_laboral_3=isset($_POST["parentesco_refe_laboral_3"])? limpiarCadena($_POST["parentesco_refe_laboral_3"]):"";


$horaentrada=isset($_POST["horaentrada"])? limpiarCadena($_POST["horaentrada"]):""; 
$horarefaccion=isset($_POST["horarefaccion"])? limpiarCadena($_POST["horarefaccion"]):""; 
$horaalmuerzo=isset($_POST["horaalmuerzo"])? limpiarCadena($_POST["horaalmuerzo"]):""; 
$horasalida=isset($_POST["horasalida"])? limpiarCadena($_POST["horasalida"]):""; 

//$horaentrada=$_POST["horaentrada"];
//$horarefaccion=$_POST["horarefaccion"];
//$horaalmuerzo=$_POST["horaalmuerzo"];
//$horasalida=$_POST["horasalida"];
 

switch ($_GET["op"]){
    case 'guardaryeditar': 

        if (!file_exists($_FILES['dpi_Lado1_imagen']['tmp_name']) || !is_uploaded_file($_FILES['dpi_Lado1_imagen']['tmp_name']))
        {


                 $imagen=$_POST["imagenactual"];
        }    
 
        if(isset($_FILES["dpi_Lado1_imagen"]) && 
            !empty($_FILES["dpi_Lado1_imagen"]["name"])){

            $foto=$_FILES["dpi_Lado1_imagen"]["name"];
            $ext = pathinfo($foto, PATHINFO_EXTENSION);
            //$foto=$foto.".".$ext;
            $ruta=$_FILES["dpi_Lado1_imagen"]["tmp_name"];
            $dpi_Lado1_imagen="../files/ficha/".$foto;
            copy($ruta,$dpi_Lado1_imagen);
        }

        if(isset($_FILES["dpi_lado2_imagen"]) && 
            !empty($_FILES["dpi_lado2_imagen"]["name"])){

            $foto1=$_FILES["dpi_lado2_imagen"]["name"];
            $ruta1=$_FILES["dpi_lado2_imagen"]["tmp_name"];
            $dpi_lado2_imagen="../files/ficha/".$foto1;
            copy($ruta1,$dpi_lado2_imagen);
        }

        if(isset($_FILES["carta_recomendacion1_imagen"]) && 
            !empty($_FILES["carta_recomendacion1_imagen"]["name"])){

            $foto3=$_FILES["carta_recomendacion1_imagen"]["name"];
            $ruta3=$_FILES["carta_recomendacion1_imagen"]["tmp_name"];
            $carta_recomendacion1_imagen="../files/ficha/".$foto3;
            copy($ruta3,$carta_recomendacion1_imagen);
        }

        if(isset($_FILES["carta_recomendacion2_imagen"]) && 
            !empty($_FILES["carta_recomendacion2_imagen"]["name"])){

            $foto4=$_FILES["carta_recomendacion2_imagen"]["name"];
            $ruta4=$_FILES["carta_recomendacion2_imagen"]["tmp_name"];
            $carta_recomendacion2_imagen="../files/ficha/".$foto4;
            copy($ruta4,$carta_recomendacion2_imagen);
        }

        if(isset($_FILES["carta_recomendacion3_imagen"]) && 
            !empty($_FILES["carta_recomendacion3_imagen"]["name"])){

            $foto5=$_FILES["carta_recomendacion3_imagen"]["name"];
            $ruta5=$_FILES["carta_recomendacion3_imagen"]["tmp_name"];
            $carta_recomendacion3_imagen="../files/ficha/".$foto5;
            copy($ruta5,$carta_recomendacion3_imagen);
        }

        if(isset($_FILES["carta_trabajo1_imagen"]) && 
            !empty($_FILES["carta_trabajo1_imagen"]["name"])){

            $foto6=$_FILES["carta_trabajo1_imagen"]["name"];
            $ruta6=$_FILES["carta_trabajo1_imagen"]["tmp_name"];
            $carta_trabajo1_imagen="../files/ficha/".$foto6;
            copy($ruta6,$carta_trabajo1_imagen);
        }

        if(isset($_FILES["carta_trabajo2_imagen"]) && 
            !empty($_FILES["carta_trabajo2_imagen"]["name"])){

            $foto7=$_FILES["carta_trabajo2_imagen"]["name"];
            $ruta7=$_FILES["carta_trabajo2_imagen"]["tmp_name"];
            $carta_trabajo2_imagen="../files/ficha/".$foto7;
            copy($ruta7,$carta_trabajo2_imagen);
        }

        if(isset($_FILES["carta_trabajo3_imagen"]) && 
            !empty($_FILES["carta_trabajo3_imagen"]["name"])){

            $foto8=$_FILES["carta_trabajo3_imagen"]["name"];
            $ruta8=$_FILES["carta_trabajo3_imagen"]["tmp_name"];
            $carta_trabajo3_imagen="../files/ficha/".$foto8;
            copy($ruta8,$carta_trabajo3_imagen);
        }

        if (empty($idficha_empleado)){
            $rspta=$fichaempleado->insertar($cod_empleado,$nombre,$direccion,$dpi_no,$telefono,$celular,$tipo_sexo,$estado_civil,$forma_pago,$email,$fecha_nacimiento,$cta_no,$tipo_banco,$sueldo_base,$bonificacion,$fecha_ingreso,$jefe_immediato_1,$tiempo_trabajo_1,$fecha_finalizacion_labora_1,$telefono_1,$jefe_immediato_2,$tiempo_trabajo_2,$fecha_finalizacion_labora_2,$telefono_2,$jefe_immediato_3,$tiempo_trabajo_3,$fecha_finalizacion_labora_3,$telefono_3,$nombre_refe_laboral_1,$telefono_refe_laboral_1,$parentesco_refe_laboral_1,$nombre_refe_laboral_2,$telefono_refe_laboral_2,$parentesco_refe_laboral_2,$nombre_refe_laboral_3,$telefono_refe_laboral_3,$parentesco_refe_laboral_3,$dpi_Lado1_imagen,$dpi_lado2_imagen,$carta_recomendacion1_imagen,$carta_recomendacion2_imagen,$carta_recomendacion3_imagen,$carta_trabajo1_imagen,$carta_trabajo2_imagen,$carta_trabajo3_imagen,$horaentrada,$horarefaccion,$horaalmuerzo,$horasalida,$horaextra);
            echo $rspta ? "Ficha Empleado registrado" : "Ficha Empleado no se pudo registrar";
        }
        else {
            $rspta=$fichaempleado->editar($idficha_empleado,$cod_empleado,$nombre,$direccion,$dpi_no,$telefono,$celular,$tipo_sexo,$estado_civil,$forma_pago,$email,$fecha_nacimiento,$cta_no,$tipo_banco,$sueldo_base,$bonificacion,$fecha_ingreso,$jefe_immediato_1,$tiempo_trabajo_1,$fecha_finalizacion_labora_1,$telefono_1,$jefe_immediato_2,$tiempo_trabajo_2,$fecha_finalizacion_labora_2,$telefono_2,$jefe_immediato_3,$tiempo_trabajo_3,$fecha_finalizacion_labora_3,$telefono_3,$nombre_refe_laboral_1,$telefono_refe_laboral_1,$parentesco_refe_laboral_1,$nombre_refe_laboral_2,$telefono_refe_laboral_2,$parentesco_refe_laboral_2,$nombre_refe_laboral_3,$telefono_refe_laboral_3,$parentesco_refe_laboral_3,$dpi_Lado1_imagen,$horaentrada,$horarefaccion,$horaalmuerzo,$horasalida,$dpi_lado2_imagen,$carta_recomendacion1_imagen,$carta_recomendacion2_imagen,$carta_recomendacion3_imagen,$carta_trabajo1_imagen,$carta_trabajo2_imagen,$carta_trabajo3_imagen,$horaextra);
            echo $rspta ? "Ficha actualizada" : "Ficha no se pudo actualizar";
        }
    break;
 
    case 'desactivar':
        $rspta=$fichaempleado->desactivar($idficha_empleado);
        echo $rspta ? "Artículo Desactivado" : "Artículo no se puede desactivar";
    break;
 
    case 'activar':
        $rspta=$fichaempleado->activar($idficha_empleado);
        echo $rspta ? "Artículo activado" : "Artículo no se puede activar";
    break;
 
    case 'mostrar':
        $rspta=$fichaempleado->mostrar($idficha_empleado);
        //Codificar el resultado utilizando json
        echo json_encode($rspta); 
    break;
 
    case 'listar':
        $rspta=$fichaempleado->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idficha_empleado.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idficha_empleado.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idficha_empleado.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idficha_empleado.')"><i class="fa fa-check"></i></button>',
                "1"=>$reg->cod_empleado,
                "2"=>$reg->nombre,
                "3"=>$reg->direccion,
                "4"=>$reg->dpi_no,
                "5"=>$reg->telefono, 
                "6"=>$reg->celular,
                "7"=>$reg->cta_no,
                "8"=>$reg->tipo_banco,
                "9"=>$reg->sueldo_base,
                "10"=>$reg->bonificacion,
                "11"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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
 

}
?> 