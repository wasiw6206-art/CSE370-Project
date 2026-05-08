<?php
session_start();
require_once "db.php";

$message = "";
$error = "";
$employees = "";

if ($_SESSION["user_email"] == "") {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $employeeID = $_POST["employeeID"];
    $fullName = $_POST["fullName"];
    $phoneNumber = $_POST["phoneNumber"];
    $email = $_POST["email"];
    $position = $_POST["position"];
    $hireDate = $_POST["hireDate"];
    $salary = $_POST["salary"];
    $status = $_POST["status"];

    if ($employeeID == "" || $fullName == "" || $phoneNumber == "" || $email == "" || $position == "" || $hireDate == "" || $salary == "" || $status == "") {
        $error = "All fields are required.";
    } else {
        $sql = "SELECT * FROM employee WHERE employeeID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$employeeID]);

        $existingEmployee = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingEmployee) {
            $error = "This employee ID already exists.";
        } else {
            $sql = "INSERT INTO employee(employeeID, fullName, phoneNumber, email, position, hireDate, salary, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([$employeeID, $fullName, $phoneNumber, $email, $position, $hireDate, $salary, $status]);

            $message = "Employee added successfully.";
        }
    }
}

$sql = "SELECT * FROM employee";
$stmt = $conn->prepare($sql);
$stmt->execute();

$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee - DreamShop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once "header.php"; ?>

<div class="search-container">
    <div class="logo">
        <h1>DreamShop</h1>
        <p>Employee Management</p>
    </div>

    <h2 class="form-title">Add Employee</h2>

    <?php if ($message != ""): ?>
        <div class="message success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if ($error != ""): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-group">
            <label>Employee ID</label>
            <input type="number" name="employeeID" placeholder="Enter employee ID">
        </div>

        <div class="input-group">
            <label>Full Name</label>
            <input type="text" name="fullName" placeholder="Enter full name">
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
            <label>Position</label>
            <input type="text" name="position" placeholder="Enter position">
        </div>

        <div class="input-group">
            <label>Hire Date</label>
            <input type="date" name="hireDate">
        </div>

        <div class="input-group">
            <label>Salary</label>
            <input type="number" name="salary" placeholder="Enter salary">
        </div>

        <div class="input-group">
            <label>Status</label>
            <input type="text" name="status" placeholder="Active / Inactive">
        </div>

        <button type="submit" class="btn btn-green">Add Employee</button>
    </form>

    <?php if ($employees != ""): ?>
        <div class="result-box">
            <h3>Employee List</h3>

            <table>
                <tr>
                    <th>Employee ID</th>
                    <th>Full Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Hire Date</th>
                    <th>Salary</th>
                    <th>Status</th>
                </tr>

                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?php echo $employee["employeeID"]; ?></td>
                        <td><?php echo $employee["fullName"]; ?></td>
                        <td><?php echo $employee["phoneNumber"]; ?></td>
                        <td><?php echo $employee["email"]; ?></td>
                        <td><?php echo $employee["position"]; ?></td>
                        <td><?php echo $employee["hireDate"]; ?></td>
                        <td><?php echo $employee["salary"]; ?></td>
                        <td><?php echo $employee["status"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>