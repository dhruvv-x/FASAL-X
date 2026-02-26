<?php
require 'config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cropName        = $_POST['cropName'] ?? '';
    $soilType        = $_POST['soilType'] ?? '';
    $soilColor       = $_POST['soilColor'] ?? '';
    $location        = $_POST['location'] ?? '';
    $climate         = $_POST['climate'] ?? '';
    $fertilizersUsed = $_POST['fertilizersUsed'] ?? '';
    $visiblesymptom  = $_POST['visiblesymptom'] ?? '';
    $imageData       = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0)
        $imageData = file_get_contents($_FILES['image']['tmp_name']);

    $stmt = $conn->prepare("INSERT INTO crops (cropName, soilType, soilColor, location, climate, fertilizersUsed, visiblesymptom, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $cropName, $soilType, $soilColor, $location, $climate, $fertilizersUsed, $visiblesymptom, $imageData);
    $stmt->execute();
    $last_insert_id = $stmt->insert_id;
    $stmt->close();

    $userMessage = "You are an expert agricultural scientist with 20+ years of Indian farming experience.

Farmer's crop details:
- Crop: $cropName | Location: $location
- Soil: $soilType, $soilColor | Weather: $climate
- Fertilizers: $fertilizersUsed
- Visible Symptoms: $visiblesymptom

Give exactly 3 points (max 3 sentences each):
1. DIAGNOSIS: Identify the exact disease/deficiency/pest with its scientific name if possible
2. IMMEDIATE ACTION: Name the EXACT fertilizer/pesticide/chemical to use with dosage and method
3. LONG TERM: Specific schedule and steps to prevent this in future seasons

Be very specific — name exact products, chemicals, quantities. No vague advice.";

    $ch = curl_init("https://api.openai.com/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . OPENAI_KEY
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        "model"      => "gpt-4-turbo",
        "messages"   => [["role" => "user", "content" => $userMessage]],
        "max_tokens" => 500
    ]));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $decoded  = json_decode(curl_exec($ch));
    $response = $decoded->choices[0]->message->content ?? "Could not generate response.";
    curl_close($ch);

    $stmt = $conn->prepare("UPDATE crops SET api_response = ? WHERE id = ?");
    $stmt->bind_param("si", $response, $last_insert_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: result.php?id=$last_insert_id");
    exit();
}
?>