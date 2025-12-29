<?php
session_start();
header('Content-Type: application/json');
include '../db_connect.php';

// 1. Check Login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = intval($_SESSION['user_id']);
$input = json_decode(file_get_contents('php://input'), true);
$cart = isset($input['cart']) ? $input['cart'] : [];

// 2. WIPE THE OLD CART (Crucial!)
// We delete everything for this user first, so we don't get duplicates.
$conn->query("DELETE FROM cart WHERE user_id = $user_id");

// 3. INSERT NEW ITEMS
if (!empty($cart)) {
    $stmt = $conn->prepare("INSERT INTO cart (user_id, track_id, license_type) VALUES (?, ?, ?)");
    
    foreach ($cart as $item) {
        $track_id = intval($item['id']);
        // Trust the key from frontend (basic, premium, exclusive)
        $license_type = isset($item['licenseKey']) ? $item['licenseKey'] : 'basic';
        
        $stmt->bind_param("iis", $user_id, $track_id, $license_type);
        $stmt->execute();
    }
    $stmt->close();
}

echo json_encode(['success' => true]);
?>