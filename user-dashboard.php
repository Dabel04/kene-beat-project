<?php
// 1. Include Header
include 'header.php';

// Force login check
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='getstarted.php';</script>";
    exit;
}

$current_user_id = $_SESSION['user_id'];
$user = isset($_SESSION['username']) ? $_SESSION['username'] : 'Producer';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : 'artist@example.com';
$msg = "";

// --- 2. HANDLE SETTINGS FORM ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $new_email = trim($_POST['email']);
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    $current_pass = $_POST['current_password'];

    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $stmt->close();

    if ($user_data && password_verify($current_pass, $user_data['password_hash'])) {
        if (!empty($new_email) && $new_email !== $_SESSION['email']) {
            $upd = $conn->prepare("UPDATE users SET email = ? WHERE user_id = ?");
            $upd->bind_param("si", $new_email, $current_user_id);
            if ($upd->execute()) {
                $_SESSION['email'] = $new_email;
                $email = $new_email;
                $msg .= "<div class='alert alert-success'>✅ Email updated successfully!</div>";
            }
            $upd->close();
        }
        if (!empty($new_pass)) {
            if ($new_pass === $confirm_pass) {
                if (strlen($new_pass) >= 6) {
                    $hash = password_hash($new_pass, PASSWORD_DEFAULT);
                    $upd = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
                    $upd->bind_param("si", $hash, $current_user_id);
                    if ($upd->execute()) {
                        $msg .= "<div class='alert alert-success'>✅ Password updated successfully!</div>";
                    }
                    $upd->close();
                } else {
                    $msg .= "<div class='alert alert-danger'>⚠️ Password must be at least 6 characters.</div>";
                }
            } else {
                $msg .= "<div class='alert alert-danger'>⚠️ New passwords do not match.</div>";
            }
        }
    } else {
        $msg .= "<div class='alert alert-danger'>❌ Incorrect current password.</div>";
    }
}

// --- 3. FETCH STATS ---
$totalSpent = 0.00;
$totalBeats = 0;
if (isset($conn) && !$conn->connect_error) {
    $statStmt = $conn->prepare("SELECT SUM(price) as total_spent, COUNT(*) as total_count FROM orders WHERE user_id = ?");
    $statStmt->bind_param("i", $current_user_id);
    $statStmt->execute();
    $statRes = $statStmt->get_result();
    if ($statRow = $statRes->fetch_assoc()) {
        $totalSpent = floatval($statRow['total_spent']);
        $totalBeats = intval($statRow['total_count']);
    }
    $statStmt->close();
}

// --- 4. FETCH LIBRARY (SMART FETCH) ---
$purchased_items = [];
if (isset($conn) && !$conn->connect_error) {
    // Select all orders first
    $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while($order = $result->fetch_assoc()) {
        $item = [
            'id' => $order['id'],
            'title' => $order['track_title'],
            'price' => $order['price'],
            'date' => $order['date'],
            'type' => isset($order['product_type']) ? $order['product_type'] : 'beat',
            'license' => $order['license_type'],
            'cover' => 'https://via.placeholder.com/60', // default
            'meta' => ''
        ];

        $p_id = isset($order['product_id']) ? $order['product_id'] : 0;

        // Fetch extra details based on type
        if ($item['type'] === 'kit') {
            // It is a kit
            if ($p_id > 0) {
                $k_res = $conn->query("SELECT cover_image FROM sound_kits WHERE id = $p_id");
                if($k_row = $k_res->fetch_assoc()) $item['cover'] = $k_row['cover_image'];
            }
            $item['license'] = "Sound Kit License";
            $item['meta'] = "Royalty Free • 100% Secure";
        } else {
            // It is a beat
            if ($p_id > 0) {
                $t_res = $conn->query("SELECT cover_image, bpm, track_key FROM tracks WHERE id = $p_id");
            } else {
                // Fallback for legacy orders
                $safe_title = $conn->real_escape_string($order['track_title']);
                $t_res = $conn->query("SELECT cover_image, bpm, track_key FROM tracks WHERE title = '$safe_title'");
            }
            
            if ($t_row = $t_res->fetch_assoc()) {
                $item['cover'] = $t_row['cover_image'];
                $item['meta'] = $t_row['bpm'] . " BPM • Key: " . $t_row['track_key'];
            }
        }
        $purchased_items[] = $item;
    }
    $stmt->close();
}
?>

