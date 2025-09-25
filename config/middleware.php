<?php
// middleware_session.php

function checkSession() {
    // Comprobar si la sesión está activa
    session_start();

    // Si la sesión no está activa (por ejemplo, la variable de sesión del usuario no está definida), redirigir a login
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php"); // Redirige al login
        exit();
    }
}

// Llamar a esta función en cada página que requiere que el usuario esté autenticado
