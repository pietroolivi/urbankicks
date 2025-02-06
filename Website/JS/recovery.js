class RecoveryHandler {
    constructor() {
        this.currentSection = 'section-forgot1';
        document.getElementById('section-forgot1').style.display = 'block';
        this.passwordRules = {
            length: (pwd) => pwd.length >= 8 && pwd.length <= 20,
            alphanumeric: (pwd) => /[a-zA-Z]/.test(pwd) && /\d/.test(pwd),
            special: (pwd) => /[!"#$%&'()*+,-./:;<=>?]/.test(pwd),
        };
        this.sectionOrder = ['section-forgot1', 'section-forgot2', 'section-forgot3'];
        this.initializeListeners();
    }

    initializeListeners() {
        // Step 1: Email verification
        const emailForm = document.getElementById('pswd-recovery-mail');
        emailForm?.addEventListener('submit', (e) => this.handleEmailSubmit(e));

        // Step 2: OTP verification
        const otpForm = document.getElementById('pswd-recovery-otp');
        otpForm?.addEventListener('submit', (e) => this.handleOTPSubmit(e));

        // Step 3: New password
        const passwordForm = document.getElementById('pswd-recovery-new');
        passwordForm?.addEventListener('submit', (e) => this.handlePasswordSubmit(e));

        // Initialize OTP input handling
        this.initializeOTPInputs();

        // Email validation on blur
        const emailInput = document.getElementById('email-forgot1');
        emailInput?.addEventListener('blur', () => this.validateEmail(emailInput.value));

        // Add back button listeners
        const backButtons = document.querySelectorAll('button[id^="back-forgot"]');
        backButtons.forEach(button => {
            button.addEventListener('click', () => this.handleBackNavigation());
        });
    }

    async validateEmail(email) {
        if (!email) {
            this.clearError('email-forgot-error');
            return false;
        }

        try {
            const response = await fetch('recovery_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `email-recovery=${encodeURIComponent(email)}&check_email_only=true`
            });
            const data = await response.json();
            
            if (!data.success) {
                this.showError('email-forgot-error', data.message);
                return false;
            }
            this.clearError('email-forgot-error');
            return true;
        } catch (error) {
            this.showError('email-forgot-error', 'Error checking email');
            return false;
        }
    }

    validatePassword(password) {
        if (!password) {
            return false;
        }

        let isValid = true;
        const errors = [];

        if (!this.passwordRules.length(password)) {
            errors.push('* Password must be 8-20 characters long');
            isValid = false;
        }
        if (!this.passwordRules.alphanumeric(password)) {
            errors.push('* Password must contain both letters and numbers');
            isValid = false;
        }
        if (!this.passwordRules.special(password)) {
            errors.push('* Password must contain at least one special character');
            isValid = false;
        }

        return { isValid, errors };
    }

    async handleEmailSubmit(e) {
        e.preventDefault();
        const email = document.getElementById('email-forgot1').value;
        
        if (await this.validateEmail(email)) {
            try {
                const response = await fetch('recovery_handler.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `email-recovery=${encodeURIComponent(email)}&send_otp=true`
                });
                const data = await response.json();
                
                if (data.success) {
                    const emailInput2 = document.getElementById('email-forgot2');
                    emailInput2.value = email;
                    emailInput2.readOnly = true;
                    this.switchSection('section-forgot2');
                } else {
                    this.showError('email-forgot-error', data.message);
                }
            } catch (error) {
                this.showError('email-forgot-error', 'Error sending verification code');
            }
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

    async handleOTPSubmit(e) {
        e.preventDefault();
        const inputs = document.querySelectorAll('.otp-input');
        const otp = Array.from(inputs).map(input => input.value).join('');
        const email = document.getElementById('email-forgot2').value;

        try {
            const response = await fetch('recovery_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `email-recovery=${encodeURIComponent(email)}&otp=${otp}&verify_otp=true`
            });
            const data = await response.json();
            
            if (data.success) {
                this.switchSection('section-forgot3');
            } else {
                this.showError('code-forgot-error', data.message);
            }
        } catch (error) {
            this.showError('code-forgot-error', 'Error verifying code');
        }
    }

    async handlePasswordSubmit(e) {
        e.preventDefault();
        const email = document.getElementById('email-forgot2').value;
        const password = document.getElementById('password-forgot3').value;
        const confirmPassword = document.getElementById('confirm-password-forgot3').value;

        if (password !== confirmPassword) {
            this.showError('password-forgot-error', 'Passwords do not match');
            return;
        }

        const { isValid, errors } = this.validatePassword(password);
        if (!isValid) {
            this.showError('password-forgot-error', errors.join('<br>'));
            return;
        }

        try {
            const response = await fetch('recovery_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&update_password=true`
            });
            const data = await response.json();
            
            if (data.success) {
                window.location.href = 'login.php';
            } else {
                this.showError('password-forgot-error', data.message);
            }
        } catch (error) {
            this.showError('password-forgot-error', 'Error updating password');
        }
    }

    handleBackNavigation() {
        const currentIndex = this.sectionOrder.indexOf(this.currentSection);
        if (currentIndex > 0) {
            this.switchSection(this.sectionOrder[currentIndex - 1]);
        }
    }

    switchSection(sectionId) {
        document.getElementById(this.currentSection).style.display = 'none';
        document.getElementById(sectionId).style.display = 'block';
        this.currentSection = sectionId;
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
}

// Initialize recovery handler
document.addEventListener('DOMContentLoaded', () => {
    new RecoveryHandler();
});