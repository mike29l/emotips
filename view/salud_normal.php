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
// normal.php
// Rompecabezas deslizante 3x3 (estado "Normal")
// Instrucciones: coloca este archivo en tu servidor (ej. htdocs) y ábrelo en el navegador.
// Para cambiar la imagen del rompecabezas, edita la variable `PUZZLE_IMAGE` más abajo.
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Rompecabezas 3×3 — Relajación</title>
  <style>
    /* ---------- Estilos generales ---------- */
    :root{
      --bg1: #f0f7ff;
      --card: #ffffff;
      --accent: #6b46c1;
      --muted: #6b7280;
    }
    *{box-sizing:border-box}
    body{
      margin:0;
      font-family: "Inter", "Segoe UI", system-ui, Arial;
      background: linear-gradient(180deg,var(--bg1), #f8f0ff);
      color:#0f172a;
      display:flex;
      align-items:center;
      justify-content:center;
      min-height:100vh;
      padding:20px;
    }
    .wrap{
      width:100%;
      max-width:960px;
      display:grid;
      grid-template-columns: 1fr 420px;
      gap:28px;
      align-items:start;
    }
    @media (max-width:900px){
      .wrap{ grid-template-columns: 1fr; padding-bottom:40px }
    }

    /* ---------- Panel del rompecabezas ---------- */
    .panel{
      background:var(--card);
      border-radius:16px;
      padding:20px;
      box-shadow: 0 8px 30px rgba(15,23,42,0.08);
    }
    h1{ margin:0 0 8px; color:var(--accent) }
    p.lead{ margin:0 0 12px; color:var(--muted) }

    /* ---------- Puzzle grid ---------- */
    .puzzle{
      width:100%;
      max-width:420px;
      aspect-ratio:1/1;
      margin:auto;
      display:grid;
      grid-template-columns: repeat(3, 1fr);
      grid-template-rows: repeat(3, 1fr);
      gap:6px;
      touch-action: manipulation;
    }
    .tile{
      background:#eee;
      border-radius:10px;
      display:flex;
      align-items:center;
      justify-content:center;
      font-size:18px;
      font-weight:700;
      color:#fff;
      user-select:none;
      cursor:pointer;
      transition: transform .14s ease, box-shadow .14s ease;
      box-shadow: 0 6px 16px rgba(15,23,42,0.06);
      position:relative;
      overflow:hidden;
    }
    .tile.blank{
      background:transparent;
      box-shadow:none;
      cursor:default;
    }

    /* background-image slices will be applied inline via JS for each tile */
    .controls{
      margin-top:18px;
      display:flex;
      gap:10px;
      justify-content:center;
      flex-wrap:wrap;
    }
    .btn{
      padding:10px 14px;
      border-radius:10px;
      border:0;
      cursor:pointer;
      font-weight:600;
    }
    .btn-primary{ background:var(--accent); color:#fff }
    .btn-ghost{ background:transparent; border:1px solid #e6e6e9; color:var(--muted) }

    /* ---------- Sidebar (info + shuffle) ---------- */
    .sidebar{ position:relative }
    .card{
      background:var(--card);
      border-radius:14px;
      padding:18px;
      box-shadow: 0 6px 20px rgba(15,23,42,0.04);
    }
    .stat{ font-weight:700; font-size:20px; color:var(--accent) }
    .small{ color:var(--muted); font-size:14px }

    /* modal message */
    .modal{
      position:fixed;
      inset:0;
      display:none;
      align-items:center;
      justify-content:center;
      background:rgba(2,6,23,0.4);
      z-index:9999;
      padding:20px;
    }
    .modal.show{ display:flex }
    .modal .box{
      background:white; border-radius:14px; padding:24px; text-align:center; max-width:420px; width:100%;
      box-shadow:0 10px 40px rgba(2,6,23,0.2);
    }
    .confetti{
      margin-top:12px;
      height:80px;
    }
  </style>
</head>
<body>
  <div class="wrap">
    <!-- Panel principal con rompecabezas -->
    <section class="panel">
      <h1>Rompecabezas relajante 3×3</h1>
      <p class="lead">Mueve las piezas haciendo clic en una pieza adyacente al espacio vacío. Intenta reconstruir la imagen. Disfruta sin prisas ✨</p>

      <!-- Contenedor del puzzle -->
      <div id="puzzle" class="puzzle" aria-label="Rompecabezas 3 por 3"></div>

      <div class="controls" style="margin-top:12px">
        <button id="shuffleBtn" class="btn btn-primary">Barajar</button>
        <button id="solveBtn" class="btn btn-ghost">Mostrar solución</button>
      </div>
    </section>

    <!-- Sidebar con información y controles -->
    <aside class="sidebar">
      <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <div>
            <div class="small">Movimientos</div>
            <div id="moves" class="stat">0</div>
          </div>
          <div>
            <div class="small">Tiempo</div>
            <div id="timer" class="stat">00:00</div>
          </div>
        </div>

        <hr style="margin:14px 0">

        <div>
          <div class="small">Imagen actual</div>
          <div style="margin-top:8px">
            <select id="imageSelect" style="width:100%;padding:8px;border-radius:8px">
              <option value="default">Imagen por defecto (relajante)</option>
              <option value="https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=1200&q=80">Campo y cielo</option>
              <option value="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=1200&q=80">Montaña</option>
              <option value="https://images.unsplash.com/photo-1502082553048-f009c37129b9?w=1200&q=80">Bosque</option>
            </select>
          </div>

          <hr style="margin:14px 0">

          <div class="small">Cómo jugar</div>
          <p class="small" style="margin-top:8px">Haz clic en una pieza que esté al lado del espacio vacío para moverla. Pulsa <strong>Barajar</strong> para desordenar de forma aleatoria y siempre resoluble (se generan movimientos válidos).</p>
        </div>
      </div>
    </aside>
  </div>

  <!-- Modal felicitación -->
  <div id="modal" class="modal" role="dialog" aria-modal="true">
    <div class="box">
      <h2>¡Felicidades! 🎉</h2>
      <p>Has completado el rompecabezas. Tómate un momento para respirar.</p>
      <div style="margin-top:12px">
        <button id="closeModal" class="btn btn-primary">Cerrar</button>
        <button id="againBtn" class="btn btn-ghost" style="margin-left:10px">Jugar otra vez</button>
      </div>
      <div class="confetti" id="confetti"></div>
    </div>
  </div>

  <script>
    /* ---------- Configuración ---------- */
    // Cambia PUZZLE_IMAGE si quieres otra imagen por defecto.
    const PUZZLE_IMAGE = "https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=1200&q=80";

    // referencias al DOM
    const puzzleEl = document.getElementById('puzzle');
    const movesEl = document.getElementById('moves');
    const timerEl = document.getElementById('timer');
    const shuffleBtn = document.getElementById('shuffleBtn');
    const solveBtn = document.getElementById('solveBtn');
    const imageSelect = document.getElementById('imageSelect');
    const modal = document.getElementById('modal');
    const closeModal = document.getElementById('closeModal');
    const againBtn = document.getElementById('againBtn');

    // estado
    let board = [];     // array length 9 with tile indices. 0..7 tiles and 8 as blank index or we keep id mapping
    let blankIndex = 8; // position of blank (0..8)
    let moves = 0;
    let timerInterval = null;
    let seconds = 0;
    let currentImage = PUZZLE_IMAGE;

    // initialize image select default to 'default' -> use PUZZLE_IMAGE
    imageSelect.addEventListener('change', (e)=>{
      const val = e.target.value;
      if(val === 'default') currentImage = PUZZLE_IMAGE;
      else currentImage = val;
      renderTiles(); // reapply backgrounds
    });

    // Create initial solved board
    function initBoard(){
      board = [];
      for(let i=0;i<9;i++) board.push(i); // 0..8 where 8 will be blank in UI
      blankIndex = 8;
      moves = 0;
      seconds = 0;
      updateStats();
    }

    // Render tiles based on board array
    function renderTiles(){
      puzzleEl.innerHTML = '';
      // tile size used in background-size/position: 300% because 3x3
      const bg = currentImage;
      for(let pos = 0; pos < 9; pos++){
        const tileValue = board[pos];
        const tile = document.createElement('button');
        tile.className = 'tile';
        tile.setAttribute('data-pos', pos);
        tile.setAttribute('data-val', tileValue);
        tile.setAttribute('aria-label', 'pieza ' + (tileValue+1));
        tile.style.outline = 'none';
        // blank tile
        if(tileValue === 8){
          tile.classList.add('blank');
        } else {
          // Use background-position to show correct slice of the image
          // row, col of the tileValue in the solved image
          const row = Math.floor(tileValue / 3);
          const col = tileValue % 3;
          // background-size 300% makes each tile background fit the entire puzzle area sliced
          tile.style.backgroundImage = `url("${bg}")`;
          tile.style.backgroundSize = '300% 300%';
          tile.style.backgroundPosition = `${col*50}% ${row*50}%`; // 0%, 50%, 100% etc -> using 50% steps matches 3x3
          // also show a subtle number for accessibility
          const numLabel = document.createElement('span');
          numLabel.style.position = 'absolute';
          numLabel.style.right = '8px';
          numLabel.style.bottom = '8px';
          numLabel.style.background = 'rgba(0,0,0,0.28)';
          numLabel.style.padding = '4px 6px';
          numLabel.style.borderRadius = '8px';
          numLabel.style.fontSize = '12px';
          numLabel.textContent = tileValue+1;
          tile.appendChild(numLabel);
        }

        tile.addEventListener('click', onTileClick);
        puzzleEl.appendChild(tile);
      }
    }

    // Click handler for moving tiles
    function onTileClick(e){
      const tile = e.currentTarget;
      const pos = Number(tile.getAttribute('data-pos'));
      // if clicked blank, ignore
      if(board[pos] === 8) return;
      // check adjacency: pos and blankIndex are adjacent in 3x3 grid?
      if(isAdjacent(pos, blankIndex)){
        // swap
        [board[pos], board[blankIndex]] = [board[blankIndex], board[pos]];
        blankIndex = pos;
        moves++;
        updateStats();
        renderTiles();
        // if first move start timer
        if(moves === 1) startTimer();
        // check win
        if(checkSolved()){
          stopTimer();
          showModal();
        }
      }
    }

    // adjacency check (grid 3x3)
    function isAdjacent(a,b){
      const ax = a % 3, ay = Math.floor(a/3);
      const bx = b % 3, by = Math.floor(b/3);
      return (Math.abs(ax-bx) + Math.abs(ay-by)) === 1;
    }

    // shuffle by making N random valid moves from solved position (ensures solvable)
    function shuffle(times=100){
      initBoard();
      let lastMove = -1;
      for(let i=0;i<times;i++){
        const neighbors = getNeighbors(blankIndex);
        // avoid undoing last move
        const possible = neighbors.filter(n => n !== lastMove);
        const moveTo = possible[Math.floor(Math.random() * possible.length)];
        // make move: swap blank with moveTo
        [board[blankIndex], board[moveTo]] = [board[moveTo], board[blankIndex]];
        lastMove = blankIndex;
        blankIndex = moveTo;
      }
      moves = 0;
      seconds = 0;
      updateStats();
      renderTiles();
    }

    // neighbors (positions adjacent to index)
    function getNeighbors(index){
      const arr = [];
      const x = index % 3, y = Math.floor(index/3);
      if(x>0) arr.push(index-1);
      if(x<2) arr.push(index+1);
      if(y>0) arr.push(index-3);
      if(y<2) arr.push(index+3);
      return arr;
    }

    // check if solved (board values in order 0..8)
    function checkSolved(){
      for(let i=0;i<9;i++){
        if(board[i] !== i) return false;
      }
      return true;
    }

    // Timer functions
    function startTimer(){
      if(timerInterval) clearInterval(timerInterval);
      timerInterval = setInterval(()=> {
        seconds++;
        updateStats();
      },1000);
    }
    function stopTimer(){
      if(timerInterval) clearInterval(timerInterval);
      timerInterval = null;
    }

    // update UI of moves & timer
    function updateStats(){
      movesEl.textContent = moves;
      const mm = String(Math.floor(seconds/60)).padStart(2,'0');
      const ss = String(seconds%60).padStart(2,'0');
      timerEl.textContent = `${mm}:${ss}`;
    }

    // show modal
    function showModal(){
      modal.classList.add('show');
      // small confetti effect (simple)
      const conf = document.getElementById('confetti');
      conf.innerHTML = '';
      for(let i=0;i<30;i++){
        const c = document.createElement('div');
        c.style.width='8px'; c.style.height='12px';
        c.style.background=['#fde68a','#fca5a5','#bbf7d0','#c7d2fe'][Math.floor(Math.random()*4)];
        c.style.position='absolute';
        c.style.left = (Math.random()*100)+'%';
        c.style.top = (Math.random()*40)+'%';
        c.style.opacity = Math.random()*0.9+0.2;
        c.style.transform = `translateY(${Math.random()*40}px) rotate(${Math.random()*360}deg)`;
        conf.appendChild(c);
      }
    }

    // hide modal and shuffle new
    closeModal.addEventListener('click', ()=>{
      modal.classList.remove('show');
    });
    againBtn.addEventListener('click', ()=>{
      modal.classList.remove('show');
      shuffle(120);
    });

    // show solution (reset to solved)
    solveBtn.addEventListener('click', ()=>{
      initBoard();
      renderTiles();
      stopTimer();
    });

    // shuffle button
    shuffleBtn.addEventListener('click', ()=> shuffle(120));

    // initial setup
    initBoard();
    renderTiles();
    // optional: initial shuffle
    shuffle(80);

    // Allow keyboard arrows to move if desired (optional UX)
    window.addEventListener('keydown', (e)=>{
      if(e.key.startsWith('Arrow')){
        const dir = e.key.replace('Arrow','').toLowerCase();
        let target = null;
        const bx = blankIndex % 3, by = Math.floor(blankIndex/3);
        if(dir === 'left' && bx < 2) target = blankIndex+1;
        if(dir === 'right' && bx > 0) target = blankIndex-1;
        if(dir === 'up' && by < 2) target = blankIndex+3;
        if(dir === 'down' && by > 0) target = blankIndex-3;
        if(target !== null){
          [board[blankIndex], board[target]] = [board[target], board[blankIndex]];
          blankIndex = target;
          moves++;
          if(moves === 1) startTimer();
          updateStats(); renderTiles();
          if(checkSolved()){
            stopTimer(); showModal();
          }
        }
      }
    });
  </script>
</body>
</html>
