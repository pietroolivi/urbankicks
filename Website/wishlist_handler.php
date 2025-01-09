<?php
require_once("bootstrap.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit();
}

// Initialize response array
$response = ["success" => false, "message" => ""];

switch ($_POST["action"]) {
    case "add":
        // Verify product exists and has stock before adding
        if ($dbh->addToWishlist($_SESSION["email"], $_POST["productId"])) {
            $response = [
                "success" => true,
                "message" => "Item added to wishlist"
            ];
        } else {
            $response = [
                "success" => false,
                "message" => "Item is already in wishlist"
            ];
        }
        break;

    case "remove":
        if ($dbh->removeFromWishlist($_SESSION["email"], $_POST["productId"])) {
            $response = [
                "success" => true,
                "message" => "Item removed from wishlist"
            ];
        } else {
            $response = [
                "success" => false,
                "message" => "Failed to remove item from wishlist"
            ];
        }
        break;
}

header("Content-Type: application/json");
echo json_encode($response);
?>