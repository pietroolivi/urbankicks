
// //------------------------------------------------Setting all the listeners----------------------------------------------------------
// //____________Listener For the "no account associated" attached to the email input_______________________
// let inputEmail = "email-login"; //from login.html
// let paragraphWarning = "email-login-error"; //where it is?
// let apiPHPfile="login_handler.php";
// let bodyPrefixName = "email-login";// PHP: given this in the $_POST return me a boolean if it exist in the emails
// let typeOfEvent="blur";
// const listenerJsonEmailWarning = new ListenerJsonDataExpected("exists", true);
// const listenerEmailSetting = new listenerObjectSetting( "No Account associated", "red");

// eventListenerFormLoginWarning(inputEmail,paragraphWarning,typeOfEvent,apiPHPfile,bodyPrefixName,
//     listenerEmailSetting,listenerJsonEmailWarning);

// inputEmail="email-forgot1";
// let buttonForgot="submit2";
// paragraphWarning="email-forgot-error";
// typeOfEvent="click";
// eventListenerFormInputButtonWarning(inputEmail,buttonForgot,paragraphWarning,typeOfEvent,apiPHPfile,bodyPrefixName,
//         listenerEmailSetting,listenerJsonEmailWarning);
         
// /*
// //____________Listener For the "no account associated" attached to the password input_____________________
// const inputPassword = "password-login"; //from login.html
// paragraphWarning = "pswd-error-login"; //where it is?
// bodyPrefixName = "passwordinsert";// PHP: given this in the $_POST return me a boolean if it exist in the emails
// typeOfEvent="blur";
// const listenerJsonPassWarning = new ListenerJsonDataExpected("exists", true);
// const listenerPasswordSetting = new listenerObjectSetting( "No Account associated", "red");

// eventListenerFormLoginWarning(inputEmail,paragraphWarning,typeOfEvent,apiPHPfile,bodyPrefixName,
//     listenerEmailSetting,listenerJsonEmailWarning);
// */


// /*************************** */
// /*NOT NEEDED FROM JAVASCRIPT**
// /*********************** */
// /*
// //____________Listener for the incorrect code in the paragraph attached to the recover code input_______________________
// const inputCode = "codeForm"; // not needed, html should already send the code with the submit, need to fix the function I use to
//                             //not send the code wrote by the user
// const buttonVerify="submit-code"; //from password-forgotten2.html
// paragraphWarning = "codeFormWarning";
// bodyPrefixName = "reset_code = ";
// typeOfEvent="click";
// const listenerJsonCodeWarning = new ListenerJsonDataExpected("message", "Password updated successfully");
// const listenerCodeErrorSetting = new listenerObjectSetting("Incorrect Code","red");
// //in this case the server may not need to query the database(the password reset code may not be modeled in the db)
// // but it should havein some associative array a cell that is created when the JS code tells it to load the insert code structure
// const preloadEventListenerFormInputWarning = () =>{  //TODO: valutare come gestire il feedback di codice inserito correttamente
//                                                     //potrebbe aver senso non mostrarlo per non chiara specifica dai mockup.
//     eventListenerFormInputButtonWarning(inputCode,buttonVerify, paragraphWarning,typeOfEvent, apiPHPfile, bodyPrefixName,
//         listenerCodeErrorSetting,listenerJsonCodeWarning); 
// }

// //__________Async function added to the recover password button to make the server generate a reset code.
// const listenerJsonCodeReceivedErrorSetting = new ListenerJsonDataExpected("Error while sending the code","red");
// const optionalAsyncCallback = preloadSendAppendedHTMLIsReady(
//             apiPHPfile,
//             true,
//             "generate_reset_code = ",
//             paragraphWarning,
//             listenerJsonCodeSetting,
//             null
//           );
// */
// //____________Listener for the recover password button________________________________________________

// //this listener has as the parameter additionalListener the array defined on the previous istener definition
// //since the incorrect code listener is added only when the incorrect code html and logic is enabled in thje document. 
// //the idHTMLStructure It's hidden until forgot button is clicked.
// buttonForgot = "forgot-password-link"; //from login.html
// let idHTMLStructure1="section-forgot1"; // from password_forgotten.html. see eventListenerAppendHTML comments in JS/Functions/listeners.js
// let idHTMLStructureLogin="section-login";
// //let additionalListeners = [preloadEventListenerFormInputWarning];
// eventListenerAppendHTML(buttonForgot,idHTMLStructure,null,null,null);
// buttonForgot = "submit-forgot1";
// let idHTMLStructure2="section-forgot2";
// eventListenerAppendHTML(buttonForgot,idHTMLStructure,null,null,idHTMLStructure1);
// buttonForgot = "submit-forgot2";
// let idHTMLStructure3="section-forgot3";
// eventListenerAppendHTML(buttonForgot,idHTMLStructure,null,null, idHTMLStructure2);

// //____________Listener For the "passwords don't match" attached to the new password confirmation_______________________
// let inputPassword = "password"; //from register2.html
// const inputPassword2 = "confirm-password"; //from register2.html
// paragraphWarning = "passWarning";
// const listenerPassMatch = new listenerObjectSetting( "Passwords don't match", "red");
// eventListenerFormInputComparisonWarning(inputPassword,inputPassword2,paragraphWarning,listenerPassMatch);


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
            this.showError('email-login-error', '');
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
                this.showError('email-login-error', 'No account associated!');
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
                window.location.href = 'index.php'; // Redirect to dashboard
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