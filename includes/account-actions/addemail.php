<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

if (isset($_POST["addEmail"])){
  $id = $_SESSION["Id"];
  $newEmail = $_POST["emailvalue"];

  $dbconn->query("UPDATE table_Users
    SET Email = '$newEmail'
    WHERE Id = '$id';
    ");

  $_SESSION["Email"] = $newEmail;

header('location:profiel.php');
}


?>
