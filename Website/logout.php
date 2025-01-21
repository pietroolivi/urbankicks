<?php
require_once("bootstrap.php");

session_regenerate_id(true);
session_destroy();

header("Location: index.php");
exit();
?>