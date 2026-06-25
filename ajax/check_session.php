<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

/*
// Definimos el tiempo de inactividad (en segundos)
$inactividad = 900; // 15 minutos = 900 segundos

// Verificamos si existe la variable de sesión 'tiempo'
if (isset($_SESSION["tiempo"])) {
    // Calculamos el tiempo de inactividad
    $tiempo_inactivo = time() - $_SESSION["tiempo"];
    
    // Si el tiempo de inactividad es mayor que el permitido, destruimos la sesión
    if ($tiempo_inactivo >= $inactividad) {
        // Destruimos la sesión
        session_unset();
        session_destroy();
        // Enviamos respuesta JSON indicando que la sesión ha expirado
        echo json_encode(array("session_active" => false));
        exit();
    }
}

// Actualizamos el tiempo de actividad
$_SESSION["tiempo"] = time();

// Si la sesión está activa, devolvemos respuesta JSON indicando que está activa
if (isset($_SESSION["nombre"])) {
    echo json_encode(array("session_active" => true));
} else {
    echo json_encode(array("session_active" => false));
}*/
?>
