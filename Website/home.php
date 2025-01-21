<?php
require_once("bootstrap.php");

$products = $dbh->getFilteredProducts();

$templateParams["title"] = "Home";
$templateParams["name"] = "home_content.php";
$templateParams["products"] = $products;
$templateParams["js"] = [
    "JS/Objects/listenerObjectSetting.js",
    "JS/Functions/listeners.js",
    "JS/home.js"
];

require_once("Template/base.php");
?>