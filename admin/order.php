<?php
// 1. DATABASE CONNECTION
$servername = "localhost";
$username = "dabel";
$password = "go uni1234";
$dbname = "kene_admin";

$conn = new mysqli($servername, $username, $password, $dbname);

// Variables
$orders = [];
$totalRevenue = 0;
$bestSellers = [];
$totalOrders = 0;

// Pagination Variables
$limit = 10; // Orders per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;
$totalPages = 1;

if (!$conn->connect_error) {
    // A. Total Revenue & Count (Global)
    $sumSql = "SELECT SUM(price) as total_rev, COUNT(*) as total_ord FROM orders";
    $sumRes = $conn->query($sumSql);
    if($row = $sumRes->fetch_assoc()){
        $totalRevenue = floatval($row['total_rev']);
        $totalOrders = intval($row['total_ord']);
    }

    // B. Calculate Total Pages
    $totalPages = ceil($totalOrders / $limit);

    // C. Get Paged Orders
    $sql = "SELECT * FROM orders ORDER BY date DESC LIMIT $start, $limit";
    $result = $conn->query($sql);
    if ($result) {
        while($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    }

    // D. Get Best Sellers (Top 5)
    $bestSql = "SELECT track_title, COUNT(*) as sales_count, SUM(price) as revenue 
                FROM orders 
                GROUP BY track_title 
                ORDER BY sales_count DESC 
                LIMIT 5";
    $bestResult = $conn->query($bestSql);
    if ($bestResult) {
        while($row = $bestResult->fetch_assoc()) {
            $bestSellers[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Orders & Sales</title>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: { "primary": "#2bee79", "primary-hover": "#24c765", "background-light": "#f6f8f7", "background-dark": "#102217", "surface-dark": "#162e21" },
                    fontFamily: { "display": ["Spline Sans", "sans-serif"] },
                    boxShadow: { 'glow': '0 0 15px rgba(43, 238, 121, 0.2)' }
                },
            },
        }
    </script>
    <style>
        .glass-panel { background: rgba(22, 46, 33, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-900 dark:text-white">
<div class="relative flex min-h-screen w-full overflow-x-hidden">
    
    <aside class="hidden lg:flex w-72 flex-col fixed inset-y-0 left-0 z-50 border-r border-gray-200 dark:border-white/5 bg-white/50 dark:bg-[#102217]/95 backdrop-blur-xl">
        <div class="flex h-full flex-col justify-between p-6">
            <div class="flex flex-col gap-8">
                <div class="flex items-center gap-4 px-2">
                    <div class="bg-center bg-cover rounded-full h-12 w-12 border-2 border-primary/20" style='background-image: url("https://images.unsplash.com/photo-1470225620780-dba8ba36b745?ixlib=rb-4.0.3&auto=format&fit=crop&w=1740&q=80");'></div>
                    <div><h1 class="text-lg font-bold">Kenton</h1><p class="text-xs text-gray-500 uppercase">Pro Producer</p></div>
                </div>
                <nav class="flex flex-col gap-2">
                    <a class="flex items-center gap-4 px-4 py-3 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 group text-gray-600 dark:text-gray-400 transition-all" href="admin-dashboard.php">
                        <span class="material-symbols-outlined group-hover:text-primary">dashboard</span>
                        <p class="group-hover:text-white text-sm font-medium">Dashboard</p>
                    </a>
                    <a class="flex items-center gap-4 px-4 py-3 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 group text-gray-600 dark:text-gray-400 transition-all" href="admin-beats.php">
                        <span class="material-symbols-outlined group-hover:text-primary">library_music</span>
                        <p class="group-hover:text-white text-sm font-medium">My Beats</p>
                    </a>
                    <a class="flex items-center gap-4 px-4 py-3 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 group text-gray-600 dark:text-gray-400 transition-all" href="customer.php">
                        <span class="material-symbols-outlined group-hover:text-primary">group</span>
                        <p class="group-hover:text-white text-sm font-medium">Customers</p>
                    </a>
                    <a class="flex items-center gap-4 px-4 py-3 rounded-full bg-primary/10 dark:bg-primary/20 border border-primary/20" href="order.php">
                        <span class="material-symbols-outlined text-primary">shopping_bag</span>
                        <p class="text-primary font-semibold text-sm">Orders</p>
                    </a>
                </nav>
            </div>
            <a href="../home.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 dark:text-gray-400 hover:text-red-400"><span class="material-symbols-outlined">logout</span><span class="text-sm font-medium">Log Out</span></a>
        </div>
    </aside>

    <main class="flex-1 lg:ml-72 flex flex-col min-h-screen">
        <div class="flex-1 p-4 md:p-8 lg:p-12 max-w-7xl mx-auto w-full flex flex-col gap-8">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-4xl md:text-5xl font-black tracking-tight">Orders & <span class="text-primary">Sales</span></h1>
                    <p class="text-gray-500 dark:text-gray-400 text-lg">Track your revenue stream.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6">
                <div class="glass-panel p-6 rounded-2xl h-40 flex flex-col justify-between">
                    <div class="flex justify-between"><span class="material-symbols-outlined p-2 bg-white/5 rounded-lg text-primary">payments</span></div>
                    <div><p class="text-sm text-gray-400">Total Revenue</p><h3 class="text-3xl font-bold">$<?php echo number_format($totalRevenue, 2); ?></h3></div>
                </div>
                <div class="glass-panel p-6 rounded-2xl h-40 flex flex-col justify-between">
                    <div class="flex justify-between"><span class="material-symbols-outlined p-2 bg-white/5 rounded-lg text-blue-400">shopping_bag</span></div>
                    <div><p class="text-sm text-gray-400">Total Orders</p><h3 class="text-3xl font-bold"><?php echo $totalOrders; ?></h3></div>
                </div>
                <div class="glass-panel p-6 rounded-2xl h-40 flex flex-col justify-between">
                    <div class="flex justify-between"><span class="material-symbols-outlined p-2 bg-white/5 rounded-lg text-purple-400">star</span></div>
                    <div><p class="text-sm text-gray-400">Best Seller</p><h3 class="text-xl font-bold truncate"><?php echo !empty($bestSellers) ? htmlspecialchars($bestSellers[0]['track_title']) : 'No sales yet'; ?></h3></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 glass-panel rounded-2xl overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-white/5 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white">Recent Transactions</h3>
                        <span class="text-xs text-gray-400">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
                    </div>
                    
                    <div class="overflow-x-auto flex-1">
                        <div class="grid grid-cols-12 gap-4 p-4 text-xs font-bold text-gray-400 uppercase bg-black/20">
                            <div class="col-span-2 pl-2">ID</div>
                            <div class="col-span-4">Customer</div>
                            <div class="col-span-3">Item</div>
                            <div class="col-span-3 text-right pr-2">Amount</div>
                        </div>

                        <div class="divide-y divide-white/5">
                            <?php if (empty($orders)): ?>
                                <div class="p-8 text-center text-gray-500">No orders found.</div>
                            <?php else: ?>
                                <?php foreach($orders as $order): ?>
                                    <div class="grid grid-cols-12 gap-4 p-4 items-center hover:bg-white/5 transition-colors text-sm group">
                                        <div class="col-span-2 text-primary font-mono truncate pl-2 opacity-80 group-hover:opacity-100">#<?php echo $order['id']; ?></div>
                                        <div class="col-span-4 text-white font-medium truncate">
                                            <?php echo htmlspecialchars($order['customer_name']); ?>
                                            <div class="text-xs text-gray-500"><?php echo date('M d, Y', strtotime($order['date'])); ?></div>
                                        </div>
                                        <div class="col-span-3 text-gray-400 truncate"><?php echo htmlspecialchars($order['track_title']); ?></div>
                                        <div class="col-span-3 text-right font-bold text-white pr-2">$<?php echo number_format($order['price'], 2); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if($totalPages > 1): ?>
                    <div class="p-4 border-t border-white/5 flex justify-center items-center gap-2">
                        <?php if($page > 1): ?>
                            <a href="?page=<?php echo $page-1; ?>" class="px-3 py-1 rounded bg-white/5 hover:bg-primary hover:text-black text-xs font-bold transition-colors">Prev</a>
                        <?php endif; ?>
                        
                        <?php for($i=1; $i<=$totalPages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>" class="px-3 py-1 rounded <?php echo ($i == $page) ? 'bg-primary text-black' : 'bg-white/5 text-gray-400 hover:text-white'; ?> text-xs font-bold transition-colors">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if($page < $totalPages): ?>
                            <a href="?page=<?php echo $page+1; ?>" class="px-3 py-1 rounded bg-white/5 hover:bg-primary hover:text-black text-xs font-bold transition-colors">Next</a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="glass-panel rounded-2xl overflow-hidden h-fit">
                    <div class="p-6 border-b border-white/5">
                        <h3 class="text-lg font-bold text-white">Top Performance</h3>
                    </div>
                    <div class="p-4 space-y-2">
                        <?php if (empty($bestSellers)): ?>
                            <div class="text-gray-500 text-sm italic p-4 text-center">No sales data yet.</div>
                        <?php else: ?>
                            <?php foreach($bestSellers as $index => $beat): ?>
                                <div class="flex items-center justify-between p-3 rounded-xl hover:bg-white/5 transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-white/5 group-hover:bg-primary/20 text-gray-400 group-hover:text-primary font-bold text-sm transition-colors">
                                            <?php echo $index + 1; ?>
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-white text-sm truncate w-32 group-hover:text-primary transition-colors">
                                                <?php echo htmlspecialchars($beat['track_title']); ?>
                                            </h4>
                                            <p class="text-xs text-gray-400"><?php echo $beat['sales_count']; ?> Sales</p>
                                        </div>
                                    </div>
                                    <span class="text-white font-bold text-sm">$<?php echo number_format($beat['revenue'], 2); ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>
</body>
</html>