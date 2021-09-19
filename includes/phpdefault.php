
<?php 

// Initialize a new session
session_start();

// these 2 need to be merged
// Include the settings file
include "includes/settings.php";
// Include the configuration file
include 'includes/config.php';

// Include functions and connect to the database using PDO MySQL
include 'includes/functions.php';
// Connect to MySQL database
$pdo = pdo_connect_mysql();

?>