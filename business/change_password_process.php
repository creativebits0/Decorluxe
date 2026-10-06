<?php
session_start();
require_once __DIR__ . '/../data/admin/user_modal.php';

if (!isset($_SESSION['id'])) {
    header("Location: /decorluxe/presentation/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['id'];
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    //  Check passwords match
    if ($new_password !== $confirm_password) {
        $_SESSION['alert_message'] = '❌ Passwords do not match!';
        header("Location: /decorluxe/presentation/change_password.php");
        exit;
    }

    //  Fetch current password from DB
    $current_hashed_password = getUserPasswordById($user_id);

    if (!$current_hashed_password) {
        $_SESSION['alert_message'] = '❌ User not found.';
        header("Location: /decorluxe/presentation/change_password.php");
        exit;
    }

    // Prevent using the same password
    if (password_verify($new_password, $current_hashed_password)) {
        $_SESSION['alert_message'] = '❌ New password must be different from the old one.';
        header("Location: /decorluxe/presentation/change_password.php");
        exit;
    }

    //  Hash and update the new password
    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $update_success = updateUserPassword($user_id, $new_hashed_password);

    if ($update_success) {
        $_SESSION['success_message'] = "✅ Password changed successfully. Please log in again.";
        // Clear login session to force re-login
        unset($_SESSION['id']);
        session_regenerate_id(true);
        header("Location: /decorluxe/presentation/login.php?password_updated=true");
        exit;
    } else {
        $_SESSION['alert_message'] = '❌ Error updating password. Please try again.';
        header("Location: /decorluxe/presentation/change_password.php");
        exit;
    }
}
?>
