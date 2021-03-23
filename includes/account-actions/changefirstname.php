<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

if (isset($_POST["changeFirstName"])){
  $id = $_SESSION["Id"];
  $newName = $_POST["nieuweVoornaam"];

  $dbconn->query("UPDATE table_Users
      SET Naam = '$newName'
      WHERE Id = '$id';
      ");

  $_SESSION["Naam"] = $newName;

header('location:profiel.php');
}


?>
