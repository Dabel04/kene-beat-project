<?php
session_start();
header('Content-Type: application/json');
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT c.license_type, t.id, t.title, t.price * 
           CASE c.license_type 
               WHEN 'basic' THEN 1 
               WHEN 'premium' THEN 2 
               WHEN 'unlimited' THEN 4 
           END as price,
           'KentonTheProducer' as producer
    FROM cart c 
    JOIN tracks t ON c.track_id = t.id 
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $row['licenseName'] = ucfirst($row['license_type']) . ' Lease';
    $items[] = $row;
}

echo json_encode(['success' => true, 'items' => $items]);