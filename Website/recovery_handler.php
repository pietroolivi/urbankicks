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
    // Email verification check
    if (isset($_POST["check_email_only"]) && $_POST["check_email_only"] === "true") {
        $email = filter_var($_POST["email-recovery"], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        if (!$dbh->isUserRegistered($email)) {
            throw new Exception("User with this email is not registered");
        }
        $response = ["success" => true, "message" => "Email verified"];
    } else if (isset($_POST["send_otp"]) && $_POST["send_otp"] === "true") {
        $email = filter_var($_POST["email-recovery"], FILTER_SANITIZE_EMAIL);
        
        // Generate 6-digit OTP
        $otp = sprintf("%06d", mt_rand(0, 999999));
        $_SESSION['recovery_otp'] = $otp;
        $_SESSION['recovery_email'] = $email;
        $_SESSION['recovery_expires'] = time() + 900; // 15 minutes

        // Add headers for better email delivery
        $headers = "From: root@localhost\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
        
        // Send email
        $subject = "Password Recovery OTP - Urban Kicks";
        $message = "Your OTP for password recovery is: $otp\n";
        $message .= "This code will expire in 15 minutes.";
        
        if (!mail($email, $subject, $message, $headers)) {
            throw new Exception("Failed to send OTP");
        }
        
        $response = ["success" => true, "message" => "OTP sent successfully"];
    } else if (isset($_POST["verify_otp"]) && $_POST["verify_otp"] === "true") {
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $otp = $_POST["otp"];
        
        if (!isset($_SESSION['recovery_otp']) || 
            !isset($_SESSION['recovery_email']) || 
            $_SESSION['recovery_email'] !== $email) {
            throw new Exception("Invalid recovery session");
        }
        
        if ($_SESSION['recovery_expires'] <= time()) {
            throw new Exception("OTP has expired");
        }
        
        if ($_SESSION['recovery_otp'] !== $otp) {
            throw new Exception("Invalid OTP");
        }
        
        $response = ["success" => true, "message" => "OTP verified"];
    } else if (isset($_POST["update_password"]) && $_POST["update_password"] === "true") {
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $password = $_POST["password"];
        
        if (!isset($_SESSION['recovery_email']) || $_SESSION['recovery_email'] !== $email) {
            throw new Exception("Invalid recovery session");
        }
        
        // Validate password rules
        if (strlen($password) < 8 || strlen($password) > 20) {
            throw new Exception("Password must be between 8 and 20 characters");
        }
        if (!preg_match('/[a-zA-Z]/', $password) || !preg_match('/\d/', $password)) {
            throw new Exception("Password must contain both letters and numbers");
        }
        if (!preg_match('/[!"#$%&\'()*+,\-./:;<=>?]/', $password)) {
            throw new Exception("Password must contain at least one special character");
        }
        
        // Update password in database
        if (!$dbh->updatePassword($email, $password)) {
            throw new Exception("Failed to update password");
        }
        
        // Clear recovery session
        unset($_SESSION['recovery_otp']);
        unset($_SESSION['recovery_email']);
        unset($_SESSION['recovery_expires']);
        
        $response = ["success" => true, "message" => "Password updated successfully"];
    } else {
        throw new Exception("Invalid operation");
    }
} catch (Exception $e) {
    $response = ["success" => false, "message" => $e->getMessage()];
}

echo json_encode($response);
exit();