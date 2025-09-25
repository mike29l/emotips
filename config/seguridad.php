<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('verificarRol')) {
    function verificarRol($rol_requerido) {
        if (!isset($_SESSION['id']) || $_SESSION['id_rol'] != $rol_requerido) {
            header("Location: ../index.html");
            exit();
        }
    }
}
?>

