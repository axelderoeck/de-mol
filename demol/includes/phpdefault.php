<?php 
// Default PHP lines for every file

// Initialize a new session
ob_start();
session_start();

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