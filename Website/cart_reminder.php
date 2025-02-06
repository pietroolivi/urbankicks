<?php
require_once("bootstrap.php");

class CartReminder {
    private $dbh;
    private const REMINDER_THRESHOLD = 24;

    public function __construct($dbh) {
        $this->dbh = $dbh;
    }

    public function sendReminders() {
        $users = $this->dbh->getAbandonedCarts(self::REMINDER_THRESHOLD);
        
        $count = 0;
        foreach ($users as $user) {
            if ($this->dbh->createCartReminderNotification($user['Email'])) {
                $count++;
            }
        }
        
        return $count;
    }
}

// Execute only if called directly
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    global $dbh; // Use the $dbh from bootstrap.php
    $reminder = new CartReminder($dbh);
    $remindersSent = $reminder->sendReminders();
    echo "Reminders sent: " . $remindersSent;
}
?>