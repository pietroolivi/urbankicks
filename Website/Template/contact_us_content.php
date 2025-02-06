<header class="back-button-and-title">
    <a href="javascript:history.back()" class="back"><img src="CSS/Images/Icons/back.svg" alt="Icon representing a backward arrow, to return to the previous page."></a>
    <h2>Contact Us</h2>
</header>

<?php if(isset($_SESSION['alert'])): ?>
    <div class="alert <?php echo $_SESSION['alert_type']; ?>">
        <?php 
        echo $_SESSION['alert'];
        unset($_SESSION['alert']);
        unset($_SESSION['alert_type']);
        ?>
    </div>
<?php endif; ?>
<div style="text-align: -webkit-center;">
    <form id="client-contact-form" class="authentication-typed-label-input" action="process_contact.php" method="POST">
        <label class="authentication-typed-label" for="first-name-contact">First Name</label>
        <input style="margin-bottom: 3%;" class="authentication-typed-input" id="first-name-contact" name="firstname" type="text" required/>

        <label class="authentication-typed-label" for="last-name-contact">Last Name</label>
        <input style="margin-bottom: 3%;" class="authentication-typed-input" id="last-name-contact" name="lastname" type="text" required/>

        <label class="authentication-typed-label" for="email-contact">Email Address</label>
        <input style="margin-bottom: 3%;" class="authentication-typed-input" id="email-contact" name="email" type="email" value="<?php echo($_SESSION["user_email"])?>" readonly/>

        <label class="authentication-typed-label" for="message-contact">Message</label>
        <textarea style="width: 100%;" id="margin-contact" name="message" placeholder="Type your concerns here (max 400 characters)." maxlength="400" rows="10" cols="40" required></textarea>
    </form>
    <button form="client-contact-form" class="full-button-black" type="submit">Submit</button>
</div>