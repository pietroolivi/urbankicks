document.addEventListener('DOMContentLoaded', function() {
    const heartIcons = document.querySelectorAll('.wishlist-heart');
    checkEmptyWishlist();
    
    heartIcons.forEach(heart => {
        heart.style.cursor = 'pointer';
        heart.addEventListener('click', function() {
            const productId = this.dataset.productId;
            removeFromWishlist(productId, this);
        });
    });
});

function checkEmptyWishlist() {
    const wishlistItems = document.querySelectorAll('.product-details');
    const wishlistNav = document.querySelector('nav');
    
    if (wishlistItems.length === 0) {
        if (wishlistNav) {
            wishlistNav.style.display = 'none';
        }
        const emptyMessage = document.createElement('p');
        emptyMessage.textContent = 'Your wishlist is empty. Browse our products to add items!';
        document.querySelector('h2').insertAdjacentElement('afterend', emptyMessage);
    }
}

function removeFromWishlist(productId, heartIcon) {
    fetch('wishlist_handler.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=remove&productId=${productId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the entire product container from the wishlist
            const productContainer = heartIcon.closest('.product-details');
            productContainer.remove();
            checkEmptyWishlist();
        } else {
            alert('Error removing item from wishlist');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}