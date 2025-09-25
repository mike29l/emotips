<?php
session_start();
require_once("../config/conexion.php");
require_once("../model/registrarModel.php");

$Registrar = new RegistrarModel();

$usuario = $_POST['usuario'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$contraseña = trim($_POST['contraseña'] ?? '');
$rolSeleccionado = $_POST['rol'] ?? '';

// Convertir rol a número (1 para profesor, 2 para cliente)
$rol = ($rolSeleccionado == 'profesor') ? 1 : ($rolSeleccionado == 'cliente' ? 2 : 0);

if (!empty($usuario) && !empty($nombre) && !empty($contraseña) && $rol != 0) {
    // Validar contraseña (8 caracteres, una mayúscula, un número y un símbolo)
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}$/', $contraseña)) {
        echo "<script>alert('La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un símbolo especial.'); window.location.href='../registrar.php';</script>";
        exit;
    }

    // ENCRIPTAR la contraseña antes de guardar
    $contraseñaHash = password_hash($contraseña, PASSWORD_DEFAULT);

    // Registrar al usuario usando la contraseña ENCRIPTADA
    $resultado = $Registrar->registrarUsuario($usuario, $nombre, $contraseñaHash, $rol);

    if ($resultado === true) {
        echo "<script>alert('Registro exitoso'); window.location.href='../index.php';</script>";
    } else {
        echo "<script>alert('Error: " . $resultado . "'); window.location.href='../registrar.php';</script>";
    }
} else {
    echo "<script>alert('Por favor completa todos los campos correctamente.'); window.location.href='../registrar.php';</script>";
}
?>