<?php

require_once("includes/phpdefault.php");

if (isset($_POST["submitFriendInvite"])){
  $notification = sendFriendInvite($_SESSION["Id"], $_POST["friendcode"]);
}

?>

<?php include "includes/header.php"; ?>

    <h1>Voeg een vriend toe</h1>
    <form action="" method="post">
      <label>Friendcode/Gebruikersnaam</label>
      <input placeholder="Friendcode" type="text" id="friendcode" name="friendcode">
      <input type="submit" name="submitFriendInvite" id="submitFriendInvite" value="Voeg toe">
    </form>
    <p class="example">
      Vul de friendcode of gebruikersnaam in van de speler die je wil toevoegen als vriend. <br>
    </p>
   
<?php include "includes/footer.php"; ?>