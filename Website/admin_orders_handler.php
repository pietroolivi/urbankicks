<?php
require_once("bootstrap.php");

// Check if user is logged in and is admin
if (!isset($_SESSION["user_email"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['orderId'], $data['status'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

try {
    $success = $dbh->updateOrderStatus(
        $data['orderId'], 
        $data['status']
    );
    
    echo json_encode(['success' => $success]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Error updating order status: ' . $e->getMessage()
    ]);
}
?>