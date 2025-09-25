<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario es cliente (rol 2)
if (!isset($_SESSION['id']) || $_SESSION['id_rol'] != "2") {
    header("Location: ../index.php");
    exit();
}

require_once '../config/conexion.php';

// Configuración de imágenes
$upload_dir = 'img/perfiles/';
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

// Crear directorio si no existe
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Procesar imagen de perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_perfil'])) {
    try {
        $file = $_FILES['foto_perfil'];
        
        // Validar tipo de archivo
        if (!in_array($file['type'], $allowed_types)) {
            throw new Exception("Solo se permiten imágenes JPG, PNG o GIF");
        }
        
        // Generar nombre único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'user_' . $_SESSION['id'] . '.' . $extension;
        $filepath = $upload_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Actualizar en base de datos
            $conectar = new Conectar();
            $pdo = $conectar->Conexion();
            $stmt = $pdo->prepare("UPDATE rol SET imagen_perfil = ? WHERE id = ?");
            if ($stmt->execute([$filepath, $_SESSION['id']])) {
                $_SESSION['mensaje'] = "Foto de perfil actualizada correctamente";
            } else {
                throw new Exception("Error al actualizar en la base de datos");
            }
        } else {
            throw new Exception("Error al subir la imagen");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
    header("Location: perfil.php");
    exit();
}

// Procesar teléfono (CORRECCIÓN IMPORTANTE)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['telefono'])) {
    try {
        $conectar = new Conectar();
        $pdo = $conectar->Conexion();
        
        $telefono_num = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
        
        // Verificar si ya existe un teléfono
        $check = $pdo->prepare("SELECT id FROM usuarios_telefonos WHERE user_id = ?");
        $check->execute([$_SESSION['id']]);
        
        if ($check->rowCount() === 0) {
            $insert = $pdo->prepare("INSERT INTO usuarios_telefonos (user_id, telefono) VALUES (?, ?)");
            if ($insert->execute([$_SESSION['id'], $telefono_num])) {
                $_SESSION['telefono'] = $telefono_num;
                $_SESSION['mensaje'] = "Teléfono registrado correctamente";
            }
        } else {
            $_SESSION['error'] = "Ya tienes un teléfono registrado";
        }
        
        header("Location: perfil.php");
        exit();
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error al registrar teléfono";
        error_log("Error DB teléfonos: " . $e->getMessage());
        header("Location: perfil.php");
        exit();
    }
}

// Obtener datos del usuario
$conectar = new Conectar();
$pdo = $conectar->Conexion();
$conectar->set_names();

$stmt = $pdo->prepare("SELECT nombre, usuario, imagen_perfil FROM rol WHERE id = ?");
$stmt->execute([$_SESSION['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener teléfono (CORRECCIÓN IMPORTANTE)
$telefono = null;
try {
    $pdo_telefonos = new PDO("mysql:host=localhost;dbname=mike", "root", "");
    $stmt_phone = $pdo_telefonos->prepare("SELECT telefono FROM usuarios_telefonos WHERE user_id = ?");
    $stmt_phone->execute([$_SESSION['id']]);
    $telefono_data = $stmt_phone->fetch(PDO::FETCH_ASSOC);
    $telefono = $telefono_data ? $telefono_data['telefono'] : null;
} catch (PDOException $e) {
    error_log("Error al obtener teléfono: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --light: #f8f9fa;
            --gray: #6c757d;
        }
        
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .profile-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            padding: 30px;
            text-align: center;
        }
        
        .profile-pic-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 20px;
        }
        
        .profile-pic {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary);
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        .profile-pic:hover {
            transform: scale(1.05);
        }
        
        .change-photo-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        
        .profile-name {
            color: var(--secondary);
            margin-bottom: 5px;
            font-size: 24px;
        }
        
        .profile-username {
            color: var(--gray);
            margin-bottom: 20px;
        }
        
        .info-section {
            background: rgba(108, 117, 125, 0.05);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .info-label {
            color: var(--gray);
            font-size: 14px;
            display: block;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16px;
        }
        
        .phone-form {
            margin-top: 20px;
        }
        
        input[type="tel"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        
        .btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: var(--secondary);
        }
        
        .hidden {
            display: none;
        }
        
        /* Modal para subir foto */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
        }
        
        .modal-title {
            margin-top: 0;
            color: var(--secondary);
        }
        
        .modal-close {
            float: right;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="profile-card">
        <!-- Foto de perfil con botón para cambiar -->
        <div class="profile-pic-container">
            <img src="<?php echo htmlspecialchars($user['imagen_perfil'] ?? 'img/default-profile.jpg'); ?>" 
                 alt="Foto de perfil" 
                 class="profile-pic"
                 id="currentPhoto">
            <button class="change-photo-btn" onclick="openModal()">+</button>
        </div>
        
        <h2 class="profile-name"><?php echo htmlspecialchars($user['nombre']); ?></h2>
        <p class="profile-username">@<?php echo htmlspecialchars($user['usuario']); ?></p>
        
        <!-- Sección de teléfono (manteniendo tu estructura original) -->
        <div class="info-section">
            <?php if ($telefono): ?>
                <span class="info-label">Teléfono registrado</span>
                <span class="info-value"><?php echo htmlspecialchars($telefono); ?></span>
            <?php else: ?>
                <form method="post" class="phone-form">
                    <span class="info-label">Registrar tu teléfono</span>
                    <input type="tel" name="telefono" pattern="[0-9]{10}" 
                           placeholder="Ingresa tu número" required>
                    <button type="submit" class="btn">Guardar teléfono</button>
                </form>
            <?php endif; ?>
        </div>
        
        <!-- Mensajes de estado -->
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div style="color: green; margin: 15px 0;">
                <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div style="color: red; margin: 15px 0;">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <a href="cliente.php" class="btn" style="display: inline-block; margin-top: 20px;">
            Volver al inicio
        </a>
    </div>

    <!-- Modal para subir foto -->
    <div class="modal" id="photoModal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal()">&times;</span>
            <h3 class="modal-title">Cambiar foto de perfil</h3>
            <form method="post" enctype="multipart/form-data" id="photoForm">
                <input type="file" name="foto_perfil" accept="image/*" required>
                <button type="submit" class="btn" style="margin-top: 15px;">Subir foto</button>
            </form>
        </div>
    </div>

    <script>
        // Funciones para el modal
        function openModal() {
            document.getElementById('photoModal').style.display = 'flex';
        }
        
        function closeModal() {
            document.getElementById('photoModal').style.display = 'none';
        }
        
        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            if (event.target == document.getElementById('photoModal')) {
                closeModal();
            }
        }
        
        // Actualizar visualización de la foto después de subir
        document.getElementById('photoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('perfil.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>