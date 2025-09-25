<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");
session_start();

// Validación de sesión y rol
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SESSION['id_rol'] != "2") {
    header("Location: ../index.php");
    exit();
}

// --- VERIFICAR EXPIRACIÓN DE CONTRASEÑA ---
require_once "../config/conexion.php";
$conectar = new Conectar();
$conexion = $conectar->conexion();

$idUsuario = $_SESSION['id'];

$sql = "SELECT ultima_actualizacion_password FROM rol WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->execute([$idUsuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario && $usuario['ultima_actualizacion_password']) {
    $ultimaFecha = new DateTime($usuario['ultima_actualizacion_password']);
    $actual = new DateTime();
    $minutosPasados = ($actual->getTimestamp() - $ultimaFecha->getTimestamp()) / 60;

    if ($minutosPasados >= 129600) {  // 90 días
        header("Location: cambiar_contraseña.php?obligatorio=1");
        exit();
    }
} else {
    header("Location: cambiar_contraseña.php?obligatorio=1");
    exit();
}

// --- REGISTRAR VISITA ---
try {
    $conectar = new Conectar();
    $conexion = $conectar->conexion();

    $usuario = $_SESSION['usuario'] ?? 'desconocido';
    $pagina = basename(__FILE__);

    $sql = "INSERT INTO visitas (usuario, pagina) VALUES (?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$usuario, $pagina]);

} catch (PDOException $e) {
    error_log("Error al registrar visita: " . $e->getMessage());
}

$cuestionarioCompletado = isset($_SESSION['cuestionario_completado']) && $_SESSION['cuestionario_completado'] === true;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mi Espacio Seguro</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body, html {
        margin: 0;
        padding: 0;
        font-family: 'Nunito', sans-serif;
        background-color: #F8F9FF;
        color: #2E2E3A;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* --- HEADER --- */
    header {
        background-color: #D7E4FF;
        padding: 10px 5%;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .logo i { color: #6C63FF; font-size: 28px; }
    .logo h1 { font-size: 22px; color: #6C63FF; margin: 0; }

    /* --- MENÚ --- */
    .user-nav { position: relative; }
    .user-nav input[type="checkbox"] { display: none; }

    .user-nav ul.menu {
        list-style: none;
        display: flex;
        gap: 10px;
        margin: 0;
        padding: 0;
    }

    .menu-btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #6CA0F1;
        color: white;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.3s, transform 0.2s;
    }
    .menu-btn:hover { background-color: #5B8DE0; transform: translateY(-2px); }

    .logout-btn {
        background-color: #FF6584;
        border: none;
        cursor: pointer;
    }
    .logout-btn:hover { background-color: #E94E77; }

    /* --- ÍCONO HAMBURGUESA --- */
    .hamburger {
        display: none;
        font-size: 28px;
        cursor: pointer;
        color: #6C63FF;
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 768px) {
        .user-nav ul.menu {
            flex-direction: column;
            position: absolute;
            top: 60px;
            right: 0;
            background: #fff;
            width: 220px;
            transform: scaleY(0);
            transform-origin: top;
            transition: transform 0.25s ease;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            padding: 10px;
        }

        #menu-toggle:checked + .hamburger + ul.menu {
            transform: scaleY(1);
        }

        .menu-btn {
            display: block;
            width: 100%;
            padding: 12px;
            margin: 5px 0;
            border-radius: 8px;
            background: #6CA0F1;
            color: white;
            text-align: center;
        }

        .menu-btn:hover {
            background: #5B8DE0;
        }

        .logout-btn {
            background: #FF6584;
            color: white;
        }

        .logout-btn:hover {
            background: #E94E77;
        }

        .hamburger { display: block; }
    }

    /* --- CHATBOT --- */
    #chatbot {
        margin: 20px auto;
        max-width: 600px;
        background: #fff;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    #chatbox {
        height: 280px;
        overflow-y: auto;
        padding: 10px;
        background: #FAFAFA;
        border-radius: 10px;
        flex-grow: 1;
    }

    .msg-user, .msg-bot {
        max-width: 80%;
        padding: 10px 15px;
        margin: 10px 0;
        border-radius: 15px;
        display: inline-block;
    }

    .msg-user {
        background: #6C63FF;
        color: white;
        align-self: flex-end;
        float: right;
        clear: both;
    }

    .msg-bot {
        background: #f1f1f1;
        color: #333;
        align-self: flex-start;
        float: left;
        clear: both;
    }

    #chatForm {
        margin-top: 10px;
        display: flex;
    }

    #userInput {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px 0 0 5px;
    }

    #chatForm button {
        padding: 10px 20px;
        border: none;
        background: #6C63FF;
        color: white;
        border-radius: 0 5px 5px 0;
    }
</style>
</head>
<body>
<header>
    <div class="logo">
        <i class="fas fa-heart"></i>
        <h1>Mi Espacio Seguro</h1>
    </div>

    <nav class="user-nav">
        <input type="checkbox" id="menu-toggle">
        <label for="menu-toggle" class="hamburger"><i class="fas fa-bars"></i></label>
        <ul class="menu">
            <li><a href="#" class="menu-btn">Inicio</a></li>
            <li><a href="formulario_salud.php" class="menu-btn">Herramientas</a></li>
            <li><a href="perfil.php" class="menu-btn">Perfil</a></li>
            <li>
                <form action="../controller/logout.php" method="post">
                    <button type="submit" class="menu-btn logout-btn">Cerrar sesión</button>
                </form>
            </li>
        </ul>
    </nav>
</header>

<main>
    <section id="chatbot">
        <h3 style="text-align: center; color: #6C63FF;">💙 Asistente Emocional EMOTIPS</h3>
        <p style="text-align: center; font-size: 14px; color: #666;">Habla con tu asistente IA sobre cómo te sientes.</p>
        
        <div id="chatbox">
            <div class="msg-bot">Hola <?php echo htmlspecialchars($_SESSION['nombre'] ?? 'amigo/a'); ?>, soy emotips, tu amigo virtual 💙</div>
        </div>
        
        <form id="chatForm">
            <input type="text" id="userInput" placeholder="Escribe cómo te sientes..." required>
            <button type="submit">Enviar <i class="fas fa-paper-plane"></i></button>
        </form>
    </section>
</main>

<script>
document.getElementById('chatForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const input = document.getElementById('userInput');
    const mensaje = input.value.trim();
    if (mensaje === "") return;

    const chatbox = document.getElementById('chatbox');
    chatbox.innerHTML += `<div class="msg-user">${mensaje}</div>`;
    chatbox.scrollTop = chatbox.scrollHeight;

    input.value = "";
    input.disabled = true;
    document.querySelector('#chatForm button').innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    try {
        const response = await fetch('../controller/chatbot.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: mensaje, model: "claude-3-haiku-20240307" })
        });

        const data = await response.json();
        const respuesta = data.respuesta || "No recibí una respuesta válida";
        chatbox.innerHTML += `<div class="msg-bot">${respuesta}</div>`;
    } catch (error) {
        chatbox.innerHTML += `<div class="msg-bot" style="color:#FF6584">⚠️ Error: ${error.message}</div>`;
    } finally {
        chatbox.scrollTop = chatbox.scrollHeight;
        input.disabled = false;
        document.querySelector('#chatForm button').innerHTML = 'Enviar <i class="fas fa-paper-plane"></i>';
        input.focus();
    }
});
</script>
</body>
</html>
