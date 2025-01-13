class CartHandler {
    constructor() {
        this.setupEventListeners();
    }

    setupEventListeners() {
        document.addEventListener('change', (e) => {
            const target = e.target;
            if (target.matches('.size-selector')) {
                this.handleSizeChange(target);
            }
            if (target.matches('.color-selector')) {
                this.handleColorChange(target);
            }
            if (target.matches('.quantity-selector')) {
                this.handleQuantityChange(target);
            }
        });

        document.addEventListener('click', (e) => {
            const target = e.target.closest('button');
            if (!target) return;

            const item = target.closest('li');
            if (!item) return;

            if (target.matches('.move-to-wishlist')) {
                this.handleMoveToWishlist(item);
            }
            if (target.matches('.remove-from-cart')) {
                this.handleRemoveFromCart(item);
            }
        });
    }

    async handleColorChange(select) {
        const item = select.closest('li');
        const sizeSelect = item.querySelector('.size-selector');
        const currentSize = sizeSelect.value;
        
        try {
            const formData = new FormData();
            formData.append('action', 'getAvailableSizes');
            formData.append('productId', item.dataset.productId);
            formData.append('color', select.value);

            const response = await fetch('cart_handler.php', {
                method: 'POST',
                body: formData
            });

            const availableSizes = await response.json();
            
            // Clear existing options
            sizeSelect.innerHTML = '';
            
            // Check if current size is available in new color
            const currentSizeAvailable = availableSizes.some(size => size.Taglia === currentSize);
            
            // Add empty option if current size not available
            if (!currentSizeAvailable) {
                const emptyOption = document.createElement('option');
                emptyOption.value = '';
                emptyOption.textContent = 'Select size';
                sizeSelect.appendChild(emptyOption);
            }
            
            // Add all available sizes
            availableSizes.forEach(size => {
                const option = document.createElement('option');
                option.value = size.Taglia;
                option.textContent = size.Taglia;
                // If current size is available, keep it selected
                if (size.Taglia === currentSize) {
                    option.selected = true;
                }
                sizeSelect.appendChild(option);
            });
            
            // Update cart if we have a valid size
            if (currentSizeAvailable) {
                await this.updateCartItem(item, {
                    color: select.value,
                    size: currentSize
                });
            }

        } catch (error) {
            console.error('Error updating color:', error);
        }
    }

    async handleSizeChange(select) {
        const item = select.closest('li');
        await this.updateCartItem(item, {
            color: item.querySelector('.color-selector').value,
            size: select.value
        });
    }

    async handleQuantityChange(input) {
        const item = input.closest('li');
        await this.updateCartItem(item, {
            quantity: parseInt(input.value)
        });
    }

    async handleMoveToWishlist(item) {
        const data = new FormData();
        data.append('action', 'moveToWishlist');
        data.append('productId', item.dataset.productId);
        data.append('color', item.dataset.color);
        data.append('size', item.dataset.size);

        try {
            const response = await fetch('cart_handler.php', {
                method: 'POST',
                body: data
            });

            const result = await response.json();
            if (!result.success) {
                throw new Error(result.message || 'Failed to move to wishlist');
            }

            this.removeItemFromUI(item);
            this.updateCartTotals(result.itemCount, result.cartTotal);

        } catch (error) {
            console.error('Error:', error);
            this.showErrorMessage(item, 'Error moving to wishlist');
        }
    }

    async handleRemoveFromCart(item) {
        const data = new FormData();
        data.append('removeItem', item.dataset.productId);
        data.append('color', item.dataset.color);
        data.append('size', item.dataset.size);

        try {
            const response = await fetch('cart_handler.php', {
                method: 'POST',
                body: data
            });

            const result = await response.json();
            if (!result.success) {
                throw new Error(result.message || 'Failed to remove item');
            }

            this.removeItemFromUI(item);
            this.updateCartTotals(result.itemCount, result.cartTotal);

        } catch (error) {
            console.error('Error:', error);
            this.showErrorMessage(item, 'Error removing item');
        }
    }

    updateSizeOptions(select, sizes, selectedSize) {
        select.innerHTML = '';
        sizes.forEach(size => {
            const option = document.createElement('option');
            option.value = size.Taglia;
            option.textContent = size.Taglia;
            option.selected = size.Taglia === selectedSize;
            select.appendChild(option);
        });
    }

    removeItemFromUI(item) {
        item.remove();
        if (document.querySelectorAll('.cart-items li').length === 0) {
            location.reload();
        }
    }

    async updateCartItem(item, changes) {
        try {
            const data = new FormData();
            data.append('productId', item.dataset.productId);
            
            if (changes.color) {
                data.append('updateColor', item.dataset.productId);
                data.append('oldColor', item.dataset.color);
                data.append('newColor', changes.color);
                data.append('size', changes.size || item.dataset.size);
            }
            if (changes.size) {
                data.append('updateSize', item.dataset.productId);
                data.append('oldSize', item.dataset.size);
                data.append('oldColor', item.dataset.color);
                data.append('newSize', changes.size);
                data.append('color', changes.color || item.dataset.color);
            }
            if (changes.quantity) {
                data.append('adjustQuantity', item.dataset.productId);
                data.append('quantity', changes.quantity);
                data.append('color', item.dataset.color);
                data.append('size', item.dataset.size);
            }

            const response = await fetch('cart_handler.php', {
                method: 'POST',
                body: data
            });

            const result = await response.json();
            if (!result.success) {
                throw new Error(result.message || 'Failed to update cart');
            }

            // Update item dataset with new values
            if (changes.color) item.dataset.color = changes.color;
            if (changes.size) item.dataset.size = changes.size;

            // Update cart totals if provided
            if (result.itemCount !== undefined && result.cartTotal !== undefined) {
                this.updateCartTotals(result.itemCount, result.cartTotal);
            }

        } catch (error) {
            console.error('Error:', error);
            this.showErrorMessage(item, 'Error updating cart');
        }
    }

    updateCartTotals(itemCount, total) {
        // Update cart total display
        const totalElement = document.querySelector('.cart-total');
        if (totalElement) {
            totalElement.textContent = `€${total.toFixed(2)}`;
        }

        // Update shipping message if exists
        const shippingMessage = document.querySelector('.warning-free-shipping p');
        if (shippingMessage) {
            if (total >= 100) {
                shippingMessage.textContent = 'You qualify for FREE STANDARD SHIPPING!';
            } else {
                shippingMessage.textContent = `Just €${(100 - total).toFixed(2)} away from getting FREE STANDARD SHIPPING`;
            }
        }
    }

    showErrorMessage(item, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        
        // Remove existing error messages
        const existingError = item.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }
        
        item.appendChild(errorDiv);
        setTimeout(() => errorDiv.remove(), 3000);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new CartHandler();
});