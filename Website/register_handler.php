<?php
require_once("bootstrap.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit();
}

// Initialize response array
$response = ["success" => false, "message" => ""];

try {
    if (isset($POST["check_email_only"]) && $POST["check_email_only"]==true) {
        $email = filter_var($_POST["emailinsert"], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        // Check if user already exists
        if ($dbh->isUserRegistered($email)) {
            throw new Exception("User with this email is already registered");
        }
    } else {
        // Validate email presence
        if (!isset($_POST["emailinsert"]) || empty($_POST["emailinsert"])) {
            throw new Exception("Email is required");
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

        $email = filter_var($_POST["emailinsert"], FILTER_SANITIZE_EMAIL);

        // Register user
        $registered = $dbh->registerUser($email, $firstName, $lastName, $password, $phone);
        if (!$registered) {
            throw new Exception("Failed to register user");
        } else {
            header("Location: home.php");
            exit();
        }
    }
} catch (Exception $e) {
    header('Content-Type: application/json');
    $response = ["success" => false, "message" => $e->getMessage()];
}

echo json_encode($response);
exit();
?>
