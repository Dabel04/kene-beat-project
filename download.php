<?php
// download.php
session_start();
include 'db_connect.php';

// 1. Validate Request
if (!isset($_GET['order_id'])) { 
    die("Error: No order specified."); 
}

if (!isset($_SESSION['user_id'])) {
    die("Error: You must be logged in to download files.");
}

$order_id = intval($_GET['order_id']);
$user_id  = $_SESSION['user_id'];

// 2. Fetch Order & Verify Ownership
// We check if this specific order belongs to the logged-in user
$sql = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) { 
    die("Access denied: Order not found or does not belong to you."); 
}

// 3. Determine File Path based on Product Type
$file_path = "";

// Check if 'product_type' column exists/is populated (Backward Compatibility)
$product_type = isset($order['product_type']) ? $order['product_type'] : 'beat';
$product_id   = isset($order['product_id']) ? $order['product_id'] : 0;

if ($product_type === 'kit') {
    // --- DOWNLOAD SOUND KIT ---
    $k_sql = "SELECT zip_file FROM sound_kits WHERE id = ?";
    $k_stmt = $conn->prepare($k_sql);
    $k_stmt->bind_param("i", $product_id);
    $k_stmt->execute();
    $kit = $k_stmt->get_result()->fetch_assoc();
    
    if ($kit) {
        $file_path = $kit['zip_file']; // e.g. "uploads/my_kit.zip"
    }

} else {
    // --- DOWNLOAD BEAT ---
    // If product_id is 0 (Legacy Order), we find the track by Title
    if ($product_id == 0) {
        $t_sql = "SELECT audio_file FROM tracks WHERE title = ? LIMIT 1";
        $t_stmt = $conn->prepare($t_sql);
        $t_stmt->bind_param("s", $order['track_title']);
        $t_stmt->execute();
        $track = $t_stmt->get_result()->fetch_assoc();
    } else {
        // Modern Order: Find by ID
        $t_sql = "SELECT audio_file FROM tracks WHERE id = ?";
        $t_stmt = $conn->prepare($t_sql);
        $t_stmt->bind_param("i", $product_id);
        $t_stmt->execute();
        $track = $t_stmt->get_result()->fetch_assoc();
    }

    if ($track) {
        $file_path = $track['audio_file']; // The secure, untagged file
    }
}

// 4. Clean Path Logic
// If path is stored as "uploads/file.mp3", we are good.
// If it is stored as "../uploads/file.mp3", we need to remove "../" because download.php is in root.
if (strpos($file_path, '../') === 0) {
    $file_path = substr($file_path, 3);
}

// 5. Serve the File
if (!empty($file_path) && file_exists($file_path)) {
    // Force Download Headers
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_path));
    
    // Clear output buffer to prevent corrupt downloads
    ob_clean();
    flush();
    
    readfile($file_path);
    exit;
} else {
    die("Error: File not found on server. Please contact support. (Path: $file_path)");
}
?>