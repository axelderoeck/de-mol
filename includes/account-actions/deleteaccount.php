<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

if (isset($_POST["deleteAccount"])){
  $id = $_SESSION["Id"];

  $dbconn->query("DELETE FROM table_Users
    WHERE Id = '$id';
    ");

  $dbconn->query("DELETE FROM table_Scores
    WHERE UserId = '$id';
    ");

  $dbconn->query("DELETE FROM table_UserAwards
    WHERE UserId = '$id';
    ");

    $_SESSION["Id"] = NULL;
    $_SESSION["Naam"] = "";
    $_SESSION["Voted"] = 0;
    $_SESSION["Admin"] = 0;
    session_destroy();
    header('location:index.php');
}


?>
