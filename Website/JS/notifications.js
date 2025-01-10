document.addEventListener('DOMContentLoaded', () => {
    // Handle "Mark all as read" button
    const markAllReadBtn = document.querySelector('button');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', markAllNotificationsAsRead);
    }

    // Handle individual notification clicks
    document.querySelectorAll('li[data-notification-id]').forEach(notification => {
        notification.addEventListener('click', () => markNotificationAsRead(notification));
    });
});

function markAllNotificationsAsRead() {
    const unreadItems = document.querySelectorAll('li[data-tipo="unread"]');
    const notificationIds = Array.from(unreadItems).map(item => 
        item.dataset.notificationId
    );

    if (notificationIds.length === 0) return;

    fetch('notifications_handler.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `notificationIds=${JSON.stringify(notificationIds)}&action=markAllRead`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            unreadItems.forEach(item => {
                item.setAttribute('data-tipo', 'read');
                const unreadDot = item.querySelector('.unread-dot');
                if (unreadDot) unreadDot.remove();
            });
        }
    })
    .catch(error => console.error('Error:', error));
}

function markNotificationAsRead(notificationElement) {
    if (notificationElement.getAttribute('data-tipo') !== 'unread') return;

    const notificationId = notificationElement.dataset.notificationId;

    fetch('notifications_handler.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `notificationIds=${JSON.stringify([notificationId])}&action=markAsRead`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            notificationElement.setAttribute('data-tipo', 'read');
            const unreadDot = notificationElement.querySelector('.unread-dot');
            if (unreadDot) unreadDot.remove();
        }
    })
    .catch(error => console.error('Error:', error));
}