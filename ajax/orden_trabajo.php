<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Orden_trabajo.php";
 
$ordentrabajo=new OrdenTRbajo();
 
$idorden=isset($_POST["idorden"])? limpiarCadena($_POST["idorden"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$modelo=isset($_POST["modelo"])? limpiarCadena($_POST["modelo"]):"";
$serie=isset($_POST["serie"])? limpiarCadena($_POST["serie"]):"";
$descripcion_equipo=isset($_POST["descripcion_equipo"])? limpiarCadena($_POST["descripcion_equipo"]):"";
$reparacion_equipo=isset($_POST["reparacion_equipo"])? limpiarCadena($_POST["reparacion_equipo"]):"";
$idusuario=$_SESSION["idusuario"]; 

$idorden2=isset($_POST["idorden2"])? limpiarCadena($_POST["idorden2"]):"";
$detalle_tecnico=isset($_POST["detalle_tecnico2"])? limpiarCadena($_POST["detalle_tecnico2"]):"";
$tipo_status=isset($_POST["tipo_status"])? limpiarCadena($_POST["tipo_status"]):"";
$tecnico=isset($_POST["tecnico"])? limpiarCadena($_POST["tecnico"]):"";
$fecha_hora_detalle=isset($_POST["fecha_hora_detalle"])? limpiarCadena($_POST["fecha_hora_detalle"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idorden)){
            $rspta=$ordentrabajo->insertar($idcliente,$fecha_hora,$modelo,$serie,$descripcion_equipo,$reparacion_equipo,$idusuario);
            echo $rspta ? "Orden registrada" : "Orden no se pudo registrar";
        }
        else {
            $rspta=$ordentrabajo->editar($idorden,$idcliente,$fecha_hora,$modelo,$serie,$descripcion_equipo,$reparacion_equipo,$idusuario);
            echo $rspta ? "Orden actualizada" : "Orden no se pudo actualizar";
        }
    break;

    case 'guardaryeditar3':
        if (empty($idorden2)){
        }
        else {
            $rspta=$ordentrabajo->editar_3($idorden2,$detalle_tecnico,$tipo_status,$tecnico,$fecha_hora_detalle);
            echo $rspta ? "Detalle Ingreso Orden actualizado" : "Detalle Ingreso Orden no se pudo actualizar";
        }
    break;    
 
     case 'activar':
        $rspta=$ordentrabajo->activar($idorden);
        echo $rspta ? "Orden Entregada" : "Orden no se puede Entregar comuniquese con el administrador";
        break;
    break;
 
 
    case 'mostrar':
        $rspta=$ordentrabajo->mostrar($idorden);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;

    case 'mostrar_idtecnico':
        $rspta=$ordentrabajo->mostrar_idtecnico($idorden);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    break;    
 
    case 'listar':
        $rspta=$ordentrabajo->listar();
        //Vamos a declarar un array
        $data= Array(); 
 
        while ($reg=$rspta->fetch_object()){
             $url='../reportes/exOrdenTrabajo.php?id=';
            $data[]=array(


                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idorden.')"><i class="fa fa-pencil"></i></button>'.'<button class="btn btn-success" onclick="detalle_tenico('.$reg->idorden.')"><i class="fa fa-pencil-square-o"></i></button>'.
                    ' <button class="btn btn-danger" onclick="activar('.$reg->idorden.')"><i class="fa fa-user"></i></button>':'<a target="_blank" href="'.$url.$reg->idorden.'"><button class="btn btn-info"><i class="fa  fa-print"></i> </button> </a>',                
                "1"=>$reg->idorden, 
                "2"=>$reg->cliente_nombre,
                "3"=>$reg->cliente_telefono,
                "4"=>$reg->fecha,
                "5"=>$reg->modelo,
                "6"=>$reg->serie,
                "7"=>$reg->usuario,
                "8"=>$reg->detalle_tecnico,
                "9"=>$reg->tipo_status,
                "10"=>$reg->tecnico,
                "11"=>$reg->fechahoratecnico,
                "12"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
                '<span class="label bg-red">Entregado</span>'
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

}
?>