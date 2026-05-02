<?php
session_start();
require_once "db.php";

$error = "";
$success = "";

if ($_SESSION["reset_email"] == "") {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    if ($new_password == "" || $confirm_password == "") {
        $error = "All fields are required.";
    } elseif ($new_password != $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $sql = "SELECT CHAR_LENGTH(?) AS password_length";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$new_password]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row["password_length"] < 6) {
            $error = "Password must be at least 6 characters.";
        } else {
            $email = $_SESSION["reset_email"];

            $sql = "UPDATE users SET password = ? WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$new_password, $email]);

            $_SESSION["reset_email"] = "";

            $success = "Password reset successfully. You can now login.";
            header("Refresh: 2; URL=index.php");
        }
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

    <?php if ($error != ""): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success != ""): ?>
        <div class="message success"><?php echo $success; ?></div>
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