<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Formulario de Registro</title>
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

    h2 {
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

    .input-group input,
    .input-group select {
      width: 100%;
      padding: 15px 20px;
      border: 2px solid #e9ecef;
      border-radius: 50px;
      font-size: 16px;
      transition: all 0.3s ease;
      background-color: rgba(255, 255, 255, 0.8);
    }

    .input-group input:focus,
    .input-group select:focus {
      outline: none;
      border-color: var(--accent-color);
      box-shadow: 0 0 0 3px rgba(76, 201, 240, 0.2);
    }

    .toggle-password {
      position: absolute;
      top: 50%;
      right: 20px;
      transform: translateY(-50%);
      cursor: pointer;
      user-select: none;
      font-size: 18px;
    }

    #formulario-profesor {
      display: none;
      margin-top: 10px;
      padding: 15px;
      border: 1px solid #e9ecef;
      border-radius: 10px;
      background-color: rgba(255, 255, 255, 0.8);
    }

    #formulario-profesor h4 {
      margin-bottom: 10px;
      color: var(--dark-color);
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
    .boton-regresar {
  display: inline-block;
  width: 100%;
  text-align: center;
  padding: 15px;
  border-radius: 50px;
  background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
  color: white;
  font-size: 16px;
  font-weight: 600;
  text-decoration: none;
  margin-top: 10px;
  box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
  transition: all 0.3s ease;
}

.boton-regresar:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
}

  </style>
</head>
<body>

<form action="controller/registrarController.php" method="POST">
  <h2>Formulario de Registro</h2>

  <div class="input-group">
    <input type="text" name="usuario" placeholder="Usuario" required />
  </div>

  <div class="input-group">
    <input type="text" name="nombre" placeholder="Nombre Completo" required />
  </div>

  <div class="input-group">
    <input 
      type="password" 
      name="contraseña" 
      id="contraseña"
      placeholder="Contraseña" 
      required
      pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}$"
      title="La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un símbolo especial." />
    <span class="toggle-password" onclick="togglePassword()">👁️</span>
  </div>

  <div class="input-group">
    <select name="rol" id="rol" required onchange="mostrarFormularioProfesor()">
      <option value="cliente">Cliente</option>
      <option value="profesor">Profesor</option>
    </select>
  </div>

  <div id="formulario-profesor">
    <h4>Información Adicional del Profesor</h4>
    <div class="input-group">
      <input type="text" name="especialidad" placeholder="Especialidad" />
    </div>
    <div class="input-group">
      <input type="text" name="titulo" placeholder="Título académico" />
    </div>
    <div class="input-group">
      <input type="text" name="experiencia" placeholder="Años de experiencia" />
    </div>
  </div>

  <button type="submit">Registrar</button>

<a href="index.php" class="boton-regresar">Regresar al inicio</a>
</form>


<script>
function togglePassword() {
  const passwordInput = document.getElementById('contraseña');
  const toggle = document.querySelector('.toggle-password');
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    toggle.textContent = "🙈";
  } else {
    passwordInput.type = "password";
    toggle.textContent = "👁️";
  }
}

function mostrarFormularioProfesor() {
  const rol = document.getElementById('rol').value;
  const formularioProfesor = document.getElementById('formulario-profesor');
  formularioProfesor.style.display = rol === 'profesor' ? 'block' : 'none';
}
</script>

</body>
</html>
