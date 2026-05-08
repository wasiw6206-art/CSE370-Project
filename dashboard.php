<?php
session_start();

if (!isset($_SESSION["user_email"]) || $_SESSION["user_email"] == "") {
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

<?php require_once "header.php"; ?>

<div class="auth-card">
    <div class="logo">
        <h1>DreamShop</h1>
        <p>Store Management System</p>
    </div>

    <h2 class="form-title">
        Welcome, <?php echo $_SESSION["user_name"]; ?>
    </h2>

    <a href="search_product.php">
        <button class="btn">Go to Product Shelf</button>
    </a>
</div>

</body>
</html>