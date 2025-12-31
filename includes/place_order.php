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

// Bind Parameters (sssissdi)
$stmt->bind_param("sssissdi", $transaction_id, $customer_name, $customer_email, $track_id, $track_title, $license_type, $price, $user_id);

$success = true;
$errorMsg = "";
$total_amount = 0;
$email_order_items = ""; // String to build email list

// --- LOOP & INSERT ---
foreach ($cart as $item) {
    $track_id = isset($item['id']) ? intval($item['id']) : 0;
    $track_title = trim($item['name']); 
    $price = floatval($item['price']);
    $license_type = isset($item['licenseKey']) ? $item['licenseKey'] : (isset($item['license_type']) ? $item['license_type'] : 'basic');

    if (!$stmt->execute()) {
        $success = false;
        $errorMsg = $stmt->error;
        break; 
    }
    
    // Add to Total
    $total_amount += $price;
    
    // Build Email Row
    $email_order_items .= "
    <tr style='border-bottom: 1px solid #333;'>
        <td style='padding: 10px; color: #fff;'>{$track_title}</td>
        <td style='padding: 10px; color: #ccc; text-transform: uppercase; font-size: 12px;'>" . ucfirst($license_type) . "</td>
        <td style='padding: 10px; color: #2bee79; text-align: right;'>$" . number_format($price, 2) . "</td>
    </tr>";
}

$stmt->close();

if ($success) {
    // 1. Clear Cart
    if ($user_id > 0) {
        $conn->query("DELETE FROM cart WHERE user_id = $user_id");
    }

    // 2. SEND RECEIPT EMAIL (Professional HTML)
    $to = $customer_email;
    $subject = "Receipt for Order #$transaction_id - KentonTheProducer";
    
    $message = "
    <html>
    <head>
        <title>Order Receipt</title>
    </head>
    <body style='background-color: #000; color: #fff; font-family: Arial, sans-serif; padding: 20px;'>
        <div style='max-width: 600px; margin: 0 auto; background-color: #111; padding: 20px; border-radius: 8px; border: 1px solid #333;'>
            <h2 style='color: #2bee79; margin-top: 0;'>Order Confirmed</h2>
            <p>Hi $customer_name,</p>
            <p>Thank you for shopping with KentonTheProducer. Your tracks are ready for download.</p>
            
            <h3 style='border-bottom: 1px solid #333; padding-bottom: 10px; margin-top: 30px;'>Order Summary</h3>
            <table style='width: 100%; border-collapse: collapse;'>
                $email_order_items
                <tr>
                    <td colspan='2' style='padding: 15px 10px; text-align: right; font-weight: bold;'>TOTAL</td>
                    <td style='padding: 15px 10px; text-align: right; font-weight: bold; color: #2bee79; font-size: 18px;'>$" . number_format($total_amount, 2) . "</td>
                </tr>
            </table>

            <div style='text-align: center; margin-top: 40px;'>
                <a href='http://localhost/keneproject/user-dashboard.php' style='background-color: #2bee79; color: #000; padding: 15px 30px; text-decoration: none; border-radius: 30px; font-weight: bold; display: inline-block;'>Download Beats</a>
                <p style='color: #666; font-size: 12px; margin-top: 20px;'>Or log in to your dashboard to access your library anytime.</p>
            </div>
            
            <p style='color: #444; font-size: 12px; margin-top: 30px; text-align: center;'>Transaction ID: $transaction_id</p>
        </div>
    </body>
    </html>
    ";

    // Email Headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: KentonTheProducer <no-reply@kenton.com>" . "\r\n"; // Change this on real server

    // Attempt to send (Suppress errors so it doesn't break JSON)
    @mail($to, $subject, $message, $headers);

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Insert failed: ' . $errorMsg]);
}
?>