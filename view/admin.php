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

if ($_SESSION['id_rol'] != "1") {
    header("Location: ../index.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Mi Espacio Seguro</title>
    <link rel="icon" type="image/png" href=" ../img/brain_6302633.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6C63FF;
            --secondary-color: #5A52D3;
            --accent-color: #FF6584;
            --light-color: #F8F9FF;
            --dark-color: #2E2E3A;
            --soft-shadow: 0 4px 20px rgba(108, 99, 255, 0.1);
            --card-radius: 16px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Nunito', sans-serif;
        }
        
        body {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
            background-color: var(--light-color);
            color: var(--dark-color);
        }
        
        /* Sidebar */
        .sidebar {
            background: var(--primary-color);
            color: white;
            padding: 20px 0;
        }
        
        .logo {
            text-align: center;
            padding: 20px 0;
            margin-bottom: 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logo h2 {
            font-size: 20px;
            font-weight: 700;
        }
        
        .nav-menu {
            list-style: none;
        }
        
        .nav-menu li {
            margin-bottom: 5px;
        }
        
        .nav-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .nav-menu a:hover, .nav-menu a.active {
            background: rgba(255, 255, 255, 0.1);
            border-left: 4px solid var(--accent-color);
        }
        
        .nav-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            padding: 30px;
        }
        
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
            border: 2px solid var(--accent-color);
        }
        
        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: var(--card-radius);
            padding: 20px;
            box-shadow: var(--soft-shadow);
            display: flex;
            align-items: center;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 20px;
            color: white;
        }
        
        .stat-icon.patients {
            background: var(--primary-color);
        }
        
        .stat-icon.sessions {
            background: var(--accent-color);
        }
        
        .stat-icon.alerts {
            background: var(--secondary-color);
        }
        
        .stat-info h3 {
            font-size: 13px;
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .stat-info p {
            font-size: 20px;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        /* Patients Section */
        .section-title {
            color: var(--primary-color);
            margin-bottom: 20px;
            font-size: 20px;
            position: relative;
            display: inline-block;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--accent-color);
            border-radius: 3px;
        }
        
        .patients-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .patient-card {
            background: white;
            border-radius: var(--card-radius);
            padding: 20px;
            box-shadow: var(--soft-shadow);
            display: flex;
            align-items: center;
        }
        
        .patient-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 3px solid var(--primary-color);
        }
        
        .patient-info h3 {
            font-size: 16px;
            margin-bottom: 5px;
            color: var(--dark-color);
        }
        
        .patient-info p {
            font-size: 13px;
            color: #7f8c8d;
            margin-bottom: 8px;
        }
        
        .patient-mood {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 50px;
            font-size: 12px;
            background: rgba(255, 101, 132, 0.1);
            color: var(--accent-color);
        }
        
        .btn {
            display: inline-block;
            padding: 8px 15px;
            background: var(--primary-color);
            color: white;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 13px;
        }
        
        .btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .btn-small {
            padding: 5px 10px;
            font-size: 12px;
        }
        
        /* Recent Journals */
        .card {
            background: white;
            border-radius: var(--card-radius);
            padding: 20px;
            box-shadow: var(--soft-shadow);
            margin-top: 30px;
        }
        
        .journal-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .journal-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .journal-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .journal-content {
            font-size: 14px;
            line-height: 1.6;
        }
        
        @media (max-width: 768px) {
            body {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                display: none;
            }
        }
                .logout-btn {
            display: block;
            width: 90%;
            margin: 20px auto 0;
            padding: 10px 15px;
            background: #FF4D4D;
            color: white;
            border: none;
            border-radius: 50px;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
            transition: background 0.3s ease;
        }

        .logout-btn:hover {
            background: #e04343;
            transform: translateY(-2px);
        }

    </style>
</head>
<script>
// Evitar que el usuario regrese con el botón "Atrás"
history.pushState(null, null, location.href);
window.onpopstate = function () {
    history.go(1);
};
</script>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <i class="fas fa-heart"></i>
            <h2>EMOTIPS</h2>
        </div>
        <ul class="nav-menu">
            <li><a href="#" class="active"><i class="fas fa-home"></i> Resumen</a></li>
            <li><a href="#"><i class="fas fa-users"></i> Pacientes</a></li>
            <li><a href="#"><i class="fas fa-calendar-alt"></i> Sesiones</a></li>
            <li><a href="#"><i class="fas fa-book"></i> Diarios</a></li>
            <li><a href="#"><i class="fas fa-chart-line"></i> Progresos</a></li>
            <li><a href="#"><i class="fas fa-cog"></i> Configuración</a></li>

            <li>
    <form action="../controller/logout.php" method="post">
        <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</button>
    </form>
</li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="header">
            <h1>Resumen del Sistema</h1>
            <div class="user-info">
                <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Usuario">
                <div>
                    <div class="user-name">Dra. Martínez</div>
                    <div class="user-role">Psicóloga Clínica</div>
                </div>
            </div>
        </header>

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon patients">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Pacientes activos</h3>
                    <p>18</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon sessions">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <h3>Sesiones hoy</h3>
                    <p>4</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon alerts">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>Alertas</h3>
                    <p>2</p>
                </div>
            </div>
        </div>

        <!-- Patients Needing Attention -->
        <section>
            <h2 class="section-title">Pacientes que necesitan atención</h2>
            <div class="patients-grid">
                <div class="patient-card">
                    <img src="https://randomuser.me/api/portraits/teens/75.jpg" alt="Paciente" class="patient-avatar">
                    <div class="patient-info">
                        <h3>Alex González</h3>
                        <p>Última sesión: hace 5 días</p>
                        <span class="patient-mood">😔 Triste</span>
                    </div>
                    <a href="#" class="btn btn-small">Contactar</a>
                </div>
                <div class="patient-card">
                    <img src="https://randomuser.me/api/portraits/teens/82.jpg" alt="Paciente" class="patient-avatar">
                    <div class="patient-info">
                        <h3>María Fernández</h3>
                        <p>Patrón de ansiedad detectado</p>
                        <span class="patient-mood">😰 Ansiosa</span>
                    </div>
                    <a href="#" class="btn btn-small">Contactar</a>
                </div>
            </div>
        </section>

        <!-- Recent Journals -->
        <section class="card">
            <h2 class="section-title">Entradas recientes de diarios</h2>
            
            <div class="journal-item">
                <div class="journal-header">
                    <h3>Alex González</h3>
                    <small>Hoy, 14:30</small>
                </div>
                <div class="journal-content">
                    "Hoy me sentí muy solo en la escuela. Nadie quiso sentarse conmigo en el almuerzo. Pero al menos pude hablar con mi mamá por teléfono y eso me ayudó un poco."
                </div>
                <a href="#" class="btn btn-small" style="margin-top: 10px;">Responder</a>
            </div>
            
            <div class="journal-item">
                <div class="journal-header">
                    <h3>Carlos Rojas</h3>
                    <small>Ayer, 19:45</small>
                </div>
                <div class="journal-content">
                    "Probé los ejercicios de respiración que me recomendó la Dra. Martínez durante un ataque de ansiedad y realmente ayudaron. Me tomó unos 15 minutos calmarme, pero funcionó mejor que la última vez."
                </div>
                <a href="#" class="btn btn-small" style="margin-top: 10px;">Responder</a>
            </div>
        </section>
    </main>
</body>
</html>
<script>
window.onload = function() {
    if (performance.navigation.type === 1) { // 2 = back/forward navigation
        location.reload(true);
    }
};
</script>
