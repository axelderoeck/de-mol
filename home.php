<?php

ob_start();
//require_once("includes/dbconn.inc.php");
require_once("includes/phpdefault.php");
//session_start();

//include "includes/settings.php";

$votetime = str_split($stemmen_uur, 2);

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

$setVotesQuery = "UPDATE `table_Users`
SET `Voted` = 0";

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <script data-name="BMC-Widget" data-cfasync="false" src="https://cdnjs.buymeacoffee.com/1.0.0/widget.prod.min.js" data-id="aksol" data-description="Support me on Buy me a coffee!" data-message="Ben je tevreden met mijn werk en wil je me steunen?" data-color="#5F7FFF" data-position="Right" data-x_margin="18" data-y_margin="18"></script>

  <?php include "includes/headinfo.php"; ?>
  <script>
    window.addEventListener('load', function() {
      <?php
      $begindate = new DateTime($seizoen_start);
      $enddate = new DateTime($seizoen_eind);
      $now = new DateTime();

      if($begindate > $now) {
        ?>
        stemKnop("uit");
        infoTekst("Het <span>seizoen</span> is nog niet begonnen.");
        <?php
      }elseif($enddate < $now) {
        ?>
        stemKnop("uit");
        infoTekst("Het <span>seizoen</span> is voorbij. <br> <button type='submit' onclick='location.href = `ranglijst.php`;'>Bekijk de scores</button>");
        <?php
      }elseif(date('D') == "$stemmen_dag" && date('Hi') < "$stemmen_uur") {
        ?>
        stemKnop("uit");
        infoTekst("Vanaf <?php echo $votetime[0] . ":" . $votetime[1]; ?>u kan je <span>stemmen</span>.");
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
          infoTekst("Je hebt al <span>gestemd</span> <i class='fas fa-check'></i><br>Kom terug na de volgende aflevering!");
          <?php
        }
      }
      ?>

    })
  </script>
</head>
<body>
  <?php include "includes/navigation.php"; ?>

<div class="homeScreen" id="main">
  <div class="respContainer">

  <h1>Dag <?php echo $_SESSION["Naam"]; ?></h1>

  <div class="buttonsDiv">
    <a href="jouwmol.php"><i class="fas fa-fingerprint translucent"></i></a>
    <a href="uitleg.php"><i class="fas fa-question-circle translucent"></i></a>
    <a href="deelnemers.php"><i class="fas fa-users translucent"></i></a>
  </div>

  <div class="submitDiv">
    <button onclick="location.href = 'stemmen.php';" id="stemKnop" class="styledBtn" type="submit">Stemmen</button>
  </div>

  <h2 id="infoTekst"></h2>

  <?php if ($bericht == true) { ?>
    <div class="bericht <?php echo $melding->soort; ?>">
      <p><?php echo $melding->tekst; ?></p>
    </div>
    <br><br>
  <?php } ?>

  <p class="hiddenField">.- -. - .-. --- .--. --- -. -.-- -- .. .</p>

  </div>
</div>

  <!-- JavaScript -->
  <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script type="text/javascript" src="js/scripts.js"></script>

<?php mysqli_close($dbconn); ?>
</body>
</html>
