<?php
require_once("bootstrap.php");

// Check if user is logged in
if(!isset($_SESSION["user_email"])){
    header("Location: login.php");
    exit();
}

$templateParams["title"] = "Contact Us";
$templateParams["name"] = "contact_us_content.php";
$templateParams["js"] = [
    "JS/Objects/listenerObjectSetting.js",
    "JS/Functions/listeners.js"
];

require_once("Template/base.php");
?>