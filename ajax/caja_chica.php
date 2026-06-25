<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/caja_chica.php";

$CajaChica = new CajaChica();

$aperturaCaja = isset($_POST["aperturaCaja"]) ? limpiarCadena($_POST["aperturaCaja"]) : "";
$fecha_hora = isset($_POST["fecha_hora"]) ? limpiarCadena($_POST["fecha_hora"]) : "";
$idusuario = $_SESSION["idusuario"];
$observacionesCaja = isset($_POST["observacionesCaja"]) ? limpiarCadena($_POST["observacionesCaja"]) : "";



switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (!empty($aperturaCaja)) {
            $rspta = $CajaChica->insertar($aperturaCaja, $fecha_hora, $idusuario, $observacionesCaja);
            echo $rspta = "Caja Chica Aperturada y Registrada";
        } else {
        }
        break;

    case 'anular':
        $Id = $_POST['Id'];
        $rspta = $CajaChica->anular($Id);
        echo $rspta ? "Caja Chica #" . $Id . " anulada" : "Caja Chica no se puede anular";
        break;

    case 'quitarPago':
        $IdCajaDetalle = $_POST['IdCajaDetalle'];
        $rspta = $CajaChica->quitarPago($IdCajaDetalle);
        echo $rspta ? "Pago de Caja Chica eliminado" : "Pago no eliminado";
        break;

    case 'terminar':
        $Id = $_POST['Id'];
        $rspta = $CajaChica->terminarcaja($Id);
        echo $rspta ? "Caja Chica #" . $Id . " terminada" : "Caja Chica no se puede terminar";
        break;



    case 'abonar':
        $Id = $_POST['Id'];
        $fecha_hora = isset($_POST["fecha_hora"]) ? limpiarCadena($_POST["fecha_hora"]) : "";
        $Fechaabono = isset($_POST["Fechaabono"]) ? limpiarCadena($_POST["Fechaabono"]) : "";
        $ConceptoAbono = isset($_POST["ConceptoAbono"]) ? limpiarCadena($_POST["ConceptoAbono"]) : "";
        $tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
        $AbonoCajaChica = isset($_POST["AbonoCajaChica"]) ? limpiarCadena($_POST["AbonoCajaChica"]) : "";
        $rspta = $CajaChica->abonar($Id, $AbonoCajaChica, $Fechaabono, $ConceptoAbono, $tipo_comprobante, $idusuario);

        // echo $rspta ? "Caja Chica #".$Id." Abono por ".$AbonoCajaChica." " : "Caja Chica no se puede Abonar";
        break;



    case 'detalle':
        $Id = $_POST['Id'];

        $rspta = $CajaChica->detalle($Id);
        echo '
            <thead>

                            <th># Caja Chica</th>
                            <th>Nombre Usuario</th>
                            <th>Tipo Documento</th>                            
                            <th>Descripción</th>
                            <th>Valor</th>
                            <th>Fecha</th>

                            <th>Acciones</th>
                          </thead>';

        while ($reg = $rspta->fetch_object()) {

            echo '
                         <tfoot>
         <tr>
                                    <td>' . $reg->Id_CajaChica . '</td>
                                    <td>' . $reg->NombreUsuarioCreador . '</td>
                                    <td>' . $reg->tipodocumento . '</td>                                    
                                    <td>' . $reg->descripcion . '</td>
                                    <td>' . $reg->valor . '</td>
                                    <td>' . $reg->fecha . '</td>
                                    <td> <button class="btn btn-danger" title="Anular Caja Chica" onclick="quitarPago(' . $reg->IdCajaDetalle . ', ' . $reg->Id_CajaChica . ')"><i class="fa fa-close"></i></button> </td>
                                    </tr>

            </tfoot>
                                    ';

        }



        break;









    case 'listar':
        $rspta = $CajaChica->listar();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            if ($reg->estado == '2') {
                # code...
                $url = '../reportes/exCajaChica.php?id=';

            } else {
                $url = '../reportes/exFactura1.php?id=';
            }

            $data[] = array(
                "0" => (($reg->estado == '1') ? '<button title="Cerrar Caja" class="btn btn-danger" onclick="terminar(' . $reg->Id . ')"><i class="fa fa-close"></i></button>' .
                    ' <button class="btn btn-warning" title="Abonar a esta Caja Chica" onclick="abonar(' . $reg->Id . ')"><i class="fa fa-plus-square"></i></button>' :
                    '<a target="_blank" href="' . $url . $reg->Id . '"><button class="btn btn-info" title="Imprimr"><i class="fa fa-print"></i> </button> </a>') .
                    '<button onclick="detalle(' . $reg->Id . ')"  class="btn btn-success" title="Detalle Caja Chica"><i class="fa fa-pencil"></i></button>',
                "1" => $reg->Id,
                "2" => $reg->fecha,
                "3" => $reg->NombreUsuarioCreador,
                "4" => $reg->valor,
                "5" => $reg->total_items,
                "6" => $reg->diferencia,
                "7" => ($reg->estado == '2') ? '<span class="label bg-green">Caja Terminada</span>' :
                    '<span class="label bg-red">Caja Pendiente</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;


}
?>