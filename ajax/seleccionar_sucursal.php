<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión



require_once "../modelos/Sucursal.php";
$sucursal = new Sucursal();

$idsucursal = isset($_POST["idsucursal"]) ? limpiarCadena($_POST["idsucursal"]) : "";

switch ($_GET["op"]) {
    case 'seleccionar':
        if (!empty($idsucursal)) {
            $_SESSION["idsucursal"] = $idsucursal;

            date_default_timezone_set('America/Guatemala');
            $fechaHora = date('Y-m-d H:i:s'); 
                $rspta=$sucursal->guardarSession($_SESSION["idsucursal"],$_SESSION["idusuario"],$fechaHora);
        

            echo json_encode(array("status" => "success", "message" => "Sucursal seleccionada correctamente"));
        } else {
            echo json_encode(array("status" => "error", "message" => "No se ha seleccionado ninguna sucursal"));
        }
    break;
}
?> 