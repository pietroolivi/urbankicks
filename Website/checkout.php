<?php
require_once("bootstrap.php");

// Check if user is logged in
if(!isset($_SESSION["user_email"])){
    header("Location: login.php");
    exit();
}

// Get cart items from database
$templateParams["cart"] = $dbh->getCartItems($dbh->getCartByEmail($_SESSION["user_email"])["ID_Carrello"]);

// Set template parameters
$templateParams["title"] = "Checkout";
$templateParams["name"] = "checkout_content.php";
$templateParams["js"] = [
    "JS/checkout.js"
];

require_once("Template/base.php");
?>