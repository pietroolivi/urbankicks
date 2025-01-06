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

        // Password reset flow
        this.initializePasswordResetListeners();
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
        const registerSection = document.getElementById('section-login');
    
        // Force register radio selection on register.php
        if (window.location.pathname.includes('register.php')) {
            registerRadio.checked = true;
            loginRadio.checked = false;
            registerSection.style.display = 'block';
        }

        registerRadio.addEventListener('change', (e) => {
            if (e.target.checked) {
                window.location.href = 'register.php';
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

    initializePasswordResetListeners() {
        // Step 1: Email verification
        const forgotForm = document.getElementById('pswd-recovery-mail');
        forgotForm?.addEventListener('submit', (e) => this.handleForgotSubmit(e));

        // Step 2: Code verification
        const otpForm = document.getElementById('pswd-recovery-otp');
        otpForm?.addEventListener('submit', (e) => this.handleCodeVerification(e));

        // Step 3: New password
        const newPasswordForm = document.getElementById('pswd-recovery-new');
        newPasswordForm?.addEventListener('submit', (e) => this.handlePasswordReset(e));

        // OTP input handlers
        this.initializeOTPInputs();
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

    async handleForgotSubmit(e) {
        e.preventDefault();
        const email = document.getElementById('email-forgot1').value;
        
        try {
            const response = await fetch('login_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `email-login=${encodeURIComponent(email)}&generate_reset_code=true`
            });
            const data = await response.json();
            
            if (data.success) {
                this.switchSection('section-forgot2');
                document.getElementById('email-forgot2').value = email;
            } else {
                this.showError('email-forgot-error', data.message);
            }
        } catch (error) {
            this.showError('email-forgot-error', 'Error sending reset code');
        }
    }

    initializeOTPInputs() {
        const inputs = document.querySelectorAll('.otp-input');
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
            
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });
    }

    async handleCodeVerification(e) {
        e.preventDefault();
        const inputs = document.querySelectorAll('.otp-input');
        const code = Array.from(inputs).map(input => input.value).join('');
        const email = document.getElementById('email-forgot2').value;

        try {
            const response = await fetch('login_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `email-login=${encodeURIComponent(email)}&reset_code=${code}`
            });
            const data = await response.json();
            
            if (data.success) {
                this.switchSection('section-forgot3');
            } else {
                this.showError('code-forgot-error', 'Incorrect code!');
            }
        } catch (error) {
            this.showError('code-forgot-error', 'Error verifying code');
        }
    }

    async handlePasswordReset(e) {
        e.preventDefault();
        const email = document.getElementById('email-forgot2').value;
        const password = document.getElementById('password-forgot3').value;
        const confirmPassword = document.getElementById('confirm-password-forgot3').value;

        if (password !== confirmPassword) {
            this.showError('password-error', 'Passwords do not match');
            return;
        }

        try {
            const response = await fetch('login_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `email-login=${encodeURIComponent(email)}&new_password=${encodeURIComponent(password)}`
            });
            const data = await response.json();
            
            if (data.success) {
                window.location.href = 'login.php';
            } else {
                this.showError('password-error', data.message);
            }
        } catch (error) {
            this.showError('password-error', 'Error resetting password');
        }
    }

    switchSection(sectionId) {
        document.getElementById(this.currentSection).style.display = 'none';
        document.getElementById(sectionId).style.display = 'block';
        this.currentSection = sectionId;
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