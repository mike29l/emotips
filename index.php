<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");
session_start();

// Debug rápido
// echo '<pre>'; print_r($_SESSION); echo '</pre>';
// Controlar caché para evitar mostrar la página de login si está logueado

if (isset($_SESSION['id'])) {
    if ($_SESSION['id_rol'] == "1") {
        header("Location: view/admin.php");
        exit();
    } elseif ($_SESSION['id_rol'] == "2") {
        header("Location: view/cliente.php");
        exit();
    }
}

?>
<?php

if (isset($_SESSION['login_error'])) {
    echo "<script>alert('" . $_SESSION['login_error'] . "');</script>";
    unset($_SESSION['login_error']); // Limpiar mensaje después de mostrarlo
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Espacio Seguro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="icon" type="image/png" href=" img/brain_6302633.png">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #4cc9f0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .bubble {
            position: fixed;
            border-radius: 50%;
            background: rgba(67, 97, 238, 0.1);
            backdrop-filter: blur(5px);
            z-index: -1;
            animation: float 15s infinite ease-in-out;
        }
        
        .bubble:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -50px;
            left: -50px;
            animation-delay: 0s;
        }
        
        .bubble:nth-child(2) {
            width: 200px;
            height: 200px;
            bottom: 100px;
            right: 100px;
            animation-delay: 2s;
        }
        
        .bubble:nth-child(3) {
            width: 150px;
            height: 150px;
            top: 30%;
            right: 10%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0);
            }
            25% {
                transform: translateY(-20px) translateX(10px);
            }
            50% {
                transform: translateY(10px) translateX(-15px);
            }
            75% {
                transform: translateY(-15px) translateX(-10px);
            }
        }
        
        form {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            transition: transform 0.3s ease;
        }
        
        form:hover {
            transform: translateY(-5px);
        }
        
        h1 {
            color: var(--secondary-color);
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
            font-size: 28px;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 25px;
        }
        
        .input-group input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e9ecef;
            border-radius: 50px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.8);
        }
        
        .input-group input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(76, 201, 240, 0.2);
        }
        
        .input-group label {
            position: absolute;
            top: 15px;
            left: 20px;
            color: #6c757d;
            transition: all 0.3s ease;
            pointer-events: none;
            background: white;
            padding: 0 5px;
        }
        
        .input-group input:focus + label,
        .input-group input:not(:placeholder-shown) + label {
            top: -10px;
            left: 15px;
            font-size: 12px;
            color: var(--primary-color);
            background: linear-gradient(to bottom, rgba(255,255,255,0.9) 50%, transparent 50%);
        }
        
        button {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 50px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
        }
        
        .footer-text {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
            font-size: 14px;
        }
        
        .footer-text a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        @media (max-width: 480px) {
            form {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 24px;
            }
        }
    </style>
    <script>
// Evitar que el usuario regrese con el botón "Atrás"
history.pushState(null, null, location.href);
window.onpopstate = function () {
    history.go(1);
};
</script>
</head>

<body>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    
    <form action="controller/loginController.php" method="post" class="animate__animated animate__fadeIn">
        <h1 class="animate__animated animate__fadeInDown">Iniciar Sesión</h1>
        
        <div class="input-group">
            <input type="text" id="usuario" name="usuario" placeholder=" " required>
            <label for="usuario">Usuario</label>
        </div>
        
        <div class="input-group">
            <input type="password" id="contrasenia" name="contrasenia" placeholder=" " required>
            <label for="contrasenia">Contraseña</label>
        </div>
        
        <button type="submit" class="animate__animated animate__fadeInUp">Ingresar</button>
        
        <p class="footer-text animate__animated animate__fadeIn">¿No tienes una cuenta? <a href="registrar.php">Regístrate</a></p>
<p class="footer-text animate__animated animate__fadeIn">
  ¿Olvidaste tu contraseña? <a href="recuperar/form_recuperar.html">Recupérala por SMS</a>
</p>

    </form>
</body>

</html>