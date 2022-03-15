<?php
// Include the database info
include_once("./db/db_info.php");
// Set the timezone
date_default_timezone_set('Europe/Brussels');

/* DATABASE SETTINGS */
// Database hostname
define('DB_HOST', $servername);
// Database username
define('DB_USER', $username);
// Database password
define('DB_PASS', $password);
// Database name
define('DB_NAME', $database);
?>