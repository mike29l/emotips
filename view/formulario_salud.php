<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['final'])) {
    $preguntas = range(1, 10);
    $suma = 0;
    foreach ($preguntas as $p) {
        if (!isset($_POST["q$p"])) {
            header("Location: formulario_salud.php?error=1");
            exit;
        }
        $suma += intval($_POST["q$p"]);
    }
    $max = 40;
    $porcentaje = ($suma / $max) * 100;

    if ($porcentaje >= 50) {
        header("Location: salud_baja.php?score=$porcentaje");
    } else {
        header("Location: salud_normal.php?score=$porcentaje");
    }
    exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Formulario Salud Mental</title>
  <style>
    body { 
      font-family: 'Segoe UI', sans-serif; 
      background:linear-gradient(135deg,#c2e9fb,#e0c3fc); 
      margin:0; 
      padding:0; 
      display:flex;
      justify-content:center;
      align-items:center;
      min-height:100vh;
    }
    .container { 
      max-width:600px; 
      width:90%;
    }
    .card { 
      display:none; 
      background:#fff; 
      padding:30px; 
      border-radius:20px; 
      box-shadow:0 6px 20px rgba(0,0,0,0.15); 
    }
    .card.active { display:block; }
    h2 { color:#4c1d95; margin-bottom:20px; }
    .options label { 
      display:block; 
      margin:8px 0; 
      padding:10px; 
      background:#f3f4f6; 
      border-radius:12px; 
      cursor:pointer; 
      transition:0.2s; 
    }
    input[type=radio] { display:none; }
    input[type=radio]:checked + span { 
      background:#a78bfa; 
      color:#fff; 
      font-weight:bold; 
      padding:8px 12px; 
      border-radius:10px; 
    }
    .buttons { 
      margin-top:20px; 
      display:flex; 
      justify-content:space-between; 
      flex-wrap:wrap;
      gap:10px;
    }
    button { 
      padding:10px 18px; 
      border:none; 
      border-radius:12px; 
      cursor:pointer; 
      font-size:15px; 
    }
    .btn-next { background:#7c3aed; color:#fff; }
    .btn-prev { background:#d1d5db; }
    .btn-exit { background:#ef4444; color:#fff; }
    .btn-submit { background:#10b981; color:#fff; width:100%; }
  </style>
</head>
<body>
  <div class="container">
    <form method="post" id="quizForm">
      <?php
      $preguntas = [
        "¿Con qué frecuencia te has sentido triste o sin esperanza?",
        "¿Con qué frecuencia te has sentido nervioso o ansioso?",
        "¿Con qué frecuencia has tenido problemas para dormir?",
        "¿Con qué frecuencia te has sentido con poca energía?",
        "¿Con qué frecuencia te ha costado concentrarte?",
        "¿Con qué frecuencia has perdido interés en actividades?",
        "¿Con qué frecuencia te has sentido irritable?",
        "¿Con qué frecuencia has evitado actividades sociales?",
        "¿Con qué frecuencia has notado cambios en el apetito?",
        "¿Con qué frecuencia has usado alcohol u otras sustancias para manejar emociones?"
      ];
      $opciones = ["Nunca ", "Rara vez ", "A veces ", "A menudo ", "Siempre "];

      foreach ($preguntas as $i => $texto) {
          $num = $i+1;
          echo "<div class='card' id='card$num'>";
          echo "<h2>Pregunta $num de 10</h2>";
          echo "<p>$texto</p>";
          echo "<div class='options'>";
          foreach ($opciones as $valor => $opcion) {
              echo "<label><input type='radio' name='q$num' value='$valor'><span>$opcion</span></label>";
          }
          echo "</div>";
          echo "<div class='buttons'>";
          if ($num > 1) {
              echo "<button type='button' class='btn-prev' onclick='prevCard($num)'>← Regresar</button>";
          } else {
              echo "<button type='button' class='btn-exit' onclick=\"window.location='cliente.php'\">Salir</button>";
          }
          if ($num < 10) {
              echo "<button type='button' class='btn-next' onclick='nextCard($num)'>Siguiente →</button>";
          } else {
              echo "<button type='submit' name='final' class='btn-submit'>Finalizar</button>";
          }
          echo "</div>";
          echo "</div>";
      }
      ?>
    </form>
  </div>

  <script>
    let current = 1;
    document.getElementById("card1").classList.add("active");

    function showCard(n) {
      document.querySelectorAll(".card").forEach(c => c.classList.remove("active"));
      document.getElementById("card"+n).classList.add("active");
      current = n;
    }

    function nextCard(n) {
      showCard(n+1);
    }
    function prevCard(n) {
      showCard(n-1);
    }

    // Validación antes de enviar (todas contestadas)
    document.getElementById("quizForm").addEventListener("submit", function(e){
      for (let i=1; i<=10; i++) {
        let radios = document.querySelectorAll(`input[name='q${i}']`);
        let checked = Array.from(radios).some(r => r.checked);
        if (!checked) {
          e.preventDefault();
          alert("Por favor responde todas las preguntas antes de finalizar.");
          showCard(i);
          return false;
        }
      }
    });
  </script>
</body>
</html>
