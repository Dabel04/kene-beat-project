<?php
// Ensure session is started if not already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Current Page Logic for Active State
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin | KentonTheProducer</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: { 
                        "primary": "#2bee79", 
                        "primary-hover": "#24c765", 
                        "background-dark": "#102217", 
                        "surface-dark": "#162e21" 
                    },
                    fontFamily: { "display": ["Spline Sans", "sans-serif"] },
                    boxShadow: { 'glow': '0 0 15px rgba(43, 238, 121, 0.2)' }
                },
            },
        }
    </script>
    <style>
        .glass-panel { background: rgba(22, 46, 33, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.05); }
        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #102217; }
        ::-webkit-scrollbar-thumb { background: #2bee79; border-radius: 4px; }
    </style>
</head>
<body class="bg-background-dark font-display text-white">
<div class="flex min-h-screen">
    
    <aside class="hidden lg:flex w-72 flex-col fixed inset-y-0 left-0 border-r border-white/5 bg-[#102217]/95 backdrop-blur-xl p-6 z-50">
        <div class="flex items-center gap-4 mb-10 px-2">
            <div class="h-10 w-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold border border-primary/30">K</div>
            <div>
                <h1 class="text-lg font-bold tracking-wide">Kenton<span class="text-primary">.</span></h1>
                <p class="text-xs text-gray-500 uppercase tracking-widest">Admin Panel</p>
            </div>
        </div>

        <nav class="flex flex-col gap-2 flex-1">
            
            <a href="admin-dashboard.php" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all <?php echo ($current_page == 'admin-dashboard.php') ? 'bg-primary/10 text-primary border border-primary/20' : 'hover:bg-white/5 text-gray-400 hover:text-white'; ?>">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="text-sm font-bold">Dashboard</span>
            </a>

            <a href="admin-beats.php" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all <?php echo ($current_page == 'admin-beats.php') ? 'bg-primary/10 text-primary border border-primary/20' : 'hover:bg-white/5 text-gray-400 hover:text-white'; ?>">
                <span class="material-symbols-outlined">library_music</span>
                <span class="text-sm font-bold">My Beats</span>
            </a>

            <a href="order.php" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all <?php echo ($current_page == 'order.php') ? 'bg-primary/10 text-primary border border-primary/20' : 'hover:bg-white/5 text-gray-400 hover:text-white'; ?>">
                <span class="material-symbols-outlined">shopping_bag</span>
                <span class="text-sm font-bold">Orders</span>
            </a>

            <a href="customer.php" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all <?php echo ($current_page == 'customer.php') ? 'bg-primary/10 text-primary border border-primary/20' : 'hover:bg-white/5 text-gray-400 hover:text-white'; ?>">
                <span class="material-symbols-outlined">group</span>
                <span class="text-sm font-bold">Customers</span>
            </a>

            <a href="admin-kits.php" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all <?php echo ($current_page == 'admin-kits.php') ? 'bg-primary/10 text-primary border border-primary/20' : 'hover:bg-white/5 text-gray-400 hover:text-white'; ?>">
                <span class="material-symbols-outlined">folder_zip</span>
                <span class="text-sm font-bold">Sound Kits</span>
            </a>

            <a href="requests.php" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all <?php echo ($current_page == 'requests.php') ? 'bg-primary/10 text-primary border border-primary/20' : 'hover:bg-white/5 text-gray-400 hover:text-white'; ?>">
                <span class="material-symbols-outlined">handshake</span>
                <span class="text-sm font-bold">Requests</span>
            </a>

        </nav>

        <div class="mt-auto pt-6 border-t border-white/5">
            <a href="../home.php" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:text-white transition-colors">
                <span class="material-symbols-outlined">public</span>
                <span class="text-sm font-medium">View Website</span>
            </a>
            <a href="../logout.php" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:text-red-300 transition-colors">
                <span class="material-symbols-outlined">logout</span>
                <span class="text-sm font-medium">Log Out</span>
            </a>
        </div>
    </aside>

    <main class="flex-1 lg:ml-72 p-4 md:p-8 lg:p-12 min-w-0">