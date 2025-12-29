<?php
// includes/edit_track.php
include '../db_connect.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Sanitize Basic Inputs
    $id = intval($_POST['track_id']);
    $title = trim($_POST['title']);
    $bpm = trim($_POST['bpm']);
    $key = trim($_POST['track_key']);
    $price = floatval($_POST['price']);
    $tags = trim($_POST['tags']);

    if ($id <= 0 || empty($title) || empty($bpm)) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
        exit;
    }

    // 2. Prepare Dynamic Update Query
    // We start with the text fields...
    $sql = "UPDATE tracks SET title=?, bpm=?, track_key=?, price=?, tags=?";
    $types = "sssds";
    $params = [$title, $bpm, $key, $price, $tags];

    // 3. Handle Optional File Updates
    // Only update the path if a new file is actually uploaded.

    // A. Cover Image
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
        $coverName = time() . "_art_" . uniqid() . "." . $ext;
        $target = "../uploads/" . $coverName;
        
        if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target)) {
            $sql .= ", cover_image=?";
            $types .= "s";
            $params[] = "uploads/" . $coverName;
        }
    }

    // B. Secure Audio (Untagged)
    if (isset($_FILES['audio_file']) && $_FILES['audio_file']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['audio_file']['name'], PATHINFO_EXTENSION);
        $audioName = time() . "_secure_" . uniqid() . "." . $ext;
        $target = "../uploads/" . $audioName;
        
        if (move_uploaded_file($_FILES['audio_file']['tmp_name'], $target)) {
            $sql .= ", audio_file=?";
            $types .= "s";
            $params[] = "uploads/" . $audioName;
        }
    }

    // C. Tagged Audio (Public Preview)
    if (isset($_FILES['tagged_file']) && $_FILES['tagged_file']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['tagged_file']['name'], PATHINFO_EXTENSION);
        $taggedName = time() . "_public_" . uniqid() . "." . $ext;
        $target = "../uploads/" . $taggedName;
        
        if (move_uploaded_file($_FILES['tagged_file']['tmp_name'], $target)) {
            $sql .= ", tagged_file=?";
            $types .= "s";
            $params[] = "uploads/" . $taggedName;
        }
    }

    // 4. Finalize Query
    $sql .= " WHERE id=?";
    $types .= "i";
    $params[] = $id;

    // 5. Execute
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Track updated successfully.';
    } else {
        $response['message'] = 'Database error: ' . $stmt->error;
    }
    $stmt->close();
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>