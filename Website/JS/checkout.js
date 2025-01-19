const steps = ['summary', 'shipping', 'payment'];
let currentStep = 0;

document.addEventListener('DOMContentLoaded', function() {
    // Hide all sections initially
    document.querySelectorAll('.checkout-step').forEach(section => {
        section.style.display = 'none';
    });

    // Show only summary section at start
    document.getElementById('summary-step').style.display = 'block';

    // Initialize values from cart
    const subtotalElement = document.getElementById('subtotal');
    const newSubtotalElement = document.getElementById('new-subtotal');
    const giftWrapCheckbox = document.getElementById('gift-wrap-checkbox');
    
    // Set initial subtotal from cart's Valore_Totale
    const cartTotal = document.querySelector('.products-overview').dataset.total;
    subtotalElement.textContent = parseFloat(cartTotal).toFixed(2);
    newSubtotalElement.textContent = parseFloat(cartTotal).toFixed(2);
    
    // Handle gift wrap changes
    giftWrapCheckbox.addEventListener('change', updateTotals);
    
    function updateTotals() {
        const subtotal = parseFloat(subtotalElement.textContent);
        const discount = document.getElementById('discount-row').style.display !== 'none' ? 
            parseFloat(document.getElementById('discount').textContent) : 0;
        const giftWrapCost = giftWrapCheckbox.checked ? 5.00 : 0;
        
        const newSubtotal = subtotal - discount + giftWrapCost;
        newSubtotalElement.textContent = newSubtotal.toFixed(2);
    }

    // Add payment method change listeners
    const paymentMethods = document.querySelectorAll('input[name="payment-method"]');
    paymentMethods.forEach(method => {
        method.addEventListener('change', (e) => {
            togglePaymentFields(e.target.value);
        });
    });

    // Initialize payment fields to credit card
    togglePaymentFields('credit-card');

    // Add payment form validation
    document.getElementById('payment-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const selectedMethod = document.querySelector('input[name="payment-method"]:checked').value;
        let isValid = true;
        
        if (selectedMethod === 'credit-card') {
            const requiredFields = ['card-holder', 'card-number', 'expiration-date', 'cvv'];
            requiredFields.forEach(field => {
                if (!document.getElementById(field).value) {
                    isValid = false;
                    showErrorMessage(document.getElementById(field), 'This field is required');
                }
            });
        } else {
            if (!document.getElementById('email-paypal').value) {
                isValid = false;
                showErrorMessage(document.getElementById('email-paypal'), 'PayPal email is required');
            }
        }
        
        if (isValid) {
            try {
                const total = document.getElementById('shipping-total').textContent;
                const response = await fetch('checkout_handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        paymentMethod: selectedMethod,
                        total: total
                    })
                });
                
                const result = await response.json();
                if (result.success) {
                    window.location.href = 'orders.php';
                } else {
                    showErrorMessage(this, result.message || 'Error placing order');
                }
            } catch (error) {
                showErrorMessage(this, 'Error processing order');
            }
        }
    });
});

let isDiscountApplied = false;

document.getElementById('promo-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const code = document.getElementById('promo-code-input').value;
    const messageEl = document.getElementById('promo-message');

    // Check if discount already applied
    if (isDiscountApplied) {
        showErrorMessage(this, 'Only one discount code can be applied');
        return;
    }
    
    try {
        const response = await fetch('checkout_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `code=${encodeURIComponent(code)}`
        });
        
        const data = await response.json();
        
        if (data.valid) {
            isDiscountApplied = true;
            const subtotal = parseFloat(document.getElementById('subtotal').textContent);
            const discount = data.type === 'Percentage' ? 
                (subtotal * data.value / 100) : 
                data.value;
                
            document.getElementById('discount').textContent = discount.toFixed(2);
            document.getElementById('discount-row').style.display = 'block';

            // Update totals considering gift wrap
            const giftWrapCost = document.getElementById('gift-wrap-checkbox').checked ? 5.00 : 0;
            document.getElementById('new-subtotal').textContent = 
                (subtotal - discount + giftWrapCost).toFixed(2);
            
            messageEl.textContent = 'Promo code applied successfully!';
            messageEl.style.color = 'green';
        } else {
            messageEl.textContent = 'Invalid promo code';
            messageEl.style.color = 'red';
        }
    } catch (error) {
        showErrorMessage(document.getElementById('promo-form'), 'Error checking promo code');
    }
});

