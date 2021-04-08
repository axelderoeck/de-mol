<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

include "includes/settings.php";
include "includes/functions.php";

// get id and key from url
$url_userKey = $_GET["s"];
$url_userId = $_GET["u"];

// get userkey from database
$queryGetUserKey = $dbconn->query("SELECT UserKey
                FROM table_Users
                WHERE Id = '$url_userId'");
$data = $queryGetUserKey->fetch_array();
$userKey = ($data['UserKey']);

// if form submit
if (isset($_POST["changePassword"])){
  //get form values
  $newWachtwoord = $_POST["Wachtwoord"];
  $confirmWachtwoord = $_POST["confirmWachtwoord"];

  //if passwords match set as new password
  if($newWachtwoord == $confirmWachtwoord){
    $hash = password_hash($newWachtwoord, PASSWORD_BCRYPT);
    $dbconn->query("UPDATE table_Users
    SET Wachtwoord = '$hash', UserKey = ''
    WHERE Id = '$url_userId'");
  }

  header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
</head>
<body>

  <div id="main">
    <div class="respContainer">
      <h1>Wachtwoord veranderen</h1>

      <?php
      // check if userkey is correct
        if ($url_userId != null && $url_userKey != null) {
          if ($url_userKey == $userKey) { ?>
            <form name="formChangePassword" action="" method="post">
                <input placeholder="Wachtwoord" name="Wachtwoord" id="Wachtwoord" type="password" required>
                <br>
                <input placeholder="Wachtwoord" name="confirmWachtwoord" id="confirmWachtwoord" type="password" required>
                <br>
                <input type="submit" name="changePassword" id="changePassword" value="Verander">
            </form> <?php
          }else{
            echo "<p class='example'>De sessie is vervallen of niet correct.</p>";
          }
        }else{
          echo "<p class='example'>De sessie is vervallen of niet correct.</p>";
        }
      ?>
    </div>
  </div>

  <script type="text/javascript" src="js/scripts.js"></script>
  <?php mysqli_close($dbconn); ?>
</body>
</html>
