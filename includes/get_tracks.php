<?php
// includes/get_tracks.php
header('Content-Type: application/json');
include '../db_connect.php';

$response = [];

// Pagination logic
$limit = 10; // Tracks per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// 1. Get Total Count
$countSql = "SELECT COUNT(*) as total FROM tracks";
$countResult = $conn->query($countSql);
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// 2. Get Tracks for Current Page
$sql = "SELECT * FROM tracks ORDER BY id DESC LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

$tracks = [];
while($row = $result->fetch_assoc()) {
    $tracks[] = $row;
}

// 3. Return JSON with Metadata
echo json_encode([
    'success' => true,
    'tracks' => $tracks,
    'pagination' => [
        'current_page' => $page,
        'total_pages' => $totalPages,
        'total_tracks' => $totalRows
    ]
]);

$conn->close();
?>