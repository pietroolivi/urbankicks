<?php
require_once("bootstrap.php");

class CartReminder {
    private $dbh;
    private const REMINDER_THRESHOLD = 24;

    public function __construct($dbh) {
        $this->dbh = $dbh;
    }

    public function sendReminders() {
        $query = "SELECT DISTINCT c.Email, c.Data_Creazione
                 FROM CARRELLO c 
                 JOIN comprendere co ON c.ID_Carrello = co.ID_Carrello 
                 WHERE c.Valore_Totale > 0 
                 AND TIMESTAMPDIFF(HOUR, c.Data_Creazione, NOW()) >= ?";

        $stmt = $this->dbh->getDb()->prepare($query);
        $stmt->bind_param("i", self::REMINDER_THRESHOLD);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);

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
    $reminder = new CartReminder($dbh);
    $remindersSent = $reminder->sendReminders();
    echo "Reminders sent: " . $remindersSent;
}
?>