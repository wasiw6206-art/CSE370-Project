<?php
session_start();

$_SESSION["userID"] = "";
$_SESSION["user_name"] = "";
$_SESSION["user_email"] = "";
$_SESSION["reset_email"] = "";

header("Location: index.php");
exit();
?>