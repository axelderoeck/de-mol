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
define('db_host',$servername);
// Database username
define('db_user',$username);
// Database password
define('db_pass',$password);
// Database name
define('db_name',$database);

?>