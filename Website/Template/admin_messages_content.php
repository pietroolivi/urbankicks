<header class="back-button-and-title">
    <a href="javascript:history.back()" class="back">
        <img src="CSS/Images/Icons/back.svg" alt="Icon representing a backward arrow, to return to the previous page.">
    </a>
    <h2>Messages</h2>
</header>

<button id="new-message-button" onclick='location.href="admin_new_message.php"' class="full-button-white">+ New Message</button>

<section class="messages-to-admin">
    <h3>Messages</h3>
    <?php if(empty($templateParams["messages"])): ?>
        <p>No messages found.</p>
    <?php else: ?>
        <?php foreach($templateParams["messages"] as $thread): 
            if ($thread['original']): 
                $message = $thread['original'];
                $messagePreview = strlen($message['Corpo']) > 30 ? 
                    substr($message['Corpo'], 0, 30) . '...' : 
                    $message['Corpo'];
        ?>
            <article class="message-thread">
                <!-- Original Message -->
                <div class="message-to-admin" onclick="expandMessage(
                    'CSS/Images/Icons/user_review.svg',
                    '<?php echo htmlspecialchars($message['Nome'] . ' ' . $message['Cognome']); ?>', 
                    '<?php echo htmlspecialchars($message['Email']); ?>', 
                    '<?php echo htmlspecialchars($message['Oggetto']); ?>', 
                    '<?php echo htmlspecialchars($message['Corpo']); ?>', 
                    '<?php echo $message['Timestamp_Invio']; ?>'
                    )">
                    <img src="CSS/Images/Icons/user_review.svg" alt="User profile photo" />
                    <div class="textual-part-message">
                        <h4><?php echo htmlspecialchars($message['Nome'] . ' ' . $message['Cognome']); ?></h4>
                        <p><?php echo htmlspecialchars($message['Email']); ?></p>
                        <p><?php echo htmlspecialchars($messagePreview); ?></p>
                        <small class="timestamp"><?php echo date('Y-m-d H:i', strtotime($message['Timestamp_Invio'])); ?></small>
                    </div>
                </div>

                <!-- Replies -->
                <?php foreach($thread['replies'] as $reply): 
                    $replyPreview = strlen($reply['Corpo']) > 30 ? 
                        substr($reply['Corpo'], 0, 30) . '...' : 
                        $reply['Corpo'];
                ?>
                    <div class="message-to-admin message-reply" onclick="expandMessage(
                        'CSS/Images/Icons/admin.svg',
                        '<?php echo htmlspecialchars($reply['Nome'] . ' ' . $reply['Cognome']); ?>', 
                        '<?php echo htmlspecialchars($reply['Email']); ?>', 
                        '<?php echo htmlspecialchars($reply['Oggetto']); ?>', 
                        '<?php echo htmlspecialchars($reply['Corpo']); ?>', 
                        '<?php echo $reply['Timestamp_Invio']; ?>'
                        )">
                        <img src="CSS/Images/Icons/admin.svg" alt="Admin profile photo" />
                        <div class="textual-part-message">
                            <h4><?php echo htmlspecialchars($reply['Nome'] . ' ' . $reply['Cognome']); ?></h4>
                            <p><?php echo htmlspecialchars($replyPreview); ?></p>
                            <small class="timestamp"><?php echo date('Y-m-d H:i', strtotime($reply['Timestamp_Invio'])); ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </article>
        <?php endif; endforeach; ?>
    <?php endif; ?>
</section>


<!-- The Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
    </div>
</div>

<script>
const modal = document.getElementById("myModal");
const modalContent = document.querySelector(".modal-content");

function expandMessage(profilePic, fullName, email, subject, messageBody, timestamp) {
    const formattedDate = new Date(timestamp).toLocaleString();
    
    modalContent.innerHTML = `
        <span class="close">&times;</span>
        <article class="message-details">
            <header>
                <img src="${profilePic}" alt="Profile photo" />
                <div>
                    <h4>${fullName}</h4>
                    <p>${email}</p>
                    <p class="timestamp">${formattedDate}</p>
                </div>
            </header>
            <div class="message-body">
                <h5>Subject: ${subject}</h5>
                <p>${messageBody}</p>
            </div>
            <footer>
                <button class="full-button-black" onclick="replyToMessage('${email}', '${subject}')" class="reply-btn">Reply</button>
            </footer>
        </article>
    `;

    modal.style.display = "block";

    const closeBtn = document.querySelector(".close");
    closeBtn.onclick = () => modal.style.display = "none";
}

function replyToMessage(email, subject) {
    const replySubject = `Re: ${subject}`;
    window.location.href = `admin_new_message.php?to=${email}&subject=${encodeURIComponent(replySubject)}`;
}

window.onclick = (event) => {
    if (event.target === modal) {
        modal.style.display = "none";
    }
}
</script>