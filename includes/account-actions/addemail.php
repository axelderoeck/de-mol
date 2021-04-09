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

  $sql = $dbconn->query("SELECT Email
                  FROM table_Users
                  WHERE Email = '$newEmail'");

  if($sql->num_rows > 0){
    $meldingSoort = "warning";
    $foutmelding = "Deze email is al in gebruik.";
  }else{
    $dbconn->query("UPDATE table_Users
      SET Email = '$newEmail'
      WHERE Id = '$id';
      ");

    $_SESSION["Email"] = $newEmail;
  }

header('location:profiel.php');
}


?>
