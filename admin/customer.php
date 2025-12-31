<?php
// 1. Setup & Auth
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../getstarted.php"); exit; }

include '../db_connect.php';

// 2. Include Sidebar & Layout
include 'includes/sidebar.php'; 
?>

<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
    <div>
        <h1 class="text-4xl md:text-5xl font-black tracking-tight">Customer <span class="text-primary">List</span></h1>
        <p class="text-gray-400 dark:text-gray-400 text-lg">Real data from your database.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
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

</main>
</div>
</body>
</html>