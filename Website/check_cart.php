<?php
require_once("bootstrap.php");

class ReminderScheduler {
    private const INTERVAL = 86400; // 24 hours in seconds
    private const LOG_FILE = 'cart_reminder.log';
    private const LAST_RUN_FILE = 'last_run.txt';

    public function run() {
        if ($this->shouldRun()) {
            try {
                require_once("cart_reminder.php");
                $this->updateLastRun();
                $this->log("Cart reminder executed successfully");
            } catch (Exception $e) {
                $this->log("Error: " . $e->getMessage());
            }
        }
    }

    private function shouldRun() {
        $lastRun = @file_get_contents(self::LAST_RUN_FILE);
        return !$lastRun || (time() - $lastRun) >= self::INTERVAL;
    }

    private function updateLastRun() {
        file_put_contents(self::LAST_RUN_FILE, time());
    }

    private function log($message) {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] $message\n";
        file_put_contents(self::LOG_FILE, $logEntry, FILE_APPEND);
    }
}

$scheduler = new ReminderScheduler();
$scheduler->run();
?>