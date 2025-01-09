<?php
require_once("bootstrap.php");

// Check if user is logged in
if(!isset($_SESSION["user_email"])){
    header("Location: login.php");
    exit();
}

// Get wishlist items
$templateParams["wishlistItems"] = $dbh->getWishlistItems($_SESSION["email"]);

// Set template parameters
$templateParams["title"] = "Wishlist";
$templateParams["name"] = "wishlist_content.php";
$templateParams["js"] = [
    "JS/Objects/listenerObjectSetting.js",
    "JS/Functions/listeners.js",
    "js/wishlist.js"
];

require_once("Template/base.php");
?>