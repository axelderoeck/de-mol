<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

include "includes/settings.php";

$meldingSoort = "succes";

$code = $_GET["code"];
if ($code==9) {
    $_SESSION["Id"] = NULL;
    $_SESSION["Naam"] = "";
    $_SESSION["Voted"] = 0;
    $_SESSION["Admin"] = 0;
    session_destroy();
    header('location:index.php');
    //$meldingSoort = "succes";
    //$foutmelding = "Je bent uitgelogd.";
}

if ($_SESSION["Id"] != NULL) {
  header('location:home.php');
}

if (isset($_POST["userLogin"])){
    //gegevens van de formfields ontvangen
    $gebruikersnaam = $_POST["Naam"];
    $wachtwoord = $_POST["Wachtwoord"];

    $sql = $dbconn->query("SELECT Id, Naam, Wachtwoord, Voted, Email
                    FROM table_Users
                    WHERE Gebruikersnaam = '$gebruikersnaam'");

    if($sql->num_rows > 0) {
          $data = $sql->fetch_array();
          $id = ($data['Id']);
          $naam = ($data['Naam']);
          $hasVoted = ($data['Voted']);
          $email = ($data['Email']);
          if(password_verify($wachtwoord, $data['Wachtwoord'])){
            $_SESSION["Id"] = $id;
            $_SESSION["Naam"] = $naam;
            $_SESSION["Gebruikersnaam"] = $gebruikersnaam;
            $_SESSION["Voted"] = $hasVoted;
            $_SESSION["Email"] = $email;
            $foutmelding = "";
            if(in_array($id, $admins)){
              //admin is gevonden => aangemeld met rechten
              $_SESSION["Admin"] = 1;
              header('location:home.php');
            }elseif ($id <> NULL){
              //gebruiker is gevonden => aangemeld
              $_SESSION["Admin"] = 0;
              header('location:home.php');
            }else{
              //gebruiker is niet gevonden => niet aangemeld
              $meldingSoort = "warning";
              $foutmelding = "Wachtwoord is niet correct!";
            }
          }
    } else {
      $meldingSoort = "warning";
      $foutmelding = "Wachtwoord is niet correct!";
    }
}

if (isset($_POST["userRegister"])){
    //waardes uit het formulier in lokale var steken
    $naam = $_POST["Naam"];
    $gebruikersnaam = $_POST["Gebruikersnaam"];
    $wachtwoord = $_POST["Wachtwoord"];
    $confirmWachtwoord = $_POST["confirmWachtwoord"];

    $sql = $dbconn->query("SELECT Gebruikersnaam
                    FROM table_Users
                    WHERE Gebruikersnaam = '$gebruikersnaam'");

    if($wachtwoord != $confirmWachtwoord){
      $meldingSoort = "warning";
      $foutmelding = "Het wachtwoord is niet bevestigd.";
    }elseif($sql->num_rows > 0){
      $meldingSoort = "warning";
      $foutmelding = "Deze gebruikersnaam is al in gebruik.";
    }else{
        $hash = password_hash($wachtwoord, PASSWORD_BCRYPT);
        $dbconn->query("INSERT INTO table_Users (Gebruikersnaam, Naam, Wachtwoord)
        VALUES ('$gebruikersnaam','$naam','$hash')");

        $selectNewId = $dbconn->query("SELECT Id
                        FROM table_Users
                        WHERE Gebruikersnaam = '$gebruikersnaam' AND Wachtwoord = '$hash'");

        $data = $selectNewId->fetch_array();
        $newId = ($data['Id']);

        for ($i=1; $i <= 10; $i++) {
          $dbconn->query("INSERT INTO table_Scores (UserId, Identifier, Score)
          VALUES ('$newId','person$i',0)");
        }

        $dbconn->query("INSERT INTO table_Followers (UserId, UserIsFollowingId)
        VALUES ('$newId','$newId')");

        //HIERE DA DING DOEN YES add id aan volgers volg zichzelf
        $meldingSoort = "succes";
        $foutmelding = "Account is aangemaakt.";
    }
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <script>
  window.addEventListener('load', function() {
    <?php
      $pageRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) &&($_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0' ||  $_SERVER['HTTP_CACHE_CONTROL'] == 'no-cache');
      if($pageRefreshed == 1){
        echo "showNotification('$foutmelding','$meldingSoort');"; //message + color style
      }
      if ($code == 9) {
        echo "showNotification('$foutmelding','$meldingSoort');";
      }
    ?>
  })
  </script>
</head>
<body>

  <div id="informationPopup">
    <!-- Dynamische info -->
  </div>

  <div class="respContainer">

  <div style="text-align: center; margin: 10% 0;">
    <img class="loginImg" src="img/assets/molLogo.png" alt="logo">
  </div>

  <div id="loginbox">
            <div id="log">
                <form name="formLogin" action="" method="post">
                    <label>Gebruikersnaam</label>
                    <input placeholder="Gebruikersnaam" name="Naam" id="Naam" type="text" required>
                    <br>
                    <label>Wachtwoord</label>
                    <input placeholder="Wachtwoord" name="Wachtwoord" id="Wachtwoord" type="password" required>
                    <br>
                    <input type="submit" name="userLogin" id="userLogin" value="Login">
                    <br>
                    <!--
                    <input type="checkbox" name="rememberme" value="">
                    <label>Onthoud Mij</label>
                    -->
                </form>
                <a href="reset_askemail.php">wachtwoord vergeten?</a>
                <p class="loginLink">Geen account? Klik <a href="javascript:openReg();">hier.</a></p>
            </div>
            <div id="reg">
                <form name="formRegister" action="" method="post">
                    <input placeholder="Gebruikersnaam" name="Gebruikersnaam" id="Gebruikersnaam" type="text" required>
                    <br>
                    <input placeholder="Voornaam" name="Naam" id="Naam" type="text" required>
                    <br>
                    <input placeholder="Wachtwoord" name="Wachtwoord" id="Wachtwoord" type="password" required>
                    <br>
                    <input placeholder="Wachtwoord" name="confirmWachtwoord" id="confirmWachtwoord" type="password" required>
                    <br>
                    <input type="submit" name="userRegister" id="userRegister" value="Register">
                    <br>
                </form>
                <p class="loginLink">Ga terug naar <a href="javascript:openReg();">login.</a></p>
            </div>
            <?php include "includes/legal.php"; ?>
    </div>
  </div>

  <!-- JavaScript -->

  <script type="text/javascript" src="js/scripts.js"></script>

<?php mysqli_close($dbconn); ?>
</body>
</html>
