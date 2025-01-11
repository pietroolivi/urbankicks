<?php
require_once("bootstrap.php");

if (!isset($_SESSION["user_email"])) {
    http_response_code(401);
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];
    
    if (isset($_POST['notificationIds'])) {
        $notificationIds = json_decode($_POST['notificationIds']);
        $action = $_POST['action'] ?? '';
        $email = $_SESSION["user_email"];

        try {
            $success = $dbh->markNotificationsAsRead($email, $notificationIds);
            
            if ($success) {
                $response = [
                    'success' => true,
                    'message' => $action === 'markAllRead' ? 
                        'All notifications marked as read' : 
                        'Notification marked as read'
                ];
            }
        } catch (Exception $e) {
            http_response_code(500);
            $response = ['success' => false, 'message' => 'Server error'];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>