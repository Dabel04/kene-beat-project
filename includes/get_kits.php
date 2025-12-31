<?php
// 1. Turn off error printing (logs errors instead of breaking JSON)
ini_set('display_errors', 0);
error_reporting(E_ALL);

// 2. Connect
include '../db_connect.php';
header('Content-Type: application/json');

// 3. Check Connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'DB Connection Failed']);
    exit;
}

// 4. Fetch Kits
$sql = "SELECT * FROM sound_kits ORDER BY created_at DESC";
$result = $conn->query($sql);

$kits = [];
if ($result) {
    while($row = $result->fetch_assoc()) {
        $kits[] = $row;
    }
}

// 5. Return JSON
echo json_encode(['success' => true, 'kits' => $kits]);
exit;
?>