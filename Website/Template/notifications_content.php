<a href="javascript:history.back()" class="back">
    <img src="CSS/Images/Icons/back.svg" alt="Icon representing a backward arrow, to return to the previous page." />
</a>
<h2>Notifications</h2>
<button>Mark all as read <img src="CSS/Images/Icons/eye_open.svg" alt="" /></button>

<?php
$notifications = $dbh->getUserNotifications($_SESSION["user_email"]);
$groupedNotifications = [];

foreach ($notifications as $notification) {
    $date = date('d F Y', strtotime($notification['Timestamp_Invio']));
    $groupedNotifications[$date][] = $notification;
}

foreach ($groupedNotifications as $date => $dayNotifications): ?>
    <section class="notifications-<?= strtolower(str_replace(' ', '-', $date)) ?>">
        <h3><?= strtoupper($date) ?></h3>
        <ol>
        <?php foreach ($dayNotifications as $notification): ?>
                <li data-notification-id="<?= htmlspecialchars($notification['ID_Notifica']) ?>" 
                    data-tipo="<?= htmlspecialchars($notification['Tipo']) ?>">
                    <a>
                        <img src="CSS/Images/Icons/notification_<?= getNotificationIcon($notification['TipoNotifica']) ?>.svg" 
                             alt="<?= getNotificationIconAlt($notification['TipoNotifica']) ?>" />
                        <h4><?= htmlspecialchars($notification['TipoNotifica']) ?></h4>
                        <p><?= htmlspecialchars($notification['Messaggio']) ?></p>
                        <?php if ($notification['Tipo'] === 'unread'): ?>
                            <img class="unread-dot" src="CSS/Images/Icons/notification_unread.svg" 
                                 alt="Blue dot, notification not yet read">
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ol>
    </section>
<?php endforeach; ?>

<?php
function getNotificationIcon($type) {
    $icons = [
        'Prodotto In Stock' => 'product',
        'Stato Ordine' => 'order1',
        'Offerta' => 'price',
        'Carrello' => 'cart',
        'Messaggio' => 'message'
    ];
    return $icons[$type] ?? 'message';
}

function getNotificationIconAlt($type) {
    $alts = [
        'Prodotto In Stock' => 'Icon of a shoe, indicating a notification about products.',
        'Stato Ordine' => 'Icon of a parcel, indicating a notification concerning the processing of an order.',
        'Offerta' => 'Percentage symbol icon, indicating a notification denoting the price change of a favorite item.',
        'Carrello' => 'Icon of a shopping cart, signaling a notification about an abandoned basket.',
        'Messaggio' => 'Chat icon, marking a notification related to a message.'
    ];
    return $alts[$type] ?? 'Notification icon';
}
?>