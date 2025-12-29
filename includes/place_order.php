<?php
session_start();
header('Content-Type: application/json');

include '../db_connect.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
    exit;
}

$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
$cart = $input['cart'];
$user = $input['user'];

$customer_name = trim($user['name']);
$customer_email = trim($user['email']);
$transaction_id = "TXN" . time() . rand(100, 999);

// Init Variables
$track_id = 0;
$track_title = "";
$price = 0.00;
$license_type = "basic";

// Prepare Statement
$stmt = $conn->prepare("INSERT INTO orders (transaction_id, customer_name, customer_email, track_id, track_title, license_type, price, user_id, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("sssisdis", $transaction_id, $customer_name, $customer_email, $track_id, $track_title, $license_type, $price, $user_id);

$success = true;
$errorMsg = "";

foreach ($cart as $item) {
    $track_id = isset($item['id']) ? intval($item['id']) : 0;
    $track_title = trim($item['name']); // or $item['title'] depending on JSON source
    $price = floatval($item['price']);
    // Handle License key/type mapping
    $key = isset($item['licenseKey']) ? $item['licenseKey'] : (isset($item['license_type']) ? $item['license_type'] : 'basic');
    $license_type = $key;

    if (!$stmt->execute()) {
        $success = false;
        $errorMsg = $stmt->error;
        break; 
    }
}

$stmt->close();

if ($success) {
    // --- CRITICAL FIX: CLEAR THE CART ---
    if ($user_id > 0) {
        $conn->query("DELETE FROM cart WHERE user_id = $user_id");
    }
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Insert failed: ' . $errorMsg]);
}
?>