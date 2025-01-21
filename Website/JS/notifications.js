document.addEventListener('DOMContentLoaded', () => {
    const markAllReadBtn = document.querySelector('button');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', markAllNotificationsAsRead);
    }

    document.addEventListener('click', async (e) => {
        const notificationLink = e.target.closest('.notification-link');
        if (notificationLink) {
            e.preventDefault();
            const notificationItem = notificationLink.closest('.notification-item');
            
            if (notificationItem.getAttribute('data-tipo') === 'Non Letta') {
                await markNotificationAsRead(notificationItem);
            }
            
            // Follow the link after marking as read
            window.location.href = notificationLink.href;
        }
    });
});

async function markNotificationAsRead(notificationElement) {
    const notificationId = notificationElement.dataset.notificationId;
    
    try {
        const response = await fetch('notifications_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `notificationIds=${JSON.stringify([notificationId])}&action=markAsRead`
        });
        
        const data = await response.json();
        if (data.success) {
            notificationElement.setAttribute('data-tipo', 'Letta');
            const unreadDot = notificationElement.querySelector('.unread-dot');
            if (unreadDot) unreadDot.remove();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function markAllNotificationsAsRead() {
    const unreadItems = document.querySelectorAll('li[data-tipo="Non Letta"]');
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
                item.setAttribute('data-tipo', 'Letta');
                const unreadDot = item.querySelector('.unread-dot');
                if (unreadDot) unreadDot.remove();
            });
        }
    })
    .catch(error => console.error('Error:', error));
}