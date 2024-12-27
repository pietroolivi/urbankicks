<?php
require_once("bootstrap.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["emailinsert"])) {
    header('Content-Type: application/json');
    $response = ["exists" => $dbh->isUserRegistered($_POST["emailinsert"])];
    echo json_encode($response);
}
?>