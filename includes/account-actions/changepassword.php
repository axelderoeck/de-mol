<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

if (isset($_POST["changePassword"])){
  $id = $_SESSION["Id"];
  $gebruikersnaam = $_SESSION["gebruikersnaam"];
  $wachtwoord = $_POST["oudWachtwoord"];
  $newWachtwoord = $_POST["Wachtwoord"];
  $confirmWachtwoord = $_POST["confirmWachtwoord"];

  $sql = $dbconn->query("SELECT Id, Wachtwoord
                  FROM table_Users
                  WHERE Id = '$id'");

  if($sql->num_rows > 0) {
    $data = $sql->fetch_array();
    if(password_verify($wachtwoord, $data['Wachtwoord'])){
      if($newWachtwoord == $confirmWachtwoord){
        $hash = password_hash($newWachtwoord, PASSWORD_BCRYPT);
        $dbconn->query("UPDATE table_Users
        SET Wachtwoord = '$hash'
        WHERE Id = '$id'");
      }
    }
    header('location:profiel.php');
  }
header('location:profiel.php');
}


?>
