<?php
session_start();
header('Content-Type: application/json');
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) exit;

$input = json_decode(file_get_contents('php://input'), true);
$cart = $input['cart'] ?? [];

$user_id = $_SESSION['user_id'];

// Clear existing cart
$conn->query("DELETE FROM cart WHERE user_id = $user_id");

// Re-insert
$stmt = $conn->prepare("INSERT INTO cart (user_id, track_id, license_type) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE license_type = VALUES(license_type)");
foreach ($cart as $item) {
    $license = strtolower(str_replace([' Lease', ' '], '', $item['licenseName']));
    $license = $license === 'unlimited' ? 'unlimited' : ($license === 'premium' ? 'premium' : 'basic');
    $stmt->bind_param("iis", $user_id, $item['id'], $license);
    $stmt->execute();
}
echo json_encode(['success' => true]);