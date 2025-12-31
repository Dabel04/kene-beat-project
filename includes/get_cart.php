<?php
session_start();
header('Content-Type: application/json');
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'items' => []]);
    exit;
}

$user_id = $_SESSION['user_id'];

// 1. Fetch Cart with AUDIO FILES
$stmt = $conn->prepare("
    SELECT c.license_type, 
           t.id, 
           t.title as name, 
           t.price, 
           'KentonTheProducer' as producer,
           t.cover_image as img,
           t.audio_file, 
           t.tagged_file
    FROM cart c 
    JOIN tracks t ON c.track_id = t.id 
    WHERE c.user_id = ?
");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Query failed']);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $basePrice = floatval($row['price']);
    $licenseKey = strtolower($row['license_type']);
    
    // 2. Calculate Real Price
    $multiplier = 1;
    $licenseName = "Basic Lease";
    
    if ($licenseKey === 'premium') {
        $multiplier = 4;
        $licenseName = "Premium Lease";
    } elseif ($licenseKey === 'exclusive') {
        $multiplier = 20;
        $licenseName = "Exclusive Rights";
    }
    
    $finalPrice = $basePrice * $multiplier;

    // 3. Format Output
    $items[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'producer' => $row['producer'],
        'price' => $finalPrice,
        'licenseKey' => $licenseKey,
        'licenseName' => $licenseName,
        'img' => $row['img'],
        // Prefer tagged file for preview, fallback to raw audio
        'audio' => !empty($row['tagged_file']) ? $row['tagged_file'] : $row['audio_file']
    ];
}

echo json_encode(['success' => true, 'items' => $items]);
?>