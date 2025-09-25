<?php
session_start();
require_once "../config/conexion.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

$mensaje = "";
$idUsuario = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nueva = $_POST['nueva'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    if (strlen($nueva) < 6) {
        $mensaje = "❌ La contraseña debe tener al menos 6 caracteres.";
    } elseif ($nueva !== $confirmar) {
        $mensaje = "❌ Las contraseñas no coinciden.";
    } else {
        $hash = password_hash($nueva, PASSWORD_DEFAULT);
        $fecha = date('Y-m-d H:i:s');

        try {
            $conectar = new Conectar();
            $conexion = $conectar->conexion();

            $sql = "UPDATE rol SET contrasenia = ?, ultima_actualizacion_password = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$hash, $fecha, $idUsuario]);

            $mensaje = "✅ Contraseña actualizada correctamente.";
            // Opcional: Redirige al cliente.php después de actualizar
            header("refresh:2;url=cliente.php");
        } catch (PDOException $e) {
            $mensaje = "❌ Error al actualizar: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #F5F5FF;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .contenedor {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        h2 {
            color: #6C63FF;
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-top: 10px;
            color: #333;
        }

        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #CCC;
            border-radius: 5px;
            margin-top: 5px;
        }

        button {
            margin-top: 15px;
            width: 100%;
            padding: 10px;
            background-color: #6C63FF;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background-color: #5848e5;
        }

        .mensaje {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
            color: #FF4444;
        }

        .info {
            font-size: 13px;
            color: #666;
            background: #FFF6D9;
            padding: 8px;
            border-left: 4px solid #FFB84D;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h2>Cambiar Contraseña</h2>

        <?php if (isset($_GET['obligatorio'])): ?>
            <div class="info">🔐 Por tu seguridad, debes actualizar tu contraseña antes de continuar.</div>
        <?php endif; ?>

        <?php if ($mensaje): ?>
            <div class="mensaje"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <form method="post">
            <label>Nueva Contraseña:</label>
            <input type="password" name="nueva" required>

            <label>Confirmar Contraseña:</label>
            <input type="password" name="confirmar" required>

            <button type="submit">Actualizar Contraseña</button>
        </form>
    </div>
</body>
</html>
