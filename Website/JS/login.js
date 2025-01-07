class LoginHandler {
    constructor() {
        this.currentSection = 'section-login';
        this.submitButton = document.getElementById('login-submit');
        this.initializeListeners();
        this.initializeRadioListeners();
    }

    initializeListeners() {
        // Email validation
        const emailInput = document.getElementById('email-login');
        emailInput.addEventListener('blur', () => this.validateEmail(emailInput.value));

        // Login form submission
        document.getElementById('access-credentials').addEventListener('submit', (e) => 
            this.handleLoginSubmit(e)
        );
    }

    initializeRadioListeners() {
        const registerRadio = document.getElementById('register');
        
        registerRadio.addEventListener('change', (e) => {
            if (e.target.checked) {
                window.location.href = 'register.php';
            }
        });
    }

    initializeRadioListeners() {
        const loginRadio = document.getElementById('login');
        const registerRadio = document.getElementById('register');
        const loginSection = document.getElementById('section-login');
    
        // Force register radio selection on register.php
        if (window.location.pathname.includes('register.php')) {
            registerRadio.checked = true;
            loginRadio.checked = false;
            loginSection.style.display = 'block';
        }

        registerRadio.addEventListener('change', (e) => {
            if (e.target.checked) {
                window.location.href = 'register.php';
            }
        });
    
        // Handle browser back button
        window.addEventListener('pageshow', (event) => {
            if (event.persisted || window.performance?.navigation.type === 2) {
                loginRadio.checked = true;
                registerRadio.checked = false;
                loginSection.style.display = 'block';
            }
        });
    }

    async validateEmail(email) {
        if (!email) {
            this.clearError('email-login-error');
            return false;
        }

        try {
            const response = await fetch('login_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `email-login=${encodeURIComponent(email)}&check_email_only=true`
            });
            const data = await response.json();
            
            if (!data.success) {
                this.showError('email-login-error', data.message);
                return false;
            }
            this.clearError('email-login-error');
            return true;
        } catch (error) {
            this.showError('email-login-error', 'Error checking email');
            return false;
        }
    }

    async handleLoginSubmit(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        
        try {
            const response = await fetch('login_handler.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            
            if (data.success) {
                window.location.href = 'index.html';
            } else {
                this.showError('email-login-error', data.message);
            }
        } catch (error) {
            this.showError('email-login-error', 'Login failed. Please try again.');
        }
    }

    showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        errorElement.textContent = message;
        errorElement.style.display = 'block';
        errorElement.style.color = 'red';
    }

    clearError(elementId) {
        const errorElement = document.getElementById(elementId);
        errorElement.style.display = 'none';
    }
}

// Initialize login handler
document.addEventListener('DOMContentLoaded', () => {
    new LoginHandler();
});