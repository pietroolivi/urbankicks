<?php
require_once("bootstrap.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_SPECIAL_CHARS);
    $lastName = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);

    // Create subject from name
    $subject = "Contact from $firstName $lastName";
    
    // Use the existing database function to save message
    if($dbh->sendMessage($email, $subject, $message)) {
        $_SESSION['alert'] = "Message sent successfully!";
        $_SESSION['alert_type'] = "success";
        header("Location: contact_us.php");
    } else {
        $_SESSION['alert'] = "Error sending message. Please try again.";
        $_SESSION['alert_type'] = "error";
        header("Location: contact_us.php");
    }
    exit();
}
?>