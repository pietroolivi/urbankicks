<?php
require_once("bootstrap.php");

// Define template parameters
$templateParams["title"] = "Register";
$templateParams["name"] = "register_content.php";
$templateParams["js"] = [
    "JS/Objects/listenerObjectSetting.js",
    "JS/Functions/listeners.js",
    "JS/register.js",
    "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"
];

// Add CSS for telephone input
$templateParams["css"] = [
    "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"
];

require_once("Template/base.php");
?>