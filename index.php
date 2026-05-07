<?php
session_start();
require_once "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if ($email == "" || $password == "") {
        $error = "Email and password are required.";
    } else {
        $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email, $password]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION["userID"] = $user["userID"];
            $_SESSION["user_name"] = $user["fullName"];
            $_SESSION["user_email"] = $user["email"];

            header("Location: search_product.php");
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

    <?php if ($error != ""): ?>
        <div class="message error"><?php echo $error; ?></div>
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