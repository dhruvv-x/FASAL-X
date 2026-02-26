<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Fasal X — AI Crop Intelligence</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --forest: #0a1f0e;
      --deep: #0d2818;
      --moss: #1a3d20;
      --leaf: #2d6a35;
      --sage: #4a8c52;
      --mint: #7bc47f;
      --gold: #d4a041;
      --amber: #e8b84b;
      --cream: #f5f0e8;
      --parchment: #faf7f2;
      --soil: #8b5e3c;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      min-height: 100vh;
      background: var(--forest);
      font-family: 'DM Sans', sans-serif;
      color: var(--cream);
      overflow-x: hidden;
    }

    /* Animated background */
    .bg-canvas {
      position: fixed;
      inset: 0;
      z-index: 0;
      background: 
        radial-gradient(ellipse 80% 60% at 20% 20%, rgba(45,106,53,0.25) 0%, transparent 60%),
        radial-gradient(ellipse 60% 80% at 80% 80%, rgba(10,31,14,0.8) 0%, transparent 70%),
        radial-gradient(ellipse 100% 100% at 50% 50%, #0a1f0e 0%, #050f07 100%);
    }

    .particles {
      position: fixed;
      inset: 0;
      z-index: 0;
      overflow: hidden;
      pointer-events: none;
    }
    .particle {
      position: absolute;
      width: 2px;
      height: 2px;
      background: var(--mint);
      border-radius: 50%;
      opacity: 0;
      animation: float-up linear infinite;
    }
    @keyframes float-up {
      0% { transform: translateY(100vh) translateX(0); opacity: 0; }
      10% { opacity: 0.4; }
      90% { opacity: 0.1; }
      100% { transform: translateY(-20px) translateX(30px); opacity: 0; }
    }

    /* Layout */
    .page-wrapper {
      position: relative;
      z-index: 1;
      display: grid;
      grid-template-columns: 1fr 1fr;
      min-height: 100vh;
    }

    /* Left panel */
    .left-panel {
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 60px 70px;
      border-right: 1px solid rgba(122,196,127,0.1);
      animation: slide-in-left 0.8s ease forwards;
    }
    @keyframes slide-in-left {
      from { opacity: 0; transform: translateX(-40px); }
      to { opacity: 1; transform: translateX(0); }
    }

    .brand-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(212,160,65,0.1);
      border: 1px solid rgba(212,160,65,0.3);
      padding: 6px 14px;
      border-radius: 100px;
      font-size: 12px;
      font-weight: 500;
      color: var(--amber);
      letter-spacing: 1px;
      text-transform: uppercase;
      margin-bottom: 32px;
      width: fit-content;
    }
    .brand-badge::before { content: '✦'; font-size: 10px; }

    .hero-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(3rem, 5vw, 4.5rem);
      line-height: 1.1;
      color: var(--cream);
      margin-bottom: 20px;
    }
    .hero-title span {
      color: var(--mint);
    }

    .hero-subtitle {
      font-size: 1.05rem;
      color: rgba(245,240,232,0.55);
      line-height: 1.7;
      max-width: 420px;
      margin-bottom: 48px;
      font-weight: 300;
    }

    .features-list {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    .feature-item {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 14px 18px;
      background: rgba(45,106,53,0.12);
      border: 1px solid rgba(122,196,127,0.08);
      border-radius: 12px;
      transition: all 0.3s ease;
    }
    .feature-item:hover {
      background: rgba(45,106,53,0.22);
      border-color: rgba(122,196,127,0.2);
      transform: translateX(4px);
    }
    .feature-icon {
      width: 38px;
      height: 38px;
      background: linear-gradient(135deg, var(--leaf), var(--sage));
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      flex-shrink: 0;
    }
    .feature-text strong {
      display: block;
      font-size: 0.88rem;
      color: var(--cream);
      font-weight: 500;
    }
    .feature-text span {
      font-size: 0.78rem;
      color: rgba(245,240,232,0.45);
    }

    /* Right panel - Form */
    .right-panel {
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 60px 70px;
      animation: slide-in-right 0.8s ease 0.2s both;
    }
    @keyframes slide-in-right {
      from { opacity: 0; transform: translateX(40px); }
      to { opacity: 1; transform: translateX(0); }
    }

    .form-header {
      margin-bottom: 36px;
    }
    .form-header h2 {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem;
      color: var(--cream);
      margin-bottom: 6px;
    }
    .form-header p {
      font-size: 0.88rem;
      color: rgba(245,240,232,0.45);
    }

    /* Step indicator */
    .steps-indicator {
      display: flex;
      align-items: center;
      gap: 0;
      margin-bottom: 36px;
    }
    .step-dot {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      border: 2px solid rgba(122,196,127,0.25);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: 600;
      color: rgba(245,240,232,0.35);
      transition: all 0.4s ease;
      cursor: pointer;
      position: relative;
    }
    .step-dot.active {
      border-color: var(--mint);
      background: rgba(122,196,127,0.15);
      color: var(--mint);
      box-shadow: 0 0 20px rgba(122,196,127,0.3);
    }
    .step-dot.done {
      border-color: var(--sage);
      background: var(--leaf);
      color: var(--cream);
    }
    .step-dot.done::after { content: '✓'; font-size: 13px; }
    .step-line {
      flex: 1;
      height: 1px;
      background: rgba(122,196,127,0.15);
      transition: background 0.4s ease;
    }
    .step-line.active { background: var(--sage); }

    /* Steps container */
    .steps-wrap {
      position: relative;
      overflow: hidden;
    }
    .step-panel {
      display: none;
      animation: step-enter 0.4s ease forwards;
    }
    .step-panel.active { display: block; }
    @keyframes step-enter {
      from { opacity: 0; transform: translateY(16px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
      margin-bottom: 14px;
    }
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 7px;
      margin-bottom: 14px;
    }
    .form-group.full { grid-column: 1 / -1; }

    label {
      font-size: 0.78rem;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: rgba(245,240,232,0.5);
    }

    input[type="text"],
    textarea {
      width: 100%;
      padding: 13px 16px;
      background: rgba(255,255,255,0.04);
      border: 1px solid rgba(122,196,127,0.12);
      border-radius: 10px;
      font-size: 0.95rem;
      font-family: 'DM Sans', sans-serif;
      color: var(--cream);
      transition: all 0.3s ease;
      outline: none;
    }
    input[type="text"]::placeholder, textarea::placeholder {
      color: rgba(245,240,232,0.2);
    }
    input[type="text"]:focus, textarea:focus {
      border-color: var(--mint);
      background: rgba(122,196,127,0.07);
      box-shadow: 0 0 0 3px rgba(122,196,127,0.08);
    }
    textarea { resize: none; min-height: 90px; }

    /* Image upload zone */
    .upload-zone {
      border: 2px dashed rgba(122,196,127,0.2);
      border-radius: 14px;
      padding: 28px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    .upload-zone:hover, .upload-zone.dragover {
      border-color: var(--mint);
      background: rgba(122,196,127,0.06);
    }
    .upload-zone input {
      position: absolute;
      inset: 0;
      opacity: 0;
      cursor: pointer;
      width: 100%;
      height: 100%;
    }
    .upload-icon { font-size: 2rem; margin-bottom: 8px; }
    .upload-text { font-size: 0.88rem; color: rgba(245,240,232,0.45); }
    .upload-text strong { color: var(--mint); }
    #preview {
      width: 100%;
      max-height: 160px;
      object-fit: cover;
      border-radius: 10px;
      margin-top: 12px;
      display: none;
      border: 1px solid rgba(122,196,127,0.2);
    }

    /* Buttons */
    .btn-row {
      display: flex;
      gap: 12px;
      margin-top: 24px;
    }
    .btn-back {
      flex: 0;
      padding: 13px 22px;
      background: transparent;
      border: 1px solid rgba(122,196,127,0.2);
      border-radius: 10px;
      color: rgba(245,240,232,0.5);
      font-size: 0.9rem;
      font-family: 'DM Sans', sans-serif;
      cursor: pointer;
      transition: all 0.2s ease;
      white-space: nowrap;
    }
    .btn-back:hover {
      border-color: rgba(122,196,127,0.4);
      color: var(--cream);
    }
    .btn-next, .btn-submit {
      flex: 1;
      padding: 14px;
      background: linear-gradient(135deg, var(--leaf) 0%, var(--sage) 100%);
      border: none;
      border-radius: 10px;
      color: white;
      font-size: 0.95rem;
      font-weight: 600;
      font-family: 'DM Sans', sans-serif;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    .btn-next::after, .btn-submit::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
      opacity: 0;
      transition: opacity 0.3s;
    }
    .btn-next:hover, .btn-submit:hover {
      transform: translateY(-1px);
      box-shadow: 0 8px 30px rgba(45,106,53,0.5);
    }
    .btn-next:hover::after, .btn-submit:hover::after { opacity: 1; }

    .btn-submit {
      background: linear-gradient(135deg, var(--gold) 0%, var(--amber) 100%);
      color: var(--forest);
    }
    .btn-submit:hover { box-shadow: 0 8px 30px rgba(212,160,65,0.4); }

    /* Progress bar */
    .progress-bar-wrap {
      width: 100%;
      height: 3px;
      background: rgba(122,196,127,0.1);
      border-radius: 10px;
      margin-bottom: 32px;
      overflow: hidden;
    }
    .progress-bar-fill {
      height: 100%;
      background: linear-gradient(to right, var(--leaf), var(--mint));
      border-radius: 10px;
      transition: width 0.5s cubic-bezier(0.4,0,0.2,1);
    }

    /* Mobile */
    @media (max-width: 900px) {
      .page-wrapper { grid-template-columns: 1fr; }
      .left-panel { display: none; }
      .right-panel { padding: 40px 28px; }
    }
  </style>
</head>
<body>

<div class="bg-canvas"></div>
<div class="particles" id="particles"></div>

<div class="page-wrapper">

  <!-- LEFT PANEL -->
  <div class="left-panel">
    <h1 class="hero-title">Grow smarter<br>with <span>Fasal X</span></h1>
    <p class="hero-subtitle">
      Describe your crop's condition and our AI will give you a precise, 
      expert-level action plan — in seconds.
    </p>
    <div class="features-list">
      <div class="feature-item">
        <div class="feature-icon">🧬</div>
        <div class="feature-text">
          <strong>Smart Diagnosis</strong>
          <span>Identifies disease, deficiency & stress patterns</span>
        </div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">💧</div>
        <div class="feature-text">
          <strong>Personalized Advice</strong>
          <span>Tailored to your soil, climate & crop type</span>
        </div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">⚡</div>
        <div class="feature-text">
          <strong>GPT-4 Powered</strong>
          <span>Latest AI model trained on agricultural science</span>
        </div>
      </div>
    </div>
  </div>

  <!-- RIGHT PANEL -->
  <div class="right-panel">
    <div class="form-header">
      <h2>Analyze Your Crop</h2>
      <p>Fill in the details below — takes less than 2 minutes</p>
    </div>

    <!-- Progress -->
    <div class="progress-bar-wrap">
      <div class="progress-bar-fill" id="progressBar" style="width: 33%"></div>
    </div>

    <!-- Steps indicator -->
    <div class="steps-indicator">
      <div class="step-dot active" id="dot1">1</div>
      <div class="step-line" id="line1"></div>
      <div class="step-dot" id="dot2">2</div>
      <div class="step-line" id="line2"></div>
      <div class="step-dot" id="dot3">3</div>
    </div>

    <!-- FORM -->
    <form action="loading.php" method="POST" enctype="multipart/form-data" id="cropForm">
      <div class="steps-wrap">

        <!-- Step 1: Crop & Soil -->
        <div class="step-panel active" id="step1">
          <div class="form-row">
            <div class="form-group">
              <label>Crop Name</label>
              <input type="text" name="cropName" placeholder="e.g. Wheat, Rice, Cotton" required />
            </div>
            <div class="form-group">
              <label>Location (City)</label>
              <input type="text" name="location" placeholder="e.g. Surat, Pune" required />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Soil Type</label>
              <input type="text" name="soilType" placeholder="e.g. Loamy, Sandy" required />
            </div>
            <div class="form-group">
              <label>Soil Color</label>
              <input type="text" name="soilColor" placeholder="e.g. Red, Black, Brown" required />
            </div>
          </div>
          <div class="btn-row">
            <button type="button" class="btn-next" onclick="goToStep(2)">Continue →</button>
          </div>
        </div>

        <!-- Step 2: Conditions -->
        <div class="step-panel" id="step2">
          <div class="form-group">
            <label>Current Climate / Weather</label>
            <input type="text" name="climate" placeholder="e.g. Sunny & Hot, Humid, Rainy" required />
          </div>
          <div class="form-group">
            <label>Fertilizers Used</label>
            <input type="text" name="fertilizersUsed" placeholder="e.g. NPK, Urea, DAP, Organic" required />
          </div>
          <div class="form-group">
            <label>Visible Symptoms</label>
            <textarea name="visiblesymptom" placeholder="Describe what you see — yellow leaves, brown spots, wilting, slow growth, etc." required></textarea>
          </div>
          <div class="btn-row">
            <button type="button" class="btn-back" onclick="goToStep(1)">← Back</button>
            <button type="button" class="btn-next" onclick="goToStep(3)">Continue →</button>
          </div>
        </div>

        <!-- Step 3: Image Upload -->
        <div class="step-panel" id="step3">
          <div class="form-group">
            <label>Upload Crop Photo</label>
            <div class="upload-zone" id="uploadZone">
              <input type="file" name="image" id="imageInput" accept="image/*" required />
              <div class="upload-icon">📸</div>
              <div class="upload-text"><strong>Click to upload</strong> or drag & drop<br><span style="font-size:0.78rem; opacity:0.5;">JPG, PNG, WEBP — max 5MB</span></div>
              <img id="preview" alt="Preview" />
            </div>
          </div>
          <div class="btn-row">
            <button type="button" class="btn-back" onclick="goToStep(2)">← Back</button>
            <button type="submit" class="btn-submit">✦ Analyze My Crop</button>
          </div>
        </div>

      </div>
    </form>
  </div>
</div>

<script>
  // Particles
  const container = document.getElementById('particles');
  for (let i = 0; i < 25; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.left = Math.random() * 100 + 'vw';
    p.style.width = p.style.height = (Math.random() * 3 + 1) + 'px';
    p.style.animationDuration = (Math.random() * 15 + 10) + 's';
    p.style.animationDelay = (Math.random() * 10) + 's';
    p.style.opacity = Math.random() * 0.5;
    container.appendChild(p);
  }

  // Step navigation
  function goToStep(n) {
    // Validate current step
    const currentStep = document.querySelector('.step-panel.active');
    const inputs = currentStep.querySelectorAll('input[required], textarea[required]');
    let valid = true;
    inputs.forEach(inp => {
      if (!inp.value.trim()) {
        inp.style.borderColor = '#e05555';
        inp.focus();
        valid = false;
      } else {
        inp.style.borderColor = '';
      }
    });
    if (!valid && n > parseInt(currentStep.id.replace('step','')) ) return;

    // Hide all
    document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('step' + n).classList.add('active');

    // Update dots
    [1,2,3].forEach(i => {
      const dot = document.getElementById('dot' + i);
      dot.className = 'step-dot';
      if (i < n) { dot.className = 'step-dot done'; dot.textContent = ''; }
      else if (i === n) { dot.className = 'step-dot active'; dot.textContent = i; }
      else { dot.textContent = i; }
    });

    // Update lines
    document.getElementById('line1').className = 'step-line' + (n > 1 ? ' active' : '');
    document.getElementById('line2').className = 'step-line' + (n > 2 ? ' active' : '');

    // Progress bar
    const prog = { 1: 33, 2: 66, 3: 100 };
    document.getElementById('progressBar').style.width = prog[n] + '%';
  }

  // Image preview
  const imageInput = document.getElementById('imageInput');
  const preview = document.getElementById('preview');
  const uploadZone = document.getElementById('uploadZone');

  imageInput.addEventListener('change', () => {
    const file = imageInput.files[0];
    if (file) {
      preview.src = URL.createObjectURL(file);
      preview.style.display = 'block';
      uploadZone.querySelector('.upload-icon').textContent = '✅';
      uploadZone.querySelector('.upload-text').innerHTML = '<strong>' + file.name + '</strong><br><span style="font-size:0.78rem;opacity:0.5;">Ready to submit</span>';
    }
  });

  // Drag & drop visual
  uploadZone.addEventListener('dragover', e => { e.preventDefault(); uploadZone.classList.add('dragover'); });
  uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('dragover'));
  uploadZone.addEventListener('drop', e => { e.preventDefault(); uploadZone.classList.remove('dragover'); });
</script>
</body>
</html>
