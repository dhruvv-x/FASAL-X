<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "agridoc_db";
$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) die("Connection failed");

$id = mysqli_real_escape_string($conn, $_GET['id']);
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM crops WHERE id='$id'"));
if (!$row) { echo "❌ No result found."; exit(); }

$response = $row['api_response'];
// Split response into paragraphs/points for nicer display
$points = array_filter(array_map('trim', preg_split('/\n*\d+\.\s*(?=DIAGNOSIS|IMMEDIATE|LONG)/', $response)));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Fasal X — Your AI Report</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
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
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      min-height: 100vh;
      background: var(--forest);
      font-family: 'DM Sans', sans-serif;
      color: var(--cream);
    }
    .bg-fixed {
      position: fixed;
      inset: 0;
      background:
        radial-gradient(ellipse 70% 50% at 10% 10%, rgba(45,106,53,0.2) 0%, transparent 60%),
        radial-gradient(ellipse 50% 60% at 90% 90%, rgba(212,160,65,0.06) 0%, transparent 60%),
        #0a1f0e;
      z-index: 0;
    }
    .page {
      position: relative;
      z-index: 1;
      max-width: 860px;
      margin: 0 auto;
      padding: 60px 40px;
      animation: page-in 0.7s ease both;
    }
    @keyframes page-in {
      from { opacity: 0; transform: translateY(24px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Header */
    .report-header {
      text-align: center;
      margin-bottom: 52px;
    }
    .badge-row {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-bottom: 20px;
    }
    .badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 5px 14px;
      border-radius: 100px;
      font-size: 11px;
      font-weight: 500;
      letter-spacing: 0.8px;
      text-transform: uppercase;
    }
    .badge-green { background: rgba(122,196,127,0.12); border: 1px solid rgba(122,196,127,0.25); color: var(--mint); }
    .badge-gold { background: rgba(212,160,65,0.1); border: 1px solid rgba(212,160,65,0.25); color: var(--amber); }

    .report-header h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2rem, 4vw, 3rem);
      color: var(--cream);
      line-height: 1.15;
      margin-bottom: 10px;
    }
    .report-header p {
      color: rgba(245,240,232,0.45);
      font-size: 0.92rem;
      font-weight: 300;
    }

    /* Crop Info cards */
    .info-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
      margin-bottom: 36px;
    }
    .info-card {
      background: rgba(255,255,255,0.03);
      border: 1px solid rgba(122,196,127,0.1);
      border-radius: 14px;
      padding: 18px 20px;
      transition: all 0.3s ease;
      animation: card-in 0.5s ease both;
    }
    .info-card:nth-child(1) { animation-delay: 0.1s; }
    .info-card:nth-child(2) { animation-delay: 0.2s; }
    .info-card:nth-child(3) { animation-delay: 0.3s; }
    .info-card:nth-child(4) { animation-delay: 0.4s; }
    .info-card:nth-child(5) { animation-delay: 0.5s; }
    .info-card:nth-child(6) { animation-delay: 0.6s; }
    @keyframes card-in {
      from { opacity: 0; transform: translateY(12px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .info-card:hover {
      background: rgba(45,106,53,0.12);
      border-color: rgba(122,196,127,0.2);
      transform: translateY(-2px);
    }
    .info-card-label {
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: rgba(245,240,232,0.35);
      margin-bottom: 5px;
    }
    .info-card-value {
      font-size: 0.95rem;
      font-weight: 500;
      color: var(--cream);
    }
    .info-card-icon {
      font-size: 1.3rem;
      margin-bottom: 8px;
    }

    /* AI Response */
    .response-section {
      background: rgba(255,255,255,0.025);
      border: 1px solid rgba(122,196,127,0.12);
      border-radius: 20px;
      overflow: hidden;
      margin-bottom: 28px;
      animation: card-in 0.6s 0.5s ease both;
    }
    .response-header {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 22px 28px;
      background: rgba(45,106,53,0.12);
      border-bottom: 1px solid rgba(122,196,127,0.08);
    }
    .ai-avatar {
      width: 42px;
      height: 42px;
      background: linear-gradient(135deg, var(--leaf), var(--sage));
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
    }
    .response-header-text strong {
      display: block;
      font-size: 0.95rem;
      color: var(--cream);
    }
    .response-header-text span {
      font-size: 0.78rem;
      color: rgba(245,240,232,0.4);
    }

    .response-body {
      padding: 28px;
    }

    /* Points list */
    .point-item {
      display: flex;
      gap: 16px;
      padding: 16px 0;
      border-bottom: 1px solid rgba(122,196,127,0.06);
      opacity: 0;
      transform: translateX(-8px);
      animation: point-in 0.4s ease forwards;
    }
    .point-item:last-child { border-bottom: none; padding-bottom: 0; }
    .point-item:nth-child(1) { animation-delay: 0.8s; }
    .point-item:nth-child(2) { animation-delay: 1.0s; }
    .point-item:nth-child(3) { animation-delay: 1.2s; }
    .point-item:nth-child(4) { animation-delay: 1.4s; }
    .point-item:nth-child(5) { animation-delay: 1.6s; }
    @keyframes point-in {
      to { opacity: 1; transform: translateX(0); }
    }

    .point-num {
      width: 28px;
      height: 28px;
      background: linear-gradient(135deg, var(--leaf), var(--sage));
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: 700;
      color: white;
      flex-shrink: 0;
      margin-top: 2px;
    }
    .point-text {
      font-size: 0.95rem;
      line-height: 1.65;
      color: rgba(245,240,232,0.82);
    }

    /* Full response fallback */
    .full-response {
      font-size: 0.95rem;
      line-height: 1.75;
      color: rgba(245,240,232,0.78);
      white-space: pre-line;
    }

    /* Actions */
    .actions {
      display: flex;
      gap: 14px;
      animation: card-in 0.5s 1s ease both;
    }
    .btn-primary {
      flex: 1;
      padding: 14px 24px;
      background: linear-gradient(135deg, var(--leaf), var(--sage));
      border: none;
      border-radius: 12px;
      color: white;
      font-family: 'DM Sans', sans-serif;
      font-size: 0.92rem;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      text-align: center;
      transition: all 0.3s ease;
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(45,106,53,0.4); }
    .btn-secondary {
      padding: 14px 24px;
      background: rgba(212,160,65,0.08);
      border: 1px solid rgba(212,160,65,0.2);
      border-radius: 12px;
      color: var(--amber);
      font-family: 'DM Sans', sans-serif;
      font-size: 0.92rem;
      font-weight: 500;
      cursor: pointer;
      text-decoration: none;
      text-align: center;
      transition: all 0.3s ease;
    }
    .btn-secondary:hover { background: rgba(212,160,65,0.14); transform: translateY(-2px); }

    @media (max-width: 680px) {
      .page { padding: 32px 20px; }
      .info-grid { grid-template-columns: 1fr 1fr; }
      .actions { flex-direction: column; }
    }
  </style>
</head>
<body>
<div class="bg-fixed"></div>
<div class="page">

  <div class="report-header">
    <div class="badge-row">
      <span class="badge badge-green">✓ Analysis Complete</span>
      <span class="badge badge-gold">✦ GPT-4 Powered</span>
    </div>
    <h1>Your Crop Report<br><em style="font-weight:400; color: var(--mint);">is ready</em></h1>
    <p>Based on your submitted crop details — <?= htmlspecialchars($row['cropName']) ?> in <?= htmlspecialchars($row['location']) ?></p>
  </div>

  <!-- Info Cards -->
  <div class="info-grid">
    <div class="info-card">
      <div class="info-card-icon">🌾</div>
      <div class="info-card-label">Crop</div>
      <div class="info-card-value"><?= htmlspecialchars($row['cropName']) ?></div>
    </div>
    <div class="info-card">
      <div class="info-card-icon">📍</div>
      <div class="info-card-label">Location</div>
      <div class="info-card-value"><?= htmlspecialchars($row['location']) ?></div>
    </div>
    <div class="info-card">
      <div class="info-card-icon">🌤️</div>
      <div class="info-card-label">Climate</div>
      <div class="info-card-value"><?= htmlspecialchars($row['climate']) ?></div>
    </div>
    <div class="info-card">
      <div class="info-card-icon">🪨</div>
      <div class="info-card-label">Soil Type</div>
      <div class="info-card-value"><?= htmlspecialchars($row['soilType']) ?></div>
    </div>
    <div class="info-card">
      <div class="info-card-icon">🎨</div>
      <div class="info-card-label">Soil Color</div>
      <div class="info-card-value"><?= htmlspecialchars($row['soilColor']) ?></div>
    </div>
    <div class="info-card">
      <div class="info-card-icon">🧪</div>
      <div class="info-card-label">Fertilizers</div>
      <div class="info-card-value"><?= htmlspecialchars($row['fertilizersUsed']) ?></div>
    </div>
  </div>

  <!-- AI Response -->
  <div class="response-section">
    <div class="response-header">
      <div class="ai-avatar">🤖</div>
      <div class="response-header-text">
        <strong>AI Expert Recommendation</strong>
      </div>
    </div>
    <div class="response-body">
      <?php if (count($points) > 1): ?>
        <?php $i = 1; foreach ($points as $point): if(strlen($point) < 5) continue; ?>
        <div class="point-item">
          <div class="point-num"><?= $i++ ?></div>
          <div class="point-text"><?= nl2br(htmlspecialchars($point)) ?></div>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="full-response"><?= nl2br(htmlspecialchars($response)) ?></div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Actions -->
  <div class="actions">
    <a href="index.php" class="btn-primary">🌱 Analyze Another Crop</a>
    <a href="javascript:window.print()" class="btn-secondary">🖨️ Save Report</a>
  </div>

</div>
</body>
</html>
