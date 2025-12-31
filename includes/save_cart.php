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
        // Updated Query: Includes product_type
        $stmt = $conn->prepare("INSERT INTO cart (user_id, track_id, license_type, product_type) VALUES (?, ?, ?, ?)");
        
        foreach ($cart as $item) {
            $id_val = isset($item['id']) ? $item['id'] : 0;
            
            // Clean ID (remove "kit_" prefix if present in string)
            $raw_id = str_replace('kit_', '', $id_val);
            $track_id = intval($raw_id);
            
            // Determine Type
            $type = (isset($item['type']) && $item['type'] === 'kit') ? 'kit' : 'beat';

            // Determine License
            $license = 'basic';
            if ($type === 'kit') {
                $license = 'royalty-free';
            } else {
                if (!empty($item['licenseKey'])) $license = $item['licenseKey'];
                elseif (!empty($item['license_type'])) $license = $item['license_type'];
            }
            $license = strtolower($license);

            $stmt->bind_param("iiss", $user_id, $track_id, $license, $type);
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