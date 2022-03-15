<?php
// Function that will connect to the MySQL database
function pdo_connect_mysql() {
  try {
      // Connect to the MySQL database using PDO...
    return new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
  } catch (PDOException $exception) {
    // Could not connect to the MySQL database, if this error occurs make sure you check your db settings are correct!
    exit('Failed to connect to database!');
  }
}
?>