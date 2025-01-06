<section id="section-forgot1">
    <h2>We got this.</h2>
    <p>Confirm the email address you used to register and you will receive a code to reset your password.</p>
    <form id="pswd-recovery-mail" action="#" method="POST">
        <ul>
            <li>
                <label for="email-forgot1">Email Address</label>
                <input type="email" id="email-forgot1" name="email" autocomplete="email" required>
                <p id="email-forgot-error" class="error-message"></p>
            </li>
            <li>                    
                <button type="button" onclick="window.history.back()">Back</button>
            </li>
            <li>
                <button type="submit" id="submit-forgot1" form="pswd-recovery-mail">Next</button>
            </li>
        </ul>
    </form>
</section>

<section id="section-forgot2">
    <h2>We got this.</h2>
    <p>Enter the code we sent you in the email and proceed to choose a new password.</p>
    <form id="pswd-recovery-otp" action="#" method="POST">
        <ul>
            <li>
                <label for="email-forgot2">Email Address</label>
                <input type="email" id="email-forgot2" name="email" readonly>
            </li>
            <li class="otp" id="otp">
                <p>Code Received</p>
                <div class="otp-inputs">
                    <input type="text" id="otp-digit1" class="otp-input" maxlength="1" required/>
                    <input type="text" id="otp-digit2" class="otp-input" maxlength="1" required/>
                    <input type="text" id="otp-digit3" class="otp-input" maxlength="1" required/>
                    <input type="text" id="otp-digit4" class="otp-input" maxlength="1" required/>
                    <input type="text" id="otp-digit5" class="otp-input" maxlength="1" required/>
                    <input type="text" id="otp-digit6" class="otp-input" maxlength="1" required/>
                </div>
            </li>
            <li>
                <button type="button" onclick="window.history.back()">Back</button>
            </li>
            <li>
                <p id="code-forgot-error" class="error-message"></p>
                <button type="submit" id="submit-forgot2">Verify</button>
            </li>
        </ul>
    </form>
</section>

<section id="section-forgot3">
    <h2>It's all set.</h2>
    <p>Choose a different password from the previous one and step into greatness.</p>
    <form id="pswd-recovery-new" action="#" method="POST">
        <ul>
            <li>
                <label for="password-forgot3">New Password</label>
                <input type="password" id="password-forgot3" name="password" autocomplete="new-password" required>
            </li>
            <li>
                <label for="confirm-password-forgot3">Confirm New Password</label>
                <input type="password" id="confirm-password-forgot3" name="confirm-password" autocomplete="new-password" required>
            </li>
            <li>
                <button type="button" onclick="window.history.back()">Back</button>
            </li>
            <li>
                <p id="password-forgot-error" class="error-message"></p>
                <button type="submit">Reset Password</button>
            </li>
        </ul>
    </form>
</section>

<script src="JS/recovery.js"></script>