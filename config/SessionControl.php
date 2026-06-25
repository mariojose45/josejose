<?php
/* ------------- SessionControl.php (compatible PHP 7.2+) ------------- */

// Si se define BYPASS_SESSION, ignoramos el control de sesión (útil para la App)
if (defined('BYPASS_SESSION') && BYPASS_SESSION === true) {
    return;
}

$MAX_IDLE = 40 * 60;

$path     = '/';
$domain   = '';
$secure   = !empty($_SERVER['HTTPS']);
$httponly = true;

/* 1 ▸ Iniciar sesión solo si aún no se ha iniciado */
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.gc_maxlifetime', $MAX_IDLE);
    ini_set('session.cookie_lifetime', $MAX_IDLE); // 👈 añadido
    session_set_cookie_params($MAX_IDLE, $path, $domain, $secure, $httponly);
    session_start();
}

/* 2 ▸ Verificar inactividad */
if (isset($_SESSION['LAST_ACTIVITY']) &&
    time() - $_SESSION['LAST_ACTIVITY'] > $MAX_IDLE) {

    session_unset();
    session_destroy();
    header('Location: ../vistas/login.html');
    exit();
}

/* 3 ▸ Actualizar marca de actividad */
$_SESSION['LAST_ACTIVITY'] = time();

/* 4 ▸ Regenerar ID de sesión cada 30 minutos (opcional) */
if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} elseif (time() - $_SESSION['CREATED'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['CREATED'] = time();
}
?>
