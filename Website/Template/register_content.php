<fieldset class="choice-login-registration">
    <legend>Please select whether you want to log in (if you already have an account) or register:</legend>
    <input type="radio" id="login" name="authentication" value="login"><label for="login">LOGIN</label><input type="radio" id="register" name="authentication" value="register" checked="checked"><label for="register">REGISTER</label>
</fieldset>
<form id="registration-form" action="#" method="POST">
    <section id="section-register">
        <h2>Create Account</h2>
        <p>Be a part of the newest fashion trends. Urbankicks' sneakers make heads turn.</p>
        <ul class="authentication-inputs">
            <li class="authentication-typed-label-input">
                <label class="authentication-typed-label" for="firstname-register">First Name</label>
                <input class="authentication-typed-input" type="text" id="firstname-register" name="firstname-register" required>
            </li>
            <li class="authentication-typed-label-input">
                <label class="authentication-typed-label" for="lastname-register">Last Name</label>
                <input class="authentication-typed-input" type="text" id="lastname-register" name="lastname-register" required>
            </li>
            <li class="authentication-typed-label-input">
                <label class="authentication-typed-label" for="email-register">Email address</label>
                <input class="authentication-typed-input" type="email" id="email-register" name="email-register" required>
                <p id="email-error" class="error-message"></p>
            </li>
            <li class="authentication-typed-label-input">
                <label class="authentication-typed-label" for="password-register">Password</label>
                <input class="authentication-typed-input" type="password" id="password-register" name="password-register" autocomplete="new-password" required>
                <p id="password-error" class="error-message"></p>
            </li>
            <li class="authentication-typed-label-input">
                <label class="authentication-typed-label" for="confirm-password-register">Confirm Password</label>
                <input class="authentication-typed-input" type="password" id="confirm-password-register" name="confirm-password-register" autocomplete="new-password" required>
                <p id="confirm-password-error" class="error-message"></p>
            </li>
            <li class="authentication-typed-label-input">
                <label class="authentication-typed-label" for="phone-register">Mobile Phone</label>
                <input class="authentication-typed-input" type="tel" id="phone-register" name="phone-register" autocomplete="tel-national">
                <p id="phone-error" class="error-message"></p>
                <script>
                    window.addEventListener("resize", adjustTelInputPadding);
                    const phoneInputField = document.querySelector("#phone-register");
                    const phoneInput = window.intlTelInput(phoneInputField, {
                        utilsScript:
                        "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                    });

                    /* ***************************************************************** */
                    /* We need to make the position of the first digit of the phone      */
                    /* number succeed the flag icon from which the drop-down menu opens. */
                    /* ***************************************************************** */
                    function adjustTelInputPadding () {
                        let flagContainerTag = document.getElementsByClassName("iti__flag-container")[0];
                        console.log(flagContainerTag);
                        let newPaddingLeftTelInput = Number(flagContainerTag.offsetWidth) * 1.5;
                        document.getElementById("phone-register").style["padding-left"] = newPaddingLeftTelInput + "px";
                    }
                </script>
            </li>
            <li class="authentication-checked-label-input">
                <label class="authentication-checked-label" for="newsletter-register">I agree to subscribe to the newsletter</label>
                <input class="authentication-checked-input" type="checkbox" id="newsletter-register" name="newsletter-register">
            </li>
            <li class="authentication-checked-label-input">
                <label class="authentication-checked-label" for="terms-privacy-register">I agree to Urbankicks' <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a></label>
                <input class="authentication-checked-input" type="checkbox" id="terms-privacy-register" name="terms" required>
            </li>
            <li>
                <button class="full-button-black" type="submit" id="register-button">Register</button>
                <p id="form-error" class="error-message"></p>
            </li>
        </ul>
    </section>
</form>