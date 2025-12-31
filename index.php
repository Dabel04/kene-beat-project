<?php
session_start();
// Redirect if logged in
if (isset($_SESSION['user_id'])) {
    header("Location: user-dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>KentonTheProducer</title>
    
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/homepage.css">

    <style>
        /* --- 1. GLOBAL THEME OVERRIDES --- */
        :root {
            --primary-green: #2bee79;
            --bg-black: #000000;
        }

        body {
            background: var(--bg-black) !important;
            color: white;
            overflow-x: hidden; 
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }

        .container1 {
            min-height: 100vh;
            max-width: 1300px;
            margin: auto;
            padding: 1rem 2rem;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* --- 2. NAVIGATION --- */
        .nav1 {
            padding: 1rem 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 100;
            position: relative;
        }

        .brand-logo {
            font-size: 24px;
            font-weight: 800;
            color: white;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: -1px;
        }
        .brand-logo span { color: var(--primary-green); }

        /* Desktop Links */
        .links {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .links div {
            font-size: 0.9rem;
            font-weight: 500;
            color: #888;
            cursor: pointer;
            transition: 0.3s;
        }
        .links div:hover { color: var(--primary-green); }

        /* HAMBURGER ICON (Hidden on Desktop) */
        .hamburger {
            display: none;
            font-size: 1.8rem;
            color: white;
            cursor: pointer;
        }

        /* Dropdowns */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #111;
            border: 1px solid #333;
            min-width: 160px;
            z-index: 10;
            border-radius: 4px;
            top: 100%;
            left: 0;
            margin-top: 10px;
        }
        .dropdown-content a {
            color: #ccc;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 0.9rem;
        }
        .dropdown-content a:hover {
            background-color: #222;
            color: var(--primary-green);
        }
        .dropdown.active .dropdown-content { display: block; }

        /* --- 3. MAIN CONTENT --- */
        .content {
            position: relative;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        /* IMAGE (Untouched) */
        .image {
            width: 100%;
            display: flex;
            justify-content: center;
            animation: shoe-animation 3s infinite;
            z-index: 1; 
            pointer-events: none;
        }

        .image img {
            width: 100%;
            max-width: 800px;
            transform: rotate(-20deg);
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.5));
        }

        @keyframes shoe-animation {
            0% { transform: translateY(0px); }
            70% { transform: translateY(15px); }
            100% { transform: translateY(0px); }
        }

        /* TEXT (Positions: Top) */
        .header {
            position: absolute;
            top: 15%; /* Kept at top as requested */
            left: 50%;
            transform: translateX(-50%);
            z-index: 0; 
            text-align: center;
            width: 100%;
        }

        .header h1 {
            font-size: 15vw; 
            font-weight: 900;
            color: rgba(255, 255, 255, 0.03);
            margin: 0;
            line-height: 0.8;
            white-space: nowrap;
            user-select: none;
        }

        .cta-btn {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-green);
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            position: relative;
            z-index: 10; 
            text-shadow: 0 0 20px rgba(43, 238, 121, 0.4);
            display: inline-block;
            margin-top: -20px; 
        }
        .cta-btn:hover { color: white; transform: scale(1.05); }

        /* --- 4. FOOTER --- */
        .footer {
            padding: 1rem 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid #222;
            margin-top: auto;
        }
        .socials span { margin-right: 20px; color: #666; cursor: pointer; }
        .socials span:hover { color: var(--primary-green); }
        .slides { font-size: 1.5rem; font-weight: 700; color: white; }
        .slides span { font-size: 1rem; color: #666; font-weight: 400; }

        /* --- 5. MOBILE MENU & RESPONSIVENESS --- */
        @media (max-width: 991px) {
            .nav1 { padding: 1rem; }
            .header { top: 20%; }
            .header h1 { font-size: 18vw; }
            .image img { max-width: 90%; }
            
            /* SHOW HAMBURGER */
            .hamburger { display: block; }

            /* HIDE NORMAL LINKS & STYLE DROPDOWN */
            .links {
                display: none; /* Hidden by default */
                position: absolute;
                top: 70px;
                right: 0;
                background: #111;
                border: 1px solid #333;
                border-radius: 8px;
                flex-direction: column;
                padding: 20px;
                width: 200px;
                align-items: flex-start;
                gap: 15px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.8);
            }

            .links.active { display: flex; } /* Show when toggled */
            
            /* Hide decorative text in dropdowns on mobile for cleaner look */
            .links .dropdown span i { display: none; }
            .links div { width: 100%; border-bottom: 1px solid #222; padding-bottom: 10px; }
            .links div:last-child { border-bottom: none; }
        }

        @media (max-width: 480px) {
            .container1 { padding: 10px; }
            .image img { max-width: 100%; margin-top: 50px; }
            .header { top: 15%; }
            .header h1 { font-size: 20vw; white-space: normal; line-height: 1; }
            .footer { flex-direction: column; gap: 15px; }
        }
    </style>
  </head>
  <body>
    <div class="container1">
      
      <div class="nav1">
        <a href="#" class="brand-logo">Kenton<span>.</span></a>
        
        <div class="hamburger" id="hamburger-btn">
            <i class="ri-menu-3-line"></i>
        </div>

        <div class="links" id="nav-links">
          <div><span><i class="ri-search-line"></i></span></div>

          <div class="dropdown">
            ENG <span><i class="ri-arrow-drop-down-fill"></i></span>
            <div class="dropdown-content">
              <a href="#">English</a>
              <a href="#">Spanish</a>
            </div>
          </div>

          <div class="dropdown">
            USD <span><i class="ri-arrow-drop-down-fill"></i></span>
            <div class="dropdown-content">
              <a href="#">USD ($)</a>
              <a href="#">EUR (€)</a>
            </div>
          </div>

          <div class="dropdown">
            ACCOUNT <span><i class="ri-arrow-drop-down-fill"></i></span>
            <div class="dropdown-content">
              <a href="getstarted.php">Login</a>
              <a href="tracks.php">Browse Beats</a>
            </div>
          </div>

          <div class="shopping__cart">
            <a href="tracks.php" style="color:inherit;"><i class="fa fa-shopping-cart"></i> Cart</a>
          </div>
        </div>
      </div>

      <div class="content">
        <div class="header">
            <h1>BEST BEATS</h1>
            <a href="tracks.php" class="cta-btn">BUY NOW <i class="ri-arrow-right-line"></i></a>
        </div>

        <div class="image">
            <img src="image/headphones1.png" alt="Headphones" />
        </div>
      </div>

      <div class="footer">
        <div class="socials">
          <span>Facebook</span>
          <span>Twitter</span>
          <span>Google</span>
        </div>
        <div class="slides">01<span>/01</span></div>
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // 1. HAMBURGER LOGIC
        const hamburger = document.getElementById('hamburger-btn');
        const navLinks = document.getElementById('nav-links');

        hamburger.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent closing immediately
            navLinks.classList.toggle('active');
            
            // Toggle icon
            const icon = hamburger.querySelector('i');
            if(navLinks.classList.contains('active')) {
                icon.className = 'ri-close-line'; // Change to X
            } else {
                icon.className = 'ri-menu-3-line'; // Back to menu
            }
        });

        // 2. DROPDOWN LOGIC
        const dropdowns = document.querySelectorAll('.dropdown');
        dropdowns.forEach(dropdown => {
          dropdown.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdowns.forEach(other => { if (other !== dropdown) other.classList.remove('active'); });
            this.classList.toggle('active');
          });
        });

        // 3. CLOSE ALL ON CLICK OUTSIDE
        document.addEventListener('click', (e) => {
            if (!navLinks.contains(e.target) && !hamburger.contains(e.target)) {
                navLinks.classList.remove('active');
                hamburger.querySelector('i').className = 'ri-menu-3-line';
            }
            dropdowns.forEach(d => d.classList.remove('active'));
        });
      });
    </script>
  </body>
</html>