<?php
// 1. Start Session & Connect DB
session_start();
include 'db_connect.php';

// 2. Security Checks
if (!isset($_SESSION['user_id'])) {
    die("Access Denied: Please log in.");
}

if (!isset($_GET['track'])) {
    die("Error: No track specified.");
}

$user_id = $_SESSION['user_id'];
$track_title = urldecode($_GET['track']); // Decode "My%20Beat" back to "My Beat"

// 3. Verify Ownership
// Check if this specific user has a record in 'orders' for this track
$stmt = $conn->prepare("SELECT id FROM orders WHERE user_id = ? AND track_title = ?");
$stmt->bind_param("is", $user_id, $track_title);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Access Denied: You have not purchased this track.");
}

// 4. Get File Path from Database
$stmt_file = $conn->prepare("SELECT audio_file FROM tracks WHERE title = ?");
$stmt_file->bind_param("s", $track_title);
$stmt_file->execute();
$file_result = $stmt_file->get_result();
$track_data = $file_result->fetch_assoc();

if (!$track_data || empty($track_data['audio_file'])) {
    die("Error: File path not found in database.");
}

$file_path = $track_data['audio_file'];

// 5. Serve the File
if (file_exists($file_path)) {
    // Define headers to tell browser "This is a file download"
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file_path).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_path));
    
    // Clear any previous output buffers to avoid corrupting the MP3
    ob_clean();
    flush();
    
    readfile($file_path);
    exit;
} else {
    die("Error: File not found on server ($file_path).");
}
?>