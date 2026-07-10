<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Persona.php";
 
$persona=new Persona();
 
$idpersona=isset($_POST["idpersona"])? limpiarCadena($_POST["idpersona"]):"";
$tipo_persona=isset($_POST["tipo_persona"])? limpiarCadena($_POST["tipo_persona"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$nombre_comercial=isset($_POST["nombre_comercial"])? limpiarCadena($_POST["nombre_comercial"]):"";
$tipo_documento=isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
$num_documento=isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$direccion_comercial=isset($_POST["direccion_comercial"])? limpiarCadena($_POST["direccion_comercial"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$trabajo=isset($_POST["trabajo"])? limpiarCadena($_POST["trabajo"]):"";
$idsector=isset($_POST["idsector"])? limpiarCadena($_POST["idsector"]):"";
$idruta=isset($_POST["idruta"])? limpiarCadena($_POST["idruta"]):"";
$tipo_cliente=isset($_POST["tipo_cliente"])? limpiarCadena($_POST["tipo_cliente"]):"";
$codigo_cliente=isset($_POST["codigo_cliente"])? limpiarCadena($_POST["codigo_cliente"]):"";
$ubicacioncliente=isset($_POST["ubicacioncliente"])? limpiarCadena($_POST["ubicacioncliente"]):"";
$descuento_cliente=isset($_POST["descuento_cliente"])? limpiarCadena($_POST["descuento_cliente"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar': 
        if (empty($idpersona)){
            $rspta=$persona->insertar($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$tipo_cliente,$trabajo,$idsector,$descuento_cliente);
            echo $rspta ? "Persona registrada" : "Persona no se pudo registrar";
        }
        else {
            $rspta=$persona->editar($idpersona,$tipo_persona,$nombre,$tipo_documento,
                $num_documento,$direccion,$telefono,$email,$tipo_cliente,$ubicacioncliente,$trabajo,$idsector,$descuento_cliente);
            echo $rspta ? "Persona actualizada" : "Persona no se pudo actualizar";
        }
    break;


    case 'guardaryeditarCliente': 
        if (empty($idpersona)){
            $rspta=$persona->insertarCliente($tipo_persona,$nombre,$nombre_comercial,$tipo_documento,$num_documento,$direccion,
            $direccion_comercial,$telefono,$email,$trabajo,$idsector,$idruta,$tipo_cliente,$codigo_cliente,$ubicacioncliente,$descuento_cliente);
            echo $rspta ? "Persona registrada" : "Persona no se pudo registrar";
        }
        else {
            $rspta=$persona->editarCliente($idpersona,$tipo_persona,$nombre,$nombre_comercial,$tipo_documento,$num_documento,$direccion,
            $direccion_comercial,$telefono,$email,$trabajo,$idsector,$idruta,$tipo_cliente,$codigo_cliente,$ubicacioncliente,$descuento_cliente);
            echo $rspta ? "Persona actualizada" : "Persona no se pudo actualizar";
        }
    break;    
 
    case 'guardaryeditar2': 
        if (empty($idpersona)){
            $rspta=$persona->insertar2($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$tipo_cliente);
            echo $rspta; 
        }
        else {
            $rspta=$persona->editar($idpersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email);
            echo $rspta ? "Persona actualizada" : "Persona no se pudo actualizar";
        }
    break;   
    case 'guardaryeditar3': 
        if (empty($idpersona)){
            $rspta=$persona->insertar3($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$tipo_cliente);
            echo $rspta; 
        }
        else {
            $rspta=$persona->editar($idpersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email);
            echo $rspta ? "Persona actualizada" : "Persona no se pudo actualizar";
        }
    break;         
 
    case 'guardaryeditarSeguimiento':
        $idcliente_seguimiento=$_REQUEST["idcliente_seguimiento"];
        $tipo_seguimiento=$_REQUEST["tipo_seguimiento"];
        $notas_seguimiento=$_REQUEST["notas_seguimiento"];

        $rspta=$persona->guardaryeditarSeguimiento($idcliente_seguimiento,$tipo_seguimiento,$notas_seguimiento);
        echo $rspta;
    break;

    case 'guardaryeditarTarea':
        $idcliente_tarea=$_REQUEST["idcliente_tarea"];
        $tarea_titulo=$_REQUEST["tarea_titulo"];
        $tarea_desc=$_REQUEST["tarea_desc"];
        $tarea_fecha_limite=$_REQUEST["tarea_fecha_limite"];
        $tarea_prioridad=$_REQUEST["tarea_prioridad"];

        $rspta=$persona->guardaryeditarTarea($idcliente_tarea,$tarea_titulo,$tarea_desc,$tarea_fecha_limite,$tarea_prioridad);
        echo $rspta;
    break;

    case 'guardaryeditarEvento':
        $idcliente_evento=$_REQUEST["idcliente_evento"];
        $evento_titulo=$_REQUEST["evento_titulo"];
        $evento_desc=$_REQUEST["evento_desc"];
        $evento_fecha_evento=$_REQUEST["evento_fecha_evento"];
        $evento_ubicacion=$_REQUEST["evento_ubicacion"];

        $rspta=$persona->guardaryeditarEvento($idcliente_evento,$evento_titulo,$evento_desc,$evento_fecha_evento,$evento_ubicacion);
        echo $rspta;
    break;

    case 'eliminar':
        $rspta=$persona->eliminar($idpersona);
        echo $rspta ? "Persona Desactivda" : "Persona no se puedo Desactivar";
    break;
 
    case 'mostrar':
        $rspta=$persona->mostrar($idpersona);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;
 
    case 'listarp':
        $rspta=$persona->listarp();
        //Vamos a declarar un array
        $data= Array(); 
  
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash"></i></button>',
                "1"=>$reg->nombre,
                "2"=>$reg->tipo_documento,
                "3"=>$reg->num_documento,
                "4"=>$reg->telefono,
                "5"=>$reg->email,
                "6"=>$reg->direccion,
                "7"=>$reg->codigo_cliente
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;
 
    case 'listarc':
        $rspta=$persona->listarc();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash"></i></button>'.
                    ' <button class="btn btn-info" onclick="addFiador('.$reg->idpersona.')"><i class="fa fa-plus"></i></button>',
                "1"=>$reg->nombre,
                "2"=>$reg->tipo_documento,
                "3"=>$reg->num_documento,
                "4"=>$reg->telefono,
                "5"=>$reg->email,
                "6"=>$reg->direccion,
                "7"=>$reg->codigo_cliente,
                "8"=>$reg->tipo_cliente,
                "9"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
                '<span class="label bg-red">Desactivado</span>',
                "10"=>'<a href="https://www.google.com/maps?q='.$reg->ubicacion_maps.'" target="_blank" class="btn btn-info">Ver en Maps</a>',
                "11"=>$reg->descuento_cliente
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;
 
    case 'listarseguimiento':
        $rspta=$persona->listarc();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'
                <div class="btn-group">
                <div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-user"></i>
                    </button>
                    <ul class="dropdown-menu">
                    <li><a onclick="seguimiento('.$reg->idpersona.')">Seguimiento</a></li>
                    <li><a onclick="tareas('.$reg->idpersona.')">Tareas</a></li>
                    <li><a onclick="eventos('.$reg->idpersona.')">Eventos</a></li>
                    </ul>
                </div>
                </div>',
                "1"=>$reg->nombre,
                "2"=>$reg->tipo_documento,
                "3"=>$reg->num_documento,
                "4"=>$reg->telefono,
                "5"=>$reg->email,
                "6"=>$reg->direccion,
                "7"=>$reg->codigo_cliente,
                "8"=>$reg->tipo_cliente,
                "9"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
                '<span class="label bg-red">Desactivado</span>',
                "10"=>'<a href="https://www.google.com/maps?q='.$reg->ubicacion_maps.'" target="_blank" class="btn btn-info">Ver en Maps</a>',
                "11"=>$reg->t_vendido,
                "12"=>$reg->f_ultima_venta,
                "13"=>$reg->f_ultimo_seguimiento,
                "14"=>$reg->f_ultima_tarea,
                "15"=>$reg->f_ultimo_evento
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
        $rspta = $persona->listarclientes();
 
        while ($reg = $rspta->fetch_object())
                {
                echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '--'.$reg->direccion.'--'.$reg->num_documento.'</option>';
                }
    break;

    case 'selectSector':
        $rspta = $persona->selectSector();
 
        echo '<option value="">Seleccione un Sector</option>';
        while ($reg = $rspta->fetch_object())
                {
                echo '<option value=' . $reg->idsector . '>' . $reg->nombre . '--'.$reg->descripcion.'</option>';
                }
    break;

    case 'selectRuta':
        $rspta = $persona->selectRuta();
 
        echo '<option value="">Seleccione una Ruta</option>';
        while ($reg = $rspta->fetch_object())
                {
                echo '<option value=' . $reg->idruta . '>' . $reg->nombre . '--'.$reg->descripcion.'</option>';
                }
    break;

    case 'listar_seguimiento_reporte':
        $fecha_inicio_reporte=$_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte=$_REQUEST["fecha_fin_reporte"];
        $idcliente=$_REQUEST["idcliente"];
        $rspta=$persona->listar_seguimiento_reporte($fecha_inicio_reporte,$fecha_fin_reporte,$idcliente);
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->fecha,
                "1"=>$reg->tipo,
                "2"=>$reg->notas,
                "3"=>$reg->cliente,
                "4"=>$reg->usuario,
                "5"=>$reg->sucursal
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
    break;


    case 'listar_seguimiento_reporte_rango':
        $fecha_inicio_reporte=$_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte=$_REQUEST["fecha_fin_reporte"];
        $rspta=$persona->listar_seguimiento_reporte_rango($fecha_inicio_reporte,$fecha_fin_reporte);
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->fecha,
                "1"=>$reg->tipo,
                "2"=>$reg->notas,
                "3"=>$reg->cliente,
                "4"=>$reg->usuario,
                "5"=>$reg->sucursal
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
    break;    

    case 'listar_tareas_reporte':
        $fecha_inicio_reporte=$_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte=$_REQUEST["fecha_fin_reporte"];
        $idcliente=$_REQUEST["idcliente"];
        $rspta=$persona->listar_tareas_reporte($fecha_inicio_reporte,$fecha_fin_reporte,$idcliente);
        $data = array();
        while ($reg = $rspta->fetch_object()) {
            // Etiqueta de prioridad con color
            $prioridad_color = '';
            switch (strtolower($reg->prioridad)) {
                case 'alta':
                    $prioridad_color = '<span class="badge badge-danger">Alta</span>';
                    break;
                case 'media':
                    $prioridad_color = '<span class="badge badge-warning">Media</span>';
                    break;
                case 'baja':
                    $prioridad_color = '<span class="badge badge-success">Baja</span>';
                    break;
                default:
                    $prioridad_color = '<span class="badge badge-secondary">Sin prioridad</span>';
            }

            // Estado de completado
            $estado = ($reg->completado == 1)
                ? '<span class="badge badge-primary">Completado</span>'
                : '<span class="badge badge-secondary">Pendiente</span>';

            // Arreglo de datos para DataTable
            $data[] = array(
                "0" => $reg->titulo,
                "1" => $reg->descripcion,
                "2" => $reg->fecha_limite,
                "3" => $reg->fecha,
                "4" => $prioridad_color,
                "5" => $reg->cliente,
                "6" => $reg->usuario,
                "7" => $reg->sucursal,
                "8" => $estado 
            );
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        echo json_encode($results);
    break;


    case 'listar_tareas_reporte_rango':
        $fecha_inicio_reporte=$_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte=$_REQUEST["fecha_fin_reporte"];
        $rspta=$persona->listar_tareas_reporte_rango($fecha_inicio_reporte,$fecha_fin_reporte);
        $data = array();
        while ($reg = $rspta->fetch_object()) {
            // Etiqueta de prioridad con color
            $prioridad_color = '';
            switch (strtolower($reg->prioridad)) {
                case 'alta':
                    $prioridad_color = '<span class="badge badge-danger">Alta</span>';
                    break;
                case 'media':
                    $prioridad_color = '<span class="badge badge-warning">Media</span>';
                    break;
                case 'baja':
                    $prioridad_color = '<span class="badge badge-success">Baja</span>';
                    break;
                default:
                    $prioridad_color = '<span class="badge badge-secondary">Sin prioridad</span>';
            }

            // Estado de completado
            $estado = ($reg->completado == 1)
                ? '<span class="badge badge-primary">Completado</span>'
                : '<span class="badge badge-secondary">Pendiente</span>';

            // Arreglo de datos para DataTable
            $data[] = array(
                "0" => $reg->titulo,
                "1" => $reg->descripcion,
                "2" => $reg->fecha_limite,
                "3" => $reg->fecha,
                "4" => $prioridad_color,
                "5" => $reg->cliente,
                "6" => $reg->usuario,
                "7" => $reg->sucursal,
                "8" => $estado 
            );
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        echo json_encode($results);
    break;    

    case 'listar_eventos_reporte':
        $fecha_inicio_reporte=$_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte=$_REQUEST["fecha_fin_reporte"];
        $idcliente=$_REQUEST["idcliente"];
        $rspta=$persona->listar_eventos_reporte($fecha_inicio_reporte,$fecha_fin_reporte,$idcliente);
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->titulo,
                "1"=>$reg->descripcion,
                "2"=>$reg->fecha,
                "3"=>$reg->cliente,
                "4"=>$reg->usuario,
                "5"=>$reg->sucursal
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
    break;


    case 'listar_eventos_reporte_rango':
        $fecha_inicio_reporte=$_REQUEST["fecha_inicio_reporte"];
        $fecha_fin_reporte=$_REQUEST["fecha_fin_reporte"];
        $rspta=$persona->listar_eventos_reporte_rango($fecha_inicio_reporte,$fecha_fin_reporte);
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->titulo,
                "1"=>$reg->descripcion,
                "2"=>$reg->fecha,
                "3"=>$reg->cliente,
                "4"=>$reg->usuario,
                "5"=>$reg->sucursal
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
    break;    

    case 'agregar_fiador':
        $idpersona_fiador=$_REQUEST["idpersona_fiador"];
        $idfiador=$_REQUEST["idfiador"];
        $rspta=$persona->agregar_fiador($idpersona_fiador,$idfiador);
        echo $rspta;
    break;

    case 'guardaryeditarModal':  
        $tipo_persona_cliente=$_REQUEST["tipo_persona_cliente"];
        $nombre_cliente=$_REQUEST["nombre_cliente"];
        $tipo_documento_cliente=$_REQUEST["tipo_documento_cliente"];
        $num_documento_cliente=$_REQUEST["num_documento_cliente"];
        $direccion_cliente=$_REQUEST["direccion_cliente"];
        $telefono_cliente=$_REQUEST["telefono_cliente"];
        $email_cliente=$_REQUEST["email_cliente"];
        $tipo_cliente_cliente=$_REQUEST["tipo_cliente_cliente"];
            $rspta=$persona->guardaryeditarModal($tipo_persona_cliente,$nombre_cliente,$tipo_documento_cliente,
            $num_documento_cliente,$direccion_cliente,$telefono_cliente,$email_cliente,$tipo_cliente_cliente);
            echo $rspta; 

    break;      
    case 'validarnit':
        $nit = $_REQUEST["nit"];
        $rspta = $persona->validarnit($nit);
        echo $rspta;
    break;

}
?>