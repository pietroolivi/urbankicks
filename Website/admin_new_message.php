<?php
require_once("bootstrap.php");

// Check if user is logged in and is admin
if (!isset($_SESSION["user_email"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    header("Location: login.php");
    exit();
}

// Initialize template parameters
$templateParams["title"] = "New Message";
$templateParams["name"] = "admin_new_message_content.php";

// Pre-fill fields if this is a reply
if (isset($_GET['to']) && isset($_GET['subject'])) {
    $templateParams["recipient"] = $_GET['to'];
    $templateParams["subject"] = $_GET['subject'];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $recipient = $_POST["email-new-message-admin"];
    $subject = $_POST["subject-new-message-admin"];
    $body = $_POST["body-new-message-admin"];
    
    // Validate recipient exists and is a customer
    $user = $dbh->getUserProfile($recipient);
    if ($user && $user["Ruolo"] === "Customer") {
        // Create notification for the user
        $dbh->createAdminMessageNotification($recipient, $subject);
        
        // Send message
        if ($dbh->sendMessage($recipient, $subject, $body)) {
            $_SESSION["success_message"] = "Message sent successfully";
            header("Location: admin_messages.php");
            exit();
        } else {
            $_SESSION["error_message"] = "Failed to send message";
        }
    } else {
        $_SESSION["error_message"] = "Invalid recipient email";
    }
}

require_once("Template/admin_base.php");
?>