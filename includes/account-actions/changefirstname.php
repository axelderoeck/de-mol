<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

include "includes/settings.php";

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

if (isset($_POST["changeFirstName"])){
  $id = $_SESSION["Id"];
  $newName = $_POST["nieuweVoornaam"];

  //check if value matches criteria for award
  if ($newName == $award_secret_mol_randomcode) {
    giveAward($id, $award_secret_mol, $dbconn);
  }else{
  // normal name change procedure
    $dbconn->query("UPDATE table_Users
        SET Naam = '$newName'
        WHERE Id = '$id';
        ");

    $_SESSION["Naam"] = $newName;
  }

header('location:profiel.php');
}


?>
