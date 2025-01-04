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
    // Validate email presence for all operations
    if (!isset($_POST["email-login"]) || empty($_POST["email-login"])) {
        throw new Exception("Email is required");
    }

    $email = filter_var($_POST["email-login"], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }

    // Handle different operations based on POST parameters
    if (isset($_POST["check_email_only"]) && $_POST["check_email_only"] === "true") {
        // Check if email exists
        $exists = $dbh->isUserRegistered($email);
        $response = ["success" => true, "exists" => $exists];
    } 
    else if (isset($_POST["password-login"])) {
        // Handle login
        if (empty($_POST["password-login"])) {
            throw new Exception("Password is required");
        }
        
        $user = $dbh->loginUser($email, $_POST["password-login"]);
        if ($user) {
            $response = ["success" => true, "exists" => true,"message" => "Login successful"];
        } else {
            $response = ["success" => false, "exists" => false,"message" => "Invalid email or password"];
        }
    }
    else if (isset($_POST["generate_reset_code"]) && $_POST["generate_reset_code"] === "true") {
        // Generate and send reset code
        $_SESSION['reset_code'] = bin2hex(random_bytes(16));
        $_SESSION['reset_code_expire'] = time() + 900; // 15 minutes
        $_SESSION['reset_email'] = $email; // Store email for verification

        $subject = "Reset password";
        $message = "Your reset code is: " . $_SESSION['reset_code'] . "\n";
        $message .= "This code will expire in 15 minutes.";
        
        if (mail($email, $subject, $message)) {
            $response = ["success" => true, "message" => "Reset code sent to your email"];
        } else {
            throw new Exception("Failed to send reset code");
        }
    }
    else if (isset($_POST["reset_code"]) && isset($_POST["new_password"])) {
        // Verify reset code and update password
        if (empty($_POST["reset_code"]) || empty($_POST["new_password"])) {
            throw new Exception("Reset code and new password are required");
        }

        if (!isset($_SESSION['reset_code']) || !isset($_SESSION['reset_code_expire']) || 
            !isset($_SESSION['reset_email']) || $_SESSION['reset_email'] !== $email) {
            throw new Exception("Invalid reset attempt");
        }

        if ($_SESSION['reset_code'] !== $_POST["reset_code"]) {
            throw new Exception("Invalid reset code");
        }

        if ($_SESSION['reset_code_expire'] <= time()) {
            throw new Exception("Reset code has expired");
        }

        // Update password
        $dbh->updatePassword($email, $_POST["new_password"]);
        
        // Clear reset session variables
        unset($_SESSION['reset_code']);
        unset($_SESSION['reset_code_expire']);
        unset($_SESSION['reset_email']);
        
        $response = ["success" => true, "message" => "Password updated successfully"];
    }
    else {
        throw new Exception("Invalid operation");
    }
} catch (Exception $e) {
    $response = ["success" => false, "message" => $e->getMessage()];
}

echo json_encode($response);
exit();