<style>
    body { background-color: #000; color: white; font-family: 'Poppins', sans-serif; }
    
    .dashboard-container {
        margin-top: 100px;
        padding-top: 40px;
        min-height: 100vh;
        padding-bottom: 80px;
    }

    .dashboard-sidebar {
        background: #0f0f0f;
        border: 1px solid #222;
        border-radius: 12px;
        overflow: hidden;
        position: sticky;
        top: 120px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }

    .user-profile-card {
        padding: 30px 20px;
        text-align: center;
        border-bottom: 1px solid #222;
        background: radial-gradient(circle at top, #1a1a1a, #0a0a0a);
    }

    .user-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #2bee79;
        padding: 3px;
        background: #000;
        margin-bottom: 15px;
        box-shadow: 0 0 20px rgba(43, 238, 121, 0.2);
    }

    .user-name { color: white; font-weight: 700; font-size: 20px; margin-bottom: 5px; }
    .user-email { color: #666; font-size: 13px; margin-bottom: 0; }

    .dash-nav { padding: 15px 0; }
    .dash-nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px 25px;
        color: #888;
        font-weight: 500;
        text-decoration: none;
        transition: 0.3s;
        border-left: 3px solid transparent;
        cursor: pointer;
    }

    .dash-nav-link:hover, .dash-nav-link.active {
        background: #161616;
        color: white;
        border-left-color: #2bee79;
        text-decoration: none;
    }
    
    .dash-nav-link i { width: 20px; text-align: center; font-size: 16px; }

    .dash-header {
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }
    .dash-header h2 { color: white; font-weight: 800; font-size: 32px; margin: 0; letter-spacing: -1px; }
    .dash-header p { color: #666; margin: 0; margin-top: 5px; font-size: 16px; }

    .stat-cards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: #0f0f0f;
        border: 1px solid #222;
        padding: 30px;
        border-radius: 12px;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s;
    }
    .stat-card:hover { transform: translateY(-5px); border-color: #333; }

    .stat-card h3 { color: #666; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; font-weight: 600; }
    .stat-card .value { color: white; font-size: 36px; font-weight: 700; }
    .stat-card .icon-bg {
        position: absolute;
        bottom: -15px;
        right: -15px;
        font-size: 80px;
        color: rgba(255,255,255,0.03);
        transform: rotate(-15deg);
    }

    .content-box {
        background: #0f0f0f;
        border: 1px solid #222;
        border-radius: 12px;
        padding: 30px;
        min-height: 400px;
    }
    
    .section-title { font-size: 20px; font-weight: 700; margin-bottom: 25px; color: white; border-bottom: 1px solid #222; padding-bottom: 15px; }

    .download-item {
        display: flex;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #1a1a1a;
        transition: 0.2s;
        border-radius: 8px;
    }
    .download-item:last-child { border-bottom: none; }
    .download-item:hover { background: #131313; }

    .dl-img {
        width: 65px;
        height: 65px;
        border-radius: 8px;
        object-fit: cover;
        margin-right: 20px;
    }
    
    .dl-info { flex-grow: 1; }
    .dl-title { color: white; font-weight: 700; font-size: 16px; display: block; margin-bottom: 4px; }
    .dl-meta { color: #666; font-size: 13px; display: flex; align-items: center; gap: 10px; }
    .license-pill {
        background: rgba(255,255,255,0.1);
        color: #ccc;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .license-pill.kit-pill {
        background: rgba(43, 238, 121, 0.1);
        color: #2bee79;
        border: 1px solid #2bee79;
    }

    .btn-download {
        background: #fff;
        color: #000;
        border: none;
        padding: 10px 20px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
        transition: 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-download:hover {
        background: #2bee79;
        color: black;
        transform: scale(1.05);
    }
    
    .alert { padding: 15px; border-radius: 8px; margin-bottom: 25px; font-size: 14px; display: flex; align-items: center; gap: 10px; }
    .alert-success { background: rgba(43, 238, 121, 0.1); border: 1px solid #2bee79; color: #2bee79; }
    .alert-danger { background: rgba(255, 71, 87, 0.1); border: 1px solid #ff4757; color: #ff4757; }

    .settings-group { margin-bottom: 20px; }
    .settings-group label { display: block; color: #888; margin-bottom: 8px; font-size: 14px; }
    input.form-control { 
        width: 100%; background: #050505; border: 1px solid #222; 
        color: white; padding: 15px; border-radius: 8px; 
        outline: none; transition: 0.3s; 
    }
    input.form-control:focus { border-color: #2bee79; }
    
    .btn-save {
        background: #2bee79; color: black; font-weight: 800;
        border: none; padding: 15px 40px; border-radius: 8px;
        cursor: pointer; transition: 0.3s; text-transform: uppercase; letter-spacing: 1px;
    }
    .btn-save:hover { background: white; }

    @media (max-width: 991px) {
        .stat-cards-grid { grid-template-columns: 1fr; }
        .dashboard-sidebar { position: static; margin-bottom: 30px; }
        .download-item { flex-direction: column; align-items: flex-start; gap: 15px; }
        .btn-download { width: 100%; justify-content: center; }
    }
</style>

<div class="container dashboard-container">
    <div class="row">
        
        <div class="col-lg-3">
            <div class="dashboard-sidebar">
                <div class="user-profile-card">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user); ?>&background=000&color=2bee79&size=128" alt="User" class="user-avatar">
                    <h3 class="user-name"><?php echo htmlspecialchars($user); ?></h3>
                    <p class="user-email">ID: #<?php echo $current_user_id; ?></p>
                </div>
                <div class="dash-nav">
                    <a onclick="showTab('overview')" class="dash-nav-link active" id="btn-overview"><i class="fa fa-th-large"></i> Overview</a>
                    <a onclick="showTab('library')" class="dash-nav-link" id="btn-library"><i class="fa fa-music"></i> My Library</a>
                    <a onclick="showTab('settings')" class="dash-nav-link" id="btn-settings"><i class="fa fa-cog"></i> Settings</a>
                    <div style="height: 1px; background: #222; margin: 15px 25px;"></div>
                    <a href="logout.php" class="dash-nav-link" style="color: #ff4757;"><i class="fa fa-sign-out"></i> Log Out</a>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            
            <div class="dash-header">
                <div>
                    <h2>Studio Dashboard</h2>
                    <p>Welcome back, create something amazing today.</p>
                </div>
            </div>

            <?php echo $msg; ?>

            <div id="overview-tab">
                <div class="stat-cards-grid">
                    <div class="stat-card">
                        <h3>Purchased Items</h3>
                        <div class="value"><?php echo count($purchased_items); ?></div>
                        <i class="fa fa-shopping-bag icon-bg"></i>
                    </div>
                    <div class="stat-card">
                        <h3>Total Invested</h3>
                        <div class="value">$<?php echo number_format($totalSpent, 2); ?></div>
                        <i class="fa fa-dollar icon-bg"></i>
                    </div>
                    <div class="stat-card">
                        <h3>Loyalty Points</h3>
                        <div class="value"><?php echo floor($totalSpent * 5); ?></div>
                        <i class="fa fa-star icon-bg"></i>
                    </div>
                </div>

                <div class="content-box">
                    <h4 class="section-title">Recent Activity</h4>
                    <?php if(empty($purchased_items)): ?>
                        <div style="text-align:center; padding: 40px; color:#666;">
                            <i class="fa fa-folder-open" style="font-size: 40px; margin-bottom:15px; opacity:0.3;"></i>
                            <p>You haven't purchased anything yet.</p>
                            <a href="tracks.php" style="color:#2bee79; text-decoration:underline;">Browse Beats</a>
                        </div>
                    <?php else: ?>
                        <?php foreach(array_slice($purchased_items, 0, 3) as $item): ?>
                            <div class="download-item">
                                <img src="<?php echo htmlspecialchars($item['cover']); ?>" alt="Cover" class="dl-img" onerror="this.src='https://via.placeholder.com/60'">
                                <div class="dl-info">
                                    <span class="dl-title"><?php echo htmlspecialchars($item['title']); ?></span>
                                    <div class="dl-meta">
                                        <span class="license-pill <?php echo ($item['type'] === 'kit') ? 'kit-pill' : ''; ?>">
                                            <?php echo ucfirst($item['license']); ?>
                                        </span>
                                        <span><?php echo date('M d, Y', strtotime($item['date'])); ?></span>
                                    </div>
                                </div>
                                <div class="dl-actions">
                                    <a href="download.php?order_id=<?php echo $item['id']; ?>" class="btn-download">
                                        <i class="fa fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div id="library-tab" style="display:none;">
                <div class="content-box">
                    <h2 class="section-title">My Collection</h2>
                    <?php if(empty($purchased_items)): ?>
                        <p style="color:#666; text-align:center; padding: 40px;">No beats found. <a href="tracks.php" style="color:#2bee79;">Go shop!</a></p>
                    <?php else: ?>
                        <?php foreach($purchased_items as $item): ?>
                            <div class="download-item">
                                <img src="<?php echo htmlspecialchars($item['cover']); ?>" alt="Cover" class="dl-img" onerror="this.src='https://via.placeholder.com/60'">
                                <div class="dl-info">
                                    <span class="dl-title"><?php echo htmlspecialchars($item['title']); ?></span>
                                    <div class="dl-meta">
                                        <span class="license-pill <?php echo ($item['type'] === 'kit') ? 'kit-pill' : ''; ?>">
                                            <?php echo ucfirst($item['license']); ?>
                                        </span>
                                        <?php if(!empty($item['meta'])): ?>
                                            <span><?php echo $item['meta']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="dl-actions">
                                    <a href="download.php?order_id=<?php echo $item['id']; ?>" class="btn-download">
                                        <i class="fa fa-cloud-download"></i> Download Files
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div id="settings-tab" style="display:none;">
                <div class="content-box">
                    <h4 class="section-title">Account Settings</h4>
                    
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="settings-group">
                                    <label>Username</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user); ?>" disabled style="opacity:0.5; cursor:not-allowed;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="settings-group">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div style="border-top:1px solid #222; margin:25px 0; padding-top:25px;">
                            <h5 style="color:white; margin-bottom:20px; font-size:16px;">Security & Password</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="settings-group">
                                        <label>New Password (Optional)</label>
                                        <input type="password" name="new_password" class="form-control" placeholder="New Password">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="settings-group">
                                        <label>Confirm Password</label>
                                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="background:#151515; padding:20px; border-radius:8px; border:1px solid #333; margin-top:10px;">
                            <div class="settings-group" style="margin-bottom:0;">
                                <label style="color:#2bee79; font-weight:bold;">Current Password (Required)</label>
                                <input type="password" name="current_password" required class="form-control" placeholder="Enter password to confirm changes">
                            </div>
                        </div>

                        <div style="margin-top:30px; text-align:right;">
                            <button type="submit" name="update_profile" class="btn-save">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
function showTab(tabName) {
    document.getElementById('overview-tab').style.display = 'none';
    document.getElementById('settings-tab').style.display = 'none';
    document.getElementById('library-tab').style.display = 'none';
    
    document.getElementById('btn-overview').classList.remove('active');
    document.getElementById('btn-settings').classList.remove('active');
    document.getElementById('btn-library').classList.remove('active');

    if (tabName === 'overview') {
        document.getElementById('overview-tab').style.display = 'block';
        document.getElementById('btn-overview').classList.add('active');
    } else if (tabName === 'settings') {
        document.getElementById('settings-tab').style.display = 'block';
        document.getElementById('btn-settings').classList.add('active');
    } else if (tabName === 'library') {
        document.getElementById('library-tab').style.display = 'block';
        document.getElementById('btn-library').classList.add('active');
    }
}

// Auto-switch tab from URL
const urlParams = new URLSearchParams(window.location.search);
const tab = urlParams.get('tab');
if (tab) {
    showTab(tab);
}
</script>