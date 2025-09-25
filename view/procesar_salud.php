<?php
session_start();

// Obtener valores enviados
$ejercicio = isset($_POST['ejercicio']) ? (int)$_POST['ejercicio'] : 0;
$comidas   = isset($_POST['comidas']) ? (int)$_POST['comidas'] : 0;
$sueno     = isset($_POST['sueno']) ? (int)$_POST['sueno'] : 0;

// Calcular un puntaje simple
$puntaje = ($ejercicio * 2) + ($comidas * 3) + ($sueno * 1);

// Definir promedio mundial (ejemplo)
$promedio_mundial = 50;

// Decidir destino
if ($puntaje < $promedio_mundial) {
    header("Location: salud_baja.php");
    exit();
} else {
    header("Location: salud_normal.php");
    exit();
}
