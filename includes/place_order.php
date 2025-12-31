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

// Init Variables for Binding
$track_id = 0; // Legacy column (kept for compatibility)
$track_title = "";
$price = 0.00;
$license_type = "basic";
$product_type = "beat"; // New
$product_id = 0;        // New

// Prepare Statement (Updated with product_type and product_id)
// Note: We check if the columns exist or if you just ran the SQL update. 
// Assuming you ran: ALTER TABLE orders ADD COLUMN product_type VARCHAR(20), ADD COLUMN product_id INT;
$stmt = $conn->prepare("INSERT INTO orders (transaction_id, customer_name, customer_email, track_id, track_title, license_type, price, user_id, date, product_type, product_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)");

if (!$stmt) {
    // Fallback if DB columns haven't been added yet (Safety net)
    $stmt = $conn->prepare("INSERT INTO orders (transaction_id, customer_name, customer_email, track_id, track_title, license_type, price, user_id, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssissdi", $transaction_id, $customer_name, $customer_email, $track_id, $track_title, $license_type, $price, $user_id);
} else {
    // Bind Parameters (s s s i s s d i s i)
    $stmt->bind_param("sssissdisi", $transaction_id, $customer_name, $customer_email, $track_id, $track_title, $license_type, $price, $user_id, $product_type, $product_id);
}

$success = true;
$errorMsg = "";
$total_amount = 0;
$email_order_items = ""; // String to build email list

// --- LOOP & INSERT ---
foreach ($cart as $item) {
    // 1. Determine Type & Clean ID
    // If 'type' is 'kit', it's a Sound Kit. Otherwise, it's a Beat.
    $product_type = (isset($item['type']) && $item['type'] === 'kit') ? 'kit' : 'beat';
    
    // Clean ID: "kit_5" -> 5, "5" -> 5
    $raw_id = str_replace('kit_', '', $item['id']);
    $product_id = intval($raw_id);
    
    // Legacy support: fill track_id with product_id for older scripts
    $track_id = $product_id; 

    $track_title = trim($item['name']); 
    $price = floatval($item['price']);
    
    // License Handling: Kits usually don't have a specific "license key", so we call it "Royalty Free" or "Standard"
    if ($product_type === 'kit') {
        $license_type = "Sound Kit License";
    } else {
        $license_type = isset($item['licenseKey']) ? $item['licenseKey'] : (isset($item['license_type']) ? $item['license_type'] : 'basic');
    }

    if (!$stmt->execute()) {
        $success = false;
        $errorMsg = $stmt->error;
        break; 
    }
    
    // Add to Total
    $total_amount += $price;
    
    // Build Email Row
    $display_license = ($product_type === 'kit') ? "Sound Kit (Royalty Free)" : ucfirst($license_type);
    
    $email_order_items .= "
    <tr style='border-bottom: 1px solid #333;'>
        <td style='padding: 10px; color: #fff;'>{$track_title}</td>
        <td style='padding: 10px; color: #ccc; text-transform: uppercase; font-size: 12px;'>" . $display_license . "</td>
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
            <p>Thank you for shopping with KentonTheProducer. Your files are ready for download.</p>
            
            <h3 style='border-bottom: 1px solid #333; padding-bottom: 10px; margin-top: 30px;'>Order Summary</h3>
            <table style='width: 100%; border-collapse: collapse;'>
                $email_order_items
                <tr>
                    <td colspan='2' style='padding: 15px 10px; text-align: right; font-weight: bold;'>TOTAL</td>
                    <td style='padding: 15px 10px; text-align: right; font-weight: bold; color: #2bee79; font-size: 18px;'>$" . number_format($total_amount, 2) . "</td>
                </tr>
            </table>

            <div style='text-align: center; margin-top: 40px;'>
                <a href='http://localhost/keneproject/user-dashboard.php' style='background-color: #2bee79; color: #000; padding: 15px 30px; text-decoration: none; border-radius: 30px; font-weight: bold; display: inline-block;'>Download Files</a>
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
    $headers .= "From: KentonTheProducer <no-reply@kenton.com>" . "\r\n"; 

    // Attempt to send
    @mail($to, $subject, $message, $headers);

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Insert failed: ' . $errorMsg]);
}
?>