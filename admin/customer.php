<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Customers - Admin</title>
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
                    <a class="flex items-center gap-4 px-4 py-3 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 group text-gray-600 dark:text-gray-400" href="admin-dashboard.php">
                        <span class="material-symbols-outlined group-hover:text-primary">dashboard</span>
                        <p class="group-hover:text-white text-sm font-medium">Dashboard</p>
                    </a>
                    <a class="flex items-center gap-4 px-4 py-3 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 group text-gray-600 dark:text-gray-400" href="admin-beats.php">
                        <span class="material-symbols-outlined group-hover:text-primary">library_music</span>
                        <p class="group-hover:text-white text-sm font-medium">My Beats</p>
                    </a>
                    <a class="flex items-center gap-4 px-4 py-3 rounded-full bg-primary/10 dark:bg-primary/20 border border-primary/20" href="customer.php">
                        <span class="material-symbols-outlined text-primary">group</span>
                        <p class="text-primary font-semibold text-sm">Customers</p>
                    </a>
                    <a class="flex items-center gap-4 px-4 py-3 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 group text-gray-600 dark:text-gray-400" href="order.php">
                        <span class="material-symbols-outlined group-hover:text-primary">shopping_bag</span>
                        <p class="group-hover:text-white text-sm font-medium">Orders</p>
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
                    <h1 class="text-4xl md:text-5xl font-black tracking-tight">Customer <span class="text-primary">List</span></h1>
                    <p class="text-gray-500 dark:text-gray-400 text-lg">Real data from your database.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="glass-panel p-4 rounded-xl flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold">Total Clients</p>
                        <p class="text-2xl font-bold text-white" id="total-customers-count">0</p>
                    </div>
                    <span class="material-symbols-outlined text-primary/50 text-3xl">groups</span>
                </div>
                <div class="glass-panel p-4 rounded-xl flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold">New This Month</p>
                        <p class="text-2xl font-bold text-white" id="new-customers-count">0</p>
                    </div>
                    <span class="material-symbols-outlined text-blue-400/50 text-3xl">person_add</span>
                </div>
            </div>

            <div class="glass-panel rounded-2xl overflow-hidden bg-white dark:bg-surface-dark">
                <div class="grid grid-cols-12 gap-4 p-4 border-b border-white/5 text-xs font-bold text-gray-400 uppercase bg-black/20">
                    <div class="col-span-5 md:col-span-4 pl-2">Customer</div>
                    <div class="col-span-3 text-center">Joined</div>
                    <div class="col-span-2 text-center">Orders</div>
                    <div class="col-span-2 text-right pr-2">Lifetime Value</div>
                </div>
                
                <div id="customer-table-body">
                    <div class="p-8 text-center text-gray-500">Loading real data...</div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        loadCustomers();
    });

    function loadCustomers() {
        fetch('../includes/get_customers.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('customer-table-body');
            const countEl = document.getElementById('total-customers-count');
            const newEl = document.getElementById('new-customers-count');

            if (!tbody) return;
            tbody.innerHTML = '';

            // Update Stats
            if(countEl) countEl.innerText = data.total_count || 0;
            if(newEl) newEl.innerText = data.new_this_month || 0;

            const list = data.list || [];

            if (list.length === 0) {
                tbody.innerHTML = `
                    <div class="p-12 text-center flex flex-col items-center justify-center text-gray-500">
                        <span class="material-symbols-outlined text-4xl mb-2 opacity-30">group_off</span>
                        <p>No sales yet.</p>
                    </div>`;
                return;
            }

            list.forEach(cust => {
                const name = cust.customer_name || 'Guest';
                const email = cust.customer_email || 'No Email';
                const spent = parseFloat(cust.total_spent || 0).toFixed(2);
                const orders = cust.total_orders;
                const joined = new Date(cust.first_purchase).toLocaleDateString();
                const initial = name.charAt(0).toUpperCase();

                tbody.innerHTML += `
                <div class="grid grid-cols-12 gap-4 p-4 items-center border-b border-white/5 hover:bg-white/5 transition-colors group">
                    <div class="col-span-5 md:col-span-4 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary/20 to-blue-500/20 flex items-center justify-center text-primary font-bold shadow-glow border border-white/5">
                            ${initial}
                        </div>
                        <div class="flex flex-col min-w-0">
                            <h4 class="text-white font-bold truncate">${name}</h4>
                            <p class="text-xs text-gray-400 truncate">${email}</p>
                        </div>
                    </div>

                    <div class="col-span-3 flex items-center justify-center">
                        <span class="text-xs text-gray-400">${joined}</span>
                    </div>

                    <div class="col-span-2 flex items-center justify-center text-center">
                        <span class="px-2 py-1 rounded-full bg-white/5 text-xs font-bold">${orders} Orders</span>
                    </div>

                    <div class="col-span-2 flex items-center justify-end pr-2">
                        <span class="text-primary font-bold">$${spent}</span>
                    </div>
                </div>`;
            });
        })
        .catch(err => {
            console.error(err);
            document.getElementById('customer-table-body').innerHTML = '<div class="p-4 text-center text-red-500">Failed to load data.</div>';
        });
    }
</script>
</body>
</html>