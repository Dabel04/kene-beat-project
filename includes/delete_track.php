<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0); 

$response = array('success' => false, 'message' => '');

try {
    // 1. Connect to Database (Go up one level)
    $rootPath = dirname(__DIR__); 
    $dbFile = $rootPath . '/db_connect.php';

    if (!file_exists($dbFile)) {
        throw new Exception("db_connect.php not found.");
    }
    require_once $dbFile;

    // 2. Get the ID
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method.");
    }

    $data = json_decode(file_get_contents("php://input"), true);
    $trackId = isset($data['id']) ? intval($data['id']) : 0;

    if ($trackId <= 0) {
        throw new Exception("Invalid Track ID.");
    }

    // 3. GET FILE PATHS FIRST (So we can delete the actual files)
    $sql = "SELECT cover_image, audio_file FROM tracks WHERE id = $trackId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Construct absolute paths to delete files safely
        $coverPath = $rootPath . '/' . $row['cover_image'];
        $audioPath = $rootPath . '/' . $row['audio_file'];

        // Delete Cover Image if exists
        if (file_exists($coverPath)) {
            unlink($coverPath);
        }

        // Delete Audio File if exists
        if (file_exists($audioPath)) {
            unlink($audioPath);
        }
    }

    // 4. DELETE DATABASE RECORD
    $deleteSql = "DELETE FROM tracks WHERE id = $trackId";
    if ($conn->query($deleteSql) === TRUE) {
        $response['success'] = true;
        $response['message'] = "Track and files deleted successfully.";
    } else {
        throw new Exception("Database Error: " . $conn->error);
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
if (isset($conn)) $conn->close();
?>