<?php
require_once("bootstrap.php");

// Check if user is logged in
if(!isset($_SESSION["user_email"])){
    header("Location: login.php");
    exit();
}

// Get orders from database
$templateParams["orders"] = $dbh->getOrders($_SESSION["user_email"]);

// Set template parameters
$templateParams["title"] = "Orders";
$templateParams["name"] = "orders_content.php";
$templateParams["js"] = [
    "JS/Objects/listenerObjectSetting.js",
    "JS/Functions/listeners.js"
];

require_once("Template/base.php");
?>