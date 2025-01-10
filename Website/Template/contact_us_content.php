<a href="javascript:history.back()" class="back"><img src="CSS/Images/Icons/back.svg" alt="Icon representing a backward arrow, to return to the previous page."></a>
<h2>Contact Us</h2>

<?php if(isset($_SESSION['alert'])): ?>
    <div class="alert <?php echo $_SESSION['alert_type']; ?>">
        <?php 
        echo $_SESSION['alert'];
        unset($_SESSION['alert']);
        unset($_SESSION['alert_type']);
        ?>
    </div>
<?php endif; ?>

<form action="process_contact.php" method="POST">
    <label for="first-name-contact">First Name</label>
    <input id="first-name-contact" name="firstname" type="text" required/>

    <label for="last-name-contact">Last Name</label>
    <input id="last-name-contact" name="lastname" type="text" required/>

    <label for="email-contact">Email Address</label>
    <input id="email-contact" name="email" type="email" value="<?php echo($_SESSION["user_email"])?>" readonly/>

    <label for="message-contact">Message</label>
    <textarea id="message-contact" name="message" placeholder="Type your concerns here (max 400 characters)." maxlength="400" rows="10" cols="40" required></textarea>

    <button type="submit">Submit</button>
</form>