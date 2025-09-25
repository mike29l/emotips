<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
session_start();
if (!isset($_SESSION['cuestionario_completado']) || $_SESSION['cuestionario_completado'] !== true) {
    header('Location: formulario_salud.php');
    exit;
}
?>
<?php
// bajo.php
// Jardín Zen interactivo (estado "Bajo")
// Instrucciones: coloca este archivo en tu servidor y ábrelo en el navegador.
// No hay objetivo ni puntuación; arrastra elementos desde la paleta al jardín y reposiciónalos.
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Jardín Zen — Relajación</title>
  <style>
    /* ---------- Estilos ---------- */
    :root{
      --bg: linear-gradient(180deg,#fff8f0,#eaf6f1);
      --panel: #ffffff;
      --muted: #6b7280;
      --accent: #60a5fa;
    }
    *{box-sizing:border-box}
    body{
      margin:0; font-family:Inter, system-ui, Arial;
      background: var(--bg);
      color:#0b1220;
      min-height:100vh;
      padding:24px;
      display:flex; align-items:flex-start; justify-content:center;
    }
    .layout{
      width:100%;
      max-width:1100px;
      display:grid;
      grid-template-columns: 300px 1fr;
      gap:22px;
      align-items:start;
    }
    @media (max-width:900px){
      .layout{ grid-template-columns: 1fr; }
    }

    /* Paleta */
    .palette{
      background:var(--panel);
      border-radius:12px;
      padding:14px;
      box-shadow:0 8px 28px rgba(2,6,23,0.06);
    }
    .palette h2{ margin:0 0 10px; color: #134e4a; }
    .items{ display:flex; flex-wrap:wrap; gap:10px; margin-top:10px; }
    .palette .item{
      width:70px; height:70px; border-radius:10px; display:flex;align-items:center;justify-content:center;
      cursor:grab; user-select:none; padding:6px;
      box-shadow: 0 6px 14px rgba(2,6,23,0.06);
      background:linear-gradient(180deg,#fff,#f3f4f6);
    }
    .palette .item img{ max-width:100%; max-height:100%; pointer-events:none }

    /* Jardin */
    .garden-wrap{
      background:transparent;
    }
    .garden{
      background: linear-gradient(180deg,#e6f9f0,#f7fff9);
      min-height:520px; border-radius:14px;
      padding:16px; box-shadow:0 8px 28px rgba(2,6,23,0.06);
      position:relative; overflow:hidden;
    }
    .hint{ color:var(--muted); font-size:13px; margin-bottom:8px; }
    .garden .draggable{
      position:absolute; touch-action:none; /* we will handle pointer events */
      cursor:grab; user-select:none; transform-origin:center;
      transition: box-shadow .12s ease;
    }
    .garden .draggable:active{ cursor:grabbing }
    .controls{ margin-top:12px; display:flex; gap:8px; }
    .btn{ padding:8px 12px; border-radius:10px; font-weight:700; cursor:pointer; border:0; }
    .btn-clear{ background:#ef4444; color:white }
    .btn-snap{ background:#10b981; color:white }

    /* small legend */
    .legend{ margin-top:12px; color:var(--muted); font-size:13px }
  </style>
</head>
<body>
  <div class="layout">
    <!-- Paleta -->
    <aside class="palette" aria-label="Paleta de elementos">
      <h2>Paleta relajante</h2>
      <p class="hint">Arrastra un elemento al jardín. Tócalo para moverlo (móvil) o arrástralo con el ratón.</p>

      <div class="items" id="palette">
        <!-- Los elementos son simples imágenes SVG inline para evitar dependencias externas -->
        <div class="item" draggable="true" data-type="flower" title="Flor">
          <!-- flor simple -->
          <svg width="44" height="44" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="2.5" fill="#f59e0b"/>
            <path d="M12 2c1.1 0 1.9 1.9 3.7 3.1 1.8 1.2 3.6 1 4.1 2.7.4 1.4-.6 2.6-1.6 3.4C16.3 12.8 12 18 12 18s-4.3-5.2-6.2-6.8C4.9 9.8 3.9 8.6 4.3 7.2 4.7 5.5 6.5 5.3 8.3 4.1 10.1 2.9 10.9 2 12 2z" fill="#fb7185"/>
          </svg>
        </div>

        <div class="item" draggable="true" data-type="tree" title="Árbol">
          <svg width="44" height="44" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2c1.6 0 3 1.2 3.4 2.8 1.8.2 3.3 1.7 3.3 3.6 0 1.6-1.1 2.9-2.6 3.4.3.6.6 1.3.6 2.1 0 1.9-1.5 3.5-3.4 3.5H11v3h-2v-3H7.6C5.7 18 4.2 16.4 4.2 14.5c0-.8.3-1.6.6-2.2C3.3 11.9 2.2 10.6 2.2 9c0-2 1.5-3.6 3.4-3.6C6.2 3.9 8.4 2 12 2z" fill="#2dd4bf"/>
          </svg>
        </div>

        <div class="item" draggable="true" data-type="rock" title="Roca">
          <svg width="44" height="44" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 15c0-2 2-6 6-6s6 4 6 6-2 4-6 4S4 17 4 15z" fill="#9ca3af"/>
          </svg>
        </div>

        <div class="item" draggable="true" data-type="bush" title="Arbusto">
          <svg width="44" height="44" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 5c2 0 3.6 1.5 3.9 3.4 2.3.3 4.1 2.2 4.1 4.5 0 2.5-2 4.6-4.5 4.6H8.5C6 17 4 14.9 4 12.4 4 10 5.9 8 8.4 8c.5-1.9 2.3-3 3.6-3z" fill="#34d399"/>
          </svg>
        </div>

        <div class="item" draggable="true" data-type="lantern" title="Linterna de piedra">
          <svg width="44" height="44" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="8" y="6" width="8" height="6" rx="1.2" fill="#fde68a"/>
            <path d="M5 18h14v1a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v-1z" fill="#f59e0b"/>
          </svg>
        </div>
      </div>

      <div class="legend">
        <p>Tips: Doble clic para eliminar un elemento del jardín. Usa el botón Limpiar para empezar de nuevo.</p>
      </div>
    </aside>

    <!-- Jardin -->
    <main class="garden-wrap">
      <div class="garden" id="garden" aria-label="Área del jardín">
        <!-- elementos añadidos aparecerán aquí como .draggable -->
      </div>

      <div class="controls">
        <button class="btn btn-clear" id="clearBtn">Limpiar jardín</button>
        <button class="btn btn-snap" id="snapBtn">Alinear a rejilla</button>
      </div>
    </main>
  </div>

  <script>
    
    /* ---------- Jardín Zen: lógica ---------- */

    // Elementos
    const palette = document.getElementById('palette');
    const garden = document.getElementById('garden');
    const clearBtn = document.getElementById('clearBtn');
    const snapBtn = document.getElementById('snapBtn');

    // Contador simple para dar id único a cada elemento creado
    let elementCounter = 0;

    // When dragging from palette, we copy an element into the garden.
    palette.querySelectorAll('.item').forEach(item => {
      item.addEventListener('dragstart', (e) => {
        e.dataTransfer.setData('text/plain', item.getAttribute('data-type'));
        // small effect
        e.dataTransfer.effectAllowed = 'copy';
      });

      // For touch devices, allow long press to "pick up" -> we will rely on pointer events below
    });

    // Allow drop on garden
    garden.addEventListener('dragover', (e) => {
      e.preventDefault();
      e.dataTransfer.dropEffect = 'copy';
    });

    garden.addEventListener('drop', (e) => {
      e.preventDefault();
      const type = e.dataTransfer.getData('text/plain');
      const rect = garden.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      createInGarden(type, x, y);
    });

    // Create element in garden at x,y (relative to garden)
    function createInGarden(type, x, y){
      elementCounter++;
      const el = document.createElement('div');
      el.className = 'draggable';
      el.setAttribute('data-id', elementCounter);
      el.setAttribute('data-type', type);
      el.style.left = (x - 35) + 'px';
      el.style.top = (y - 35) + 'px';
      el.style.width = '70px';
      el.style.height = '70px';
      el.style.borderRadius = '12px';
      el.style.display = 'flex';
      el.style.alignItems = 'center';
      el.style.justifyContent = 'center';
      el.style.boxShadow = '0 8px 18px rgba(2,6,23,0.08)';
      el.style.transition = 'transform .12s ease';

      // Create inner SVG depending on type
      el.innerHTML = getSVGForType(type);
      garden.appendChild(el);

      // Make it draggable/repositionable with pointer events (works on mobile & desktop)
      makePointerDraggable(el);

      // double click to remove
      el.addEventListener('dblclick', ()=> {
        el.remove();
      });
    }

    // Helper: returns inline SVG string for type
    function getSVGForType(type){
      switch(type){
        case 'flower':
          return `<svg width="100%" height="100%" viewBox="0 0 24 24"><circle cx="12" cy="12" r="2.5" fill="#f59e0b"/><path d="M12 2c1.1 0 1.9 1.9 3.7 3.1 1.8 1.2 3.6 1 4.1 2.7.4 1.4-.6 2.6-1.6 3.4C16.3 12.8 12 18 12 18s-4.3-5.2-6.2-6.8C4.9 9.8 3.9 8.6 4.3 7.2 4.7 5.5 6.5 5.3 8.3 4.1 10.1 2.9 10.9 2 12 2z" fill="#fb7185"/></svg>`;
        case 'tree':
          return `<svg width="100%" height="100%" viewBox="0 0 24 24"><path d="M12 2c1.6 0 3 1.2 3.4 2.8 1.8.2 3.3 1.7 3.3 3.6 0 1.6-1.1 2.9-2.6 3.4.3.6.6 1.3.6 2.1 0 1.9-1.5 3.5-3.4 3.5H11v3h-2v-3H7.6C5.7 18 4.2 16.4 4.2 14.5c0-.8.3-1.6.6-2.2C3.3 11.9 2.2 10.6 2.2 9c0-2 1.5-3.6 3.4-3.6C6.2 3.9 8.4 2 12 2z" fill="#2dd4bf"/></svg>`;
        case 'rock':
          return `<svg width="100%" height="100%" viewBox="0 0 24 24"><path d="M4 15c0-2 2-6 6-6s6 4 6 6-2 4-6 4S4 17 4 15z" fill="#9ca3af"/></svg>`;
        case 'bush':
          return `<svg width="100%" height="100%" viewBox="0 0 24 24"><path d="M12 5c2 0 3.6 1.5 3.9 3.4 2.3.3 4.1 2.2 4.1 4.5 0 2.5-2 4.6-4.5 4.6H8.5C6 17 4 14.9 4 12.4 4 10 5.9 8 8.4 8c.5-1.9 2.3-3 3.6-3z" fill="#34d399"/></svg>`;
        case 'lantern':
          return `<svg width="100%" height="100%" viewBox="0 0 24 24"><rect x="8" y="6" width="8" height="6" rx="1.2" fill="#fde68a"/><path d="M5 18h14v1a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v-1z" fill="#f59e0b"/></svg>`;
        default:
          return `<div style="width:100%;height:100%;background:#f3f4f6;border-radius:10px"></div>`;
      }
    }

    // Make element draggable using pointer events (works on mobile)
    function makePointerDraggable(el){
      let startX=0, startY=0, elX=0, elY=0, dragging=false;
      el.addEventListener('pointerdown', (ev) => {
        ev.preventDefault();
        el.setPointerCapture(ev.pointerId);
        dragging = true;
        el.style.transition = 'none';
        startX = ev.clientX;
        startY = ev.clientY;
        elX = parseFloat(el.style.left || 0);
        elY = parseFloat(el.style.top || 0);
        el.style.zIndex = 1000;
      });

      window.addEventListener('pointermove', (ev) => {
        if(!dragging) return;
        const dx = ev.clientX - startX;
        const dy = ev.clientY - startY;
        el.style.left = (elX + dx) + 'px';
        el.style.top = (elY + dy) + 'px';
      });

      window.addEventListener('pointerup', (ev) => {
        if(!dragging) return;
        dragging = false;
        el.releasePointerCapture && el.releasePointerCapture(ev.pointerId);
        el.style.transition = 'transform .12s ease';
        el.style.zIndex = 1;
      });

      // on double tap/double click remove (handled earlier by dblclick)
    }

    // make palette items also draggable by touch: we listen pointerdown and then create element
    palette.querySelectorAll('.item').forEach(item=>{
      item.addEventListener('pointerdown', (ev)=>{
        // for touch, if long press moves, we won't create immediately; keep simple: on pointerup in garden create via drop events,
        // but many mobile browsers don't trigger drag events. We'll also support creating on tap: user taps item and then tap garden to place.
        // To keep UX simple: support "tap-to-place": single tap selects type, then next tap in garden places there.
      });

      // Implement tap-to-place: listen click
      item.addEventListener('click', (ev)=>{
        const type = item.getAttribute('data-type');
        // temporary instruction: "Toca el jardín para colocar"
        garden.style.outline = '3px dashed rgba(34,197,94,0.25)';
        const once = function(e){
          garden.style.outline='';
          // coordinates relative
          const rect = garden.getBoundingClientRect();
          let x, y;
          if(e.type === 'click'){
            x = e.clientX - rect.left;
            y = e.clientY - rect.top;
          } else if(e.type === 'pointerdown'){
            x = e.clientX - rect.left;
            y = e.clientY - rect.top;
          }
          createInGarden(type, x, y);
          garden.removeEventListener('click', once);
          garden.removeEventListener('pointerdown', once);
        };
        garden.addEventListener('click', once);
        garden.addEventListener('pointerdown', once);
      });
    });

    // Clear garden
    clearBtn.addEventListener('click', ()=>{
      // remove all .draggable children
      garden.querySelectorAll('.draggable').forEach(el=>el.remove());
    });

    // Snap to grid (align elements to 20px grid)
    snapBtn.addEventListener('click', ()=>{
      garden.querySelectorAll('.draggable').forEach(el=>{
        const left = parseFloat(el.style.left || 0);
        const top = parseFloat(el.style.top || 0);
        const snapX = Math.round(left / 20) * 20;
        const snapY = Math.round(top / 20) * 20;
        el.style.left = snapX + 'px';
        el.style.top = snapY + 'px';
      });
    });

    // Accept drops also when using native dragstart from palette in some browsers (already handled)
    garden.addEventListener('dragenter', (e)=> e.preventDefault());
    garden.addEventListener('dragleave', (e)=> e.preventDefault());
  </script>
  
</body>
</html>
