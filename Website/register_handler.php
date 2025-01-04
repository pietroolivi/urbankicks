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

    // Validate first name
    if (!isset($_POST["firstname"]) || empty($_POST["firstname"])) {
        throw new Exception("First name is required");
    }
    $firstName = filter_var($_POST["firstname"], FILTER_SANITIZE_STRING);

    // Validate last name
    if (!isset($_POST["lastname"]) || empty($_POST["lastname"])) {
        throw new Exception("Last name is required");
    }
    $lastName = filter_var($_POST["lastname"], FILTER_SANITIZE_STRING);

    // Validate password
    if (!isset($_POST["password"]) || empty($_POST["password"])) {
        throw new Exception("Password is required");
    }
    $password = $_POST["password"];

    // Optional: Validate phone number
    $phone = isset($_POST["phone"]) ? filter_var($_POST["phone"], FILTER_SANITIZE_STRING) : null;

    // Check if user already exists
    if ($dbh->isUserRegistered($email)) {
        throw new Exception("User with this email is already registered");
    }

    // Register user
    $registered = $dbh->registerUser($email, $firstName, $lastName, $password, $phone);
    if (!$registered) {
        throw new Exception("Failed to register user");
    }

    $response = ["success" => true, "message" => "User registered successfully"];
} catch (Exception $e) {
    $response = ["success" => false, "message" => $e->getMessage()];
}

echo json_encode($response);
exit();
?>
