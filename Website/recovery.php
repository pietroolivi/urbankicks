<?php
require_once("bootstrap.php");

// Define template parameters
$templateParams["title"] = "Recovery";
$templateParams["name"] = "recovery_content.php";
$templateParams["js"] = [
    "JS/Objects/listenerObjectSetting.js",
    "JS/Functions/listeners.js",
    "JS/recovery.js",
    "otp.js"
];

require_once("Template/base.php");
?>