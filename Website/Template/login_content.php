<fieldset>
    <legend>Please select whether you want to log in (if you already have an account) or register:</legend>
    <input type="radio" id="login" name="authentication" value="login" checked="checked"><label for="login">LOGIN</label>
    <input type="radio" id="register" name="authentication" value="register"><label for="register">REGISTER</label>
</fieldset>
<section id="section-login">
    <h2>Welcome Back</h2>
    <p>Happy to see you again. Please Login Here</p>
    <form id="access-credentials" action="login_handler.php" method="POST">
        <ul>
            <li>
                <label for="email-login">Email Address</label>
                <input type="email" id="email-login" name="email-login" autocomplete="email" required>
                <p id="email-login-error"></p>
            </li>
            <li>
                <label for="password-login">Password</label>
                <input type="password" id="password-login" name="password-login" autocomplete="current-password" required>
                <span><img id="eye-icon" src="CSS/Images/Icons/eye_open.svg" alt="Makes the password visible."></span>
                <a href="recovery.php" id="forgot-password-link">Forgot Password?</a>
                </script>
            </li>
            <li>
                <button type="submit" id="login-submit" form="access-credentials">Login</button>
                <p>Don't have an account? <a href="register.php">Register Here</a></p>
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