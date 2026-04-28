<?php
session_start();
require_once "db.php";

$error = "";
$success = "";

if (!isset($_SESSION["reset_email"])) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_password = trim($_POST["new_password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if (empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $email = $_SESSION["reset_email"];
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashed_password, $email]);

        unset($_SESSION["reset_email"]);

        $success = "Password reset successfully. You can now login.";
        header("Refresh: 2; URL=index.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DreamShop Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-card">
    <div class="logo">
        <h1>DreamShop</h1>
        <p>Create a new password</p>
    </div>

    <h2 class="form-title">Reset Password</h2>

    <?php if (!empty($error)): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="message success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-group">
            <label>New Password</label>
            <input type="password" name="new_password" placeholder="Enter new password" required>
        </div>

        <div class="input-group">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm new password" required>
        </div>

        <button type="submit" class="btn btn-green">Reset Password</button>
    </form>

    <p class="auth-link">
        Back to
        <a href="index.php">Login</a>
    </p>
</div>

</body>
</html>