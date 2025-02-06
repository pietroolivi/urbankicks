<fieldset class="choice-login-registration">
    <legend>Please select whether you want to log in (if you already have an account) or register:</legend>
    <input type="radio" id="login" name="authentication" value="login" checked="checked"><label for="login">LOGIN</label><input type="radio" id="register" name="authentication" value="register"><label for="register">REGISTER</label>
</fieldset>
<section id="section-login">
    <h2>Welcome Back</h2>
    <p>Happy to see you again. Please Login Here</p>
    <form id="access-credentials" action="login_handler.php" method="POST">
        <ul class="authentication-inputs">
            <li class="authentication-typed-label-input">
                <label class="authentication-typed-label" for="email-login">Email Address</label>
                <input class="authentication-typed-input" type="email" id="email-login" name="email-login" autocomplete="email" required>
                <p id="email-login-error"></p>
            </li>
            <li class="authentication-typed-label-input">
                <label class="authentication-typed-label" for="password-login">Password</label>
                <span class="password-and-visibility-switch">
                    <input id="password-login-field" class="authentication-typed-input" type="password" id="password-login" name="password-login" autocomplete="current-password" required><span><img id="eye-icon" src="CSS/Images/Icons/eye_open.svg" alt="Makes the password visible."></span>
                </span>
                <a href="recovery.php" id="forgot-password-link">Forgot Password?</a>
            </li>
            <li>
                <button class="full-button-black" type="submit" id="login-submit" form="access-credentials">Login</button>
                <p class="link-to-register-wrapper">Don't have an account? <a href="register.php">Register Here</a></p>
            </li>
        </ul>
    </form>
    <script>
        updatePswdVisibilitySwitch();
        window.addEventListener("resize", updatePswdVisibilitySwitch);
        /* **************************************************************************** */
        /* In order to avoid having to manipulate the element related to the eye        */
        /* image that changes the visibility of the password, either by extracting it   */
        /* from the document flow or by handling overlaps and absolute/fixed positions, */
        /* we place it next to the input field trying to make the tags look like one.   */
        /* **************************************************************************** */
        function updatePswdVisibilitySwitch() {
            let pswdAndEyeContainers = document.getElementsByClassName("password-and-visibility-switch");
            for (let outerSpanTag of pswdAndEyeContainers) {
                innerSpanTag = outerSpanTag.getElementsByTagName("span")[0];
                /* We resize the internal <span>, and therefore the <img> based on a ratio with respect to the external <span> considered reasonable in the mobile version. */
                const ratioWidthImgToSpan = 1 / 5;
                let newImgWidth = Number(innerSpanTag.clientWidth) * Number(ratioWidthImgToSpan);
                innerSpanTag.getElementsByTagName("img")[0].style.width = newImgWidth + "px";
                /* We make the border of <input> and <span> form a single rectangle, equalizing their height with that of */
                /* their container, in turn resized to the height of the textual input field above, taken as a reference. */
                let newInputClientHeight = document.getElementsByClassName("authentication-typed-input")[0].clientHeight + "px";
                let newInputOffsetHeight = document.getElementsByClassName("authentication-typed-input")[0].offsetHeight + "px";             
                outerSpanTag.style.height = newInputOffsetHeight;
                outerSpanTag.getElementsByTagName("input")[0].style.height = newInputOffsetHeight;
                innerSpanTag.style.height = newInputOffsetHeight;
                /*
                console.log("client email: " + newInputClientHeight);
                console.log("offset email: " + newInputOffsetHeight);
                console.log("height email: " + document.getElementsByClassName("authentication-typed-input")[0].style.height);
                console.log( outerSpanTag.getElementsByTagName("input")[0].clientHeight);
                console.log(outerSpanTag.clientHeight);
                console.log(innerSpanTag.clientHeight);
                console.log("client pswd: " + newInputClientHeight);
                console.log("offset pswd: " + newInputOffsetHeight);
                console.log("height pswd: " + document.getElementsByClassName("authentication-typed-input")[0].style.height);
                */
            }
        }
        /* We change the visibility of the password and the icon. */
        let eyeIcon = document.getElementById("eye-icon");
        let password = document.getElementById("password-login-field");
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
            /* We want that when the icon changes from that of the open eye to that of the closed eye the vertical centering of the image is recalculated since the height of the two images is different. */
            updatePswdVisibilitySwitch();
        }
    </script>
</section>