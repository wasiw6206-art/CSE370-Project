<?php
session_start();
require_once "db.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];

    if ($email == "") {
        $error = "Email address is required.";
    } else {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION["reset_email"] = $email;
            $success = "Email verified. You can now reset your password.";
            header("Refresh: 2; URL=reset_password.php");
        } else {
            $error = "No account found with this email address.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DreamShop Forgot Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-card">
    <div class="logo">
        <h1>DreamShop</h1>
        <p>Recover your account</p>
    </div>

    <h2 class="form-title">Forgot Password</h2>

    <?php if ($error != ""): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success != ""): ?>
        <div class="message success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-group">
            <label>Registered Email Address</label>
            <input type="email" name="email" placeholder="Enter your registered email" required>
        </div>

        <button type="submit" class="btn">Verify Email</button>
    </form>

    <p class="auth-link">
        Remember your password?
        <a href="index.php">Login</a>
    </p>
</div>

</body>
</html>