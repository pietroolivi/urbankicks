<?php
require_once("bootstrap.php");

// Check if user is logged in and is admin
if (!isset($_SESSION["user_email"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    header("Location: login.php");
    exit();
}

// Get orders with completed "Delivered" status
$completedOrders = $dbh->getOrdersByStatus(true);

// Get orders that are not yet delivered
$ongoingOrders = $dbh->getOrdersByStatus(false);

// Set template parameters
$templateParams["title"] = "Orders";
$templateParams["name"] = "admin_orders_content.php";
$templateParams["js"] = [
    "JS/admin_orders.js"
];
$templateParams["ongoingOrders"] = $ongoingOrders;
$templateParams["completedOrders"] = $completedOrders;

require_once("Template/admin_base.php");
?>