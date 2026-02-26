<?php
// DB Connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "agridoc_db";

$conn = new mysqli($host, $user, $pass, $db); // ✅ Correct variable here
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT * FROM crops ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>📊 AgriDoc Records</title>
  <link rel="stylesheet" href="view-style.css">
  
</head>
<body>
  <div class="container">
    <h1>🌱 AgriDoc Submissions</h1>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Crop</th>
          <th>Soil Type</th>
          <th>Soil Color</th>
          <th>Location</th>
          <th>Climate</th>
          <th>Fertilizers</th>
          <th>visiblesymptom</th>
          <th>Image</th>
          <th>API Response</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= $row['cropName'] ?></td>
          <td><?= $row['soilType'] ?></td>
          <td><?= $row['soilColor'] ?></td>
          <td><?= $row['location'] ?></td>
          <td><?= $row['climate'] ?></td>
          
          <td><?= $row['fertilizersUsed'] ?></td>
          <td><?= $row['visiblesymptom']?></td>
          <td>
            <img src="data:image/jpeg;base64,<?= base64_encode($row['image']) ?>" alt="crop" />
          </td>
          <td><?=$row['api_response']?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
<?php $conn->close(); ?>