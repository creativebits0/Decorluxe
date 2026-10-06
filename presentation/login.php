<?php
session_start();
include_once '../includes/header.php';
?>

<style>
    .bg-image{
        background-image: url('../assets/images/index-img.jpeg')
    }
</style>

<div class="bg-image h-100"></div>

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

<div class="container d-flex justify-content-center align-items-center min-vh-100 login">
    <div class="row border rounded-corner p-3 bg-white shadow box-area">
        <div class="col-md-12 main-box">
            <div class="row align-items-center">
                <div class="col-6 logo" style="margin-top: -40px;">
                    <img src="/decorluxe/assets/images/logo-bg.png" alt="logo" style="max-width: 100px;">
                </div>
                <div class="header-text mb-4 col-6 text-end">
                    <h3>Welcome</h3>
                    <p class="text-danger">Decorluxe PRMS</p>
                </div>

                <form method="post" action="/decorluxe/business/login_process.php">
                    <div class="input-group mb-4">
                        <input type="text" class="form-control form-control-lg bg-light fs-5 ps-4"
                               placeholder="Username" name="username" required>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control form-control-lg bg-light fs-5 ps-4"
                               placeholder="Password" name="password" id="password" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                            <span id="toggleIcon"><i class="material-icons">visibility</i></span>
                        </button>
                    </div>

                    <div class="input-group mb-5 d-flex justify-content-between">
                        <small><a href="/decorluxe/presentation/forgot_password.php" class="text-primary">Forgot Password?</a></small>
                    </div>

                    <div class="input-group">
                        <button class="btn btn-lg  w-100 fs-6 text-white" type="submit" style="background: linear-gradient(to bottom right, #aa8c2c, #2D332D);">Login</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>

<script>
function togglePassword() {
    const passwordField = document.getElementById("password");
    const toggleIcon = document.getElementById("toggleIcon");
    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.innerHTML = '<i class="material-icons">visibility_off</i>';
    } else {
        passwordField.type = "password";
        toggleIcon.innerHTML = '<i class="material-icons">visibility</i>';
    }
}
</script>
