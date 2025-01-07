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
        $email = filter_var($_POST["email-login"], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        // Check if user already exists
        if (!$dbh->isUserRegistered($email)) {
            throw new Exception("User with this email is not registered");
        }

        echo json_encode([
            "success" => true, 
            "message" => "Email is registered"
        ]);
        exit();
    } else if (isset($_POST["password-login"])) {
        $user = $dbh->loginUser($_POST['email-login'], $_POST["password-login"]);
        if ($user) {
            $_SESSION['user_email'] = $_POST['email-login'];
            $response = ["success" => true, "message" => "Login successful"];
        } else {
            throw new Exception("Invalid password");
        }
    }
    else if (isset($_POST["generate_reset_code"]) && $_POST["generate_reset_code"] === "true") {
        // Generate reset code
        if (!$dbh->isUserRegistered($email)) {
            throw new Exception("User with this email is not registered");
        }
        
        $_SESSION['reset_code'] = bin2hex(random_bytes(16));
        $_SESSION['reset_code_expire'] = time() + 900; // 15 minutes
        $_SESSION['reset_email'] = $email;

        $subject = "Password Reset - Urban Kicks";
        $message = "Your password reset code is: " . $_SESSION['reset_code'] . "\n";
        $message .= "This code will expire in 15 minutes.";
        
        if (!mail($email, $subject, $message)) {
            throw new Exception("Failed to send reset code");
        }
        
        $response = ["success" => true, "message" => "Reset code sent"];
    }
    else if (isset($_POST["reset_code"]) && isset($_POST["new_password"])) {
        // Verify reset code and update password
        if (!isset($_SESSION['reset_code']) || !isset($_SESSION['reset_email']) || 
            $_SESSION['reset_email'] !== $email) {
            throw new Exception("Invalid reset attempt");
        }

        if ($_SESSION['reset_code'] !== $_POST["reset_code"]) {
            throw new Exception("Invalid reset code");
        }

        if ($_SESSION['reset_code_expire'] <= time()) {
            throw new Exception("Reset code has expired");
        }

        // Update password
        if (!$dbh->updatePassword($email, $_POST["new_password"])) {
            throw new Exception("Failed to update password");
        }

        // Clear reset session data
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