<?php
// 1. Setup & Auth
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../getstarted.php"); exit; }

include '../db_connect.php';

// 2. Fetch Stats
// A. Total Sales
$revResult = $conn->query("SELECT SUM(price) as total FROM orders");
$totalRevenue = $revResult->fetch_assoc()['total'] ?? 0;

// B. Total Beats
$beatResult = $conn->query("SELECT COUNT(*) as total FROM tracks");
$totalBeats = $beatResult->fetch_assoc()['total'] ?? 0;

// C. Pending Requests
$reqResult = $conn->query("SELECT COUNT(*) as total FROM service_requests WHERE status='Pending'");
$pendingRequests = $reqResult->fetch_assoc()['total'] ?? 0;

// D. Recent Orders (Last 5)
$recentOrders = $conn->query("SELECT * FROM orders ORDER BY date DESC LIMIT 5");

// 3. INCLUDE THE SIDEBAR
include 'includes/sidebar.php'; 
?>

<div class="flex flex-col gap-8">
    
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-black tracking-tight">Studio <span class="text-primary">Dashboard</span></h1>
            <p class="text-gray-400 mt-2">Overview of your music business.</p>
        </div>
        <div class="hidden md:block text-right">
            <p class="text-xs text-gray-500 uppercase font-bold">Today's Date</p>
            <p class="text-white font-mono"><?php echo date('M d, Y'); ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-6xl">payments</span>
            </div>
            <p class="text-gray-400 text-sm font-bold uppercase tracking-widest">Total Revenue</p>
            <h3 class="text-4xl font-black text-white mt-2">$<?php echo number_format($totalRevenue, 2); ?></h3>
            <a href="order.php" class="inline-flex items-center gap-2 mt-4 text-xs text-primary font-bold hover:underline">View Transactions <span class="material-symbols-outlined text-sm">arrow_forward</span></a>
        </div>

        <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-6xl">library_music</span>
            </div>
            <p class="text-gray-400 text-sm font-bold uppercase tracking-widest">Active Beats</p>
            <h3 class="text-4xl font-black text-white mt-2"><?php echo $totalBeats; ?></h3>
            <a href="admin-beats.php" class="inline-flex items-center gap-2 mt-4 text-xs text-primary font-bold hover:underline">Manage Catalog <span class="material-symbols-outlined text-sm">arrow_forward</span></a>
        </div>

        <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group border-l-4 <?php echo ($pendingRequests > 0) ? 'border-primary' : 'border-gray-700'; ?>">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-6xl">handshake</span>
            </div>
            <p class="text-gray-400 text-sm font-bold uppercase tracking-widest">Pending Requests</p>
            <h3 class="text-4xl font-black text-white mt-2"><?php echo $pendingRequests; ?></h3>
            <a href="requests.php" class="inline-flex items-center gap-2 mt-4 text-xs text-primary font-bold hover:underline">Check Inbox <span class="material-symbols-outlined text-sm">arrow_forward</span></a>
        </div>
    </div>

    <div class="glass-panel rounded-2xl overflow-hidden mt-4">
        <div class="p-6 border-b border-white/5 flex justify-between items-center">
            <h3 class="text-lg font-bold text-white">Recent Orders</h3>
            <a href="order.php" class="text-xs text-gray-400 hover:text-white">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-400">
                <thead class="bg-black/20 text-xs uppercase font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Track</th>
                        <th class="px-6 py-4 text-right">Price</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if($recentOrders->num_rows > 0): ?>
                        <?php while($row = $recentOrders->fetch_assoc()): ?>
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 font-medium text-white"><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($row['track_title']); ?></td>
                            <td class="px-6 py-4 text-right font-mono text-primary">$<?php echo number_format($row['price'], 2); ?></td>
                            <td class="px-6 py-4 text-center"><span class="px-2 py-1 rounded bg-green-500/10 text-green-400 text-[10px] font-bold border border-green-500/20">PAID</span></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="px-6 py-8 text-center italic">No orders yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</div> </body>
</html>