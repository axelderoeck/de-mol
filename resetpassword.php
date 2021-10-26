<?php

require_once("includes/phpdefault.php");

// get id and key from url
$url_userKey = $_GET["s"];
$url_userId = $_GET["u"];

// get userkey from database
$stmt = $pdo->prepare('SELECT UserKey FROM table_Users WHERE Id = ?');
$stmt->execute([ $_GET["u"] ]);
$key = $stmt->fetchColumn(0);

// if form submit
if (isset($_POST["changePassword"])){

  // Check if passwords match
  if($_POST["password"] == $_POST["confirmPassword"]){
    // Hash the password
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    // Update password from user
    $stmt = $pdo->prepare('UPDATE table_Users SET Password = ? WHERE Id = ?');
    $stmt->execute([ $password, $_GET["u"] ]);
    // Notify user
    $meldingSoort = "success";
    $foutmelding = "Wachtwoord is aangepast.";

    // Reset key
    $stmt = $pdo->prepare('UPDATE table_Users SET UserKey = "" WHERE Id = ?');
    $stmt->execute([ $_GET["u"] ]);
  }else{
    // Notify user
    $meldingSoort = "warning";
    $foutmelding = "Wachtwoorden komen niet overeen.";
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
        if ($_GET["u"] != null && $_GET["s"] != null) {
          if ($_GET["s"] == $key) { ?>
            <form name="formChangePassword" action="" method="post">
                <input placeholder="Wachtwoord" name="password" id="password" type="password" required>
                <br>
                <input placeholder="Wachtwoord" name="confirmPassword" id="confirmPassword" type="password" required>
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
</body>
</html>
