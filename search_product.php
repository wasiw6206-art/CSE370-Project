<?php
session_start();
require_once "db.php";

$error = "";
$search = "";
$products = "";

if ($_SESSION["user_email"] == "") {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $search = $_POST["search"];

    if ($search == "") {
        $error = "Please enter a product name to search.";
    } else {
        $sql = "SELECT 
                    Product.productID,
                    Product.productName,
                    Product.sellingPrice,
                    Product.currentQuantity,
                    Product.status,
                    Location.locationName,
                    Location.shelfNumber,
                    Location.description
                FROM Product, Location
                WHERE Product.locationID = Location.locationID
                AND Product.productName LIKE ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute(["%" . $search . "%"]);

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($products == false) {
            $error = "No product found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Product - DreamShop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="search-container">
    <div class="logo">
        <h1>DreamShop</h1>
        <p>Search Product Location</p>
    </div>

    <h2 class="form-title">Search Product</h2>

    <?php if ($error != ""): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-group">
            <label>Product Name</label>
            <input type="text" name="search" placeholder="Enter product name">
        </div>

        <button type="submit" class="btn">Search</button>
    </form>

    <?php if ($products != ""): ?>
        <div class="result-box">
            <h3>Search Results</h3>

            <table>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Location</th>
                    <th>Shelf Number</th>
                    <th>Description</th>
                </tr>

                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product["productID"]; ?></td>
                        <td><?php echo $product["productName"]; ?></td>
                        <td><?php echo $product["sellingPrice"]; ?></td>
                        <td><?php echo $product["currentQuantity"]; ?></td>
                        <td><?php echo $product["status"]; ?></td>
                        <td><?php echo $product["locationName"]; ?></td>
                        <td><?php echo $product["shelfNumber"]; ?></td>
                        <td><?php echo $product["description"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>

    <div class="extra-links">
        <a href="dashboard.php">Back to Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

</body>
</html>