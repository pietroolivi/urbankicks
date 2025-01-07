<fieldset>
    <legend>Please select whether you want to log in (if you already have an account) or register:</legend>
    <input type="radio" id="login" name="authentication" value="login"><label for="login">LOGIN</label>
    <input type="radio" id="register" name="authentication" value="register" checked="checked"><label for="register">REGISTER</label>
</fieldset>
<form id="registration-form" action="#" method="POST">
    <section id="section-register">
        <h2>Create Account</h2>
        <ul>
            <li>
                <label for="firstname-register">First Name</label>
                <input type="text" id="firstname-register" name="firstname-register" required>
            </li>
            <li>
                <label for="lastname-register">Last Name</label>
                <input type="text" id="lastname-register" name="lastname-register" required>
            </li>
            <li>
                <label for="email-register">Email address</label>
                <input type="email" id="email-register" name="email-register" required>
                <p id="email-error" class="error-message"></p>
            </li>
            <li>
                <label for="password-register">Password</label>
                <input type="password" id="password-register" name="password-register" autocomplete="new-password" required>
                <p id="password-error" class="error-message"></p>
            </li>
            <li>
                <label for="confirm-password-register">Confirm Password</label>
                <input type="password" id="confirm-password-register" name="confirm-password-register" autocomplete="new-password" required>
                <p id="confirm-password-error" class="error-message"></p>
            </li>
            <li>
                <label for="phone-register">Mobile Phone</label>
                <input type="tel" id="phone-register" name="phone-register" autocomplete="tel-national">
                <p id="phone-error" class="error-message"></p>
                <script>
                    const phoneInputField = document.querySelector("#phone-register");
                    const phoneInput = window.intlTelInput(phoneInputField, {
                        utilsScript:
                        "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                    });
                    console.log("---------------------------------");
                    console.log("NUMERO GENERATO DALLO SCRIPT JS: ");
                    console.log(phoneInput);
                    console.log("---------------------------------");
                </script>
            </li>
            <li>
                <label for="newsletter-register">I agree to subscribe to the newsletter.</label>
                <input type="checkbox" id="newsletter-register" name="newsletter-register">
            </li>
            <li>
                <label for="terms-privacy-register">I agree to Urbankicks' <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>.</label>
                <input type="checkbox" id="terms-privacy-register" name="terms" required>
            </li>
            <li>
                <button type="submit" id="register-button">Register</button>
                <p id="form-error" class="error-message"></p>
            </li>
        </ul>
    </section>
</form>