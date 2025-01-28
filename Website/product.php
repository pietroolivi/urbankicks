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
if(!isset($productData)) {
    header("Location: home.php");
    exit();
}

// Add status check with back navigation
if($productData["product"]['Sta_Tipo'] === 'Not Available') {
    $_SESSION['error'] = "This product is no longer available.";
    if(isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header('Location: home.php');
    }
    exit();
}

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