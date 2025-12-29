<?php
// 1. SECURE CONNECTION
// Go up one level to find db_connect.php
include '../db_connect.php'; 

// Initialize Default Values
$totalRevenue = 0;
$totalSales = 0;
$totalTracks = 0;
$recentTracks = [];

if (!$conn->connect_error) {
    
    // A. Get Revenue & Sales Count (From Orders)
    // We check if the 'orders' table exists first to avoid crashes
    $checkOrders = $conn->query("SHOW TABLES LIKE 'orders'");
    if($checkOrders && $checkOrders->num_rows > 0) {
        $sqlStats = "SELECT SUM(price) as rev, COUNT(*) as count FROM orders";
        $resStats = $conn->query($sqlStats);
        if($resStats && $row = $resStats->fetch_assoc()) {
            $totalRevenue = $row['rev'] ? floatval($row['rev']) : 0;
            $totalSales = $row['count'] ? intval($row['count']) : 0;
        }
    }

    // B. Get Total Tracks Count & Recent Uploads
    $sqlTracks = "SELECT COUNT(*) as count FROM tracks";
    $resTracks = $conn->query($sqlTracks);
    if($resTracks && $row = $resTracks->fetch_assoc()) {
        $totalTracks = $row['count'];
    }

    // C. Get Recent Uploaded Beats (Limit 5)
    $sqlRecent = "SELECT * FROM tracks ORDER BY id DESC LIMIT 5";
    $resRecent = $conn->query($sqlRecent);
    if($resRecent) {
        while($row = $resRecent->fetch_assoc()) {
            $recentTracks[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Producer Admin Dashboard</title>
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
                    <a class="flex items-center gap-4 px-4 py-3 rounded-full bg-primary/10 dark:bg-primary/20 border border-primary/20" href="admin-dashboard.php">
                        <span class="material-symbols-outlined text-primary">dashboard</span>
                        <p class="text-primary font-semibold text-sm">Dashboard</p>
                    </a>
                    <a class="flex items-center gap-4 px-4 py-3 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 group text-gray-600 dark:text-gray-400" href="admin-beats.php">
                        <span class="material-symbols-outlined group-hover:text-primary">library_music</span>
                        <p class="group-hover:text-white text-sm font-medium">My Beats</p>
                    </a>
                    <a class="flex items-center gap-4 px-4 py-3 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 group text-gray-600 dark:text-gray-400" href="customer.php">
                        <span class="material-symbols-outlined group-hover:text-primary">group</span>
                        <p class="group-hover:text-white text-sm font-medium">Customers</p>
                    </a>
                    <a class="flex items-center gap-4 px-4 py-3 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 group transition-all text-gray-600 dark:text-gray-400" href="order.php">
                        <span class="material-symbols-outlined group-hover:text-primary transition-colors">shopping_bag</span>
                        <p class="group-hover:text-white text-sm font-medium transition-colors">Orders</p>
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
                    <h1 class="text-4xl md:text-5xl font-black tracking-tight">Studio <span class="text-primary">Command</span></h1>
                    <p class="text-gray-500 dark:text-gray-400 text-lg">Systems online.</p>
                </div>
                <a href="admin-beats.php" class="flex items-center gap-2 bg-primary hover:bg-primary-hover text-background-dark px-6 py-3 rounded-full font-bold shadow-glow transition-transform hover:scale-105">
                    <span class="material-symbols-outlined text-xl">upload_file</span><span>Upload Track</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6">
                <div class="glass-panel p-6 rounded-2xl h-40 flex flex-col justify-between">
                    <div class="flex justify-between"><span class="material-symbols-outlined p-2 bg-white/5 rounded-lg">payments</span><span class="text-primary bg-primary/10 px-2 py-1 rounded-full text-sm">Live</span></div>
                    <div>
                        <p class="text-sm text-gray-400">Total Revenue</p>
                        <h3 class="text-3xl font-bold">$<?php echo number_format($totalRevenue, 2); ?></h3>
                    </div>
                </div>
                <div class="glass-panel p-6 rounded-2xl h-40 flex flex-col justify-between">
                    <div class="flex justify-between"><span class="material-symbols-outlined p-2 bg-white/5 rounded-lg">graphic_eq</span><span class="text-primary bg-primary/10 px-2 py-1 rounded-full text-sm">Active</span></div>
                    <div>
                        <p class="text-sm text-gray-400">Total Tracks</p>
                        <h3 class="text-3xl font-bold"><?php echo $totalTracks; ?></h3>
                    </div>
                </div>
                <div class="glass-panel p-6 rounded-2xl h-40 flex flex-col justify-between">
                    <div class="flex justify-between"><span class="material-symbols-outlined p-2 bg-white/5 rounded-lg">download</span><span class="text-primary bg-primary/10 px-2 py-1 rounded-full text-sm">Lifetime</span></div>
                    <div>
                        <p class="text-sm text-gray-400">Total Sales</p>
                        <h3 class="text-3xl font-bold"><?php echo $totalSales; ?></h3>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold">Recent Uploads</h2>
                    <a href="admin-beats.php" class="text-sm text-primary hover:underline">View All</a>
                </div>
                <div class="glass-panel rounded-2xl overflow-hidden bg-white dark:bg-surface-dark">
                    <div class="grid grid-cols-12 gap-4 p-4 border-b border-white/5 text-xs font-bold text-gray-400 uppercase bg-black/20">
                        <div class="col-span-5 md:col-span-4 pl-2">Track Details</div>
                        <div class="hidden md:flex col-span-2 justify-center">BPM / Key</div>
                        <div class="hidden md:flex col-span-2 justify-center">Price</div>
                        <div class="col-span-3 md:col-span-2 text-center">Status</div>
                        <div class="col-span-4 md:col-span-2 text-right pr-2">Actions</div>
                    </div>

                    <div id="dashboard-table-body">
                        <?php if (empty($recentTracks)): ?>
                            <div class="p-8 text-center text-gray-500">No tracks uploaded yet.</div>
                        <?php else: ?>
                            <?php foreach($recentTracks as $beat): ?>
                                <?php 
                                    // Handle Image Path (Ensure it works in Admin folder)
                                    $imgPath = $beat['cover_image'];
                                    if ($imgPath && !str_starts_with($imgPath, '../') && !str_starts_with($imgPath, 'http')) {
                                        $imgPath = '../' . $imgPath;
                                    }
                                ?>
                                <div class="grid grid-cols-12 gap-4 p-4 items-center border-b border-white/5 hover:bg-white/5 transition-colors group">
                                    <div class="col-span-5 md:col-span-4 flex items-center gap-4">
                                        <div class="relative w-12 h-12 rounded-lg overflow-hidden flex-shrink-0">
                                            <img class="w-full h-full object-cover" src="<?php echo htmlspecialchars($imgPath); ?>" onerror="this.src='https://via.placeholder.com/50'"/>
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <h4 class="text-white font-bold truncate"><?php echo htmlspecialchars($beat['title']); ?></h4>
                                            <p class="text-xs text-gray-400"><?php echo htmlspecialchars($beat['tags']); ?></p>
                                        </div>
                                    </div>
                                    <div class="hidden md:flex col-span-2 flex-col items-center justify-center">
                                        <span class="text-white font-medium text-sm"><?php echo htmlspecialchars($beat['bpm']); ?></span>
                                        <span class="text-xs text-gray-400"><?php echo htmlspecialchars($beat['track_key']); ?></span>
                                    </div>
                                    <div class="hidden md:flex col-span-2 items-center justify-center">
                                        <span class="text-primary font-bold">$<?php echo number_format($beat['price'], 2); ?></span>
                                    </div>
                                    <div class="col-span-3 md:col-span-2 flex items-center justify-center">
                                        <span class="px-2 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-bold border border-primary/20">PUBLISHED</span>
                                    </div>
                                    <div class="col-span-4 md:col-span-2 flex items-center justify-end gap-2 pr-2">
                                        <a href="admin-beats.php" class="p-2 rounded-full hover:bg-white/10 text-gray-400 hover:text-white transition-colors">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="fixed bottom-0 left-0 lg:left-72 right-0 bg-[#162e21] border-t border-primary/20 p-3 z-50 flex items-center justify-between gap-4 backdrop-blur-xl">
                <div class="flex items-center gap-3 w-1/4">
                    <div class="bg-center bg-cover rounded-md h-10 w-10 flex-shrink-0" style='background-image: url("https://images.unsplash.com/photo-1470225620780-dba8ba36b745?ixlib=rb-4.0.3&auto=format&fit=crop&w=1740&q=80");'></div>
                    <div class="hidden md:flex flex-col"><span class="text-white text-sm font-bold">Midnight Vibes</span><span class="text-primary text-xs">Playing</span></div>
                </div>
                <div class="flex flex-col items-center flex-1 max-w-lg">
                    <div class="flex items-center gap-4 mb-1"><span class="material-symbols-outlined text-gray-400">skip_previous</span><span class="material-symbols-outlined text-2xl bg-primary text-black rounded-full">play_arrow</span><span class="material-symbols-outlined text-gray-400">skip_next</span></div>
                    <div class="w-full h-1 bg-white/10 rounded-full"><div class="h-full bg-primary w-1/3"></div></div>
                </div>
                <div class="flex items-center gap-3 justify-end w-1/4"><span class="material-symbols-outlined text-gray-400">volume_up</span></div>
            </div>
        </div>
    </main>
</div>
</body>
</html>