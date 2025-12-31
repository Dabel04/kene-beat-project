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

// 2. Transaction Start
$conn->begin_transaction();

try {
    // 3. Wipe Old Cart
    $conn->query("DELETE FROM cart WHERE user_id = $user_id");

    // 4. Insert New Items
    if (!empty($cart)) {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, track_id, license_type) VALUES (?, ?, ?)");
        
        foreach ($cart as $item) {
            $track_id = intval($item['id']);
            
            // --- THE FIX: Check ALL possible names for the license ---
            $license = 'basic'; // Default
            
            if (!empty($item['licenseKey'])) {
                $license = $item['licenseKey'];
            } elseif (!empty($item['license_type'])) {
                $license = $item['license_type'];
            } elseif (!empty($item['license'])) {
                $license = $item['license'];
            } elseif (!empty($item['key'])) {
                $license = $item['key'];
            }
            
            // Force lowercase to match your database enum/strings
            $license = strtolower($license);

            $stmt->bind_param("iis", $user_id, $track_id, $license);
            $stmt->execute();
        }
        $stmt->close();
    }
    
    // 5. Commit
    $conn->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>