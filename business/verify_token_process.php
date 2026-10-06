<?php
session_start();
require_once __DIR__ . '/../data/admin/user_modal.php';

if (!isset($_SESSION['reset_email'])) {
    $_SESSION['alert_message'] = '⚠️ Session expired. Please request a new reset link.';
    header("Location: /decorluxe/presentation/forgot_password.php");
    exit;
}

$email = $_SESSION['reset_email'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify'])) {
    $entered_token = trim($_POST["entered_token"]);

    // 1️⃣ Get user reset info from DB
    $user = getUserResetData($email);

    if (!$user) {
        $_SESSION['alert_message'] = '❌ No user found for this email.';
        header("Location: /decorluxe/presentation/forgot_password.php");
        exit;
    }

    // 2️⃣ Check token and expiry
    $valid_token = ($user['reset_token'] === $entered_token);
    $not_expired = (strtotime($user['reset_expires']) > time());

    if ($valid_token && $not_expired) {
        $_SESSION['verified'] = true;
        $_SESSION['id'] = $user['id'];
        $_SESSION['success_message'] = "✅ Email verified successfully. You can now change your password.";
        header("Location: /decorluxe/presentation/change_password.php");
        exit;
    } else {
        $_SESSION['alert_message'] = '❌ Invalid or expired token.';
        header("Location: /decorluxe/presentation/send_reset_email.php");
        exit;
    }
}
?>
