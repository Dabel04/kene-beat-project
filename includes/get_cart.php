<?php
session_start();
header('Content-Type: application/json');
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'items' => []]);
    exit;
}

$user_id = $_SESSION['user_id'];

// 1. Fetch Cart Items (Checking if product_type column exists to be safe)
$checkCol = $conn->query("SHOW COLUMNS FROM cart LIKE 'product_type'");
$hasType = ($checkCol->num_rows > 0);

if ($hasType) {
    $sql = "SELECT track_id, license_type, product_type FROM cart WHERE user_id = ?";
} else {
    // Fallback if you forgot to run the SQL ALTER command
    $sql = "SELECT track_id, license_type, 'beat' as product_type FROM cart WHERE user_id = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$items = [];

// 2. Loop & Fetch Details Dynamically
while ($row = $result->fetch_assoc()) {
    $id = $row['track_id'];
    $type = isset($row['product_type']) ? $row['product_type'] : 'beat';
    $licenseKey = strtolower($row['license_type']);
    
    // Prepare Item Skeleton
    $item = [
        'id' => ($type === 'kit' ? 'kit_' : '') . $id,
        'type' => $type,
        'licenseKey' => $licenseKey,
        'producer' => 'KentonTheProducer'
    ];

    if ($type === 'kit') {
        // --- FETCH KIT ---
        $k_sql = "SELECT title, price, cover_image, demo_audio FROM sound_kits WHERE id = $id";
        $k_res = $conn->query($k_sql);
        if ($kit = $k_res->fetch_assoc()) {
            $item['name'] = $kit['title'];
            $item['price'] = floatval($kit['price']);
            $item['img'] = $kit['cover_image'];
            $item['audio'] = $kit['demo_audio'];
            $item['licenseName'] = "Sound Kit (Royalty Free)";
        }
    } else {
        // --- FETCH BEAT ---
        $t_sql = "SELECT title, price, cover_image, audio_file, tagged_file FROM tracks WHERE id = $id";
        $t_res = $conn->query($t_sql);
        if ($track = $t_res->fetch_assoc()) {
            $item['name'] = $track['title'];
            $item['img'] = $track['cover_image'];
            // Prefer tagged audio for preview
            $item['audio'] = !empty($track['tagged_file']) ? $track['tagged_file'] : $track['audio_file'];
            
            // Calculate Price based on License
            $basePrice = floatval($track['price']);
            $multiplier = 1;
            $item['licenseName'] = "Basic Lease";

            if ($licenseKey === 'premium') {
                $multiplier = 4;
                $item['licenseName'] = "Premium Lease";
            } elseif ($licenseKey === 'exclusive') {
                $multiplier = 20;
                $item['licenseName'] = "Exclusive Rights";
            }
            $item['price'] = $basePrice * $multiplier;
        }
    }

    // Only add if product actually exists
    if (isset($item['name'])) {
        $items[] = $item;
    }
}

echo json_encode(['success' => true, 'items' => $items]);
?>