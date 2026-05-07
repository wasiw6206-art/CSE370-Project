<?php
session_start();
require_once "db.php";

$error = "";
$searchPhone = "";
$purchases = "";

if ($_SESSION["user_email"] == "") {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $searchPhone = $_POST["searchPhone"];
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $searchPhone = $_GET["phoneNumber"] ?? "";
}

if ($searchPhone != "") {
    $sql = "SELECT 
                cart.cartID,
                cart.phoneNumber,
                customers.fullName,
                customers.email,
                cart.productNumber,
                ProductList.productName,
                ProductList.type,
                ProductList.price,
                cart.quantity,
                cart.totalPrice,
                cart.addedDate
            FROM cart, customers, ProductList
            WHERE cart.phoneNumber = customers.phoneNumber
            AND cart.productNumber = ProductList.productNumber
            AND cart.phoneNumber = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$searchPhone]);

    $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($purchases == false) {
        $error = "No purchase history found for this customer.";
    }
} else {
    $sql = "SELECT 
                cart.cartID,
                cart.phoneNumber,
                customers.fullName,
                customers.email,
                cart.productNumber,
                ProductList.productName,
                ProductList.type,
                ProductList.price,
                cart.quantity,
                cart.totalPrice,
                cart.addedDate
            FROM cart, customers, ProductList
            WHERE cart.phoneNumber = customers.phoneNumber
            AND cart.productNumber = ProductList.productNumber";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase History - DreamShop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once "header.php"; ?>

<div class="search-container">
    <div class="logo">
        <h1>DreamShop</h1>
        <p>Customer Purchase History</p>
    </div>

    <h2 class="form-title">Purchase History</h2>

    <?php if ($error != ""): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-group">
            <label>Search by Customer Phone Number</label>
            <input type="text" name="searchPhone" value="<?php echo $searchPhone; ?>" placeholder="Enter customer phone number">
        </div>

        <button type="submit" class="btn">Search Purchase History</button>
    </form>

    <?php if ($purchases != ""): ?>
        <div class="result-box">
            <h3>Purchase History List</h3>

            <table>
                <tr>
                    <th>Cart ID</th>
                    <th>Customer Phone</th>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Product Number</th>
                    <th>Product Name</th>
                    <th>Type</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Purchase Date</th>
                </tr>

                <?php foreach ($purchases as $purchase): ?>
                    <tr>
                        <td><?php echo $purchase["cartID"]; ?></td>
                        <td><?php echo $purchase["phoneNumber"]; ?></td>
                        <td><?php echo $purchase["fullName"]; ?></td>
                        <td><?php echo $purchase["email"]; ?></td>
                        <td><?php echo $purchase["productNumber"]; ?></td>
                        <td><?php echo $purchase["productName"]; ?></td>
                        <td><?php echo $purchase["type"]; ?></td>
                        <td><?php echo $purchase["price"]; ?></td>
                        <td><?php echo $purchase["quantity"]; ?></td>
                        <td><?php echo $purchase["totalPrice"]; ?></td>
                        <td><?php echo $purchase["addedDate"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>