<?php
if(isset($_SESSION['error'])) {
    echo "<input type='hidden' id='error-message' value='" . htmlspecialchars($_SESSION['error']) . "'>";
    unset($_SESSION['error']);
}
?>
<a href="javascript:history.back()" class="back">
    <img src="CSS/Images/Icons/back.svg" alt="Icon representing a backward arrow, to return to the previous page." />
</a>
<h2>Notifications</h2>
<button>Mark all as read <img src="CSS/Images/Icons/eye_open.svg" alt="" /></button>

<?php
$groupedNotifications = [];

foreach ($templateParams["notifications"] as $notification) {
    $date = date('d F Y', strtotime($notification['Timestamp_Invio']));
    $groupedNotifications[$date][] = $notification;
}

foreach ($groupedNotifications as $date => $dayNotifications): ?>
    <section class="notifications-<?= strtolower(str_replace(' ', '-', $date)) ?>">
        <h3><?= strtoupper($date) ?></h3>
        <ol>
        <?php foreach ($dayNotifications as $notification): 
            $isAdminMessage = $notification['TipoNotifica'] === 'Admin Message';
            $isExpanded = isset($_GET['expanded']) && $_GET['expanded'] == $notification['ID_Notifica'];
            $messageContent = $isAdminMessage ? $notification['MessaggioCompleto'] : $notification['Messaggio'];
            $url = $isAdminMessage ? "?expanded=" . $notification['ID_Notifica'] : getNotificationUrl($notification);
        ?>
            <li data-notification-id="<?= htmlspecialchars($notification['ID_Notifica']) ?>" 
                data-tipo="<?= htmlspecialchars($notification['Tipo']) ?>"
                class="notification-item">
                <a href="<?= htmlspecialchars($url) ?>" class="notification-link">
                    <img src="CSS/Images/Icons/notification_<?= getNotificationIcon($notification['TipoNotifica']) ?>.svg" 
                        alt="<?= getNotificationIconAlt($notification['TipoNotifica']) ?>" />
                    <h4><?= htmlspecialchars($notification['TipoNotifica']) ?></h4>
                    <p><?= htmlspecialchars($notification['Messaggio']) ?></p>
                    <?php if ($notification['Tipo'] === 'Unread'): ?>
                        <img class="unread-dot" src="CSS/Images/Icons/notification_unread.svg" 
                            alt="Blue dot, notification not yet read">
                    <?php endif; ?>
                </a>
                <?php if ($isAdminMessage && $isExpanded): ?>
                    <div class="message-content">
                        <p class="message-body"><?= htmlspecialchars($messageContent) ?></p>
                        <a href="contact_us.php" class="reply-btn">Reply</a>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
        </ol>
    </section>
<?php endforeach; ?>

<?php
function getNotificationIcon($type) {
    $icons = [
        'Stock Product' => 'product',
        'Order Status' => 'order1',
        'Flash Sale' => 'price',
        'Cart Reminder' => 'cart',
        'Admin Message' => 'message',
        'Review Request' => 'review'
    ];
    return $icons[$type] ?? 'message';
}

function getNotificationIconAlt($type) {
    $alts = [
        'Stock Product' => 'Icon of a shoe, indicating a notification about products.',
        'Order Status' => 'Icon of a parcel, indicating a notification concerning the processing of an order.',
        'Flash Sale' => 'Percentage symbol icon, indicating a notification denoting the price change of a favorite item.',
        'Cart Reminder' => 'Icon of a shopping cart, signaling a notification about an abandoned basket.',
        'Admin Message' => 'Chat icon, marking a notification related to a message.',
        'Review Request' => 'Icon of a star, indicating a notification about a review request.'
    ];
    return $alts[$type] ?? 'Notification icon';
}

function getNotificationUrl($notification) {
    if (preg_match('/\[(\d+)\]/', $notification['Messaggio'], $matches)) {
        $id = $matches[1];
        switch($notification['TipoNotifica']) {
            case 'Stock Product':
            case 'Review Request':
            case 'Flash Sale':
                return "product.php?id=" . $id;
            case 'Order Status':
                return "tracking.php?order=" . $id;
            case 'Cart Reminder':
                return "cart.php";
        }
    }
    return '#';
}
?>