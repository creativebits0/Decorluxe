<?php
session_start();
include_once '../includes/header.php';
include_once '../includes/db.inc.php';

if (!isset($_SESSION['reset_email'])) {
    die("Missing session data.");
}

$email = $_SESSION['reset_email'];


// Get the 4-digit token from DB
$stmt = $conn->prepare("SELECT id,reset_token FROM Users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$token = $row['reset_token'];
$id = $row['id'];
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify'])) {
?>
    <script>
        alert('email' + $email);
    </script>
<?php
    $entered_token = $_POST["entered_token"];

    $stmt = $conn->prepare("SELECT reset_token, reset_expires FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $user["reset_token"] === $entered_token && strtotime($user["reset_expires"]) > time()) {
        $_SESSION['verified'] = true;
        $_SESSION['id'] = $id;
   $_SESSION['success_message'] = "✅ Email verified successfully. Change your Password Now ";
        header("Location: change_password.php");
        exit();
    } else {
        $_SESSION['alert_message'] = '❌ Invalid or Expired Token';
        echo "<script>
                        setTimeout(function() { window.location.href = '/decorluxe/presentation/login.php'; }, 100);
                        </script>";
        exit();
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>

<div class="bg-image h-100 "></div>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        emailjs.init("Y-YtvnP39L8fZEzfd");
        emailjs.send("service_73brger", "template_d8msxod", {
            to_email: "<?= $email ?>",
            token_code: "<?= $token ?>"
        }).then(function() {
            alert("Email sent to " + $email);
        }, function(error) {
            alert("Failed to send email. Error: " + JSON.stringify(error));
        });
    });
</script>


<div class="d-flex justify-content-center align-items-center min-vh-100 text-center">
    <div class="shadow-lg p-5 pb-2 bg-white rounded col-4">
        <h2 class="text-center mb-3">Verify Token</h2>
        <hr>
        <div>
            <p>A 4-digit reset token has been sent to: </p>
            <strong><?= htmlspecialchars($email) ?></strong>

            <form method="post" action="/decorluxe/presentation/send_reset_email.php">
                <label>Enter the 4-digit token:</label>

                <input type="text" name="entered_token" maxlength="4" required>
                <footer class="mt-5">
                    <button type="submit" class="btn text-white" name="verify" style="background-color:#4CAF50;">Verify Token</button>
                </footer>
            </form>
        </div>
    </div>
</div>