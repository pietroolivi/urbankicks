document.addEventListener('DOMContentLoaded', function() {
    const statusButtons = document.querySelectorAll('footer button');
    
    statusButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderItem = this.closest('li');
            const orderId = orderItem.querySelector('header p:first-child')
                .textContent.replace('Order #', '').trim();
            const newStatus = this.textContent.replace('Mark as ', '').trim();
            
            updateOrderStatus(orderId, newStatus, orderItem);
        });
    });
});

async function updateOrderStatus(orderId, newStatus, orderItem) {
    try {
        const button = orderItem.querySelector('button');
        button.disabled = true;
        button.textContent = 'Updating...';

        const response = await fetch('admin_orders_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                orderId: orderId,
                status: newStatus
            })
        });

        const data = await response.json();
        
        if (data.success) {
            // Update UI
            orderItem.querySelector('header p:last-child').textContent = newStatus;
            
            if (newStatus === 'Delivered') {
                // Move to completed orders
                const completedSection = document.querySelector('section:last-child ol');
                orderItem.querySelector('footer').remove();
                completedSection.insertBefore(orderItem, completedSection.firstChild);
            } else {
                // Update button text
                const nextStatus = newStatus === 'In Progress' ? 'Shipped' : 'Delivered';
                button.textContent = `Mark as ${nextStatus}`;
            }
        } else {
            throw new Error(data.message || 'Failed to update order status');
        }
    } catch (error) {
        alert('Error updating order status: ' + error.message);
    } finally {
        const button = orderItem.querySelector('button');
        if (button) {
            button.disabled = false;
        }
    }
}