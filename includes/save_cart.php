<?php
session_start();
header('Content-Type: application/json');
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) exit;

$input = json_decode(file_get_contents('php://input'), true);
$cart = $input['cart'] ?? [];
$user_id = $_SESSION['user_id'];

// 1. Clear old cart
$conn->query("DELETE FROM cart WHERE user_id = $user_id");

// 2. Insert new items
$stmt = $conn->prepare("INSERT INTO cart (user_id, track_id, license_type) VALUES (?, ?, ?)");

foreach ($cart as $item) {
    // USE THE KEY, NOT THE NAME
    // Frontend sends: licenseKey = 'basic', 'premium', or 'exclusive'
    $license = strtolower($item['licenseKey']);
    
    // Safety fallback
    if (!in_array($license, ['basic', 'premium', 'exclusive'])) {
        $license = 'basic';
    }

    $stmt->bind_param("iis", $user_id, $item['id'], $license);
    $stmt->execute();
}

echo json_encode(['success' => true]);
?>