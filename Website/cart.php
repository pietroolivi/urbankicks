<?php
require_once("bootstrap.php");

$templateParams["title"] = "Cart";
$templateParams["name"] = "cart_content.php";
$templateParams["js"] = [
    "JS/Objects/listenerObjectSetting.js",
    "JS/Functions/listeners.js",
    "JS/cart.js"
];

require_once("Template/base.php");
?>