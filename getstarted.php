<?php
// 1. ENABLE ERROR REPORTING
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. CHECK DB CONNECTION FILE
if (!file_exists('db_connect.php')) {
    die("<h2 style='color:red; text-align:center; margin-top:50px;'>CRITICAL ERROR: db_connect.php is missing!</h2>");
}

include 'db_connect.php';

// Check DB
if (isset($conn) && $conn->connect_error) {
    die("<h2 style='color:red; text-align:center;'>DATABASE ERROR: ".$conn->connect_error."</h2>");
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. REDIRECT IF ALREADY LOGGED IN
if (isset($_SESSION['user_id'])) {
    // ADMIN CHECK
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
        header("Location: admin/admin-dashboard.php");
        exit;
    }
    // NORMAL USER CHECK: Send to Dashboard (FIXED)
    header("Location: user-dashboard.php"); 
    exit;
}

$message = "";

// 4. LOGIN LOGIC
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])) {
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    // --- ADMIN BACKDOOR ---
    $admin_email = "admin@kenton.com"; 
    $admin_pass  = "Kenton123!";       

    if ($email === $admin_email && $pass === $admin_pass) {
        $_SESSION['user_id'] = 999999; 
        $_SESSION['username'] = "Kenton (Admin)";
        $_SESSION['email'] = $admin_email;
        $_SESSION['is_admin'] = true; 
        header("Location: admin/admin-dashboard.php");
        exit;
    }

    // NORMAL USER LOGIN
    if ($stmt = $conn->prepare("SELECT user_id, username, email, password_hash FROM users WHERE email = ?")) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            if (password_verify($pass, $row['password_hash'])) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                
                // SUCCESS: Redirect to Dashboard (FIXED)
                header("Location: user-dashboard.php");
                exit;
            } else {
                $message = "<div class='alert error'>❌ Incorrect Password</div>";
            }
        } else {
            $message = "<div class='alert error'>❌ User not found</div>";
        }
        $stmt->close();
    } else {
        die("Query Failed: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | KentonTheProducer</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- GLOBAL RESET --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        
        body {
            background-color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            overflow: hidden; 
        }

        /* --- COLORS --- */
        :root {
            --primary: #2bee79;
            --dark: #0a0a0a;
            --grey: #1a1a1a;
            --text: #ffffff;
        }

        .brand-logo {
            font-size: 2rem;
            font-weight: 800;
            color: white;
            text-transform: uppercase;
            letter-spacing: -1px;
            margin-bottom: 20px;
            text-decoration: none;
            z-index: 100;
        }
        .brand-logo span { color: var(--primary); }

        /* --- AUTH CONTAINER --- */
        .auth-container {
            background-color: var(--grey);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.8), 0 0 20px rgba(43, 238, 121, 0.1);
            position: relative;
            overflow: hidden;
            width: 768px;
            max-width: 100%;
            min-height: 480px;
            border: 1px solid #333;
        }

        /* --- FORMS --- */
        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        form {
            background-color: var(--grey);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 50px;
            height: 100%;
            text-align: center;
        }

        h1 { color: var(--text); margin-bottom: 10px; font-weight: 700; font-size: 2rem; }
        span { font-size: 12px; color: #666; margin-bottom: 15px; }

        .social-icons { margin: 15px 0; }
        .social-icons a {
            border: 1px solid #333;
            border-radius: 20%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin: 0 3px;
            width: 35px;
            height: 35px;
            color: #888;
            text-decoration: none;
            transition: 0.3s;
        }
        .social-icons a:hover { border-color: var(--primary); color: var(--primary); }

        input {
            background-color: #0a0a0a;
            border: 1px solid #333;
            margin: 8px 0;
            padding: 10px 15px;
            font-size: 13px;
            border-radius: 8px;
            width: 100%;
            outline: none;
            color: white;
            transition: 0.3s;
        }
        input:focus { border-color: var(--primary); }

        button {
            background-color: var(--primary);
            color: #000;
            font-size: 12px;
            padding: 10px 40px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 20px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover { background-color: #fff; transform: scale(1.05); }

        .forgot-link { color: #666; font-size: 12px; text-decoration: none; margin: 10px 0; }
        .forgot-link:hover { color: var(--primary); }

        /* --- DESKTOP ANIMATION LOGIC --- */
        .sign-in { left: 0; width: 50%; z-index: 2; }
        .sign-up { left: 0; width: 50%; opacity: 0; z-index: 1; }

        .auth-container.active .sign-in { transform: translateX(100%); }
        .auth-container.active .sign-up { 
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.6s;
        }

        @keyframes show {
            0%, 49.99% { opacity: 0; z-index: 1; }
            50%, 100% { opacity: 1; z-index: 5; }
        }

        /* --- TOGGLE OVERLAY --- */
        .toggle-container {
            position: absolute; top: 0; left: 50%; width: 50%; height: 100%;
            overflow: hidden; transition: all 0.6s ease-in-out;
            border-radius: 150px 0 0 100px; z-index: 1000;
        }

        .auth-container.active .toggle-container { transform: translateX(-100%); border-radius: 0 150px 100px 0; }

        .toggle {
            background: linear-gradient(to right, #1a1a1a, #000);
            border-left: 2px solid var(--primary);
            color: #fff; 
            height: 100%; 
            width: 200%;
            position: relative; 
            left: -100%; 
            transform: translateX(0); 
            transition: all 0.6s ease-in-out;
        }

        .auth-container.active .toggle { transform: translateX(50%); }

        .toggle-panel {
            position: absolute; width: 50%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            flex-direction: column; padding: 0 30px; text-align: center;
            top: 0; transition: all 0.6s ease-in-out;
        }

        .toggle-panel p { color: #ccc; font-size: 13px; line-height: 1.5; margin: 10px 0 20px; }
        
        .toggle-left { transform: translateX(-200%); }
        .auth-container.active .toggle-left { transform: translateX(0); }
        .toggle-right { right: 0; transform: translateX(0); }
        .auth-container.active .toggle-right { transform: translateX(200%); }

        .ghost-btn { background-color: transparent; border-color: #fff; color: #fff; margin-top: 10px; }
        .ghost-btn:hover { background-color: #fff; color: #000; }

        .alert { width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 5px; font-size: 12px; }
        .error { background: rgba(255, 71, 87, 0.1); color: #ff4757; border: 1px solid #ff4757; }

        /* --- MOBILE FIXES --- */
        @media (max-width: 768px) {
            .auth-container { min-height: 580px; width: 90vw; }
            .toggle-container { width: 100%; height: 25%; top: 0; left: 0; border-radius: 0 0 20px 20px; }
            .toggle { width: 100%; height: 100%; transform: translateY(0); border-left: none; border-bottom: 2px solid var(--primary); left: 0; }
            .toggle-panel { width: 100%; height: 100%; padding: 5px; justify-content: center; }
            .toggle-panel h1 { font-size: 1.2rem; margin-bottom: 5px; }
            .toggle-panel p { display: none; }
            .ghost-btn { padding: 8px 25px; margin-top: 5px; font-size: 11px; }
            .toggle-right { top: 0; transform: translateY(0); }
            .toggle-left { top: 0; transform: translateY(-100%); } 
            .form-container { width: 100%; height: 75%; top: 25%; }
            .sign-in { top: 25%; left: 0; width: 100%; }
            .sign-up { top: 25%; left: 0; width: 100%; opacity: 0; }
            .auth-container.active .toggle-container { transform: translateY(300%); border-radius: 20px 20px 0 0; border-top: 2px solid var(--primary); border-bottom: none; }
            .auth-container.active .toggle { transform: translateY(0); }
            .auth-container.active .toggle-left { transform: translateY(0); }
            .auth-container.active .toggle-right { transform: translateY(100%); }
            .auth-container.active .sign-in { transform: translateY(-33.3%); opacity: 0; pointer-events: none;}
            .auth-container.active .sign-up { transform: translateY(-33.3%); opacity: 1; z-index: 5; animation: none; }
        }
    </style>
</head>
<body>

    <a href="index.php" class="brand-logo">Kenton<span>.</span></a>

    <div class="auth-container" id="container">
        
        <div class="form-container sign-up">
            <form method="POST" action="signup.php">
                <h1>Create Account</h1>
                <div class="social-icons">
                    <a href="#"><i class="fa-brands fa-google"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                </div>
                <span>or use your email for registration</span>
                <input type="text" name="username" placeholder="Username" required />
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit" name="register_submit">Sign Up</button>
            </form>
        </div>

        <div class="form-container sign-in">
            <form method="POST">
                <h1>Sign In</h1>
                <?php echo $message; ?>
                <div class="social-icons">
                    <a href="#"><i class="fa-brands fa-google"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                </div>
                <span>or use your email password</span>
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <a href="#" class="forgot-link">Forget Password?</a>
                <button type="submit" name="login_submit">Sign In</button>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="ghost-btn" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Producer!</h1>
                    <p>Register with your personal details to start buying and selling beats.</p>
                    <button class="ghost-btn" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const container = document.getElementById('container');
        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');

        registerBtn.addEventListener('click', () => {
            container.classList.add("active");
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });
    </script>

</body>
</html>