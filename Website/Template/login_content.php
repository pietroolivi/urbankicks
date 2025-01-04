<fieldset>
    <legend>Please select whether you want to log in (if you already have an account) or register:</legend>
    <input type="radio" id="login" name="authentication" value="login" checked="checked"><label for="login">LOGIN</label>
    <input type="radio" id="register" name="authentication" value="register"><label for="register">REGISTER</label>
</fieldset>
<h2>Welcome Back</h2>
<p>Happy to see you again. Please Login Here</p>
<form id="access-credentials" action="process-login.php" method="POST">
    <ul>
        <li>
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" autocomplete="email" required>
            <p id="email-error-login">No account associated!</p>
        </li>
        <li>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" autocomplete="current-password" required>
            <span><img id="eye-icon" src="CSS/Images/Icons/eye_open.svg" alt="Makes the password visible."></span>
            <a href="password_forgotten1.html" id="forgot-password">Forgot Password?</a>
        </li>
        <li>
            <button type="submit" form="access-credentials">Login</button>
            <p>Don't have an account? <a href="register1.html">Register Here</a></p>
        </li>
    </ul>
</form>

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