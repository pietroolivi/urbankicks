const steps = ['summary', 'shipping', 'payment'];
let currentStep = 0;

document.addEventListener('DOMContentLoaded', function() {
    // Hide all sections initially
    document.querySelectorAll('.checkout-step').forEach(section => {
        section.style.display = 'none';
    });

    // Show only summary section at start
    document.getElementById('summary-step').style.display = 'block';
    
    // Add event listeners to next buttons
    document.querySelectorAll('button[onclick^="nextStep"]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const nextStepId = this.getAttribute('onclick').match(/'([^']+)'/)[1];
            validateAndProceed(nextStepId);
        });
    });
});

document.getElementById('promo-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const code = document.getElementById('promo-code-input').value;
    const messageEl = document.getElementById('promo-message');
    
    try {
        const response = await fetch('api/check_promo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `code=${encodeURIComponent(code)}`
        });
        
        const data = await response.json();
        
        if (data.valid) {
            const subtotal = parseFloat(document.getElementById('subtotal').textContent);
            const discount = data.type === 'PERCENTAGE' ? 
                (subtotal * data.value / 100) : 
                data.value;
                
            document.getElementById('discount').textContent = discount.toFixed(2);
            document.getElementById('discount-row').style.display = 'block';
            document.getElementById('new-subtotal').textContent = 
                (subtotal - discount).toFixed(2);
            
            messageEl.textContent = 'Promo code applied successfully!';
            messageEl.style.color = 'green';
        } else {
            messageEl.textContent = 'Invalid promo code';
            messageEl.style.color = 'red';
        }
    } catch (error) {
        messageEl.textContent = 'Error checking promo code';
        messageEl.style.color = 'red';
    }
});

function validateAndProceed(nextStep) {
    let isValid = true;
    
    if (currentStep === 1) { // Validate shipping form
        const shippingForm = document.getElementById('shipping-form');
        isValid = shippingForm.checkValidity();
        if (!isValid) {
            shippingForm.reportValidity();
            return;
        }
    }
    
    if (isValid) {
        showSection(nextStep);
    }
}

function showSection(step) {
    // Hide current section
    document.getElementById(`${steps[currentStep]}-step`).style.display = 'none';
    
    // Show next section
    const nextIndex = steps.indexOf(step);
    document.getElementById(`${step}-step`).style.display = 'block';
    
    // Update progress bar and title
    const progressBar = document.getElementById('progress-bar');
    const stepTitle = document.getElementById('step-title');
    
    switch(step) {
        case 'shipping':
            progressBar.src = 'CSS/Images/Illustrations/progress_bar2.svg';
            stepTitle.textContent = 'SHIPPING';
            break;
        case 'payment':
            progressBar.src = 'CSS/Images/Illustrations/progress_bar3.svg';
            stepTitle.textContent = 'PAYMENT';
            break;
        default:
            progressBar.src = 'CSS/Images/Illustrations/progress_bar1.svg';
            stepTitle.textContent = 'ORDER SUMMARY';
    }
    
    currentStep = nextIndex;
}