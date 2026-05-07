<?php
session_start();
require_once "db.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($name == "" || $email == "" || $phone == "" || $password == "" || $confirm_password == "") {
        $error = "All fields are required.";
    } elseif ($password != $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $sql = "SELECT CHAR_LENGTH(?) AS password_length";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$password]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row["password_length"] < 6) {
            $error = "Password must be at least 6 characters.";
        } else {
            $sql = "SELECT * FROM users WHERE email = ?";
            $check = $conn->prepare($sql);
            $check->execute([$email]);

            $existing_user = $check->fetch(PDO::FETCH_ASSOC);

            if ($existing_user) {
                $error = "This email is already registered.";
            } else {
                $sql = "INSERT INTO users (fullName, email, phone, password) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$name, $email, $phone, $password]);

                $success = "Account created successfully. You can now login.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DreamShop Sign In</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-card">
    <div class="logo">
        <h1>DreamShop</h1>
        <p>Create your store account</p>
    </div>

    <h2 class="form-title">Sign In</h2>

    <?php if ($error != ""): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success != ""): ?>
        <div class="message success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-group">
            <label>Full Name</label>
            <input type="text" name="name" placeholder="Enter your full name" required>
        </div>

        <div class="input-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="Enter your email" required>
        </div>

        <div class="input-group">
            <label>Phone Number</label>
            <input type="text" name="phone" placeholder="Enter your phone number" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Create password" required>
        </div>

        <div class="input-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm password" required>
        </div>

        <button type="submit" class="btn btn-green">Create Account</button>
    </form>

    <p class="auth-link">
        Already have an account?
        <a href="index.php">Login</a>
    </p>
</div>

</body>
</html>