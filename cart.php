<?php
session_start();
require_once "db.php";

$message = "";
$error = "";
$cartItems = "";

if ($_SESSION["user_email"] == "") {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $phoneNumber = $_POST["phoneNumber"];
    $productNumber = $_POST["productNumber"];
    $quantity = $_POST["quantity"];

    if ($phoneNumber == "" || $productNumber == "" || $quantity == "") {
        $error = "All fields are required.";
    } else {
        $sql = "SELECT * FROM customers WHERE phoneNumber = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$phoneNumber]);

        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customer) {
<<<<<<< HEAD
            $sql = "SELECT * FROM productlist WHERE productNumber = ?";
=======
            $sql = "SELECT * FROM ProductList WHERE productNumber = ?";
>>>>>>> e43f6d20d66a6f26299eb24314683ca4f5d9dc25
            $stmt = $conn->prepare($sql);
            $stmt->execute([$productNumber]);

            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $sql = "INSERT INTO cart(phoneNumber, productNumber, quantity, totalPrice, addedDate)
                        SELECT ?, productNumber, ?, price * ?, CURDATE()
<<<<<<< HEAD
                        FROM productlist
=======
                        FROM ProductList
>>>>>>> e43f6d20d66a6f26299eb24314683ca4f5d9dc25
                        WHERE productNumber = ?";

                $stmt = $conn->prepare($sql);
                $stmt->execute([$phoneNumber, $quantity, $quantity, $productNumber]);

                $message = "Product added to cart successfully.";
            } else {
                $error = "Product number does not exist in Product List.";
            }
        } else {
            $error = "Customer phone number does not exist in Customer list.";
        }
    }
}

$sql = "SELECT 
            cart.cartID,
            cart.phoneNumber,
            customers.fullName,
            customers.email,
            cart.productNumber,
<<<<<<< HEAD
            productlist.productName,
            productlist.price,
            cart.quantity,
            cart.totalPrice,
            cart.addedDate
        FROM cart, customers, productlist
        WHERE cart.phoneNumber = customers.phoneNumber
        AND cart.productNumber = productlist.productNumber";
=======
            ProductList.productName,
            ProductList.price,
            cart.quantity,
            cart.totalPrice,
            cart.addedDate
        FROM cart, customers, ProductList
        WHERE cart.phoneNumber = customers.phoneNumber
        AND cart.productNumber = ProductList.productNumber";
>>>>>>> e43f6d20d66a6f26299eb24314683ca4f5d9dc25

$stmt = $conn->prepare($sql);
$stmt->execute();

$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart - DreamShop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once "header.php"; ?>

<div class="search-container">
    <div class="logo">
        <h1>DreamShop</h1>
        <p>Cart Management</p>
    </div>

    <h2 class="form-title">Add Product To Cart</h2>

    <?php if ($message != ""): ?>
        <div class="message success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if ($error != ""): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-group">
            <label>Customer Phone Number</label>
            <input type="text" name="phoneNumber" placeholder="Enter customer phone number">
        </div>

        <div class="input-group">
            <label>Product Number</label>
            <input type="number" name="productNumber" placeholder="Enter product number from Product List">
        </div>

        <div class="input-group">
            <label>Product Quantity</label>
            <input type="number" name="quantity" placeholder="Enter product quantity">
        </div>

        <button type="submit" class="btn btn-green">Add To Cart</button>
    </form>

    <?php if ($cartItems != ""): ?>
        <div class="result-box">
            <h3>Cart Items</h3>

            <table>
                <tr>
                    <th>Cart ID</th>
                    <th>Customer Phone</th>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Product Number</th>
                    <th>Product Name</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Date Added</th>
                </tr>

                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><?php echo $item["cartID"]; ?></td>
                        <td><?php echo $item["phoneNumber"]; ?></td>
                        <td><?php echo $item["fullName"]; ?></td>
                        <td><?php echo $item["email"]; ?></td>
                        <td><?php echo $item["productNumber"]; ?></td>
                        <td><?php echo $item["productName"]; ?></td>
                        <td><?php echo $item["price"]; ?></td>
                        <td><?php echo $item["quantity"]; ?></td>
                        <td><?php echo $item["totalPrice"]; ?></td>
                        <td><?php echo $item["addedDate"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>