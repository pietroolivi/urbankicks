document.addEventListener('DOMContentLoaded', function() {
    const heartIcons = document.querySelectorAll('.wishlist-heart');
    checkEmptyWishlist();
  /*  
    heartIcons.forEach(heart => {
        heart.style.cursor = 'pointer';
        heart.addEventListener('click', function() {
            const productId = this.dataset.productId;
            removeFromWishlist(productId, this);
        });*/
        document.addEventListener('change', (event) => {
            if (event.target.classList.contains('wishlist-checkbox')) {
                handleWishlistToggle(event.target);
            }
    });
});

async function handleWishlistToggle(checkbox) {
    const productCard = checkbox.closest('.product-card');
    const productId = productCard.dataset.productId;
    const isAdd = checkbox.checked;
    //const wishlistText = checkbox.nextElementSibling;
    const imgHeart = productCard.querySelector('img.wishlist-checkbox');
    console.log("product card vale "+productCard);
    try {
       /* const formData = new FormData();
        formData.append('action', 'toggleWishlist');
        formData.append('productId', productId);
        formData.append('isAdd', isAdd);*/

        const response = await fetch('wishlist_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body:new URLSearchParams({
                action: 'toggleWishlist',
                productId: productId,
            })
        });
      /*  const data= await response.text();
        console.log(data);*/
        const data = await response.json();

        if (data.success) {
                productCard.remove();
                checkEmptyWishlist(); 
        } else {
            // Revert checkbox state on error
            checkbox.checked = !isAdd;
            wishlistText.textContent = !isAdd ? 'Remove from Wishlist' : 'Add to Wishlist';
            
            // Show error message
            if (data.message === 'Please login first') {
                window.location.href = 'login.php';
            } else {
                alert(data.message);
            }
        }
    } catch (error) {
        console.error('Error:', error);
        // Revert checkbox state on error
        checkbox.checked = !isAdd;
        //wishlistText.textContent = !isAdd ? 'Remove from Wishlist' : 'Add to Wishlist';
        alert('An error occurred. Please try again.');
    }
}




function checkEmptyWishlist() {
    const wishlistItems = document.querySelectorAll('.product-card');
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
/*
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
            const productContainer = heartIcon.closest('.product-container');
            productContainer.remove();
            checkEmptyWishlist();
        } else {
            alert('Error removing item from wishlist');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}*/

window.onload = function() {
    const errorMsg = document.getElementById('error-message');
    if (errorMsg && errorMsg.value) {
        setTimeout(() => {
            alert(errorMsg.value);
            errorMsg.remove();
        }, 100);
    }
};