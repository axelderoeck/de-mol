<?php

require_once("includes/phpdefault.php");

if (isset($_POST["submitGroupInvite"])){
  $notification = sendGroupInvite($_GET["g"], $_POST["friendcode"]);
}

?>

<?php include "includes/header.php"; ?>

    <h1>Nodig een speler uit</h1>
    <form action="" method="post">
      <label>Friendcode/Gebruikersnaam</label>
      <input placeholder="Friendcode" type="text" id="friendcode" name="friendcode">
      <input type="submit" name="submitGroupInvite" id="submitGroupInvite" value="Nodig uit">
    </form>
    <p class="example">
      Vul de friendcode of gebruikersnaam in van de speler die je wil toevoegen in jouw groep.
    </p>
   
<?php include "includes/footer.php"; ?>


