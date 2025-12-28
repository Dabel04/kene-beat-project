<?php
// 1. Clean Headers & Error Handling
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

// 2. DIRECT CONNECTION (No external file dependency)
$servername = "localhost";
$username = "dabel";      
$password = "go uni1234";          
$dbname = "kene_admin";

$response = [];

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // 3. GET ORDERS
    $orders = [];
    $totalRevenue = 0;
    
    // Check if table exists first
    $checkTable = $conn->query("SHOW TABLES LIKE 'orders'");
    if($checkTable->num_rows > 0) {
        $sql = "SELECT * FROM orders ORDER BY date DESC";
        $result = $conn->query($sql);
        
        if ($result) {
            while($row = $result->fetch_assoc()) {
                $orders[] = $row;
                $totalRevenue += floatval($row['price']);
            }
        }
    }

    // 4. GET BEST SELLERS
    $bestSellers = [];
    if($checkTable->num_rows > 0) {
        $sql = "SELECT track_title, COUNT(*) as sales_count, SUM(price) as revenue 
                FROM orders 
                GROUP BY track_title 
                ORDER BY sales_count DESC 
                LIMIT 5";
        $result = $conn->query($sql);
        
        if ($result) {
            while($row = $result->fetch_assoc()) {
                $bestSellers[] = $row;
            }
        }
    }

    // 5. Send Data
    echo json_encode([
        "status" => "success",
        "orders" => $orders,
        "total_revenue" => $totalRevenue,
        "best_sellers" => $bestSellers
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error", 
        "message" => $e->getMessage()
    ]);
}
?>