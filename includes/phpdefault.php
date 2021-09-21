<?php 
// Default PHP lines for every file

ob_start();
// Initialize a new session
session_start();
// these 2 need to be merged
// Include the settings file
include "includes/settings.php";
// Include the configuration file
include 'includes/config.php';
// Include functions
include 'includes/functions.php';
// Connect to MySQL database
$pdo = pdo_connect_mysql();

// If user is not logged in -> send to index page
if ($_SESSION["Id"] == NULL) {
    header('location:index.php');
}

?>