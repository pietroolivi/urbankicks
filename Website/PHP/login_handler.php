<?php
require_once("bootstrap.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["emailinsert"]) && !empty($_POST["emailinsert"])) {
    header('Content-Type: application/json');
    $email = $_POST["emailinsert"];
    if (isset($_POST["password"])) {
        $password = $_POST["password"];
        $user = $dbh->loginUser($email, $password);
    } else {
        $response = ["exists" => $dbh->isUserRegistered($_POST["emailinsert"])];
        echo json_encode($response);
        exit();
    }

    if ($user) {
        $response = ["success" => true, "message" => "Login successful"];
    } else {
        $response = ["success" => false, "message" => "Invalid email or password"];
    }

    echo json_encode($response);
    exit();
}
?>