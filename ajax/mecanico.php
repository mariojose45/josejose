<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Mecanico.php";
 
$categoria=new Mecanico();
 
$idingreso_vehiculo=isset($_POST["idingreso_vehiculo"])? limpiarCadena($_POST["idingreso_vehiculo"]):"";
 
switch ($_GET["op"]){
    case 'listar':
        $fecha_inicio_reporte=$_REQUEST["fecha_inicio_reporte"]; 
        $fecha_fin_reporte=$_REQUEST["fecha_fin_reporte"];
        $rspta=$categoria->listar($fecha_inicio_reporte,$fecha_fin_reporte);
        //Vamos a declarar un array
        $data= Array();
  
        while ($reg=$rspta->fetch_object()){ 
            $opciones = '';
            if ($reg->estado == 'Aceptado' && $reg->facturado == 0) {
                $opciones .= '<button class="btn btn-primary" onclick="addDetalles(' . $reg->idingreso_vehiculo . ')"><i class="fa fa-plus"></i>Detalles</button>';
            }else{
                $opciones = 'N/A';
            }
            $data[]=array(
                "0"=>$opciones,
                "1"=>$reg->nombre_cliente,
                "2"=>$reg->nombre_usuario,
                "3"=>$reg->fecha,
                "4"=>$reg->no_placa,
                "5"=>$reg->no_chasis,
                "6"=>$reg->serie,
                "7"=>$reg->no_motor,
                "8"=>$reg->modelo,
                "9"=>$reg->km,
                "10"=>$reg->nombre_marca,
                "11"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
                '<span class="label bg-red">Anulado</span>',
                "12"=>($reg->facturado=='1')?'<span class="label bg-green">Facturado</span>':
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

    case 'guardaryeditar':
        $idingreso_vehiculo=isset($_POST["idingreso_vehiculo"])? limpiarCadena($_POST["idingreso_vehiculo"]):"";
        $horaInicio=isset($_POST["horaInicio"])? limpiarCadena($_POST["horaInicio"]):"";
        $horaFinalizada=isset($_POST["horaFinalizada"])? limpiarCadena($_POST["horaFinalizada"]):"";
        $tecnico=isset($_POST["tecnico"])? limpiarCadena($_POST["tecnico"]):"";
        $gradoAceite=isset($_POST["gradoAceite"])? limpiarCadena($_POST["gradoAceite"]):"";
        $filtroAceite=isset($_POST["filtroAceite"])? limpiarCadena($_POST["filtroAceite"]):"";
        $filtroAire=isset($_POST["filtroAire"])? limpiarCadena($_POST["filtroAire"]):"";
        $filtroCombustible=isset($_POST["filtroCombustible"])? limpiarCadena($_POST["filtroCombustible"]):"";
        $observaciones=isset($_POST["observaciones"])? limpiarCadena($_POST["observaciones"]):"";

        $detalles_json = isset($_POST["detalles_json"]) ? json_decode($_POST["detalles_json"], true) : [];
        $revisiones_json = isset($_POST["revisiones_json"]) ? json_decode($_POST["revisiones_json"], true) : [];

        $rspta=$categoria->insertar_detalles($idingreso_vehiculo,$detalles_json,$revisiones_json,
        $horaInicio,$horaFinalizada,$tecnico,$gradoAceite,$filtroAceite,$filtroAire,
        $filtroCombustible,$observaciones);
        echo $rspta;
        
    break;

    case 'mostrar_detalles':
        $idingreso_vehiculo = $_POST["idingreso_vehiculo"];
        $rspta = $categoria->mostrarDetalles($idingreso_vehiculo);
        echo json_encode($rspta);
    break;

    case 'mostrar':
        //$idingreso_vehiculo = $_POST["idingreso_vehiculo"];
        $idingreso_vehiculo = isset($_GET["idingreso_vehiculo"]) ? $_GET["idingreso_vehiculo"] : "";
        $datos = $categoria->mostrar($idingreso_vehiculo);

        if ($datos) {
            ?>
            <div class="panel-body-DatosCargados">
                <h3 class="box-title-DatosCargados">Detalles del Vehículo</h3>
                <ul class="list-group-DatosCargados">
                    <li class="list-group-item-DatosCargados">
                        <p><strong>Placa:</strong> <?php echo htmlspecialchars($datos['no_placa']); ?></p>
                    </li>
                    <li class="list-group-item-DatosCargados">
                        <p><strong>Modelo:</strong> <?php echo htmlspecialchars($datos['modelo']); ?></p>
                    </li>
                    <li class="list-group-item-DatosCargados">
                        <p><strong>Chasis:</strong> <?php echo htmlspecialchars($datos['no_chasis']); ?></p>
                    </li>
                    <li class="list-group-item-DatosCargados">
                        <p><strong>Serie:</strong> <?php echo htmlspecialchars($datos['serie']); ?></p>
                    </li>
                    <li class="list-group-item-DatosCargados">
                        <p><strong>Motor:</strong> <?php echo htmlspecialchars($datos['no_motor']); ?></p>
                    </li>
                    <li class="list-group-item-DatosCargados">
                        <p><strong>Kilometraje:</strong> <?php echo htmlspecialchars($datos['km']); ?></p>
                    </li>
                    <li class="list-group-item-DatosCargados">
                        <label for="trabajos_detalle"><strong>Trabajos a Detallar:</strong></label>
                        <textarea class="form-control-DatosCargados" id="trabajos_detalle" rows="4" readonly><?php echo htmlspecialchars($datos['trabajos_detalle']); ?></textarea>
                    </li>
                    <li class="list-group-item-DatosCargados">
                        <label for="observaciones_adicionales"><strong>Observaciones Adicionales:</strong></label>
                        <textarea class="form-control-DatosCargados" id="observaciones_adicionales" rows="4" readonly><?php echo htmlspecialchars($datos['observaciones_adicionales']); ?></textarea>
                    </li>
                </ul>

                <hr>

                <h3 class="box-title-DatosCargados">Imágenes Cargadas</h3>
                <div class="row-DatosCargados">
                    <?php
                    for ($i = 1; $i <= 8; $i++) {
                        $imagen_key = 'imagen' . $i;
                        $descripcion_key = 'descripcion' . $i;

                        if (!empty($datos[$imagen_key])) {
                    ?>
                            <div class="col-md-3-DatosCargados text-center">
                                <img src="../files/articulos/<?php echo htmlspecialchars($datos[$imagen_key]); ?>"
                                    class="img-responsive-DatosCargados"
                                    style="max-height: 200px; max-width: 100%; border: 1px solid #ddd; padding: 5px;">
                                <p><?php echo htmlspecialchars($datos[$descripcion_key]); ?></p>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <?php
        } else {
            echo "<p>No se encontraron datos para este vehículo.</p>";
        }
    break;
}
?>