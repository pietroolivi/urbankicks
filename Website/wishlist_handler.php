<?php
require_once("bootstrap.php");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit();
}

if (!isset($_SESSION["user_email"])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit();
}

// Initialize response array
$response = ["success" => false, "message" => ""];

try {
    if (isset($_POST["action"]) && $_POST["action"] === "remove") {
        $productId = isset($_POST["productId"]) ? $_POST["productId"] : null;
        
        if ($productId) {
            $result = $dbh->removeFromWishlist($_SESSION["user_email"], $productId);
            $response = ["success" => true, "message" => "Product removed from wishlist"];
        } else {
            throw new Exception("Product ID not provided");
        }
    }
} catch (Exception $e) {
    $response = ["success" => false, "message" => $e->getMessage()];
}

echo json_encode($response);
exit();
?>