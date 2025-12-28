<?php
header('Content-Type: application/json');
error_reporting(0);

$rootPath = dirname(__DIR__); 
$dbFile = $rootPath . '/db_connect.php';

if (!file_exists($dbFile)) {
    echo json_encode(["error" => "db_connect.php missing"]);
    exit;
}
require_once $dbFile;

// 1. Get List of Unique Customers
$sql = "SELECT 
            customer_name, 
            customer_email, 
            COUNT(*) as total_orders, 
            SUM(price) as total_spent, 
            MIN(date) as first_purchase,
            MAX(date) as last_order 
        FROM orders 
        GROUP BY customer_email 
        ORDER BY total_spent DESC";

$result = $conn->query($sql);

$customers = [];
$newThisMonth = 0;
$currentMonth = date('Y-m'); // e.g., "2023-10"

if ($result) {
    while($row = $result->fetch_assoc()) {
        $customers[] = $row;

        // Check if their first purchase was this month
        $joinDate = substr($row['first_purchase'], 0, 7); // Get "YYYY-MM"
        if ($joinDate === $currentMonth) {
            $newThisMonth++;
        }
    }
}

// 2. Return Data + Stats
echo json_encode([
    "list" => $customers,
    "total_count" => count($customers),
    "new_this_month" => $newThisMonth
]);

$conn->close();
?>