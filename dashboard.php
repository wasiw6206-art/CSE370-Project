<?php
session_start();

if (!isset($_SESSION["user_email"])) {
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
        Welcome, <?php echo htmlspecialchars($_SESSION["user_name"]); ?> 👋
    </h2>

    <!-- ✅ FEATURE LINKS -->
    <div class="dashboard-links">

        <h3>Product Management</h3>
        <a href="add_product.php">
            <button class="btn">➕ Add Product</button>
        </a>

        <a href="view_products.php">
            <button class="btn">📦 View Products</button>
        </a>

        <h3>Sales Management</h3>
        <a href="add_sale.php">
            <button class="btn">💰 Record Sale</button>
        </a>

        <a href="view_sales.php">
            <button class="btn">📊 View Sales</button>
        </a>

        <br><br>

        <a href="logout.php">
            <button class="btn logout">Logout</button>
        </a>

    </div>
</div>

</body>
</html>
