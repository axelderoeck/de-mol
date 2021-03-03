<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

if (isset($_POST["changeName"])){
  $id = $_SESSION["Id"];
  $newName = $_POST["nieuweNaam"];

  $sql = $dbconn->query("SELECT Gebruikersnaam
                  FROM table_Users
                  WHERE Gebruikersnaam = '$newName'");

  if($sql->num_rows > 0){
    $meldingSoort = "warning";
    $foutmelding = "Deze gebruikersnaam is al in gebruik.";
  }else{
    $dbconn->query("UPDATE table_Users
      SET Gebruikersnaam = '$newName'
      WHERE Id = '$id';
      ");

    $_SESSION["Gebruikersnaam"] = $newName;
  }
header('location:profiel.php');
}


?>
