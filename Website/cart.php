<?php
require_once("bootstrap.php");

// Check if user is logged in
if(!isset($_SESSION["user_email"])){
    header("Location: login.php");
    exit();
}

$templateParams["title"] = "Cart";
$templateParams["name"] = "cart_content.php";
$templateParams["js"] = [
    "JS/Objects/listenerObjectSetting.js",
    "JS/Functions/listeners.js",
    "JS/cart.js"
];

require_once("Template/base.php");
?>