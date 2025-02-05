class RegistrationHandler {
    constructor() {
        this.passwordRules = {
            length: (pwd) => pwd.length >= 8 && pwd.length <= 20,
            alphanumeric: (pwd) => /[a-zA-Z]/.test(pwd) && /\d/.test(pwd),
            special: (pwd) => /[!"#$%&'()*+,-./:;<=>?]/.test(pwd),
        };
        this.phonePattern = /[1-9]\d{1,14}$/;
        this.submitButton = document.getElementById('register-button');
        this.submitButton.disabled = true;
        this.initializeListeners();
        this.initializeRadioListeners();
    }

    initializeListeners() {
        // Step 1: Email validation
        const emailInput = document.getElementById('email-register');
        emailInput.addEventListener('blur', () => this.validateEmail(emailInput.value));

        // Step 2: Password validation
        const passwordInput = document.getElementById('password-register');
        const confirmPasswordInput = document.getElementById('confirm-password-register');
        passwordInput.addEventListener('input', () => this.validatePassword(passwordInput.value));
        confirmPasswordInput.addEventListener('input', () => this.validatePasswordMatch(
            passwordInput.value, confirmPasswordInput.value
        ));

        // Add phone validation
        const phoneInput = document.getElementById('phone-register');
        phoneInput.addEventListener('blur', () => this.validatePhone(phoneInput.value ? phoneInput.value : ""));

        // Add validation check on any form field change
        const form = document.getElementById('registration-form');
        form.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', () => this.validateAll());
        });

        // Form submission
        document.getElementById('registration-form').addEventListener('submit', (e) => 
            this.handleSubmit(e)
        );
    }

    initializeRadioListeners() {
        const loginRadio = document.getElementById('login');
        const registerRadio = document.getElementById('register');
        const registerSection = document.getElementById('section-register');
    
        // Force register radio selection on register.php
        if (window.location.pathname.includes('register.php')) {
            registerRadio.checked = true;
            loginRadio.checked = false;
            registerSection.style.display = 'block';
        }
    
        // Handle login radio change
        loginRadio.addEventListener('change', (e) => {
            if (e.target.checked) {
                window.location.href = 'login.php';
            }
        });
    
        // Handle browser back button
        window.addEventListener('pageshow', (event) => {
            if (event.persisted || window.performance?.navigation.type === 2) {
                registerRadio.checked = true;
                loginRadio.checked = false;
                registerSection.style.display = 'block';
            }
        });
    }

    async validateEmail(email) {
        if (!email) {
            this.clearError('email-error');
            return false;
        }

        try {
            const response = await fetch('register_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `emailinsert=${encodeURIComponent(email)}&check_email_only=true`
            });
            const data = await response.json();
            
            if (!data.success) {
                this.showError('email-error', data.message);
                return false;
            }
            this.clearError('email-error');
            return true;
        } catch (error) {
            this.showError('email-error', 'Error checking email');
            return false;
        }
    }

    validatePassword(password) {
        if (!password) {
            this.clearError('password-error');
            return false;
        }

        let isValid = true;
        const errors = [];

        if (!this.passwordRules.length(password)) {
            errors.push('◦ Password must be 8-20 characters long');
            isValid = false;
        }
        if (!this.passwordRules.alphanumeric(password)) {
            errors.push('◦ Password must contain both letters and numbers');
            isValid = false;
        }
        if (!this.passwordRules.special(password)) {
            errors.push('◦ Password must contain at least one special character');
            isValid = false;
        }

        if (!isValid) {
            this.showError('password-error', errors.join('<br>'));
        } else {
            this.clearError('password-error');
        }
        return isValid;
    }

    validatePasswordMatch(password, confirmPassword) {
        if (!password || !confirmPassword) {
            this.clearError('confirm-password-error');
            return false;
        }

        if (password !== confirmPassword) {
            this.showError('confirm-password-error', 'Passwords do not match');
            return false;
        }
        this.clearError('confirm-password-error');
        return true;
    }

    validatePhone(phone) {
        if (!phone) {
            this.clearError('phone-error');
            return true; // Phone is optional
        }

        if (!this.phonePattern.test(phone)) {
            this.showError('phone-error', 'Please enter a valid mobile phone number');
            return false;
        }
        this.clearError('phone-error');
        return true;
    }

    showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        errorElement.innerHTML = message;
        errorElement.style.display = 'block';
        errorElement.style.color = 'red';
    }

    clearError(elementId) {
        const errorElement = document.getElementById(elementId);
        errorElement.style.display = 'none';
    }

    async validateAll() {
        const emailValid = await this.validateEmail(document.getElementById('email-register').value);
        
        const pwd = document.getElementById('password-register').value;
        const confirmPwd = document.getElementById('confirm-password-register').value;
        const passwordValid = this.validatePassword(pwd) && 
                            this.validatePasswordMatch(pwd, confirmPwd);
        
        const phone = document.getElementById('phone-register').value;
        const phoneValid = this.validatePhone(phone);

        const isValid = emailValid && passwordValid && phoneValid;
        this.submitButton.disabled = !isValid;
        return isValid;
    }

    async handleSubmit(e) {
        e.preventDefault();
        if (await this.validateAll()) {
            const formData = new FormData(e.target);
            try {
                const response = await fetch('register_handler.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    window.location.href = 'login.php';
                } else {
                    this.showError('form-error', data.message);
                }
            } catch (error) {
                this.showError('form-error', 'Registration failed. Please try again.');
            }
        }
    }
}

// Initialize registration handler
document.addEventListener('DOMContentLoaded', () => {
    new RegistrationHandler();
});