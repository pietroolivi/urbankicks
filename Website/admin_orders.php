<?php
require_once("bootstrap.php");

// Check if user is logged in and is admin
if (!isset($_SESSION["user_email"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    header("Location: login.php");
    exit();
}

// Get orders from database
$ongoingOrders = [];
$completedOrders = [];

// Get ongoing orders (where Arrivo_Effettivo is NULL)
foreach(['Placed', 'In progress', 'Shipped'] as $status) {
    $orders = $dbh->getOrdersByStatus($status);
    if (!empty($orders)) {
        $ongoingOrders = array_merge($ongoingOrders, $orders);
    }
}

// Get completed orders (where Arrivo_Effettivo is NOT NULL for 'Delivered' status)
$completedOrders = $dbh->getOrdersByStatus('Delivered');

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