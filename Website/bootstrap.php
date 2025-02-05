<?php

session_start();
require_once("Database/database.php");
$dbh = new DatabaseHelper("localhost", "root", "", "UrbanKicks", 3306);

?>