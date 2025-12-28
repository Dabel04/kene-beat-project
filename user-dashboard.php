<?php
// 1. Include Header (Session, DB, Navbar)
include 'header.php';

// Force login check
if (!isset($_SESSION['user_id'])) {
    header("Location: getstarted.php");
    exit;
}

$user = isset($_SESSION['username']) ? $_SESSION['username'] : 'Producer';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : 'artist@example.com';
$current_user_id = $_SESSION['user_id'];
$msg = "";

// --- 2. HANDLE SETTINGS FORM SUBMISSION (New Logic) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $new_email = trim($_POST['email']);
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    $current_pass = $_POST['current_password'];
    $uid = $_SESSION['user_id'];

    // Verify Current Password First
    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $stmt->close();

    if ($user_data && password_verify($current_pass, $user_data['password_hash'])) {
        
        // Update Email — only if changed and valid
        if (!empty($new_email) && $new_email !== $_SESSION['email']) {
            // Basic email format check
            if (filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
                $upd = $conn->prepare("UPDATE users SET email = ? WHERE user_id = ?");
                $upd->bind_param("si", $new_email, $uid);
                if ($upd->execute()) {
                    $_SESSION['email'] = $new_email;
                    $email = $new_email;
                    $msg .= "<div style='color:#2bee79; margin-bottom:10px;'>✅ Email updated successfully!</div>";
                } else {
                    $msg .= "<div style='color:#ff4757; margin-bottom:10px;'>⚠️ Failed to update email.</div>";
                }
                $upd->close();
            } else {
                $msg .= "<div style='color:#ff4757; margin-bottom:10px;'>⚠️ Invalid email format.</div>";
            }
        }

        // Update Password
        if (!empty($new_pass)) {
            if ($new_pass === $confirm_pass) {
                if (strlen($new_pass) >= 8) { // Optional: enforce min length
                    $hash = password_hash($new_pass, PASSWORD_DEFAULT);
                    $upd = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
                    $upd->bind_param("si", $hash, $uid);
                    if ($upd->execute()) {
                        $msg .= "<div style='color:#2bee79; margin-bottom:10px;'>✅ Password changed successfully!</div>";
                    } else {
                        $msg .= "<div style='color:#ff4757; margin-bottom:10px;'>⚠️ Failed to update password.</div>";
                    }
                    $upd->close();
                } else {
                    $msg .= "<div style='color:#ff4757; margin-bottom:10px;'>⚠️ Password must be at least 8 characters.</div>";
                }
            } else {
                $msg .= "<div style='color:#ff4757; margin-bottom:10px;'>⚠️ New passwords do not match.</div>";
            }
        }

        if (empty($new_email) && empty($new_pass)) {
            $msg .= "<div style='color:#666; margin-bottom:10px;'>No changes made.</div>";
        }
    } else {
        $msg .= "<div style='color:#ff4757; margin-bottom:10px;'>❌ Incorrect current password. Changes not saved.</div>";
    }
}

// 3. FETCH PURCHASED BEATS (Existing Logic)
$purchased_beats = [];
if (isset($conn) && !$conn->connect_error) {
    $sql = "SELECT 
                orders.date as purchase_date,
                tracks.title, 
                tracks.cover_image, 
                tracks.audio_file, 
                tracks.bpm, 
                tracks.track_key 
            FROM orders 
            JOIN tracks ON orders.track_title = tracks.title 
            WHERE orders.user_id = ? 
            ORDER BY orders.date DESC";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while($row = $result->fetch_assoc()) {
        $purchased_beats[] = $row;
    }
}
?>

