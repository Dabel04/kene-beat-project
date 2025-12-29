<?php
include '../db_connect.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $title = trim($_POST['title']);
    $bpm = trim($_POST['bpm']);
    $track_key = trim($_POST['track_key']);
    $price = floatval($_POST['price']);
    $tags = trim($_POST['tags']);

    if (empty($title) || $price <= 0) {
        echo json_encode(['success' => false, 'message' => "Invalid title or price."]);
        exit;
    }

    $targetDir = "../uploads/";
    if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

    // --- FILE HANDLERS ---
    $cover = $_FILES['cover_image'];
    $audio = $_FILES['audio_file']; // The UNTAGGED (Protected) file
    $tagged = $_FILES['tagged_file']; // The TAGGED (Public) file

    if ($cover['error'] !== UPLOAD_ERR_OK || $audio['error'] !== UPLOAD_ERR_OK || $tagged['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => "All files (Cover, Untagged Audio, Tagged Audio) are required."]);
        exit;
    }

    // Generate Names
    $coverName  = time() . "_art_" . uniqid() . "." . pathinfo($cover['name'], PATHINFO_EXTENSION);
    $audioName  = time() . "_secure_" . uniqid() . "." . pathinfo($audio['name'], PATHINFO_EXTENSION);
    $taggedName = time() . "_public_" . uniqid() . "." . pathinfo($tagged['name'], PATHINFO_EXTENSION);

    // Move Files
    if (move_uploaded_file($cover['tmp_name'], $targetDir . $coverName) && 
        move_uploaded_file($audio['tmp_name'], $targetDir . $audioName) &&
        move_uploaded_file($tagged['tmp_name'], $targetDir . $taggedName)) {

        // DB Paths
        $dbCover  = "uploads/" . $coverName;
        $dbAudio  = "uploads/" . $audioName;
        $dbTagged = "uploads/" . $taggedName;

        // INSERT (Now includes tagged_file)
        $stmt = $conn->prepare("INSERT INTO tracks (title, bpm, track_key, price, tags, cover_image, audio_file, tagged_file) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdssss", $title, $bpm, $track_key, $price, $tags, $dbCover, $dbAudio, $dbTagged);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Track published successfully!";
        } else {
            $response['message'] = "Database Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = "Failed to save files to disk.";
    }
}
echo json_encode($response);
?>