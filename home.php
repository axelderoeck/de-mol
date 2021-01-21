<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

$setVotesQuery = "UPDATE `table_Users`
SET `Voted` = 0";

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
  <script>
    window.addEventListener('load', function() {
      <?php

        if(date('D') == 'Sun') {
          ?>
          stemKnop("uit");
          infoTekst("Op de dag van de aflevering kan je niet stemmen. <br> Kom morgen terug!");
          <?php
          mysqli_query($dbconn, $setVotesQuery);
        } else {
          if ($_SESSION["Voted"] == 0) {
            ?>
            stemKnop("aan");
            infoTekst("Je hebt nog tot en met <span>zaterdag</span> om te stemmen <i style='color: #53adb5;' class='fas fa-clock'></i>");
            <?php
          } elseif ($_SESSION["Voted"] == 1) {
            ?>
            stemKnop("uit");
            infoTekst("Je hebt al <span>gestemd</span> <i style='color: #53adb5;' class='fas fa-check'></i>");
            <?php
          }
        }
        if ($_SESSION["Id"] == 7) {
          ?> stemKnop("aan") <?php
        }

      ?>

    })
  </script>
</head>
<body>
  <?php include "includes/navigation.php"; ?>

<div class="homeScreen" id="main">

  <h1>Dag <?php echo $_SESSION["Naam"]; ?></h1>

  <div class="buttonsDiv">
    <a href="jouwmol.php"><i class="fas fa-fingerprint"></i></a>
    <a href="uitleg.php"><i class="fas fa-question-circle"></i></a>
    <a href="ranglijst.php"><i class="fas fa-medal"></i></a>
  </div>

  <div class="submitDiv">
    <button onclick="location.href = 'stemmen.php';" id="stemKnop" class="formSubmitBtn" type="submit">Stemmen</button>
  </div>

  <p id="infoTekst"></p>
</div>

  <!-- JavaScript -->

  <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script type="text/javascript" src="js/scripts.js"></script>

<?php mysqli_close($dbconn); ?>
</body>
</html>
