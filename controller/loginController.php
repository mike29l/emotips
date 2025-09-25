<?php
// Iniciar sesión si no está activa
if (session_status() == PHP_SESSION_NONE) { 
    session_start(); 
}

require_once("../config/conexion.php");
require_once("../model/loginModel.php");

$Login = new Login();

$usuario = $_POST['usuario'] ?? '';
$contraseña = trim($_POST['contrasenia'] ?? '');

if (empty($usuario) || empty($contraseña)) {
    $_SESSION['login_error'] = "Usuario o contraseña vacíos.";
    header("Location: ../index.php");
    exit();
}

$DatosDelLogin = $Login->GetLogin($usuario, $contraseña);

if (is_array($DatosDelLogin) && count($DatosDelLogin) > 0) {
    $resultado = $DatosDelLogin[0];

    if (password_verify($contraseña, $resultado['contrasenia'])) {
        $_SESSION['id'] = $resultado['id'];
        $_SESSION['nombre'] = $resultado["nombre"];
        $_SESSION['usuario'] = $resultado["usuario"];
        $_SESSION['id_rol'] = $resultado['id_rol'];

        if ($resultado['id_rol'] == "1") {
            header("Location: ../view/admin.php");
        } else {
            header("Location: ../view/cliente.php");
        }
        exit();
    }
}

// Si falla la autenticación
$_SESSION['login_error'] = "Usuario o contraseña incorrectos.";
header("Location: ../index.php");
exit();
?>
