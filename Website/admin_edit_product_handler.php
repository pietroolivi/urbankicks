<?php
require_once("bootstrap.php");

if (!isset($_SESSION["user_email"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productId = $_POST["product_id"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $variants = $_POST["quantity"];
    
    try {
        $result = $dbh->updateProduct($productId, $description, $price, $variants);
        echo json_encode(["success" => true, "message" => "Product updated successfully"]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Error updating product"]);
    }
}
?>