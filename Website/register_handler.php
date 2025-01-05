<?php
require_once("bootstrap.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit();
}

// Initialize response array
$response = ["success" => false, "message" => ""];

try {
    if (isset($_POST["check_email_only"]) && $_POST["check_email_only"]==="true") {
        $email = filter_var($_POST["emailinsert"], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        // Check if user already exists
        if ($dbh->isUserRegistered($email)) {
            throw new Exception("User with this email is already registered");
        }

        echo json_encode([
            "success" => true, 
            "message" => "Email is available"
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>
