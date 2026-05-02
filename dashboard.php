<?php
session_start();

if ($_SESSION["user_email"] == "") {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DreamShop Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-card">
    <div class="logo">
        <h1>DreamShop</h1>
        <p>Store Management System</p>
    </div>

    <h2 class="form-title">
        Welcome, <?php echo $_SESSION["user_name"]; ?>
    </h2>

    <a href="logout.php">
        <button class="btn">Logout</button>
    </a>
</div>

</body>
</html>