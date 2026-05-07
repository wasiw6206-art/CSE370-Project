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
                    productNumber,
                    productName,
                    price,
                    type,
                    produceDate,
                    expiryDate,
                    locationName,
                    shelfNumber
                FROM ProductList
                WHERE productName LIKE ?";

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
    <title>Product Shelf - DreamShop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once "header.php"; ?>

<div class="search-container">
    <div class="logo">
        <p>Search Product Location</p>
    </div>

    <h2 class="form-title">Product Shelf</h2>

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
                    <th>Product Number</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Type</th>
                    <th>Produce Date</th>
                    <th>Expiry Date</th>
                    <th>Location</th>
                    <th>Shelf Number</th>
                </tr>

                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product["productNumber"]; ?></td>
                        <td><?php echo $product["productName"]; ?></td>
                        <td><?php echo $product["price"]; ?></td>
                        <td><?php echo $product["type"]; ?></td>
                        <td><?php echo $product["produceDate"]; ?></td>
                        <td><?php echo $product["expiryDate"]; ?></td>
                        <td><?php echo $product["locationName"]; ?></td>
                        <td><?php echo $product["shelfNumber"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>