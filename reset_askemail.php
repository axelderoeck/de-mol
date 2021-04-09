<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

include "includes/settings.php";
include "includes/functions.php";

if (isset($_POST["sendMail"])){
  // get email from form
  $email = $_POST["email"];

  // get account where email is attached to
  $queryGetAccountEmail = $dbconn->query("SELECT Id, Gebruikersnaam
                  FROM table_Users
                  WHERE Email = '$email'");

  // IF user found
  if($queryGetAccountEmail->num_rows > 0) {
    // set values
    $data = $queryGetAccountEmail->fetch_array();
    $id = ($data['Id']);
    $gebruikersnaam = ($data['Gebruikersnaam']);

    // set a random string as security measure
    $randomGeneratedString = generateRandomString(15);

    // set a random unique key to the user
    $dbconn->query("UPDATE table_Users
    SET UserKey = '$randomGeneratedString'
    WHERE Id = $id");

    // set mail values
    $subject = "De Mol: Wachtwoord Reset";
    $message = "Dag (vergeetachtige) mollenjager,\n\n
Jouw gebruikersnaam (moest je dat ook vergeten zijn) is: $gebruikersnaam\n\n
Klik op de onderstaande link om je wachtwoord opnieuw in te stellen.\n
https://aksol.be/demol/reset_password.php?u=$id&s=$randomGeneratedString\n\n
Veel succes met je opdracht en tot zo meteen.\n
*Heb jij dit niet aangevraagd? Geen probleem, dan kan je dit bericht gewoon negeren.";
    $headers = "From: mail@aksol.be";

    // send the mail
    mail($email,$subject,$message,$headers);

    sleep(60);
    $dbconn->query("UPDATE table_Users
    SET UserKey = ''
    WHERE Id = '$id'");
  }else{
    // user is not found
    $foutmelding = "Email is niet in gebruik";
    $meldingSoort = "warning";
  }
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
  <script type="text/javascript">
  window.addEventListener('load', function() {
    <?php
      $pageRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) &&($_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0' ||  $_SERVER['HTTP_CACHE_CONTROL'] == 'no-cache');
      if($pageRefreshed == 1){
        echo "showNotification('$foutmelding','$meldingSoort');"; //message + color style
      }
    ?>
  })
  </script>
</head>
<body>

  <div id="informationPopup">
    <!-- Dynamische info -->
  </div>

  <a href="home.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>

  <div id="main">
    <div class="respContainer">
      <h1>Wachtwoord vergeten</h1>
      <h2>We vergeten allemaal wel eens.</h2>

      <form method="post" action="">
        <p>Geef je email waar jouw account aan verbonden is.</p>
        <input placeholder="Email" type="text" name="email">
        <input value="Stuur" type="submit" name="sendMail" id="sendMail">
      </form>
      <p class="example">Als je geen email hebt toegevoegd aan je account zal je hier geen gebruik van kunnen maken.</p>
      <p class="example">Komt de email niet aan? Check zeker je spam folder eens na. Probeer opnieuw als het na een paar minuten nog steeds niet is aangekomen.</p>
    </div>
  </div>

  <script type="text/javascript" src="js/scripts.js"></script>
  <?php mysqli_close($dbconn); ?>
</body>
</html>
