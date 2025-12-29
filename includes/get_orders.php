<?php
// includes/get_orders.php
header('Content-Type: application/json');
error_reporting(0); // Supress warnings in JSON output

include '../db_connect.php'; // The only connection you need

$response = [
    "status" => "error",
    "orders" => [],
    "total_revenue" => 0,
    "best_sellers" => []
];

if (isset($conn) && !$conn->connect_error) {
    
    // 1. GET ALL ORDERS
    $orders = [];
    $totalRevenue = 0;
    
    $sql = "SELECT * FROM orders ORDER BY date DESC";
    $result = $conn->query($sql);
    
    if ($result) {
        while($row = $result->fetch_assoc()) {
            $orders[] = $row;
            $totalRevenue += floatval($row['price']);
        }
    }

    // 2. GET BEST SELLERS
    $bestSellers = [];
    $sqlBest = "SELECT track_title, COUNT(*) as sales_count, SUM(price) as revenue 
                FROM orders 
                GROUP BY track_title 
                ORDER BY sales_count DESC 
                LIMIT 5";
    $resultBest = $conn->query($sqlBest);
    
    if ($resultBest) {
        while($row = $resultBest->fetch_assoc()) {
            $bestSellers[] = $row;
        }
    }

    // 3. SUCCESS RESPONSE
    $response = [
        "status" => "success",
        "orders" => $orders,
        "total_revenue" => $totalRevenue,
        "best_sellers" => $bestSellers
    ];
} else {
    $response['message'] = "Database connection failed.";
}

echo json_encode($response);
$conn->close();
?>