<?php
require_once("bootstrap.php");

// Define template parameters
$templateParams["title"] = "Home";
$templateParams["name"] = "home_content.php";
$templateParams["js"] = [
    "JS/Objects/listenerObjectSetting.js",
    "JS/Functions/listeners.js",
    "JS/home.js"
];

require_once("Template/base.php");
?>