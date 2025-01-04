<?php
require_once("bootstrap.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];

    if (isset($_POST['notificationIds']) && isset($_POST['email'])) {
        $notificationIds = $_POST['notificationIds'];
        $email = $_POST['email'];

        if ($dbh->markNotificationsAsRead($notificationIds, $email)) {
            $response['success'] = true;
            $response['message'] = 'Notifications marked as read';
        }
    } else if (isset($_POST['reloadNotifications'])) {
        $notifications = $dbh->getUserNotifications($_SESSION['email']);
        $response['success'] = true;
        $response['notifications'] = $notifications;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>