<?php
session_start();
require_once __DIR__ . '/../data/admin/user_modal.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    // 1️⃣ Check if the email exists
    $user = getUserByEmail($email);
    if (!$user) {
        $_SESSION['alert_message'] = '❌ No account found with this email.';
        header("Location: /decorluxe/presentation/forgot_password.php");
        exit;
    }

    // 2️⃣ Create a secure 6-digit token (stronger than rand())
    $token = random_int(100000, 999999);
    $expires = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    // 3️⃣ Save token in the database
    $saved = saveResetToken($email, $token, $expires);

    if ($saved) {
        // Store the email in session for next step
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_token'] = $token;

        // 4️⃣ Redirect to the page that sends the reset email
        header("Location: /decorluxe/presentation/send_reset_email.php");
        exit;
    } else {
        $_SESSION['alert_message'] = '❌ Error saving reset token. Try again.';
        header("Location: /decorluxe/presentation/forgot_password.php");
        exit;
    }
}
?>
