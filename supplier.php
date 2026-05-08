<?php
session_start();
require_once "db.php";

$message = "";
$error = "";
$suppliers = "";
$products = "";

if ($_SESSION["user_email"] == "") {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $supplierName = $_POST["supplierName"];
    $phoneNumber = $_POST["phoneNumber"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $productNumber = $_POST["productNumber"];
    $supplyPrice = $_POST["supplyPrice"];
    $supplyDate = $_POST["supplyDate"];

    if ($supplierName == "" || $phoneNumber == "" || $email == "" || $address == "" || $productNumber == "" || $supplyPrice == "" || $supplyDate == "") {
        $error = "All fields are required.";
    } else {
<<<<<<< HEAD
        $sql = "SELECT * FROM productlist WHERE productNumber = ?";
=======
        $sql = "SELECT * FROM ProductList WHERE productNumber = ?";
>>>>>>> e43f6d20d66a6f26299eb24314683ca4f5d9dc25
        $stmt = $conn->prepare($sql);
        $stmt->execute([$productNumber]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $sql = "INSERT INTO suppliers(supplierName, phoneNumber, email, address, productNumber, supplyPrice, supplyDate)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([$supplierName, $phoneNumber, $email, $address, $productNumber, $supplyPrice, $supplyDate]);

            $message = "Supplier added successfully.";
        } else {
            $error = "Product number does not exist in Product List.";
        }
    }
}

<<<<<<< HEAD
$sql = "SELECT * FROM productlist";
=======
$sql = "SELECT * FROM ProductList";
>>>>>>> e43f6d20d66a6f26299eb24314683ca4f5d9dc25
$stmt = $conn->prepare($sql);
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT 
            suppliers.supplierID,
            suppliers.supplierName,
            suppliers.phoneNumber,
            suppliers.email,
            suppliers.address,
            suppliers.productNumber,
<<<<<<< HEAD
            productlist.productName,
            suppliers.supplyPrice,
            suppliers.supplyDate
        FROM suppliers, productlist
        WHERE suppliers.productNumber = productlist.productNumber";
=======
            ProductList.productName,
            suppliers.supplyPrice,
            suppliers.supplyDate
        FROM suppliers, ProductList
        WHERE suppliers.productNumber = ProductList.productNumber";
>>>>>>> e43f6d20d66a6f26299eb24314683ca4f5d9dc25

$stmt = $conn->prepare($sql);
$stmt->execute();

$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supplier - DreamShop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once "header.php"; ?>

<div class="search-container">
    <div class="logo">
        <h1>DreamShop</h1>
        <p>Supplier Management</p>
    </div>

    <h2 class="form-title">Add Supplier</h2>

    <?php if ($message != ""): ?>
        <div class="message success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if ($error != ""): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-group">
            <label>Supplier Name</label>
            <input type="text" name="supplierName" placeholder="Enter supplier name">
        </div>

        <div class="input-group">
            <label>Phone Number</label>
            <input type="text" name="phoneNumber" placeholder="Enter phone number">
        </div>

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter email">
        </div>

        <div class="input-group">
            <label>Address</label>
            <input type="text" name="address" placeholder="Enter address">
        </div>

        <div class="input-group">
            <label>Product Number</label>
            <input type="number" name="productNumber" placeholder="Enter product number from Product List">
        </div>

        <div class="input-group">
            <label>Supply Price</label>
            <input type="number" name="supplyPrice" placeholder="Enter supply price">
        </div>

        <div class="input-group">
            <label>Supply Date</label>
            <input type="date" name="supplyDate">
        </div>

        <button type="submit" class="btn btn-green">Add Supplier</button>
    </form>

    <?php if ($products != ""): ?>
        <div class="result-box">
            <h3>Available Product Numbers</h3>

            <table>
                <tr>
                    <th>Product Number</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Shelf Number</th>
                </tr>

                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product["productNumber"]; ?></td>
                        <td><?php echo $product["productName"]; ?></td>
                        <td><?php echo $product["price"]; ?></td>
                        <td><?php echo $product["type"]; ?></td>
                        <td><?php echo $product["locationName"]; ?></td>
                        <td><?php echo $product["shelfNumber"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>

    <?php if ($suppliers != ""): ?>
        <div class="result-box">
            <h3>Supplier List</h3>

            <table>
                <tr>
                    <th>Supplier ID</th>
                    <th>Supplier Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Product Number</th>
                    <th>Product Name</th>
                    <th>Supply Price</th>
                    <th>Supply Date</th>
                </tr>

                <?php foreach ($suppliers as $supplier): ?>
                    <tr>
                        <td><?php echo $supplier["supplierID"]; ?></td>
                        <td><?php echo $supplier["supplierName"]; ?></td>
                        <td><?php echo $supplier["phoneNumber"]; ?></td>
                        <td><?php echo $supplier["email"]; ?></td>
                        <td><?php echo $supplier["address"]; ?></td>
                        <td><?php echo $supplier["productNumber"]; ?></td>
                        <td><?php echo $supplier["productName"]; ?></td>
                        <td><?php echo $supplier["supplyPrice"]; ?></td>
                        <td><?php echo $supplier["supplyDate"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>