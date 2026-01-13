<?php
include 'db_connect.php';

echo "<h1>System Diagnostic</h1>";

function checkColumn($conn, $table, $column) {
    $result = $conn->query("SHOW COLUMNS FROM $table LIKE '$column'");
    if ($result && $result->num_rows > 0) {
        return "<span style='color:green; font-weight:bold;'>[PASS]</span> Column '$column' exists in '$table'.<br>";
    } else {
        return "<span style='color:red; font-weight:bold;'>[FAIL]</span> Column '$column' MISSING in '$table'.<br>";
    }
}

function checkTable($conn, $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result && $result->num_rows > 0) {
        return "<span style='color:green; font-weight:bold;'>[PASS]</span> Table '$table' exists.<br>";
    } else {
        return "<span style='color:red; font-weight:bold;'>[FAIL]</span> Table '$table' MISSING.<br>";
    }
}

// 1. Check Tables
echo "<h3>1. Database Tables</h3>";
echo checkTable($conn, 'sound_kits');
echo checkTable($conn, 'orders');
echo checkTable($conn, 'cart');

// 2. Check Columns
echo "<h3>2. Required Columns</h3>";
echo checkColumn($conn, 'cart', 'product_type');
echo checkColumn($conn, 'orders', 'product_type');
echo checkColumn($conn, 'orders', 'product_id');

// 3. Check Session
session_start();
echo "<h3>3. Session Status</h3>";
echo "User ID: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "<span style='color:red'>Not Logged In</span>") . "<br>";

echo "<hr>";
echo "<h3>FIX COMMANDS (Run in PHPMyAdmin if you see FAIL above)</h3>";
echo "<pre style='background:#eee; padding:10px;'>";
echo "ALTER TABLE cart ADD COLUMN product_type VARCHAR(20) DEFAULT 'beat';\n";
echo "ALTER TABLE orders ADD COLUMN product_type VARCHAR(20) DEFAULT 'beat';\n";
echo "ALTER TABLE orders ADD COLUMN product_id INT(11) DEFAULT 0;";
echo "</pre>";
?>