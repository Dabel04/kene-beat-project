<?php
    include 'db_connect.php';
?>

<?php
$message = "";
$redirect = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])) {
    $login_email = trim($_POST['email'] ?? '');
    $login_pass  = $_POST['password'] ?? '';

    if ($login_email && $login_pass) {
        // We select user_id here so we can save it to the session
        $sql  = "SELECT user_id, username, email, password_hash FROM users WHERE email = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $login_email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user   = mysqli_fetch_assoc($result);

        if ($user && password_verify($login_pass, $user['password_hash'])) {
            session_start();
            
            // --- THIS WAS MISSING ---
            $_SESSION['user_id'] = $user['user_id']; // Crucial for isolating data
            $_SESSION['email'] = $user['email'];
            // ------------------------
            
            $_SESSION['username'] = $user['username'];
            
            header("Location: user-dashboard.php"); // Redirect to dashboard
            exit;
        } else {
            $message = "❌ Invalid email or password.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $message = "⚠️ Please enter both email and password.";
    }
}
?>

<?php
include 'signup.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="css/font-awesome.min.css"
    />
    <link rel="stylesheet" href="css/getstarted.css" />
    <title>Login Page | Caged coder</title>
    <?php if ($redirect): ?>
    <!-- Redirect to getstarted.php after 3 seconds -->
    <meta http-equiv="refresh" content="3;url=getstarted.php">
    <?php endif; ?>
  </head>

  <body>
    <div class="container" id="container">
      <div class="form-container sign-up">
        <!-- signup -->
        <form action="signup.php" method="post">
          <h1>Create Account</h1>
          <div class="social-icons">
            <a href="#" class="icon"
              ><i class="fa fa-google"></i
            ></a>
            <a href="#" class="icon"><i class="fa fa-facebook-f"></i></a>
            <a href="#" class="icon"><i class="fa fa-github"></i></a>
            <a href="#" class="icon"
              ><i class="fa fa-linkedin"></i
            ></a>
          </div>
          <span>or use your email for registeration</span>
          <input type="text" name="username" class="form-control" placeholder="username" />
          <input type="email" name="email" class="form-control" placeholder="Email" />
          <input type="password" name="password" class="form-control" placeholder="Password" />
          <button>Sign Up</button>
        </form>
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
      </div>

      <!-- login -->
      <div class="form-container sign-in">
        <form method="post">
          <h1>Sign In</h1>
          <div class="social-icons">
            <a href="#" class="icon"
              ><i class="fa fa-google"></i
            ></a>
            <a href="#" class="icon"><i class="fa fa-facebook-f"></i></a>
            <a href="#" class="icon"><i class="fa fa-github"></i></a>
            <a href="#" class="icon"
              ><i class="fa fa-linkedin"></i
            ></a>
          </div>
          <span>or use your email password</span>
          <input type="email" name="email" placeholder="Email" />
          <input type="password" name="password" placeholder="Password" />
          <a href="#">Forget Your Password?</a>
          <button type="submit" name="login_submit">Sign In</button>
        </form>
      </div>
      <div class="toggle-container">
        <div class="toggle">
          <div class="toggle-panel toggle-left">
            <h1>Welcome Back!</h1>
            <p>Enter your personal details to use all of site features</p>
            <button class="hidden" id="login">Sign In</button>
          </div>
          <div class="toggle-panel toggle-right">
            <h1>Hello, Friend!</h1>
            <p>
              Register with your personal details to use all of site features
            </p>
            <button class="hidden" id="register">Sign Up</button>
          </div>
        </div>
      </div>
    </div>


    <!------------ bottom of signup.php (inside <body>) ---------- -->
    <?php if ($redirect): ?>
    <script>
        setTimeout(function(){
            window.location.href = 'getstarted.php';
        }, 3000);
    </script>
    <?php endif; ?>
    <script src="js/getstarted.js"></script>
  </body>
</html>