<?php
session_start();
header('Content-Type: application/json');
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'items' => []]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Multipliers: Basic=1, Premium=4, Exclusive=20
$stmt = $conn->prepare("
    SELECT c.license_type, 
           t.id, 
           t.title as name, 
           t.price * CASE c.license_type 
               WHEN 'basic' THEN 1 
               WHEN 'premium' THEN 4 
               WHEN 'exclusive' THEN 20 
               ELSE 1
           END as price,
           'KentonTheProducer' as producer,
           t.cover_image as img
    FROM cart c 
    JOIN tracks t ON c.track_id = t.id 
    WHERE c.user_id = ?
");

if (!$stmt) {
    // Return empty if query fails (prevents frontend crash)
    echo json_encode(['success' => false, 'items' => []]); 
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $type = ucfirst($row['license_type']);
    // Format Name: "Premium Lease" or "Exclusive Rights"
    $suffix = (strtolower($row['license_type']) == 'exclusive') ? ' Rights' : ' Lease';
    $row['licenseName'] = $type . $suffix;
    $items[] = $row;
}

echo json_encode(['success' => true, 'items' => $items]);
?>