<?php
require_once("bootstrap.php");

// Check for product ID
if (!isset($_GET['id'])) {
    header("Location: home.php");
    exit();
}

// Get user email if logged in
$userEmail = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : null;

// Get product data
$productData = $dbh->getProductData($_GET['id'], $userEmail);

// Define template parameters
$templateParams["title"] = $productData['product']['Nome'];
$templateParams["name"] = "product_content.php";
$templateParams["js"] = [
    "JS/Objects/listenerObjectSetting.js",
    "JS/Functions/listeners.js",
    "JS/product.js"
];
$templateParams["productData"] = $productData;

require_once("Template/base.php");
?>