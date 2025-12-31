<?php
// 1. Connect & Check Admin
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../getstarted.php"); exit; }

include '../db_connect.php';

// 2. Handle Actions (Delete / Update Status)
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM service_requests WHERE id=$id");
    header("Location: requests.php");
}

if (isset($_GET['mark_done'])) {
    $id = intval($_GET['mark_done']);
    $conn->query("UPDATE service_requests SET status='Completed' WHERE id=$id");
    header("Location: requests.php");
}

// 3. Fetch Requests
$sql = "SELECT * FROM service_requests ORDER BY created_at DESC";
$result = $conn->query($sql);

// 4. Include Sidebar
include 'includes/sidebar.php';
?>

<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
    <div>
        <h1 class="text-4xl md:text-5xl font-black tracking-tight">Service <span class="text-primary">Requests</span></h1>
        <p class="text-gray-400 dark:text-gray-400 text-lg">Manage collaborations and custom beat inquiries.</p>
    </div>
</div>

<div class="glass-panel rounded-2xl overflow-hidden bg-white dark:bg-surface-dark">
    <div class="grid grid-cols-12 gap-4 p-4 border-b border-white/5 text-xs font-bold text-gray-400 uppercase bg-black/20">
        <div class="col-span-1">ID</div>
        <div class="col-span-3">Artist / Email</div>
        <div class="col-span-2">Service Type</div>
        <div class="col-span-4">Project Details</div>
        <div class="col-span-2 text-right">Actions</div>
    </div>

    <div class="divide-y divide-white/5">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="grid grid-cols-12 gap-4 p-4 items-center hover:bg-white/5 transition-colors group">
                    <div class="col-span-1 text-gray-500">#<?php echo $row['id']; ?></div>
                    
                    <div class="col-span-3">
                        <div class="font-bold text-white"><?php echo htmlspecialchars($row['customer_name']); ?></div>
                        <div class="text-xs text-primary"><?php echo htmlspecialchars($row['email']); ?></div>
                        <div class="text-[10px] text-gray-500 mt-1"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></div>
                    </div>

                    <div class="col-span-2">
                        <span class="px-2 py-1 rounded bg-white/10 text-xs font-bold border border-white/10 <?php echo ($row['status'] == 'Completed') ? 'text-green-400 border-green-500/30' : 'text-yellow-400 border-yellow-500/30'; ?>">
                            <?php echo htmlspecialchars($row['service_type']); ?>
                        </span>
                        <?php if($row['beat_reference']): ?>
                            <div class="text-[10px] text-gray-400 mt-2">
                                Beat: <span class="text-white"><?php echo htmlspecialchars($row['beat_reference']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-span-4 text-sm text-gray-300">
                        <p class="line-clamp-2"><?php echo htmlspecialchars($row['project_details']); ?></p>
                    </div>

                    <div class="col-span-2 flex justify-end gap-2">
                        <?php if($row['status'] != 'Completed'): ?>
                        <a href="?mark_done=<?php echo $row['id']; ?>" class="p-2 rounded-full hover:bg-green-500/20 text-gray-400 hover:text-green-500 transition-colors" title="Mark as Done">
                            <span class="material-symbols-outlined text-lg">check_circle</span>
                        </a>
                        <?php endif; ?>

                        <a href="mailto:<?php echo $row['email']; ?>?subject=Re: Your Request - <?php echo $row['service_type']; ?>" class="p-2 rounded-full bg-primary text-black hover:bg-white transition-colors" title="Reply">
                            <span class="material-symbols-outlined text-lg">mail</span>
                        </a>

                        <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this request?')" class="p-2 rounded-full hover:bg-red-500/20 text-gray-400 hover:text-red-500 transition-colors" title="Delete">
                            <span class="material-symbols-outlined text-lg">delete</span>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="p-12 text-center flex flex-col items-center justify-center text-gray-500">
                <span class="material-symbols-outlined text-4xl mb-2 opacity-30">inbox</span>
                <p>No new requests found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

</main>
</div>
</body>
</html>