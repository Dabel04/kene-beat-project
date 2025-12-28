<?php
// Go up one level to find db_connect.php
include '../db_connect.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
   // Sanitize and validate input
$title = trim($_POST['title']);
$bpm = trim($_POST['bpm']);
$track_key = trim($_POST['track_key']);
$price = floatval($_POST['price']); // Ensure it's a number
$tags = trim($_POST['tags']);

if (empty($title) || empty($bpm) || empty($track_key) || $price <= 0) {
    $response['message'] = "Please fill all required fields correctly.";
    echo json_encode($response);
    exit;
}

// Define upload directory
$targetDir = "../uploads/";
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Basic file validation
$allowedCoverTypes = ['image/jpeg', 'image/png', 'image/gif'];
$allowedAudioTypes = ['audio/mpeg', 'audio/wav', 'audio/mp3'];

$coverFile = $_FILES['cover_image'];
$audioFile = $_FILES['audio_file'];

// Check if files were uploaded
if ($coverFile['error'] !== UPLOAD_ERR_OK || $audioFile['error'] !== UPLOAD_ERR_OK) {
    $response['message'] = "File upload error.";
    echo json_encode($response);
    exit;
}

// Validate cover
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$coverMime = finfo_file($finfo, $coverFile['tmp_name']);
$audioMime = finfo_file($finfo, $audioFile['tmp_name']);

if (!in_array($coverMime, $allowedCoverTypes)) {
    $response['message'] = "Invalid cover image type. Only JPG, PNG, GIF allowed.";
    echo json_encode($response);
    exit;
}
if (!in_array($audioMime, $allowedAudioTypes)) {
    $response['message'] = "Invalid audio type. Only MP3 or WAV allowed.";
    echo json_encode($response);
    exit;
}

// Optional: limit file sizes (e.g., 10MB cover, 100MB audio)
if ($coverFile['size'] > 10 * 1024 * 1024) {
    $response['message'] = "Cover image too large (max 10MB).";
    echo json_encode($response);
    exit;
}
if ($audioFile['size'] > 100 * 1024 * 1024) {
    $response['message'] = "Audio file too large (max 100MB).";
    echo json_encode($response);
    exit;
}

// Generate safe filenames
$coverExt = pathinfo($coverFile['name'], PATHINFO_EXTENSION);
$audioExt = pathinfo($audioFile['name'], PATHINFO_EXTENSION);

$coverName = time() . "_cover_" . uniqid() . "." . $coverExt;
$audioName = time() . "_audio_" . uniqid() . "." . $audioExt;

$coverTarget = $targetDir . $coverName;
$audioTarget = $targetDir . $audioName;

$dbCoverPath = "uploads/" . $coverName;
$dbAudioPath = "uploads/" . $audioName;

// Move files
if (move_uploaded_file($coverFile['tmp_name'], $coverTarget) && 
    move_uploaded_file($audioFile['tmp_name'], $audioTarget)) {

    // SECURE INSERT WITH PREPARED STATEMENT
    $stmt = $conn->prepare("INSERT INTO tracks (title, bpm, track_key, price, tags, cover_image, audio_file) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdsss", $title, $bpm, $track_key, $price, $tags, $dbCoverPath, $dbAudioPath);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Track published successfully!";
    } else {
        // If DB fails, optionally delete uploaded files to avoid orphans
        @unlink($coverTarget);
        @unlink($audioTarget);
        $response['message'] = "Database error: " . $stmt->error;
    }
    $stmt->close();

} else {
    $response['message'] = "Failed to move uploaded files. Check folder permissions.";
}

finfo_close($finfo);
}

echo json_encode($response);
$conn->close();
?>