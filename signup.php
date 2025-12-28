<?php
// ---------- CONFIG & DB CONNECTION ----------
include 'db_connect.php'; // <--- THIS LINE IS CRITICAL

$message = "";
$redirect = false;

/************ HANDLE FORM SUBMISSION  ************/
// Check if the signup form was actually submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if ($username && $email && $password) {
        // 1. Check if User Already Exists
        $check_sql  = "SELECT user_id FROM users WHERE username=? OR email=?";
        // ensure $conn exists before using it
        if (!$conn) { die("Database connection failed."); }

        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "ss", $username, $email);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $message = "⚠️ Username or Email already exists.";
        } else {
            // 2. Create New User
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql  = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hash);

            if (mysqli_stmt_execute($stmt)) {
                // 3. AUTO LOGIN (Session Magic)
                session_start();
                $_SESSION['user_id'] = mysqli_insert_id($conn); // Save the new ID
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                
                // 4. Redirect to Dashboard
                header("Location: user-dashboard.php");
                exit();
            } else {
                $message = "❌ Error: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($check_stmt);
    } else {
        $message = "⚠️ All fields are required.";
    }
}
?>