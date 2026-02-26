<?php
require 'config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$result = $conn->query("SELECT id, cropName, location, soilType, climate, visiblesymptom FROM crops ORDER BY id DESC");
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Fasal X — History</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
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
      max-width: 1000px;
      margin: 0 auto;
      padding: 60px 40px;
      animation: page-in 0.7s ease both;
    }
    @keyframes page-in {
      from { opacity: 0; transform: translateY(24px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Header */
    .page-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 40px;
      flex-wrap: wrap;
      gap: 16px;
    }
    .page-header h1 {
      font-family: 'Playfair Display', serif;
      font-size: 2.2rem;
      color: var(--cream);
    }
    .page-header h1 span { color: var(--mint); }
    .page-header p {
      font-size: 0.85rem;
      color: rgba(245,240,232,0.4);
      margin-top: 4px;
    }
    .btn-new {
      padding: 12px 22px;
      background: linear-gradient(135deg, var(--gold), var(--amber));
      border: none;
      border-radius: 10px;
      color: var(--forest);
      font-family: 'DM Sans', sans-serif;
      font-size: 0.9rem;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.3s ease;
      white-space: nowrap;
    }
    .btn-new:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(212,160,65,0.35); }

    /* Empty state */
    .empty-state {
      text-align: center;
      padding: 80px 20px;
      color: rgba(245,240,232,0.3);
    }
    .empty-state .icon { font-size: 3rem; margin-bottom: 16px; }
    .empty-state p { font-size: 1rem; }

    /* Table */
    .table-wrap {
      background: rgba(255,255,255,0.025);
      border: 1px solid rgba(122,196,127,0.1);
      border-radius: 20px;
      overflow: hidden;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    thead {
      background: rgba(45,106,53,0.2);
    }
    thead th {
      padding: 16px 20px;
      text-align: left;
      font-size: 0.72rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: rgba(245,240,232,0.45);
      border-bottom: 1px solid rgba(122,196,127,0.1);
    }
    tbody tr {
      border-bottom: 1px solid rgba(122,196,127,0.06);
      transition: background 0.2s ease;
      animation: row-in 0.4s ease both;
    }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: rgba(45,106,53,0.1); }
    @keyframes row-in {
      from { opacity: 0; transform: translateX(-10px); }
      to { opacity: 1; transform: translateX(0); }
    }
    td {
      padding: 16px 20px;
      font-size: 0.9rem;
      color: rgba(245,240,232,0.75);
      vertical-align: middle;
    }
    .crop-cell {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .crop-icon {
      width: 34px;
      height: 34px;
      background: rgba(45,106,53,0.25);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      flex-shrink: 0;
    }
    .crop-name {
      font-weight: 500;
      color: var(--cream);
    }
    .badge-location {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      background: rgba(122,196,127,0.08);
      border: 1px solid rgba(122,196,127,0.15);
      padding: 3px 10px;
      border-radius: 100px;
      font-size: 0.78rem;
      color: var(--mint);
    }
    .symptom-text {
      max-width: 200px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      font-size: 0.82rem;
      color: rgba(245,240,232,0.45);
    }
    .id-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 28px;
      height: 28px;
      background: rgba(212,160,65,0.1);
      border: 1px solid rgba(212,160,65,0.2);
      border-radius: 6px;
      font-size: 0.78rem;
      font-weight: 600;
      color: var(--amber);
    }
    .btn-view {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      background: linear-gradient(135deg, var(--leaf), var(--sage));
      border-radius: 8px;
      color: white;
      text-decoration: none;
      font-size: 0.82rem;
      font-weight: 600;
      transition: all 0.2s ease;
      white-space: nowrap;
    }
    .btn-view:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(45,106,53,0.4); }

    /* Footer */
    .footer {
      text-align: center;
      margin-top: 32px;
      font-size: 0.82rem;
      color: rgba(245,240,232,0.25);
    }

    @media (max-width: 700px) {
      .page { padding: 32px 16px; }
      thead th:nth-child(4), td:nth-child(4) { display: none; }
      thead th:nth-child(3), td:nth-child(3) { display: none; }
    }
  </style>
</head>
<body>
<div class="bg-fixed"></div>
<div class="page">

  <div class="page-header">
    <div>
      <h1>Crop <span>History</span></h1>
      <p>All your past crop submissions</p>
    </div>
    <a href="index.php" class="btn-new">+ Analyze New Crop</a>
  </div>

  <?php if ($result->num_rows === 0): ?>
    <div class="empty-state">
      <div class="icon">🌱</div>
      <p>No submissions yet. Analyze your first crop!</p>
    </div>
  <?php else: ?>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Crop</th>
          <th>Location</th>
          <th>Soil & Climate</th>
          <th>Symptoms</th>
          <th>Report</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><span class="id-badge"><?= $row['id'] ?></span></td>
          <td>
            <div class="crop-cell">
              <div class="crop-icon">🌾</div>
              <span class="crop-name"><?= htmlspecialchars($row['cropName']) ?></span>
            </div>
          </td>
          <td><span class="badge-location">📍 <?= htmlspecialchars($row['location']) ?></span></td>
          <td><?= htmlspecialchars($row['soilType']) ?> · <?= htmlspecialchars($row['climate']) ?></td>
          <td><div class="symptom-text"><?= htmlspecialchars($row['visiblesymptom']) ?></div></td>
          <td><a href="result.php?id=<?= $row['id'] ?>" class="btn-view">👁️ View</a></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>

  <div class="footer">Fasal X — AI Crop Intelligence</div>
</div>
</body>
</html>
