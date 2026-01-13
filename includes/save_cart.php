<?php
session_start();
header('Content-Type: application/json');
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = intval($_SESSION['user_id']);
$input = json_decode(file_get_contents('php://input'), true);
$cart = isset($input['cart']) ? $input['cart'] : [];

$conn->begin_transaction();

try {
    // 1. Clear previous cart
    $conn->query("DELETE FROM cart WHERE user_id = $user_id");

    // 2. Insert new items
    if (!empty($cart)) {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, track_id, license_type, product_type) VALUES (?, ?, ?, ?)");
        
        foreach ($cart as $item) {
            // Clean ID: "kit_5" becomes 5
            $raw_id = str_replace('kit_', '', $item['id']);
            $track_id = intval($raw_id);
            
            // Logic: Is it a Kit?
            $type = (isset($item['type']) && $item['type'] === 'kit') ? 'kit' : 'beat';
            
            // License logic
            $license = 'basic';
            if ($type === 'kit') {
                $license = 'royalty-free';
            } else {
                $license = isset($item['licenseKey']) ? $item['licenseKey'] : 'basic';
            }

            $stmt->bind_param("iiss", $user_id, $track_id, $license, $type);
            $stmt->execute();
        }
        $stmt->close();
    }
    
    $conn->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>