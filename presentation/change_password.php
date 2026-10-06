<?php
include_once '../includes/header.php';
?>

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
<div class="container  min-vh-100">
    <!-- Change password form -->
    <div class="d-flex align-items-center justify-content-center m-5">

        <!-- Back to login -->
        <div class="d-flex align-items-center me-3">
            <button type="button" class="btn" style="background-color:#BBC2CC;">
                <a class="text-success d-flex align-items-center justify-content-center" href="/decorluxe/presentation/login.php">
                    <i class="material-icons">arrow_circle_left</i>Back</a>
            </button>
        </div>

        <div class="shadow-lg p-5 bg-white rounded col-4">
            <h2 class="text-center mb-3">Change Password</h2>
            <hr>

            <form method="POST" action="/decorluxe/business/change_password_process.php">
                <div class="mb-4">
                    <label for="newPassword" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="newPassword" name="new_password" required>
                </div>

                <div class="mb-4">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                </div>

                <footer class="col-12 d-flex justify-content-center" style="background: linear-gradient(to bottom right, #aa8c2c, #2D332D)">
                    <button type="reset" class="btn me-2 text-white" style="background-color:#BBC2CC;">Cancel</button>
                    <button type="submit" class="btn text-white" style="background-color:#4CAF50;">Submit</button>
                </footer>
            </form>
        </div>
    </div>
</div>
<?php include_once '../includes/footer.php'; ?>