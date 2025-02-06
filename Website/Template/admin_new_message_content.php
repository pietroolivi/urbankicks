<header class="back-button-and-title">
    <a href="javascript:history.back()" class="back">
        <img src="CSS/Images/Icons/back.svg" alt="Icon representing a backward arrow, to return to the previous page.">
    </a>
    <h2>New Message</h2>
</header>

<?php if (isset($_SESSION["error_message"])): ?>
    <div class="error-message">
        <?php 
            echo $_SESSION["error_message"];
            unset($_SESSION["error_message"]);
        ?>
    </div>
<?php endif; ?>

<div style="text-align: -webkit-center;">
    <form id="new-message-admin-form" style="text-align: -webkit-center;" class="authentication-typed-label-input" action="admin_new_message.php" method="POST">
        <label class="authentication-typed-label" for="email-new-message-admin">Recipient's Email</label>
        <p id="error-email-new-message-admin" style="display: none;">No customers found with this email</p>
        <input
            style="margin-bottom: 3%;"
            class="authentication-typed-input"
            name="email-new-message-admin" 
            id="email-new-message-admin" 
            type="email" 
            value="<?php echo isset($templateParams['recipient']) ? htmlspecialchars($templateParams['recipient']) : ''; ?>"
            required 
        />
        
        <label class="authentication-typed-label" for="subject-new-message-admin">Subject</label>
        <input
            style="margin-bottom: 3%;"
            class="authentication-typed-input"
            name="subject-new-message-admin" 
            id="subject-new-message-admin" 
            type="text"  
            value="<?php echo isset($templateParams['subject']) ? htmlspecialchars($templateParams['subject']) : ''; ?>"
            required 
        />
        
        <label class="authentication-typed-label" for="body-new-message-admin">Body</label>
        <textarea
            style="margin-bottom: 3%;"
            class="authentication-typed-input"
            name="body-new-message-admin" 
            id="body-new-message-admin" 
            placeholder="Type your message here (max 400 characters)." 
            maxlength="400" 
            rows="10" 
            cols="40" 
            required
        ></textarea>
    </form>
    <button form="new-message-admin-form" class="full-button-black" type="submit">Send</button>
</div>

<script>
document.getElementById('email-new-message-admin').addEventListener('input', function(e) {
    const errorElement = document.getElementById('error-email-new-message-admin');
    // Hide error message when user starts typing
    errorElement.style.display = 'none';
});
</script>