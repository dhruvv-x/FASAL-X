<?php
session_start();
$_SESSION['form_data'] = $_POST;
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $_SESSION['imageData'] = base64_encode(file_get_contents($_FILES['image']['tmp_name']));
    $_SESSION['imageMime'] = $_FILES['image']['type'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Fasal X — Analyzing Your Crop...</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --forest: #0a1f0e;
      --leaf: #2d6a35;
      --sage: #4a8c52;
      --mint: #7bc47f;
      --gold: #d4a041;
      --amber: #e8b84b;
      --cream: #f5f0e8;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      min-height: 100vh;
      background: var(--forest);
      font-family: 'DM Sans', sans-serif;
      color: var(--cream);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }
    .bg {
      position: fixed;
      inset: 0;
      background: radial-gradient(ellipse 80% 80% at 50% 50%, rgba(45,106,53,0.2) 0%, transparent 70%);
    }
    .loading-card {
      position: relative;
      z-index: 1;
      text-align: center;
      padding: 60px 80px;
      max-width: 480px;
    }
    .ring-wrap {
      position: relative;
      width: 120px;
      height: 120px;
      margin: 0 auto 40px;
    }
    .ring {
      position: absolute;
      inset: 0;
      border-radius: 50%;
      border: 2px solid transparent;
    }
    .ring-outer {
      border-top-color: var(--mint);
      border-right-color: rgba(122,196,127,0.3);
      animation: spin 1.6s linear infinite;
    }
    .ring-inner {
      inset: 14px;
      border-bottom-color: var(--gold);
      border-left-color: rgba(212,160,65,0.3);
      animation: spin-rev 2.2s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes spin-rev { to { transform: rotate(-360deg); } }
    .ring-center {
      position: absolute;
      inset: 30px;
      background: rgba(45,106,53,0.15);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 26px;
      animation: pulse 2s ease-in-out infinite;
    }
    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 0.8; }
      50% { transform: scale(1.12); opacity: 1; }
    }
    h2 {
      font-family: 'Playfair Display', serif;
      font-size: 1.9rem;
      color: var(--cream);
      margin-bottom: 10px;
    }
    .sub {
      font-size: 0.9rem;
      color: rgba(245,240,232,0.45);
      margin-bottom: 40px;
      font-weight: 300;
    }
    .analysis-steps {
      display: flex;
      flex-direction: column;
      gap: 12px;
      text-align: left;
    }
    .a-step {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 12px 16px;
      background: rgba(255,255,255,0.03);
      border: 1px solid rgba(122,196,127,0.07);
      border-radius: 10px;
      opacity: 0;
      transform: translateX(-10px);
      animation: step-show 0.5s ease forwards;
    }
    .a-step:nth-child(1) { animation-delay: 0.3s; }
    .a-step:nth-child(2) { animation-delay: 1.2s; }
    .a-step:nth-child(3) { animation-delay: 2.2s; }
    .a-step:nth-child(4) { animation-delay: 3.2s; }
    @keyframes step-show { to { opacity: 1; transform: translateX(0); } }
    .a-step-icon {
      width: 32px;
      height: 32px;
      background: rgba(45,106,53,0.25);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      flex-shrink: 0;
    }
    .a-step-label { font-size: 0.85rem; color: rgba(245,240,232,0.7); }
    .a-step-label strong {
      display: block;
      font-size: 0.88rem;
      color: var(--cream);
      font-weight: 500;
    }
  </style>
</head>
<body>
<div class="bg"></div>
<div class="loading-card">
  <div class="ring-wrap">
    <div class="ring ring-outer"></div>
    <div class="ring ring-inner"></div>
    <div class="ring-center">🌿</div>
  </div>
  <h2>Analyzing Your Crop</h2>
  <p class="sub">Our AI is studying your crop data.<br>This usually takes 10–20 seconds.</p>
  <div class="analysis-steps">
    <div class="a-step">
      <div class="a-step-icon">📋</div>
      <div class="a-step-label"><strong>Reading Crop Details</strong>Soil type, location, climate loaded</div>
    </div>
    <div class="a-step">
      <div class="a-step-icon">🔬</div>
      <div class="a-step-label"><strong>Processing Symptoms</strong>Matching against disease patterns</div>
    </div>
    <div class="a-step">
      <div class="a-step-icon">🤖</div>
      <div class="a-step-label"><strong>Consulting GPT-4</strong>Generating expert recommendations</div>
    </div>
    <div class="a-step">
      <div class="a-step-icon">✨</div>
      <div class="a-step-label"><strong>Preparing Your Report</strong>Almost ready...</div>
    </div>
  </div>
</div>

<form id="submitForm" action="submit.php" method="POST" style="display:none;">
  <?php foreach ($_POST as $key => $val): ?>
    <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($val) ?>">
  <?php endforeach; ?>
</form>

<script>
  setTimeout(() => {
    document.getElementById('submitForm').submit();
  }, 4500);
</script>
</body>
</html>