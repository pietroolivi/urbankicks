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
    // Validate email presence
    if (!isset($_POST["emailinsert"]) || empty($_POST["emailinsert"])) {
        throw new Exception("Email is required");
    }

    $email = filter_var($_POST["emailinsert"], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }

    $exists = $dbh->isUserRegistered($email);
    $response = ["success" => true, "exists" => $exists];
} catch (Exception $e) {
    $response = ["success" => false, "message" => $e->getMessage()];
}

echo json_encode($response);
exit();
?>