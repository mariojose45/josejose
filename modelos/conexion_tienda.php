<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

    $conexion2 = mysqli_connect('drywall.com.gt', 'maxime_ferroel', 'zYFzhzW6yi6V2024@!ferroelectro', 'maxime_ferelec');

    mysqli_query($conexion2, 'SET NAMES "utf8"'); // Asegúrate de que DB_ENCODE sea utf8 o lo que necesites

    // Si tenemos un posible error en la conexión lo mostramos
    if (mysqli_connect_errno()) {
        printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
        exit();
    }
?>
