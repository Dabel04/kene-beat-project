<?php
// 1. Start Session (to access the current data)
session_start();

// 2. Clear all session variables (username, user_id, email, etc.)
$_SESSION = array();

// 3. Destroy the session cookie (optional but good security practice)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Destroy the actual session
session_destroy();

// 5. Redirect the user back to the "Get Started" / Login page
header("Location: getstarted.php");
exit;
?>