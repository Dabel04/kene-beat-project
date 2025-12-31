<?php
// 1. Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. DATABASE CONNECTION (THE FIX)
// This uses the absolute path so it works from ANY folder (admin, user, etc.)
include_once __DIR__ . '/db_connect.php';

// Debug: Stop everything if connection failed
if (!isset($conn)) {
    die("Error: db_connect.php was loaded, but \$conn variable is missing.");
}

// 3. AUTHENTICATION CHECK
// Redirect if not logged in (and handle folder paths correctly)
if (!isset($_SESSION['user_id'])) {
    $current_script = basename($_SERVER['PHP_SELF']);
    // Pages that don't require login
    $public_pages = ['getstarted.php', 'login.php', 'signup.php', 'index.php', 'home.php', 'about.php', 'tracks.php', 'contact.php'];
    
    if (!in_array($current_script, $public_pages)) {
        // If we are in the admin folder, we need to go UP one level to find getstarted.php
        $redirect_path = file_exists('getstarted.php') ? 'getstarted.php' : '../getstarted.php';
        header("Location: " . $redirect_path);
        exit;
    }
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KentonTheProducer</title>
  
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/homepage.css">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  
  <style>
    /* --- VARIABLES --- */
    :root {
        --primary-color: #2bee79;
        --bg-dark: #0a0a0a;
        --nav-height: 80px;
    }

    /* --- GLOBAL LAYOUT FIXES --- */
    body { 
        background-color: #000;
        margin: 0;
        padding: 0;
        font-family: 'Poppins', sans-serif; /* Ensuring consistent font */
    }

    /* Fix for pages with Hero sections (like Home) */
    .hero {
        padding-top: var(--nav-height); 
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    
    .hero .content { margin-top: 40px; }

    /* Fix for pages WITHOUT a hero (like tracks.php) to prevent content hiding behind nav */
    body > .container, body > .section-box, .main-content-spacer {
        margin-top: var(--nav-height);
    }

    /* --- HEADER STYLES --- */
    .main-header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: var(--nav-height);
        background: rgba(10, 10, 10, 0.95);
        backdrop-filter: blur(15px);
        border-bottom: 1px solid rgba(255,255,255,0.05);
        z-index: 1000;
        display: flex;
        align-items: center;
    }

    .nav-container {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .brand-logo {
        font-size: 24px;
        font-weight: 800;
        color: white;
        text-decoration: none;
        letter-spacing: -1px;
        text-transform: uppercase;
        font-family: sans-serif;
    }
    .brand-logo span { color: var(--primary-color); }
    .brand-logo:hover { color: white; text-decoration: none; }

    .nav-links {
        display: flex;
        gap: 30px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-links a {
        color: #888;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
    }

    .nav-links a:hover, .nav-links a.active { color: white; }

    .nav-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .icon-btn {
        background: none;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        position: relative;
        transition: 0.3s;
        padding: 5px;
    }

    .icon-btn:hover { color: var(--primary-color); }

    .cart-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: var(--primary-color);
        color: black;
        font-size: 10px;
        font-weight: bold;
        height: 16px;
        width: 16px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Profile Dropdown */
    .profile-wrapper { position: relative; }
    .profile-dropdown {
        position: absolute;
        top: 150%;
        right: 0;
        width: 200px;
        background: #1a1a1a;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 10px 0;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: 0.3s;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        z-index: 1001;
    }
    
    .profile-wrapper:hover .profile-dropdown,
    .profile-wrapper:focus-within .profile-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .profile-dropdown a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 20px;
        color: #ccc;
        font-size: 14px;
        text-decoration: none;
        transition: 0.2s;
    }

    .profile-dropdown a:hover {
        background: rgba(255,255,255,0.05);
        color: var(--primary-color);
    }

    .mobile-toggle { display: none; font-size: 24px; color: white; cursor: pointer; margin-left: 10px;}

    /* --- RESPONSIVE --- */
    @media (max-width: 991px) {
        .nav-links {
            position: fixed;
            top: var(--nav-height);
            left: 0;
            width: 100%;
            height: auto;
            background: #0a0a0a;
            flex-direction: column;
            align-items: center;
            padding: 30px 0;
            gap: 25px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            transform: translateY(-150%);
            transition: 0.4s ease;
            z-index: 998;
        }

        .nav-links.active { transform: translateY(0); }
        .mobile-toggle { display: block; }
        .profile-dropdown { right: -50px; }
    }
  </style>
</head>
<body>

<header class="main-header">
    <div class="nav-container">
        <a href="home.php" class="brand-logo">Kenton<span>.</span></a>

        <ul class="nav-links" id="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="tracks.php">Tracks</a></li>
            <li><a href="kits.php">Sound Kits</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>

        <div class="nav-actions">
            <button class="icon-btn" id="open-cart-btn">
                <i class="fa fa-shopping-cart"></i>
                <span class="cart-badge" id="cart-count">0</span>
            </button>

            <div class="profile-wrapper">
                <button class="icon-btn">
                    <i class="fa fa-user-circle"></i>
                </button>
                <div class="profile-dropdown">
                    <div style="padding: 10px 20px; border-bottom:1px solid rgba(255,255,255,0.1); margin-bottom:5px;">
                        <small style="color:#666;">Signed in as</small><br>
                        <strong style="color:white;"><?php echo htmlspecialchars($username); ?></strong>
                    </div>
                    <a href="user-dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a>
                    <a href="user-dashboard.php?tab=settings"><i class="fa fa-cog"></i> Settings</a>
                    <a href="logout.php" style="color: #ff4757;"><i class="fa fa-sign-out"></i> Log Out</a>
                </div>
            </div>

            <div class="mobile-toggle" id="mobile-menu-btn">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </div>
</header>