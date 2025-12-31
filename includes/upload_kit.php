<?php
// 1. Setup & Debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../db_connect.php';
header('Content-Type: application/json');

// Helper to explain PHP upload errors
function getUploadErrorMessage($errCode) {
    switch ($errCode) {
        case UPLOAD_ERR_INI_SIZE: return 'File exceeds upload_max_filesize in php.ini';
        case UPLOAD_ERR_FORM_SIZE: return 'File exceeds MAX_FILE_SIZE in HTML form';
        case UPLOAD_ERR_PARTIAL: return 'File was only partially uploaded';
        case UPLOAD_ERR_NO_FILE: return 'No file was uploaded';
        case UPLOAD_ERR_NO_TMP_DIR: return 'Missing a temporary folder';
        case UPLOAD_ERR_CANT_WRITE: return 'Failed to write file to disk';
        case UPLOAD_ERR_EXTENSION: return 'A PHP extension stopped the file upload';
        default: return 'Unknown upload error';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 2. Sanitize Inputs
    $title = $conn->real_escape_string($_POST['title'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $desc  = $conn->real_escape_string($_POST['desc'] ?? '');
    
    if(!$title) {
        echo json_encode(['success' => false, 'message' => 'Title is required']);
        exit;
    }

    // 3. Setup Upload Directory
    $targetDir = "../uploads/";
    if (!file_exists($targetDir)) {
        if (!mkdir($targetDir, 0777, true)) {
            echo json_encode(['success' => false, 'message' => 'Failed to create uploads folder. Check permissions.']);
            exit;
        }
    }

    // 4. Handle File Uploads (With Error Checking)
    function processUpload($fileKey, $prefix) {
        global $targetDir;
        
        if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] != UPLOAD_ERR_OK) {
            $errorCode = $_FILES[$fileKey]['error'] ?? UPLOAD_ERR_NO_FILE;
            return ['error' => "Error uploading $fileKey: " . getUploadErrorMessage($errorCode)];
        }

        $file = $_FILES[$fileKey];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Validation
        if ($prefix == 'cover' && !in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) return ['error' => 'Cover must be an image (jpg/png)'];
        if ($prefix == 'demo' && !in_array($ext, ['mp3', 'wav'])) return ['error' => 'Demo must be audio (mp3/wav)'];
        if ($prefix == 'kit' && !in_array($ext, ['zip', 'rar'])) return ['error' => 'Kit must be an archive (zip/rar)'];

        $fileName = time() . '_' . $prefix . '_' . preg_replace("/[^a-zA-Z0-9\.]/", "", basename($file["name"]));
        $targetPath = $targetDir . $fileName;

        if(move_uploaded_file($file["tmp_name"], $targetPath)) {
            return ['path' => "uploads/" . $fileName];
        }
        
        return ['error' => "Failed to move $fileKey to uploads folder."];
    }

    // Process all 3 files
    $coverResult = processUpload('cover_image', 'cover');
    if (isset($coverResult['error'])) { echo json_encode(['success' => false, 'message' => $coverResult['error']]); exit; }

    $audioResult = processUpload('demo_audio', 'demo');
    if (isset($audioResult['error'])) { echo json_encode(['success' => false, 'message' => $audioResult['error']]); exit; }

    $zipResult = processUpload('zip_file', 'kit');
    if (isset($zipResult['error'])) { echo json_encode(['success' => false, 'message' => $zipResult['error']]); exit; }

    // 5. Insert Database
    $coverPath = $coverResult['path'];
    $audioPath = $audioResult['path'];
    $zipPath   = $zipResult['path'];

    $sql = "INSERT INTO sound_kits (title, price, description, cover_image, demo_audio, zip_file) 
            VALUES ('$title', '$price', '$desc', '$coverPath', '$audioPath', '$zipPath')";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database Error: ' . $conn->error]);
    }
}
?>