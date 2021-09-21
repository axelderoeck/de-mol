<?php 

// CHANGE THIS WHEN GOING BACK ONLINE

/* Local and Online db */
/*
$localdbinfo = './demol_database_info.php';
$onlinedbinfo = './../demol_database_info.php';

if (file_exists($localdbinfo)) {
  include $localdbinfo;
} else {
  include $onlinedbinfo;
}
*/

$servername = "sql189.main-hosting.eu";
$database = "u939917173_demoltest";
$username = "u939917173_demolAdm1n";
$password = "demolAdm1n";

/* DATABASE SETTINGS */
// Database hostname
define('DB_HOST',$servername);
// Database username
define('DB_USER',$username);
// Database password
define('DB_PASS',$password);
// Database name
define('DB_NAME',$database);

?>