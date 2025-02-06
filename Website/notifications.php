<?php
require_once("bootstrap.php");

// Check if user is logged in
if(!isset($_SESSION["user_email"])){
    header("Location: login.php");
    exit();
}

$templateParams["notifications"] = $dbh->getUserNotifications($_SESSION["user_email"]);

$templateParams["title"] = "Notifications";
$templateParams["name"] = "notifications_content.php";
$templateParams["js"] = [
    "JS/Objects/listenerObjectSetting.js",
    "JS/Functions/listeners.js",
    "JS/notifications.js"
];

require_once("Template/base.php");
?>