<?php
session_start();
include_once '../includes/header.php';
include_once '../includes/db.inc.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    $stmt = $conn->prepare("SELECT id FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token = rand(1000, 9999); // 4-digit numeric token
        $expires = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        $stmt = $conn->prepare("UPDATE Users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expires, $email);
        $stmt->execute();

        $_SESSION['reset_email'] = $email;
      

        header("Location: send_reset_email.php");
        exit();
    } else {
         $_SESSION['alert_message'] = '❌ No account found with this Email';
    }
    $stmt->close();
}
$conn->close(); // Close connection if not exiting

?>

<div class="bg-image h-100"></div>

<div class="container d-flex justify-content-center align-items-center min-vh-100 login">

    <!-- Messages -->
    <div class="d-flex justify-content-center">
        <?php
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success text-center mt-5">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
            unset($_SESSION['success_message']);
        } elseif (isset($_SESSION['alert_message'])) {
            echo '<div class="alert alert-danger text-center mt-5">' . htmlspecialchars($_SESSION['alert_message']) . '</div>';
            unset($_SESSION['alert_message']);
        }
        ?>
    </div>

    <!-- Back to Login -->
    <div class="">
        <div class="d-flex justify-content-start me-3 mb-3">
            <button type="button" class="btn btn-light">
                <a class="text-success d-flex align-items-center justify-content-center" href="/decorluxe/presentation/login.php">
                    <i class="material-icons">arrow_circle_left</i>Back</a>
            </button>
        </div>

        <!-- Forgot Password Form -->
        <div class="row border rounded-corner p-3 bg-white shadow box-area">
            <div class="col-md-12 main-box">
                <div class="row align-items-center">
                    <div class="col-6 logo" style="margin-top: -40px;">
                        <img src="/decorluxe/assets/images/logo-bg.png" alt="logo" style="max-width: 100px;">
                    </div>
                    <div class="header-text mb-4 col-6 text-end">
                        <h3>Forgot Password</h3>
                        <p class="text-danger">Decorluxe PRMS</p>
                    </div>

                    <form method="POST" action="">
                        <div class="input-group mb-4">
                            <input type="email" class="form-control form-control-lg bg-light fs-5 ps-4"
                                placeholder="Enter your email address" name="email" required>
                        </div>
                        <div class="input-group mb-3">
                            <button type="submit" class="btn btn-lg w-100 fs-6 text-white" name="reset" style="background: linear-gradient(to bottom right, #aa8c2c, #2D332D)">Send Reset Code</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



    <footer id="footer">© 2026 Creative Bits</footer>
