class CartHandler {
    constructor() {
        this.setupEventListeners();
        this.initQuantityControls();
    }

    initQuantityControls() {
        document.querySelectorAll('.quantity-control').forEach(control => {
            const increment = control.querySelector('.increment');
            const decrement = control.querySelector('.decrement');
            const display = control.querySelector('.quantity-display');
            const input = control.nextElementSibling; // Hidden input

            increment.addEventListener('click', () => this.handleQuantityButton(1, display, input));
            decrement.addEventListener('click', () => this.handleQuantityButton(-1, display, input));
        });
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
        const quantityInput = item.querySelector('.quantity-selector');
        const quantityDisplay = item.querySelector('.quantity-display');
        const currentQuantity = parseInt(quantityInput.value);
        
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

            // Show alert if quantity was greater than 1
            if (currentQuantity > 1) {
                const alertElement = document.createElement('div');
                alertElement.className = 'alert alert-info';
                alertElement.textContent = 'Color changed: Quantity has been reset to 1';
                alertElement.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 15px;
                    background-color: #f8f9fa;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                    z-index: 1000;
                `;
                
                document.body.appendChild(alertElement);
                
                // Remove alert after 3 seconds
                setTimeout(() => {
                    alertElement.remove();
                }, 3000);
            }

            // Always reset quantity to 1 when color changes
            quantityInput.value = 1;
            quantityDisplay.textContent = '1';

            if (!currentSizeAvailable || !currentSize) {
                quantityInput.disabled = true;
            } else {
                quantityInput.disabled = false;
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
        const quantityInput = item.querySelector('.quantity-selector');

        // Enable/disable quantity based on size selection
        if (!select.value || select.value === 'Select Size') {
            quantityInput.disabled = true;
            quantityInput.value = 1;
            return;
        }

        quantityInput.disabled = false;
        await this.checkAndUpdateQuantity(
            item,
            item.querySelector('.color-selector').value,
            select.value
        );

        if (item.querySelector('.color-selector').value !== 'Select size') {
            await this.updateCartItem(item, {
                size: select.value
            });
        }
    }


    async handleQuantityButton(change, display, input) {
        const currentValue = parseInt(input.value);
        const newValue = currentValue + change;
        const quantityControl = display.closest('.quantity-control');
        const maxQuantity = parseInt(quantityControl.dataset.maxQuantity);
        
        // Don't allow less than 1 or more than max
        if (newValue < 1 || newValue > maxQuantity) return;
    
        // Update display and hidden input
        display.textContent = newValue;
        input.value = newValue;
    
        // Update cart
        await this.updateCartItem(input.closest('li'), {
            quantity: newValue
        });
    }


    // Modify existing handleQuantityChange to work with hidden input
    async handleQuantityChange(input) {
        const item = input.closest('li');
        const display = item.querySelector('.quantity-display');
        
        display.textContent = input.value;
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

            // Get current quantity from the selector
            const quantitySelector = item.querySelector('.quantity-selector');
            const currentQuantity = parseInt(quantitySelector.value);
            
            if (changes.color && changes.size) {
                data.append('updateBoth', true);
                data.append('oldColor', item.dataset.color);
                data.append('oldSize', item.dataset.size);
                data.append('newColor', changes.color);
                data.append('newSize', changes.size);
            } else if (changes.size) {
                data.append('updateSize', true);
                data.append('oldSize', item.dataset.size);
                data.append('newSize', changes.size);
                data.append('color', item.dataset.color);
                data.append('quantity', currentQuantity);
            } else if (changes.color) {
                data.append('updateColor', true);
                data.append('oldColor', item.dataset.color);
                data.append('newColor', changes.color);
                data.append('size', item.dataset.size);
                data.append('quantity', currentQuantity);
            } else if (changes.quantity) {
                data.append('adjustQuantity', true);
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
            if (changes.quantity) item.dataset.quantity = changes.quantity;
    
            if (result.itemCount !== undefined && result.cartTotal !== undefined) {
                this.updateCartTotals(result.itemCount, result.cartTotal);
            }
        } catch (error) {
            console.error('Error:', error);
            this.showErrorMessage(item, error.message);
        }
    }

    async checkAndUpdateQuantity(item, color, size) {
        const data = new FormData();
        data.append('getQuantity', true);
        data.append('productId', item.dataset.productId);
        data.append('color', color);
        data.append('size', size);

        console.log('Checking quantity:', color, size);

        try {
            const response = await fetch('cart_handler.php', {
                method: 'POST',
                body: data
            });
            const result = await response.json();
            
            if (result.success) {
                const quantityInput = item.querySelector('.quantity-selector');
                const currentQty = parseInt(quantityInput.value);
                const maxQty = result.quantity;
                
                quantityInput.max = maxQty;
                
                if (currentQty > maxQty) {
                    quantityInput.value = maxQty;
                    await this.updateCartItem(item, {
                        quantity: maxQty
                    });
                }
            }
        } catch (error) {
            console.error('Error checking quantity:', error);
        }
    }

    updateCartTotals(itemCount, total) {
        // Update cart total display
        const totalElement = document.querySelector('.cart-total-container .cart-total');
        if (!totalElement) {
            console.error('Cart total element not found');
            return;
        }

        totalElement.textContent = `€${total.toFixed(2)}`;

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