<?php

require_once("includes/phpdefault.php");

if (isset($_POST["submitFriendInvite"])){
  // Search for an existing user
  $stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Friendcode = ? OR Email = ?');
  $stmt->execute([ $_POST["friendcode"], $_POST["friendcode"] ]);
  $to_be_invited_user = $stmt->fetch(PDO::FETCH_ASSOC);

  // If the user exists -> send notification to user
  if($to_be_invited_user){
    $stmt = $pdo->prepare('INSERT INTO table_Notifications (NotificationType, InviterId, InvitedId) VALUES (0, ?, ?)');
    $stmt->execute([ $_SESSION["Id"], $to_be_invited_user["Id"] ]);
    $invited_user = $stmt->fetch(PDO::FETCH_ASSOC);
    // Notify user
    $foutmelding = "Vriendschapsverzoek verzonden.";
    $meldingSoort = "success";
  }else{
    // Notify user
    $foutmelding = "Gebruiker niet gevonden.";
    $meldingSoort = "warning";
  }
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
  <script>
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
  <?php include "includes/navigation.php"; ?>

  <div id="informationPopup">
    <!-- Dynamische info -->
  </div>

  <div id="main">
    <div class="respContainer">

    <a href="deelnemers.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
    <h1>Voeg een speler toe</h1>
    <form action="" method="post">
      <label>Friend code of email</label>
      <input placeholder="Friendcode" type="text" id="friendcode" name="friendcode">
      <input type="submit" name="submitFriendInvite" id="submitFriendInvite" value="Voeg toe">
    </form>
    <p class="example">
      Vul de gebruikersnaam in van de speler die je wil toevoegen in jouw mollenjacht. <br><br>
      Let op: Het is belangrijk dat je de gebruikersnaam invult en niet de voornaam.
    </p>
    <h2>Voor wat dient dit?</h2>
    <p>Als je jouw vrienden en/of familie hier <span>toevoegt</span>, spelen jullie tegen elkaar in de <span>mollenjacht</span>. <br><br>
    Wanneer de <span>mol</span> bekend is zal er een <span>ranglijst</span> te zien zijn die jullie vertelt wie de beste <span>mollenjager</span> is.</p>
    </div>
  </div>

  <script type="text/javascript" src="js/scripts.js"></script>
</body>
</html>
