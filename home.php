<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

$code = $_GET["code"];
if ($code==7) {
    $meldingSoort = "succes";
    $foutmelding = "Je bent ingelogd.";
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
      //begin datum van het seizoen
      $begindate = new DateTime("01/19/2021"); // maand-dag-jaar
      //eind datum van het seizoen
      $enddate = new DateTime("05/10/2021"); // maand-dag-jaar
      $now = new DateTime();

      if($begindate > $now) {
        ?>
        stemKnop("uit");
        infoTekst("Het <span>seizoen</span> is nog niet begonnen.");
        <?php
      }elseif($enddate < $now) {
        ?>
        stemKnop("uit");
        infoTekst("Het <span>seizoen</span> is voorbij.");
        <?php
      }elseif(date('D') == 'Sun') {
        ?>
        stemKnop("uit");
        infoTekst("Op de dag van de aflevering kan je niet stemmen. <br> Kom morgen terug!");
        <?php
        mysqli_query($dbconn, $setVotesQuery);
      }else{
        if($_SESSION["Voted"] == 0) {
          ?>
          stemKnop("aan");
          infoTekst("Je hebt nog tot en met <span>zaterdag</span> om te stemmen <i class='fas fa-clock'></i>");
          <?php
        }elseif($_SESSION["Voted"] == 1) {
          ?>
          stemKnop("uit");
          infoTekst("Je hebt al <span>gestemd</span> <i class='fas fa-check'></i>");
          <?php
        }
      }

      if ($code == 7) {
        echo "showNotification('$foutmelding','$meldingSoort');";
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

<div class="homeScreen" id="main">

  <h1>Dag <?php echo $_SESSION["Naam"]; ?></h1>

  <div class="buttonsDiv">
    <a href="jouwmol.php"><i class="fas fa-fingerprint translucent"></i></a>
    <a href="uitleg.php"><i class="fas fa-question-circle translucent"></i></a>
    <a href="ranglijst.php"><i class="fas fa-medal translucent"></i></a>
  </div>

  <div class="submitDiv">
    <button onclick="location.href = 'stemmen.php';" id="stemKnop" class="styledBtn" type="submit">Stemmen</button>
  </div>

  <h2 id="infoTekst"></h2>
</div>

  <!-- JavaScript -->

  <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script type="text/javascript" src="js/scripts.js"></script>

<?php mysqli_close($dbconn); ?>
</body>
</html>
