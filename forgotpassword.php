<?php

require_once("includes/phpdefault.php");

if (isset($_POST["sendMail"])){

  // get account where email is attached to
  $stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Email = ?');
  $stmt->execute([ $_POST["email"] ]);
  $account = $stmt->fetch(PDO::FETCH_ASSOC);

  // Account exists -> create key and send mail
  if($account){
    // Set a random string as security measure
    $random = generateRandomString(15);
    $username = $account["Username"];
    $id = $account["Id"];
    $email = $account["Email"];

    // Link random string with user
    $stmt = $pdo->prepare('UPDATE table_Users SET UserKey = ? WHERE Id = ?');
    $stmt->execute([ $random, $id ]);

    // Set mail values
    $subject = "De Mol: Wachtwoord Reset";
    $message = "Dag (vergeetachtige) mollenjager,\n\n
    Jouw gebruikersnaam (moest je dat ook vergeten zijn) is: $username\n\n
    Klik op de onderstaande link om je wachtwoord opnieuw in te stellen.\n
    https://aksol.be/demol/resetpassword.php?u=$id&s=$random\n\n
    *Heb jij dit niet aangevraagd? Geen probleem, dan kan je dit bericht gewoon negeren.";
    $headers = "From: mail@aksol.be";

    // Send the mail
    mail($email,$subject,$message,$headers);

    // Notify user
    $foutmelding = "Email is verzonden";
    $meldingSoort = "success";
  }else{
    // Notify user
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
</body>
</html>
