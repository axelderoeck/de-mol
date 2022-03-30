<?php

require_once("includes/phpdefault.php");

if (isset($_POST["resetVotes"])){
  $notification = resetVotes($_SESSION["Id"]);
  // Update session aswell
  $_SESSION["Voted"] = 0;
  // Refresh page
  header('location: vote.php');
}

?>

<?php include "includes/header.php"; ?>

      <h1>Opnieuw stemmen</h1>

      <?php if($_SESSION["Voted"] == 1): ?>
        <p>Ben je zeker dat je opnieuw wilt stemmen? <br> Je stem van deze week wordt verwijderd en je krijgt deze punten terug om in te zetten.</p>
        <p class="example"><b>Opgelet!</b> Zorg ervoor dat je dit doet voor de tijd op is! (vanaf het zondag is kan je niet meer stemmen).</p>
        <form method="post">
          <input type="submit" name="resetVotes" id="resetVotes" value="Ja ik ben zeker">
        </form>
      <?php else: ?>
        <p>Je kan je stem niet meer aanpassen.</p>
      <?php endif; ?>

<?php include "includes/footer.php"; ?>
