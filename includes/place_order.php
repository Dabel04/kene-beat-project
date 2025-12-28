<?php
session_start();
header('Content-Type: application/json');

// 1. Get the Raw JSON Data from Checkout
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
    exit;
}

// 2. Connect to Database
$conn = new mysqli("localhost", "dabel", "go uni1234", "kene_admin");

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB Connection failed']);
    exit;
}

// 3. GET USER ID FROM SESSION (Crucial Step)
// If they are logged in, we get their ID. If not, it defaults to 0.
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

$cart = $input['cart'];
$user = $input['user'];

$customer_name = trim($user['name']);
$customer_email = trim($user['email']);
$transaction_id = "TXN" . time() . rand(100, 999);

// Prepare the INSERT statement ONCE outside the loop (better performance + secure)
$stmt = $conn->prepare("INSERT INTO orders (transaction_id, customer_name, customer_email, track_title, price, user_id) VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

// Bind parameters
$stmt->bind_param("ssssdi", $transaction_id, $customer_name, $customer_email, $track_title, $price, $user_id);

// Loop through cart and execute safely
foreach ($cart as $item) {
    $track_title = trim($item['name']); // track title
    $price = floatval($item['price']);

    if (!$stmt->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'Insert failed: ' . $stmt->error]);
        $stmt->close();
        exit;
    }
}

$stmt->close();
echo json_encode(['status' => 'success']);
?>