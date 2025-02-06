<?php
require_once("bootstrap.php");
include_once('check_cart.php');

// Define template parameters
$templateParams["title"] = "Home";
$templateParams["name"] = "index_content.php";
$templateParams["js"] = [
    "JS/Objects/listenerObjectSetting.js",
    "JS/Functions/listeners.js"
];

require_once("Template/base.php");
?>