<style>
    body { background-color: #000; color: white; }
    
    .dashboard-container {
        margin-top: var(--nav-height);
        padding-top: 50px;
        min-height: 80vh;
        padding-bottom: 50px;
    }

    /* Sidebar Styling */
    .dashboard-sidebar {
        background: #0a0a0a;
        border: 1px solid #222;
        border-radius: 12px;
        overflow: hidden;
        position: sticky;
        top: 100px;
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
        border: 2px solid #2bee79;
        padding: 3px;
        background: #000;
        margin-bottom: 15px;
        box-shadow: 0 0 20px rgba(43, 238, 121, 0.2);
    }

    .user-name { color: white; font-weight: 700; font-size: 20px; margin-bottom: 5px; }
    .user-email { color: #666; font-size: 13px; margin-bottom: 0; }

    .dash-nav { padding: 10px 0; }
    .dash-nav-link {
        display: block;
        padding: 15px 25px;
        color: #888;
        font-weight: 500;
        text-decoration: none;
        transition: 0.3s;
        border-left: 3px solid transparent;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer; /* Added for interactions */
    }

    .dash-nav-link i { width: 20px; text-align: center; }

    .dash-nav-link:hover, .dash-nav-link.active {
        background: #111;
        color: white;
        border-left-color: #2bee79;
        text-decoration: none;
    }

    /* Main Content Styling */
    .dash-header {
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }
    .dash-header h2 { color: white; font-weight: 800; margin: 0; letter-spacing: -1px; }
    .dash-header p { color: #666; margin: 0; margin-top: 5px; }

    .stat-cards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: #0a0a0a;
        border: 1px solid #222;
        padding: 25px;
        border-radius: 12px;
        position: relative;
        overflow: hidden;
    }

    .stat-card h3 { color: #666; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; }
    .stat-card .value { color: white; font-size: 32px; font-weight: 700; }
    .stat-card .icon-bg {
        position: absolute;
        bottom: -10px;
        right: -10px;
        font-size: 80px;
        color: rgba(255,255,255,0.03);
        transform: rotate(-15deg);
    }

    /* Purchases List Styling */
    .downloads-section {
        background: #0a0a0a;
        border: 1px solid #222;
        border-radius: 12px;
        padding: 25px;
    }
    
    .download-item {
        display: flex;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #222;
    }
    .download-item:last-child { border-bottom: none; }

    .dl-img {
        width: 60px;
        height: 60px;
        border-radius: 6px;
        object-fit: cover;
        margin-right: 20px;
    }
    
    .dl-info { flex-grow: 1; }
    .dl-title { color: white; font-weight: 700; font-size: 16px; display: block; }
    .dl-meta { color: #666; font-size: 12px; }

    .btn-download {
        background: #111;
        color: #ccc;
        border: 1px solid #333;
        padding: 8px 15px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        transition: 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-download:hover {
        background: #2bee79;
        color: black;
        border-color: #2bee79;
    }

    @media (max-width: 991px) {
        .stat-cards-grid { grid-template-columns: 1fr; }
        .dashboard-sidebar { position: static; margin-bottom: 30px; }
    }
</style>

<div class="container dashboard-container">
    <div class="row">
        
        <div class="col-lg-3">
            <div class="dashboard-sidebar">
                <div class="user-profile-card">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user); ?>&background=111&color=2bee79" alt="User" class="user-avatar">
                    <h3 class="user-name"><?php echo htmlspecialchars($user); ?></h3>
                    <p class="user-email">User ID: #<?php echo $current_user_id; ?></p>
                </div>
                <div class="dash-nav">
                    <a href="#" onclick="showTab('overview')" class="dash-nav-link active" id="btn-overview"><i class="fa fa-home"></i> Overview</a>
                    <a href="#" onclick="showTab('library')" class="dash-nav-link" id="btn-library"><i class="fa fa-music"></i> My Library</a>
                    <a href="#" onclick="showTab('settings')" class="dash-nav-link" id="btn-settings"><i class="fa fa-cog"></i> Settings</a>
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

            <!-- OVERVIEW TAB -->
            <div id="overview-tab">
                <div class="stat-cards-grid">
                    <div class="stat-card">
                        <h3>Purchased Beats</h3>
                        <div class="value"><?php echo count($purchased_beats); ?></div>
                        <i class="fa fa-music icon-bg"></i>
                    </div>
                    <div class="stat-card">
                        <h3>Total Spent</h3>
                        <div class="value">$0.00</div> 
                        <i class="fa fa-usd icon-bg"></i>
                    </div>
                    <div class="stat-card">
                        <h3>Loyalty Points</h3>
                        <div class="value">150</div>
                        <i class="fa fa-star icon-bg"></i>
                    </div>
                </div>

                <div class="downloads-section">
                    <h4 style="color:white; margin-bottom: 20px; font-weight:700;">My Collection</h4>
                    
                    <?php if(empty($purchased_beats)): ?>
                        <div style="text-align:center; padding: 40px; color:#666;">
                            <i class="fa fa-shopping-cart" style="font-size: 40px; margin-bottom:10px; opacity:0.5;"></i>
                            <p>You haven't purchased any beats yet.</p>
                            <a href="beats.php" style="color:#2bee79; text-decoration:underline;">Browse Beats</a>
                        </div>
                    <?php else: ?>
                        <?php foreach($purchased_beats as $beat): ?>
                            <?php 
                                // Handle Image Path
                                $img = !empty($beat['cover_image']) ? $beat['cover_image'] : 'https://via.placeholder.com/60';
                                // Handle Download Path (Secure)
                                $dl_link = "download.php?track=" . urlencode($beat['title']);
                            ?>
                            <div class="download-item">
                                <img src="<?php echo htmlspecialchars($img); ?>" alt="Cover" class="dl-img" onerror="this.src='https://via.placeholder.com/60'">
                                <div class="dl-info">
                                    <span class="dl-title"><?php echo htmlspecialchars($beat['title']); ?></span>
                                    <span class="dl-meta">
                                        <span style="color:#2bee79">Premium License</span> • 
                                        <?php echo htmlspecialchars($beat['bpm']); ?> BPM • 
                                        Key: <?php echo htmlspecialchars($beat['track_key']); ?> • 
                                        Purchased: <?php echo date('M d, Y', strtotime($beat['purchase_date'])); ?>
                                    </span>
                                </div>
                                <div class="dl-actions">
                                    <a href="<?php echo htmlspecialchars($dl_link); ?>" class="btn-download" download>
                                        <i class="fa fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div> <!-- END OF OVERVIEW TAB -->

            <!-- SETTINGS TAB -->
            <div id="settings-tab" style="display:none; background: #0a0a0a; border: 1px solid #222; border-radius: 12px; padding: 30px;">
                <h4 style="color:white; margin-bottom: 25px; border-bottom:1px solid #222; padding-bottom:15px;">Edit Profile</h4>
                
                <form method="POST">
                    <div style="margin-bottom: 20px;">
                        <label style="color:#888; font-size:14px; display:block; margin-bottom:8px;">Email Address</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required 
                               style="width:100%; background:#111; border:1px solid #333; color:white; padding:12px; border-radius:8px; outline:none;">
                    </div>

                    <div style="border-top:1px solid #222; margin:25px 0; padding-top:25px;">
                        <h5 style="color:#2bee79; margin-bottom:15px; font-size:16px;">Change Password <span style="font-size:12px; color:#666;">(Leave blank to keep current)</span></h5>
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                            <input type="password" name="new_password" placeholder="New Password" 
                                   style="width:100%; background:#111; border:1px solid #333; color:white; padding:12px; border-radius:8px; outline:none;">
                            <input type="password" name="confirm_password" placeholder="Confirm Password" 
                                   style="width:100%; background:#111; border:1px solid #333; color:white; padding:12px; border-radius:8px; outline:none;">
                        </div>
                    </div>

                    <div style="background:#151515; padding:20px; border-radius:8px; border:1px solid #333;">
                        <label style="color:white; font-weight:bold; display:block; margin-bottom:5px;">Current Password <span style="color:#ff4757">*</span></label>
                        <p style="color:#666; font-size:12px; margin-bottom:10px;">Enter your current password to save changes.</p>
                        <input type="password" name="current_password" required placeholder="Enter password to confirm"
                               style="width:100%; background:#000; border:1px solid #444; color:white; padding:12px; border-radius:8px; outline:none;">
                    </div>

                    <div style="margin-top:20px; text-align:right;">
                        <button type="submit" name="update_profile" 
                                style="background:#2bee79; color:#000; font-weight:bold; border:none; padding:12px 30px; border-radius:30px; cursor:pointer;">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- MY LIBRARY TAB -->
            <div id="library-tab" style="display:none;">
                <div style="padding:20px;">
                    <h2 style="color:white; font-size:24px; margin-bottom:20px;">My Library</h2>

                    <?php
                    $stmt = $conn->prepare("
                        SELECT o.track_title, o.price, o.date, o.license_type, t.cover_image 
                        FROM orders o 
                        JOIN tracks t ON o.track_title = t.title 
                        WHERE o.user_id = ? 
                        ORDER BY o.date DESC
                    ");
                    $stmt->bind_param("i", $current_user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    ?>

                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <?php $license = strtolower($row['license_type'] ?? 'basic'); ?>
                            <div style="background:#151515; padding:20px; margin-bottom:20px; border-radius:8px; border:1px solid #333;">
                                <img src="<?php echo $row['cover_image']; ?>" style="width:100%; max-height:200px; object-fit:cover; border-radius:8px; margin-bottom:15px;" alt="Cover">
                                <h3 style="color:white; margin:0 0 10px 0; font-size:20px;"><?php echo htmlspecialchars($row['track_title']); ?></h3>
                                <p style="color:#888; margin:0 0 5px 0;">Purchased: <?php echo date('F j, Y', strtotime($row['date'])); ?></p>
                                <p style="color:#2bee79; margin:0 0 15px 0; font-weight:bold;">
                                    License: <?php echo ucfirst($license); ?> - $<?php echo number_format($row['price'], 2); ?>
                                </p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="color:#888; text-align:center; padding:60px 0; font-size:18px;">
                            No purchases yet. Go cop some beats!
                        </p>
                    <?php endif; ?>

                    <?php $stmt->close(); ?>
                </div>
            </div>
        </div> <!-- END OF col-lg-9 -->
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.getElementById('overview-tab').style.display = 'none';
    document.getElementById('settings-tab').style.display = 'none';
    const libraryTab = document.getElementById('library-tab');
    if (libraryTab) libraryTab.style.display = 'none';

    // Remove active class from all buttons
    document.getElementById('btn-overview').classList.remove('active');
    document.getElementById('btn-settings').classList.remove('active');
    const libraryBtn = document.getElementById('btn-library');
    if (libraryBtn) libraryBtn.classList.remove('active');

    // Show selected tab and activate button
    if (tabName === 'overview') {
        document.getElementById('overview-tab').style.display = 'block';
        document.getElementById('btn-overview').classList.add('active');
    } else if (tabName === 'settings') {
        document.getElementById('settings-tab').style.display = 'block';
        document.getElementById('btn-settings').classList.add('active');
    } else if (tabName === 'library') {
        if (libraryTab) libraryTab.style.display = 'block';
        if (libraryBtn) libraryBtn.classList.add('active');
    }
}

// Load default tab (overview)
showTab('overview');
</script>