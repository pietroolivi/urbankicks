<?php
require_once("bootstrap.php");

// Check if user is logged in and is admin
if (!isset($_SESSION["user_email"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    header("Location: login.php");
    exit();
}

$templateParams["completed_orders"] = $dbh->getCompletedOrders();
$templateParams["pending_orders"] = $dbh->getPendingOrders();
$templateParams["total_users"] = $dbh->getTotalUsers();
$templateParams["best_seller"] = $dbh->getBestSeller();

// Define template parameters
$templateParams["title"] = "Dashboard";
$templateParams["name"] = "admin_home_content.php";

require_once("Template/admin_base.php");
?>