function validateAndProceed(nextStep) {
    let isValid = true;
    
    if (currentStep === 1) { // Validate shipping form
        const shippingForm = document.getElementById('shipping-form');
        isValid = shippingForm.checkValidity();
        if (!isValid) {
            showErrorMessage(shippingForm, 'Please fill in all required fields');
            return;
        }
    }
    
    if (isValid) {
        showSection(nextStep);
    }
}

// Add event listeners for delivery options
document.querySelectorAll('input[name="delivery-options"]').forEach(radio => {
    radio.addEventListener('change', updateShippingTotals);
});

function updateShippingOptions() {
    const subtotal = parseFloat(document.getElementById('new-subtotal').textContent);
    const standardPaidOption = document.getElementById('standard-paid-option');
    const standardFreeOption = document.getElementById('standard-free-option');

    if (subtotal >= 200) {
        standardPaidOption.style.display = 'none';
        standardFreeOption.style.display = 'block';
        document.getElementById('shipping-standard-free').checked = true;
    } else {
        standardPaidOption.style.display = 'block';
        standardFreeOption.style.display = 'none';
        document.getElementById('shipping-standard-paid').checked = true;
    }

    updateShippingTotals();
}

function updateShippingTotals() {
    const summarySubtotal = parseFloat(document.getElementById('new-subtotal').textContent);
    const selectedDeliveryOption = document.querySelector('input[name="delivery-options"]:checked');
    const shippingCost = selectedDeliveryOption ? parseFloat(selectedDeliveryOption.dataset.cost) : 0;

    document.getElementById('shipping-subtotal').textContent = summarySubtotal.toFixed(2);
    document.getElementById('shipping-total').textContent = (summarySubtotal + shippingCost).toFixed(2);
}

function togglePaymentFields(method) {
    const creditCardFields = document.querySelector('.credit-card-fields');
    const paypalFields = document.querySelector('.paypal-fields');
    
    if (method === 'credit-card') {
        creditCardFields.style.display = 'block';
        paypalFields.style.display = 'none';
        
        // Set required fields
        creditCardFields.querySelectorAll('input').forEach(input => {
            input.required = true;
        });
        paypalFields.querySelectorAll('input').forEach(input => {
            input.required = false;
        });
    } else {
        creditCardFields.style.display = 'none';
        paypalFields.style.display = 'block';
        
        // Set required fields
        creditCardFields.querySelectorAll('input').forEach(input => {
            input.required = false;
        });
        paypalFields.querySelectorAll('input').forEach(input => {
            input.required = true;
        });
    }
}

// Update existing changePaymentMethod function
function changePaymentMethod(method) {
    document.querySelector("#" + method).checked = true;
    togglePaymentFields(method);
}

function showSection(step) {
    // Hide all sections
    document.querySelectorAll('.checkout-step').forEach(section => {
        section.style.display = 'none';
    });
    
    // Get next step index
    const nextIndex = steps.indexOf(step);
    if (nextIndex === -1) return;
    
    // Show selected section
    document.getElementById(`${step}-step`).style.display = 'block';
    
    // Update progress bar and title
    const progressBar = document.getElementById('progress-bar');
    const stepTitle = document.getElementById('step-title');
    
    switch(step) {
        case 'shipping':
            progressBar.src = 'CSS/Images/Illustrations/progress_bar2.svg';
            stepTitle.textContent = 'SHIPPING';
            updateShippingOptions();
            break;
        case 'payment':
            progressBar.src = 'CSS/Images/Illustrations/progress_bar3.svg';
            stepTitle.textContent = 'PAYMENT';
            // Update payment total when showing payment section
            const total = document.getElementById('new-subtotal').textContent;
            document.getElementById('payment-total').textContent = total;
            break;
        default:
            progressBar.src = 'CSS/Images/Illustrations/progress_bar1.svg';
            stepTitle.textContent = 'ORDER SUMMARY';
    }
    
    currentStep = nextIndex;
}

function showErrorMessage(item, message, timeout = 3000) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    
    // Remove existing error messages
    const existingError = item.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    item.appendChild(errorDiv);
    setTimeout(() => errorDiv.remove(), timeout);
}

function nextStep(step) {
    if (currentStep === 1) { // From shipping to payment
        if (!validateAndProceed('payment')) {
            return;
        }
        const total = document.getElementById('new-subtotal').textContent;
        showSection('payment');
        document.getElementById('payment-total').textContent = total;
    } else { // From summary to shipping
        if (!validateAndProceed('shipping')) {
            return;
        }
        showSection('shipping');
    }
}

function goBack() {
    if (currentStep === 0) {
        window.history.back();
    } else {
        const previousStep = steps[currentStep - 1];
        showSection(previousStep);
    }
}