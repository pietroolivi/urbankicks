class CartHandler {
    constructor() {
        this.cartContainer = document.getElementById('cart-container');
        this.cartItemTemplate = document.getElementById('cart-item-template');
        this.emptyCartTemplate = document.getElementById('empty-cart-template');
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Event delegation for dynamic elements
        document.addEventListener('change', (e) => {
            const target = e.target;
            if (target.matches('.size-selector')) this.handleSizeChange(target);
            if (target.matches('.color-selector')) this.handleColorChange(target);
            if (target.matches('.quantity-selector')) this.handleQuantityChange(target);
        });

        document.addEventListener('click', (e) => {
            const target = e.target;
            
            if (target.closest('.move-to-wishlist')) {
                e.preventDefault();
                this.handleMoveToWishlist(target.closest('li'));
            }
            
            if (target.closest('.remove-from-cart')) {
                e.preventDefault();
                this.handleRemoveFromCart(target.closest('li'));
            }

            if (target.matches('.continue-shopping')) {
                window.location.href = 'home.php';
            }

            if (target.matches('.proceed-checkout')) {
                window.location.href = 'checkout.php';
            }
        });
    }

    async handleSizeChange(select) {
        const item = select.closest('li');
        const data = {
            productId: item.dataset.productId,
            color: item.dataset.color,
            newSize: select.value,
            action: 'updateSize'
        };

        const success = await this.updateCart(data);
        if (success) item.dataset.size = select.value;
    }

    async handleColorChange(select) {
        const item = select.closest('li');
        const data = {
            productId: item.dataset.productId,
            size: item.dataset.size,
            newColor: select.value,
            action: 'updateColor'
        };

        const success = await this.updateCart(data);
        if (success) item.dataset.color = select.value;
    }

    async handleQuantityChange(input) {
        const item = input.closest('li');
        const data = {
            productId: item.dataset.productId,
            color: item.dataset.color,
            size: item.dataset.size,
            quantity: input.value,
            action: 'updateQuantity'
        };

        await this.updateCart(data);
    }

    async handleMoveToWishlist(item) {
        const data = {
            productId: item.dataset.productId,
            color: item.dataset.color,
            size: item.dataset.size,
            action: 'moveToWishlist'
        };

        const success = await this.updateCart(data);
        if (success) this.removeItemFromUI(item);
    }

    async handleRemoveFromCart(item) {
        const data = {
            productId: item.dataset.productId,
            color: item.dataset.color,
            size: item.dataset.size,
            action: 'removeItem'
        };

        const success = await this.updateCart(data);
        if (success) this.removeItemFromUI(item);
    }

    async updateCart(data) {
        try {
            const response = await fetch('cart_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(data)
            });

            const result = await response.json();
            
            if (result.success) {
                this.updateCartTotals(result.itemCount, result.cartTotal);
                return true;
            }
            return false;
        } catch (error) {
            console.error('Error updating cart:', error);
            return false;
        }
    }

    async handleColorChange(select) {
        const item = select.closest('li');
        const sizeSelect = item.querySelector('.size-selector');
        const currentSize = sizeSelect.value;
        
        try {
            const formData = new FormData();
            formData.append('productId', item.dataset.productId);
            formData.append('color', select.value);
            formData.append('action', 'getAvailableSizes');

            const response = await fetch('cart_handler.php', {
                method: 'POST',
                body: formData
            });

            const availableSizes = await response.json();
            
            // Clear current size options
            sizeSelect.innerHTML = '';
            
            if (availableSizes.length === 0) {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No sizes available for this color';
                sizeSelect.appendChild(option);
                this.showErrorMessage(item, 'Selected color has no available sizes');
                return;
            }
            
            // Check if current size is available in new color
            const currentSizeAvailable = availableSizes.some(size => size.Taglia === currentSize);
            
            availableSizes.forEach(size => {
                const option = document.createElement('option');
                option.value = size.Taglia;
                option.textContent = size.Taglia;
                if (size.Taglia === currentSize) {
                    option.selected = true;
                }
                sizeSelect.appendChild(option);
            });

            if (!currentSizeAvailable) {
                this.showErrorMessage(item, 'Selected size not available in this color');
                sizeSelect.value = availableSizes[0].Taglia;
            }

            // Update cart in database
            const updateData = new FormData();
            updateData.append('action', 'updateColor');
            updateData.append('productId', item.dataset.productId);
            updateData.append('newColor', select.value);
            updateData.append('size', sizeSelect.value);

            const updateResponse = await fetch('cart_handler.php', {
                method: 'POST',
                body: updateData
            });

            const result = await updateResponse.json();
            if (result.success) {
                item.dataset.color = select.value;
            }
        } catch (error) {
            console.error('Error handling color change:', error);
            this.showErrorMessage(item, 'Error updating color');
        }
    }
    
    showErrorMessage(item, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        
        const existingError = item.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }
        
        item.querySelector('.item-info').appendChild(errorDiv);
        setTimeout(() => errorDiv.remove(), 3000);
    }

    removeItemFromUI(item) {
        item.remove();
        const remainingItems = document.querySelectorAll('.cart-items li').length;
        
        if (remainingItems === 0) {
            this.showEmptyCart();
        }
    }

    showEmptyCart() {
        this.cartContainer.innerHTML = '';
        const emptyCart = this.emptyCartTemplate.content.cloneNode(true);
        this.cartContainer.appendChild(emptyCart);
    }

    updateCartTotals(itemCount, total) {
        const itemCountElement = document.querySelector('p:contains("ITEMS")');
        const totalElement = document.querySelector('p:contains("SUBTOTAL")');
        
        if (itemCountElement) {
            itemCountElement.textContent = `${itemCount} ITEMS`;
        }
        
        if (totalElement) {
            totalElement.textContent = `SUBTOTAL €${parseFloat(total).toFixed(2)}`;
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new CartHandler();
});