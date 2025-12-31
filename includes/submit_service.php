<?php
include '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Sanitize Input
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $service = $conn->real_escape_string($_POST['service_type']);
    $beat = $conn->real_escape_string($_POST['beat_name']);
    $details = $conn->real_escape_string($_POST['project_details']);

    // 2. Insert into Database
    $sql = "INSERT INTO service_requests (customer_name, email, service_type, beat_reference, project_details) 
            VALUES ('$name', '$email', '$service', '$beat', '$details')";

    if ($conn->query($sql) === TRUE) {
        // Success: Go back to services page with success flag
        header("Location: ../services.php?success=1");
    } else {
        // Error: Go back with error message
        header("Location: ../services.php?error=" . urlencode($conn->error));
    }
} else {
    header("Location: ../services.php");
}
$conn->close();
?>