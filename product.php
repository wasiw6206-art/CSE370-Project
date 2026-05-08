<?php
session_start();
require_once "db.php";

$message = "";
$error = "";
$products = "";

if ($_SESSION["user_email"] == "") {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productName = $_POST["productName"];
    $productNumber = $_POST["productNumber"];
    $price = $_POST["price"];
    $type = $_POST["type"];
    $produceDate = $_POST["produceDate"];
    $expiryDate = $_POST["expiryDate"];
    $locationName = $_POST["locationName"];
    $shelfNumber = $_POST["shelfNumber"];

    if ($produceDate == "") {
        $produceDate = NULL;
    }

    if ($expiryDate == "") {
        $expiryDate = NULL;
    }

    if ($productName == "" || $productNumber == "" || $price == "" || $type == "" || $locationName == "" || $shelfNumber == "") {
        $error = "All fields are required except Produce Date and Expiry Date.";
    } else {
        $sql = "SELECT * FROM productlist WHERE productNumber = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$productNumber]);

        $existingProduct = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingProduct) {
            $error = "This product number already exists.";
        } else {
            $sql = "INSERT INTO productlist(productNumber, productName, price, type, produceDate, expiryDate, locationName, shelfNumber)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([$productNumber, $productName, $price, $type, $produceDate, $expiryDate, $locationName, $shelfNumber]);

            $message = "Product added successfully.";
        }
    }
}

$sql = "SELECT * FROM productlist";
$stmt = $conn->prepare($sql);
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product List - DreamShop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once "header.php"; ?>

<div class="search-container">
    <div class="logo">
        <h1>DreamShop</h1>
        <p>Product List Management</p>
    </div>

    <h2 class="form-title">Add Product</h2>

    <?php if ($message != ""): ?>
        <div class="message success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if ($error != ""): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-group">
            <label>Product Name</label>
            <input type="text" name="productName" placeholder="Enter product name">
        </div>

        <div class="input-group">
            <label>Product Number</label>
            <input type="number" name="productNumber" placeholder="Enter product number">
        </div>

        <div class="input-group">
            <label>Price</label>
            <input type="number" name="price" placeholder="Enter product price">
        </div>

        <div class="input-group">
            <label>Type</label>
            <input type="text" name="type" placeholder="Enter product type">
        </div>

        <div class="input-group">
            <label>Produce Date Optional</label>
            <input type="date" name="produceDate">
        </div>

        <div class="input-group">
            <label>Expiry Date Optional</label>
            <input type="date" name="expiryDate">
        </div>

        <div class="input-group">
            <label>Location</label>
            <input type="text" name="locationName" placeholder="Enter product location">
        </div>

        <div class="input-group">
            <label>Shelf Number</label>
            <input type="text" name="shelfNumber" placeholder="Enter shelf number">
        </div>

        <button type="submit" class="btn btn-green">Add Product</button>
    </form>

    <?php if ($products != ""): ?>
        <div class="result-box">
            <h3>Product List</h3>

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