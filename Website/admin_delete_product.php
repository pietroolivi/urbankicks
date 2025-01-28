<?php
require_once("bootstrap.php");

// Check if user is logged in and is admin
if (!isset($_SESSION["user_email"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    header("Location: login.php");
    exit();
}

// Check if product ID is provided
if (!isset($_GET["id"])) {
    header("Location: admin_products.php");
    exit();
}

// Get product data
$templateParams["product"] = $dbh->getProductData($_GET["id"]);
$templateParams["variants"] = $dbh->getProductVariants($_GET["id"]);

// Define template parameters
$templateParams["title"] = "Delete Product";
$templateParams["name"] = "admin_delete_product_content.php";
$templateParams["js"] = [
    "JS/admin_delete_product.js"
];

require_once("Template/admin_base.php");
?>