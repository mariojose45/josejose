<?php

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Cargadeguias.php";


$categoria = new Categoria();

$idcategoria = isset($_POST["idcategoria"]) ? limpiarCadena($_POST["idcategoria"]) : "";

switch ($_GET["op"]) {


    case 'cargarexcel':
        $idtransporte = isset($_POST["idtransporte"]) ? $_POST["idtransporte"] : ""; // Capturar idtransporte correctamente
        if (!empty($_FILES['archivoexcel']['tmp_name'])) {
            $tmpfname = $_FILES['archivoexcel']['tmp_name'];
            $rspta = $categoria->cargarexcel($tmpfname, $idtransporte);
            echo $rspta;
        }
        break;

    case 'anular':
        $id = $_REQUEST["id"];
        $rspta = $categoria->anular($id);
        echo $rspta;
        break;

    case 'guardarexcel':
        $idtransporte = $_REQUEST["idtransporte"];
        $obervacioncargaexcel = $_REQUEST["obervacioncargaexcel"];
        $fecha_cargaExcel = $_REQUEST["fecha_cargaExcel"];

        $idguia = htmlspecialchars($_POST['idguia'], ENT_QUOTES, 'utf-8');
        $fechaliqui = htmlspecialchars($_POST['fechaliqui'], ENT_QUOTES, 'utf-8');

        $mventa = htmlspecialchars($_POST['mventa'], ENT_QUOTES, 'utf-8');
        $comision = htmlspecialchars($_POST['comision'], ENT_QUOTES, 'utf-8');
        $vcomision = htmlspecialchars($_POST['vcomision'], ENT_QUOTES, 'utf-8');
        $mliquido = htmlspecialchars($_POST['mliquido'], ENT_QUOTES, 'utf-8');
        $autorizacion = htmlspecialchars($_POST['autorizacion'], ENT_QUOTES, 'utf-8');
        $ctabanco = htmlspecialchars($_POST['ctabanco'], ENT_QUOTES, 'utf-8');
        $vflete = htmlspecialchars($_POST['vflete'], ENT_QUOTES, 'utf-8');
        $codigo = htmlspecialchars($_POST['codigo'], ENT_QUOTES, 'utf-8');

        $array_idguia = explode(",", $idguia); //cuando encuentra una , lo separa y lo covierte en una arreglo
        $array_fechaliqui = explode(",", $fechaliqui);

        $array_mventa = explode(",", $mventa);
        $array_comision = explode(",", $comision);
        $array_vcomision = explode(",", $vcomision);
        $array_mliquido = explode(",", $mliquido);
        $array_autorizacion = explode(",", $autorizacion);
        $array_ctabanco = explode(",", $ctabanco);
        $array_vflete = explode(",", $vflete);
        $array_codigo = explode(",", $codigo);

        $rspta1 = $categoria->guardarexcel1($idtransporte, $obervacioncargaexcel, $fecha_cargaExcel);


        for ($i = 0; $i < count($array_idguia); $i++) {
            # code...
            $rspta = $categoria->guardarexcel($array_idguia[$i], $array_fechaliqui[$i], $rspta1, $array_mventa[$i], $array_comision[$i], $array_vcomision[$i], $array_mliquido[$i], $array_autorizacion[$i], $array_ctabanco[$i], $array_vflete[$i], $array_codigo[$i]);

        }
        echo $rspta;
        break;




    case 'listar':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $rspta = $categoria->listar($fecha_inicio, $fecha_fin);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $url = '../reportes/exCargaExcel.php?id=';
            $data[] = array(
                "0" => ($reg->estado == 'Aceptado') ? ' <button class="btn btn-danger" onclick="anular(' . $reg->idguias_excel . ')"><i class="fa fa-close"></i></button>' . '<a target="_blank" href="' . $url . $reg->idguias_excel . '"><button class="btn btn-info"><i class="fa fa-tag"></i> </button> </a>' : '<a target="_blank" href="' . $url . $reg->idguias_excel . '"><button class="btn btn-info"><i class="fa fa-tag"></i> </button> </a>',
                "1" => $reg->fecha_cargaExcel,
                "2" => $reg->obervacioncargaexcel,
                "3" => $reg->nombre_transporte,
                "4" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>',
                "5" => $reg->idguias_excel,
                "6" => $reg->nombre_sucursal
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

    case 'listar':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $rspta = $categoria->listar($fecha_inicio, $fecha_fin);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $url = '../reportes/exCargaExcel.php?id=';
            $data[] = array(
                "0" => ($reg->estado == 'Aceptado') ? ' <button class="btn btn-danger" onclick="anular(' . $reg->idguias_excel . ')"><i class="fa fa-close"></i></button>' . '<a target="_blank" href="' . $url . $reg->idguias_excel . '"><button class="btn btn-info"><i class="fa fa-tag"></i> </button> </a>' : '<a target="_blank" href="' . $url . $reg->idguias_excel . '"><button class="btn btn-info"><i class="fa fa-tag"></i> </button> </a>',
                "1" => $reg->fecha_cargaExcel,
                "2" => $reg->obervacioncargaexcel,
                "3" => $reg->nombre_transporte,
                "4" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>',
                "5" => $reg->idguias_excel,
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

    case 'listargeneral':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $rspta = $categoria->listargeneral($fecha_inicio, $fecha_fin);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $url = '../reportes/exCargaExcel.php?id=';
            $data[] = array(
                "0" => ($reg->estado == 'Aceptado') ? ' <button class="btn btn-danger" onclick="anular(' . $reg->idguias_excel . ')"><i class="fa fa-close"></i></button>' . '<a target="_blank" href="' . $url . $reg->idguias_excel . '"><button class="btn btn-info"><i class="fa fa-tag"></i> </button> </a>' : '<a target="_blank" href="' . $url . $reg->idguias_excel . '"><button class="btn btn-info"><i class="fa fa-tag"></i> </button> </a>',
                "1" => $reg->fecha_cargaExcel,
                "2" => $reg->obervacioncargaexcel,
                "3" => $reg->nombre_transporte,
                "4" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>',
                "5" => $reg->idguias_excel,
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