<?php
session_start();
require_once "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        $error = "Email and password are required.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["userID"] = $user["userID"];
            $_SESSION["user_name"] = $user["fullName"];
            $_SESSION["user_email"] = $user["email"];

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DreamShop Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-card">
    <div class="logo">
        <h1>DreamShop</h1>
        <p>Store Management System</p>
    </div>

    <h2 class="form-title">Login</h2>

    <?php if (!empty($error)): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="Enter your email" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="btn">Login</button>
    </form>

    <div class="extra-links">
        <a href="signin.php">Create Account</a>
        <a href="forgot_password.php">Forgot Password?</a>
    </div>
</div>

</body>
</html>