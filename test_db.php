<?php
// Enable error reporting to see everything
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Database Diagnostic Tool</h1>";

// 1. Test Connection
echo "<h3>1. Testing Connection...</h3>";
if(file_exists('db_connect.php')) {
    include 'db_connect.php';
    echo "<p style='color:green'>✅ db_connect.php found.</p>";
} else {
    die("<p style='color:red'>❌ db_connect.php is MISSING.</p>");
}

if ($conn->connect_error) {
    die("<p style='color:red'>❌ Connection Failed: " . $conn->connect_error . "</p>");
} else {
    echo "<p style='color:green'>✅ Database Connected successfully.</p>";
}

// 2. Test Tracks Table
echo "<h3>2. Testing 'tracks' Table...</h3>";
$sql = "SELECT * FROM tracks";
$result = $conn->query($sql);

if ($result) {
    echo "<p style='color:green'>✅ Query ran successfully.</p>";
    $count = $result->num_rows;
    echo "<p><strong>Found $count tracks in the database.</strong></p>";

    if ($count > 0) {
        $row = $result->fetch_assoc();
        echo "<pre style='background:#eee; padding:10px;'>";
        print_r($row); // Print the first track data
        echo "</pre>";
    } else {
        echo "<p style='color:orange'>⚠️ The table exists, but it is EMPTY (0 rows).</p>";
    }
} else {
    echo "<p style='color:red'>❌ Query Failed.</p>";
    echo "<p><strong>Error Message:</strong> " . $conn->error . "</p>";
}
?>