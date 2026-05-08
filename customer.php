<?php
session_start();
require_once "db.php";

$message = "";
$error = "";
$customers = "";
$points = "";

if ($_SESSION["user_email"] == "") {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullName = $_POST["fullName"];
    $phoneNumber = $_POST["phoneNumber"];
    $email = $_POST["email"];
    $purchaseDate = $_POST["purchaseDate"];
    $totalAmount = $_POST["totalAmount"];

    if ($fullName == "" || $phoneNumber == "" || $email == "" || $purchaseDate == "" || $totalAmount == "") {
        $error = "All fields are required.";
    } else {
        $sql = "SELECT * FROM customers WHERE phoneNumber = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$phoneNumber]);

        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customer) {
            $customerID = $customer["customerID"];
        } else {
            $sql = "INSERT INTO customers(firstPurchaseDate, totalPoints, fullName, phoneNumber, email)
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([$purchaseDate, 0, $fullName, $phoneNumber, $email]);

            $sql = "SELECT * FROM customers WHERE phoneNumber = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$phoneNumber]);

            $customer = $stmt->fetch(PDO::FETCH_ASSOC);
            $customerID = $customer["customerID"];
        }

        $sql = "INSERT INTO Purchase(customerID, purchaseDate, totalAmount)
                VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$customerID, $purchaseDate, $totalAmount]);

        $sql = "DELETE FROM CustomerPoint
                WHERE customerID = ?
                AND pointDate = ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$customerID, $purchaseDate]);

        $sql = "
        INSERT INTO CustomerPoint(customerID, pointDate, totalPurchase, earnedPoint)
        SELECT
            customerID,
            purchaseDate,
            SUM(totalAmount),
            CASE
                WHEN SUM(totalAmount) <= 2000 THEN 2
                WHEN SUM(totalAmount) > 2000 AND SUM(totalAmount) <= 5000 THEN 5
                WHEN SUM(totalAmount) > 5000 AND SUM(totalAmount) <= 10000 THEN 7
                WHEN SUM(totalAmount) > 10000 THEN 10
            END
        FROM Purchase
        WHERE customerID = ?
        AND purchaseDate = ?
        GROUP BY customerID, purchaseDate
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$customerID, $purchaseDate]);

        $sql = "
        UPDATE customers
        SET totalPoints = (
            SELECT SUM(earnedPoint)
            FROM CustomerPoint
            WHERE CustomerPoint.customerID = customers.customerID
        )
        WHERE customerID = ?
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$customerID]);

        $message = "Customer purchase added and points calculated successfully.";
    }
}

$sql = "SELECT * FROM customers";
$stmt = $conn->prepare($sql);
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT 
            customers.customerID,
            customers.fullName,
            customers.phoneNumber,
            customers.email,
            CustomerPoint.pointDate,
            CustomerPoint.totalPurchase,
            CustomerPoint.earnedPoint
        FROM customers, CustomerPoint
        WHERE customers.customerID = CustomerPoint.customerID";

$stmt = $conn->prepare($sql);
$stmt->execute();
$points = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer - DreamShop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once "header.php"; ?>

<div class="search-container">
    <div class="logo">
        <h1>DreamShop</h1>
        <p>Customer and Points Management</p>
    </div>

    <h2 class="form-title">Add Customer Purchase</h2>

    <?php if ($message != ""): ?>
        <div class="message success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if ($error != ""): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-group">
            <label>Full Name</label>
            <input type="text" name="fullName" placeholder="Enter customer full name">
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
            <label>Customer First Purchase / Purchase Date</label>
            <input type="date" name="purchaseDate">
        </div>

        <div class="input-group">
            <label>Purchase Amount</label>
            <input type="number" name="totalAmount" placeholder="Enter purchase amount">
        </div>

        <button type="submit" class="btn btn-green">Add Purchase</button>
    </form>

    <?php if ($customers != ""): ?>
        <div class="result-box">
            <h3>Customer List</h3>

            <table>
                <tr>
                    <th>Customer ID</th>
                    <th>First Purchase Date</th>
                    <th>Total Points</th>
                    <th>Full Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Purchase History</th>
                </tr>

                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?php echo $customer["customerID"]; ?></td>
                        <td><?php echo $customer["firstPurchaseDate"]; ?></td>
                        <td><?php echo $customer["totalPoints"]; ?></td>
                        <td><?php echo $customer["fullName"]; ?></td>
                        <td><?php echo $customer["phoneNumber"]; ?></td>
                        <td><?php echo $customer["email"]; ?></td>
                        <td>
                            <a href="purchase_history.php?phoneNumber=<?php echo $customer["phoneNumber"]; ?>">
                                View History
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>

    <?php if ($points != ""): ?>
        <div class="result-box">
            <h3>Customer Points List</h3>

            <table>
                <tr>
                    <th>Customer ID</th>
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Point Date</th>
                    <th>Total Purchase</th>
                    <th>Earned Point</th>
                </tr>

                <?php foreach ($points as $point): ?>
                    <tr>
                        <td><?php echo $point["customerID"]; ?></td>
                        <td><?php echo $point["fullName"]; ?></td>
                        <td><?php echo $point["phoneNumber"]; ?></td>
                        <td><?php echo $point["email"]; ?></td>
                        <td><?php echo $point["pointDate"]; ?></td>
                        <td><?php echo $point["totalPurchase"]; ?></td>
                        <td><?php echo $point["earnedPoint"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>