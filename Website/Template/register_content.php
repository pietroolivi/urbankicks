<?php
// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle Step 1
    if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['emailinsert'])) {
        $_SESSION['register_step1'] = [
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'email' => $_POST['emailinsert']
        ];
        header('Location: ' . $_SERVER['PHP_SELF'] . '?step=2');
        exit();
    }
    
    // Handle Step 2
    if (isset($_POST['password']) && isset($_POST['phone'])) {
        $_SESSION['register_step2'] = [
            'password' => $_POST['password'],
            'phone' => $_POST['phone']
        ];
        header('Location: ' . $_SERVER['PHP_SELF'] . '?step=3');
        exit();
    }
}

$currentStep = isset($_GET['step']) ? $_GET['step'] : '1';
?>

<fieldset>
    <legend>Please select whether you want to log in (if you already have an account) or register:</legend>
    <input type="radio" id="login" name="authentication" value="login"><label for="login">LOGIN</label>
    <input type="radio" id="register" name="authentication" value="register" checked="checked"><label for="register">REGISTER</label>
</fieldset>

<!-- Step 1: Basic Information -->
<section id="section-register1" <?php echo $currentStep !== '1' ? 'style="display:none;"' : ''; ?>>
    <div><img src="CSS/Images/Illustrations/progress_bar1.svg" alt="First of three steps for registration."></div>
    <h2>New to UrbanKicks?</h2>
    <p>Start the journey now!</p>
    <form id="register-name-mail" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <ul>
            <li>
                <label for="first-name">First Name</label>
                <input type="text" id="first-name" name="firstname" autocomplete="given-name" required>
            </li>
            <li>
                <label for="last-name">Last Name</label>
                <input type="text" id="last-name" name="lastname" autocomplete="family-name" required>
            </li>
            <li>
                <label for="email-register">Email Address</label>
                <input type="email" id="email-register" name="emailinsert" autocomplete="email" required>
                <p id="email-register-error">Email already in use!</p>
            </li>
            <li>
                <button type="submit" id="submit-1" form="register-name-mail" disabled>Next</button>
            </li>
        </ul>
    </form>
</section>

<!-- Step 2: Password and Phone -->
<section id="section-register2" <?php echo $currentStep !== '2' ? 'style="display:none;"' : ''; ?>>
    <div><img src="CSS/Images/Illustrations/progress_bar2.svg" alt="Second of three steps for registration."></div>
    <h2>Elevate your Style</h2>
    <p>Be a part of the newest fashion trends. Urbankicks' sneakers make heads turn.</p>
    <form id="register-pswd-tel" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <ul>
            <li>
                <label for="password-register">Password</label>
                <input type="password" id="password-register" name="password" autocomplete="new-password" required>
                <p class="pswd-format-error">8 to 20 characters long</p>
                <p class="pswd-format-error">Contains both letters and numbers</p>
                <p class="pswd-format-error">One symbols !\"#$%&'()*+,-./:;&lt;=>?</p>
            </li>
            <li>
                <label for="confirm-password-register">Confirm Password</label>
                <input type="password" id="confirm-password-register" name="confirm-password" autocomplete="new-password" required>
            </li>
            <li>
                <label for="phone-register">Mobile Phone</label>
                <input type="tel" id="phone-register" name="phone" autocomplete="tel-national">
            </li>
            <li>
                <button type="button" class="back-btn">Back</button>
                <button type="submit" id="submit-2" form="register-pswd-tel" disabled>Next</button>
            </li>
        </ul>
    </form>
</section>

<!-- Step 3: Consents -->
<section id="section-register3" <?php echo $currentStep !== '3' ? 'style="display:none;"' : ''; ?>>
    <div><img src="CSS/Images/Illustrations/progress_bar3.svg" alt="Third of three steps for registration."></div>
    <h2>Almost there!</h2>
    <p>Never miss out on a hot release again with our latest's drops newsletter! You'll also receive personalized shopping reminders about your Wishlist and Shopping Bag. You may unsubscribe at any time from the personal area.</p>
    <form id="register-consents" action="#" method="POST">
        <ul>
            <li>
                <label for="newsletter-register">I agree to subscribe to the newsletter.</label>
                <input type="checkbox" id="newsletter-register" name="newsletter">
            </li>
            <li>
                <label for="terms-privacy-register">I agree to Urbankicks' <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>.</label>
                <input type="checkbox" id="terms-privacy-register" name="terms" required>
            </li>
            <li>
                <button type="button" class="back-btn">Back</button>
                <button type="submit" id="register-button" form="register-consents" disabled>Register</button>
            </li>
        </ul>
    </form>
</section>