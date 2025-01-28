<?php
require_once("bootstrap.php");

// Check if user is logged in and is admin
if (!isset($_SESSION["user_email"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    header("Location: login.php");
    exit();
}

$templateParams["messages"] = $dbh->getAllMessages();

// Define template parameters
$templateParams["title"] = "Messages";
$templateParams["name"] = "admin_messages_content.php";

require_once("Template/admin_base.php");
?>