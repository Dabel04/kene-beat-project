<?php
session_start();
header('Content-Type: application/json');
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'items' => []]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch all cart items
$sql = "SELECT * FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$items = [];

while ($row = $result->fetch_assoc()) {
    $id = $row['track_id'];
    // Default to 'beat' if type is missing
    $type = (!empty($row['product_type'])) ? $row['product_type'] : 'beat';
    
    $item = [
        'id' => ($type === 'kit' ? 'kit_' : '') . $id,
        'type' => $type,
        'licenseKey' => $row['license_type'],
        'producer' => 'KentonTheProducer'
    ];

    if ($type === 'kit') {
        // Fetch from Sound Kits
        $k_sql = "SELECT title, price, cover_image FROM sound_kits WHERE id = $id";
        $k_res = $conn->query($k_sql);
        if ($k_res && $kit = $k_res->fetch_assoc()) {
            $item['name'] = $kit['title'];
            $item['price'] = floatval($kit['price']);
            $item['img'] = $kit['cover_image'];
            $item['licenseName'] = "Sound Kit";
            $items[] = $item;
        }
    } else {
        // Fetch from Tracks
        $t_sql = "SELECT title, price, cover_image FROM tracks WHERE id = $id";
        $t_res = $conn->query($t_sql);
        if ($t_res && $track = $t_res->fetch_assoc()) {
            $item['name'] = $track['title'];
            $item['img'] = $track['cover_image'];
            
            // Recalculate price based on license
            $base = floatval($track['price']);
            $mult = 1;
            if ($row['license_type'] == 'premium') $mult = 4;
            if ($row['license_type'] == 'exclusive') $mult = 20;
            
            $item['price'] = $base * $mult;
            $item['licenseName'] = ucfirst($row['license_type']);
            $items[] = $item;
        }
    }
}

echo json_encode(['success' => true, 'items' => $items]);
?>