<?php

require_once("includes/phpdefault.php");

if (isset($_POST["submitGroupInvite"])){
  $notification = sendGroupInvite($_GET["g"], $_POST["friendcode"]);
}

?>

<?php include "includes/header.php"; ?>

    <a href="friends.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
    <h1>Nodig een speler uit</h1>
    <form action="" method="post">
      <label>Friend code</label>
      <input placeholder="Friendcode" type="text" id="friendcode" name="friendcode">
      <input type="submit" name="submitGroupInvite" id="submitGroupInvite" value="Nodig uit">
    </form>
    <p class="example">
      Vul de gebruikersnaam in van de speler die je wil toevoegen in jouw mollenjacht. <br><br>
      Let op: Het is belangrijk dat je de gebruikersnaam invult en niet de voornaam.
    </p>
    <h2>Voor wat dient dit?</h2>
    <p>Als je jouw vrienden en/of familie hier <span>toevoegt</span>, spelen jullie tegen elkaar in de <span>mollenjacht</span>. <br><br>
    Wanneer de <span>mol</span> bekend is zal er een <span>ranglijst</span> te zien zijn die jullie vertelt wie de beste <span>mollenjager</span> is.</p>

<?php include "includes/footer.php"; ?>


