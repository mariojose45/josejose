<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

 
require_once "../modelos/Ingreso_vehiculo.php";
     
$cotizaciones=new Ingreso_vehiculo();       
   
$idingreso_vehiculo=isset($_POST["idingreso_vehiculo"])? limpiarCadena($_POST["idingreso_vehiculo"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$codigo_cliente=isset($_POST["codigo_cliente"])? limpiarCadena($_POST["codigo_cliente"]):"";
$nit=isset($_POST["nit"])? limpiarCadena($_POST["nit"]):"";
$nombre_cliente=isset($_POST["nombre_cliente"])? limpiarCadena($_POST["nombre_cliente"]):"";
$telefono_cliente=isset($_POST["telefono_cliente"])? limpiarCadena($_POST["telefono_cliente"]):"";
$direccion_cliente=isset($_POST["direccion_cliente"])? limpiarCadena($_POST["direccion_cliente"]):"";
$correo_cliente=isset($_POST["correo_cliente"])? limpiarCadena($_POST["correo_cliente"]):"";
$tipo_cliente=isset($_POST["tipo_cliente"])? limpiarCadena($_POST["tipo_cliente"]):"";
$valor_tarjeta=isset($_POST["valor_tarjeta"])? limpiarCadena($_POST["valor_tarjeta"]):"";
$tipo_documento_cliente=isset($_POST["tipo_documento_cliente"])? limpiarCadena($_POST["tipo_documento_cliente"]):"";
$idusuario=$_SESSION["idusuario"]; 
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$forma_pago=isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$tipo_pagoBacVisaNet=isset($_POST["tipo_pagoBacVisaNet"])? limpiarCadena($_POST["tipo_pagoBacVisaNet"]):"";
$opcionesAdicionales=isset($_POST["opcionesAdicionales"])? limpiarCadena($_POST["opcionesAdicionales"]):"";
$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";
$total_ventades=isset($_POST["total_ventades"])? limpiarCadena($_POST["total_ventades"]):"";
$idvendedor=isset($_POST["idvendedor"])? limpiarCadena($_POST["idvendedor"]):"";

//
$no_placa=isset($_POST["no_placa"])? limpiarCadena($_POST["no_placa"]):"";
$no_chasis=isset($_POST["no_chasis"])? limpiarCadena($_POST["no_chasis"]):"";
$serie=isset($_POST["serie"])? limpiarCadena($_POST["serie"]):"";
$no_motor=isset($_POST["no_motor"])? limpiarCadena($_POST["no_motor"]):"";
$modelo=isset($_POST["modelo"])? limpiarCadena($_POST["modelo"]):"";
$km=isset($_POST["km"])? limpiarCadena($_POST["km"]):"";
$trabajos_detalle=isset($_POST["trabajos_detalle"])? limpiarCadena($_POST["trabajos_detalle"]):"";
$observaciones_adicionales=isset($_POST["observaciones_adicionales"])? limpiarCadena($_POST["observaciones_adicionales"]):"";

//imagenes
$descripcion1=isset($_POST["descripcion1"])? limpiarCadena($_POST["descripcion1"]):"";
$descripcion2=isset($_POST["descripcion2"])? limpiarCadena($_POST["descripcion2"]):"";
$descripcion3=isset($_POST["descripcion3"])? limpiarCadena($_POST["descripcion3"]):"";
$descripcion4=isset($_POST["descripcion4"])? limpiarCadena($_POST["descripcion4"]):"";
$descripcion5=isset($_POST["descripcion5"])? limpiarCadena($_POST["descripcion5"]):"";
$descripcion6=isset($_POST["descripcion6"])? limpiarCadena($_POST["descripcion6"]):"";
$descripcion7=isset($_POST["descripcion7"])? limpiarCadena($_POST["descripcion7"]):"";
$descripcion8=isset($_POST["descripcion8"])? limpiarCadena($_POST["descripcion8"]):"";


switch ($_GET["op"]){   
    case 'guardaryeditar':
        $revisiones_json = isset($_POST["revisiones_json"]) ? json_decode($_POST["revisiones_json"], true) : [];
        $nombres_imagenes = [];
        $ruta_archivos = "../files/articulos/";

        // Bucle para procesar las 8 imágenes
        for ($i = 1; $i <= 8; $i++) {
            $campo_imagen = 'imagen' . $i; // Nombre del campo file (imagen1, imagen2, etc.)
            $campo_actual = 'imagenactual' . $i; // Nombre del campo hidden (imagenactual1, etc.)
            $nombre_final_imagen = ''; // Variable para guardar el nombre de la imagen actual

            // 1. Verifica si se subió un nuevo archivo para este campo
            if (!isset($_FILES[$campo_imagen]) || !file_exists($_FILES[$campo_imagen]['tmp_name']) || !is_uploaded_file($_FILES[$campo_imagen]['tmp_name'])) {
                // Si no se subió una nueva imagen, usa el valor del campo hidden 'imagenactual'
                $nombre_final_imagen = isset($_POST[$campo_actual]) ? limpiarCadena($_POST[$campo_actual]) : "";
            } else {
                // Si se subió una nueva imagen, la procesamos
                $ext = explode(".", $_FILES[$campo_imagen]["name"]);
                $extension_archivo = end($ext);

                // 2. Valida el tipo de archivo
                $tipos_permitidos = ["image/jpg", "image/jpeg", "image/png"];
                if (in_array($_FILES[$campo_imagen]['type'], $tipos_permitidos)) {
                    
                    // 3. Crea un nombre único para el archivo y lo mueve a su destino
                    $nombre_final_imagen = round(microtime(true) * 1000) . '_' . $i . '.' . $extension_archivo;
                    move_uploaded_file($_FILES[$campo_imagen]["tmp_name"], $ruta_archivos . $nombre_final_imagen);

                } else {
                    // Si el tipo de archivo no es válido, se mantiene el valor actual (o vacío)
                    $nombre_final_imagen = isset($_POST[$campo_actual]) ? limpiarCadena($_POST[$campo_actual]) : "";
                }
            }
            
            // 4. Agrega el nombre de la imagen (nueva o actual) al array
            $nombres_imagenes[] = $nombre_final_imagen;
        }

        if (empty($idingreso_vehiculo)){ 
            $rspta=$cotizaciones->insertar($idcliente,$codigo_cliente,$nit,$nombre_cliente,$telefono_cliente,$direccion_cliente,$correo_cliente,
            $tipo_documento_cliente,
            $no_placa,$no_chasis,$serie,$no_motor,$modelo,$km,$trabajos_detalle,$observaciones_adicionales,$idvendedor,$tipo_cliente,
            $nombres_imagenes[0],
            $nombres_imagenes[1],
            $nombres_imagenes[2],
            $nombres_imagenes[3],
            $nombres_imagenes[4],
            $nombres_imagenes[5],
            $nombres_imagenes[6],
            $nombres_imagenes[7],
            $descripcion1,$descripcion2,$descripcion3,$descripcion4,$descripcion5,$descripcion6,$descripcion7,$descripcion8,$revisiones_json);
            echo $rspta;
        }
        else {
            $rspta=$cotizaciones->editar($idingreso_vehiculo, $idcliente,$codigo_cliente,$nit,$nombre_cliente,$telefono_cliente,$direccion_cliente,$correo_cliente,
            $tipo_documento_cliente,
            $no_placa,$no_chasis,$serie,$no_motor,$modelo,$km,$trabajos_detalle,$observaciones_adicionales,$idvendedor,$tipo_cliente,
            $nombres_imagenes[0],
            $nombres_imagenes[1],
            $nombres_imagenes[2],
            $nombres_imagenes[3],
            $nombres_imagenes[4],
            $nombres_imagenes[5],
            $nombres_imagenes[6],
            $nombres_imagenes[7],
            $descripcion1,$descripcion2,$descripcion3,$descripcion4,$descripcion5,$descripcion6,$descripcion7,$descripcion8,$revisiones_json);
            echo $rspta;
        }
    break;
 
    case 'listar':
        
        $fecha_inicio_reporte=$_REQUEST["fecha_inicio_reporte"]; 
        $fecha_fin_reporte=$_REQUEST["fecha_fin_reporte"];    

        $rspta=$cotizaciones->listar($fecha_inicio_reporte,$fecha_fin_reporte);
        //Vamos a declarar un array
        $data= Array();
  
        while ($reg=$rspta->fetch_object()){ 
            $url='../reportes/exIngresoVehiculov2.php?id=';

            $data[]=array(
                "0"=>($reg->estado=='Aceptado')?
            (($reg->facturado=='1')?
            '<a target="_blank" href="'.$url.$reg->idingreso_vehiculo.'" title="Doc"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>':
            '<button class="btn btn-warning btn-xl" onclick="mostrar('.$reg->idingreso_vehiculo.')"><i class="fa fa-pencil"></i></button> '.
            '<a target="_blank" href="'.$url.$reg->idingreso_vehiculo.'" title="Doc"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>'.
            '<button class="btn btn-danger btn-xl" onclick="anular('.$reg->idingreso_vehiculo.')"><i class="fa fa-close"></i></button>')
            :
            '<a target="_blank" href="'.$url.$reg->idingreso_vehiculo.'" title="Doc"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>',
                'N/A',
                "1"=>$reg->idingreso_vehiculo,
                "2"=>$reg->nombre_cliente,
                "3"=>$reg->nombre_usuario,
                "4"=>$reg->fecha,
                "5"=>$reg->no_placa,
                "6"=>$reg->no_chasis,
                "7"=>$reg->serie,
                "8"=>$reg->no_motor,
                "9"=>$reg->modelo,
                "10"=>$reg->km,
                "11"=>$reg->nombre_marca,
                "12"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
                '<span class="label bg-red">Anulado</span>',
                "13"=>($reg->facturado=='1')?'<span class="label bg-green">Facturado</span>':
                '<span class="label bg-yellow">No Facturado</span>'
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
        
    break;
    
 
    case 'selectCliente':
        require_once "../modelos/Persona.php";
        $persona = new Persona();
 
        $rspta = $persona->listarC();
 
        while ($reg = $rspta->fetch_object())
                {
                echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '--'.$reg->direccion.'</option>';
                }
    break;
    case "selectMarca":
        $rspta = $cotizaciones->selectMarca();
        echo '<option value="">Seleccione una Marca</option>';
        while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idmarca . '>' . $reg->nombre . ' -- ' . $reg->descripcion . '</option>';
                }
    break;

    case 'mostrar':
        $rspta=$cotizaciones->mostrar($idingreso_vehiculo);
        echo json_encode($rspta);
    break;

    case 'anular':
        $rspta=$cotizaciones->anular($idingreso_vehiculo);
        echo $rspta ? "Ingreso Vehiculo Desactivada" : "Ingreso Vehiculo no se puede desactivar";
        break;
    break;
}
?> 