<?php
session_start();
require_once __DIR__ . '/../data/admin/user_modal.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $entered_password = $_POST["password"];

    // Fetch user data from database
    $user = getUserByUsername($username);

    if ($user && password_verify($entered_password, $user["password"])) {
        if ($user["status"] == '1') {

            // Store info in session
            $_SESSION['id'] = $user["id"];
            $_SESSION['role'] = $user["role"];
            $_SESSION['username'] = $user["username"];
            $_SESSION['is_password_changed'] = $user["is_password_changed"];

            // If user must change password first
            if (!$user["is_password_changed"]) {
                $_SESSION['alert_message'] = '🔒 Please change your password.';
                header("Location: /decorluxe/presentation/change_password.php");
                exit;
            }

            // Redirect by role
            $_SESSION['success_message'] = "✅ Welcome to Dashboard";
            header("Location: /decorluxe/presentation/{$user['role']}/default_dashboard.php");
            exit;
        } else {
            $_SESSION['alert_message'] = '🔒 Unauthorized login. Contact admin.';
            header("Location: /decorluxe/presentation/login.php");
            exit;
        }
    } else {
        $_SESSION['alert_message'] = '❌ Invalid username or password.';
        header("Location: /decorluxe/presentation/login.php");
        exit;
    }
}
?>
