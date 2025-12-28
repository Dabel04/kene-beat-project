<?php
// Prevent re-declaring the connection if it already exists
if (isset($conn)) {
    return;
}

$servername = "localhost";
$username = "dabel"; 
$password = "go uni1234"; 
$dbname = "kene_admin";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>