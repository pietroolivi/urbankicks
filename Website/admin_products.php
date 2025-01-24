<?php
require_once("bootstrap.php");

// Check if user is logged in and is admin
if (!isset($_SESSION["user_email"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    header("Location: login.php");
    exit();
}

// Get all products
$templateParams["products"] = $dbh->getProducts();

// Define template parameters
$templateParams["title"] = "Products";
$templateParams["name"] = "admin_products_content.php";
$templateParams["js"] = [
    "JS/admin_products.js"
];

require_once("Template/admin_base.php");
?>