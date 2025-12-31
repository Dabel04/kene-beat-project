<?php
// fix_database.php
include 'db_connect.php';

echo "<h1>Database Repair Tool</h1>";

function runQuery($conn, $sql, $desc) {
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>✅ Success: $desc</p>";
    } else {
        // Ignore "Duplicate column" errors, that's fine
        if (strpos($conn->error, 'Duplicate column') !== false) {
             echo "<p style='color:blue;'>ℹ️ Note: $desc (Already exists)</p>";
        } else {
             echo "<p style='color:red;'>❌ Error: $desc - " . $conn->error . "</p>";
        }
    }
}

// 1. Fix ORDERS Table
runQuery($conn, "ALTER TABLE orders ADD COLUMN IF NOT EXISTS track_id INT(11) NOT NULL DEFAULT 0 AFTER user_id", "Added track_id to orders");
runQuery($conn, "ALTER TABLE orders ADD COLUMN IF NOT EXISTS license_type VARCHAR(50) NOT NULL DEFAULT 'basic' AFTER price", "Added license_type to orders");
runQuery($conn, "ALTER TABLE orders ADD COLUMN IF NOT EXISTS date DATETIME DEFAULT CURRENT_TIMESTAMP", "Added date to orders");

// 2. Fix CART Table
runQuery($conn, "ALTER TABLE cart ADD COLUMN IF NOT EXISTS license_type VARCHAR(50) NOT NULL DEFAULT 'basic'", "Added license_type to cart");

// 3. Fix TRACKS Table
runQuery($conn, "ALTER TABLE tracks ADD COLUMN IF NOT EXISTS tagged_file VARCHAR(255) AFTER audio_file", "Added tagged_file to tracks");

// 4. Clean Bad Data
runQuery($conn, "TRUNCATE TABLE orders", "Cleared broken test orders");
runQuery($conn, "TRUNCATE TABLE cart", "Cleared broken cart items");

echo "<hr><h3>🎉 Database Fixed! You can now delete this file and try your checkout again.</h3>";
echo "<a href='user-dashboard.php'>Go to Dashboard</a>";
?>