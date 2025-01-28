<header>
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

<form action="admin_new_message.php" method="POST">
    <label for="email-new-message-admin">Recipient's Email</label>
    <p id="error-email-new-message-admin" style="display: none;">No customers found with this email</p>
    <input 
        name="email-new-message-admin" 
        id="email-new-message-admin" 
        type="email" 
        value="<?php echo isset($templateParams['recipient']) ? htmlspecialchars($templateParams['recipient']) : ''; ?>"
        required 
    />
    
    <label for="subject-new-message-admin">Subject</label>
    <input 
        name="subject-new-message-admin" 
        id="subject-new-message-admin" 
        type="text"  
        value="<?php echo isset($templateParams['subject']) ? htmlspecialchars($templateParams['subject']) : ''; ?>"
        required 
    />
    
    <label for="body-new-message-admin">Body</label>
    <textarea 
        name="body-new-message-admin" 
        id="body-new-message-admin" 
        placeholder="Type your message here (max 400 characters)." 
        maxlength="400" 
        rows="10" 
        cols="40" 
        required
    ></textarea>
    
    <button type="submit">Send</button>
</form>

<script>
document.getElementById('email-new-message-admin').addEventListener('input', function(e) {
    const errorElement = document.getElementById('error-email-new-message-admin');
    // Hide error message when user starts typing
    errorElement.style.display = 'none';
});
</script>