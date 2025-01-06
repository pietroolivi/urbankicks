<?php
require_once("bootstrap.php");

// Define template parameters
$templateParams["title"] = "Login";
$templateParams["name"] = "login_content.php";
$templateParams["js"] = [
    "JS/Objects/listenerObjectSetting.js",
    "JS/Functions/listeners.js",
    "JS/login.js",
    "JS/otp.js"
];

require_once("Template/base.php");
?>