<fieldset>
    <legend>Please select whether you want to log in (if you already have an account) or register:</legend>
    <input type="radio" id="login" name="authentication" value="login" checked="checked"><label for="login">LOGIN</label>
    <input type="radio" id="register" name="authentication" value="register"><label for="register">REGISTER</label>
</fieldset>
<section id="section-login">
    <h2>Welcome Back</h2>
    <p>Happy to see you again. Please Login Here</p>
    <form id="access-credentials" action="process-login.php" method="POST">
        <ul>
            <li>
                <label for="email">Email Address</label>
                <input type="email" id="email-login" name="email" autocomplete="email" required>
                <p id="email-login-error">No account associated!</p>
            </li>
            <li>
                <label for="password">Password</label>
                <input type="password" id="password-login" name="password" autocomplete="current-password" required>
                <span><img id="eye-icon" src="CSS/Images/Icons/eye_open.svg" alt="Makes the password visible."></span>
                <a href="password_forgotten1.html" id="forgot-password-link">Forgot Password?</a>
            </li>
            <li>
                <button type="submit" id="submit-1" form="access-credentials">Login</button>
                <p>Don't have an account? <a href="register1.html">Register Here</a></p>
            </li>
        </ul>
    </form>
</section>

<section id="section-forgot1">
    <h2>We got this.</h2>
    <p>Confirm the email address you used to register and you will receive a code to reset your password.</p>
    <form id="pswd-recovery-mail" action="#" method="POST">
        <ul>
            <li>
                <label for="email">Email Address</label>
                <input type="email" id="email-forgot1" name="email" autocomplete="email" required>
            </li>
            <li>                    
                <button type="button" onclick="window.history.back()">Back</button>
            </li>
            <li>
                <p id="email-forgot-error">No Account associated!</p>
                <button type="submit" id="submit-forgot1" form="pswd-recovery-mail">Next</button>
            </li>
        </ul>
        <p id="register-link" >Don't have an account? <a href="register1.html">Register Here</a></p>
    </form>
</section>

<section id="section-forgot2">
    <h2>We got this.</h2>
    <p>Enter the code we sent you in the email and proceed to choose a new password.</p>
    <form id="pswd-recovery-otp" action="#" method="POST">
        <ul>
            <li>
                <label for="email">Email Address</label>
                <input type="email" id="email-forgot2" name="email" readonly>
            </li>
            <!--
            For the markup of the OTP input field we could have done it in two ways: use a single <input> 
            tag containing the 6 digits of which the code is composed, which although semantically more 
            correct would have been very complicated to implement, since on the CSS side we would have had 
            to simulate the separation into 6 fields, each with its own background & outline made via linear-
            gradient, but also handle the cursor ending outside the 6 boxes at the end of the code typing. 
            The second way, chosen by us, is to use an <input> tag for each digit of code, with the addition 
            of a JS script that detects when we enter or leave a field, which greatly simplifies the styling part.
            -->
            <li class="otp" id="otp">
                <p>Code Received</p>
                <label for="otp-digit1">First digit of the code received by email</label>
                <input type="text" id="otp-digit1" class="otp-input" maxlength="1" required/>
                <label for="otp-digit2">Second digit of the code received by email</label>
                <input type="text" id="otp-digit2" class="otp-input" maxlength="1" required/>
                <label for="otp-digit3">Third digit of the code received by email</label>
                <input type="text" id="otp-digit3" class="otp-input" maxlength="1" required/>
                <label for="otp-digit4">Fourth digit of the code received by email</label>
                <input type="text" id="otp-digit4" class="otp-input" maxlength="1" required/>
                <label for="otp-digit5">Fifth digit of the code received by email</label>
                <input type="text" id="otp-digit5" class="otp-input" maxlength="1" required/>
                <label for="otp-digit6">Sixth digit of the code received by email</label>
                <input type="text" id="otp-digit6" class="otp-input" maxlength="1" required/>
            </li>
            <li>
                <button type="button" onclick="window.history.back()">Back</button>
            </li>
            <li>
                <p id="code-forgot-error">Incorrect code!</p>
                <button type="submit" id="submit-forgot2" form="pswd-recovery-otp">Verify</button>
            </li>
        </ul>
        <p>Don't have an account? <a href="#">Register Here</a></p>
    </form>
</section>


<section id="section-forgot3">
    <h2>It's all set.</h2>
    <p>Choose a different password from the previous one and step into greatness.</p>
    <form id="pswd-recovery-new" action="#" method="POST">
        <ul>
            <li>
                <label for="password">New Password</label>
                <input type="password" id="password-forgot3" name="password" autocomplete="new-password" required>
            </li>
            <li>
                <label for="confirm-password">Confirm New Password</label>
                <input type="password" id="confirm-password-forgot3" name="confirm-password" autocomplete="new-password" required>
            </li>
            <li>
                <button type="button" onclick="window.history.back()">Back</button>
            </li>
            <li>
                <button type="submit" form="pswd-recovery-new">Login</button>
            </li>
        </ul>
    </form>
</section>

<script>
    let eyeIcon = document.getElementById("eye-icon");
    let password = document.getElementById("password");

    eyeIcon.onclick = function() {
        if (password.type == "password") {
            password.type = "text";
            eyeIcon.src = "CSS/Images/Icons/eye_closed.svg";
            eyeIcon.alt = "Makes the password masked.";
        } else {
            password.type = "password";
            eyeIcon.src = "CSS/Images/Icons/eye_open.svg";
            eyeIcon.alt = "Makes the password visible.";
        }
    }
</script>