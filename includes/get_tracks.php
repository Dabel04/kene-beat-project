<?php
// Turn off error reporting to prevent HTML warnings from breaking JSON
error_reporting(0);
header('Content-Type: application/json');

// Use __DIR__ to safely find the root folder regardless of where this is called
$rootPath = dirname(__DIR__); 

if (file_exists($rootPath . '/db_connect.php')) {
    include $rootPath . '/db_connect.php';
} else {
    echo json_encode(["error" => "Critical: db_connect.php not found at $rootPath/db_connect.php"]);
    exit;
}

// Check Database Connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Database Connection Failed: " . $conn->connect_error]);
    exit;
}

// Fetch Tracks
$sql = "SELECT * FROM tracks ORDER BY id DESC";
$result = $conn->query($sql);

$tracks = array();

if ($result) {
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $tracks[] = $row;
        }
    }
    echo json_encode($tracks);
} else {
    echo json_encode(["error" => "SQL Error: " . $conn->error]);
}

$conn->close();
?>