<?php
require_once("bootstrap.php");

// Check if user is logged in
if(!isset($_SESSION["user_email"])){
    header("Location: login.php");
    exit();
}

// Check if order ID is provided
if(!isset($_GET["order"])) {
    header("Location: orders.php");
    exit();
}

// Get tracking info from database
$templateParams["tracking"] = $dbh->getOrderTracking($_GET["order"]);

// Set template parameters
$templateParams["title"] = "Track Order";
$templateParams["name"] = "tracking_content.php";
$templateParams["js"] = [
    "JS/Objects/listenerObjectSetting.js",
    "JS/Functions/listeners.js"
];

require_once("Template/base.php");
?>