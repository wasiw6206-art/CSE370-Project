<?php
session_start();
require_once "db.php";

$message = "";
$error = "";
$ratings = "";

if ($_SESSION["user_email"] == "") {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $phoneNumber = $_POST["phoneNumber"];
    $employeeID = $_POST["employeeID"];
    $productNumber = $_POST["productNumber"];

    $deliveryRating = $_POST["deliveryRating"];
    $employeeBehaviorRating = $_POST["employeeBehaviorRating"];
    $productQualityRating = $_POST["productQualityRating"];

    $improvement = $_POST["improvement"];

    if ($phoneNumber == "" || $employeeID == "" || $productNumber == "" || $deliveryRating == "" || $employeeBehaviorRating == "" || $productQualityRating == "") {
        $error = "All fields are required except improvement.";
    } else {
        $sql = "SELECT * FROM customers WHERE phoneNumber = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$phoneNumber]);

        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customer) {
            $customerID = $customer["customerID"];

            $sql = "SELECT * FROM employee WHERE employeeID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$employeeID]);

            $employee = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($employee) {
                $sql = "SELECT * FROM productlist WHERE productNumber = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$productNumber]);

                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($product) {
                    $sql = "SELECT * FROM rating WHERE customerID = ? AND productNumber = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$customerID, $productNumber]);

                    $existingRating = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($existingRating) {
                        $error = "This customer already rated this product.";
                    } else {
                        $sql = "INSERT INTO rating(customerID, employeeID, productNumber, deliveryRating, employeeBehaviorRating, productQualityRating, improvement)
                                VALUES (?, ?, ?, ?, ?, ?, ?)";

                        $stmt = $conn->prepare($sql);
                        $stmt->execute([
                            $customerID,
                            $employeeID,
                            $productNumber,
                            $deliveryRating,
                            $employeeBehaviorRating,
                            $productQualityRating,
                            $improvement
                        ]);

                        $message = "Rating added successfully.";
                    }
                } else {
                    $error = "Product number does not exist.";
                }
            } else {
                $error = "Employee ID does not exist.";
            }
        } else {
            $error = "Customer phone number does not exist.";
        }
    }
}

$sql = "SELECT 
            rating.ratingID,
            customers.customerID,
            customers.fullName AS customerName,
            customers.phoneNumber,
            employee.employeeID,
            employee.fullName AS employeeName,
            productlist.productNumber,
            productlist.productName,
            rating.deliveryRating,
            rating.employeeBehaviorRating,
            rating.productQualityRating,
            rating.improvement,
            rating.ratingDate
        FROM rating, customers, employee, productlist
        WHERE rating.customerID = customers.customerID
        AND rating.employeeID = employee.employeeID
        AND rating.productNumber = productlist.productNumber";

$stmt = $conn->prepare($sql);
$stmt->execute();

$ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rating - DreamShop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once "header.php"; ?>

<div class="search-container">
    <div class="logo">
        <h1>DreamShop</h1>
        <p>Customer Rating System</p>
    </div>

    <h2 class="form-title">Add Rating</h2>

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
            <label>Employee ID</label>
            <input type="number" name="employeeID" placeholder="Enter employee ID">
        </div>

        <div class="input-group">
            <label>Product Number</label>
            <input type="number" name="productNumber" placeholder="Enter product number">
        </div>

        <div class="input-group">
            <label>Delivery Rating</label>
            <input type="number" name="deliveryRating" placeholder="Rate delivery 1 to 5">
        </div>

        <div class="input-group">
            <label>Employee Behavior Rating</label>
            <input type="number" name="employeeBehaviorRating" placeholder="Rate employee behavior 1 to 5">
        </div>

        <div class="input-group">
            <label>Product Quality Rating</label>
            <input type="number" name="productQualityRating" placeholder="Rate product quality 1 to 5">
        </div>

        <div class="input-group">
            <label>Improvement</label>
            <input type="text" name="improvement" placeholder="Write improvement suggestion">
        </div>

        <button type="submit" class="btn btn-green">Submit Rating</button>
    </form>

    <?php if ($ratings != ""): ?>
        <div class="result-box">
            <h3>Rating List</h3>

            <table>
                <tr>
                    <th>Rating ID</th>
                    <th>Customer ID</th>
                    <th>Customer Name</th>
                    <th>Customer Phone</th>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Product Number</th>
                    <th>Product Name</th>
                    <th>Delivery</th>
                    <th>Employee Behavior</th>
                    <th>Product Quality</th>
                    <th>Improvement</th>
                    <th>Rating Date</th>
                </tr>

                <?php foreach ($ratings as $rating): ?>
                    <tr>
                        <td><?php echo $rating["ratingID"]; ?></td>
                        <td><?php echo $rating["customerID"]; ?></td>
                        <td><?php echo $rating["customerName"]; ?></td>
                        <td><?php echo $rating["phoneNumber"]; ?></td>
                        <td><?php echo $rating["employeeID"]; ?></td>
                        <td><?php echo $rating["employeeName"]; ?></td>
                        <td><?php echo $rating["productNumber"]; ?></td>
                        <td><?php echo $rating["productName"]; ?></td>
                        <td><?php echo $rating["deliveryRating"]; ?></td>
                        <td><?php echo $rating["employeeBehaviorRating"]; ?></td>
                        <td><?php echo $rating["productQualityRating"]; ?></td>
                        <td><?php echo $rating["improvement"]; ?></td>
                        <td><?php echo $rating["ratingDate"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>